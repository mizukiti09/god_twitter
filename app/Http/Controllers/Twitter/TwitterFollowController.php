<?php

namespace App\Http\Controllers\Twitter;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use packages\UseCase\Twitter\Follow\TwitterAutoFollowUseCaseInterface;

class TwitterFollowController extends Controller
{
    public function autoFollow(Request $request, TwitterAutoFollowUseCaseInterface $useCase)
    {
        $useCase->autoFollowSaveHandle(
            $request->user_id,
            $request->screen_name,
            $request->array_search_text
        );
    }

    public function autoFollowStop(Request $request, TwitterAutoFollowUseCaseInterface $useCase)
    {
        $useCase->stopAutoFollowHandle($request->user_id);
    }
}
