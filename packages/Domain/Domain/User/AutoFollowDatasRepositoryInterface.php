<?php

namespace packages\Domain\Domain\User;

interface AutoFollowDatasRepositoryInterface
{
    /**
     * @param $user_twitter_account_id
     */
    public function getFollowData($user_twitter_account_id);
    /**
     * @param $user_twitter_account_id
     */
    public function getTargetAccountScreenName($user_twitter_account_id);

    /**
     * @param $user_twitter_account_id
     */
    public function nextTargetAccountId($user_twitter_account_id);

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

    /**
     * @param $user_twitter_account_id
     */
    public function changeTrueFollowActionFlg($user_twitter_account_id);

    /**
     * @param $user_twitter_account_id
     */
    public function changeFalseFollowActionFlg($user_twitter_account_id);

    /**
     * @param $user_twitter_account_id
     */
    public function getFollowActionFlg($user_twitter_account_id);

    /**
     * @param $user_twitter_account_id
     */
    public function checkFollowEnd($user_twitter_account_id);

    /**
     * @param $user_twitter_account_id, $plus
     */
    public function plusCursorCount($user_twitter_account_id, $plus);

    /**
     * @param $user_twitter_account_id
     */
    public function resetCursorCount($user_twitter_account_id);

    /**
     * @param $user_twitter_account_id
     */
    public function getTarget($user_twitter_account_id);

    /**
     * @param $user_twitter_account_id
     */
    public function getSearchTextAndCondition($user_twitter_account_id);

    /**
     * @param $user_twitter_account_id
     */
    public function changeFirstTargetAccountId($user_twitter_account_id);

    /**
     * @param $user_id, $screen_name
     */
    public function reset($user_id, $screen_name);
}
