<?php

// thanks to rvencu
function relevanssi_wpml_filter($data) {
    $use_filter = get_option('relevanssi_wpml_only_current');
    if ('on' == $use_filter) {
		//save current blog language
		$lang = get_bloginfo('language');
		$filtered_hits = array();
		foreach ($data[0] as $hit) {
            if (is_integer($hit)) $hit = get_post($hit); // this in case "fields" is set to "ids"

			if (isset($hit->blog_id)) {
				switch_to_blog($hit->blog_id);
			}
			global $sitepress;

			if (function_exists('icl_object_id') && !function_exists('pll_is_translated_post_type')) {
				if ($sitepress->is_translated_post_type($hit->post_type)) {
					if ($hit->ID == icl_object_id($hit->ID, $hit->post_type, false, $sitepress->get_current_language())) $filtered_hits[] = $hit;
				}
				else {
					$filtered_hits[] = $hit;
				}
			}
			// if there is no WPML but the target blog has identical language with current blog,
			// we use the hits. Note en-US is not identical to en-GB!
			elseif (get_bloginfo('language') == $lang) {
				$filtered_hits[] = $hit;
			}
			if (isset($hit->blog_id)) {
				restore_current_blog();
			}
		}
		return array($filtered_hits, $data[1]);
	}
	return $data;
}

/*
 * Fetches a key-direction pair from the orderby array. Converts key names to match the post object parameters
 * when necessary and seeds the random generator, if required.
*/
function relevanssi_get_next_key(&$orderby) {
	if (!is_array($orderby) || count($orderby) < 1) return array('key' => null, 'dir' => null, 'compare' => null);
	
 	list($key) = array_keys($orderby);
	$dir = $orderby[$key];
	unset($orderby[$key]);

	$compare = "string";

	if (strtolower($dir) == "rand") $key = "rand";
	
	if ('title' == $key) $key = 'post_title';
	if ('date' == $key) $key = 'post_date';
	if ('modified' == $key) $key = 'post_modified';
	if ('parent' == $key) $key = 'post_parent';
	if ('type' == $key) $key = 'post_type';
	if ('name' == $key) $key = 'post_name';
	if ('author' == $key) $key = 'post_author';
	if ('relevance' == $key) $key = 'relevance_score';

	$numeric_keys = array('menu_order', 'ID', 'post_parent', 'post_author', 'comment_count', 'relevance_score');
	$date_keys = array('post_date', 'post_date_gmt', 'post_modified', 'post_modified_gmt');

	if (in_array($key, $numeric_keys)) $compare = "number";
	if (in_array($key, $date_keys)) $compare = "date";

	if ('rand' == $key) {
		if (is_numeric($dir)) srand($dir);
	}
	else {
		$dir = strtolower($dir);
		if ($dir != "asc") $dir = "desc";
	}
	
	$values = array(
		'key' => $key,
		'dir' => $dir,
		'compare' => $compare
	);
	return $values;
}

/*
 * Fetches the key values for the item pair. If random order is required, will randomize the order.
 */
function relevanssi_get_compare_values($key, $item_1, $item_2) {
    function_exists('mb_strtolower') ? $strtolower = 'mb_strtolower' : $strtolower = 'strtolower';

	if ($key == "rand") {
		do {
			$key1 = rand();
			$key2 = rand();
		} while ($key1 == $key2);
		$keys = array(
			'key1' => $key1,
			'key2' => $key2,
		);
		return $keys;
	}

	$key1 = "";
	$key2 = "";

	if ($key == "meta_value" || $key == "meta_value_num") {
		global $wp_query;
		$key = $wp_query->query_vars['meta_key'];
		if (!isset($key)) return array("", "");

		$key1 = get_post_meta($item_1->ID, $key, true);
		if (empty($key1)) $key1 = apply_filters('relevanssi_missing_sort_key', $key1, $key);

		$key2 = get_post_meta($item_2->ID, $key, true);
		if (empty($key2)) $key2 = apply_filters('relevanssi_missing_sort_key', $key2, $key);
	}
	else {
		if (isset($item_1->$key)) {
			$key1 = call_user_func($strtolower, $item_1->$key);
		}
		else {
			$key1 = apply_filters('relevanssi_missing_sort_key', $key1, $key);
		}
		if (isset($item_2->$key)) {
			$key2 = call_user_func($strtolower, $item_2->$key);
		}
		else {
			$key2 = apply_filters('relevanssi_missing_sort_key', $key2, $key);
		}
	}

	$keys = array(
		'key1' => $key1,
		'key2' => $key2,
	);
	return $keys;
}

