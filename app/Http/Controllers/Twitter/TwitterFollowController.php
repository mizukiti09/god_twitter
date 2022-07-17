<?php

namespace App\Http\Controllers\Twitter;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use packages\UseCase\Twitter\Follow\TwitterAutoFollowUseCaseInterface;

class TwitterFollowController extends Controller
{
    public function autoFollow(Request $request, TwitterAutoFollowUseCaseInterface $repository)
    {
        $repository->autoFollowSaveHandle(
            $request->user_id,
            $request->screen_name,
            $request->array_search_text
        );
    }

    public function autoFollowStop(Request $request, TwitterAutoFollowUseCaseInterface $repository)
    {
        $repository->stopAutoFollowHandle($request->user_id);
    }
}
