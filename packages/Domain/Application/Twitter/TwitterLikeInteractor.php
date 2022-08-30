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
        $arrayText = explode(",", $array_search_text);

        foreach ($arrayText as $text) {
            if (mb_strlen($text) > 140) {
                return;
            }
        }
        $this->l_repository->saveArraySearchText($user_id, $screen_name, $array_search_text, $condition);
    }

    public function startAutoLikeHandle($user_id, $screen_name)
    {
        $this->u_repository->onAutoLikeFlg($user_id, $screen_name);
    }

    public function stopAutoLikeHandle($user_id, $screen_name)
    {
        $this->u_repository->offAutoLikeFlg($user_id, $screen_name);
    }

    public function autoLikeResetHandle($user_id, $screen_name)
    {
        $this->l_repository->reset($user_id, $screen_name);
    }
}
