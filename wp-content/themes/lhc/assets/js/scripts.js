(function ($) {
    var rgm = "#######   ###### ##   ##\n" +
        "      ## ###     ### ###\n" +
        " ######  ###  ## #######\n" +
        " ##  ##  ###  ## ## # ##\n" +
        " ##   ##  ###### ##   ##\n" +
        " ##                   ##\n" +
        " ##--RainGauge Media--##\n";
    console.log(rgm);

    var hdr = $("#header");
    var prm = $("#primary");
    var pul = $("#primary ul");
    $("#menu-toggle-wrap").on("click", function () {
        prm.toggleClass("open");
    });


    $(window).on('scroll', function () {
        var h = hdr.height();
        if ($(window).scrollTop() >= h) {
            prm.addClass("stuck");
        } else {
            prm.removeClass("stuck");
        }
    });

    $(window).on('resize', function () {
        if ($(window).width() > 768 && prm.hasClass("open")) {
            prm.removeClass("open");
        }
    });


})(jQuery);