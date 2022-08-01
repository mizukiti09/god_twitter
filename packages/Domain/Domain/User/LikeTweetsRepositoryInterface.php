<?php

namespace packages\Domain\Domain\User;

interface LikeTweetsRepositoryInterface
{
    /**
     * @param $user_twitter_account_id
     */
    public function saveLikeTweet($user_twitter_account_id, $tweetId);

    /**
     * @param $user_twitter_account_id
     */
    public function getTenTweets($user_twitter_account_id);

    /**
     * @param $id
     */
    public function deleteLikeTweet($id);
}
