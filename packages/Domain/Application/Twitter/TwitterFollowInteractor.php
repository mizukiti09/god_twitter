<?php

namespace packages\Domain\Application\Twitter;

use App\Facades\Twitter;
use Illuminate\Support\Facades\Log;
// use packages\Domain\Domain\User\AutoFollowDatasRepositoryInterface;
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

    // 1分都度に自動フォロー
    public function autoFollowSaveHandle($user_id, $screen_name, $array_search_text)
    {
        Log::debug('とりあえず');
        Log::info($user_id);
        Log::info($screen_name);
        Log::info($array_search_text);

        $this->u_repository->onAutoFollowFlg($user_id, $screen_name);
        $this->a_repository->saveArraySearchText($user_id, $screen_name, $array_search_text);
        // $this->u_repository->userFollowCountResetBy24HoursAgo($user_id, $screen_name);
        // if ($this->u_repository->followCountUpperCheck($user_id, $screen_name) == true) {


        // $accounts = Twitter::getConnection()->get('followers/ids', array(
        //     // "screen_name" => $repo->getRandomAccount(),
        //     "screen_name" => 'matsu_bouzu',
        //     "count"       => 5000,
        // ));
        // $this->a_repository->saveArraySearchText($user_id, $screen_name, $accounts->next_cursor, $array_search_text);

        // $response = Twitter::getAuthConnection($user_id, $screen_name)->post('friendships/create', array(
        //     "screen_name" => $account->screen_name,
        // ));

        // if (isset($response->error) && $response->error != '') {
        //     return $response->error;
        // } else {
        //     $this->u_repository->followCountSave($user_id, $screen_name);
        //     Log::info('カウントアップ');
        // }
        // } else {
        //     return;
        // }
    }

    public function stopAutoFollowHandle()
    {
    }
}
