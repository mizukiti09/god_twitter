<?php

namespace packages\Infrastructure;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use packages\Domain\Domain\TargetAccountsRepositoryInterface;

class TargetAccountsRepository implements TargetAccountsRepositoryInterface
{
    public function getAllTargetAccounts($user_twitter_account_id)
    {
        $accounts = DB::table('target_accounts')
            ->where('user_twitter_account_id', $user_twitter_account_id)
            ->select([
                'screen_name',
                'follower'
            ])
            ->get();

        return $accounts;
    }

    public function getRandomAccount()
    {
        $account = DB::table('target_accounts')
            ->select([
                'screen_name',
            ])
            ->inRandomOrder()
            ->first();
        return $account->screen_name;
    }

    public function saveTargetAccount($user_id, $auth_screen_name, $target_screen_name, $follower)
    {
        $user_twitter_account_id = DB::table('user_twitter_accounts')
            ->where('user_id', $user_id)
            ->where('screen_name', $auth_screen_name)
            ->select([
                'id'
            ])
            ->get()
            ->first();

        $user_twitter_account_id = $user_twitter_account_id->id;

        DB::table('target_accounts')
            ->insert([
                'user_twitter_account_id' => $user_twitter_account_id,
                'screen_name' => $target_screen_name,
                'follower' => $follower
            ]);
    }
}
