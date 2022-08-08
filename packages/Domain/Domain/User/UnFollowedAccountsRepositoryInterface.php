<?php

namespace packages\Domain\Domain\User;

interface UnFollowedAccountsRepositoryInterface
{
    /**
     * @param $user_twitter_account_id, $twitterId
     */
    public function saveUnFollowedAccount($user_twitter_account_id, $twitterId);

    /**
     * @param $user_twitter_account_id, $twitterId
     */
    public function checkUnFollowedExist($user_twitter_account_id, $twitterId);
}
