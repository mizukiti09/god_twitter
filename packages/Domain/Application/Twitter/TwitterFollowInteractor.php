<?php

namespace packages\Domain\Application\Twitter;

use App\Facades\Twitter;
use Illuminate\Support\Facades\Log;
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
        Log::debug('addTargetAccountHandle Start (VueからのPOST API)');
        Log::info($user_id);
        Log::info($auth_screen_name);
        Log::info($target_screen_name);

        $response = Twitter::getAuthConnection($user_id, $auth_screen_name)->get('users/show', array(
            'screen_name' => $target_screen_name
        ));

        if (isset($response->error) && $response->error != '') {
            return $response->error;
        } else {
            $this->ta_repository->saveTargetAccount($user_id, $auth_screen_name, $target_screen_name, $response->followers_count);

            Log::info('入力された targetのscreen_nameがあったので、DBに保存');
        }
    }

    public function deleteTargetAccountHandle($user_id, $auth_screen_name, $target_id, $reset_auto_follow_flg)
    {
        Log::debug('deleteTargetAccountHandle Start (VueからのPOST API)');
        Log::info($user_id);
        Log::info($auth_screen_name);
        Log::info($target_id);
        Log::info($reset_auto_follow_flg);

        if ($reset_auto_follow_flg == true) {
            $this->u_repository->offAutoFollowFlg($user_id);
        }

        $this->ta_repository->deleteTargetAccount($user_id, $auth_screen_name, $target_id);
        Log::info('削除しました。');
    }

    public function autoFollowSaveHandle($user_id, $screen_name, $array_search_text, $condition)
    {
        Log::debug('autoFollowSaveHandle Start (VueからのPOST API)');
        Log::info($user_id);
        Log::info($screen_name);
        Log::info($array_search_text);

        $this->a_repository->saveArraySearchText($user_id, $screen_name, $array_search_text, $condition);
    }

    public function startAutoFollowHandle($user_id, $screen_name)
    {
        Log::debug('autoFollowStartHandle Start (VueからのPOST API)');
        Log::info($user_id);
        Log::info($screen_name);
        $this->u_repository->onAutoFollowFlg($user_id, $screen_name);
    }

    public function stopAutoFollowHandle($user_id)
    {
        Log::debug('stopAutoFollowHandle Start (VueからのPOST API)');

        $this->u_repository->offAutoFollowFlg($user_id);
    }

    public function startAutoUnFollowHandle($user_id, $screen_name)
    {
        Log::debug('autoUnFollowStartHandle Start (VueからのPOST API)');
        Log::info($user_id);
        Log::info($screen_name);
        $this->u_repository->onAutoUnFollowFlg($user_id, $screen_name);
    }

    public function stopAutoUnFollowHandle($user_id)
    {
        Log::debug('stopAutoUnFollowHandle Start (VueからのPOST API)');

        $this->u_repository->offAutoUnFollowFlg($user_id);
    }
}
