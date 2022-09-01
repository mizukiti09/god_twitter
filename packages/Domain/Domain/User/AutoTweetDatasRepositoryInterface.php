<?php

namespace packages\Domain\Domain\User;

interface AutoTweetDatasRepositoryInterface
{
    /**
     * @param $user_id, $screen_name, $tweet_text, $date_value 
     */
    public function saveTweetTextAndTime(
        $user_id,
        $screen_name,
        $tweet_text,
        $date_value
    );

    /**
     * @param $user_twitter_account_id
     */
    public function getAutoTweetDatas($user_twitter_account_id);

    /**
     * @param $id
     */
    public function deleteAutoTweetData($id);

    /**
     * @param $user_twitter_account_id
     */
    public function existDataResetAutoFlg($user_twitter_account_id);

    /**
     * @param $user_twitter_account_id
     */
    public function getAllUserTweets($user_twitter_account_id);

    /**
     * @param $user_twitter_account_id
     */
    public function getAllUserTweeted($user_twitter_account_id);

    /**
     * @param $id, $text, $time
     */
    public function editAutoTweetData($id, $text, $time);

    /**
     * @param $id
     */
    public function updateOnTweetedFlg($id);

    /**
     * @param $user_id, $screen_name
     */
    public function resetAutoTweetedData($user_id, $screen_name);
}
