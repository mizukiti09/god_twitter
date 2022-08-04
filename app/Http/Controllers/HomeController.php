<?php

namespace App\Http\Controllers;


use App\Facades\Twitter;
use App\Http\Middleware\CleanArchitectureMiddleware;
use packages\Domain\Domain\User\FollowedAccountsRepositoryInterface;
use packages\Domain\Domain\User\UserTwitterAccountsRepositoryInterface;

class HomeController extends Controller
{
    public function index(FollowedAccountsRepositoryInterface $repo)
    {

        $result = Twitter::getAuthConnection(1, 'gori_tiki_')->get("friendships/lookup", array(
            "user_id" => 1551892535122862082,
        ));

        // array_key_exists( 'key_name', $array );


        dd($result);




        $created_at = $result->status->created_at;
        $timestamp = strtotime($created_at);
        dd($timestamp);

        CleanArchitectureMiddleware::$view = view('pages.home');
    }
}
