<?php

add_filter( 'the_content', function( $content ) {
	$memberPos = strpos( $content, '<!--corporate-members-->');
	if ( $memberPos === false ) {
		return $content;
	}

	$members = json_decode( file_get_contents( GW_RESOURCE_DIR . '/corporate-members.json' ), true );
	shuffle( $members );

	$memberContent = '<ul class="row" id="corporate-members">';
	foreach( $members as $index => $member ) {

		// Only show four members at a time
		if ( $index >= 4 ) {
			break;
		}

		$logoUrl = "//jquery.org/resources/members/" .
			preg_replace( '/[^a-z0-9]/', '', strtolower( $member[ 'name' ] ) ) . ".png";
		$memberContent .=
			'<li class="three columns">' .
				'<a href="' . $member[ 'url' ] . '">' .
					'<span></span>' .
					'<img src="' . $logoUrl . '" title="' . $member[ 'name' ] . '">' .
				'</a>' .
			'</li>';
	}
	$memberContent .= '</ul>';

	return str_replace( '<!--corporate-members-->', $memberContent, $content );
} );

?>
