<?php

namespace packages\Infrastructure\User;

use Illuminate\Support\Facades\DB;
use packages\Domain\Domain\User\FollowAccountsRepositoryInterface;

class FollowAccountsRepository implements FollowAccountsRepositoryInterface
{
    public function saveFollowAccount($user_twitter_account_id, $screen_name, $twitterId)
    {
        DB::table('follow_accounts')
            ->insert([
                'user_twitter_account_id' => $user_twitter_account_id,
                'screen_name' => $screen_name,
                'twitterId' => $twitterId
            ]);
    }

    public function getEightAccounts($user_twitter_account_id)
    {
        $param = [
            'user_twitter_account_id' => $user_twitter_account_id
        ];

        $account = DB::select(
            "SELECT 
                id,
                screen_name,
                twitterId
            FROM
                follow_accounts
            WHERE user_twitter_account_id = :user_twitter_account_id
            LIMIT 0, 8",
            $param
        );

        return $account;
    }

    public function deleteFollowAccount($id)
    {
        DB::table('follow_accounts')
            ->where('id', $id)
            ->delete();
    }

    public function existsFollowData($user_twitter_account_id)
    {
        $result = DB::table('follow_accounts')
            ->where('user_twitter_account_id', $user_twitter_account_id)
            ->exists();

        return $result;
    }
}
