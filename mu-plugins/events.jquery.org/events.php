<?php

add_action( 'init', function() {
	global $events;

	$events = array(
		'future' => array(),
		'past' => array()
	);

	$allEvents = json_decode( file_get_contents( GW_RESOURCE_DIR . '/events.json' ) );

	$now = time();
	foreach ( $allEvents as $event ) {
		$event->end = strtotime( $event-> end );

		if ( $event->end > $now ) {
			$events[ 'future' ][] = $event;
		} else {
			$events[ 'past' ][] = $event;
		}
	}
});
