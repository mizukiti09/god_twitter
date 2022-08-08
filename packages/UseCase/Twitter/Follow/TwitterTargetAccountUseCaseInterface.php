<?php

namespace packages\UseCase\Twitter\Follow;

interface TwitterTargetAccountUseCaseInterface
{
    /**
     * @param $user_id, $auth_screen_name, $target_screen_name
     */
    public function addTargetAccountHandle($user_id, $auth_screen_name, $target_screen_name);

    /**
     * @param $user_id, $auth_screen_name, $target_id, $reset_auto_follow_flg
     */
    public function deleteTargetAccountHandle($user_id, $auth_screen_name, $target_id, $reset_auto_follow_flg);
}
