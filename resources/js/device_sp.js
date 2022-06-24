// ハンバーガーメニュ 
$(".c-btn-open").on('click', function() {
    $(this).toggleClass('active');
    $(".c-nav").toggleClass('panelactive');
});

$(".c-nav a").on('click', function() {
    $(".c-btn-open").removeClass('active');
    $(".c-nav").removeClass('panelactive');
});