<?php

namespace App\Console\Commands;

use App\Facades\Twitter;
use App\Mail\AutoFollowMail;
use Illuminate\Console\Command;
use App\Mail\AutoFollowOverMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Abraham\TwitterOAuth\TwitterOAuthException;
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
        $userTwitterAccountIds = $u_repository->getOnAutoFollowAccounts();
        if (empty($userTwitterAccountIds[0])) {
            return;
        }
        foreach ($userTwitterAccountIds as $user_twitter_account_id) {

            // APIのリクエスト上限にひっかからないよう、15分確実に空けてから再開させることのチェック
            // 一番最初の時はチェックはスルーされる
            if ($u_repository->checkRestartFollowUnixTime($user_twitter_account_id) === false) {
                continue;
            }

            if ($a_repository->getFollowActionFlg($user_twitter_account_id) !== 1) {
                continue;
            }

            // 24時間経過していたらfollow_countをリセットする
            $u_repository->resetCountBy24HoursAgo($user_twitter_account_id);
            $account = $u_repository->getAccount($user_twitter_account_id);

            // follow_countが1000件未満かチェック
            if ($u_repository->followCountUpperCheck($user_twitter_account_id) === false) {
                $u_repository->offAutoFollowFlg($account->user_id, $account->screen_name);
                $user = $u_repository->cronFindUser($account->user_id, $account->screen_name);
                Mail::to($user->email)->send(new AutoFollowOverMail($user));
                continue;
            }

            $selectedEightAccounts = $f_repository->getEightAccounts($user_twitter_account_id);

            if (empty($selectedEightAccounts)) {
                // cursor_countをリセット
                $a_repository->resetCursorCount($user_twitter_account_id);
                // フォローアクションフラグを0にする
                $a_repository->changeFalseFollowActionFlg($user_twitter_account_id);

                if ($a_repository->checkFollowEnd($user_twitter_account_id)) {
                    // 全てのターゲットアカウントを巡回。自動フォローモードをOFFにする。
                    $u_repository->offAutoFollowFlg($account->user_id, $account->screen_name);
                    // ターゲットアカウントを一番はじめに戻します。
                    $a_repository->changeFirstTargetAccountId($user_twitter_account_id);
                } else {
                    // 次のターゲットアカウントはある状態、ターゲットアカウントIDを次のターゲットアカウントにアップデート。
                    $a_repository->nextTargetAccountId($user_twitter_account_id);
                }
                continue;
            }

            foreach ($selectedEightAccounts as $key => $selectedAccount) {

                try {
                    $response = Twitter::getAuthConnection($account->user_id, $account->screen_name)->post('friendships/create', array(
                        "user_id" => $selectedAccount->twitterId,
                    ));
                    // 'DBフォローアカウント削除
                    $f_repository->deleteFollowAccount($selectedAccount->id);
                    // フォロー済みリストとしてアカウントをfollowed_accountsテーブルへ保存
                    $fed_repository->saveFollowedAccount($user_twitter_account_id, $selectedAccount->twitterId);

                    if ($f_repository->existsFollowData($user_twitter_account_id) === false) {
                        // cursor_countをリセット
                        $a_repository->resetCursorCount($user_twitter_account_id);
                        // 全てのデータのフォローが終わりました。follow_action_flgを 0にする
                        $a_repository->changeFalseFollowActionFlg($user_twitter_account_id);

                        if ($a_repository->checkFollowEnd($user_twitter_account_id)) {
                            // 全てのターゲットアカウントを巡回。自動フォローモードをOFFにする。
                            $u_repository->offAutoFollowFlg($account->user_id, $account->screen_name);
                            // ターゲットアカウントを一番はじめに戻し
                            $a_repository->changeFirstTargetAccountId($user_twitter_account_id);
                        } else {
                            // 次のターゲットアカウントはある状態、ターゲットアカウントIDを次のターゲットアカウントにアップデート。
                            $a_repository->nextTargetAccountId($user_twitter_account_id);
                        }
                    }

                    // このループの最後のフォローが終わったら
                    if ($key === array_key_last($selectedEightAccounts)) {
                        // 自動フォローアクション: メール通知
                        $user = $u_repository->cronFindUser($account->user_id, $account->screen_name);
                        Mail::to($user->email)->send(new AutoFollowMail($user));

                        try {
                            $response = Twitter::getAuthConnection($account->user_id, $account->screen_name)->get('users/show', array(
                                "screen_name" => $account->screen_name,
                            ));
                            // カウントアップ
                            $u_repository->followOrUnFollowCountSave($user_twitter_account_id, $response->friends_count, $response->followers_count);
                        } catch (TwitterOAuthException $e) {
                            Log::info('|======================|');
                            Log::info($e);
                            Log::info('|======================|');
                            continue;
                        }
                    }
                } catch (TwitterOAuthException $e) {
                    Log::info('======================');
                    Log::info($e);
                    Log::info('======================');
                    continue;
                }
            }
        }
    }
}
