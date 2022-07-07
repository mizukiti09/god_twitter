<?php

namespace packages\Domain\Application\Twitter;

use App\Facades\Twitter;
use Illuminate\Support\Facades\Log;
use packages\Domain\Domain\User\UserTwitterAccountsRepositoryInterface;
use packages\UseCase\Twitter\Follow\TwitterAutoFollowUseCaseInterface;


class TwitterFollowInteractor implements TwitterAutoFollowUseCaseInterface
{
    private $u_repository;
    private $t_repository;

    public function __construct(
        UserTwitterAccountsRepositoryInterface $u_repository
    ) {
        $this->u_repository = $u_repository;
    }

    // 1分都度に自動フォロー
    public function autoFollowHandle($user_id, $screen_name, $array_search_text)
    {
        $this->u_repository->userFollowCountResetBy24HoursAgo($user_id, $screen_name);
        if ($this->u_repository->followCountUpperCheck($user_id, $screen_name) == true) {

            $response = Twitter::getAuthConnection($user_id, $screen_name)->post('friendships/create', array(
                "screen_name" => $account->screen_name,
            ));

            if (isset($response->error) && $response->error != '') {
                return $response->error;
            } else {
                $this->u_repository->followCountSave($user_id, $screen_name);
                Log::info('カウントアップ');
            }
        } else {
            return;
        }
    }

    public function stopAutoFollowHandle()
    {
        
    }
}
