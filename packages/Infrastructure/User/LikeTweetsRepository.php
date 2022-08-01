<?php

namespace packages\Infrastructure\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use packages\Domain\Domain\User\LikeTweetsRepositoryInterface;

class LikeTweetsRepository implements LikeTweetsRepositoryInterface
{
    public function saveLikeTweet($user_twitter_account_id, $tweetId)
    {
        DB::table('like_tweets')
            ->insert([
                'user_twitter_account_id' => $user_twitter_account_id,
                'tweetId' => $tweetId,
            ]);
    }

    public function getTenTweets($user_twitter_account_id)
    {
        $param = [
            'user_twitter_account_id' => $user_twitter_account_id
        ];

        $tweets = DB::select(
            "SELECT 
                id,
                tweetId
            FROM
                like_tweets
            WHERE user_twitter_account_id = :user_twitter_account_id
            LIMIT 0, 10",
            $param
        );

        return $tweets;
    }

    public function deleteLikeTweet($id)
    {
        DB::table('like_tweets')
            ->where('id', $id)
            ->delete();
    }
}
