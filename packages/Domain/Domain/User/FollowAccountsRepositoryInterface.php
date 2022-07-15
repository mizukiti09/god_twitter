<?php

namespace packages\Domain\Domain\User;

interface FollowAccountsRepositoryInterface
{
    /**
     * @param $user_twitter_account_id
     */
    public function saveFollowAccountScreenName($user_twitter_account_id, $screen_name);
}
