<?php

namespace packages\Infrastructure\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use packages\Domain\Domain\User\AutoLikeDatasRepositoryInterface;

class AutoLikeDatasRepository implements AutoLikeDatasRepositoryInterface
{
    public function saveArraySearchText($user_id, $screen_name, $array_search_text, $condition)
    {
        $accountId = DB::table('user_twitter_accounts')
            ->where('user_id', $user_id)
            ->where('screen_name', $screen_name)
            ->select([
                'id'
            ])
            ->get()
            ->first();

        DB::table('auto_like_datas')
            ->updateOrInsert(
                [
                    'user_twitter_account_id' => $accountId->id
                ],
                [
                    'search_text' => $array_search_text,
                    'like_condition' => $condition
                ]
            );
    }

    public function getCondition($user_twitter_account_id)
    {
        $condition = DB::table('auto_like_datas')
            ->where('user_twitter_account_id', $user_twitter_account_id)
            ->pluck('like_condition')
            ->first();

        return $condition;
    }

    public function getArraySearchText($user_twitter_account_id)
    {
        $search_texts = DB::table('auto_like_datas')
            ->where('user_twitter_account_id', $user_twitter_account_id)
            ->pluck('search_text')
            ->first();

        $array_search_text = explode(',', $search_texts);

        return $array_search_text;
    }

    public function getSearchTextAndCondition($user_twitter_account_id)
    {
        $data = DB::table('auto_like_datas')
            ->where('user_twitter_account_id', $user_twitter_account_id)
            ->select([
                'search_text',
                'like_condition'
            ])
            ->first();

        return $data;
    }

    public function reset($user_id, $screen_name)
    {
        $accountId = DB::table('user_twitter_accounts')
            ->where('user_id', $user_id)
            ->where('screen_name', $screen_name)
            ->select([
                'id'
            ])
            ->get()
            ->first();

        DB::table('auto_like_datas')
            ->where('user_twitter_account_id', $accountId->id)
            ->delete();
    }
}
