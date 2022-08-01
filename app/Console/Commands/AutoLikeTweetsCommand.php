<?php

namespace App\Console\Commands;

use App\Facades\Twitter;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use packages\Domain\Domain\User\LikeTweetsRepositoryInterface;
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
        AutoLikeDatasRepositoryInterface $a_repository,
        LikeTweetsRepositoryInterface $l_repository
    ) {
        Log::info('=============================');
        Log::info('AutoLikeAccounts Start');
        Log::info('=============================');

        $userTwitterAccountIds = $u_repository->getOnAutoLikeAccounts();
        if (!empty($userTwitterAccountIds[0])) {
            foreach ($userTwitterAccountIds as $user_twitter_account_id) {
                // フォロワーサーチキーワード
                $array_search_text = $a_repository->getArraySearchText($user_twitter_account_id);
                // フォローコンディション( NOT か OR か AND か null)
                $condition = $a_repository->getCondition($user_twitter_account_id);
                // DBに保存する前の格納用変数
                $lists = array();
                // user_twitter_account
                $account = $u_repository->getAccount($user_twitter_account_id);

                Log::info('アカウントユーザーID:' . $account->user_id);
                Log::info('アカウントスクリーンネーム' . $account->screen_name);

                if ($condition == ('AND' || 'NOT')) {
                    Log::info('コンディションは:' . $condition);
                    $searchKey = '';
                    foreach ($array_search_text as $text) {
                        $searchKey = $searchKey . $text . ' ';
                    }
                } else if ($condition == 'OR') {
                    Log::info('コンディションは:' . $condition);

                    $searchKey = '';
                    foreach ($array_search_text as $text) {
                        $searchKey = $searchKey . $text . ' OR ';
                    }
                }
                Log::info('searchKeyは: ' . $searchKey);

                // 取得オプション
                $options = array('q' => $searchKey, 'lang' => 'ja', 'count' => 20, 'result_type' => 'recent');
                $tweetsData = Twitter::getAuthConnection($account->user_id, $account->screen_name)
                    ->get("search/tweets", $options);

                foreach ($tweetsData->statuses as $data) {
                    Log::info($data->text);

                    $response = Twitter::getAuthConnection($account->user_id, $account->screen_name)
                        ->post("favorites/create", array(
                            'id' => $data->id,
                        ));

                    if (isset($response->error) && $response->error != '') {
                        return $response->error;
                    } else {
                        $u_repository->likeCountSave($user_twitter_account_id);
                        Log::info('いいね成功');
                    }

                    // $l_repository->saveLikeTweet($user_twitter_account_id, $data->id);
                }
                Log::info('tweet 保存完了');
            }
        }
    }
}
