<?php

// thanks to rvencu
function relevanssi_wpml_filter($data) {
    $use_filter = get_option('relevanssi_wpml_only_current');
    if ('on' == $use_filter) {
		//save current blog language
		$lang = get_bloginfo('language');
		$filtered_hits = array();
		foreach ($data[0] as $hit) {
			if (isset($hit->blog_id)) {
				switch_to_blog($hit->blog_id);
			}
			global $sitepress;

			if (function_exists('icl_object_id') && !function_exists('pll_is_translated_post_type')) {
				if ($sitepress->is_translated_post_type($hit->post_type)) {
					if ($hit->ID == icl_object_id($hit->ID, $hit->post_type, false, ICL_LANGUAGE_CODE)) $filtered_hits[] = $hit;
				}
				else {
					$filtered_hits[] = $hit;
				}
			}
			elseif (function_exists('icl_object_id') && function_exists('pll_is_translated_post_type')) {
				if (pll_is_translated_post_type($hit->post_type)) {
					global $polylang;
					if ($polylang->model->get_post_language($hit->ID)->slug == ICL_LANGUAGE_CODE) {
						$filtered_hits[] = $hit;
					}
					else if ($hit->ID == icl_object_id($hit->ID, $hit->post_type, false, ICL_LANGUAGE_CODE)) {
						$filtered_hits[] = $hit;
					}
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

/**
 * Function by Matthew Hood http://my.php.net/manual/en/function.sort.php#75036
 */
function relevanssi_object_sort(&$data, $key, $dir = 'desc') {
	if ('title' == $key) $key = 'post_title';
	if ('date' == $key) $key = 'post_date';
	if (!isset($data[0]->$key)) return;			// trying to sort by a non-existent key
	$dir = strtolower($dir);
    for ($i = count($data) - 1; $i >= 0; $i--) {
		$swapped = false;
      	for ($j = 0; $j < $i; $j++) {
      		if (function_exists('mb_strtolower')) {
	   			$key1 = mb_strtolower($data[$j]->$key);
   				$key2 = mb_strtolower($data[$j + 1]->$key);
   			}
   			else {
	   			$key1 = strtolower($data[$j]->$key);
   				$key2 = strtolower($data[$j + 1]->$key);
   			}
      		if ('asc' == $dir) {
	           	if ($key1 > $key2) { 
    		        $tmp = $data[$j];
        	        $data[$j] = $data[$j + 1];
            	    $data[$j + 1] = $tmp;
                	$swapped = true;
	           	}
	        }
			else {
	           	if ($key1 < $key2) { 
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
	isset($data['body_matches'][$hit]) ? $body = $data['body_matches'][$hit] : $body = "";
	isset($data['title_matches'][$hit]) ? $title = $data['title_matches'][$hit] : $title = "";
	isset($data['tag_matches'][$hit]) ? $tag = $data['tag_matches'][$hit] : $tag = "";
	isset($data['comment_matches'][$hit]) ? $comment = $data['comment_matches'][$hit] : $comment = "";
	isset($data['scores'][$hit]) ? $score = round($data['scores'][$hit], 2) : $score = 0;
	isset($data['term_hits'][$hit]) ? $term_hits_a = $data['term_hits'][$hit] : $term_hits_a = array();
	arsort($term_hits_a);
	$term_hits = "";
	$total_hits = 0;
	foreach ($term_hits_a as $term => $hits) {
		$term_hits .= " $term: $hits";
		$total_hits += $hits;
	}
	
	$text = get_option('relevanssi_show_matches_text');
	$replace_these = array("%body%", "%title%", "%tags%", "%comments%", "%score%", "%terms%", "%total%");
	$replacements = array($body, $title, $tag, $comment, $score, $term_hits, $total_hits);
	
	$result = " " . str_replace($replace_these, $replacements, $text);
	
	return apply_filters('relevanssi_show_matches', $result);
}

function relevanssi_update_log($query, $hits) {
	if(isset($_SERVER['HTTP_USER_AGENT']) && $_SERVER['HTTP_USER_AGENT'] == "Mediapartners-Google")
		return;

	global $wpdb, $relevanssi_variables;
	
	$user = wp_get_current_user();
	if ($user->ID != 0 && get_option('relevanssi_omit_from_logs')) {
		$omit = explode(",", get_option('relevanssi_omit_from_logs'));
		if (in_array($user->ID, $omit)) return;
		if (in_array($user->user_login, $omit)) return;
	}

	// Bot filter, by Justin_K
	// See: http://wordpress.org/support/topic/bot-logging-problem-w-tested-solution
	if (isset($_SERVER['HTTP_USER_AGENT'])) {
	    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    	$bots = array('Google'=>'Mediapartners-Google');
	    $bots = apply_filters('relevanssi_bots_to_not_log', $bots);
    	foreach ($bots as $name => $lookfor) {
	        if (stristr($user_agent, $lookfor) !== false) return;
	    }
	}	
	
	get_option('relevanssi_log_queries_with_ip') == "on" ? $ip = $_SERVER['REMOTE_ADDR'] : $ip = '';
	$q = $wpdb->prepare("INSERT INTO " . $relevanssi_variables['log_table'] . " (query, hits, user_id, ip, time) VALUES (%s, %d, %d, %s, NOW())", $query, intval($hits), $user->ID, $ip);
	$wpdb->query($q);
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
		else {
			// Basic WordPress version
			$type = relevanssi_get_post_type($doc);
			$cap = 'read_private_' . $type . 's';
			$cap = apply_filters('relevanssi_private_cap', $cap);
			if (current_user_can($cap)) {
				$post_ok = true;
			}
		}
	}
	
	// only show drafts, pending and future posts in admin search
	if (in_array($status, array('draft', 'pending', 'future')) && is_admin()) {
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
	if ( function_exists( 'mb_strpos' ) )
		$pos = mb_strpos($q, '"');
	else
		$pos = strpos($q, '"');

	$phrases = array();
	while ($pos !== false) {
		$start = $pos;
		if ( function_exists( 'mb_strpos' ) )
			$end = mb_strpos($q, '"', $start + 1);
		else
			$end = strpos($q, '"', $start + 1);
		
		if ($end === false) {
			// just one " in the query
			$pos = $end;
			continue;
		}
		if ( function_exists( 'mb_substr' ) )
			$phrase = mb_substr($q, $start + 1, $end - $start - 1);
		else
			$phrase = substr($q, $start + 1, $end - $start - 1);
		
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
	return strlen($b) - strlen($a);
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
		$a = strip_tags($a);
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
		if (strlen($t) < $min_word_length) {
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
function relevanssi_stripos($content, $term, $offset) {
	if (function_exists('mb_strlen')) {
		if ($offset > mb_strlen($content)) return false;
	}
	else {
		if ($offset > strlen($content)) return false;
	}
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
function relevanssi_the_title() {
	global $post;
	if (empty($post->post_highlighted_title)) $post->post_highlighted_title = $post->post_title;
	echo $post->post_highlighted_title;
}

/* Returns the post title with highlighting.
 */
function relevanssi_get_the_title($post_id) {
	$post = get_post($post_id);
	if (empty($post->post_highlighted_title)) $post->post_highlighted_title = $post->post_title;
	return $post->post_highlighted_title;
}

function relevanssi_update_doc_count( $values, $post ) {
	$D = get_option( 'relevanssi_doc_count');
	$count = count($values);
	if ($values && $count > 0) {
		update_option( 'relevanssi_doc_count', $D + $count);
	}
	return $values;
}
?>
