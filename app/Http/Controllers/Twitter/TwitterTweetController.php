<?php

namespace App\Http\Controllers\Twitter;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use packages\UseCase\Twitter\Tweet\TwitterAutoTweetUseCaseInterface;

class TwitterTweetController extends Controller
{
    public function autoTweet(Request $request, TwitterAutoTweetUseCaseInterface $useCase)
    {
        $useCase->autoTweetSaveHandle(
            $request->user_id,
            $request->screen_name,
            $request->tweet_text,
            $request->date_value
        );
    }

    public function autoTweetOn(Request $request, TwitterAutoTweetUseCaseInterface $useCase)
    {
        $useCase->onAutoTweetHandle($request->user_id, $request->screen_name);
    }

    public function autoTweetStop(Request $request, TwitterAutoTweetUseCaseInterface $useCase)
    {
        $useCase->stopAutoTweetHandle($request->user_id);
    }
}
