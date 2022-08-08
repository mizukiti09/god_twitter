<?php

namespace packages\Infrastructure\User;

use Illuminate\Support\Facades\DB;
use packages\Domain\Domain\User\UnFollowedAccountsRepositoryInterface;

class UnFollowedAccountsRepository implements UnFollowedAccountsRepositoryInterface
{
    public function saveUnFollowedAccount($user_twitter_account_id, $twitterId)
    {
        DB::table('unFollowed_accounts')
            ->insert([
                'user_twitter_account_id' => $user_twitter_account_id,
                'twitterId' => $twitterId,
                'unFollowed_unixTime' => time(),
            ]);
    }

    public function checkUnFollowedExist($user_twitter_account_id, $twitterId)
    {
        $result = DB::table('unFollowed_accounts')
            ->where('user_twitter_account_id', $user_twitter_account_id)
            ->where('twitterId', $twitterId)
            ->exists();

        return $result;
    }
}
