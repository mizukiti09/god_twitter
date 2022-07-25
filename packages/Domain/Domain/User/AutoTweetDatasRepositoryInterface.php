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
    public function notExistDataResetAutoFlg($user_twitter_account_id);
}
