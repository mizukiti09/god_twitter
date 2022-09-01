<?php

namespace packages\UseCase\Twitter\Tweet;

interface TwitterAutoTweetUseCaseInterface
{
    /**
     * @param $user_id, $screen_name, $tweet_text, $date_value
     */
    public function autoTweetSaveHandle(
        $user_id,
        $screen_name,
        $tweet_text,
        $date_value
    );

    /**
     * @param $user_id, $screen_name
     */
    public function onAutoTweetHandle($user_id, $screen_name);

    /**
     * @param $user_id
     */
    public function stopAutoTweetHandle($user_id, $screen_name);

    /**
     * @param $id
     */
    public function tweetDeleteHandle($id);

    /**
     * @param $id, $text, $time
     */
    public function tweetEditHandle(
        $id,
        $text,
        $time
    );

    /**
     * @param $user_id, $screen_name
     */
    public function tweetHistoryResetHandle($user_id, $screen_name);
}
