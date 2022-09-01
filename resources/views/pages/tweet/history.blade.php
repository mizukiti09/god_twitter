@section('title', 'God Twitter / tweetList')@extends('app') @section('content')

<div class="c-structure u-inner u-m__contents">
    <div class="c-currentPage__title">
        ツイート履歴 &nbsp;\&nbsp;<br class="u-sp_br"> 履歴数 &nbsp;:&nbsp; <span
            id="js-tweeted-count">{{ count($tweetedList) }}</span>
    </div>
    <div class="c-structure__container">
        @include('parts.aside')

        <article class="c-structure__article">
            <h1 class="c-structure__article__title"><i class="fas fa-adjust"></i>&nbsp;履歴ツイート一覧</h1>
            @if ($auth_account)
                <twitter-auto-tweet-history-action :user_id="{{ $user_id }}"
                    :auth_screen_name="{{ json_encode($auth_account->screen_name) }}"
                    :auto_tweet_flg="{{ $auth_account->auto_tweet_flg }}"
                    :tweeted_list="{{ json_encode($tweetedList) }}">
                </twitter-auto-tweet-history-action>
            @endif
        </article>
    </div>

</div>
@endsection
