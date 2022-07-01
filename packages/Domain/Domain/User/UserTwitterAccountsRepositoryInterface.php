<?php

namespace packages\Domain\Domain\User;

interface UserTwitterAccountsRepositoryInterface
{
    /**
     * @param $twitterAuth
     */
    public function save($twitterAuth);

    public function find();

    public function userTwitterAuthLogout();

    public function logout();

    /**
     * @param $screen_name
     */
    public function deleteAccount($screen_name);
}
