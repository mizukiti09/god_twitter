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
        <div class="c-structure__aside__item__head">Twitter認証</div>
        <div class="c-appBtn">
            <a href="{{ route('document.first') }}" class="c-appBtn--none">
                ドキュメント
            </a>
        </div>
        <div class="c-appBtn"><a class="c-appBtn--auth" href="{{ route('login.twitter') }}">アカウント認証</a></div>
        <div class="c-appBtn"><a class="c-appBtn--logout" href="{{ route('logout.twitter') }}" target="_blank">ログアウト</a>
        </div>
    </div>
    @if ($auth_account)
        <div class="c-structure__aside__item">
            <div class="c-structure__aside__item__head">ターゲットアカウント</div>
            <div class="c-appBtn">
                <a href="{{ route('document.autoFollow') }}" class="c-appBtn--none">
                    ドキュメント
                </a>
            </div>
            <div class="c-appBtn">
                @if (!$auth_account->auto_follow_flg)
                    <a href="{{ route('twitter.targetAccounts') }}" class="c-appBtn--none">
                        ターゲットアカウント一覧
                    </a>
                @else
                    <a href="{{ route('twitter.targetAccounts') }}" class="c-appBtn--auto">
                        ターゲットアカウント一覧
                    </a>
                @endif
            </div>
            <twitter-target-account :user_id="{{ $user_id }}"
                :auth_screen_name="{{ json_encode($auth_account->screen_name) }}"
                :auto_follow_flg="{{ $auth_account->auto_follow_flg }}" :target="{{ json_encode($target) }}">
            </twitter-target-account>
            @if ($target_accounts)
                <div class="c-structure__aside__item__head">フォロー</div>
                <twitter-auto-follow-action :user_id="{{ $user_id }}"
                    :auth_screen_name="{{ json_encode($auth_account->screen_name) }}"
                    :auto_follow_flg="{{ $auth_account->auto_follow_flg }}"
                    :auto_un_follow_flg="{{ $auth_account->auto_unFollow_flg }}"
                    :db_search_text_condition="{{ json_encode($followSearchTextAndCondition) }}">
                </twitter-auto-follow-action>
            @endif
        </div>
        <div class="c-structure__aside__item">
            <div class="c-structure__aside__item__head">いいね</div>
            <div class="c-appBtn">
                <a href="{{ route('document.autoLike') }}" class="c-appBtn--none">
                    ドキュメント
                </a>
            </div>
            <twitter-auto-like-action :user_id="{{ $user_id }}"
                :auth_screen_name="{{ json_encode($auth_account->screen_name) }}"
                :auto_like_flg="{{ $auth_account->auto_like_flg }}"
                :db_search_text_condition="{{ json_encode($likeSearchTextAndCondition) }}">
            </twitter-auto-like-action>
        </div>
        <div class="c-structure__aside__item">
            <div class="c-structure__aside__item__head">ツイート</div>
            <div class="c-appBtn">
                <a href="{{ route('document.autoTweet') }}" class="c-appBtn--none">
                    ドキュメント
                </a>
            </div>
            <div class="c-appBtn">
                <a href="{{ route('twitter.tweetList') }}" class="c-appBtn--none">
                    ツイート予定一覧
                </a>
            </div>
            <div class="c-appBtn">
                <a href="{{ route('twitter.tweetHistory') }}" class="c-appBtn--none">
                    ツイート履歴
                </a>
            </div>
            <twitter-auto-tweet-action :user_id="{{ $user_id }}"
                :auth_screen_name="{{ json_encode($auth_account->screen_name) }}"
                :auto_tweet_flg="{{ $auth_account->auto_tweet_flg }}"></twitter-auto-tweet-action>
        </div>
    @endif
</aside>
