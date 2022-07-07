<?php

namespace App\Services;

use Abraham\TwitterOAuth\TwitterOAuth;
use Laravel\Socialite\Facades\Socialite;
use packages\Domain\Domain\User\UserTwitterAccountsRepositoryInterface;

class Twitter
{
    private $client_id;
    private $client_secret;
    private $access_token;
    private $access_token_secret;
    private $connection;
    private $userTwitterAccountsRepository;

    public function __construct(UserTwitterAccountsRepositoryInterface $userTwitterAccountsRepository)
    {
        $this->client_id = config('services.twitter.client_id');
        $this->client_secret = config('services.twitter.client_secret');
        $this->access_token = config('services.twitter.access_token');
        $this->access_token_secret = config('services.twitter.access_token_secret');
        $this->connection = new TwitterOAuth($this->client_id, $this->client_secret, $this->access_token, $this->access_token_secret);
        $this->userTwitterAccountsRepository = $userTwitterAccountsRepository;
    }

    // twitterAPI接続
    public function getConnection()
    {
        return $this->connection;
    }

    // twitterAPI接続 (user個別用)
    public function getAuthConnection($user_id, $screen_name)
    {
        return new TwitterOAuth(
            $this->client_id,
            $this->client_secret,
            $this->userTwitterAccountsRepository->getAccessToken($user_id, $screen_name),
            $this->userTwitterAccountsRepository->getAccessTokenSecret($user_id, $screen_name),
        );
    }

    // twitter認証ユーザーの情報を取得
    public function connectUserAuth()
    {
        $twitterUser = Socialite::driver('twitter')->user();
        return $twitterUser;
    }

    public function getUserTwitterAccounts()
    {
    }
}
