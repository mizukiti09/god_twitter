@section('title', 'God Twitter / Document autoTweet')@extends('app') @section('content')
<div class="p-document u-inner u-m__contents">
    <h1 class="p-document__title">
        自動ツイート
    </h1>

    <div class="p-document__contents__item">
        <div class="p-document__contents__item__img u-device__pc">
            <img src="{{ asset('images/document/autoTweet_pc_01.png') }}" alt="">
        </div>

        <div class="p-document__contents__item__img u-device__sp">
            <img src="{{ asset('images/document/autoTweet_sp_01.png') }}" alt="">
        </div>

        <div class="p-document__contents__item__description">
            自動ツイートするbuttonをクリックすると、このようなモーダルウィンドが展開されます。<br>
            日時を選択し、ツイート内容を入力してください。<br>
            入力を終えたら登録buttonをクリックしてください。<br>
            登録された内容はツイート予定一覧ページに表示されます。<br>
            自動ツイート ON のbuttonをクリックすると指定した時間に自動ツイートがされます。
        </div>
    </div>

    <div class="p-document__contents__item">
        <div class="p-document__contents__item__img u-device__pc">
            <img src="{{ asset('images/document/autoTweet_pc_02.png') }}" alt="">
        </div>

        <div class="p-document__contents__item__img u-device__sp">
            <img src="{{ asset('images/document/autoTweet_sp_02.png') }}" alt="">
        </div>

        <div class="p-document__contents__item__description">
            ツイート予定一覧をクリックすると登録したツイートの一覧が表示されます。<br>
        </div>
    </div>

    <div class="p-document__contents__item">
        <div class="p-document__contents__item__img u-device__pc">
            <img src="{{ asset('images/document/autoTweet_pc_03.png') }}" alt="">
        </div>

        <div class="p-document__contents__item__img u-device__sp">
            <img src="{{ asset('images/document/autoTweet_sp_03.png') }}" alt="">
        </div>

        <div class="p-document__contents__item__description">
            指定した時間が現在時刻以前だとツイート予定が赤くなり、ツイートされないので<br>
            ツイート予定時刻を設定し直してください。
        </div>
    </div>

    <div class="p-document__contents__item">
        <div class="p-document__contents__item__img u-device__pc">
            <img src="{{ asset('images/document/autoTweet_pc_04.png') }}" alt="">
        </div>

        <div class="p-document__contents__item__img u-device__sp">
            <img src="{{ asset('images/document/autoTweet_sp_04.png') }}" alt="">
        </div>

        <div class="p-document__contents__item__description">
            ツイート履歴一覧をクリックすると自動ツイートが完了したツイートの一覧が表示されます。<br><br>
            ※履歴リセットbuttonをクリックするとこのページに表示されている履歴がリセットされます。
        </div>
    </div>
</div>
@endsection
