<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use packages\Domain\Application\Twitter\TwitterAuthInteractor;
use packages\Domain\Application\Twitter\UserTwitterAccountInteractor;
use packages\UseCase\Twitter\Auth\TwitterAuthUseCaseInterface;
use packages\Infrastructure\User\UserTwitterAccountsRepository;
use packages\Domain\Domain\User\UserTwitterAccountsRepositoryInterface;
use packages\UseCase\Twitter\UserTwitterAccountUseCaseInterface;

class TwitterServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerForFacade();
    }

    private function registerForFacade()
    {
        $this->app->bind('twitter', 'App\Services\Twitter');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * 登録する必要のある全コンテナ結合
     *
     * @var array
     */
    public $bindings = [
        UserTwitterAccountsRepositoryInterface::class => UserTwitterAccountsRepository::class,
        UserTwitterAccountUseCaseInterface::class => UserTwitterAccountInteractor::class,
        TwitterAuthUseCaseInterface::class => TwitterAuthInteractor::class,
    ];
}
