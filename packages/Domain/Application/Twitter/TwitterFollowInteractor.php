<?php

namespace packages\Domain\Application\Twitter;

use App\Facades\Twitter;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Exceptions\HttpResponseException;
use packages\Domain\Domain\TargetAccountsRepositoryInterface;
use packages\Domain\Domain\User\AutoFollowDatasRepositoryInterface;
use packages\UseCase\Twitter\Follow\TwitterAutoFollowUseCaseInterface;
use packages\Domain\Domain\User\UserTwitterAccountsRepositoryInterface;
use packages\UseCase\Twitter\Follow\TwitterTargetAccountUseCaseInterface;


class TwitterFollowInteractor implements TwitterAutoFollowUseCaseInterface, TwitterTargetAccountUseCaseInterface
{
    private $u_repository;
    private $a_repository;
    private $ta_repository;

    public function __construct(
        UserTwitterAccountsRepositoryInterface $u_repository,
        AutoFollowDatasRepositoryInterface $a_repository,
        TargetAccountsRepositoryInterface $ta_repository
    ) {
        $this->u_repository  = $u_repository;
        $this->a_repository  = $a_repository;
        $this->ta_repository = $ta_repository;
    }

    public function addTargetAccountHandle($user_id, $auth_screen_name, $target_screen_name)
    {
        $response = Twitter::getAuthConnection($user_id, $auth_screen_name)->get('users/show', array(
            'screen_name' => $target_screen_name
        ));



        if (isset($response->errors[0])) {
            $error = $response->errors[0]->message;

            throw new HttpResponseException(response(
                $error,
                404
            ));
        } else {
            $this->ta_repository->saveTargetAccount($user_id, $auth_screen_name, $target_screen_name, $response->followers_count);
        }
    }

    public function deleteTargetAccountHandle($user_id, $auth_screen_name, $target_id, $reset_auto_follow_flg)
    {
        if ($reset_auto_follow_flg === 'true') {
            $this->u_repository->offAutoFollowFlg($user_id, $auth_screen_name);
        }

        $this->ta_repository->deleteTargetAccount($user_id, $auth_screen_name, $target_id);
    }

    public function autoFollowSaveHandle($user_id, $screen_name, $array_search_text, $condition)
    {
        $arrayText = explode(",", $array_search_text);

        foreach ($arrayText as $text) {
            if (mb_strlen($text) > 140) {
                return;
            }
        }

        $this->a_repository->saveArraySearchText($user_id, $screen_name, $array_search_text, $condition);
    }

    public function autoFollowResetHandle($user_id, $screen_name)
    {
        $this->a_repository->reset($user_id, $screen_name);
    }

    public function startAutoFollowHandle($user_id, $screen_name)
    {
        $this->u_repository->onAutoFollowFlg($user_id, $screen_name);
    }

    public function stopAutoFollowHandle($user_id, $screen_name)
    {
        $this->u_repository->offAutoFollowFlg($user_id, $screen_name);
    }

    public function startAutoUnFollowHandle($user_id, $screen_name)
    {
        $this->u_repository->onAutoUnFollowFlg($user_id, $screen_name);
    }

    public function stopAutoUnFollowHandle($user_id, $screen_name)
    {
        $this->u_repository->offAutoUnFollowFlg($user_id, $screen_name);
    }
}
