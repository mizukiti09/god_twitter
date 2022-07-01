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
    public function save($account)
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
                        'profile_photo_path' => $account->profile_image_url,
                        'follow'             => $account->friends_count,
                        'follower'           => $account->followers_count,
                        'auth_flg'           => true
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
}