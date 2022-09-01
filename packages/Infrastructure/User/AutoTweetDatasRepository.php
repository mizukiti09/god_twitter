<?php

namespace packages\Infrastructure\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use packages\Domain\Domain\User\AutoTweetDatasRepositoryInterface;

class AutoTweetDatasRepository implements AutoTweetDatasRepositoryInterface
{
    public function saveTweetTextAndTime(
        $user_id,
        $screen_name,
        $tweet_text,
        $date_value
    ) {
        $user_twitter_account_id = DB::table('user_twitter_accounts')
            ->where('user_id', $user_id)
            ->where('screen_name', $screen_name)
            ->select([
                'id'
            ])
            ->get()
            ->first();

        $user_twitter_account_id = $user_twitter_account_id->id;

        DB::table('auto_tweet_datas')
            ->insert(
                [
                    'user_twitter_account_id' => $user_twitter_account_id,
                    'tweetText' => $tweet_text,
                    'tweetTime' => $date_value
                ]
            );
    }

    public function getAutoTweetDatas($user_twitter_account_id)
    {
        $results = DB::table('auto_tweet_datas')
            ->where('user_twitter_account_id', $user_twitter_account_id)
            ->where('tweeted_flg', 0)
            ->select([
                'id',
                'user_twitter_account_id',
                'tweetText',
                'tweetTime'
            ])
            ->get();

        return $results;
    }

    public function deleteAutoTweetData($id)
    {
        DB::table('auto_tweet_datas')
            ->where('id', $id)
            ->delete();
    }

    public function editAutoTweetData($id, $text, $time)
    {
        DB::table('auto_tweet_datas')
            ->where('id', $id)
            ->update([
                'tweetText' => $text,
                'tweetTime' => $time
            ]);
    }

    public function existDataResetAutoFlg($user_twitter_account_id)
    {
        $results = DB::table('auto_tweet_datas')
            ->where('user_twitter_account_id', $user_twitter_account_id)
            ->where('tweeted_flg', 0)
            ->select([
                'id'
            ])
            ->get()
            ->first();

        if (empty($results)) {
            DB::table('user_twitter_accounts')
                ->where('id', $user_twitter_account_id)
                ->update([
                    'auto_tweet_flg' => 0,
                ]);
        }
    }

    public function getAllUserTweets($user_twitter_account_id)
    {
        $results = DB::table('auto_tweet_datas')
            ->where('user_twitter_account_id', $user_twitter_account_id)
            ->where('tweeted_flg', 0)
            ->select([
                'id',
                'tweetText',
                'tweetTime'
            ])
            ->orderBy('tweetTime', 'asc')
            ->get();


        foreach ($results as $key => $result) {
            $result->tweetTime = date("Y-m-d H:i", $result->tweetTime);
        }

        return $results;
    }

    public function getAllUserTweeted($user_twitter_account_id)
    {
        $results = DB::table('auto_tweet_datas')
            ->where('user_twitter_account_id', $user_twitter_account_id)
            ->where('tweeted_flg', 1)
            ->select([
                'id',
                'tweetText',
                'tweetTime'
            ])
            ->orderBy('tweetTime', 'desc')
            ->get();


        foreach ($results as $key => $result) {
            $result->tweetTime = date("Y-m-d H:i", $result->tweetTime);
        }

        return $results;
    }

    public function updateOnTweetedFlg($id)
    {
        DB::table('auto_tweet_datas')
            ->where('id', $id)
            ->update([
                'tweeted_flg' => 1,
            ]);
    }

    public function resetAutoTweetedData($user_id, $screen_name)
    {
        $user_twitter_account_id = DB::table('user_twitter_accounts')
            ->where('user_id', $user_id)
            ->where('screen_name', $screen_name)
            ->select([
                'id'
            ])
            ->get()
            ->first();

        DB::table('auto_tweet_datas')
            ->where('user_twitter_account_id', $user_twitter_account_id->id)
            ->where('tweeted_flg', 1)
            ->delete();
    }
}
