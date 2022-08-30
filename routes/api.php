<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::namespace('Twitter')->group(function () {
    Route::post('twitter/account/delete', 'UserTwitterAccountController@delete')->name('twitter.deleteAccount');
    Route::post('twitter/addTargetAccount', 'TwitterFollowController@addTargetAccount')->name('twitter.addTargetAccount');
    Route::post('twitter/deleteTargetAccount', 'TwitterFollowController@deleteTargetAccount')->name('twitter.deleteTargetAccount');
    Route::post('twitter/autoFollowSave', 'TwitterFollowController@autoFollowSave')->name('twitter.autoFollowSave');
    Route::post('twitter/autoFollowReset', 'TwitterFollowController@autoFollowReset')->name('twitter.autoFollowReset');
    Route::post('twitter/autoFollowStart', 'TwitterFollowController@autoFollowStart')->name('twitter.autoFollowStart');
    Route::post('twitter/autoFollowStop', 'TwitterFollowController@autoFollowStop')->name('twitter.autoFollowStop');
    Route::post('twitter/autoUnFollowStart', 'TwitterFollowController@autoUnFollowStart')->name('twitter.autoUnFollowStart');
    Route::post('twitter/autoUnFollowStop', 'TwitterFollowController@autoUnFollowStop')->name('twitter.autoUnFollowStop');
    Route::post('twitter/autoLikeSave', 'TwitterLikeController@autoLikeSave')->name('twitter.autoLikeSave');
    Route::post('twitter/autoLikeReset', 'TwitterLikeController@autoLikeReset')->name('twitter.autoLikeReset');
    Route::post('twitter/autoLikeStart', 'TwitterLikeController@autoLikeStart')->name('twitter.autoLikeStart');
    Route::post('twitter/autoLikeStop', 'TwitterLikeController@autoLikeStop')->name('twitter.autoLikeStop');
    Route::post('twitter/autoTweet', 'TwitterTweetController@autoTweet')->name('twitter.autoTweet');
    Route::post('twitter/autoTweetOn', 'TwitterTweetController@autoTweetOn')->name('twitter.autoTweetOn');
    Route::post('twitter/autoTweetStop', 'TwitterTweetController@autoTweetStop')->name('twitter.autoTweetStop');
    Route::post('twitter/tweetDelete', 'TwitterTweetController@tweetDelete')->name('twitter.tweetDelete');
    Route::post('twitter/tweetEdit', 'TwitterTweetController@tweetEdit')->name('twitter.tweetEdit');
});
