<?php

namespace packages\UseCase\Twitter\Follow;

interface TwitterAutoFollowUseCaseInterface
{
    /**
     * @param $user_id, $screen_name, $array_search_text, $condition
     */
    public function autoFollowSaveHandle($user_id, $screen_name, $array_search_text, $condition);

    /**
     * @param $user_id, $screen_name
     */
    public function startAutoFollowHandle($user_id, $screen_name);

    /**
     * @param $user_id
     */
    public function stopAutoFollowHandle($user_id, $screen_name);

    /**
     * @param $user_id, $screen_name
     */
    public function startAutoUnFollowHandle($user_id, $screen_name);

    /**
     * @param $user_id
     */
    public function stopAutoUnFollowHandle($user_id, $screen_name);
}
