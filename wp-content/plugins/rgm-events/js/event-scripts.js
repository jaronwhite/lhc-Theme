(function ($) {
    (function eventSlider() {
        var $play = null;
        var $stopped = true;
        var $event = $(".event");
        var $ctrl = $("#controls span");
        var $eventCount = $event.length;
        var $eventIndexCount = $eventCount - 1;
        var $activeIndex = 0;

        start();

        function start() {
            $stopped = false;
            $play = setInterval(function () {
                $activeIndex = getIndex($activeIndex, 1);
                setActive($activeIndex);
            }, 6000);
        }

        function stop() {
            $stopped = true;
            clearInterval($play);
        }

        function setActive(active) {
            $(".active").removeClass("active");
            $event.eq(active).addClass("active");
            $ctrl.eq(active).addClass("active");
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
    })();
})(jQuery);
