<?php

namespace App\Console\Commands;

use App\Facades\Twitter;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use packages\Domain\Domain\User\FollowAccountsRepositoryInterface;
use packages\Domain\Domain\User\AutoFollowDatasRepositoryInterface;
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
        FollowAccountsRepositoryInterface $f_repository
    ) {
        Log::info('=============================');
        Log::info('AutoFollowAccounts Start');
        Log::info('=============================');

        $userTwitterAccountIds = $u_repository->getOnAutoFollowAccounts();
        if (!empty($userTwitterAccountIds)) {
            foreach ($userTwitterAccountIds as $user_twitter_account_id) {
                // ターゲットアカウントのスクリーンネーム
                $target_account_screen_name = $a_repository->getTargetAccountScreenName($user_twitter_account_id);
                // フォロワーサーチキーワード
                $array_search_text = $a_repository->getArraySearchText($user_twitter_account_id);
                // ネクストカーソル
                $next_cursor = $a_repository->getNextCursor($user_twitter_account_id);
                // DBに保存する前の格納用変数
                $lists = array();
                // user_twitter_account
                $account = $u_repository->getAccount($user_twitter_account_id);

                Log::info($account->user_id);
                Log::info($account->screen_name);

                if (empty($next_cursor)) {
                    $followers_list = Twitter::getAuthConnection($account->user_id, $account->screen_name)
                        ->get('followers/list', array(
                            "screen_name" => $target_account_screen_name,
                            "count"       => 200,
                        ));
                } else {
                    $followers_list = Twitter::getAuthConnection($account->user_id, $account->screen_name)
                        ->get('followers/list', array(
                            "screen_name" => $target_account_screen_name,
                            "count"       => 200,
                            "cursor" => $next_cursor,
                        ));
                }

                // 次のフォロワーリストがあったらnext_cursorをDBに保存
                if ($followers_list->next_cursor) {
                    $a_repository->saveNextCursor($user_twitter_account_id, $followers_list->next_cursor);
                } else {
                    $a_repository->incrementTargetAccountId($user_twitter_account_id);
                    Log::info('incrementTargetAccountIdされました。');
                }

                foreach ($followers_list->users as $user) {

                    // プロフィールの中にフォロワーサーチキーワードが入っていたら
                    // $check_countをインクリメントする。
                    // $array_search_textの値の数とインクリメントされた$check_countが
                    // 同じ数になればデータベースに保存する。
                    $check_count = 0;
                    if ($user->following == false) {
                        foreach ($array_search_text as $text) {
                            if (strpos($user->description, $text) !== false) {
                                $check_count += 1;
                            }
                            if (count($array_search_text) == $check_count) {
                                array_push($lists, $user->screen_name);
                            }
                        }
                    }
                }

                foreach ($lists as $screen_name) {
                    $f_repository->saveFollowAccountScreenName($user_twitter_account_id, $screen_name);
                }
            }
        }

        Log::info('=============================');
        Log::info('AutoFollowAccounts End');
        Log::info('=============================');
    }
}
