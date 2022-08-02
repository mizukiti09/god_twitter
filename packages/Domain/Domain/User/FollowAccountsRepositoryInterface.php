<?php

namespace packages\Domain\Domain\User;

interface FollowAccountsRepositoryInterface
{
    /**
     * @param $user_twitter_account_id, $screen_name, $twitterId
     */
    public function saveFollowAccount($user_twitter_account_id, $screen_name, $twitterId);

    /**
     * @param $user_twitter_account_id
     */
    public function getTenAccounts($user_twitter_account_id);

    /**
     * @param $id
     */
    public function deleteFollowAccount($id);
}
