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

    /**
     * Trigger the menu toggle on a click event
     */
    $("#menu-toggle-wrap").on("click", function () {
        prm.toggleClass("open");
    });

    /**
     * Scroll event to fix nav to top as scrolled down
     */
    $(window).on('scroll', function () {
        var h = hdr.height();
        if ($(window).scrollTop() >= h) {
            prm.addClass("stuck");
        } else {
            prm.removeClass("stuck");
        }
    });

    /**
     * Resize event that closes the menu if it is open when resized larger than landscape ipad
     */
    $(window).on('resize', function () {
        if ($(window).width() > 1026 && prm.hasClass("open")) {
            prm.removeClass("open");
        }
    });


})(jQuery);