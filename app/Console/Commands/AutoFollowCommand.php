<?php

namespace App\Console\Commands;

use App\Facades\Twitter;
use Illuminate\Console\Command;
use packages\Domain\Domain\User\AutoFollowDatasRepositoryInterface;
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
    protected $description = '自動フォロー';

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
        AutoFollowDatasRepositoryInterface $a_repository
    ) {
        $accountsId = $u_repository->getOnAutoFollowAccounts();
        if (!empty($accountsId)) {
            foreach ($accountsId as $id) {
                $account = $u_repository->getAccount($id);

                $results = Twitter::getAuthConnection($account->user_id, $account->screen_name)
                    ->get('followers/ids', array(
                        // "screen_name" => $repo->getRandomAccount(),
                        "screen_name" => 'matsu_bouzu',
                        "count"       => 5000,
                    ));

                $a_repository->saveNextCursor($account->user_twitter_account_id, $$results->next_cursor);

                // ここからforeach で users/show
            }
        }
    }
}
