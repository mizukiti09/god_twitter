<?php

namespace packages\Infrastructure;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use packages\Domain\Domain\TargetAccountsRepositoryInterface;

class TargetAccountsRepository implements TargetAccountsRepositoryInterface
{
    public function getAllAccounts()
    {
        $accounts = DB::table('target_accounts')
            ->select([
                'screen_name',
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
}
