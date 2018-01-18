(function ($) {
    var rgm =
        "\n\n#######   ###### ##   ##\n" +
        "      ## ###     ### ###\n" +
        " ######  ###  ## #######\n" +
        " ##  ##  ###  ## ## # ##\n" +
        " ##   ##  ###### ##   ##\n" +
        " ##                   ##\n" +
        " ##--RainGauge Media--##\n\n";
    console.log(rgm);

    console.log($(window).width());
    if ($(window).width() > 1024) {
        $("#primary").addClass("desktop");
    }
    var hdr = $("#header");
    var prm = $("#primary");
    var menuItem = $("#primary ul#primary-ul li.menu-item");
    var subUl = $("#primary.desktop ul#primary-ul > li.menu-item-has-children");
    var subOpen = {open: false, id: null};
    var toggled = false;

    $("#wp-custom-header-video-button.wp-custom-header-video-button.wp-custom-header-video-play").html("<span></span><span></span>");


    $("#menu-toggle-wrap").on("click", function () {
        prm.toggleClass("open");
        if (toggled) {
            prm.toggleClass("closed");
        }
        menuItem.slideToggle();
        toggled = true;
    });

    subUl.on("mouseenter", function () {
        console.clear();
        console.log($(this).attr("id"));
        var diffListItem = subOpen.id !== $(this).attr("id");

        if (!subOpen.open || diffListItem) {
            if (diffListItem) {
                $(
                    "#primary.desktop ul#primary-ul li#" + subOpen.id + " ul.sub-menu"
                ).fadeOut(100);
            }
            $(
                "#primary.desktop ul#primary-ul li#" +
                $(this).attr("id") +
                " ul.sub-menu"
            )
                .fadeIn(500)
                .css("display", "flex");
        }
        subOpen.open = true;
        subOpen.id = $(this).attr("id");
    });
    subUl.on("mouseleave", function () {
        subOpen.open = false;
        setTimeout(function () {
            if (!subOpen.open) {
                $("#primary.desktop ul#primary-ul li ul.sub-menu").fadeOut(500);
            }
        }, 1000);
    });

    $(window).on("resize", function () {
        var w = hdr.width();
        if ($(window).width() > 1024) {
            $("#primary").addClass("desktop");
            if ($("#primary.closed").length) {
                $("#primary")
                    .removeClass("closed")
                    .addClass("open");
                menuItem.slideDown();
            }
        } else {
            $("#primary.desktop").removeClass("desktop");
        }
    });
    $(window).on("scroll", function () {
        var h = hdr.height();

        if (($(window).scrollTop() >= h)) {
            prm.addClass("stuck");
        } else {
            prm.removeClass("stuck");
        }
    });
})(jQuery);