function relevanssi_compare_values($key1, $key2, $compare) {
	$val = 0;
	if ($compare == "date") {
		if (strtotime($key1) > strtotime($key2)) {
			$val = 1;
		}
		else if (strtotime($key1) < strtotime($key2)) {
			$val = -1;
		}
	}
	else if ($compare == "string") {
		$val = relevanssi_mb_strcasecmp($key1, $key2);
	}
	else {
		if ($key1 > $key2) {
			$val = 1;
		}
		else if ($key1 < $key2) {
			$val = -1;
		}
	}
	return $val;
}

function relevanssi_mb_strcasecmp($str1, $str2, $encoding = null) {
	if (!function_exists('mb_internal_encoding')) {
		return strcasecmp($str1, $str2);
	}
	else {
		if (null === $encoding) $encoding = mb_internal_encoding();
		return strcmp(mb_strtoupper($str1, $encoding), mb_strtoupper($str2, $encoding));
	}
}

/**
 * Function by Matthew Hood http://my.php.net/manual/en/function.sort.php#75036
 */
function relevanssi_object_sort(&$data, $orderby) {
	$keys = array();
	$dirs = array();
	$compares = array();
	do {
		$values = relevanssi_get_next_key($orderby);
		if (!empty($values['key'])) {
			$keys[] = $values['key'];
			$dirs[] = $values['dir'];
			$compares[] = $values['compare'];
		}
	} while (!empty($values['key']));
	
	$primary_key = $keys[0];
	if (!isset($data[0]->$primary_key)) return;			// trying to sort by a non-existent key

	for ($i = count($data) - 1; $i >= 0; $i--) {
        $swapped = false;
       	for ($j = 0; $j < $i; $j++) {
            $key1 = "";
            $key2 = "";

		    $level = -1;
			$val = 0;

            while ($val == 0) {
				$level++;
				if (!isset($keys[$level])) {
            		$level--;
            		break; // give up – we can't sort these two
            	}
				$compare = $compares[$level];
				$compare_values = relevanssi_get_compare_values($keys[$level], $data[$j], $data[$j + 1]);
				$val = relevanssi_compare_values($compare_values['key1'], $compare_values['key2'], $compare);
	        }

            if ('asc' == $dirs[$level]) {
                if ($val > 0) {
                    $tmp = $data[$j];
                    $data[$j] = $data[$j + 1];
                    $data[$j + 1] = $tmp;
                    $swapped = true;
                }
            }
            else {
                if ($val < 1) {
                    $tmp = $data[$j];
                    $data[$j] = $data[$j + 1];
                    $data[$j + 1] = $tmp;
                    $swapped = true;
                }
            }
        }
        if (!$swapped) return;
    }
}

