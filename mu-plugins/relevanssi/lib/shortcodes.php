<?php

add_shortcode('search', 'relevanssi_shortcode');
add_shortcode('noindex', 'relevanssi_noindex_shortcode');

function relevanssi_shortcode($atts, $content, $name) {
	global $wpdb;

	extract(shortcode_atts(array('term' => false, 'phrase' => 'not'), $atts));
	
	if ($term != false) {
		$term = urlencode(strtolower($term));
	}
	else {
		$term = urlencode(strip_tags(strtolower($content)));
	}
	
	if ($phrase != 'not') {
		$term = '%22' . $term . '%22';	
	}
	
	$link = get_bloginfo('url') . "/?s=$term";
	
	$pre  = "<a href='$link'>";
	$post = "</a>";

	return $pre . do_shortcode($content) . $post;
}

function relevanssi_noindex_shortcode($atts, $content) {
	// When in general use, make the shortcode disappear.
	return do_shortcode($content);
}

function relevanssi_noindex_shortcode_indexing($atts, $content) {
	// When indexing, make the text disappear.
	return '';
}

?>