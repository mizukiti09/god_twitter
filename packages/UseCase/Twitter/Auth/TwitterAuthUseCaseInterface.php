<?php

namespace packages\UseCase\Twitter\Auth;

interface TwitterAuthUseCaseInterface
{
    // ログイン認証
    public function loginRedirectProvider();

    // ログイン認証コールバック
    public function loginHandleProviderCallback();

    // 認証解除
    public function logoutHandle();
}