function relevanssi_show_matches($data, $hit) {
	isset($data['body_matches'][$hit]) ? $body = $data['body_matches'][$hit] : $body = 0;
	isset($data['title_matches'][$hit]) ? $title = $data['title_matches'][$hit] : $title = 0;
	isset($data['tag_matches'][$hit]) ? $tag = $data['tag_matches'][$hit] : $tag = 0;
	isset($data['category_matches'][$hit]) ? $category = $data['category_matches'][$hit] : $category = 0;
	isset($data['taxonomy_matches'][$hit]) ? $taxonomy = $data['taxonomy_matches'][$hit] : $taxonomy = 0;
	isset($data['comment_matches'][$hit]) ? $comment = $data['comment_matches'][$hit] : $comment = 0;
	isset($data['scores'][$hit]) ? $score = round($data['scores'][$hit], 2) : $score = 0;
	isset($data['term_hits'][$hit]) ? $term_hits_a = $data['term_hits'][$hit] : $term_hits_a = array();
	arsort($term_hits_a);
	$term_hits = "";
	$total_hits = 0;
	foreach ($term_hits_a as $term => $hits) {
		$term_hits .= " $term: $hits";
		$total_hits += $hits;
	}

	$text = stripslashes(get_option('relevanssi_show_matches_text'));
	$replace_these = array("%body%", "%title%", "%tags%", "%categories%", "%taxonomies%", "%comments%", "%score%", "%terms%", "%total%");
	$replacements = array($body, $title, $tag, $category, $taxonomy, $comment, $score, $term_hits, $total_hits);

	$result = " " . str_replace($replace_these, $replacements, $text);

	return apply_filters('relevanssi_show_matches', $result);
}

function relevanssi_update_log($query, $hits) {
	if(isset($_SERVER['HTTP_USER_AGENT']) && $_SERVER['HTTP_USER_AGENT'] == "Mediapartners-Google")
		return;

	global $wpdb, $relevanssi_variables;

	$user = apply_filters('relevanssi_log_get_user', wp_get_current_user());
	if ($user->ID != 0 && get_option('relevanssi_omit_from_logs')) {
		$omit = explode(",", get_option('relevanssi_omit_from_logs'));
		if (in_array($user->ID, $omit)) return;
		if (in_array($user->user_login, $omit)) return;
	}

	// Bot filter, by Justin_K
	// See: http://wordpress.org/support/topic/bot-logging-problem-w-tested-solution
    $user_agent = "";
	if (isset($_SERVER['HTTP_USER_AGENT'])) {
	    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    	$bots = array('Google'=>'Mediapartners-Google');
	    $bots = apply_filters('relevanssi_bots_to_not_log', $bots);
    	foreach ($bots as $name => $lookfor) {
	        if (stristr($user_agent, $lookfor) !== false) return;
	    }
	}

	get_option('relevanssi_log_queries_with_ip') == "on" ? $ip = apply_filters('relevanssi_remote_addr', $_SERVER['REMOTE_ADDR']) : $ip = '';

    $ok_to_log = apply_filters('relevanssi_ok_to_log', true, $query, $hits, $user_agent, $ip);
    if ($ok_to_log) {
        $q = $wpdb->prepare("INSERT INTO " . $relevanssi_variables['log_table'] . " (query, hits, user_id, ip, time) VALUES (%s, %d, %d, %s, NOW())", $query, intval($hits), $user->ID, $ip);
	    $wpdb->query($q);
    }
}

/**
 *	Do note that while this function takes $post_ok as a parameter, it actually doesn't care much
 *  about the previous value, and will instead overwrite it. If you want to make sure your value
 *  is preserved, either disable this default function, or run your function on a later priority
 *  (this defaults to 10).
 */
