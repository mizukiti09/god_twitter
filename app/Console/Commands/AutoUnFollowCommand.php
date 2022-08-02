<?php

namespace App\Console\Commands;

use App\Facades\Twitter;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use packages\Domain\Domain\User\FollowedAccountsRepositoryInterface;
use packages\Domain\Domain\User\UserTwitterAccountsRepositoryInterface;

class AutoUnFollowCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:unFollow';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '自動アンフォロー';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(
        UserTwitterAccountsRepositoryInterface $u_repository,
        FollowedAccountsRepositoryInterface $fed_repository
    ) {
        Log::info('=============================');
        Log::info('AutoUnFollowCommand Start');
        Log::info('=============================');

        $userTwitterAccountIds = $u_repository->getOnAutoUnFollowAccounts();
        if (!empty($userTwitterAccountIds[0])) {
            foreach ($userTwitterAccountIds as $user_twitter_account_id) {
                // user_twitter_account
                $account = $u_repository->getAccount($user_twitter_account_id);
                // 一定時間過ぎたフォローしたTwitterユーザーのIDを取得
                $followedAccountIds = $fed_repository->getFollowedIds($user_twitter_account_id);

                $arrayTwitterUserList = array();
                foreach ($followedAccountIds as $followedAccountId) {
                    array_push($arrayTwitterUserList, $followedAccountId);
                }
            }
        }
    }
}
