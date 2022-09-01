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
        if (mb_strlen($tweet_text) > 140) {
            return;
        }
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
        $this->u_repository->onAutoTweetFlg($user_id, $screen_name);
    }

    public function stopAutoTweetHandle($user_id, $screen_name)
    {
        $this->u_repository->offAutoTweetFlg($user_id, $screen_name);
    }

    public function tweetDeleteHandle($id)
    {
        $this->t_repository->deleteAutoTweetData($id);
    }

    public function tweetEditHandle($id, $text, $time)
    {
        if (mb_strlen($text) > 140) {
            return;
        }

        $halfDayUnixTime = 0;
        $result = substr($time, 0, 16);
        $date = new DateTime($result);
        $unixTime = $date->format('U');
        $halfDayUnixTime = 43200;

        if (!empty($time)) {
            if (strpos($time, '午前') !== false) {
                $time = substr($result, -5);
                $hour = strstr($time, ':', true);
                if ($hour == '12') {
                    $unixTime = $unixTime - $halfDayUnixTime;
                }
            }
            if (strpos($time, '午後') !== false) {
                $time = substr($result, -5);
                $hour = strstr($time, ':', true);

                $unixTime = $unixTime + $halfDayUnixTime;
                if ($hour == '12') {
                    $unixTime = $unixTime - $halfDayUnixTime;
                }
            }
        } else {
            $unixTime = time();
        }

        $this->t_repository->editAutoTweetData($id, $text, $unixTime);
    }

    public function tweetHistoryResetHandle($user_id, $screen_name)
    {
        $this->t_repository->resetAutoTweetedData($user_id, $screen_name);
    }
}
