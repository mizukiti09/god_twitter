<?php

namespace App\Console\Commands;

use App\Facades\Twitter;
use App\Mail\AutoLikeMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Abraham\TwitterOAuth\TwitterOAuthException;
use packages\Domain\Domain\User\AutoLikeDatasRepositoryInterface;
use packages\Domain\Domain\User\UserTwitterAccountsRepositoryInterface;

class AutoLikeTweetsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:autoLikeTweets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '自動いいねに関するツイートを取得する';

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
        AutoLikeDatasRepositoryInterface $a_repository
    ) {
        $userTwitterAccountIds = $u_repository->getOnAutoLikeAccounts();
        if (empty($userTwitterAccountIds[0])) {
            return;
        }
        foreach ($userTwitterAccountIds as $user_twitter_account_id) {

            // APIのリクエスト上限にひっかからないよう、15分確実に空けてから再開させることのチェック
            // 一番最初の時はチェックはスルーされる
            if ($u_repository->checkRestartLikeUnixTime($user_twitter_account_id) === false) {
                continue;
            }

            // フォロワーサーチキーワード
            $array_search_text = $a_repository->getArraySearchText($user_twitter_account_id);
            // フォローコンディション( NOT か OR か AND か null)
            $condition = $a_repository->getCondition($user_twitter_account_id);
            // user_twitter_account
            $account = $u_repository->getAccount($user_twitter_account_id);

            if ($condition === ('AND' || 'NOT')) {
                $searchKey = '';
                foreach ($array_search_text as $text) {
                    $searchKey = $searchKey . $text . ' ';
                }
            } else if ($condition === 'OR') {
                $searchKey = '';
                $or = ' OR ';
                foreach ($array_search_text as $text) {
                    $searchKey = $searchKey . $text . $or;
                }
                $searchKey = substr($searchKey, 0, strlen($searchKey) - 4);
            }

            // 取得オプション
            $options = array('q' => $searchKey, 'lang' => 'ja', 'count' => 5, 'result_type' => 'recent');

            try {
                $tweetsData = Twitter::getAuthConnection($account->user_id, $account->screen_name)
                    ->get("search/tweets", $options);
            } catch (TwitterOAuthException $e) {
                Log::info('|======================|');
                Log::info($e);
                Log::info('|======================|');
                continue;
            }

            foreach ($tweetsData->statuses as $key => $data) {
                try {
                    $response = Twitter::getAuthConnection($account->user_id, $account->screen_name)
                        ->post("favorites/create", array(
                            'id' => $data->id,
                        ));

                    // カウントアップ
                    $u_repository->likeCountSave($user_twitter_account_id);
                    if ($key === array_key_last($tweetsData->statuses)) {
                        // 自動いいねアクション: メール通知
                        $user = $u_repository->cronFindUser($account->user_id, $account->screen_name);
                        Mail::to($user->email)->send(new AutoLikeMail($user));
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
