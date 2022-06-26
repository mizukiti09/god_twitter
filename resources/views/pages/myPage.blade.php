@section('title', 'God Twitter / MyPage')@extends('app') @section('content')
<div class="p-myPage u-inner u-m__contents">
    <div class="c-currentPage__title">
        MyPage &nbsp;\&nbsp;<div class="button04"><a href="">Twitterアカウント登録</a></div>
    </div>
    <div class="p-myPage__container">
        <aside class="p-myPage__aside">
            <div class="p-myPage__aside__item">
                <div class="p-myPage__aside__item__head">Guest</div>
                <div class="p-myPage__aside__item__info">金子銀二</div>
            </div>
            <div class="p-myPage__aside__item">
                <div class="p-myPage__aside__item__head">today Likes</div>
                <div class="p-myPage__aside__item__info">123</div>
            </div>
        </aside>
        <article class="p-myPage__article">
            <h1 class="p-myPage__article__title"><i class="fas fa-adjust"></i>&nbsp;活動項目</h1>
            <div class="p-myPage__article__panel__group">
                <div class="p-myPage__article__panel"></div>
                <div class="p-myPage__article__panel"></div>
                <div class="p-myPage__article__panel"></div>
                <div class="p-myPage__article__panel"></div>
            </div>
        </article>
    </div>
    
</div>
@endsection