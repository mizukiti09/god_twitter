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

    public function getFollowed_data($user_twitter_account_id)
    {
        // unixTime
        // 1分 60
        // 1時間 60 * 60
        // 1日 60 * 60 * 24
        // １週間 60 * 60 * 24 * 7
        $param = [
            'plusTime' => 60 * 60 * 24 * 7,
            'currentUnixTime' => time(),
            'user_twitter_account_id' => $user_twitter_account_id
        ];

        $data = DB::select(
            "SELECT 
                id,
                twitterId,
                follow_unixTime
            FROM
                followed_accounts 
            WHERE ( follow_unixTime + :plusTime ) <  :currentUnixTime
            AND user_twitter_account_id = :user_twitter_account_id
            LIMIT 5",
            $param
        );


        return $data;
    }

    public function deleteFollowedAccount($id)
    {
        DB::table('followed_accounts')
            ->where('id', $id)
            ->delete();
    }
}
