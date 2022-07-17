<?php

namespace packages\Domain\Domain\User;

interface FollowAccountsRepositoryInterface
{
    /**
     * @param $user_twitter_account_id
     */
    public function saveFollowAccountScreenName($user_twitter_account_id, $screen_name);

    /**
     * @param $user_twitter_account_id
     */
    public function getTenAccounts($user_twitter_account_id);

    /**
     * @param $id
     */
    public function deleteFollowAccount($id);
}
