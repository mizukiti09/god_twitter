<?php

namespace App\Console\Commands;

use App\Facades\Twitter;
use App\Mail\AutoUnFollowMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
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
        Log::info('=============================');
        Log::info('AutoUnFollowCommand Start');
        Log::info('=============================');

        $userTwitterAccountIds = $u_repository->getOnAutoUnFollowAccounts();
        if (!empty($userTwitterAccountIds[0])) {
            foreach ($userTwitterAccountIds as $key => $user_twitter_account_id) {
                // user_twitter_account
                $account = $u_repository->getAccount($user_twitter_account_id);
                // 一定時間過ぎたフォローしたTwitterユーザーのIDを取得
                $followed_data = $fed_repository->getFollowed_data($user_twitter_account_id);

                if (!empty($followed_data)) {
                    Log::info('user_id:' . $followed_data->twitterId);

                    $result = Twitter::getAuthConnection($account->user_id, $account->screen_name)
                        ->get("users/show", array(
                            "user_id" => $followed_data->twitterId,
                        ));

                    if (isset($result->status)) {
                        Log::info('ツイート履歴あり');

                        $created_at = $result->status->created_at;
                        $lastTweetUnixTime = strtotime($created_at);
                        $currentUnixTime = time();

                        if ($lastTweetUnixTime + (60 * 60 * 24 * 15) < $currentUnixTime) {

                            Log::info('最後のツイートから15日経っています。非アクティブユーザーです。削除します。');

                            $response = Twitter::getAuthConnection($account->user_id, $account->screen_name)->post("friendships/destroy", array(
                                "user_id" => $followed_data->twitterId,
                            ));

                            if (isset($response->error) && $response->error != '') {
                                return $response->error;
                            } else {
                                $unf_repository->saveUnFollowedAccount($user_twitter_account_id, $followed_data->twitterId);
                                Log::info('アンフォロ-リストへ:' . $followed_data->twitterId);

                                $u_repository->unFollowCountSave($user_twitter_account_id);
                                Log::info('アンフォローカウントアップ');

                                $fed_repository->deleteFollowedAccount($followed_data->id);
                                Log::info('フォローリストから削除:' . $followed_data->id);
                                Log::info('=============================');
                                Log::info('自動アンフォローアクション: メール通知');
                                $user = $u_repository->cronFindUser($account->user_id, $account->screen_name);
                                Mail::to($user->email)->send(new AutoUnFollowMail($user));
                            }
                        } else if ($lastTweetUnixTime + (60 * 60 * 24 * 15) > $currentUnixTime) {
                            Log::info('最後のツイートから15日以内です。アクティブユーザーです。');
                            $result = Twitter::getAuthConnection($account->user_id, $account->screen_name)->get("friendships/lookup", array(
                                "user_id" => $followed_data->twitterId,
                            ));

                            if (!empty($result[0])) {
                                if (in_array('followed_by', $result[0]->connections) == false) {
                                    Log::info('フォローはされてません');
                                    $response = Twitter::getAuthConnection($account->user_id, $account->screen_name)->post("friendships/destroy", array(
                                        "user_id" => $followed_data->twitterId,
                                    ));

                                    if (isset($response->error) && $response->error != '') {
                                        return $response->error;
                                    } else {
                                        $unf_repository->saveUnFollowedAccount($user_twitter_account_id, $followed_data->twitterId);
                                        Log::info('アンフォロ-リストへ:' . $followed_data->twitterId);

                                        $u_repository->unFollowCountSave($user_twitter_account_id);
                                        Log::info('アンフォローカウントアップ');

                                        $fed_repository->deleteFollowedAccount($followed_data->id);
                                        Log::info('フォローリストから削除:' . $followed_data->id);
                                    }

                                    Log::info('=============================');
                                    Log::info('自動アンフォローアクション: メール通知');
                                    $user = $u_repository->cronFindUser($account->user_id, $account->screen_name);
                                    Mail::to($user->email)->send(new AutoUnFollowMail($user));
                                } else {
                                    Log::info('フォローされています。');
                                }
                            }
                        }
                    } else {
                        Log::info('ツイート履歴なし');
                        $result = Twitter::getAuthConnection($account->user_id, $account->screen_name)->get("friendships/lookup", array(
                            "user_id" => $followed_data->twitterId,
                        ));
                        if (!empty($result[0])) {
                            if (in_array('followed_by', $result[0]->connections) == false) {
                                Log::info('フォローはされてません');
                                $response = Twitter::getAuthConnection($account->user_id, $account->screen_name)->post("friendships/destroy", array(
                                    "user_id" => $followed_data->twitterId,
                                ));

                                if (isset($response->error) && $response->error != '') {
                                    return $response->error;
                                } else {
                                    $unf_repository->saveUnFollowedAccount($user_twitter_account_id, $followed_data->twitterId);
                                    Log::info('アンフォロ-リストへ:' . $followed_data->twitterId);

                                    $u_repository->unFollowCountSave($user_twitter_account_id);
                                    Log::info('アンフォローカウントアップ');

                                    $fed_repository->deleteFollowedAccount($followed_data->id);
                                    Log::info('フォローリストから削除:' . $followed_data->id);
                                }

                                Log::info('=============================');
                                Log::info('自動アンフォローアクション: メール通知');
                                $user = $u_repository->cronFindUser($account->user_id, $account->screen_name);
                                Mail::to($user->email)->send(new AutoUnFollowMail($user));
                            } else {
                                Log::info('フォローされています。');
                            }
                        }
                    }
                }
            }
        }

        Log::info('=============================');
        Log::info('AutoUnFollowCommand End');
        Log::info('=============================');
    }
}