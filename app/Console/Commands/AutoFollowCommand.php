<?php

namespace App\Console\Commands;

use App\Facades\Twitter;
use App\Mail\AutoFollowMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use packages\Domain\Domain\User\FollowAccountsRepositoryInterface;
use packages\Domain\Domain\User\UserTwitterAccountsRepositoryInterface;

class AutoFollowCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:autoFollow';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'autoFollowAccountsで抽出したアカウントを自動フォロー';

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
        FollowAccountsRepositoryInterface $f_repository
    ) {
        Log::info('=============================');
        Log::info('AutoFollowCommand Start');
        Log::info('=============================');
        $userTwitterAccountIds = $u_repository->getOnAutoFollowAccounts();
        if (!empty($userTwitterAccountIds[0])) {
            Log::info('user_twitter_account 有り');
            foreach ($userTwitterAccountIds as $user_twitter_account_id) {
                Log::info('user_twitter_account_id:' . $user_twitter_account_id);

                $u_repository->resetCountBy24HoursAgo($user_twitter_account_id);
                Log::info('resetCountBy24HoursAgo CheckOK');
                if ($u_repository->followCountUpperCheck($user_twitter_account_id) == true) {
                    Log::info('followCountUpperCheck 1000件未満OK');
                    $account = $u_repository->getAccount($user_twitter_account_id);
                    Log::info('account_user_id:' . $account->user_id);
                    Log::info('account_screen_name:' . $account->screen_name);

                    $selectedTenAccounts = $f_repository->getTenAccounts($user_twitter_account_id);

                    if (!empty($selectedTenAccounts)) {
                        Log::info('selectedTenAccounts 有り');
                        foreach ($selectedTenAccounts as $key => $selectedAccount) {
                            $response = Twitter::getAuthConnection($account->user_id, $account->screen_name)->post('friendships/create', array(
                                "screen_name" => $selectedAccount->screen_name,
                            ));

                            if (isset($response->error) && $response->error != '') {
                                return $response->error;
                            } else {
                                $u_repository->followCountSave($user_twitter_account_id);
                                Log::info('フォローカウントアップ');
                                $f_repository->deleteFollowAccount($selectedAccount->id);
                                Log::info('DBフォローアカウント削除ID:' . $selectedAccount->id);

                                Log::info('=============================');
                                Log::info('AutoFollowCommand End');
                                Log::info('=============================');
                                if ($key === array_key_last($selectedTenAccounts)) {
                                    Log::info('=============================');
                                    Log::info('自動フォローアクション: メール通知');
                                    $user = $u_repository->cronFindUser($account->user_id, $account->screen_name);
                                    Mail::to($user->email)->send(new AutoFollowMail($user));
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
