<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Middleware\CleanArchitectureMiddleware;
use App\User;
use Illuminate\Support\Facades\Auth;
use packages\Domain\Domain\User\UserTwitterAccountsRepositoryInterface;

class MyPageController extends Controller
{
    public function index(UserTwitterAccountsRepositoryInterface $repository)
    {
        $accounts = $repository->find();


        // if ($accounts) {
        foreach ($accounts as $account) {
            if ($account->auth_flg) {
                $auth_screen_name = $account->screen_name;
            } else {
                $auth_screen_name = 'MyPage_auth_screen_name_null';
            }
        }

        // }

        $user_id = Auth::id();
        CleanArchitectureMiddleware::$view = view('pages.myPage', compact('accounts', 'user_id', 'auth_screen_name'));
    }
}