function relevanssi_default_post_ok($post_ok, $doc) {
	$status = relevanssi_get_post_status($doc);

	// if it's not public, don't show
	if ('publish' != $status) {
		$post_ok = false;
	}

	// ...unless

	if ('private' == $status) {
		$post_ok = false;

		if (function_exists('awp_user_can')) {
			// Role-Scoper, though Role-Scoper actually uses a different function to do this
			// So whatever is in here doesn't actually run.
			$current_user = wp_get_current_user();
			$post_ok = awp_user_can('read_post', $doc, $current_user->ID);
		}
		else if (defined('GROUPS_CORE_VERSION')) {
			// Groups
			$current_user = wp_get_current_user();
			$access = Groups_Post_Access::user_can_read_post($doc, $current_user->ID);
		}
		else if (defined('SIMPLE_WP_MEMBERSHIP_VER')) {
			// Simple Membership
			$access_ctrl = SwpmAccessControl::get_instance();
			$access = $access_ctrl->can_i_read_post($post);
		}
		else {
			// Basic WordPress version
			$type = relevanssi_get_post_type($doc);
			if (isset($GLOBALS['wp_post_types'][$type]->cap->read_private_posts)) {
				$cap = $GLOBALS['wp_post_types'][$type]->cap->read_private_posts;
			}
			else {
				// guessing here
				$cap = 'read_private_' . $type . 's';
			}
			if (current_user_can($cap)) {
				$post_ok = true;
			}
		}
	}

	// only show drafts, pending and future posts in admin search
	if (in_array($status, apply_filters('relevanssi_valid_admin_status', array('draft', 'pending', 'future'))) && is_admin()) {
		$post_ok = true;
	}

	if (relevanssi_s2member_level($doc) == 0) $post_ok = false; // not ok with s2member

	return $post_ok;
}

/**
 * Return values:
 *  2: full access to post
 *  1: show title only
 *  0: no access to post
 * -1: s2member not active
 */
function relevanssi_s2member_level($doc) {
	$return = -1;
	if (function_exists('is_permitted_by_s2member')) {
		// s2member
		$alt_view_protect = $GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["filter_wp_query"];

		if (version_compare (WS_PLUGIN__S2MEMBER_VERSION, "110912", ">="))
			$completely_hide_protected_search_results = (in_array ("all", $alt_view_protect) || in_array ("searches", $alt_view_protect)) ? true : false;
		else /* Backward compatibility with versions of s2Member, prior to v110912. */
			$completely_hide_protected_search_results = (strpos ($alt_view_protect, "all") !== false || strpos ($alt_view_protect, "searches") !== false) ? true : false;

		if (is_permitted_by_s2member($doc)) {
			// Show title and excerpt, even full content if you like.
			$return = 2;
		}
		else if (!is_permitted_by_s2member($doc) && $completely_hide_protected_search_results === false) {
			// Show title and excerpt. Alt View Protection is NOT enabled for search results. However, do NOT show full content body.
			$return = 1;
		}
		else {
			// Hide this search result completely.
			$return = 0;
		}
	}

	return $return;
}

function relevanssi_populate_array($matches) {
	global $relevanssi_post_array, $relevanssi_post_types, $wpdb;
	if (function_exists('wp_suspend_cache_addition'))
		wp_suspend_cache_addition(true);

	$ids = array();
	foreach ($matches as $match) {
		array_push($ids, $match->doc);
	}

	$ids = implode(',', $ids);
	$posts = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE id IN ($ids)");
	foreach ($posts as $post) {
		$relevanssi_post_array[$post->ID] = $post;
		$relevanssi_post_types[$post->ID] = $post->post_type;
	}

	if (function_exists('wp_suspend_cache_addition'))
		wp_suspend_cache_addition(false);
}

function relevanssi_get_term_taxonomy($id) {
	global $wpdb;
	$taxonomy = $wpdb->get_var("SELECT taxonomy FROM $wpdb->term_taxonomy WHERE term_id = $id");
	return $taxonomy;
}

/**
 * Extracts phrases from search query
 * Returns an array of phrases
 */
function relevanssi_extract_phrases($q) {
	function_exists( 'mb_strpos' ) ? $strpos_function = "mb_strpos" : $strpos_function = "strpos";
	function_exists( 'mb_substr' ) ? $substr_function = "mb_substr" : $substr_function = "substr";

	$pos = call_user_func($strpos_function, $q, '"');

	$phrases = array();
	while ($pos !== false) {
		$start = $pos;
		$end = call_user_func($strpos_function, $q, '"', $start + 1);

		if ($end === false) {
			// just one " in the query
			$pos = $end;
			continue;
		}
		$phrase = call_user_func($substr_function, $q, $start + 1, $end - $start - 1);
		$phrase = trim($phrase);

		if (!empty($phrase)) $phrases[] = $phrase;
		$pos = $end;
	}
	return $phrases;
}

