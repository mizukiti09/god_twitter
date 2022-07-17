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
                Log::debug('自動フォローアカウンツ保存:成功');
            })
            ->onFailure(function () {
                Log::error('自動フォローアカウンツ保存:失敗');
            });

        $schedule->command('command:autoFollow')
            ->everyTenMinutes()
            ->onSuccess(function () {
                Log::debug('自動フォロー:成功');
            })
            ->onFailure(function () {
                Log::error('自動フォロー:失敗');
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
