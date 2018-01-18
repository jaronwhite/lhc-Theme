<?php
date_default_timezone_set( 'America/Denver' );
$filePath   = get_theme_file_path() . "/inc/event-slider/";
$configFile = $filePath . "event-slider-config.json";
$config     = json_decode( file_get_contents( $configFile ) );
$eventsFile = $filePath . "event-slider-events.json";
$events     = json_decode( file_get_contents( $eventsFile ) );
$active     = 0;

if ( $_POST['update-config'] ) {
	$config->duration      = $_POST['duration-select'];
	$config->restart       = false || ( $_POST['slider-restart'] );
	$config->controls->lr  = false || ( $_POST['lr-flag'] );
	$config->controls->sel = false || ( $_POST['sel-flag'] );
	file_put_contents( $configFile, json_encode( $config ) );
}

if ( $_POST['event-edit-mode'] ) {
	$active             = getActiveIndex( $events );
	$event              = $events[ $active ];
	$event->id          = $_POST['event-id-input'];
	$event->image       = $_POST['event-background-image'];
	$event->title       = $_POST['event-title-input'];
	$event->description = $_POST['event-description-input'];
	$event->dateTime    = $_POST['event-year-input'] . addLeadingZero( $_POST['event-month-input'] + 1 )
	                      . addLeadingZero( $_POST['event-day-input'] ) . addLeadingZero( $_POST['event-hour-input'] )
	                      . addLeadingZero( $_POST['event-minute-input'] ) . $_POST['event-meridiem-input'];
	$event->textColor   = $_POST['event-detail-color'];
	$event->bgColor     = $_POST['event-background-color'];
	file_put_contents( $eventsFile, stripslashes( json_encode( $events ) ) );
}

if ( $_POST['event-create-mode'] ) {
	$active             = sizeof( $events );
	$events[ $active ]  = new stdClass();
	$event              = $events[ $active ];
	$event->id          = $_POST['event-id-input'];
	$event->image       = $_POST['event-background-image'];
	$event->title       = $_POST['event-title-input'];
	$event->description = $_POST['event-description-input'];
	$event->dateTime    = $_POST['event-year-input'] . addLeadingZero( $_POST['event-month-input'] + 1 )
	                      . addLeadingZero( $_POST['event-day-input'] ) . addLeadingZero( $_POST['event-hour-input'] )
	                      . addLeadingZero( $_POST['event-minute-input'] ) . $_POST['event-meridiem-input'];
	$event->textColor   = $_POST['event-detail-color'];
	$event->bgColor     = $_POST['event-background-color'];
	file_put_contents( $eventsFile, json_encode( $events ) );
}

if ( $_POST['event-delete-button'] ) {
	unset( $events[ getActiveIndex( $events ) ] );
	$events = array_values( $events );
	file_put_contents( $eventsFile, stripslashes( json_encode( $events ) ) );
}

updateEventsJS( $events );

$eventId     = $events[ $active ]->id;
$title       = $events[ $active ]->title;
$description = $events[ $active ]->description;
$dateTime    = DateTime::createFromFormat( 'YmdhiA', $events[ $active ]->dateTime );
$date        = $dateTime->format( 'F d, Y @ h:i A' );
$year        = $dateTime->format( 'Y' );
$month       = $dateTime->format( 'F' );
$day         = $dateTime->format( 'd' );
$hour        = $dateTime->format( 'h' );
$minute      = $dateTime->format( 'i' );
$meridiem    = $dateTime->format( 'A' );
$image       = $events[ $active ]->image;
$textColor   = $events[ $active ]->textColor;
$bgColor     = $events[ $active ]->bgColor;