/* If no phrase hits are made, this function returns an empty string
 * If phrase matches are found, the function returns MySQL queries
 */
function relevanssi_recognize_phrases($q) {
	global $wpdb;

	$phrases = relevanssi_extract_phrases($q);

	$all_queries = array();
	if (count($phrases) > 0) {
		foreach ($phrases as $phrase) {
			$queries = array();
			$phrase = esc_sql($phrase);
			"on" == get_option("relevanssi_index_excerpt") ? $excerpt = " OR post_excerpt LIKE '%$phrase%'" : $excerpt = "";
			$query = "(SELECT ID FROM $wpdb->posts
				WHERE (post_content LIKE '%$phrase%' OR post_title LIKE '%$phrase%' $excerpt)
				AND post_status IN ('publish', 'draft', 'private', 'pending', 'future', 'inherit'))";

			$queries[] = $query;

			$query = "(SELECT ID FROM $wpdb->posts as p, $wpdb->term_relationships as r, $wpdb->term_taxonomy as s, $wpdb->terms as t
				WHERE r.term_taxonomy_id = s.term_taxonomy_id AND s.term_id = t.term_id AND p.ID = r.object_id
				AND t.name LIKE '%$phrase%' AND p.post_status IN ('publish', 'draft', 'private', 'pending', 'future', 'inherit'))";

			$queries[] = $query;

			$query = "(SELECT ID
              FROM $wpdb->posts AS p, $wpdb->postmeta AS m
              WHERE p.ID = m.post_id
              AND m.meta_value LIKE '%$phrase%'
              AND p.post_status IN ('publish', 'draft', 'private', 'pending', 'future', 'inherit'))";

			$queries[] = $query;

			$queries = implode(' OR relevanssi.doc IN ', $queries);
			$queries = "AND (relevanssi.doc IN $queries)";
			$all_queries[] = $queries;
		}
	}
	else {
		$phrases = "";
	}

	$all_queries = implode(" ", $all_queries);

	return $all_queries;
}

// found here: http://forums.digitalpoint.com/showthread.php?t=1106745
function relevanssi_strip_invisibles($text) {
	$text = preg_replace(
		array(
			'@<style[^>]*?>.*?</style>@siu',
			'@<script[^>]*?.*?</script>@siu',
			'@<object[^>]*?.*?</object>@siu',
			'@<embed[^>]*?.*?</embed>@siu',
			'@<applet[^>]*?.*?</applet>@siu',
			'@<noscript[^>]*?.*?</noscript>@siu',
			'@<noembed[^>]*?.*?</noembed>@siu',
			'@<iframe[^>]*?.*?</iframe>@siu',
			'@<del[^>]*?.*?</del>@siu',
		),
		' ',
		$text );
	return $text;
}

function relevanssi_strlen_sort($a, $b) {
	return relevanssi_strlen($b) - relevanssi_strlen($a);
}

function relevanssi_get_custom_fields() {
	$custom_fields = get_option("relevanssi_index_fields");
	if ($custom_fields) {
		if ($custom_fields == 'all') {
			return $custom_fields;
		}
		else if ($custom_fields == 'visible') {
			return $custom_fields;
		}
		else {
			$custom_fields = explode(",", $custom_fields);
			for ($i = 0; $i < count($custom_fields); $i++) {
				$custom_fields[$i] = trim($custom_fields[$i]);
			}
		}
	}
	else {
		$custom_fields = false;
	}
	return $custom_fields;
}

function relevanssi_mb_trim($string) {
	$string = str_replace(chr(194) . chr(160), '', $string);
    $string = preg_replace( "/(^\s+)|(\s+$)/us", "", $string );
    return $string;
}

