<?php

namespace packages\Infrastructure\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use packages\Domain\Domain\User\AutoFollowDatasRepositoryInterface;

class AutoFollowDatasRepository implements AutoFollowDatasRepositoryInterface
{
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

    public function saveNextCursor($user_twitter_account_id, $next_cursor)
    {
        DB::table('auto_follow_datas')
            ->where('user_twitter_account_id', $user_twitter_account_id)
            ->update([
                'next_cursor' => $next_cursor,
            ]);
    }
}
