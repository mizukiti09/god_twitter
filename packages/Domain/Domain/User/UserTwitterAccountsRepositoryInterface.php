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

    /**
     * @param $user_id
     */
    public function offAutoFollowFlg($user_id, $screen_name);

    /**
     * @param $user_id, $screen_name
     */
    public function onAutoUnFollowFlg($user_id, $screen_name);

    /**
     * @param $user_id
     */
    public function offAutoUnFollowFlg($user_id, $screen_name);

    /**
     * @param $user_id, $screen_name
     */
    public function onAutoLikeFlg($user_id, $screen_name);

    /**
     * @param $user_id
     */
    public function offAutoLikeFlg($user_id, $screen_name);


    /**
     * @param $user_id, $screen_name
     */
    public function onAutoTweetFlg($user_id, $screen_name);

    /**
     * @param $user_id
     */
    public function offAutoTweetFlg($user_id, $screen_name);

    public function getOnAutoFollowAccounts();

    public function getOnAutoUnFollowAccounts();

    public function getOnAutoTweetAccounts();

    public function getOnAutoLikeAccounts();

    public function userTwitterAuthLogout();

    public function logout();

    /**
     * @param $screen_name
     */
    public function deleteAccount($screen_name);

    /**
     * @param $id
     */
    public function resetCountBy24HoursAgo($id);

    /**
     * @param $id
     */
    public function resetUnFollowCountBy24HoursAgo($id);

    /**
     * @param $user_id, $screen_name
     */
    public function resetCountBy24HoursAgoParam2($user_id, $screen_name);

    /**
     * @param $id
     */
    public function followCountUpperCheck($id);

    /**
     * @param $id
     */
    public function unFollowCountUpperCheck($id);

    /**
     * @param $id
     */
    public function followCountSave($id, $follow, $follower);

    /**
     * @param $id
     */
    public function unFollowCountSave($id, $follow, $follower);


    /**
     * @param $id
     */
    public function followOrUnFollowCountSave($id, $follow, $follower);

    /**
     * @param $id
     */
    public function tweetCountSave($id);

    /**
     * @param $id
     */
    public function likeCountSave($id);

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
     * @param $id
     */
    public function followedByServiceUserFollow5000Over($user_id, $screen_name);

    /**
     * @param $id
     */
    public function checkRestartFollowUnixTime($id);

    /**
     * @param $id
     */
    public function checkRestartUnFollowUnixTime($id);

    /**
     * @param $id
     */
    public function checkRestartLikeUnixTime($id);

    /**
     * @param $id
     */
    public function allResetAutoFlg($id);
}
