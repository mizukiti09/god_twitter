<?php

namespace packages\UseCase\Twitter\Follow;

interface TwitterAutoFollowUseCaseInterface
{
    /**
     * @param $user_id, $screen_name, $array_search_text
     */
    public function autoFollowSaveHandle($user_id, $screen_name, $array_search_text);

    public function stopAutoFollowHandle();
}
