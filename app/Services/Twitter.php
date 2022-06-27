<?php

namespace App\Services;

use Abraham\TwitterOAuth\TwitterOAuth;
use Laravel\Socialite\Facades\Socialite;

class Twitter
{
    private $client_id;
    private $client_secret;
    private $access_token;
    private $access_token_secret;
    private $connection;

    public function __construct()
    {
        $this->client_id = config('services.twitter.client_id');
        $this->client_secret = config('services.twitter.client_secret');
        $this->access_token = config('services.twitter.access_token');
        $this->access_token_secret = config('services.twitter.access_token_secret');
        $this->connection = new TwitterOAuth($this->client_id, $this->client_secret, $this->access_token, $this->access_token_secret);
    }

    // twitterAPI接続
    public function getConnection()
    {
        return $this->connection;
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
