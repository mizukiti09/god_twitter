<?php

namespace packages\Infrastructure\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use packages\Domain\Domain\User\AutoFollowDatasRepositoryInterface;

class AutoFollowDatasRepository implements AutoFollowDatasRepositoryInterface
{
    public function getFollowData($user_twitter_account_id)
    {
        $param = [
            'user_twitter_account_id' => $user_twitter_account_id
        ];

        $data = DB::select(
            "SELECT 
                t.screen_name,
                a.cursor_count,
                a.target_account_followers_count,
                a.next_cursor,
                a.search_text,
                a.follow_condition,
                a.follow_action_flg
            FROM
                auto_follow_datas as a
            LEFT JOIN target_accounts as t
            ON a.target_account_id = t.id
            WHERE a.user_twitter_account_id = :user_twitter_account_id",
            $param
        );

        return $data[0];
    }

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

    public function nextTargetAccountId($user_twitter_account_id)
    {
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

        $target_account_id = DB::table('auto_follow_datas')
            ->where('user_twitter_account_id', $user_twitter_account_id)
            ->pluck('target_account_id')
            ->first();

        $target_array_key = 0;
        foreach ($targets as $key => $target) {
            if ($target->id === $target_account_id) {
                $target_array_key = $key;
            }
        }
        $target_array_key = $target_array_key + 1;

        if (isset($targets[$target_array_key])) {
            DB::table('auto_follow_datas')
                ->where('user_twitter_account_id', $user_twitter_account_id)
                ->update([
                    'target_account_id' => $targets[$target_array_key]->id,
                ]);
        }
    }

    public function checkFollowEnd($user_twitter_account_id)
    {
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

        $array_last_key = array_key_last($targets);

        $target_account_id = DB::table('auto_follow_datas')
            ->where('user_twitter_account_id', $user_twitter_account_id)
            ->pluck('target_account_id')
            ->first();

        if ($targets[$array_last_key]->id === $target_account_id) {
            return true;
        } else {
            return false;
        }
    }

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

        $param = [
            'user_twitter_account_id' => $accountId->id
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

        DB::table('auto_follow_datas')
            ->updateOrInsert(
                [
                    'user_twitter_account_id' => $accountId->id
                ],
                [
                    'search_text' => $array_search_text,
                    'next_cursor' => 0,
                    'follow_condition' => $condition,
                    'target_account_id' => $targets[0]->id
                ]
            );
    }

    public function getCondition($user_twitter_account_id)
    {
        $condition = DB::table('auto_follow_datas')
            ->where('user_twitter_account_id', $user_twitter_account_id)
            ->pluck('follow_condition')
            ->first();

        return $condition;
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

    public function changeTrueFollowActionFlg($user_twitter_account_id)
    {
        DB::table('auto_follow_datas')
            ->where('user_twitter_account_id', $user_twitter_account_id)
            ->update([
                'follow_action_flg' => 1,
            ]);
    }

    public function changeFalseFollowActionFlg($user_twitter_account_id)
    {
        DB::table('auto_follow_datas')
            ->where('user_twitter_account_id', $user_twitter_account_id)
            ->update([
                'follow_action_flg' => 0,
            ]);
    }

    public function getFollowActionFlg($user_twitter_account_id)
    {
        $follow_action_flg = DB::table('auto_follow_datas')
            ->where('user_twitter_account_id', $user_twitter_account_id)
            ->pluck('follow_action_flg')
            ->first();

        return $follow_action_flg;
    }


    public function plusCursorCount($user_twitter_account_id, $plus)
    {
        $cursor_count = DB::table('auto_follow_datas')
            ->where('user_twitter_account_id', $user_twitter_account_id)
            ->pluck('cursor_count')
            ->first();

        DB::table('auto_follow_datas')
            ->where('user_twitter_account_id', $user_twitter_account_id)
            ->update([
                'cursor_count' => $cursor_count + $plus,
            ]);
    }


    public function resetCursorCount($user_twitter_account_id)
    {
        DB::table('auto_follow_datas')
            ->where('user_twitter_account_id', $user_twitter_account_id)
            ->update([
                'cursor_count' => 0,
            ]);
    }

    public function getTarget($user_twitter_account_id)
    {
        $param = [
            'user_twitter_account_id' => $user_twitter_account_id
        ];

        $target = DB::select(
            "SELECT 
                t.screen_name,
                a.cursor_count,
                t.follower
            FROM
                auto_follow_datas as a
            LEFT JOIN target_accounts as t
            ON a.target_account_id = t.id
            WHERE a.user_twitter_account_id = :user_twitter_account_id",
            $param
        );

        if (isset($target[0])) {
            return $target[0];
        } else {
            return array();
        }

        return $target[0];
    }

    public function getSearchTextAndCondition($user_twitter_account_id)
    {
        $data = DB::table('auto_follow_datas')
            ->where('user_twitter_account_id', $user_twitter_account_id)
            ->select([
                'search_text',
                'follow_condition'
            ])
            ->first();

        return $data;
    }

    public function changeFirstTargetAccountId($user_twitter_account_id)
    {
        $param = [
            'user_twitter_account_id' => $user_twitter_account_id
        ];

        $targets = DB::select(
            "SELECT 
                id
            FROM
                target_accounts
            WHERE user_twitter_account_id = :user_twitter_account_id",
            $param
        );

        DB::table('auto_follow_datas')
            ->where('user_twitter_account_id', $user_twitter_account_id)
            ->update([
                'target_account_id' => $targets[0]->id,
            ]);
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

        DB::table('auto_follow_datas')
            ->where('user_twitter_account_id', $accountId->id)
            ->delete();
    }
}
