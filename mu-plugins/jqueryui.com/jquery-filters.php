<?php

add_filter( 'the_content', function( $content ) {
	global $post;

	$demoContent = '';
	if ( !preg_match( '/<!--demos\(?(.*?)?\)?-->/', $content, $matches ) ) {
		return $content;
	}

	$content = explode( $matches[ 0 ], $content, 2 );
	$isStandard = empty( $matches[ 1 ] );

	if ( $isStandard ) {
		$plugin = $post->post_name;
		$active = 'default';
	} else {
		$plugin = $matches[ 1 ];
		$active = $post->post_name === $matches[ 1 ] ? 'default' : $post->post_name;
	}
	$demoList = json_decode( file_get_contents( GW_RESOURCE_DIR . '/demos/demo-list.json' ) );
	$demos = $demoList->$plugin;

	$demoContent .=
		'<div class="demo-list"' . ( $isStandard ? '' : ' data-full-nav="true"' ) . '>' .
			'<h2>Examples</h2>' .
			'<ul>';
	foreach ( $demoList->$plugin as $demo ) {
		$filename = $demo->filename;
		if ( $filename === $active ) {
			$demoContent .= '<li class="active">';
			$demoDescription = $demo->description;
		} else {
			$demoContent .= '<li>';
		}
		$demoContent .=
				'<a href="/resources/demos/' . $plugin . '/' . $filename . '.html">' .
					$demo->title .
				'</a>' .
			'</li>';
	}
	$demoContent .=
			'</ul>' .
		'</div>';

	$demoContent .= '<iframe src="/resources/demos/' . $plugin . '/' . $active . '.html" class="demo-frame"></iframe>';
	$demoContent .= '<div class="demo-description">' . $demoDescription . '</div>';

	$demoContent .=
		'<div class="view-source">' .
			'<a tabindex="0"><i class="icon-eye-open"></i> view source</a>' .
			'<div>' .
				file_get_contents( GW_RESOURCE_DIR . '/demos-highlight/' . $plugin . '/' . $active . '.html' ) .
			'</div>' .
		'</div>';

	return $content[ 0 ] . $demoContent . $content[ 1 ];
} );

add_filter( 'the_content', function( $content ) {
	$output = array();
	$parts = preg_split( '/(<!--category-links\(\w+\)-->)/', $content, -1, PREG_SPLIT_DELIM_CAPTURE );

	foreach( $parts as $part ) {
		if ( !preg_match( '/<!--category-links\((\w+)\)-->/', $part, $matches ) ) {
			$output[] = $part;
			continue;
		}

		$output[] = "<ul>" . jq_page_links_for_category( $matches[ 1 ] ) . "</ul>";
	}

	return implode( $output );
} );

?>
