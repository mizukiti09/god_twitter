<?php

namespace packages\Domain\Domain\User;

interface AutoFollowDatasRepositoryInterface
{
    /**
     * @param $user_twitter_account_id
     */
    public function getTargetAccountScreenName($user_twitter_account_id);

    /**
     * @param $user_twitter_account_id
     */
    public function incrementTargetAccountId($user_twitter_account_id);

    /**
     * @param $user_id, $next_cursor, $search_text, $condition
     */
    public function saveArraySearchText($user_id, $screen_name, $array_search_text, $condition);

    /**
     * @param $user_twitter_account_id
     */
    public function getCondition($user_twitter_account_id);

    /**
     * @param $user_twitter_account_id
     */
    public function getNextCursor($user_twitter_account_id);

    /**
     * @param $user_twitter_account_id
     */
    public function saveNextCursor($user_twitter_account_id, $next_cursor);

    /**
     * @param $user_twitter_account_id
     */
    public function getArraySearchText($user_twitter_account_id);
}
