<?php

namespace packages\Domain\Application\Twitter;

use DateTime;
use App\Facades\Twitter;
use Illuminate\Support\Facades\Log;
use packages\Domain\Domain\User\AutoTweetDatasRepositoryInterface;
use packages\UseCase\Twitter\Tweet\TwitterAutoTweetUseCaseInterface;
use packages\Domain\Domain\User\UserTwitterAccountsRepositoryInterface;


class TwitterTweetInteractor implements TwitterAutoTweetUseCaseInterface
{
    private $u_repository;
    private $t_repository;

    public function __construct(
        UserTwitterAccountsRepositoryInterface $u_repository,
        AutoTweetDatasRepositoryInterface $t_repository
    ) {
        $this->u_repository = $u_repository;
        $this->t_repository = $t_repository;
    }

    public function autoTweetSaveHandle(
        $user_id,
        $screen_name,
        $tweet_text,
        $date_value
    ) {
        Log::debug('autoTweetSaveHandle Start (VueからのPOST API)');
        Log::info($user_id);
        Log::info($screen_name);
        Log::info($tweet_text);
        Log::info($date_value);

        $halfDayUnixTime = 0;

        if (!empty($date_value)) {
            if (strpos($date_value, '午後') !== false) {
                $halfDayUnixTime = 43200;
            }
            $result = substr($date_value, 0, 16);
            $date = new DateTime($result);
            $unixTime = $date->format('U');
            $unixTime = $unixTime + $halfDayUnixTime;
        } else {
            $unixTime = time();
        }

        $this->t_repository->saveTweetTextAndTime(
            $user_id,
            $screen_name,
            $tweet_text,
            $unixTime
        );
    }

    public function onAutoTweetHandle($user_id, $screen_name)
    {
        Log::info($user_id);
        Log::info($screen_name);
        Log::debug('onAutoTweetHandle Start (VueからのPOST API)');

        $this->u_repository->onAutoTweetFlg($user_id, $screen_name);
    }

    public function stopAutoTweetHandle($user_id)
    {
        Log::debug('stopAutoTweetHandle Start (VueからのPOST API)');

        $this->u_repository->offAutoTweetFlg($user_id);
    }

    public function tweetDeleteHandle($id)
    {
        Log::debug('tweetDeleteHandle Start (VueからのPOST API)');

        $this->t_repository->deleteAutoTweetData($id);
    }

    public function tweetEditHandle($id, $text, $time)
    {
        Log::debug('tweetEditHandle Start (VueからのPOST API)');
        Log::debug('id:' . $id);
        Log::debug('text:' . $text);
        Log::debug('time:' . $time);

        $halfDayUnixTime = 0;
        if (!empty($time)) {
            if (strpos($time, '午後') !== false) {
                $halfDayUnixTime = 43200;
            }
            $result = substr($time, 0, 16);
            $date = new DateTime($result);
            $unixTime = $date->format('U');
            $unixTime = $unixTime + $halfDayUnixTime;
        } else {
            $unixTime = time();
        }

        $this->t_repository->editAutoTweetData($id, $text, $unixTime);
    }
}
