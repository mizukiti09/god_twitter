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
                'auth_flg',
                'auto_follow_flg',
                'auto_unFollow_flg',
                'auto_tweet_flg',
                'auto_like_flg'
            ])
            ->latest('updated_at')
            ->get();

        return $accounts;
    }

    public function cronFindUser($user_id, $screen_name)
    {
        $param = [
            'user_id' => $user_id,
            'screen_name' => $screen_name
        ];

        $account = DB::select(
            "SELECT 
                u.id,
                u.name,
                u.email,
                t.screen_name
            FROM
                user_twitter_accounts as t
            JOIN users as u
            ON u.id = t.user_id
            WHERE t.user_id = :user_id
            AND t.screen_name = :screen_name",
            $param
        );

        return $account[0];
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

    public function offAutoFollowFlg($user_id)
    {
        DB::table('user_twitter_accounts')
            ->where('user_id', $user_id)
            ->update([
                'auto_follow_flg' => 0,
            ]);
    }

    public function onAutoTweetFlg($user_id, $screen_name)
    {
        // ユーザーが自動モードを使用する際には一つのアカウントだけに
        // 絞りたいためまずユーザーが保有しているアカウントの自動フラグを
        // 全て0にしてからその後に、特定のアカウントのみ自動モードフラグを立てる
        DB::table('user_twitter_accounts')
            ->where('user_id', $user_id)
            ->update([
                'auto_tweet_flg' => 0
            ]);

        DB::table('user_twitter_accounts')
            ->where('user_id', $user_id)
            ->where('screen_name', $screen_name)
            ->update([
                'auto_tweet_flg' => 1
            ]);
    }

    public function offAutoTweetFlg($user_id)
    {
        DB::table('user_twitter_accounts')
            ->where('user_id', $user_id)
            ->update([
                'auto_tweet_flg' => 0,
            ]);
    }

    public function getOnAutoFollowAccounts()
    {
        $accounts = DB::table('user_twitter_accounts')
            ->where('auto_follow_flg', 1)
            ->pluck('id');

        return $accounts;
    }

    public function getOnAutoTweetAccounts()
    {
        $accounts = DB::table('user_twitter_accounts')
            ->where('auto_tweet_flg', 1)
            ->pluck('id');

        return $accounts;
    }

    public function userTwitterAuthLogout()
    {
        DB::table('user_twitter_accounts')
            ->where('user_id', Auth::id())
            ->update([
                'auth_flg' => 0,
                'auto_follow_flg' => 0,
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

    public function resetCountBy24HoursAgo($id)
    {
        $result = DB::table('user_twitter_accounts')
            ->where('id', $id)
            ->get()
            ->first();

        $followCountFirstUnixTime = $result->follow_count_first_unix_time;

        // // 現在のUnixタイム
        $currentTime = time();

        if ($followCountFirstUnixTime) {
            // 86400 は 1日あたりのUnixタイム
            if (($followCountFirstUnixTime + 86400) < $currentTime) {
                DB::table('user_twitter_accounts')
                    ->where('id', $id)
                    ->update([
                        'follow_count'                 => 0,
                        'follower_count'               => 0,
                        'unFollow_count'               => 0,
                        'like_count'                   => 0,
                        'like_count_get'               => 0,
                        'tweet_count'                  => 0,
                        'follow_count_first_unix_time' => time(),
                    ]);
            }
        } else {
            //  ユーザーが初めてフォローする時は$followCountFirstUnixTime は nullになるので
            // こちらの処理がされる
            DB::table('user_twitter_accounts')
                ->where('id', $id)
                ->update([
                    'follow_count_first_unix_time' => time(),
                ]);
        }
    }

    public function resetCountBy24HoursAgoParam2($user_id, $screen_name)
    {
        $result = DB::table('user_twitter_accounts')
            ->where('user_id', $user_id)
            ->where('screen_name', $screen_name)
            ->get()
            ->first();

        $followCountFirstUnixTime = $result->follow_count_first_unix_time;

        // // 現在のUnixタイム
        $currentTime = time();

        if ($followCountFirstUnixTime) {
            // 86400 は 1日あたりのUnixタイム
            if (($followCountFirstUnixTime + 86400) < $currentTime) {
                DB::table('user_twitter_accounts')
                    ->where('user_id', $user_id)
                    ->where('screen_name', $screen_name)
                    ->update([
                        'follow_count'                 => 0,
                        'follower_count'               => 0,
                        'unFollow_count'               => 0,
                        'like_count'                   => 0,
                        'like_count_get'               => 0,
                        'tweet_count'                  => 0,
                        'follow_count_first_unix_time' => time(),
                    ]);
            }
        } else {
            //  ユーザーが初めてフォローする時は$followCountFirstUnixTime は nullになるので
            // こちらの処理がされる
            DB::table('user_twitter_accounts')
                ->where('user_id', $user_id)
                ->where('screen_name', $screen_name)
                ->update([
                    'follow_count_first_unix_time' => time(),
                ]);
        }
    }

    public function followCountUpperCheck($id)
    {
        $result = DB::table('user_twitter_accounts')
            ->where('id', $id)
            ->select('follow_count')
            ->get()
            ->first();
        $followCount = $result->follow_count;

        Log::info('現状のDBフォローカウント' . $followCount);

        if ($followCount < 1000) {
            Log::info('現状のDBフォローカウントは1000件未満 OK');
            return true;
        } else {
            Log::info('現状のDBフォローカウントは1000件以上');
            return false;
        }
    }

    public function followCountSave($id)
    {
        DB::table('user_twitter_accounts')
            ->where('id', $id)
            ->increment('follow_count');
    }

    public function tweetCountSave($id)
    {
        DB::table('user_twitter_accounts')
            ->where('id', $id)
            ->increment('tweet_count');
    }

    public function getAccessToken($user_id, $screen_name)
    {
        $result = DB::table('user_twitter_accounts')
            ->where('user_id', $user_id)
            ->where('screen_name', $screen_name)
            ->pluck('access_token')
            ->first();

        return $result;
    }

    public function getAccessTokenSecret($user_id, $screen_name)
    {
        $result = DB::table('user_twitter_accounts')
            ->where('user_id', $user_id)
            ->where('screen_name', $screen_name)
            ->pluck('access_token_secret')
            ->first();

        return $result;
    }

    public function getAccount($id)
    {
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
                a.target_account_id
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
