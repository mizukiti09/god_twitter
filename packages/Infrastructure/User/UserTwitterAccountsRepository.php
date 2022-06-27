<?php

namespace packages\Infrastructure\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use packages\Domain\Domain\User\UserTwitterAccountsRepositoryInterface;

class UserTwitterAccountsRepository implements UserTwitterAccountsRepositoryInterface
{
    /**
     * @param $twitterAuth
     */
    public function save($account)
    {
        // ログインしているユーザーが登録しているTwitterアカウントが10より下の場合
        if ($this->upperAccountsSave()) {
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
        }
    }

    public function find()
    {
        DB::table('user_twitter_accounts')
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
                'updated_at'
            ])
            ->get();
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

    // 登録しているTwitterアカウントの数を上限10としてチェック
    private function upperAccountsSave()
    {
        $result = DB::table('user_twitter_accounts')
            ->where('user_id', Auth::id())
            ->count();

        if ($result < 10) {
            return true;
        } else {
            return false;
        }
    }
}
