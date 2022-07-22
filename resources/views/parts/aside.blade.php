<aside class="p-myPage__aside">
            <div class="p-myPage__aside__item">
                <div class="p-myPage__aside__item__head">認証アカウント</div>
                <div class="p-myPage__aside__item__info">
                    @if ($accounts) 
                        @foreach ($accounts as $account) 
                            @if ($account->auth_flg) 
                                {{ $account->screen_name }}
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="p-myPage__aside__item">
                <div class="p-myPage__aside__item__attention">*最大10件まで登録可能です。</div>
                <div class="p-myPage__aside__item__head">Twitter認証モード</div>
                <div class="c-appBtn"><a class="c-appBtn--auth" href="{{ route('login.twitter') }}">アカウント認証</a></div>
                <div class="c-appBtn"><a class="c-appBtn--logout" href="{{ route('logout.twitter') }}" target="_blank">ログアウト</a></div>
            </div>
            @if ($accounts) 
                @foreach ($accounts as $account) 
                    @if ($account->auth_flg == 1) 
                        <div class="p-myPage__aside__item">
                            <div class="p-myPage__aside__item__head">フォローモード</div>
                                <twitter-auto-follow-action 
                                :user_id="{{$user_id}}"
                                :auth_screen_name="{{json_encode($accounts[0]->screen_name)}}"
                                :auto_follow_flg="{{$account->auto_follow_flg}}"
                                ></twitter-auto-follow-action>
                        </div>
                        <div class="p-myPage__aside__item">
                            <div class="p-myPage__aside__item__head">ツイートモード</div>
                            <twitter-auto-tweet-action 
                                :user_id="{{$user_id}}"
                                :auth_screen_name="{{json_encode($accounts[0]->screen_name)}}"
                                :auto_follow_flg="{{$account->auto_follow_flg}}"
                                ></twitter-auto-tweet-action>
                        </div>
                    @endif
                @endforeach
            @endif
            
        </aside>