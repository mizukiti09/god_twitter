<?php

namespace packages\Infrastructure\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use packages\Domain\Domain\User\UserTwitterAccountsRepositoryInterface;

class UserTwitterAccountsRepository implements UserTwitterAccountsRepositoryInterface
{
    /**
     * @param $twitterAuth
     */
    public function save($account, $token, $token_secret)
    {
        // ログインしているユーザーが登録しているTwitterアカウントが10より下の場合

        $result = DB::table('user_twitter_accounts')
            ->where('user_id', Auth::id())
            ->count();

        $result2 = DB::table('user_twitter_accounts')
            ->where('screen_name', $account->screen_name)
            ->get()
            ->first();

        if ($result < 10 || ($result <= 10 && !empty($result2))) {
            DB::table('user_twitter_accounts')
                ->updateOrInsert(
                    [
                        'user_id' => Auth::id(),
                        'screen_name' => $account->screen_name,
                    ],
                    [
                        'profile_photo_path'  => $account->profile_image_url,
                        'follow'              => $account->friends_count,
                        'follower'            => $account->followers_count,
                        'auth_flg'            => true,
                        'access_token'        => $token,
                        'access_token_secret' => $token_secret,
                    ]
                );
        } else {
            session()->flash('flash_message', '10まで');
        }
    }

    public function find()
    {
        $accounts = DB::table('user_twitter_accounts')
            ->where('user_id', Auth::id())
            ->select([
                'screen_name',
                'profile_photo_path',
                'follow',
                'follower',
                'follow_count',
                'follower_count',
                'unFollow_count',
                'like_count',
                'like_count_get',
                'tweet_count',
                'updated_at',
                'auth_flg'
            ])
            ->latest('updated_at')
            ->get();

        return $accounts;
    }

    public function onAutoFollowFlg($user_id, $screen_name)
    {
        // ユーザーが自動モードを使用する際には一つのアカウントだけに
        // 絞りたいためまずユーザーが保有しているアカウントの自動フラグを
        // 全て0にしてからその後に、特定のアカウントのみ自動モードフラグを立てる
        DB::table('user_twitter_accounts')
            ->where('user_id', $user_id)
            ->update([
                'auto_follow_flg' => 0
            ]);

        DB::table('user_twitter_accounts')
            ->where('user_id', $user_id)
            ->where('screen_name', $screen_name)
            ->update([
                'auto_follow_flg' => 1
            ]);
    }

    public function getOnAutoFollowAccounts()
    {
        $accounts = DB::table('user_twitter_accounts')
            ->where('auto_follow_flg', 1)
            ->pluck('id');

        return $accounts;
    }

    public function userTwitterAuthLogout()
    {
        DB::table('user_twitter_accounts')
            ->where('user_id', Auth::id())
            ->update([
                'auth_flg' => 0
            ]);
    }

    public function logout()
    {
        DB::table('user_twitter_accounts')
            ->where('user_id', Auth::id())
            ->where('twitter', 1)
            ->update([
                'auth_flg' => false,
            ]);
    }

    public function deleteAccount($screen_name)
    {
        DB::table('user_twitter_accounts')
            ->where('screen_name', $screen_name)
            ->delete();
    }

    public function userFollowCountResetBy24HoursAgo($user_id, $screen_name)
    {
        // follow_countが１の時のUnixタイム
        $result = DB::table('user_twitter_accounts')
            ->where('id', $user_id)
            ->where('screen_name', $screen_name)
            ->get()
            ->first();

        Log::info($result);
        $followCountFirstUnixTime = $result->follow_count_first_unix_time;

        // // 現在のUnixタイム
        $currentTime = time();

        if ($followCountFirstUnixTime) {
            // 86400 は 1日あたりのUnixタイム
            if (($followCountFirstUnixTime + 86400) < $currentTime) {
                DB::table('users')
                    ->where('id', $user_id)
                    ->where('screen_name', $screen_name)
                    ->update([
                        'follow_count' => 0,
                        'follow_count_first_unix_time' => time(),
                    ]);
            }
        } else {
            //  ユーザーが初めてフォローする時は$followCountFirstUnixTime は nullになるので
            // こちらの処理がされる
            DB::table('users')
                ->where('id', $user_id)
                ->where('screen_name', $screen_name)
                ->update([
                    'follow_count_first_unix_time' => time(),
                ]);
        }
    }

    public function followCountUpperCheck($user_id, $screen_name)
    {
        $result = DB::table('user_twitter_accounts')
            ->where('id', $user_id)
            ->where('screen_name', $screen_name)
            ->select('follow_count')
            ->get()
            ->first();
        $followCount = $result->follow_count;

        Log::info($followCount);

        if ($followCount < 1000) {
            return true;
        } else {
            return false;
        }
    }

    public function followCountSave($user_id, $screen_name)
    {
        DB::table('user_twitter_accounts')
            ->where('id', $user_id)
            ->where('screen_name', $screen_name)
            ->increment('follow_count');
    }

    public function getAccessToken($user_id, $screen_name)
    {
        $result =  DB::table('user_twitter_accounts')
            ->where('id', $user_id)
            ->where('screen_name', $screen_name)
            ->select([
                'access_token',
            ])
            ->get()
            ->first();

        return $result->access_token;
    }

    public function getAccessTokenSecret($user_id, $screen_name)
    {
        $result = DB::table('user_twitter_accounts')
            ->where('id', $user_id)
            ->where('screen_name', $screen_name)
            ->select([
                'access_token_secret',
            ])
            ->get()
            ->first();

        return $result->access_token_secret;
    }

    public function getAccount($id)
    {
        // $account = DB::table('user_twitter_accounts')
        //     ->where('id', $id)
        //     ->join('auto_follow_datas', 'user_twitter_accounts.id', '=', 'auto_follow_datas.user_twitter_account_id')
        //     ->get();

        $param = [
            'id' => $id
        ];

        $account = DB::select(
            "SELECT 
                u.id,
                u.user_id,
                u.screen_name,
                u.access_token,
                u.access_token_secret,
                a.cursor_count,
                a.next_cursor,
                a.search_text,
                a.target_account
            FROM
                user_twitter_accounts as u
            LEFT JOIN auto_follow_datas as a
            ON u.id = a.user_twitter_account_id
            WHERE u.id = :id",
            $param
        );

        return $account[0];
    }
}
