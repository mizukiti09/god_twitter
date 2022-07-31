<?php

namespace App\Http\Controllers\Twitter;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use packages\UseCase\Twitter\Follow\TwitterAutoFollowUseCaseInterface;

class TwitterFollowController extends Controller
{
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
}
