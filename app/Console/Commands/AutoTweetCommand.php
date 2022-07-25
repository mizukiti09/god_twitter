<?php

namespace App\Console\Commands;

use App\Facades\Twitter;
use App\Mail\AutoFollowMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use packages\Domain\Domain\User\AutoTweetDatasRepositoryInterface;
use packages\Domain\Domain\User\UserTwitterAccountsRepositoryInterface;

class AutoTweetCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:autoTweet';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '自動ツイート';

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
        AutoTweetDatasRepositoryInterface $t_repository
    ) {
        Log::info('=============================');
        Log::info('AutoTweetCommand Start');
        Log::info('=============================');
        $userTwitterAccountIds = $u_repository->getOnAutoTweetAccounts();

        if (!empty($userTwitterAccountIds[0])) {
            Log::info('user_twitter_account 有り');

            // 現在のunixTime
            $currentUnixTime = time();
            Log::info($currentUnixTime);

            foreach ($userTwitterAccountIds as $userTwitterAccountId) {
                if ($t_repository->notExistDataResetAutoFlg($userTwitterAccountId) == false) {
                    Log::info('実行user_twitter_account_id:' . $userTwitterAccountId);
                    $autoTweetDatas = $t_repository->getAutoTweetDatas($userTwitterAccountId);

                    foreach ($autoTweetDatas as $autoTweetData) {
                        $highTime = $autoTweetData->tweetTime + 60;
                        $lowTime = $autoTweetData->tweetTime - 60;
                        Log::info('登録unixTime:' . $autoTweetData->tweetTime);

                        if (($lowTime <= $currentUnixTime) && ($currentUnixTime <= $highTime)) {
                            Log::info('登録unixTimeは現在のunixTimeから見て前後60秒以内です');
                            $account = $u_repository->getAccount($userTwitterAccountId);

                            Log::info('user_id:' . $account->user_id);
                            Log::info('screen_name:' . $account->screen_name);

                            $response = Twitter::getAuthConnection($account->user_id, $account->screen_name)->post("statuses/update", array(
                                "status" => $autoTweetData->tweetText,
                            ));

                            if (isset($response->error) && $response->error != '') {
                                return $response->error;
                            } else {
                                Log::info('ツイート成功');
                                $u_repository->tweetCountSave($userTwitterAccountId);
                                Log::info('ツイートカウントアップ');
                                Log::info('autoTweetDataのID:' . $autoTweetData->id . 'をDBから削除します');
                                $t_repository->deleteAutoTweetData($autoTweetData->id);
                                Log::info('=============================');
                                Log::info('自動ツイートアクション: メール通知');
                                Log::info('=============================');
                                $user = $u_repository->cronFindUser($account->user_id, $account->screen_name);
                                Mail::to($user->email)->send(new AutoFollowMail($user));
                            }
                        }
                        Log::info('登録unixTimeは現在のunixTimeから見て前後60秒以内ではありません');
                    }
                } else {
                    Log::info('userTwitterAccountId:' . $userTwitterAccountId . 'は実行するtweetDataがないのでauto_tweet_flgを0にします');
                }
            }
        }

        Log::info('=============================');
        Log::info('AutoTweetCommand END');
        Log::info('=============================');
    }
}
