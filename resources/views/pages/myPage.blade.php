@section('title', 'God Twitter / MyPage')@extends('app') @section('content')


<div class="c-structure u-inner u-m__contents">
    <div class="c-currentPage__title">
        MyPage &nbsp;\&nbsp; サービス登録アカウント {{ count($accounts) }}/10
    </div>
    <div class="c-structure__container">
        @include('parts.aside')

        <article class="c-structure__article">
            <h1 class="c-structure__article__title"><i class="fas fa-adjust"></i>&nbsp;活動アカウント</h1>
            <twitter-accounts :accounts="{{ json_encode($accounts) }}"></twitter-accounts>
        </article>
    </div>

</div>
@endsection
