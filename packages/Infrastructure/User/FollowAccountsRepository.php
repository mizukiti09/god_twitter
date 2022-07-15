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
}
