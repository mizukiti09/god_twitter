@section('title', 'God Twitter / Document autoFollow')@extends('app') @section('content')
<div class="p-document u-inner u-m__contents">


    <div class="p-document__contents">
        <h1 class="p-document__title">
            Auto Follow Document <br>(自動フォロー)
        </h1>
        <div class="p-document__contents__item">
            <div class="p-document__contents__item__img u-device__pc">
                <img src="{{ asset('images/document/autoFollow_pc_01.png') }}" alt="">
            </div>

            <div class="p-document__contents__item__img u-device__sp">
                <img src="{{ asset('images/document/autoFollow_sp_01.png') }}" alt="">
            </div>
            <div class="p-document__contents__item__description">

                <p>
                    TargetAccount登録 buttonをクリックするとこのようなモーダルウィンドが展開されます。
                    @を抜いたTwitterアカウント名を正しく入力してターゲットアカウントリストに登録を行います。
                </p>
            </div>
        </div>

        <div class="p-document__contents__item">
            <div class="p-document__contents__item__img u-device__pc">
                <img src="{{ asset('images/document/autoFollow_pc_02.png') }}" alt="">
            </div>

            <div class="p-document__contents__item__img u-device__sp">
                <img src="{{ asset('images/document/autoFollow_sp_02.png') }}" alt="">
            </div>

            <div class="p-document__contents__item__description">
                <p>
                    登録ができたら現在のTargetAccount一覧 か TargetAccounts をクリックすると、登録したターゲットアカウント一覧が表示されます。
                    登録ターゲットアカウントが一つ以上あると、Followモード(自動フォロー、自動アンフォロー)が展開されます。
                </p>
            </div>
        </div>

        <div class="p-document__contents__item">
            <div class="p-document__contents__item__img u-device__pc">
                <img src="{{ asset('images/document/autoFollow_pc_04.png') }}" alt="">
            </div>

            <div class="p-document__contents__item__img u-device__sp">
                <img src="{{ asset('images/document/autoFollow_sp_04.png') }}" alt="">
            </div>

            <div class="p-document__contents__item__description">
                自動フォローするbuttonをクリックすると、このようなモーダルウィンドが展開されます。
                Keywordを入力して追加buttonをクリックすると入力したKeywordが下に表示されます。
                Keywordを設定して更新buttonをクリックするとデータベースに設定が反映されます。
                データベースに反映されたKeywordを基にTwitterアカウントのプロフィールにそのKeywordが入ったアカウントを
                ターゲットアカウントのフォロワーから探します。<br><br><br><br>
                ※Keywordの設定の仕方は下記3種類になります。<br><br>
            </div>
        </div>

        <div class="p-document__contents__item">
            <div class="p-document__contents__item__img u-device__pc">
                <img src="{{ asset('images/document/autoFollow_pc_03.png') }}" alt="">
            </div>

            <div class="p-document__contents__item__img u-device__sp">
                <img src="{{ asset('images/document/autoFollow_sp_03.png') }}" alt="">
            </div>

            <div class="p-document__contents__item__description">
                ・1つだけのKeywordがプロフィールに含まれているアカウントをフォローしたい場合は NOT を選択してください。<br>
            </div>
        </div>

        <div class="p-document__contents__item">
            <div class="p-document__contents__item__img u-device__pc">
                <img src="{{ asset('images/document/autoFollow_pc_06.png') }}" alt="">
            </div>

            <div class="p-document__contents__item__img u-device__sp">
                <img src="{{ asset('images/document/autoFollow_sp_06.png') }}" alt="">
            </div>

            <div class="p-document__contents__item__description">
                ・2つ以上Keywordが設定されていて、全部のKeywordがプロフィールに含まれているアカウントをフォローしたい場合は AND を選択してください。<br>
            </div>
        </div>

        <div class="p-document__contents__item">
            <div class="p-document__contents__item__img u-device__pc">
                <img src="{{ asset('images/document/autoFollow_pc_07.png') }}" alt="">
            </div>

            <div class="p-document__contents__item__img u-device__sp">
                <img src="{{ asset('images/document/autoFollow_sp_07.png') }}" alt="">
            </div>

            <div class="p-document__contents__item__description">
                ・2つ以上Keywordが設定されていて、どれか1つでも設定したKeywordがプロフィールに含まれているアカウントをフォローしたい場合は OR を選択してください。<br>

                <br><br><br><br>
                Keywordを更新した後に自動フォローONをクリックすると自動フォローが始まります。
            </div>
        </div>
    </div><br><br>


    <div class="p-document__contents">
        <h1 class="p-document__title">
            Auto Follow Document <br>(自動アンフォロー)
        </h1>
        <div class="p-document__contents__item">
            <div class="p-document__contents__item__img u-device__pc">
                <img src="{{ asset('images/document/autoUnFollow_pc_01.png') }}" alt="">
            </div>

            <div class="p-document__contents__item__img u-device__sp">
                <img src="{{ asset('images/document/autoUnFollow_sp_01.png') }}" alt="">
            </div>

            <div class="p-document__contents__item__description">
                自動アンフォローbuttonをクリックすると、このようなモーダルウィンドが展開されます。<br>
                自動アンフォロー ON をクリックすると、自動フォローモードでフォローした日時から7日以上経過していてフォローバックがないアカウント<br>
                又は非アクティブユーザー（15日投稿なし)を自動アンフォローします。
            </div>
        </div>
    </div>
</div>
@endsection
