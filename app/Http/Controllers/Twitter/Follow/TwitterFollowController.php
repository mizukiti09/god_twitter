<?php

namespace App\Http\Controllers\Twitter\Follow;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TwitterFollowController extends Controller
{
    public function autoFollow(Request $request)
    {
        $repository->autoFollowHandle(
            $request->user_id, 
            $request->screen_name, 
            $request->array_search_text);
    }
}
