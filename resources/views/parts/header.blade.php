<header class="l-header u-fade-down-header">
    <div class="l-headerInner">
        <div class="l-header__logo">
            <a href="{{ route('home') }}">God Twitter</a>
        </div>
        <div id="u-device__desc">
            <nav>
                <ul class="c-navbar">
                    @guest @if (Route::has('login'))
                        <li class="c-nav__item"><a href="{{ route('login') }}">{{ __('Login') }}</a></li>
                    @endif @if (Route::has('register'))
                        <li class="c-nav__item"><a href="{{ route('register') }}">{{ __('Register') }}</a></li>
                    @endif @else
                    <li class="c-nav__item"><a href="{{ route('myPage') }}">{{ __('MyPage') }}</a></li>
                        <li class="c-nav__item"><a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>

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

        <div id="u-device__sp">
            <div class="c-btn-open">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <nav class="c-nav">
                <div class="c-nav__list">
                    <ul>
                        @guest @if (Route::has('login'))
                            <li class="c-nav__item"><a href="{{ route('login') }}">{{ __('Login') }}</a></li>
                        @endif @if (Route::has('register'))
                            <li class="c-nav__item"><a href="{{ route('register') }}">{{ __('Register') }}</a></li>
                        @endif 
                        @else
                            <li class="c-nav__item"><a href="{{ route('myPage') }}">{{ __('myPage') }}</a></li>
                            <li class="c-nav__item"><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
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