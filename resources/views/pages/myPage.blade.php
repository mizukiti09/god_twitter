@section('title', 'God Twitter / MyPage')@extends('app') @section('content')

<div class="p-myPage u-inner u-m__contents">
    <div class="c-currentPage__title">
        MyPage &nbsp;\&nbsp; サービス登録アカウント {{count($accounts)}}/10
    </div>
    <div class="p-myPage__container">
        <aside class="p-myPage__aside">
            <div class="p-myPage__aside__item">
                <div class="p-myPage__aside__item__head">認証アカウント</div>
                <div class="p-myPage__aside__item__info">
                    @foreach ($accounts as $account)
                        @if ($account->auth_flg)
                            {{ $account->screen_name }}
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="p-myPage__aside__item">
                <div class="p-myPage__aside__item__attention">*最大10件まで登録可能です。</div>
                <div class="p-myPage__aside__item__head">Twitter認証モード</div>
                <div class="c-appBtn"><a class="c-appBtn--auth" href="{{ route('login.twitter') }}">アカウント認証</a></div>
                <div class="c-appBtn"><a class="c-appBtn--logout" href="{{ route('logout.twitter') }}" target="_blank">ログアウト</a></div>
            </div>
            <div class="p-myPage__aside__item">
                <div class="p-myPage__aside__item__head">自動モード</div>
                <div class="c-appBtn"><a class="c-appBtn--auto" href="{{ route('logout.twitter') }}" target="_blank">自動フォロー中</a></div>
                <div class="c-appBtn"><a class="c-appBtn--auto" href="{{ route('logout.twitter') }}" target="_blank">自動アンフォロー中</a></div>
                <div class="c-appBtn"><a class="c-appBtn--auto" href="{{ route('logout.twitter') }}" target="_blank">自動いいね中</a></div>
                <div class="c-appBtn"><a class="c-appBtn--none" href="{{ route('logout.twitter') }}" target="_blank">自動ツイート</a></div>
            </div>
        </aside>
        <article class="p-myPage__article">
            <h1 class="p-myPage__article__title"><i class="fas fa-adjust"></i>&nbsp;活動項目</h1>
            
            <twitter-accounts
            :accounts="{{ json_encode($accounts) }}"
            ></twitter-accounts>
            
        </article>
    </div>
    
</div>
@endsection