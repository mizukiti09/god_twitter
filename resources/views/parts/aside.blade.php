<aside class="c-structure__aside">
    <div class="c-structure__aside__item">
        <div class="c-structure__aside__item__head">認証アカウント</div>
        <div class="c-structure__aside__item__info">
            @if ($auth_account)
                {{ $auth_account->screen_name }}
            @endif
        </div>
    </div>
    <div class="c-structure__aside__item">
        <div class="c-structure__aside__item__attention">*最大10件まで登録可能です。</div>
        <div class="c-structure__aside__item__head">TwitterAuth</div>
        <div class="c-appBtn"><a class="c-appBtn--auth" href="{{ route('login.twitter') }}">アカウント認証</a></div>
        <div class="c-appBtn"><a class="c-appBtn--logout" href="{{ route('logout.twitter') }}" target="_blank">ログアウト</a>
        </div>
    </div>
    @if ($auth_account)
        <div class="c-structure__aside__item">
            <div class="c-structure__aside__item__attention">
                <a href="{{ route('twitter.targetAccounts') }}">現在のTargetAccount一覧
                    <span id="other-link-arrow"><i class="fas fa-arrow-circle-right icon-hover-right"></i></span>
                </a>
            </div>
            <div class="c-structure__aside__item__head">TargetAccount</div>
            <twitter-target-account :user_id="{{ $user_id }}"
                :auth_screen_name="{{ json_encode($auth_account->screen_name) }}"
                :auto_follow_flg="{{ $auth_account->auto_follow_flg }}" :target="{{ json_encode($target) }}">
            </twitter-target-account>
            @if ($target_accounts)
                <div class="c-structure__aside__item__head">Follow</div>
                <twitter-auto-follow-action :user_id="{{ $user_id }}"
                    :auth_screen_name="{{ json_encode($auth_account->screen_name) }}"
                    :auto_follow_flg="{{ $auth_account->auto_follow_flg }}"
                    :auto_un_follow_flg="{{ $auth_account->auto_unFollow_flg }}"
                    :db_search_text_condition="{{ json_encode($followSearchTextAndCondition) }}">
                </twitter-auto-follow-action>
            @endif
        </div>
        <div class="c-structure__aside__item">
            <div class="c-structure__aside__item__head">Like</div>
            <twitter-auto-like-action :user_id="{{ $user_id }}"
                :auth_screen_name="{{ json_encode($auth_account->screen_name) }}"
                :auto_like_flg="{{ $auth_account->auto_like_flg }}"
                :db_search_text_condition="{{ json_encode($likeSearchTextAndCondition) }}">
            </twitter-auto-like-action>
        </div>
        <div class="c-structure__aside__item">
            <div class="c-structure__aside__item__attention"><a href="{{ route('twitter.tweetList') }}">現在のツイート予定一覧
                    <span id="other-link-arrow"><i class="fas fa-arrow-circle-right icon-hover-right"></i></span></a>
            </div>
            <div class="c-structure__aside__item__head">Tweet</div>
            <twitter-auto-tweet-action :user_id="{{ $user_id }}"
                :auth_screen_name="{{ json_encode($auth_account->screen_name) }}"
                :auto_tweet_flg="{{ $auth_account->auto_tweet_flg }}"></twitter-auto-tweet-action>
        </div>
    @endif
</aside>
