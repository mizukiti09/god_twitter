<?php

namespace packages\Domain\Domain;

interface TargetAccountsRepositoryInterface
{
    /**
     * @param $user_twitter_account_id
     */
    public function getAllTargetAccounts($user_twitter_account_id);

    public function getRandomAccount();

    /**
     * @param $user_id, $auth_screen_name, $target_screen_name
     */
    public function saveTargetAccount($user_id, $auth_screen_name, $target_screen_name, $follower);
}
