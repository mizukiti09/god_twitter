@section('title', 'God Twitter / MyPage')@extends('app') @section('content')

<div class="p-myPage u-inner u-m__contents">
    <div class="c-currentPage__title">
        MyPage &nbsp;\&nbsp; サービス登録アカウント {{count($accounts)}}/10
    </div>
    <div class="p-myPage__container">
        @include('parts.aside')
        
        <article class="p-myPage__article">
            <h1 class="p-myPage__article__title"><i class="fas fa-adjust"></i>&nbsp;活動項目</h1>
            <twitter-accounts
            :accounts="{{ json_encode($accounts) }}"
            ></twitter-accounts>
        </article>
    </div>
    
</div>
@endsection