(function ($) {
    console.log('Event Admin by RainGaugeMedia');

    //DOM Elements
    let $idInput = $('#event-id-input'),
        $titleInput = $('#event-title-input'),
        $descInput = $('#event-description-input'),
        $monthSelect = $('#event-month-input'),
        $dayInput = $('#event-day-input'),
        $yearInput = $('#event-year-input'),
        $hourInput = $('#event-hour-input'),
        $minuteInput = $('#event-minute-input'),
        $meridiemInput = $('#event-meridiem-input'),
        $bgColorInput = $('#event-background-color'),
        $textColorInput = $('#event-detail-color'),
        $imageInput = $('#event-background-image'),
        $saveButton = $('#event-edit-save');

    let $activeIndex = 0,
        $newEvent = false,
        $event = events[$activeIndex],
        $date = stringToDate($event.dateTime);

    //Select event by event list table row
    $('.events-list-table-row').on('click', function () {
        $('.events-list-table-row.active').removeClass('active');
        $(this).addClass('active');
        $activeIndex = ($(this).index() - 1); //Subtracting 1 to include table header row
        $event = events[$activeIndex];
        $date = stringToDate($event.dateTime);
        $("#event-slide-preview").css('background-color', $event.bgColor);
        $("#event-detail-image").css({
            'background-color': $event.bgColor,
            'background-image': 'url(' + $event.image + ')',
            'background-position': 'center',
            'background-repeat': 'no-repeat',
            'background-size': 'cover'
        });
        $(".event-detail").css('color', $event.textColor);
        $("#event-detail-title").text($event.title);
        $("#event-detail-desc").text($event.description);
        $("#event-detail-dateTime").text($date.format('MMMM dd, yyyy @ hh:mm AA '));
        $idInput.val($event.id);
    });

    //Fill form values on save or edit event
    $('.event-open-form').on('click', function (e) {
        $('#event-edit-table').fadeIn();
        $('#events-admin-page-wrap-foreground').fadeIn();

        if (e.target.id === 'event-create-button') {
            $newEvent = true;
            $saveButton.attr('name', 'event-create-mode');
            let $tempDate = new Date();
            $idInput.val('es' + Date.now());
            $titleInput.val('');
            $descInput.val('');
            $('#event-month-input option[value="' + $tempDate.getMonth() + '"]').attr('selected', 'selected');
            $dayInput.val($tempDate.format('dd'));
            $yearInput.val($tempDate.format('yyyy'));
            $hourInput.val(5);
            $minuteInput.val(30);
            $meridiemInput.val('PM');
            $bgColorInput.iris('color', '#2f4f4f');
            $textColorInput.iris('color', '#ffffff');
            $imageInput.val('');
        } else if (e.target.id === 'event-edit-button') {
            $newEvent = false;
            $saveButton.attr('name', 'event-edit-mode');
            $titleInput.val($event.title);
            $descInput.val($event.description);
            $monthSelect.val($date.format('MMMM'));
            $('#event-month-input option[value="' + $date.getMonth() + '"]').attr('selected', 'selected');
            $dayInput.val($date.getDate());
            $yearInput.val($date.getFullYear());
            $hourInput.val($date.format('h'));
            $minuteInput.val($date.getMinutes());
            $meridiemInput.val($date.format('AA'));
            $bgColorInput.iris('color', $event.bgColor);
            $textColorInput.iris('color', $event.textColor);
            $imageInput.val($event.image);
        }
    });

    $('#event-edit-cancel').on('click', function () {
        $('#event-edit-table').fadeOut();
        $('#events-admin-page-wrap-foreground').fadeOut();
    });

    function getEventObject($id) {
        for (let $o in events) {
            if (events[$o].id === $id) {
                return events[$o];
            }
        }
    }

    function stringToDate($string) {
        var $year = parseInt($string.substr(0, 4)),
            $month = parseInt($string.substr(4, 2)),
            $day = parseInt($string.substr(6, 2)),
            $hour = parseInt($string.substr(8, 2)),
            $minute = parseInt($string.substr(10, 2)),
            $meridiem = $string.substr(12, 2);

        if ($meridiem == "PM")
            $hour += 12;

        return new Date($year, $month - 1, $day, $hour, $minute);
    }

    //WP Color Picker
    let textColorOption = {
        color: "#FFFFFF",
        change: function (event, ui) {
            if (!$newEvent) {
                $('.event-detail').css('color', ui.color.toString());
            }
        },
        clear: function () {
        },
        hide: true,
        palettes: true
    };
    $('#event-detail-color').wpColorPicker(textColorOption);

    let bgColorOption = {
        color: "#2F4F4F",
        change: function (event, ui) {
            if (!$newEvent) {
                $("#event-slide-preview").css('background-color', ui.color.toString());
            }
        },
        clear: function () {
        },
        hide: true,
        palettes: true
    };
    $('#event-background-color').wpColorPicker(bgColorOption);

    //WP Media Library
    let mediaLib;
    $('#event-media-lib-button').on('click', function (e) {

        // If the uploader object has already been created, reopen the dialog
        if (mediaLib) {
            mediaLib.open();
            return;
        }
        // Extend the wp.media object
        mediaLib = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            }, multiple: false
        });

        // When a file is selected, grab the URL and set it as the text field's value
        mediaLib.on('select', function () {
            attachment = mediaLib.state().get('selection').first().toJSON();
            $imageInput.val(attachment.url);
        });
        // Open the uploader dialog
        mediaLib.open();
    });


})(jQuery);