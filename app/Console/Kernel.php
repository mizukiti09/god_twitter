<?php

namespace App\Console;

use Illuminate\Support\Facades\Log;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\AutoFollowAccountsCommand::class,
        \App\Console\Commands\AutoFollowCommand::class,
        \App\Console\Commands\AutoUnFollowCommand::class,
        \App\Console\Commands\AutoTweetCommand::class,
        \App\Console\Commands\AutoLikeTweetsCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('command:autoFollowAccounts')
            ->everyFiveMinutes()
            ->onSuccess(function () {
                Log::debug('自動フォローアカウンツ保存:確認OK');
            })
            ->onFailure(function () {
                Log::error('自動フォローアカウンツ保存:確認失敗');
            });

        $schedule->command('command:autoFollow')
            ->everyFiveMinutes()
            ->onSuccess(function () {
                Log::debug('自動フォロー:確認成功');
            })
            ->onFailure(function () {
                Log::error('自動フォロー:確認失敗');
            });

        $schedule->command('command:autoTweet')
            ->everyMinute()
            ->onSuccess(function () {
                Log::debug('自動ツイート:確認OK');
            })
            ->onFailure(function () {
                Log::error('自動ツイート:確認失敗');
            });

        $schedule->command('command:autoLikeTweets')
            ->everyMinute()
            ->onSuccess(function () {
                Log::debug('自動いいねツイート保存:確認OK');
            })
            ->onFailure(function () {
                Log::error('自動いいねツイート保存:確認失敗');
            });

        $schedule->command('command:autoUnFollow')
            ->everyMinute()
            ->onSuccess(function () {
                Log::debug('自動アンフォロー:確認成功');
            })
            ->onFailure(function () {
                Log::error('自動アンフォロー:確認失敗');
            });
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
