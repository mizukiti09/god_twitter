<?php

namespace App\Http\Controllers\Twitter;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\CleanArchitectureMiddleware;
use packages\Domain\Domain\TargetAccountsRepositoryInterface;
use packages\UseCase\Twitter\Follow\TwitterAutoFollowUseCaseInterface;
use packages\Domain\Domain\User\UserTwitterAccountsRepositoryInterface;
use packages\UseCase\Twitter\Follow\TwitterTargetAccountUseCaseInterface;

class TwitterFollowController extends Controller
{
    public function targetAccounts(
        UserTwitterAccountsRepositoryInterface $repository,
        TargetAccountsRepositoryInterface $ta_repository
    ) {
        $accounts = $repository->find();

        if (empty($accounts[0])) {
            $accounts = array();
            $auth_screen_name = 'MyPage_auth_screen_name_null';
        } else {
            foreach ($accounts as $account) {
                if ($account->auth_flg) {
                    $auth_screen_name = $account->screen_name;
                    $target_accounts = $ta_repository->getAllTargetAccounts($account->id);

                    if (isset($target_accounts[0])) {
                        $target_accounts = $target_accounts;
                    } else {
                        $target_accounts = [];
                    }
                } else {
                    $auth_screen_name = 'MyPage_auth_screen_name_null';
                }
            }
        }

        $user_id = Auth::id();
        CleanArchitectureMiddleware::$view = view('pages.targetAccounts.lists', compact('accounts', 'user_id', 'auth_screen_name', 'target_accounts'));
    }

    public function addTargetAccount(Request $request, TwitterTargetAccountUseCaseInterface $useCase)
    {
        $useCase->addTargetAccountHandle(
            $request->user_id,
            $request->auth_screen_name,
            $request->target_screen_name
        );
    }

    public function autoFollowSave(Request $request, TwitterAutoFollowUseCaseInterface $useCase)
    {
        $useCase->autoFollowSaveHandle(
            $request->user_id,
            $request->screen_name,
            $request->array_search_text,
            $request->condition
        );
    }

    public function autoFollowStart(Request $request, TwitterAutoFollowUseCaseInterface $useCase)
    {
        $useCase->startAutoFollowHandle($request->user_id, $request->screen_name);
    }

    public function autoFollowStop(Request $request, TwitterAutoFollowUseCaseInterface $useCase)
    {
        $useCase->stopAutoFollowHandle($request->user_id);
    }

    public function autoUnFollowStart(Request $request, TwitterAutoFollowUseCaseInterface $useCase)
    {
        $useCase->startAutoUnFollowHandle($request->user_id, $request->screen_name);
    }

    public function autoUnFollowStop(Request $request, TwitterAutoFollowUseCaseInterface $useCase)
    {
        $useCase->stopAutoUnFollowHandle($request->user_id);
    }
}
