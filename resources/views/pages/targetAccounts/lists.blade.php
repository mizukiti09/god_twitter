@section('title', 'God Twitter / targetAccounts')@extends('app') @section('content')

<div class="c-structure u-inner u-m__contents">
    <div class="c-currentPage__title">
        ターゲットアカウント &nbsp;\&nbsp;<br class="u-sp_br"> 登録アカウント数 &nbsp;:&nbsp;<span
            id="js-target-account-count">{{ count($target_accounts) }}</span>
    </div>
    <div class="c-structure__container">
        @include('parts.aside')

        <article class="c-structure__article">
            <h1 class="c-structure__article__title"><i class="fas fa-adjust"></i>&nbsp;ターゲットアカウント一覧</h1>
            @if ($auth_account)
                <twitter-target-account-edit :user_id="{{ $user_id }}"
                    :auth_screen_name="{{ json_encode($auth_account->screen_name) }}"
                    :auto_follow_flg="{{ $auth_account->auto_follow_flg }}"
                    :target_accounts="{{ json_encode($target_accounts) }}" :target="{{ json_encode($target) }}">
                </twitter-target-account-edit>
            @endif
        </article>
    </div>

</div>
@endsection
