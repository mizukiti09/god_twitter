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

    public function autoFollowSaveHandle($user_id, $screen_name, $array_search_text)
    {
        Log::debug('autoFollowSaveHandle Start (VueからのPOST API)');
        Log::info($user_id);
        Log::info($screen_name);
        Log::info($array_search_text);

        $this->u_repository->onAutoFollowFlg($user_id, $screen_name);
        $this->a_repository->saveArraySearchText($user_id, $screen_name, $array_search_text);
    }

    public function stopAutoFollowHandle($user_id)
    {
        Log::debug('stopAutoFollowHandle Start (VueからのPOST API)');

        $this->u_repository->offAutoFollowFlg($user_id);
    }
}
