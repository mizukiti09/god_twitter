<?php

namespace packages\UseCase\Twitter\Like;

interface TwitterAutoLikeUseCaseInterface
{
    /**
     * @param $user_id, $screen_name, $array_search_text, $condition
     */
    public function autoLikeSaveHandle($user_id, $screen_name, $array_search_text, $condition);

    /**
     * @param $user_id, $screen_name
     */
    public function startAutoLikeHandle($user_id, $screen_name);

    /**
     * @param $user_id
     */
    public function stopAutoLikeHandle($user_id, $screen_name);

    /**
     * @param $user_id, $screen_name
     */
    public function autoLikeResetHandle($user_id, $screen_name);
}
