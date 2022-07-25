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
    Route::post('twitter/autoFollow', 'TwitterFollowController@autoFollow')->name('twitter.autoFollow');
    Route::post('twitter/autoFollowStop', 'TwitterFollowController@autoFollowStop')->name('twitter.autoFollowStop');
    Route::post('twitter/autoTweet', 'TwitterTweetController@autoTweet')->name('twitter.autoTweet');
    Route::post('twitter/autoTweetOn', 'TwitterTweetController@autoTweetOn')->name('twitter.autoTweetOn');
    Route::post('twitter/autoTweetStop', 'TwitterTweetController@autoTweetStop')->name('twitter.autoTweetStop');
});
