<?php

namespace App\Console\Commands;

use App\Facades\Twitter;
use App\Mail\AutoFollowMail;
use Illuminate\Console\Command;
use App\Mail\AutoFollowOverMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use packages\Domain\Domain\User\FollowAccountsRepositoryInterface;
use packages\Domain\Domain\User\AutoFollowDatasRepositoryInterface;
use packages\Domain\Domain\User\FollowedAccountsRepositoryInterface;
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
        FollowAccountsRepositoryInterface $f_repository,
        FollowedAccountsRepositoryInterface $fed_repository,
        AutoFollowDatasRepositoryInterface $a_repository
    ) {
        Log::info('=============================');
        Log::info('AutoFollowCommand Start');
        Log::info('=============================');
        $userTwitterAccountIds = $u_repository->getOnAutoFollowAccounts();
        if (!empty($userTwitterAccountIds[0])) {
            Log::info('user_twitter_account 有り');
            foreach ($userTwitterAccountIds as $user_twitter_account_id) {

                // APIのリクエスト上限にひっかからないよう、いいね再開15分確実に空けてから再開させることのチェック
                // 一番最初の時はチェックはスルーされる
                if ($u_repository->checkRestartFollowUnixTime($user_twitter_account_id) === true) {
                    Log::info('checkRestartFollowUnixTime の チェックOK');

                    Log::info('user_twitter_account_id:' . $user_twitter_account_id);

                    if ($a_repository->getFollowActionFlg($user_twitter_account_id) === 1) {
                        Log::info('follow_action_flg: ' . $a_repository->getFollowActionFlg($user_twitter_account_id) . ':自動フォロー可能');

                        // 24時間経過していたらfollow_countをリセットする
                        $u_repository->resetCountBy24HoursAgo($user_twitter_account_id);
                        Log::info('resetCountBy24HoursAgo CheckOK');

                        $account = $u_repository->getAccount($user_twitter_account_id);
                        Log::info('account_user_id:' . $account->user_id);
                        Log::info('account_screen_name:' . $account->screen_name);

                        // follow_countが1000件未満かチェック
                        if ($u_repository->followCountUpperCheck($user_twitter_account_id) === true) {
                            Log::info('followCountUpperCheck 1000件未満OK');

                            $selectedTenAccounts = $f_repository->getEightAccounts($user_twitter_account_id);

                            if (!empty($selectedTenAccounts)) {
                                // 一回に最高5件フォロー
                                Log::info('selectedFiveAccounts 有り');
                                foreach ($selectedTenAccounts as $key => $selectedAccount) {
                                    $response = Twitter::getAuthConnection($account->user_id, $account->screen_name)->post('friendships/create', array(
                                        "user_id" => $selectedAccount->twitterId,
                                    ));

                                    if (isset($response->error) && $response->error != '') {
                                        return $response->error;
                                    } else {
                                        $f_repository->deleteFollowAccount($selectedAccount->id);
                                        Log::info('DBフォローアカウント削除ID:' . $selectedAccount->id);
                                        $fed_repository->saveFollowedAccount($user_twitter_account_id, $selectedAccount->twitterId);
                                        Log::info('フォロー済みリストとしてアカウントをfollowed_accountsテーブルへ保存:' . $selectedAccount->id);

                                        if ($f_repository->existsFollowData($user_twitter_account_id) === false) {
                                            // cursor_countをリセット
                                            $a_repository->resetCursorCount($user_twitter_account_id);
                                            $a_repository->changeFalseFollowActionFlg($user_twitter_account_id);
                                            Log::info('全てのデータのフォローが終わりました。follow_action_flgを 0 にします。');

                                            if ($a_repository->checkFollowEnd($user_twitter_account_id)) {
                                                $u_repository->offAutoFollowFlg($account->user_id, $account->screen_name);
                                                Log::info('全てのターゲットアカウントを巡回しました。自動フォローモードをOFFにします');

                                                $a_repository->changeFirstTargetAccountId($user_twitter_account_id);
                                                Log::info('ターゲットアカウントを一番はじめに戻します。');
                                            } else {
                                                Log::info('ターゲットアカウントはまだ残っています。');
                                                $a_repository->nextTargetAccountId($user_twitter_account_id);
                                                Log::info('nextTargetAccountIdされました。');
                                            }
                                        }

                                        Log::info('=============================');
                                        Log::info('AutoFollowCommand End');
                                        Log::info('=============================');
                                        if ($key === array_key_last($selectedTenAccounts)) {
                                            Log::info('=============================');
                                            Log::info('自動フォローアクション: メール通知');
                                            $user = $u_repository->cronFindUser($account->user_id, $account->screen_name);
                                            Mail::to($user->email)->send(new AutoFollowMail($user));

                                            $response = Twitter::getAuthConnection($account->user_id, $account->screen_name)->get('users/show', array(
                                                "screen_name" => $account->screen_name,
                                            ));

                                            if (isset($response->error) && $response->error != '') {
                                                return $response->error;
                                            } else {
                                                $u_repository->followOrUnFollowCountSave($user_twitter_account_id, $response->friends_count, $response->followers_count);
                                                Log::info('フォローカウントアップ');
                                            }
                                        }
                                    }
                                }
                            } else { // if (!empty($selectedTenAccounts)) {
                                // cursor_countをリセット
                                $a_repository->resetCursorCount($user_twitter_account_id);
                                $a_repository->changeFalseFollowActionFlg($user_twitter_account_id);
                                Log::info('フォローするデータがないです。follow_action_flgを 0 にします。');

                                if ($a_repository->checkFollowEnd($user_twitter_account_id)) {
                                    $u_repository->offAutoFollowFlg($account->user_id, $account->screen_name);
                                    Log::info('全てのターゲットアカウントを巡回しました。自動フォローモードをOFFにします');

                                    $a_repository->changeFirstTargetAccountId($user_twitter_account_id);
                                    Log::info('ターゲットアカウントを一番はじめに戻します。');
                                } else {
                                    Log::info('ターゲットアカウントはまだ残っています。');
                                    $a_repository->nextTargetAccountId($user_twitter_account_id);
                                    Log::info('nextTargetAccountIdされました。');
                                }
                            }
                        } else {
                            Log::info('フォローカウントが1000件以上ある為、自動フォローはできません。自動フォローモードをOFFにします。');
                            $u_repository->offAutoFollowFlg($account->user_id, $account->screen_name);
                            $user = $u_repository->cronFindUser($account->user_id, $account->screen_name);
                            Mail::to($user->email)->send(new AutoFollowOverMail($user));
                        } // if ($u_repository->followCountUpperCheck($user_twitter_account_id) === true) {
                    } else {
                        Log::info('follow_action_flg: ' . $a_repository->getFollowActionFlg($user_twitter_account_id) . ':自動フォロー不可能');
                    }
                } else {
                    Log::info('checkRestartFollowUnixTime の チェックNG。もうしばらくお待ちください。');
                } // if ($u_repository->checkRestartFollowUnixTime($user_twitter_account_id) === true) {
            } // foreach ($userTwitterAccountIds as $user_twitter_account_id) {
        }
    }
}
