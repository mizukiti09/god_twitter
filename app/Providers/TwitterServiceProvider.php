<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use packages\Domain\Application\Twitter\TwitterAuthInteractor;
use packages\UseCase\Twitter\Auth\TwitterAuthUseCaseInterface;
use packages\Infrastructure\User\UserTwitterAccountsRepository;
use packages\Domain\Application\Twitter\TwitterFollowInteractor;
use packages\UseCase\Twitter\UserTwitterAccountUseCaseInterface;
use packages\Domain\Application\Twitter\UserTwitterAccountInteractor;
use packages\Domain\Domain\TargetAccountsRepositoryInterface;
use packages\Domain\Domain\User\AutoFollowDatasRepositoryInterface;
use packages\Domain\Domain\User\FollowAccountsRepositoryInterface;
use packages\UseCase\Twitter\Follow\TwitterAutoFollowUseCaseInterface;
use packages\Domain\Domain\User\UserTwitterAccountsRepositoryInterface;
use packages\Infrastructure\TargetAccountsRepository;
use packages\Infrastructure\User\AutoFollowDatasRepository;
use packages\Infrastructure\User\FollowAccountsRepository;

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
        UserTwitterAccountUseCaseInterface::class     => UserTwitterAccountInteractor::class,
        TwitterAuthUseCaseInterface::class            => TwitterAuthInteractor::class,
        TwitterAutoFollowUseCaseInterface::class      => TwitterFollowInteractor::class,
        TargetAccountsRepositoryInterface::class      => TargetAccountsRepository::class,
        AutoFollowDatasRepositoryInterface::class     => AutoFollowDatasRepository::class,
        FollowAccountsRepositoryInterface::class      => FollowAccountsRepository::class,
    ];
}
