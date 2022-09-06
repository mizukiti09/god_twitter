@section('title', 'God Twitter / Document first')@extends('app') @section('content')
<div class="p-document u-inner u-m__contents">
    <h1 class="p-document__title">
        Twitter認証
    </h1>
    <div class="p-document__contents">
        <div class="p-document__contents__item">
            <div class="p-document__contents__item__img">
                <img class="u-device__pc" src="{{ asset('images/document/first_pc_01.png') }}" alt="">
                <img class="u-device__sp" src="{{ asset('images/document/first_sp_01.png') }}" alt="">
            </div>
            <div class="p-document__contents__item__description">

                <p>
                    Twitter認証のアカウント認証buttonをクリックし、Twitterログインします。
                </p>
            </div>
        </div>

        <div class="p-document__contents__item">
            <div class="p-document__contents__item__img u-device__pc">
                <img src="{{ asset('images/document/first_pc_02.png') }}" alt="">
            </div>

            <div class="p-document__contents__item__img u-device__sp u-m-b-12">
                <img src="{{ asset('images/document/first_sp_02.png') }}" alt="">
            </div>
            <div class="p-document__contents__item__img u-device__sp">
                <img src="{{ asset('images/document/first_sp_021.png') }}" alt="">
            </div>

            <div class="p-document__contents__item__description">
                <p>
                    Twitterログイン完了後の表示画面はこのようになります。
                    ログイン完了後に自動システム機能をご利用できます。
                </p>
            </div>
        </div>

    </div>
</div>
@endsection
