<?php

namespace App\Http\Controllers\Twitter;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Middleware\CleanArchitectureMiddleware;
use packages\UseCase\Twitter\Follow\TwitterAutoFollowUseCaseInterface;
use packages\UseCase\Twitter\Follow\TwitterTargetAccountUseCaseInterface;

class TwitterFollowController extends Controller
{
    public function targetAccounts()
    {
        CleanArchitectureMiddleware::$view = view('pages.targetAccounts.lists');
    }

    public function addTargetAccount(Request $request, TwitterTargetAccountUseCaseInterface $useCase)
    {
        $useCase->addTargetAccountHandle(
            $request->user_id,
            $request->auth_screen_name,
            $request->target_screen_name
        );
    }

    public function deleteTargetAccount(Request $request, TwitterTargetAccountUseCaseInterface $useCase)
    {
        $useCase->deleteTargetAccountHandle(
            $request->user_id,
            $request->auth_screen_name,
            $request->target_id,
            $request->reset_auto_follow_flg
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

    public function autoFollowReset(Request $request, TwitterAutoFollowUseCaseInterface $useCase)
    {
        $useCase->autoFollowResetHandle(
            $request->user_id,
            $request->screen_name
        );
    }

    public function autoFollowStart(Request $request, TwitterAutoFollowUseCaseInterface $useCase)
    {
        $useCase->startAutoFollowHandle($request->user_id, $request->screen_name);
    }

    public function autoFollowStop(Request $request, TwitterAutoFollowUseCaseInterface $useCase)
    {
        $useCase->stopAutoFollowHandle($request->user_id, $request->screen_name);
    }

    public function autoUnFollowStart(Request $request, TwitterAutoFollowUseCaseInterface $useCase)
    {
        $useCase->startAutoUnFollowHandle($request->user_id, $request->screen_name);
    }

    public function autoUnFollowStop(Request $request, TwitterAutoFollowUseCaseInterface $useCase)
    {
        $useCase->stopAutoUnFollowHandle($request->user_id, $request->screen_name);
    }
}
