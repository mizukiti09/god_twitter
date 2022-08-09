<?php

namespace App\Console\Commands;

use App\Facades\Twitter;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use packages\Domain\Domain\User\FollowAccountsRepositoryInterface;
use packages\Domain\Domain\User\AutoFollowDatasRepositoryInterface;
use packages\Domain\Domain\User\UnFollowedAccountsRepositoryInterface;
use packages\Domain\Domain\User\UserTwitterAccountsRepositoryInterface;

class AutoFollowAccountsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:autoFollowAccounts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '自動フォロー用のフォローアカウントを自動保存';

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
        AutoFollowDatasRepositoryInterface $a_repository,
        FollowAccountsRepositoryInterface $f_repository,
        UnFollowedAccountsRepositoryInterface $unf_repository
    ) {
        Log::info('=============================');
        Log::info('AutoFollowAccounts Start');
        Log::info('=============================');

        $userTwitterAccountIds = $u_repository->getOnAutoFollowAccounts();
        if (!empty($userTwitterAccountIds[0])) {
            foreach ($userTwitterAccountIds as $user_twitter_account_id) {

                // APIのリクエスト上限にひっかからないよう、いいね再開15分確実に空けてから再開させることのチェック
                // 一番最初の時はチェックはスルーされる
                if ($u_repository->checkRestartFollowUnixTime($user_twitter_account_id) == true) {
                    Log::info('checkRestartFollowUnixTime の チェックOK');

                    // フォローデータ
                    $followData = $a_repository->getFollowData($user_twitter_account_id);
                    // フォローアカウント保存フラグ
                    $follow_action_flg = $followData->follow_action_flg;

                    if ($follow_action_flg == 0) {
                        Log::info('フォローアカウント保存フラグ0');
                        // ターゲットアカウントのスクリーンネーム
                        $target_account_screen_name = $followData->screen_name;
                        // フォロワーサーチキーワード
                        $array_search_text = explode(',', $followData->search_text);
                        // フォローコンディション( NOT か OR か AND か null)
                        $condition = $followData->follow_condition;
                        // ネクストカーソル
                        $next_cursor = $followData->next_cursor;
                        // DBに保存する前の格納用変数
                        $listNames = array();
                        $listIds = array();

                        // user_twitter_account
                        $account = $u_repository->getAccount($user_twitter_account_id);

                        // if (!empty($array_search_text[0])) {
                        $listGetCount = 200;
                        // } else {
                        //     $listGetCount = 10;
                        // }

                        Log::info($account->user_id);
                        Log::info($account->screen_name);



                        if (empty($next_cursor)) {
                            $followers_list = Twitter::getAuthConnection($account->user_id, $account->screen_name)
                                ->get('followers/list', array(
                                    "screen_name" => $target_account_screen_name,
                                    "count"       => $listGetCount,
                                ));
                        } else {
                            $followers_list = Twitter::getAuthConnection($account->user_id, $account->screen_name)
                                ->get('followers/list', array(
                                    "screen_name" => $target_account_screen_name,
                                    "count"       => $listGetCount,
                                    "cursor" => $next_cursor,
                                ));
                        }

                        // 次のフォロワーリストがあったらnext_cursorをDBに保存
                        if ($followers_list->next_cursor) {
                            $a_repository->saveNextCursor($user_twitter_account_id, $followers_list->next_cursor);
                        } else {
                            $a_repository->changeTrueFollowActionFlg($user_twitter_account_id);
                            Log::info('フォローアカウント保存フラグを1に更新します。');
                        }


                        foreach ($followers_list->users as $user) {

                            if (preg_match("/[ぁ-ん]+|[ァ-ヴー]+|[一-龠]/u", $user->description)) {
                                // 日本語文字列が含まれている
                                Log::info('日本人のアカウントです');

                                if ($array_search_text !== null) {
                                    Log::info('プロフィールの中にフォロワーサーチキーワードが入っています。');
                                    // プロフィールの中にフォロワーサーチキーワードが入っていたら
                                    // $check_countをインクリメントする。
                                    // $array_search_textの値の数とインクリメントされた$check_countが
                                    // 同じ数になればデータベースに保存する。


                                    if ($unf_repository->checkUnFollowedExist($user_twitter_account_id, $user->id) == false) {
                                        // ユーザー都度のアンフォローリストに該当のtwitterIdが存在しなければフォローリストへ追加する

                                        $check_count = 0;
                                        if ($user->following == false) {
                                            foreach ($array_search_text as $text) {
                                                if ($condition !== 'OR') {
                                                    if (strpos($user->description, $text) !== false) {
                                                        $check_count += 1;
                                                    }
                                                    if (count($array_search_text) == $check_count) {
                                                        array_push($listNames, $user->screen_name);
                                                        array_push($listIds, $user->id);
                                                    }
                                                } else {
                                                    Log::info('ORです');
                                                    if (strpos($user->description, $text) !== false) {
                                                        array_push($listNames, $user->screen_name);
                                                        array_push($listIds, $user->id);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    array_push($listNames, $user->screen_name);
                                    array_push($listIds, $user->id);
                                }
                            } else {
                                // 日本語文字列が含まれていない
                                Log::info('日本人ではないアカウントです');
                                Log::info($user->screen_name);
                                Log::info($user->description);
                            }
                        }
                        // どれだけ巡回したかを数字にしてcount
                        $a_repository->plusCursorCount($user_twitter_account_id, $listGetCount);

                        foreach ($listNames as $key => $screen_name) {
                            $f_repository->saveFollowAccount($user_twitter_account_id, $screen_name, $listIds[$key]);
                        }
                    } else {
                        Log::info('フォローアカウント保存フラグ1。自動フォローを実施します。');
                    } // if ($follow_action_flg == 0)
                } else {
                    Log::info('checkRestartFollowUnixTime の チェックNG。もうしばらくお待ちください。');
                } // if ($u_repository->checkRestartFollowUnixTime($user_twitter_account_id) == true) {
            } // foreach ($userTwitterAccountIds as $user_twitter_account_id)
        }

        Log::info('=============================');
        Log::info('AutoFollowAccounts End');
        Log::info('=============================');
    }
}
