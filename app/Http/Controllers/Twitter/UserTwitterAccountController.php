<?php

namespace App\Http\Controllers\Twitter;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use packages\UseCase\Twitter\UserTwitterAccountUseCaseInterface;

class UserTwitterAccountController extends Controller
{
    // このサービスに登録しているアカウント削除
    public function delete(Request $request, UserTwitterAccountUseCaseInterface $interactor)
    {
        $interactor->deleteHandle($request->screen_name);
    }
}
