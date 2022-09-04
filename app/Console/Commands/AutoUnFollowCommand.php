<?php

namespace App\Console\Commands;

use App\Facades\Twitter;
use App\Mail\AutoUnFollowMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use packages\Domain\Domain\User\FollowedAccountsRepositoryInterface;
use packages\Domain\Domain\User\UnFollowedAccountsRepositoryInterface;
use packages\Domain\Domain\User\UserTwitterAccountsRepositoryInterface;

class AutoUnFollowCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:autoUnFollow';

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
        FollowedAccountsRepositoryInterface $fed_repository,
        UnFollowedAccountsRepositoryInterface $unf_repository
    ) {
        $userTwitterAccountIds = $u_repository->getOnAutoUnFollowAccounts();
        if (empty($userTwitterAccountIds[0])) {
            return;
        }
        foreach ($userTwitterAccountIds as $key => $user_twitter_account_id) {

            // APIのリクエスト上限にひっかからないよう、いいね再開15分確実に空けてから再開させることのチェック
            // 一番最初の時はチェックはスルーされる
            if ($u_repository->checkRestartUnFollowUnixTime($user_twitter_account_id) === false) {
                continue;
            }

            // 24時間経過していたらunFollow_countをリセットする
            $u_repository->resetUnFollowCountBy24HoursAgo($user_twitter_account_id);

            // user_twitter_account
            $account = $u_repository->getAccount($user_twitter_account_id);

            // unFollow_countが1000件未満かチェック
            if ($u_repository->unFollowCountUpperCheck($user_twitter_account_id) === false) {
                // アンフォローカウントが1000件以上ある為、自動アンフォローはできません。自動アンフォローモードをOFFにする。
                $u_repository->offAutoUnFollowFlg($account->user_id, $account->screen_name);
                continue;
            }


            // 一定時間過ぎたフォローしたTwitterユーザーのIDを取得
            $followed_datas = $fed_repository->getFollowed_data($user_twitter_account_id);

            if (empty($followed_datas[0])) {
                continue;
            }

            foreach ($followed_datas as $key => $followed_data) {
                $result = Twitter::getAuthConnection($account->user_id, $account->screen_name)
                    ->get("users/show", array(
                        "user_id" => $followed_data->twitterId,
                    ));

                if (isset($result->status)) {
                    // ツイート履歴あり
                    $created_at = $result->status->created_at;
                    $lastTweetUnixTime = strtotime($created_at);
                    $currentUnixTime = time();

                    if ($lastTweetUnixTime + (60 * 60 * 24 * 15) < $currentUnixTime) {
                        // 最後のツイートから15日経っています。非アクティブユーザーです。アンフォローします。

                        $response = Twitter::getAuthConnection($account->user_id, $account->screen_name)->post("friendships/destroy", array(
                            "user_id" => $followed_data->twitterId,
                        ));

                        if (isset($response->errors[0])) {
                            break;
                        } else {
                            // アンフォロ-リストへ:
                            $unf_repository->saveUnFollowedAccount($user_twitter_account_id, $followed_data->twitterId);
                            // フォローリストから削除
                            $fed_repository->deleteFollowedAccount($followed_data->id);

                            if ($key === array_key_last($followed_datas)) {
                                // 自動アンフォローアクション: メール通知
                                $user = $u_repository->cronFindUser($account->user_id, $account->screen_name);
                                Mail::to($user->email)->send(new AutoUnFollowMail($user));

                                $response = Twitter::getAuthConnection($account->user_id, $account->screen_name)->get('users/show', array(
                                    "screen_name" => $account->screen_name,
                                ));

                                if (isset($response->errors[0])) {
                                    return $response->error;
                                } else {
                                    // アンフォローカウントアップ
                                    $u_repository->followOrUnFollowCountSave($user_twitter_account_id, $response->friends_count, $response->followers_count);
                                }
                            }
                        }
                    } else if ($lastTweetUnixTime + (60 * 60 * 24 * 15) > $currentUnixTime) {
                        // 最後のツイートから15日以内です。アクティブユーザーです。
                        $result = Twitter::getAuthConnection($account->user_id, $account->screen_name)->get("friendships/lookup", array(
                            "user_id" => $followed_data->twitterId,
                        ));

                        if (!empty($result[0])) {
                            if (in_array('followed_by', $result[0]->connections) === false) {
                                // フォローされていない
                                $response = Twitter::getAuthConnection($account->user_id, $account->screen_name)->post("friendships/destroy", array(
                                    "user_id" => $followed_data->twitterId,
                                ));

                                if (isset($response->errors[0])) {
                                    break;
                                } else {
                                    // アンフォロ-リストへ
                                    $unf_repository->saveUnFollowedAccount($user_twitter_account_id, $followed_data->twitterId);
                                    // フォローリストから削除
                                    $fed_repository->deleteFollowedAccount($followed_data->id);
                                }

                                if ($key === array_key_last($followed_datas)) {
                                    // 自動アンフォローアクション: メール通知
                                    $user = $u_repository->cronFindUser($account->user_id, $account->screen_name);
                                    Mail::to($user->email)->send(new AutoUnFollowMail($user));

                                    $response = Twitter::getAuthConnection($account->user_id, $account->screen_name)->get('users/show', array(
                                        "screen_name" => $account->screen_name,
                                    ));

                                    if (isset($response->errors[0])) {
                                        break;
                                    } else {
                                        // アンフォローカウントアップ
                                        $u_repository->followOrUnFollowCountSave($user_twitter_account_id, $response->friends_count, $response->followers_count);
                                    }
                                }
                            }
                        }
                    }
                } else {
                    // ツイート履歴なし
                    $result = Twitter::getAuthConnection($account->user_id, $account->screen_name)->get("friendships/lookup", array(
                        "user_id" => $followed_data->twitterId,
                    ));
                    if (!empty($result[0])) {
                        if (in_array('followed_by', $result[0]->connections) === false) {
                            // フォローはされていない
                            $response = Twitter::getAuthConnection($account->user_id, $account->screen_name)->post("friendships/destroy", array(
                                "user_id" => $followed_data->twitterId,
                            ));

                            if (isset($response->errors[0])) {
                                break;
                            } else {
                                // アンフォロ-リストへ
                                $unf_repository->saveUnFollowedAccount($user_twitter_account_id, $followed_data->twitterId);
                                // フォローリストから削除
                                $fed_repository->deleteFollowedAccount($followed_data->id);
                            }
                            // 自動アンフォローアクション: メール通知
                            $user = $u_repository->cronFindUser($account->user_id, $account->screen_name);
                            Mail::to($user->email)->send(new AutoUnFollowMail($user));

                            $response = Twitter::getAuthConnection($account->user_id, $account->screen_name)->get('users/show', array(
                                "screen_name" => $account->screen_name,
                            ));

                            if (isset($response->errors[0])) {
                                break;
                            } else {
                                // アンフォローカウントアップ
                                $u_repository->followOrUnFollowCountSave($user_twitter_account_id, $response->friends_count, $response->followers_count);
                            }
                        }
                    }
                }
            }
        }
    }
}
