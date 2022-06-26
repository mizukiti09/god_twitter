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
});