?>
    <h1>Living Hope Events</h1>
    <!--Start Admin Section-->
    <div id="events-admin-page-wrap">
        <div id="events-admin-page-wrap-foreground"></div>
        <section id="events-admin-section" class="event-section">
            <h2><span class="dashicons dashicons-calendar-alt"></span>Create and View Events</h2>
            <div id="events-admin-wrap">

                <article id="events-list-wrap" class="event-admin-article">
                    <p>Click on any event to view details.</p>
                    <div id="events-list">
                        <div id="events-list-header-row">
                            <p class="events-list-table-cell">Date</p>
                            <p class="events-list-table-cell">Time</p>
                            <p class="events-list-table-cell">Title</p>
                        </div>
						<?php
						$count = 0;
						foreach ( $events as $event ) {
							$activeClass   = ( $count == $active ) ? "active" : "";
							$eventDateTime = DateTime::createFromFormat( 'YmdhiA', $event->dateTime );
							echo "<div id='' class='events-list-table-row {$activeClass}'>"
							     . "<p class='events-list-table-cell'>{$eventDateTime->format( 'd M Y' )}</p>"
							     . "<p class='events-list-table-cell'>{$eventDateTime->format( 'h:i A' )}</p>"
							     . "<p class='events-list-table-cell'>{$event->title}</p>"
							     . "</div>";
							$count ++;
						}
						?>
                    </div>
                </article>

                <article id="event-slide-preview-wrap" class="event-admin-article">

                    <!--Start slide preview-->
                    <div id="event-slide-preview" style="background-color: <?php echo $bgColor; ?>;">
                        <div id="event-detail-image"
                             style="background: url(<?php echo $image; ?>) center no-repeat; background-size: cover">
                            <div id="event-detail-wrap" class="event-detail"
                                 style="color:<?php echo $textColor; ?>;">
                                <h1 id="event-detail-title" class="event-detail"
                                    style="color:<?php echo $textColor; ?>;">
									<?php echo $title; ?>
                                </h1>
                                <h3 id="event-detail-desc" class="event-detail"
                                    style="color:<?php echo $textColor; ?>;">
									<?php echo $description; ?>
                                </h3>
                                <p id="event-detail-dateTime" class="event-detail">
									<?php echo $date; ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!--Start event edit form-->
                    <form method="post" action="">
                        <input id="event-id-input" name="event-id-input" value="<?php echo $eventId; ?>" hidden/>
                        <div id="event-edit-table">
                            <div class="event-edit-table-row">
                                <div class="event-edit-table-cell">
                                    <h2>Event Title: </h2>
                                </div>
                                <div class="event-edit-table-cell">
                                    <input type="text" id="event-title-input" name="event-title-input"
                                           class="event-edit-input"
                                           value="Event Title"/>
                                </div>
                            </div>
                            <div class="event-edit-table-row">
                                <div class="event-edit-table-cell">
                                    <h2>Event Description: </h2>
                                </div>
                                <div class="event-edit-table-cell">
                                    <input type="text" id="event-description-input" name="event-description-input"
                                           class="event-edit-input"
                                           value="Event Desc"/>
                                </div>
                            </div>
                            <div class="event-edit-table-row">
                                <div class="event-edit-table-cell">
                                    <h2>Event Date: </h2>
                                </div>
                                <div class="event-edit-table-cell">
                                    <select id="event-month-input" name="event-month-input"
                                            class="event-edit-input small">
										<?php
										$monthArray = [
											"January",
											"February",
											"March",
											"April",
											"May",
											"June",
											"July",
											"August",
											"September",
											"October",
											"November",
											"December"
										];
										foreach ( $monthArray as $i => $month ) {
											$selectMonth;
											( date( 'F' ) == $month ) ? $selectMonth = "selected" : $selectMonth = "";
											echo "<option value='{$i}' {$selectMonth}>{$month}</option>";
										}
										?>
                                    </select>
                                    <input type="number" min="1" max="31" id="event-day-input" name="event-day-input"
                                           class="event-edit-input small"
                                           value="<?php echo date( 'd' ); ?>"/>,
                                    <input type="number" min="<?php echo date( 'Y' ); ?>" max="" id="event-year-input"
                                           name="event-year-input"
                                           class="event-edit-input small"
                                           value="<?php echo date( 'Y' ); ?>"/>
                                </div>
                            </div>
                            <div class="event-edit-table-row">
                                <div class="event-edit-table-cell">
                                    <h2>Event Time: </h2>
                                </div>
                                <div class="event-edit-table-cell">
                                    <input type="number" min="1" max="12" id="event-hour-input" name="event-hour-input"
                                           class="event-edit-input small"
                                           value="6"/> :
                                    <input type="number" min="0" max="55" step="5" id="event-minute-input"
                                           name="event-minute-input"
                                           class="event-edit-input small"
                                           value="0"/>
                                    <select id="event-meridiem-input" name="event-meridiem-input"
                                            class="event-edit-input small">
                                        <option value="AM">AM</option>
                                        <option value="PM" selected>PM</option>
                                    </select>
                                </div>
                            </div>
                            <div class="event-edit-table-row">
                                <div class="event-edit-table-cell">
                                    <h2>Slide Text Color: </h2>
                                </div>
                                <div class="event-edit-table-cell">
                                    <input type="text" id="event-detail-color" name="event-detail-color"
                                           value="<?php echo $textColor; ?>"/>
                                </div>
                            </div>
                            <div class="event-edit-table-row">
                                <div class="event-edit-table-cell">
                                    <h2>Slide Background Color: </h2>
                                </div>
                                <div class="event-edit-table-cell">
                                    <input type="text" id="event-background-color" name="event-background-color"
                                           value="<?php echo $bgColor; ?>"/>
                                </div>
                            </div>
                            <div class="event-edit-table-row">
                                <div class="event-edit-table-cell">
                                    <h2>Slide Background Image: </h2>
                                </div>
                                <div class="event-edit-table-cell">
                                    <input type="text" id="event-background-image" name="event-background-image"
                                           value="<?php echo $image; ?>"/>
                                    <input type="button" id="event-media-lib-button" name="event-media-lib-button"
                                           value="Select Image"/>
                                </div>
                            </div>
                            <div class="event-edit-table-row">
                                <div class="event-edit-table-cell">
                                </div>
                                <div class="event-edit-table-cell">
                                    <input type="reset" id="event-edit-cancel" name="event-edit-cancel"
                                           class="event-edit-button"
                                           value="Cancel"/>
                                    <input type="submit" id="event-edit-save" name="event-edit-save"
                                           class="event-edit-button"
                                           value="Save Event"/>
                                </div>
                            </div>
                        </div>
                        <input type="button" id="event-edit-button" name="event-edit-button" class="event-open-form"
                               value="Edit Event"/>
                        <input type="submit" id="event-delete-button" name="event-delete-button" class=""
                               value="Delete Event"/>
                        <input type="button" id="event-create-button" name="event-create-button" class="event-open-form"
                               value="Create Event"/>
                    </form>

                </article>

            </div>
        </section>

        <!--Start Config Section-->
        <section id="event-config-section" class="event-section">
            <form method="post" action="">
                <h2><span class="dashicons dashicons-admin-settings"></span>Slider Settings</h2>
                <label for="duration-select">Slide Duration:
                    <input type="number" min="0" max="12" id="duration-select" name="duration-select"
                           value="<?php echo $config->duration; ?>"> Second(s)
                </label>
                <label for="slider-restart">Restart slideshow after selection?
                    <input type="checkbox"
                           name="slider-restart"
                           id="slider-restart"
                           value="restart"
						<?php echo checkBox( $config->restart ); ?>
                    />
                </label>
                <label for="slider-controls">
                    Left-Right Arrow Control
                    <input type="checkbox" name="lr-flag" value="lr"
						<?php echo checkBox( $config->controls->lr ); ?> />
                    Bottom
                    Selector Control
                    <input type="checkbox" name="sel-flag" value="sel"
						<?php echo checkBox( $config->controls->sel ); ?>
                    />
                </label>
                <input type="submit" id="update-config" name="update-config"
                       value="Save Settings"/>
            </form>
        </section>

    </div>

<?php

function checkBox( $arg ) {
	( $arg ) ? $checked = "checked" : $checked = '';

	return $checked;
}

function getActiveIndex( $events ) {
	foreach ( $events as $i => $event ) {
		if ( $event->id == $_POST['event-id-input'] ) {
			return $i;
		}
	}

	return - 1;
}

function updateEventsJS( $events ) {
	$evts = json_encode( $events );
	echo "<script>"
	     . "let events = {$evts};"
	     . "console.log(events);"
	     . "</script>";
}

function addLeadingZero( $num ) {
	( strlen( $num ) < 2 ) ? $zeroed = "0{$num}" : $zeroed = "{$num}";

	return $zeroed;
}