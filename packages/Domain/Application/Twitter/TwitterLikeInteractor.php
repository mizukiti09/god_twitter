<?php

namespace packages\Domain\Application\Twitter;

use App\Facades\Twitter;
use Illuminate\Support\Facades\Log;
use packages\Domain\Domain\User\AutoLikeDatasRepositoryInterface;
use packages\UseCase\Twitter\Like\TwitterAutoLikeUseCaseInterface;
use packages\Domain\Domain\User\UserTwitterAccountsRepositoryInterface;


class TwitterLikeInteractor implements TwitterAutoLikeUseCaseInterface
{
    private $u_repository;
    private $l_repository;

    public function __construct(
        UserTwitterAccountsRepositoryInterface $u_repository,
        AutoLikeDatasRepositoryInterface $l_repository
    ) {
        $this->u_repository = $u_repository;
        $this->l_repository = $l_repository;
    }

    public function autoLikeSaveHandle($user_id, $screen_name, $array_search_text, $condition)
    {
        Log::debug('autoLikeSaveHandle Start (VueからのPOST API)');
        Log::info($user_id);
        Log::info($screen_name);
        Log::info($array_search_text);

        $this->l_repository->saveArraySearchText($user_id, $screen_name, $array_search_text, $condition);
    }

    public function startAutoLikeHandle($user_id, $screen_name)
    {
        Log::debug('autoLikeStartHandle Start (VueからのPOST API)');
        Log::info($user_id);
        Log::info($screen_name);
        $this->u_repository->onAutoLikeFlg($user_id, $screen_name);
    }

    public function stopAutoLikeHandle($user_id)
    {
        Log::debug('stopAutoLikeHandle Start (VueからのPOST API)');

        $this->u_repository->offAutoLikeFlg($user_id);
    }
}
