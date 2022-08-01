<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use packages\Domain\Application\Twitter\TwitterAuthInteractor;
use packages\UseCase\Twitter\Auth\TwitterAuthUseCaseInterface;
use packages\Infrastructure\User\UserTwitterAccountsRepository;
use packages\Domain\Application\Twitter\TwitterFollowInteractor;
use packages\Domain\Application\Twitter\TwitterLikeInteractor;
use packages\Domain\Application\Twitter\TwitterTweetInteractor;
use packages\UseCase\Twitter\UserTwitterAccountUseCaseInterface;
use packages\Domain\Application\Twitter\UserTwitterAccountInteractor;
use packages\Domain\Domain\TargetAccountsRepositoryInterface;
use packages\Domain\Domain\User\AutoFollowDatasRepositoryInterface;
use packages\Domain\Domain\User\AutoLikeDatasRepositoryInterface;
use packages\Domain\Domain\User\AutoTweetDatasRepositoryInterface;
use packages\Domain\Domain\User\FollowAccountsRepositoryInterface;
use packages\Domain\Domain\User\LikeTweetsRepositoryInterface;
use packages\UseCase\Twitter\Follow\TwitterAutoFollowUseCaseInterface;
use packages\Domain\Domain\User\UserTwitterAccountsRepositoryInterface;
use packages\Infrastructure\TargetAccountsRepository;
use packages\Infrastructure\User\AutoFollowDatasRepository;
use packages\Infrastructure\User\AutoLikeDatasRepository;
use packages\Infrastructure\User\AutoTweetDatasRepository;
use packages\Infrastructure\User\FollowAccountsRepository;
use packages\Infrastructure\User\LikeTweetsRepository;
use packages\UseCase\Twitter\Like\TwitterAutoLikeUseCaseInterface;
use packages\UseCase\Twitter\Tweet\TwitterAutoTweetUseCaseInterface;

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
        TwitterAutoTweetUseCaseInterface::class       => TwitterTweetInteractor::class,
        TwitterAuthUseCaseInterface::class            => TwitterAuthInteractor::class,
        TwitterAutoFollowUseCaseInterface::class      => TwitterFollowInteractor::class,
        TargetAccountsRepositoryInterface::class      => TargetAccountsRepository::class,
        AutoFollowDatasRepositoryInterface::class     => AutoFollowDatasRepository::class,
        FollowAccountsRepositoryInterface::class      => FollowAccountsRepository::class,
        AutoTweetDatasRepositoryInterface::class      => AutoTweetDatasRepository::class,
        TwitterAutoLikeUseCaseInterface::class        => TwitterLikeInteractor::class,
        AutoLikeDatasRepositoryInterface::class       => AutoLikeDatasRepository::class,
        LikeTweetsRepositoryInterface::class          => LikeTweetsRepository::class,
    ];
}