function relevanssi_remove_punct($a) {
    if (!is_string($a)) return "";  // In case something sends a non-string here.

	$a = preg_replace ('/<[^>]*>/', ' ', $a);

	$a = str_replace("\r", '', $a);    // --- replace with empty space
	$a = str_replace("\n", ' ', $a);   // --- replace with space
	$a = str_replace("\t", ' ', $a);   // --- replace with space

	$a = stripslashes($a);

	$a = str_replace('ß', 'ss', $a);

	$a = str_replace("·", '', $a);
	$a = str_replace("…", '', $a);
	$a = str_replace("€", '', $a);
	$a = str_replace("&shy;", '', $a);

	$a = str_replace(chr(194) . chr(160), ' ', $a);
	$a = str_replace("&nbsp;", ' ', $a);
	$a = str_replace('&#8217;', ' ', $a);
	$a = str_replace("'", ' ', $a);
	$a = str_replace("’", ' ', $a);
	$a = str_replace("‘", ' ', $a);
	$a = str_replace("”", ' ', $a);
	$a = str_replace("“", ' ', $a);
	$a = str_replace("„", ' ', $a);
	$a = str_replace("´", ' ', $a);
	$a = str_replace("—", ' ', $a);
	$a = str_replace("–", ' ', $a);
	$a = str_replace("×", ' ', $a);
    $a = preg_replace('/[[:punct:]]+/u', ' ', $a);

    $a = preg_replace('/[[:space:]]+/', ' ', $a);
	$a = trim($a);

    return $a;
}


/**
 * This function will prevent the default search from running, when Relevanssi is
 * active.
 * Thanks to John Blackbourne.
 */
function relevanssi_prevent_default_request( $request, $query ) {
	if ($query->is_search) {
		global $wpdb;
		if (isset($query->query_vars['post_type']) && isset($query->query_vars['post_status'])) {
			if ($query->query_vars['post_type'] == 'attachment' && $query->query_vars['post_status'] == 'inherit,private') {
			  	// this is a media library search; do not meddle
			  	return $request;
			}
		}
		$bbpress = false;
		if ($query->query_vars['post_type'] == 'topic' || $query->query_vars['post_type'] == 'reply') $bbpress = true;
		if (is_array($query->query_vars['post_type'])) {
		 	if (in_array('topic', $query->query_vars['post_type'])) $bbpress = true;
		 	if (in_array('reply', $query->query_vars['post_type'])) $bbpress = true;
		}
		if ($bbpress) {
			// this is a BBPress search; do not meddle
			return $request;
		}
		$admin_search_ok = true;
		$admin_search_ok = apply_filters('relevanssi_admin_search_ok', $admin_search_ok, $query );

		$prevent = true;
		$prevent = apply_filters('relevanssi_prevent_default_request', $prevent, $query );

		if (empty($query->query_vars['s'])) {
			$prevent = false;
			$admin_search_ok = false;
		}

        if ( $query->is_admin && defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			$prevent = false;
			$admin_search_ok = false;
		}

        if ( $query->is_admin && $query->query_vars['post_type'] == 'page') {
            $prevent = false;
			$admin_search_ok = false;
		}

		if (!is_admin() && $prevent )
			$request = "SELECT * FROM $wpdb->posts WHERE 1=2";
		else if ('on' == get_option('relevanssi_admin_search') && $admin_search_ok )
			$request = "SELECT * FROM $wpdb->posts WHERE 1=2";
	}
	return $request;
}

