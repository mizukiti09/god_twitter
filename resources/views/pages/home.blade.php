@section('title', 'God Twitter / TOP')@extends('app') @section('content')
@include('parts.hero')
<div class="p-home__article">
    <div class="u-inner">
        <h1>
            <i class="fab fa-twitter-square"></i>
            Twitterの自動システム
        </h1>

        <div class="p-home__contents">
            <p>以下のシステムをご利用頂けます。</p>
            <ul class="p-home__ul">
                <li>自身が選んだ他アカウントのフォロワーを対象にした自動フォロー機能(Keywordの指定も可)</li>
                <li>自動フォローした中から15日間ツイートがない非アカウントや、1週間フォローが返ってこないアカウントを自動アンフォロー</li>
                <li>登録した特定のKeywordを基に自動いいね機能</li>
                <li>登録した特定のKeyword、日時を基に自動ツイート機能</li>
            </ul>

            <ul class="p-home__list">
                <li class="p-home__list__img">
                    <span class="p-home__list__img__text">自動フォロー登録画面</span>
                    <img src="{{ asset('images/follow.png') }}" alt="">
                </li>
                <li class="p-home__list__img">
                    <span class="p-home__list__img__text">自動アンフォロー登録画面</span>
                    <img src="{{ asset('images/un_follow.png') }}" alt="">
                </li>
                <li class="p-home__list__img">
                    <span class="p-home__list__img__text">自動いいね登録画面</span>
                    <img src="{{ asset('images/like.png') }}" alt="">
                </li>
                <li class="p-home__list__img">
                    <span class="p-home__list__img__text">自動ツイート登録画面</span>
                    <img src="{{ asset('images/tweet.png') }}" alt="">
                </li>
            </ul>
        </div>
    </div>
</div>
{{-- <div class="p-home c-section">
    <div class="p-home__wave">
        <p class="p-home__title">コンテンツが入ります。</p>
        <ul class="p-home__list">
            <li class="p-home__list__img"><img src="" alt=""></li>
            <li class="p-home__list__description">text.text.text.text.text.text.text.text.text.text.text.text.text.text.
                text.text.text.text.text.text.text.text.text.text.text.text.text.
                text.text.text.text.text.text.text.text.text.text.
                text.text.text.text.text.text.text.text.text.
            </li>
        </ul>
        <canvas id="waveCanvas"></canvas>
    </div>
    <div class="p-home__wave">
        <p class="p-home__title">コンテンツが入ります。</p>
        <ul class="p-home__list p-home__list__even">
            <li class="p-home__list__img"><img src="" alt=""></li>
            <li class="p-home__list__description">text.text.text.text.text.text.text.text.text.text.text.text.text.text.
                text.text.text.text.text.text.text.text.text.text.text.text.text.
                text.text.text.text.text.text.text.text.text.text.
                text.text.text.text.text.text.text.text.text.
            </li>
        </ul>
        <canvas id="waveCanvas2"></canvas>
    </div>
    <div class="p-home__wave">
        <p class="p-home__title">コンテンツが入ります。</p>
        <ul class="p-home__list">
            <li class="p-home__list__img"><img src="" alt=""></li>
            <li class="p-home__list__description">text.text.text.text.text.text.text.text.text.text.text.text.text.text.
                text.text.text.text.text.text.text.text.text.text.text.text.text.
                text.text.text.text.text.text.text.text.text.text.
                text.text.text.text.text.text.text.text.text.
            </li>
        </ul>
        <canvas id="waveCanvas3"></canvas>
    </div> --}}
@endsection
