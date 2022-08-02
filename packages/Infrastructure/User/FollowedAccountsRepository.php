<?php

namespace packages\Infrastructure\User;

use Illuminate\Support\Facades\DB;
use packages\Domain\Domain\User\FollowedAccountsRepositoryInterface;

class FollowedAccountsRepository implements FollowedAccountsRepositoryInterface
{
    public function saveFollowedAccount($user_twitter_account_id, $twitterId)
    {
        DB::table('followed_accounts')
            ->insert([
                'user_twitter_account_id' => $user_twitter_account_id,
                'twitterId' => $twitterId,
                'follow_unixTime' => time(),
            ]);
    }

    public function getFollowedIds($user_twitter_account_id)
    {
        $param = [
            'plusTime' => 60 * 10,
            'currentUnixTime' => time(),
            'user_twitter_account_id' => $user_twitter_account_id
        ];

        $followedAccountIds = DB::select(
            "SELECT 
                id,
                twitterId
            FROM
                followed_accounts 
            WHERE ( follow_unixTime + :plusTime ) <  :currentUnixTime
            AND user_twitter_account_id = :user_twitter_account_id",
            $param
        );

        return $followedAccountIds;
    }
}
