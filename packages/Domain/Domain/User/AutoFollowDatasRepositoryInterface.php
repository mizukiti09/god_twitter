<?php

namespace packages\Domain\Domain\User;

interface AutoFollowDatasRepositoryInterface
{
    /**
     * @param $user_id, $next_cursor, $search_text
     */
    public function saveArraySearchText($user_id, $screen_name, $array_search_text);

    /**
     * @param $user_twitter_account_id
     */
    public function saveNextCursor($user_twitter_account_id, $next_cursor);
}
