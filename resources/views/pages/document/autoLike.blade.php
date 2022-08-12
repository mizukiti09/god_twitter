@section('title', 'God Twitter / Document autoLike')@extends('app') @section('content')
<div class="p-document u-inner u-m__contents">
    <h1 class="p-document__title">
        Auto Like Document
    </h1>

    <div class="p-document__contents__item">
        <div class="p-document__contents__item__img u-device__pc">
            <img src="{{ asset('images/document/autoLike_pc_01.png') }}" alt="">
        </div>

        <div class="p-document__contents__item__img u-device__sp">
            <img src="{{ asset('images/document/autoLike_sp_01.png') }}" alt="">
        </div>

        <div class="p-document__contents__item__description">
            自動いいねするbuttonをクリックすると、このようなモーダルウィンドが展開されます。
            Keywordを入力して追加buttonをクリックすると入力したKeywordが下に表示されます。
            Keywordを設定して更新buttonをクリックするとデータベースに設定が反映されます。
            データベースに反映されたKeywordを基にそのKeywordが入ったツイートを探します。<br><br><br><br>
            ※Keywordの設定の仕方は下記3種類になります。<br><br>
        </div>
    </div>

    <div class="p-document__contents__item">
        <div class="p-document__contents__item__img u-device__pc">
            <img src="{{ asset('images/document/autoLike_pc_02.png') }}" alt="">
        </div>

        <div class="p-document__contents__item__img u-device__sp">
            <img src="{{ asset('images/document/autoLike_sp_02.png') }}" alt="">
        </div>

        <div class="p-document__contents__item__description">
            ・1つだけのKeywordが含まれているツイートをいいねしたい場合は NOT を選択してください。<br>
        </div>
    </div>

    <div class="p-document__contents__item">
        <div class="p-document__contents__item__img u-device__pc">
            <img src="{{ asset('images/document/autoLike_pc_03.png') }}" alt="">
        </div>

        <div class="p-document__contents__item__img u-device__sp">
            <img src="{{ asset('images/document/autoLike_sp_03.png') }}" alt="">
        </div>

        <div class="p-document__contents__item__description">
            ・2つ以上Keywordが設定されていて、全部のKeywordが含まれているツイートをいいねしたい場合は AND を選択してください。<br>
        </div>
    </div>

    <div class="p-document__contents__item">
        <div class="p-document__contents__item__img u-device__pc">
            <img src="{{ asset('images/document/autoLike_pc_04.png') }}" alt="">
        </div>

        <div class="p-document__contents__item__img u-device__sp">
            <img src="{{ asset('images/document/autoLike_sp_04.png') }}" alt="">
        </div>

        <div class="p-document__contents__item__description">
            ・2つ以上Keywordが設定されていて、どれか1つでも設定したKeywordが含まれているツイートをいいねしたい場合は OR を選択してください。<br>

            <br><br><br><br>
            Keywordを更新した後に自動いいねONをクリックすると自動いいねが始まります。
        </div>
    </div>
</div>
@endsection
