<?php

namespace packages\Infrastructure\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use packages\Domain\Domain\User\AutoFollowDatasRepositoryInterface;

class AutoFollowDatasRepository implements AutoFollowDatasRepositoryInterface
{
    public function getTargetAccountScreenName($user_twitter_account_id)
    {
        $param = [
            'user_twitter_account_id' => $user_twitter_account_id
        ];

        $account_screen_name = DB::select(
            "SELECT 
                t.screen_name
            FROM
                auto_follow_datas as a
            LEFT JOIN target_accounts as t
            ON a.target_account_id = t.id
            WHERE a.user_twitter_account_id = :user_twitter_account_id",
            $param
        );

        return $account_screen_name[0]->screen_name;
    }

    public function saveArraySearchText($user_id, $screen_name, $array_search_text)
    {
        $accountId = DB::table('user_twitter_accounts')
            ->where('user_id', $user_id)
            ->where('screen_name', $screen_name)
            ->select([
                'id'
            ])
            ->get()
            ->first();

        DB::table('auto_follow_datas')
            ->updateOrInsert(
                [
                    'user_twitter_account_id' => $accountId->id
                ],
                [
                    'search_text' => $array_search_text
                ]
            );
    }

    public function getNextCursor($user_twitter_account_id)
    {
        $next_cursor = DB::table('auto_follow_datas')
            ->where('user_twitter_account_id', $user_twitter_account_id)
            ->pluck('next_cursor')
            ->first();

        return $next_cursor;
    }

    public function saveNextCursor($user_twitter_account_id, $next_cursor)
    {
        DB::table('auto_follow_datas')
            ->where('user_twitter_account_id', $user_twitter_account_id)
            ->update([
                'next_cursor' => $next_cursor,
            ]);
    }

    public function getArraySearchText($user_twitter_account_id)
    {
        $search_texts = DB::table('auto_follow_datas')
            ->where('user_twitter_account_id', $user_twitter_account_id)
            ->pluck('search_text')
            ->first();

        $array_search_text = explode(',', $search_texts);

        return $array_search_text;
    }
}
