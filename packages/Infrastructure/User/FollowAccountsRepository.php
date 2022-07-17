<?php

namespace packages\Infrastructure\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use packages\Domain\Domain\User\FollowAccountsRepositoryInterface;

class FollowAccountsRepository implements FollowAccountsRepositoryInterface
{
    public function saveFollowAccountScreenName($user_twitter_account_id, $screen_name)
    {
        DB::table('follow_accounts')
            ->insert([
                'user_twitter_account_id' => $user_twitter_account_id,
                'screen_name' => $screen_name,
            ]);
    }

    public function getTenAccounts($user_twitter_account_id)
    {
        $param = [
            'user_twitter_account_id' => $user_twitter_account_id
        ];

        $account = DB::select(
            "SELECT 
                id,
                screen_name
            FROM
                follow_accounts
            WHERE user_twitter_account_id = :user_twitter_account_id
            LIMIT 0, 10",
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
}
