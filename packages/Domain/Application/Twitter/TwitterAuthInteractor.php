<?php

namespace packages\Domain\Application\Twitter;

use App\Facades\Twitter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use packages\UseCase\Twitter\Auth\TwitterAuthUseCaseInterface;
use packages\Domain\Domain\User\UserTwitterAccountsRepositoryInterface;

class TwitterAuthInteractor implements TwitterAuthUseCaseInterface
{
    /**
     * @var UserTwitterAccountsRepositoryInterface
     */
    private $twitterAccountsRepository;

    public function __construct(UserTwitterAccountsRepositoryInterface $twitterAccountsRepository)
    {
        $this->twitterAccountsRepository = $twitterAccountsRepository;
    }

    /**
     * Twitterの認証ページヘユーザーをリダイレクト
     *
     * @return \Illuminate\Http\Response
     */
    public function loginRedirectProvider()
    {
        return Socialite::driver('twitter')->redirect();
    }
    /**
     * Twitterからユーザー情報を取得(Callback先)
     */
    public function loginHandleProviderCallback()
    {
        $twitterAuth = Twitter::connectUserAuth();

        Log::info('Twitterから取得しました。', ['user' => $twitterAuth]);

        $account = Twitter::getConnection(Auth::id(), $twitterAuth->getNickname())->get('users/lookup', array(
            'screen_name' => $twitterAuth->getNickname(),
        ));

        $this->twitterAccountsRepository->save($account[0], $twitterAuth->token, $twitterAuth->tokenSecret);


        Auth::login(Auth::user());
        return redirect()->route('myPage');
    }

    // twitter　認証解除
    public function logoutHandle()
    {
        $this->twitterAccountsRepository->userTwitterAuthLogout();
    }
}
