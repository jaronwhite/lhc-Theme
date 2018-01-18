(function ($) {
    (function eventSlider() {
        var $play = null;
        var $stopped = true;
        var $event = $(".event");
        var $ctrl = $('#controls span');
        var $eventCount = $event.length;
        var $eventIndexCount = $eventCount - 1;
        var $activeIndex = 0;

        function setActive(active) {
            $('.active').removeClass('active');
            $event.removeClass('prev next left right');
            $event.eq(active).addClass('active');
            $ctrl.eq(active).addClass('active');
            $event.eq(getIndex(active, -1)).addClass('prev left');
            $event.eq(getIndex(active, -2)).addClass('left');
            $event.eq(getIndex(active, +1)).addClass('next right');
            $event.eq(getIndex(active, +2)).addClass('right');
        }

        setActive($activeIndex);
        start();

        function start() {
            $stopped = false;
            $play = setInterval(function () {
                $activeIndex = getIndex($activeIndex, 1);
                setActive($activeIndex);
            }, (parseInt($config.duration) * 1000));
        }

        function stop() {
            $stopped = true;
            clearInterval($play);
        }

        function getIndex(active, direction) {
            var tmp = active + direction;
            if (tmp > $eventIndexCount) {
                return active - ($eventIndexCount - (direction - 1));
            } else if (tmp < 0) {
                return active + ($eventIndexCount + (direction + 1));
            } else {
                return tmp;
            }
        }

        // Circle control event
        $("#controls span").on("click", function () {
            stop();
            $activeIndex = $(this).index();
            setActive($activeIndex);
        });

        // Arrow control event
        $(".slider-arrow").on("click", function () {
            stop();
            var id = $(this).attr("id");
            if (id == "slider-right-arrow") {
                $activeIndex = getIndex($activeIndex, 1);
            } else if (id == "slider-left-arrow") {
                $activeIndex = getIndex($activeIndex, -1);
            }
            if ($config.restart)
                setTimeout(start, $config.duration);
            setActive($activeIndex);
        });
    })();

})(jQuery);