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
                'id',
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

    public function deleteTargetAccount($user_id, $auth_screen_name, $target_id)
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

        $result = DB::table('auto_follow_datas')
            ->where('user_twitter_account_id', $user_twitter_account_id)
            ->exists();

        // auto_follow_datasにユーザーのデータが存在したら

        if ($result) {
            // auto_follow_datasにユーザーのデータが存在します
            $auto_follow_data = DB::table('auto_follow_datas')
                ->where('user_twitter_account_id', $user_twitter_account_id)
                ->get()
                ->first();

            if ($auto_follow_data->target_account_id === $target_id) {
                Log::info('target_account_id と target_id は同じ値です');

                $param = [
                    'user_twitter_account_id' => $user_twitter_account_id
                ];

                $targets = DB::select(
                    "SELECT 
                id,
                screen_name
            FROM
                target_accounts
            WHERE user_twitter_account_id = :user_twitter_account_id",
                    $param
                );

                $target_array_key = 0;
                foreach ($targets as $key => $target) {
                    if ($target->id === $target_id) {
                        $target_array_key = $key;
                    }
                }
                $target_array_key = $target_array_key + 1;

                if (count($targets) >= 2) {
                    if (isset($targets[$target_array_key])) {
                        DB::table('auto_follow_datas')
                            ->where('user_twitter_account_id', $user_twitter_account_id)
                            ->update([
                                'target_account_id' => $targets[$target_array_key]->id,
                                'cursor_count' => 0,
                            ]);
                        // 1
                    } else {
                        DB::table('auto_follow_datas')
                            ->where('user_twitter_account_id', $user_twitter_account_id)
                            ->update([
                                'target_account_id' => $targets[0]->id,
                                'cursor_count' => 0,
                            ]);
                        // 2
                    }
                    DB::table('target_accounts')
                        ->where('id', $target_id)
                        ->delete();
                    // 3
                } else {
                    DB::table('target_accounts')
                        ->where('id', $target_id)
                        ->delete();
                    // 4
                }
            } else {
                Log::info('target_account_id と target_id は同じ値ではありません');
                DB::table('target_accounts')
                    ->where('id', $target_id)
                    ->delete();
                // 5
            }
        } else {
            // auto_follow_datasにユーザーのデータが存在しません'
            DB::table('target_accounts')
                ->where('id', $target_id)
                ->delete();
            // 6
        }
    }
}
