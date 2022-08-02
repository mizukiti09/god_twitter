<?php

namespace packages\Domain\Application\Twitter;

use App\Facades\Twitter;
use Illuminate\Support\Facades\Log;
use packages\Domain\Domain\User\AutoFollowDatasRepositoryInterface;
use packages\UseCase\Twitter\Follow\TwitterAutoFollowUseCaseInterface;
use packages\Domain\Domain\User\UserTwitterAccountsRepositoryInterface;


class TwitterFollowInteractor implements TwitterAutoFollowUseCaseInterface
{
    private $u_repository;
    private $a_repository;

    public function __construct(
        UserTwitterAccountsRepositoryInterface $u_repository,
        AutoFollowDatasRepositoryInterface $a_repository
    ) {
        $this->u_repository = $u_repository;
        $this->a_repository = $a_repository;
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
