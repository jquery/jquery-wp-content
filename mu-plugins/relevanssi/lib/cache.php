<?php

function relevanssi_purge_excerpt_cache($post) {
	global $wpdb, $relevanssi_variables;
	
	$wpdb->query("DELETE FROM " . $relevanssi_variables['relevanssi_excerpt_cache'] . " WHERE post = $post");
}

function relevanssi_fetch_excerpt($post, $query) {
	global $wpdb, $relevanssi_variables;

	$query = mysql_real_escape_string($query);	
	$excerpt = $wpdb->get_var("SELECT excerpt FROM " . $relevanssi_variables['relevanssi_excerpt_cache'] . " WHERE post = $post AND query = '$query'");
	
	if (!$excerpt) return null;
	
	return $excerpt;
}

function relevanssi_store_excerpt($post, $query, $excerpt) {
	global $wpdb, $relevanssi_variables;
	
	$query = mysql_real_escape_string($query);
	$excerpt = mysql_real_escape_string($excerpt);

	$wpdb->query("INSERT INTO " . $relevanssi_variables['relevanssi_excerpt_cache'] . " (post, query, excerpt)
		VALUES ($post, '$query', '$excerpt')
		ON DUPLICATE KEY UPDATE excerpt = '$excerpt'");
}

function relevanssi_fetch_hits($param) {
	global $wpdb, $relevanssi_variables;

	$time = get_option('relevanssi_cache_seconds', 172800);

	$hits = $wpdb->get_var("SELECT hits FROM " . $relevanssi_variables['relevanssi_cache'] . " WHERE param = '$param' AND UNIX_TIMESTAMP() - UNIX_TIMESTAMP(tstamp) < $time");
	
	if ($hits) {
		return unserialize($hits);
	}
	else {
		return null;
	}
}

function relevanssi_store_hits($param, $data) {
	global $wpdb, $relevanssi_variables;

	$param = mysql_real_escape_string($param);
	$data = mysql_real_escape_string($data);
	$wpdb->query("INSERT INTO " . $relevanssi_variables['relevanssi_cache'] . " (param, hits)
		VALUES ('$param', '$data')
		ON DUPLICATE KEY UPDATE hits = '$data'");
}

function relevanssi_truncate_cache($all = false) {
	global $wpdb, $relevanssi_variables;
	$relevanssi_excerpt_cache = $relevanssi_variables['relevanssi_excerpt_cache'];
	$relevanssi_cache = $relevanssi_variables['relevanssi_cache'];

	if ($all) {
		$query = "TRUNCATE TABLE $relevanssi_excerpt_cache";
		$wpdb->query($query);

		$query = "TRUNCATE TABLE $relevanssi_cache";
	}
	else {
		$time = get_option('relevanssi_cache_seconds', 172800);
		$query = "DELETE FROM $relevanssi_cache
			WHERE UNIX_TIMESTAMP() - UNIX_TIMESTAMP(tstamp) > $time";
		// purge all expired cache data
	}
	$wpdb->query($query);
}


?>
