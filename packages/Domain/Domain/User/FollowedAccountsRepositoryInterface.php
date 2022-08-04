<?php

namespace packages\Domain\Domain\User;

interface FollowedAccountsRepositoryInterface
{
    /**
     * @param $user_twitter_account_id, $twitterId
     */
    public function saveFollowedAccount($user_twitter_account_id, $twitterId);

    /**
     * @param $user_twitter_account_id
     */
    public function getFollowed_data($user_twitter_account_id);

    /**
     * @param $id
     */
    public function deleteFollowedAccount($id);
}