function relevanssi_tokenize($str, $remove_stops = true, $min_word_length = -1) {
	$tokens = array();
	if (is_array($str)) {
		foreach ($str as $part) {
			$tokens = array_merge($tokens, relevanssi_tokenize($part, $remove_stops, $min_word_length));
		}
	}
	if (is_array($str)) return $tokens;

	if ( function_exists('mb_internal_encoding') )
		mb_internal_encoding("UTF-8");

	if ($remove_stops) {
		$stopword_list = relevanssi_fetch_stopwords();
	}

	if (function_exists('relevanssi_thousandsep')) {
		$str = relevanssi_thousandsep($str);
	}

	$str = apply_filters('relevanssi_remove_punctuation', $str);

	if ( function_exists('mb_strtolower') )
		$str = mb_strtolower($str);
	else
		$str = strtolower($str);

	$t = strtok($str, "\n\t ");
	while ($t !== false) {
		$t = strval($t);
		$accept = true;

		if (relevanssi_strlen($t) < $min_word_length) {
			$t = strtok("\n\t  ");
			continue;
		}
		if ($remove_stops == false) {
			$accept = true;
		}
		else {
			if (count($stopword_list) > 0) {	//added by OdditY -> got warning when stopwords table was empty
				if (in_array($t, $stopword_list)) {
					$accept = false;
				}
			}
		}

		if (RELEVANSSI_PREMIUM) {
			$t = apply_filters('relevanssi_premium_tokenizer', $t);
		}

		if ($accept) {
			$t = relevanssi_mb_trim($t);
			if (is_numeric($t)) $t = " $t";		// $t ends up as an array index, and numbers just don't work there
			if (!isset($tokens[$t])) {
				$tokens[$t] = 1;
			}
			else {
				$tokens[$t]++;
			}
		}

		$t = strtok("\n\t ");
	}
	return $tokens;
}

function relevanssi_get_post_status($id) {
	global $relevanssi_post_array;

	$type = substr($id, 0, 2);
	if ($type == '**') {
		return 'publish';
	}
	if ($type == 'u_') {
		return 'publish';
	}

	if (isset($relevanssi_post_array[$id])) {
		$status = $relevanssi_post_array[$id]->post_status;
		if ('inherit' == $status) {
			$parent = $relevanssi_post_array[$id]->post_parent;
			$status = relevanssi_get_post_status($parent);
			if ($status == false) {
				// attachment without a parent
				// let's assume it's public
				$status = 'publish';
			}
		}
		return $status;
	}
	else {
		return get_post_status($id);
	}
}

function relevanssi_get_post_type($id) {
	global $relevanssi_post_array;

	if (isset($relevanssi_post_array[$id])) {
		return $relevanssi_post_array[$id]->post_type;
	}
	else {
		return get_post_type($id);
	}
}

function relevanssi_the_tags($sep = ', ', $echo = true) {
	$tags = relevanssi_highlight_terms(get_the_tag_list('', $sep), get_search_query());
	if ($echo) {
		echo $tags;
	}
	else {
		return $tags;
	}
}

function relevanssi_get_the_tags($sep = ', ') {
	return relevanssi_the_tags($sep, false);
}

