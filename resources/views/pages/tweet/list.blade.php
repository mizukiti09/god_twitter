@section('title', 'God Twitter / tweetList')@extends('app') @section('content')

<div class="c-structure u-inner u-m__contents">
    <div class="c-currentPage__title">
        TweetList &nbsp;\&nbsp; 登録ツイート数 &nbsp;:&nbsp; <span id="js-tweet-count">{{ count($tweetList) }}</span>
    </div>
    <div class="c-structure__container">
        @include('parts.aside')
        
        <article class="c-structure__article">
            <h1 class="c-structure__article__title"><i class="fas fa-adjust"></i>&nbsp;予定一覧</h1>
            @if ($accounts) 
                @foreach ($accounts as $account) 
                    @if ($account->auth_flg == 1) 
                        <twitter-auto-tweet-edit-action
                        :user_id="{{$user_id}}"
                        :auth_screen_name="{{json_encode($account->screen_name)}}"
                        :auto_tweet_flg="{{$account->auto_tweet_flg}}"
                        :tweet_list="{{json_encode($tweetList)}}"
                        ></twitter-auto-tweet-edit-action>
                    @endif
                @endforeach
            @endif
        </article>
    </div>
    
</div>
@endsection