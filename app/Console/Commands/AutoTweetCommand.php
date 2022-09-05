<?php

namespace App\Console\Commands;

use App\Facades\Twitter;
use App\Mail\AutoTweetMail;
use Illuminate\Console\Command;
use App\Mail\AutoActionStopMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Abraham\TwitterOAuth\TwitterOAuthException;
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
        $userTwitterAccountIds = $u_repository->getOnAutoTweetAccounts();

        if (empty($userTwitterAccountIds[0])) {
            return;
        }

        // 現在のunixTime
        $currentUnixTime = time();

        foreach ($userTwitterAccountIds as $userTwitterAccountId) {
            $t_repository->existDataResetAutoFlg($userTwitterAccountId);
            $autoTweetDatas = $t_repository->getAutoTweetDatas($userTwitterAccountId);

            if (empty($autoTweetDatas)) {
                continue;
            }
            foreach ($autoTweetDatas as $autoTweetData) {
                $highTime = $autoTweetData->tweetTime + 60;
                $lowTime = $autoTweetData->tweetTime - 60;

                if (($lowTime <= $currentUnixTime) && ($currentUnixTime <= $highTime)) {
                    // 登録unixTimeは現在のunixTimeから見て前後60秒以内
                    $account = $u_repository->getAccount($userTwitterAccountId);

                    try {
                        $response = Twitter::getAuthConnection($account->user_id, $account->screen_name)->post("statuses/update", array(
                            "status" => $autoTweetData->tweetText,
                        ));

                        if (isset($response->id)) {
                            // ツイートカウントアップ
                            $u_repository->tweetCountSave($userTwitterAccountId);
                            $t_repository->updateOnTweetedFlg($autoTweetData->id);
                            // 自動ツイートアクション: メール通知
                            $user = $u_repository->cronFindUser($account->user_id, $account->screen_name);
                            Mail::to($user->email)->send(new AutoTweetMail($user));
                        } else {
                            Log::info('リセット');
                            $u_repository->allResetAutoFlg($userTwitterAccountId);
                            $user = $u_repository->cronFindUser($account->user_id, $account->screen_name);
                            Mail::to($user->email)->send(new AutoActionStopMail($user));
                        }
                    } catch (TwitterOAuthException $e) {
                        Log::info('|======================|');
                        Log::info($e);
                        Log::info('|======================|');
                        continue;
                    }
                }
            }
        }
    }
}
