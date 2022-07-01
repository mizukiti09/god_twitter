<?php

namespace packages\Domain\Application\Twitter;

use App\Facades\Twitter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use packages\UseCase\Twitter\UserTwitterAccountUseCaseInterface;
use packages\Domain\Domain\User\UserTwitterAccountsRepositoryInterface;

class UserTwitterAccountInteractor implements UserTwitterAccountUseCaseInterface
{
    /**
     * @var UserTwitterAccountsRepositoryInterface
     */
    private $twitterAccountsRepository;

    public function __construct(UserTwitterAccountsRepositoryInterface $twitterAccountsRepository)
    {
        $this->twitterAccountsRepository = $twitterAccountsRepository;
    }

    public function deleteHandle($screen_name)
    {
        $this->twitterAccountsRepository->deleteAccount($screen_name);
    }
}
