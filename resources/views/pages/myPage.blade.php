@section('title', 'God Twitter / MyPage')@extends('app') @section('content')
<div class="p-myPage u-inner u-m__contents">
    <div class="c-currentPage__title">
        MyPage &nbsp;\&nbsp;
    </div>
    <div class="p-myPage__container">
        <aside class="p-myPage__aside">
            <div class="p-myPage__aside__item">
                <div class="p-myPage__aside__item__head">認証アカウント</div>
                <div class="p-myPage__aside__item__info">@gori_tiki</div>
            </div>
            <div class="p-myPage__aside__item">
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
            <div class="p-myPage__article__panel__group">
                <div class="p-myPage__article__panel p-myPage__article__panel--active">
                    <div class="p-myPage__article__panel--active p-myPage__article__panel__status">稼働中</div>
                    <div class="p-myPage__article__panel__upDatedAt">更新日時: 2022/06/28&nbsp;00:00:00</div>
                    <div class="p-myPage__article__panel__leftInfo">
                        <div class="p-myPage__article__panel__photo">
                            <img src="{{ asset('images/maeda.jpg') }}" alt="twitter photo img">
                        </div>
                    </div>
                    <div class="p-myPage__article__panel__rightInfo">
                        <div class="p-myPage__article__panel__name p-myPage__article__panel__item">
                            <p class="p-myPage__article__panel__head">アカウント名</p>
                            <p class="p-myPage__article__panel__info">gori_tiki</p>
                        </div>
                        <div class="p-myPage__article__panel__follow p-myPage__article__panel__item">
                            <p class="p-myPage__article__panel__head">フォロー数</p>
                            <p class="p-myPage__article__panel__info">112</p>
                        </div>
                        <div class="p-myPage__article__panel__follower p-myPage__article__panel__item">
                            <p class="p-myPage__article__panel__head">フォロワー数</p>
                            <p class="p-myPage__article__panel__info">345</p>
                        </div>
                        <div class="p-myPage__article__panel__follower p-myPage__article__panel__item">
                            <p class="p-myPage__article__panel__head">本日の自動フォローした数</p>
                            <p class="p-myPage__article__panel__info">35</p>
                        </div>
                        <div class="p-myPage__article__panel__follower p-myPage__article__panel__item">
                            <p class="p-myPage__article__panel__head">本日のフォローされた数</p>
                            <p class="p-myPage__article__panel__info">60</p>
                        </div>
                        <div class="p-myPage__article__panel__follower p-myPage__article__panel__item">
                            <p class="p-myPage__article__panel__head">本日の自動アンフォロー数</p>
                            <p class="p-myPage__article__panel__info">13</p>
                        </div>
                        <div class="p-myPage__article__panel__follower p-myPage__article__panel__item">
                            <p class="p-myPage__article__panel__head">本日の自動いいねした数</p>
                            <p class="p-myPage__article__panel__info">100</p>
                        </div>
                        <div class="p-myPage__article__panel__follower p-myPage__article__panel__item">
                            <p class="p-myPage__article__panel__head">本日のいいねされた数</p>
                            <p class="p-myPage__article__panel__info">46</p>
                        </div>
                        <div class="p-myPage__article__panel__follower p-myPage__article__panel__item">
                            <p class="p-myPage__article__panel__head">本日の自動ツイート数</p>
                            <p class="p-myPage__article__panel__info">30</p>
                        </div>
                    </div>
                </div>
                <div class="p-myPage__article__panel">
                    <div class="p-myPage__article__panel__leftInfo">
                        <div class="p-myPage__article__panel__photo">
                            <img src="" alt="twitter photo img">
                        </div>
                    </div>
                    <div class="p-myPage__article__panel__rightInfo">
                        <div class="p-myPage__article__panel__name p-myPage__article__panel__item">
                            <p class="p-myPage__article__panel__head">アカウント名</p>
                            <p class="p-myPage__article__panel__info">ゴリチキ</p>
                        </div>
                        <div class="p-myPage__article__panel__follow p-myPage__article__panel__item">
                            <p class="p-myPage__article__panel__head">フォロー数</p>
                            <p class="p-myPage__article__panel__info">112</p>
                        </div>
                        <div class="p-myPage__article__panel__follower p-myPage__article__panel__item">
                            <p class="p-myPage__article__panel__head">フォロワー数</p>
                            <p class="p-myPage__article__panel__info">345</p>
                        </div>
                        <div class="p-myPage__article__panel__follower p-myPage__article__panel__item">
                            <p class="p-myPage__article__panel__head">本日のフォローした数</p>
                            <p class="p-myPage__article__panel__info">35</p>
                        </div>
                        <div class="p-myPage__article__panel__follower p-myPage__article__panel__item">
                            <p class="p-myPage__article__panel__head">本日のフォローされた数</p>
                            <p class="p-myPage__article__panel__info">60</p>
                        </div>
                        <div class="p-myPage__article__panel__follower p-myPage__article__panel__item">
                            <p class="p-myPage__article__panel__head">本日のアンフォロー数</p>
                            <p class="p-myPage__article__panel__info">13</p>
                        </div>
                        <div class="p-myPage__article__panel__follower p-myPage__article__panel__item">
                            <p class="p-myPage__article__panel__head">本日のいいねした数</p>
                            <p class="p-myPage__article__panel__info">100</p>
                        </div>
                        <div class="p-myPage__article__panel__follower p-myPage__article__panel__item">
                            <p class="p-myPage__article__panel__head">本日のいいねされた数</p>
                            <p class="p-myPage__article__panel__info">46</p>
                        </div>
                        <div class="p-myPage__article__panel__follower p-myPage__article__panel__item">
                            <p class="p-myPage__article__panel__head">本日のツイート数</p>
                            <p class="p-myPage__article__panel__info">30</p>
                        </div>
                    </div>
                </div>
                <div class="p-myPage__article__panel">
                    <div class="p-myPage__article__panel__leftInfo">
                        <div class="p-myPage__article__panel__photo">
                            <img src="" alt="twitter photo img">
                        </div>
                    </div>
                    <div class="p-myPage__article__panel__rightInfo">
                        <div class="p-myPage__article__panel__name p-myPage__article__panel__item">
                            <p class="p-myPage__article__panel__head">アカウント名</p>
                            <p class="p-myPage__article__panel__info">ゴリチキ</p>
                        </div>
                        <div class="p-myPage__article__panel__follow p-myPage__article__panel__item">
                            <p class="p-myPage__article__panel__head">フォロー数</p>
                            <p class="p-myPage__article__panel__info">112</p>
                        </div>
                        <div class="p-myPage__article__panel__follower p-myPage__article__panel__item">
                            <p class="p-myPage__article__panel__head">フォロワー数</p>
                            <p class="p-myPage__article__panel__info">345</p>
                        </div>
                        <div class="p-myPage__article__panel__follower p-myPage__article__panel__item">
                            <p class="p-myPage__article__panel__head">本日のフォローした数</p>
                            <p class="p-myPage__article__panel__info">35</p>
                        </div>
                        <div class="p-myPage__article__panel__follower p-myPage__article__panel__item">
                            <p class="p-myPage__article__panel__head">本日のフォローされた数</p>
                            <p class="p-myPage__article__panel__info">60</p>
                        </div>
                        <div class="p-myPage__article__panel__follower p-myPage__article__panel__item">
                            <p class="p-myPage__article__panel__head">本日のアンフォロー数</p>
                            <p class="p-myPage__article__panel__info">13</p>
                        </div>
                        <div class="p-myPage__article__panel__follower p-myPage__article__panel__item">
                            <p class="p-myPage__article__panel__head">本日のいいねした数</p>
                            <p class="p-myPage__article__panel__info">100</p>
                        </div>
                        <div class="p-myPage__article__panel__follower p-myPage__article__panel__item">
                            <p class="p-myPage__article__panel__head">本日のいいねされた数</p>
                            <p class="p-myPage__article__panel__info">46</p>
                        </div>
                        <div class="p-myPage__article__panel__follower p-myPage__article__panel__item">
                            <p class="p-myPage__article__panel__head">本日のツイート数</p>
                            <p class="p-myPage__article__panel__info">30</p>
                        </div>
                    </div>
                </div>
                <div class="p-myPage__article__panel">
                    <div class="p-myPage__article__panel__leftInfo">
                        <div class="p-myPage__article__panel__photo">
                            <img src="" alt="twitter photo img">
                        </div>
                    </div>
                    <div class="p-myPage__article__panel__rightInfo">
                        <div class="p-myPage__article__panel__name p-myPage__article__panel__item">
                            <p class="p-myPage__article__panel__head">アカウント名</p>
                            <p class="p-myPage__article__panel__info">ゴリチキ</p>
                        </div>
                        <div class="p-myPage__article__panel__follow p-myPage__article__panel__item">
                            <p class="p-myPage__article__panel__head">フォロー数</p>
                            <p class="p-myPage__article__panel__info">112</p>
                        </div>
                        <div class="p-myPage__article__panel__follower p-myPage__article__panel__item">
                            <p class="p-myPage__article__panel__head">フォロワー数</p>
                            <p class="p-myPage__article__panel__info">345</p>
                        </div>
                        <div class="p-myPage__article__panel__follower p-myPage__article__panel__item">
                            <p class="p-myPage__article__panel__head">本日のフォローした数</p>
                            <p class="p-myPage__article__panel__info">35</p>
                        </div>
                        <div class="p-myPage__article__panel__follower p-myPage__article__panel__item">
                            <p class="p-myPage__article__panel__head">本日のフォローされた数</p>
                            <p class="p-myPage__article__panel__info">60</p>
                        </div>
                        <div class="p-myPage__article__panel__follower p-myPage__article__panel__item">
                            <p class="p-myPage__article__panel__head">本日のアンフォロー数</p>
                            <p class="p-myPage__article__panel__info">13</p>
                        </div>
                        <div class="p-myPage__article__panel__follower p-myPage__article__panel__item">
                            <p class="p-myPage__article__panel__head">本日のいいねした数</p>
                            <p class="p-myPage__article__panel__info">100</p>
                        </div>
                        <div class="p-myPage__article__panel__follower p-myPage__article__panel__item">
                            <p class="p-myPage__article__panel__head">本日のいいねされた数</p>
                            <p class="p-myPage__article__panel__info">46</p>
                        </div>
                        <div class="p-myPage__article__panel__follower p-myPage__article__panel__item">
                            <p class="p-myPage__article__panel__head">本日のツイート数</p>
                            <p class="p-myPage__article__panel__info">30</p>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </div>
    
</div>
@endsection