<?php

namespace App\Console\Commands;

use App\Facades\Twitter;
use App\Mail\AutoLikeMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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

                // APIのリクエスト上限にひっかからないよう、いいね再開15分確実に空けてから再開させることのチェック
                // 一番最初の時はチェックはスルーされる
                if ($u_repository->checkRestartLikeUnixTime($user_twitter_account_id) == true) {
                    Log::info('checkRestartLikeUnixTime の チェックOK');

                    // フォロワーサーチキーワード
                    $array_search_text = $a_repository->getArraySearchText($user_twitter_account_id);
                    // フォローコンディション( NOT か OR か AND か null)
                    $condition = $a_repository->getCondition($user_twitter_account_id);
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
                        Log::info('$array_search_text:' . $array_search_text);

                        Log::info('コンディションは:' . $condition);
                        $searchKey = '';
                        $or = ' OR ';
                        foreach ($array_search_text as $text) {
                            $searchKey = $searchKey . $text . $or;
                        }
                        $searchKey = substr($searchKey, 0, strlen($searchKey) - 4);
                    }
                    Log::info('searchKeyは: ' . $searchKey);

                    // 取得オプション
                    $options = array('q' => $searchKey, 'lang' => 'ja', 'count' => 5, 'result_type' => 'recent');
                    $tweetsData = Twitter::getAuthConnection($account->user_id, $account->screen_name)
                        ->get("search/tweets", $options);

                    foreach ($tweetsData->statuses as $key => $data) {
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

                        if ($key === array_key_last($tweetsData->statuses)) {
                            Log::info('=============================');
                            Log::info('自動いいねアクション: メール通知');
                            $user = $u_repository->cronFindUser($account->user_id, $account->screen_name);
                            Mail::to($user->email)->send(new AutoLikeMail($user));
                        }
                    }
                    Log::info('tweet 保存完了');
                } else {
                    Log::info('checkRestartLikeUnixTime の チェックNG。もうしばらくお待ちください。');
                } // if ($u_repository->checkRestartLikeUnixTime($user_twitter_account_id) == true) {
            } // foreach ($userTwitterAccountIds as $user_twitter_account_id) {
        }
    }
}
