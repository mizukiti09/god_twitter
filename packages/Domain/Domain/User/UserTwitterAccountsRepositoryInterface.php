<?php

namespace packages\Domain\Domain\User;

interface UserTwitterAccountsRepositoryInterface
{
    /**
     * @param $twitterAuth, $token, $token_secret
     */
    public function save($twitterAuth, $token, $token_secret);

    public function find();

    /**
     * @param $user_id, $screen_name
     */
    public function cronFindUser($user_id, $screen_name);

    /**
     * @param $user_id, $screen_name
     */
    public function onAutoFollowFlg($user_id, $screen_name);

    public function getOnAutoFollowAccounts();

    public function userTwitterAuthLogout();

    public function logout();

    /**
     * @param $screen_name
     */
    public function deleteAccount($screen_name);

    /**
     * @param $id
     */
    public function userFollowCountResetBy24HoursAgo($id);

    /**
     * @param $id
     */
    public function followCountUpperCheck($id);

    /**
     * @param $user_id, $screen_name
     */
    public function followCountSave($id);

    // ユーザーのアクセストークン取得
    /**
     * @param $user_id, $screen_name
     */
    public function getAccessToken($user_id, $screen_name);

    // ユーザーのアクセストークンシークレット取得
    /**
     * @param $user_id, $screen_name
     */
    public function getAccessTokenSecret($user_id, $screen_name);

    /**
     * @param $id
     */
    public function getAccount($id);

    /**
     * @param $user_id
     */
    public function offAutoFollowFlg($user_id);
}
