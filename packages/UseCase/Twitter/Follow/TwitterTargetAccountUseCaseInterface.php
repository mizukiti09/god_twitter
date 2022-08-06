<?php

namespace packages\UseCase\Twitter\Follow;

interface TwitterTargetAccountUseCaseInterface
{
    /**
     * @param $user_id, $auth_screen_name, $target_screen_name
     */
    public function addTargetAccountHandle($user_id, $auth_screen_name, $target_screen_name);
}
