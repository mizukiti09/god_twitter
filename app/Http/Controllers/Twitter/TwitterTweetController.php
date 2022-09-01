<?php

namespace App\Http\Controllers\Twitter;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Middleware\CleanArchitectureMiddleware;
use packages\UseCase\Twitter\Tweet\TwitterAutoTweetUseCaseInterface;

class TwitterTweetController extends Controller
{
    public function list()
    {
        CleanArchitectureMiddleware::$view = view('pages.tweet.list');
    }

    public function history()
    {
        CleanArchitectureMiddleware::$view = view('pages.tweet.history');
    }

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
        $useCase->stopAutoTweetHandle($request->user_id, $request->screen_name);
    }

    public function tweetDelete(Request $request, TwitterAutoTweetUseCaseInterface $useCase)
    {
        $useCase->tweetDeleteHandle($request->tweet_id);
    }

    public function tweetEdit(Request $request, TwitterAutoTweetUseCaseInterface $useCase)
    {
        $useCase->tweetEditHandle(
            $request->tweet_id,
            $request->tweet_text,
            $request->tweet_time
        );
    }

    public function tweetHistoryReset(Request $request, TwitterAutoTweetUseCaseInterface $useCase)
    {
        $useCase->tweetHistoryResetHandle(
            $request->user_id,
            $request->screen_name
        );
    }
}
