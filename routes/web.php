<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index')->name('home');

Auth::routes();

// アプリドキュメンツ
Route::namespace('Document')->group(function () {
    Route::prefix('document')->group(function () {
        Route::get('/first', 'DocumentController@first')->name('document.first');
        Route::get('/autoFollow', 'DocumentController@autoFollow')->name('document.autoFollow');
        Route::get('/autoLike', 'DocumentController@autoLike')->name('document.autoLike');
        Route::get('/autoTweet', 'DocumentController@autoTweet')->name('document.autoTweet');
    });
});

Route::namespace('Auth')->group(function () {
    //パスワードリセット
    Route::get(
        'password/reset',
        'ForgotPasswordController@showLinkRequestForm'
    )->name('password.request');
    Route::post(
        'password/email',
        'ForgotPasswordController@sendResetLinkEmail'
    )->name('password.email');
    Route::get(
        'password/reset/{token}',
        'ResetPasswordController@showResetForm'
    )->name('password.reset');
    Route::post(
        'password/reset',
        'ResetPasswordController@reset'
    )->name('password.resetPost');
});

Route::get('/myPage', 'MyPageController@index')->name('myPage')->middleware('auth');


Route::namespace('Twitter')->group(function () {
    // Twitter認証====================
    // ログイン
    Route::get('login/twitter', 'TwitterLoginController@twitterLogin')->name('login.twitter');
    Route::get('login/twitter/callback', 'TwitterLoginController@twitterCallback')->name('login.twitter.callback');

    // ログアウト
    Route::get('logout/twitter', 'TwitterLogoutController@handle')->name('logout.twitter');
    // ================================

    Route::prefix('twitter')->group(function () {
        Route::get('/follow', 'TwitterFollowController@index')->name('twitter.follow')->middleware('auth');
        Route::get('/tweetList', 'TwitterTweetController@list')->name('twitter.tweetList')->middleware('auth');
        Route::get('/targetAccounts', 'TwitterFollowController@targetAccounts')->name('twitter.targetAccounts')->middleware('auth');
    });
});
