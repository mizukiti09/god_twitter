<?php

namespace App\Http\Controllers\Twitter;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use packages\UseCase\Twitter\Like\TwitterAutoLikeUseCaseInterface;

class TwitterLikeController extends Controller
{
    public function autoLikeSave(Request $request, TwitterAutoLikeUseCaseInterface $useCase)
    {
        $useCase->autoLikeSaveHandle(
            $request->user_id,
            $request->screen_name,
            $request->array_search_text,
            $request->condition
        );
    }

    public function autoLikeStart(Request $request, TwitterAutoLikeUseCaseInterface $useCase)
    {
        $useCase->startAutoLikeHandle($request->user_id, $request->screen_name);
    }

    public function autoLikeStop(Request $request, TwitterAutoLikeUseCaseInterface $useCase)
    {
        $useCase->stopAutoLikeHandle($request->user_id);
    }
}
