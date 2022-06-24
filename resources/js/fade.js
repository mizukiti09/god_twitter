$(window).on('scroll', function() {
    $('.u-fade-up , .u-fade-down , .u-fade-right').each(function() {
        //ターゲットの位置を取得
        var target = $(this).offset().top;
        //スクロール量を取得
        var scroll = $(window).scrollTop();
        //ウィンド高さ
        var height = $(window).height();

        var point = target - height; // 画面下部からのターゲットの位置


        //ターゲットまでスクロールするとフェードインする
        if (scroll > point && scroll < (point + $(this).height() + height)) {
            //クラスを付与
            $(this).addClass('active');
        } else {
            $(this).removeClass('active');
        }
    });
});