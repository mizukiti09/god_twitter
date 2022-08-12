@section('title', 'God Twitter / TOP')@extends('app') @section('content')
@include('parts.hero')
<div class="p-home__article">
    <div class="u-inner">
        <h1>
            <i class="fab fa-twitter-square"></i>
            Auto Services
        </h1>

        <div class="p-home__contents">
            <p>以下のシステムをご利用頂けます。</p>
            <ul class="p-home__ul">
                <li>自身が選んだ他アカウントのフォロワーを対象にした自動フォロー機能(Keywordの指定も可)</li>
                <li>自動フォローした中から15日間ツイートがない非アカウントや、1週間フォローが返ってこないアカウントを自動アンフォロー</li>
                <li>登録した特定のKeywordを基に自動いいね機能</li>
                <li>登録した特定のKeyword、日時を基に自動ツイート機能</li>
            </ul>

            <h1>
                <i class="fas fa-book"></i>
                Document Links
            </h1>
            <ul class="p-home__list">
                <li class="p-home__list__img p-home__list__img--start">
                    <span class="p-home__list__img__text">最初にやること</span>
                    <a href="{{ route('document.first') }}">
                        First Start
                    </a>
                </li>
                <li class="p-home__list__img p-home__list__img--follow">
                    <span class="p-home__list__img__text">自動フォロー、自動アンフォロー</span>
                    <a href="{{ route('document.autoFollow') }}">
                        Auto Follow
                    </a>
                </li>
                <li class="p-home__list__img p-home__list__img--like ">
                    <span class="p-home__list__img__text">自動いいね</span>
                    <a href="{{ route('document.autoLike') }}">
                        Auto Like
                    </a>
                </li>
                <li class="p-home__list__img p-home__list__img--tweet">
                    <span class="p-home__list__img__text">自動ツイート</span>
                    <a href="{{ route('document.autoTweet') }}">
                        Auto Tweet
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection
