<header class="l-header">
    <div class="l-headerInner">
        <div class="l-header__logo">
            <a href="{{ route('home') }}">神ったー</a>
        </div>
        <div class="u-device__desc">
            <nav>
                <ul class="c-navbar">
                    @guest @if (Route::has('login'))
                        <li class="c-nav__item"><a href="{{ route('login') }}">ログイン</a></li>
                        @endif @if (Route::has('register'))
                            <li class="c-nav__item"><a href="{{ route('register') }}">ユーザー登録</a></li>
                        @endif
                    @else
                        @if ($auth_account)
                            <li class="c-nav__item"><a href="{{ route('twitter.tweetHistory') }}">ツイート履歴一覧</a></li>
                            <li class="c-nav__item"><a href="{{ route('twitter.tweetList') }}">ツイート予定一覧</a></li>
                            <li class="c-nav__item"><a href="{{ route('twitter.targetAccounts') }}">ターゲットアカウント一覧</a>
                            </li>
                        @endif
                        <li class="c-nav__item"><a href="{{ route('myPage') }}">マイページ</a></li>
                        <li class="c-nav__item"><a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">ログアウト</a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    @endguest
            </ul>
            <div class="btn-trigger" id="btn02">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </nav>
    </div>

    <div class="u-device__sp">
        <div class="c-btn-open">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <nav class="c-nav">
            <div class="c-nav__list">
                <ul>
                    @guest @if (Route::has('login'))
                        <li class="c-nav__item"><a href="{{ route('login') }}">ログイン</a></li>
                        @endif @if (Route::has('register'))
                            <li class="c-nav__item"><a href="{{ route('register') }}">ユーザー登録</a>
                            </li>
                        @endif
                    @else
                        @if ($auth_account)
                            <li class="c-nav__item"><a href="{{ route('twitter.tweetHistory') }}">ツイート履歴一覧</a></li>
                            <li class="c-nav__item"><a href="{{ route('twitter.tweetList') }}">ツイート予定一覧</a></li>
                            <li class="c-nav__item"><a
                                    href="{{ route('twitter.targetAccounts') }}">ターゲットアカウント一覧</a></li>
                        @endif
                        <li class="c-nav__item"><a href="{{ route('myPage') }}">マイページ</a></li>
                        <li class="c-nav__item"><a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">ログアウト</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    @endguest
            </ul>
        </div>
    </nav>
</div>


</div>
</header>