function relevanssi_get_term_tax_id($field, $id, $taxonomy) {
	global $wpdb;
	return $wpdb->get_var(
					"SELECT term_taxonomy_id
					FROM $wpdb->term_taxonomy
					WHERE term_id = $id AND taxonomy = '$taxonomy'");
}

/**
 * Takes in a search query, returns it with synonyms added.
 */
function relevanssi_add_synonyms($q) {
	if (empty($q)) return $q;

	$synonym_data = get_option('relevanssi_synonyms');
	if ($synonym_data) {
		$synonyms = array();
		if (function_exists('mb_strtolower')) {
			$synonym_data = mb_strtolower($synonym_data);
		}
		else {
			$synonym_data = strtolower($synonym_data);
		}
		$pairs = explode(";", $synonym_data);
		foreach ($pairs as $pair) {
			if (empty($pair)) continue; 		// skip empty rows
			$parts = explode("=", $pair);
			$key = strval(trim($parts[0]));
			$value = trim($parts[1]);
			$synonyms[$key][$value] = true;
		}
		if (count($synonyms) > 0) {
			$new_terms = array();
			$terms = array_keys(relevanssi_tokenize($q, false)); // remove stopwords is false here
			if (!in_array($q, $terms)) $terms[] = $q;

			foreach ($terms as $term) {
                $term = trim($term);
				if (in_array(strval($term), array_keys($synonyms))) {		// strval, otherwise numbers cause problems
					if (isset($synonyms[strval($term)])) {		// necessary, otherwise terms like "02" can cause problems
						$new_terms = array_merge($new_terms, array_keys($synonyms[strval($term)]));
					}
				}
			}
			if (count($new_terms) > 0) {
				foreach ($new_terms as $new_term) {
					$q .= " $new_term";
				}
			}
		}
	}
	return $q;
}

/* Helper function that does mb_stripos, falls back to mb_strpos and mb_strtoupper
 * if that cannot be found, and falls back to just strpos if even that is not possible.
 */
function relevanssi_stripos($content, $term, $offset = 0) {
	if ($offset > relevanssi_strlen($content)) return false;

	if (function_exists('mb_stripos')) {
		$pos = ("" == $content) ? false : mb_stripos($content, $term, $offset);
	}
	else if (function_exists('mb_strpos') && function_exists('mb_strtoupper') && function_exists('mb_substr')) {
		$pos = mb_strpos(mb_strtoupper($content), mb_strtoupper($term), $offset);
	}
	else {
		$pos = strpos(strtoupper($content), strtoupper($term), $offset);
	}
	return $pos;
}

/* Function to close tags in a bit of HTML code. Used to make sure no tags are left open
 * in excerpts. This method is not foolproof, but it's good enough for now.
 */
function relevanssi_close_tags($html) {
    preg_match_all('#<(?!meta|img|br|hr|input\b)\b([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
    $openedtags = $result[1];
    preg_match_all('#</([a-z]+)>#iU', $html, $result);
    $closedtags = $result[1];
    $len_opened = count($openedtags);
    if (count($closedtags) == $len_opened) {
        return $html;
    }
    $openedtags = array_reverse($openedtags);
    for ($i=0; $i < $len_opened; $i++) {
        if (!in_array($openedtags[$i], $closedtags)) {
            $html .= '</'.$openedtags[$i].'>';
        } else {
            unset($closedtags[array_search($openedtags[$i], $closedtags)]);
        }
    }
    return $html;
}

/* Prints out post title with highlighting.
 */
function relevanssi_the_title($echo = true) {
	global $post;
	if (empty($post->post_highlighted_title)) $post->post_highlighted_title = $post->post_title;
	if ($echo) echo $post->post_highlighted_title;
	return $post->post_highlighted_title;
}

/* Returns the post title with highlighting.
 */
function relevanssi_get_the_title($post_id) {
	$post = relevanssi_get_post($post_id);
	if (empty($post->post_highlighted_title)) $post->post_highlighted_title = $post->post_title;
	return $post->post_highlighted_title;
}

function relevanssi_update_doc_count( $values, $post ) {
	global $wpdb, $relevanssi_variables;
	$relevanssi_table = $relevanssi_variables['relevanssi_table'];
	$D = $wpdb->get_var("SELECT COUNT(DISTINCT(relevanssi.doc)) FROM $relevanssi_table AS relevanssi");
	update_option( 'relevanssi_doc_count', $D);
	return $values;
}

/* Uses mb_strlen() if available, otherwise falls back to strlen().
*/
function relevanssi_strlen($s) {
	if ( function_exists( 'mb_strlen' ) ) return mb_strlen( $s );
	return strlen( $s );
}

/* If WP_CLI is available, print out the debug notice as a WP_CLI::log(), otherwise
 * just echo.
 */
function relevanssi_debug_echo($s) {
	if ( defined( 'WP_CLI' ) && WP_CLI ) {
		WP_CLI::log($s);
	}
	else {
		echo $s . "\n";
	}
}
