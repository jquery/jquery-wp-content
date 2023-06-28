<?php

function relevanssi_options() {
	global $relevanssi_variables;
	if (RELEVANSSI_PREMIUM) {
		$options_txt = __('Relevanssi Premium Search Options', 'relevanssi');
	}
	else {
		$options_txt = __('Relevanssi Search Options', 'relevanssi');
	}

	printf("<div class='wrap'><h2>%s</h2>", $options_txt);
	if (!empty($_POST)) {
		if (isset($_REQUEST['submit'])) {
			check_admin_referer(plugin_basename($relevanssi_variables['file']), 'relevanssi_options');
			update_relevanssi_options();
		}

		if (isset($_REQUEST['index'])) {
			check_admin_referer(plugin_basename($relevanssi_variables['file']), 'relevanssi_options');
			update_relevanssi_options();
			relevanssi_build_index();
		}

		if (isset($_REQUEST['index_extend'])) {
			check_admin_referer(plugin_basename($relevanssi_variables['file']), 'relevanssi_options');
			update_relevanssi_options();
			relevanssi_build_index(true);
		}

		if (isset($_REQUEST['import_options'])) {
			if (function_exists('relevanssi_import_options')) {
				check_admin_referer(plugin_basename($relevanssi_variables['file']), 'relevanssi_options');
				$options = $_REQUEST['relevanssi_settings'];
				relevanssi_import_options($options);
			}
		}

		if (isset($_REQUEST['search'])) {
			relevanssi_search($_REQUEST['q']);
		}

		if (isset($_REQUEST['dowhat'])) {
			if ("add_stopword" == $_REQUEST['dowhat']) {
				if (isset($_REQUEST['term'])) {
					check_admin_referer(plugin_basename($relevanssi_variables['file']), 'relevanssi_options');
					relevanssi_add_stopword($_REQUEST['term']);
				}
			}
		}

		if (isset($_REQUEST['addstopword'])) {
			check_admin_referer(plugin_basename($relevanssi_variables['file']), 'relevanssi_options');
			relevanssi_add_stopword($_REQUEST['addstopword']);
		}

		if (isset($_REQUEST['removestopword'])) {
			check_admin_referer(plugin_basename($relevanssi_variables['file']), 'relevanssi_options');
			relevanssi_remove_stopword($_REQUEST['removestopword']);
		}

		if (isset($_REQUEST['removeallstopwords'])) {
			check_admin_referer(plugin_basename($relevanssi_variables['file']), 'relevanssi_options');
			relevanssi_remove_all_stopwords();
		}
	}
	relevanssi_options_form();

	if (apply_filters('relevanssi_display_common_words', true))
		relevanssi_common_words(25);

	echo "<div style='clear:both'></div>";

	echo "</div>";
}

function relevanssi_search_stats() {
	$relevanssi_hide_branding = get_option( 'relevanssi_hide_branding' );

	if ( 'on' == $relevanssi_hide_branding )
		$options_txt = __('User Searches', 'relevanssi');
	else
		$options_txt = __('Relevanssi User Searches', 'relevanssi');

	if (isset($_REQUEST['relevanssi_reset']) and current_user_can('manage_options')) {
		check_admin_referer('relevanssi_reset_logs', '_relresnonce');
		if (isset($_REQUEST['relevanssi_reset_code'])) {
			if ($_REQUEST['relevanssi_reset_code'] == 'reset') {
				$verbose = true;
				relevanssi_truncate_logs($verbose);
			}
		}
	}

	wp_enqueue_style('dashboard');
	wp_print_styles('dashboard');
	wp_enqueue_script('dashboard');
	wp_print_scripts('dashboard');

	printf("<div class='wrap'><h2>%s</h2>", $options_txt);

	if ( 'on' == $relevanssi_hide_branding )
		echo '<div class="postbox-container">';
	else
		echo '<div class="postbox-container" style="width:70%;">';


	if ('on' == get_option('relevanssi_log_queries')) {
		relevanssi_query_log();
	}
	else {
		echo "<p>" . __('Enable query logging to see stats here.', 'relevanssi') . "</p>";
	}

	echo "</div>";

	if ('on' != $relevanssi_hide_branding )
		relevanssi_sidebar();
}

function relevanssi_truncate_logs($verbose = true) {
	global $wpdb, $relevanssi_variables;

	$query = "TRUNCATE " . $relevanssi_variables['log_table'];
	$result = $wpdb->query($query);

	if ($verbose) {
		if ($result !== false) {
			echo "<div id='relevanssi-warning' class='updated fade'>" . __('Logs clear!', 'relevanssi') . "</div>";
		}
		else {
			echo "<div id='relevanssi-warning' class='updated fade'>" . __('Clearing the logs failed.', 'relevanssi') . "</div>";
		}
	}

	return $result;
}

function update_relevanssi_options() {
	if (isset($_REQUEST['relevanssi_title_boost'])) {
		$boost = floatval($_REQUEST['relevanssi_title_boost']);
		update_option('relevanssi_title_boost', $boost);
	}

	if (isset($_REQUEST['relevanssi_comment_boost'])) {
		$boost = floatval($_REQUEST['relevanssi_comment_boost']);
		update_option('relevanssi_comment_boost', $boost);
	}

	if (isset($_REQUEST['relevanssi_min_word_length'])) {
		$value = intval($_REQUEST['relevanssi_min_word_length']);
		if ($value == 0) $value = 3;
		update_option('relevanssi_min_word_length', $value);
	}

	if (!isset($_REQUEST['relevanssi_admin_search'])) {
		$_REQUEST['relevanssi_admin_search'] = "off";
	}

	if (!isset($_REQUEST['relevanssi_excerpts'])) {
		$_REQUEST['relevanssi_excerpts'] = "off";
	}

	if (!isset($_REQUEST['relevanssi_show_matches'])) {
		$_REQUEST['relevanssi_show_matches'] = "off";
	}

	if (!isset($_REQUEST['relevanssi_throttle'])) {
		$_REQUEST['relevanssi_throttle'] = "off";
	}

	if (!isset($_REQUEST['relevanssi_index_author'])) {
		$_REQUEST['relevanssi_index_author'] = "off";
	}

	if (!isset($_REQUEST['relevanssi_index_excerpt'])) {
		$_REQUEST['relevanssi_index_excerpt'] = "off";
	}

	if (!isset($_REQUEST['relevanssi_log_queries'])) {
		$_REQUEST['relevanssi_log_queries'] = "off";
	}

	if (!isset($_REQUEST['relevanssi_log_queries_with_ip'])) {
		$_REQUEST['relevanssi_log_queries_with_ip'] = "off";
	}

	if (!isset($_REQUEST['relevanssi_disable_or_fallback'])) {
		$_REQUEST['relevanssi_disable_or_fallback'] = "off";
	}

	if (!isset($_REQUEST['relevanssi_hilite_title'])) {
		$_REQUEST['relevanssi_hilite_title'] = "off";
	}

	if (!isset($_REQUEST['relevanssi_highlight_docs'])) {
		$_REQUEST['relevanssi_highlight_docs'] = "off";
	}

	if (!isset($_REQUEST['relevanssi_highlight_comments'])) {
		$_REQUEST['relevanssi_highlight_comments'] = "off";
	}

	if (!isset($_REQUEST['relevanssi_expand_shortcodes'])) {
		$_REQUEST['relevanssi_expand_shortcodes'] = "off";
	}

	if (!isset($_REQUEST['relevanssi_respect_exclude'])) {
		$_REQUEST['relevanssi_respect_exclude'] = "off";
	}

	if (!isset($_REQUEST['relevanssi_wpml_only_current'])) {
		$_REQUEST['relevanssi_wpml_only_current'] = "off";
	}

	if (!isset($_REQUEST['relevanssi_word_boundaries'])) {
		$_REQUEST['relevanssi_word_boundaries'] = "off";
	}

	if (isset($_REQUEST['relevanssi_excerpt_length'])) {
		$value = intval($_REQUEST['relevanssi_excerpt_length']);
		if ($value != 0) {
			update_option('relevanssi_excerpt_length', $value);
		}
	}

	if (isset($_REQUEST['relevanssi_synonyms'])) {
		$linefeeds = array("\r\n", "\n", "\r");
		$value = str_replace($linefeeds, ";", $_REQUEST['relevanssi_synonyms']);
		$value = stripslashes($value);
		update_option('relevanssi_synonyms', $value);
	}

	if (isset($_REQUEST['relevanssi_show_matches'])) update_option('relevanssi_show_matches', $_REQUEST['relevanssi_show_matches']);
	if (isset($_REQUEST['relevanssi_show_matches_text'])) {
		$value = $_REQUEST['relevanssi_show_matches_text'];
		$value = str_replace('"', "'", $value);
		update_option('relevanssi_show_matches_text', $value);
	}

	$post_type_weights = array();
	$index_post_types = array();
	$index_taxonomies_list = array();
	$index_terms_list = array();
	foreach ($_REQUEST as $key => $value) {
		if (substr($key, 0, strlen('relevanssi_weight_')) == 'relevanssi_weight_') {
			$type = substr($key, strlen('relevanssi_weight_'));
			$post_type_weights[$type] = floatval($value);
		}
		if (substr($key, 0, strlen('relevanssi_index_type_')) == 'relevanssi_index_type_') {
			$type = substr($key, strlen('relevanssi_index_type_'));
			if ('on' == $value) $index_post_types[$type] = true;
		}
		if (substr($key, 0, strlen('relevanssi_index_taxonomy_')) == 'relevanssi_index_taxonomy_') {
			$type = substr($key, strlen('relevanssi_index_taxonomy_'));
			if ('on' == $value) $index_taxonomies_list[$type] = true;
		}
		if (substr($key, 0, strlen('relevanssi_index_terms_')) == 'relevanssi_index_terms_') {
			$type = substr($key, strlen('relevanssi_index_terms_'));
			if ('on' == $value) $index_terms_list[$type] = true;
		}
	}

	if (count($post_type_weights) > 0) {
		update_option('relevanssi_post_type_weights', $post_type_weights);
	}

	if (count($index_post_types) > 0) {
		update_option('relevanssi_index_post_types', array_keys($index_post_types));
	}

	update_option('relevanssi_index_taxonomies_list', array_keys($index_taxonomies_list));
	if (RELEVANSSI_PREMIUM) update_option('relevanssi_index_terms', array_keys($index_terms_list));

	if (isset($_REQUEST['relevanssi_admin_search'])) update_option('relevanssi_admin_search', $_REQUEST['relevanssi_admin_search']);
	if (isset($_REQUEST['relevanssi_excerpts'])) update_option('relevanssi_excerpts', $_REQUEST['relevanssi_excerpts']);
	if (isset($_REQUEST['relevanssi_excerpt_type'])) update_option('relevanssi_excerpt_type', $_REQUEST['relevanssi_excerpt_type']);
	if (isset($_REQUEST['relevanssi_excerpt_allowable_tags'])) update_option('relevanssi_excerpt_allowable_tags', $_REQUEST['relevanssi_excerpt_allowable_tags']);
	if (isset($_REQUEST['relevanssi_log_queries'])) update_option('relevanssi_log_queries', $_REQUEST['relevanssi_log_queries']);
	if (isset($_REQUEST['relevanssi_log_queries_with_ip'])) update_option('relevanssi_log_queries_with_ip', $_REQUEST['relevanssi_log_queries_with_ip']);
	if (isset($_REQUEST['relevanssi_highlight'])) update_option('relevanssi_highlight', $_REQUEST['relevanssi_highlight']);
	if (isset($_REQUEST['relevanssi_highlight_docs'])) update_option('relevanssi_highlight_docs', $_REQUEST['relevanssi_highlight_docs']);
	if (isset($_REQUEST['relevanssi_highlight_comments'])) update_option('relevanssi_highlight_comments', $_REQUEST['relevanssi_highlight_comments']);
	if (isset($_REQUEST['relevanssi_txt_col'])) update_option('relevanssi_txt_col', $_REQUEST['relevanssi_txt_col']);
	if (isset($_REQUEST['relevanssi_bg_col'])) update_option('relevanssi_bg_col', $_REQUEST['relevanssi_bg_col']);
	if (isset($_REQUEST['relevanssi_css'])) update_option('relevanssi_css', $_REQUEST['relevanssi_css']);
	if (isset($_REQUEST['relevanssi_class'])) update_option('relevanssi_class', $_REQUEST['relevanssi_class']);
	if (isset($_REQUEST['relevanssi_cat'])) update_option('relevanssi_cat', $_REQUEST['relevanssi_cat']);
	if (isset($_REQUEST['relevanssi_excat'])) update_option('relevanssi_excat', $_REQUEST['relevanssi_excat']);
	if (isset($_REQUEST['relevanssi_extag'])) update_option('relevanssi_extag', $_REQUEST['relevanssi_extag']);
	if (isset($_REQUEST['relevanssi_index_fields'])) update_option('relevanssi_index_fields', $_REQUEST['relevanssi_index_fields']);
	if (isset($_REQUEST['relevanssi_expst'])) update_option('relevanssi_exclude_posts', $_REQUEST['relevanssi_expst']); 			//added by OdditY
	if (isset($_REQUEST['relevanssi_hilite_title'])) update_option('relevanssi_hilite_title', $_REQUEST['relevanssi_hilite_title']); 	//added by OdditY
	if (isset($_REQUEST['relevanssi_index_comments'])) update_option('relevanssi_index_comments', $_REQUEST['relevanssi_index_comments']); //added by OdditY
	if (isset($_REQUEST['relevanssi_index_author'])) update_option('relevanssi_index_author', $_REQUEST['relevanssi_index_author']);
	if (isset($_REQUEST['relevanssi_index_excerpt'])) update_option('relevanssi_index_excerpt', $_REQUEST['relevanssi_index_excerpt']);
	if (isset($_REQUEST['relevanssi_fuzzy'])) update_option('relevanssi_fuzzy', $_REQUEST['relevanssi_fuzzy']);
	if (isset($_REQUEST['relevanssi_expand_shortcodes'])) update_option('relevanssi_expand_shortcodes', $_REQUEST['relevanssi_expand_shortcodes']);
	if (isset($_REQUEST['relevanssi_implicit_operator'])) update_option('relevanssi_implicit_operator', $_REQUEST['relevanssi_implicit_operator']);
	if (isset($_REQUEST['relevanssi_omit_from_logs'])) update_option('relevanssi_omit_from_logs', $_REQUEST['relevanssi_omit_from_logs']);
	if (isset($_REQUEST['relevanssi_index_limit'])) update_option('relevanssi_index_limit', $_REQUEST['relevanssi_index_limit']);
	if (isset($_REQUEST['relevanssi_disable_or_fallback'])) update_option('relevanssi_disable_or_fallback', $_REQUEST['relevanssi_disable_or_fallback']);
	if (isset($_REQUEST['relevanssi_respect_exclude'])) update_option('relevanssi_respect_exclude', $_REQUEST['relevanssi_respect_exclude']);
	if (isset($_REQUEST['relevanssi_throttle'])) update_option('relevanssi_throttle', $_REQUEST['relevanssi_throttle']);
	if (isset($_REQUEST['relevanssi_wpml_only_current'])) update_option('relevanssi_wpml_only_current', $_REQUEST['relevanssi_wpml_only_current']);
	if (isset($_REQUEST['relevanssi_word_boundaries'])) update_option('relevanssi_word_boundaries', $_REQUEST['relevanssi_word_boundaries']);
	if (isset($_REQUEST['relevanssi_default_orderby'])) update_option('relevanssi_default_orderby', $_REQUEST['relevanssi_default_orderby']);

	if (function_exists('relevanssi_update_premium_options')) {
		relevanssi_update_premium_options();
	}
}

function relevanssi_add_stopword($term) {
	global $wpdb;
	if ('' == $term) return; // do not add empty $term to stopwords - added by renaissancehack

	$n = 0;
	$s = 0;

	$terms = explode(',', $term);
	if (count($terms) > 1) {
		foreach($terms as $term) {
			$n++;
			$term = trim($term);
			$success = relevanssi_add_single_stopword($term);
			if ($success) $s++;
		}
		printf(__("<div id='message' class='updated fade'><p>Successfully added %d/%d terms to stopwords!</p></div>", "relevanssi"), $s, $n);
	}
	else {
		// add to stopwords
		$success = relevanssi_add_single_stopword($term);

		if ($success) {
			printf(__("<div id='message' class='updated fade'><p>Term '%s' added to stopwords!</p></div>", "relevanssi"), stripslashes($term));
		}
		else {
			printf(__("<div id='message' class='updated fade'><p>Couldn't add term '%s' to stopwords!</p></div>", "relevanssi"), stripslashes($term));
		}
	}
}

function relevanssi_add_single_stopword($term) {
	global $wpdb, $relevanssi_variables;
	if ('' == $term) return;

	$term = stripslashes($term);

	if (method_exists($wpdb, 'esc_like')) {
		$term = esc_sql($wpdb->esc_like($term));
	}
	else {
		// Compatibility for pre-4.0 WordPress
		$term = esc_sql(like_escape($term));
	}

	$q = $wpdb->prepare("INSERT INTO " . $relevanssi_variables['stopword_table'] . " (stopword) VALUES (%s)", $term);
	// Clean: escaped.
	$success = $wpdb->query($q);

	if ($success) {
		// remove from index
		$q = $wpdb->prepare("DELETE FROM " . $relevanssi_variables['relevanssi_table'] . " WHERE term=%s", $term);
		$wpdb->query($q);
		return true;
	}
	else {
		return false;
	}
}

function relevanssi_remove_all_stopwords() {
	global $wpdb, $relevanssi_variables;

	$success = $wpdb->query("TRUNCATE " . $relevanssi_variables['stopword_table']);

	printf(__("<div id='message' class='updated fade'><p>Stopwords removed! Remember to re-index.</p></div>", "relevanssi"), $term);
}

function relevanssi_remove_stopword($term, $verbose = true) {
	global $wpdb, $relevanssi_variables;

	$q = $wpdb->prepare("DELETE FROM " . $relevanssi_variables['stopword_table'] . " WHERE stopword=%s", $term);
	$success = $wpdb->query($q);

	if ($success) {
		if ($verbose) {
			echo "<div id='message' class='updated fade'><p>";
			printf(__("Term '%s' removed from stopwords! Re-index to get it back to index.", "relevanssi"), stripslashes($term));
			echo "</p></div>";
		}
		else {
			return true;
		}
	}
	else {
		if ($verbose) {
			echo "<div id='message' class='updated fade'><p>";
			printf(__("Couldn't remove term '%s' from stopwords!", "relevanssi"), stripslashes($term));
			echo "</p></div>";
		}
		else {
			return false;
		}
	}
}

function relevanssi_common_words($limit = 25, $wp_cli = false) {
	global $wpdb, $relevanssi_variables, $wp_version;

	RELEVANSSI_PREMIUM ? $plugin = 'relevanssi-premium' : $plugin = 'relevanssi';

	if (!is_numeric($limit)) $limit = 25;

	$words = $wpdb->get_results("SELECT COUNT(*) as cnt, term FROM " . $relevanssi_variables['relevanssi_table'] . " GROUP BY term ORDER BY cnt DESC LIMIT $limit");
	// Clean: $limit is numeric.

	if (!$wp_cli) {
		echo "<div style='float:left; width: 45%'>";
		echo "<h3>" . __("25 most common words in the index", 'relevanssi') . "</h3>";
		echo "<p>" . __("These words are excellent stopword material. A word that appears in most of the posts in the database is quite pointless when searching. This is also an easy way to create a completely new stopword list, if one isn't available in your language. Click the icon after the word to add the word to the stopword list. The word will also be removed from the index, so rebuilding the index is not necessary.", 'relevanssi') . "</p>";

?>
<form method="post">
<?php wp_nonce_field(plugin_basename($relevanssi_variables['file']), 'relevanssi_options'); ?>
<input type="hidden" name="dowhat" value="add_stopword" />
<ul>
<?php

		if (function_exists("plugins_url")) {
			if (version_compare($wp_version, '2.8dev', '>' )) {
				$src = plugins_url('delete.png', $relevanssi_variables['file']);
			}
			else {
				$src = plugins_url($plugin . '/delete.png');
			}
		}
		else {
			// We can't check, so let's assume something sensible
			$src = '/wp-content/plugins/' . $plugin . '/delete.png';
		}

		foreach ($words as $word) {
			$stop = __('Add to stopwords', 'relevanssi');
			printf('<li>%s (%d) <input style="padding: 0; margin: 0" type="image" src="%s" alt="%s" name="term" value="%s"/></li>', $word->term, $word->cnt, $src, $stop, $word->term);
		}
		echo "</ul>\n</form>";

		echo "</div>";
	}
	else {
		// WP CLI gets the list of words
		return $words;
	}
}

function relevanssi_query_log() {
	global $wpdb;

	$days30 = apply_filters('relevanssi_30days', 30);

	echo '<h3>' . __("Total Searches", 'relevanssi') . '</h3>';

	echo "<div style='width: 30%; float: left; margin-right: 2%; overflow: scroll'>";
	relevanssi_total_queries( __("Totals", 'relevanssi') );
	echo '</div>';

	echo '<div style="clear: both"></div>';

	echo '<h3>' . __("Common Queries", 'relevanssi') . '</h3>';

	$limit = apply_filters('relevanssi_user_searches_limit', 20);
	$lead = __("Here you can see the %d most common user search queries, how many times those
		queries were made and how many results were found for those queries.", 'relevanssi');

	sprintf("<p>" . $lead . "</p>", $limit);

	echo "<div style='width: 30%; float: left; margin-right: 2%; overflow: scroll'>";
	relevanssi_date_queries(1, __("Today and yesterday", 'relevanssi'));
	echo '</div>';

	echo "<div style='width: 30%; float: left; margin-right: 2%; overflow: scroll'>";
	relevanssi_date_queries(7, __("Last 7 days", 'relevanssi'));
	echo '</div>';

	echo "<div style='width: 30%; float: left; margin-right: 2%; overflow: scroll'>";
	relevanssi_date_queries($days30, sprintf(__("Last %d days", 'relevanssi'), $days30));
	echo '</div>';

	echo '<div style="clear: both"></div>';

	echo '<h3>' . __("Unsuccessful Queries", 'relevanssi') . '</h3>';

	echo "<div style='width: 30%; float: left; margin-right: 2%; overflow: scroll'>";
	relevanssi_date_queries(1, __("Today and yesterday", 'relevanssi'), 'bad');
	echo '</div>';

	echo "<div style='width: 30%; float: left; margin-right: 2%; overflow: scroll'>";
	relevanssi_date_queries(7, __("Last 7 days", 'relevanssi'), 'bad');
	echo '</div>';

	echo "<div style='width: 30%; float: left; margin-right: 2%; overflow: scroll'>";
	relevanssi_date_queries($days30, sprintf(__("Last %d days", 'relevanssi'), $days30), 'bad');
	echo '</div>';

	if ( current_user_can('manage_options') ) {

		echo '<div style="clear: both"></div>';
		$nonce = wp_nonce_field('relevanssi_reset_logs', '_relresnonce', true, false);
		echo '<h3>' . __('Reset Logs', 'relevanssi') . "</h3>\n";
		echo "<form method='post'>\n$nonce";
		echo "<p>";
		printf(__('To reset the logs, type "reset" into the box here %s and click %s', 'relevanssi'), ' <input type="text" name="relevanssi_reset_code" />', ' <input type="submit" name="relevanssi_reset" value="Reset" class="button" />');
		echo "</p></form>";

	}

	echo "</div>";
}

function relevanssi_total_queries( $title ) {
	global $wpdb, $relevanssi_variables;
	$log_table = $relevanssi_variables['log_table'];

	$count = array();

	$count[__('Today and yesterday', 'relevanssi')] = $wpdb->get_var("SELECT COUNT(id) FROM $log_table WHERE TIMESTAMPDIFF(DAY, time, NOW()) <= 1;");
	$count[__('Last 7 days', 'relevanssi')] = $wpdb->get_var("SELECT COUNT(id) FROM $log_table WHERE TIMESTAMPDIFF(DAY, time, NOW()) <= 7;");
	$count[__('Last 30 days', 'relevanssi')] = $wpdb->get_var("SELECT COUNT(id) FROM $log_table WHERE TIMESTAMPDIFF(DAY, time, NOW()) <= 30;");
	$count[__('Forever', 'relevanssi')] = $wpdb->get_var("SELECT COUNT(id) FROM $log_table;");

	echo "<table class='widefat'><thead><tr><th colspan='2'>$title</th></tr></thead><tbody><tr><th>" . __('When', 'relevanssi') . "</th><th>" . __('Searches', 'relevanssi') . "</th></tr>";
	foreach ($count as $when => $searches) {
		echo "<tr><td style='padding: 3px 5px'>$when</td><td style='padding: 3px 5px;'>$searches</td></tr>";
	}
	echo "</tbody></table>";

}

function relevanssi_date_queries($d, $title, $version = 'good') {
	global $wpdb, $relevanssi_variables;
	$log_table = $relevanssi_variables['log_table'];

	$limit = apply_filters('relevanssi_user_searches_limit', 20);

	if ($version == 'good')
		$queries = $wpdb->get_results("SELECT COUNT(DISTINCT(id)) as cnt, query, hits
		  FROM $log_table
		  WHERE TIMESTAMPDIFF(DAY, time, NOW()) <= $d
		  GROUP BY query
		  ORDER BY cnt DESC
		  LIMIT $limit");

	if ($version == 'bad')
		$queries = $wpdb->get_results("SELECT COUNT(DISTINCT(id)) as cnt, query, hits
		  FROM $log_table
		  WHERE TIMESTAMPDIFF(DAY, time, NOW()) <= $d
		    AND hits = 0
		  GROUP BY query
		  ORDER BY cnt DESC
		  LIMIT $limit");

	if (count($queries) > 0) {
		echo "<table class='widefat'><thead><tr><th colspan='3'>$title</th></tr></thead><tbody><tr><th>" . __('Query', 'relevanssi') . "</th><th>#</th><th>" . __('Hits', 'relevanssi') . "</th></tr>";
		foreach ($queries as $query) {
			$url = get_bloginfo('url');
			$u_q = urlencode($query->query);
			echo "<tr><td style='padding: 3px 5px'><a href='$url/?s=$u_q'>" . esc_attr($query->query) . "</a></td><td style='padding: 3px 5px; text-align: center'>" . $query->cnt . "</td><td style='padding: 3px 5px; text-align: center'>" . $query->hits . "</td></tr>";
		}
		echo "</tbody></table>";
	}
}

function relevanssi_options_form() {
	global $relevanssi_variables, $wpdb;

	wp_enqueue_style('dashboard');
	wp_print_styles('dashboard');
	wp_enqueue_script('dashboard');
	wp_print_scripts('dashboard');

	$docs_count = $wpdb->get_var("SELECT COUNT(DISTINCT doc) FROM " . $relevanssi_variables['relevanssi_table']);
	$terms_count = $wpdb->get_var("SELECT COUNT(*) FROM " . $relevanssi_variables['relevanssi_table']);
	$biggest_doc = $wpdb->get_var("SELECT doc FROM " . $relevanssi_variables['relevanssi_table'] . " ORDER BY doc DESC LIMIT 1");

	$serialize_options = array();

	$title_boost = get_option('relevanssi_title_boost');
	$serialize_options['relevanssi_title_boost'] = $title_boost;
	$comment_boost = get_option('relevanssi_comment_boost');
	$serialize_options['relevanssi_comment_boost'] = $comment_boost;
	$admin_search = get_option('relevanssi_admin_search');
	$serialize_options['relevanssi_admin_search'] = $admin_search;
	if ('on' == $admin_search) {
		$admin_search = 'checked="checked"';
	}
	else {
		$admin_search = '';
	}

	$index_limit = get_option('relevanssi_index_limit');
	$serialize_options['relevanssi_index_limit'] = $index_limit;

	$excerpts = get_option('relevanssi_excerpts');
	$serialize_options['relevanssi_excerpts'] = $excerpts;
	if ('on' == $excerpts) {
		$excerpts = 'checked="checked"';
	}
	else {
		$excerpts = '';
	}

	$excerpt_length = get_option('relevanssi_excerpt_length');
	$serialize_options['relevanssi_excerpt_length'] = $excerpt_length;
	$excerpt_type = get_option('relevanssi_excerpt_type');
	$serialize_options['relevanssi_excerpt_type'] = $excerpt_type;
	$excerpt_chars = "";
	$excerpt_words = "";
	switch ($excerpt_type) {
		case "chars":
			$excerpt_chars = 'selected="selected"';
			break;
		case "words":
			$excerpt_words = 'selected="selected"';
			break;
	}
	$excerpt_allowable_tags = get_option('relevanssi_excerpt_allowable_tags');
	$serialize_options['relevanssi_excerpt_allowable_tags'] = $excerpt_allowable_tags;

	$log_queries = get_option('relevanssi_log_queries');
	$serialize_options['relevanssi_log_queries'] = $log_queries;
	if ('on' == $log_queries) {
		$log_queries = 'checked="checked"';
	}
	else {
		$log_queries = '';
	}

	$log_queries_with_ip = get_option('relevanssi_log_queries_with_ip');
	$serialize_options['relevanssi_log_queries_with_ip'] = $log_queries_with_ip;
	if ('on' == $log_queries_with_ip) {
		$log_queries_with_ip = 'checked="checked"';
	}
	else {
		$log_queries_with_ip = '';
	}

	$hide_branding = get_option('relevanssi_hide_branding');
	$serialize_options['relevanssi_hide_branding'] = $hide_branding;
	if ('on' == $hide_branding) {
		$hide_branding = 'checked="checked"';
	}
	else {
		$hide_branding = '';
	}

	$highlight = get_option('relevanssi_highlight');
	$serialize_options['relevanssi_highlight'] = $highlight;
	$highlight_none = "";
	$highlight_mark = "";
	$highlight_em = "";
	$highlight_strong = "";
	$highlight_col = "";
	$highlight_bgcol = "";
	$highlight_style = "";
	$highlight_class = "";
	switch ($highlight) {
		case "no":
			$highlight_none = 'selected="selected"';
			break;
		case "mark":
			$highlight_mark = 'selected="selected"';
			break;
		case "em":
			$highlight_em = 'selected="selected"';
			break;
		case "strong":
			$highlight_strong = 'selected="selected"';
			break;
		case "col":
			$highlight_col = 'selected="selected"';
			break;
		case "bgcol":
			$highlight_bgcol = 'selected="selected"';
			break;
		case "css":
			$highlight_style = 'selected="selected"';
			break;
		case "class":
			$highlight_class = 'selected="selected"';
			break;
	}

	$index_fields = get_option('relevanssi_index_fields');
	$serialize_options['relevanssi_index_fields'] = $index_fields;

	$txt_col = get_option('relevanssi_txt_col');
	$serialize_options['relevanssi_txt_col'] = $txt_col;
	$bg_col = get_option('relevanssi_bg_col');
	$serialize_options['relevanssi_bg_col'] = $bg_col;
	$css = get_option('relevanssi_css');
	$serialize_options['relevanssi_css'] = $css;
	$class = get_option('relevanssi_class');
	$serialize_options['relevanssi_class'] = $class;

	$cat = get_option('relevanssi_cat');
	$serialize_options['relevanssi_cat'] = $cat;
	$excat = get_option('relevanssi_excat');
	$serialize_options['relevanssi_excat'] = $excat;
	$extag = get_option('relevanssi_extag');
	$serialize_options['relevanssi_extag'] = $extag;

	$fuzzy = get_option('relevanssi_fuzzy');
	$serialize_options['relevanssi_fuzzy'] = $fuzzy;
	$fuzzy_sometimes = ('sometimes' == $fuzzy ? 'selected="selected"' : '');
	$fuzzy_always = ('always' == $fuzzy ? 'selected="selected"' : '');
	$fuzzy_never = ('never' == $fuzzy ? 'selected="selected"' : '');

	$implicit = get_option('relevanssi_implicit_operator');
	$serialize_options['relevanssi_implicit_operator'] = $implicit;
	$implicit_and = ('AND' == $implicit ? 'selected="selected"' : '');
	$implicit_or = ('OR' == $implicit ? 'selected="selected"' : '');

	$expand_shortcodes = ('on' == get_option('relevanssi_expand_shortcodes') ? 'checked="checked"' : '');
	$serialize_options['relevanssi_expand_shortcodes'] = get_option('relevanssi_expand_shortcodes');
	$disablefallback = ('on' == get_option('relevanssi_disable_or_fallback') ? 'checked="checked"' : '');
	$serialize_options['relevanssi_disable_or_fallback'] = get_option('relevanssi_disable_or_fallback');

	$throttle = ('on' == get_option('relevanssi_throttle') ? 'checked="checked"' : '');
	$serialize_options['relevanssi_throttle'] = get_option('relevanssi_throttle');

	$throttle_limit = get_option('relevanssi_throttle_limit');
	$serialize_options['relevanssi_throttle_limit'] = $throttle_limit;

	$omit_from_logs	= get_option('relevanssi_omit_from_logs');
	$serialize_options['relevanssi_omit_from_logs'] = $omit_from_logs;

	$synonyms = get_option('relevanssi_synonyms');
	$serialize_options['relevanssi_synonyms'] = $synonyms;
	isset($synonyms) ? $synonyms = str_replace(';', "\n", $synonyms) : $synonyms = "";

	//Added by OdditY ->
	$expst = get_option('relevanssi_exclude_posts');
	$serialize_options['relevanssi_exclude_posts'] = $expst;
	$hititle = ('on' == get_option('relevanssi_hilite_title') ? 'checked="checked"' : '');
	$serialize_options['relevanssi_hilite_title'] = get_option('relevanssi_hilite_title');
	$incom_type = get_option('relevanssi_index_comments');
	$serialize_options['relevanssi_index_comments'] = $incom_type;
	$incom_type_all = "";
	$incom_type_normal = "";
	$incom_type_none = "";
	switch ($incom_type) {
		case "all":
			$incom_type_all = 'selected="selected"';
			break;
		case "normal":
			$incom_type_normal = 'selected="selected"';
			break;
		case "none":
			$incom_type_none = 'selected="selected"';
			break;
	}//added by OdditY END <-

	$highlight_docs = ('on' == get_option('relevanssi_highlight_docs') ? 'checked="checked"' : '');
	$highlight_coms = ('on' == get_option('relevanssi_highlight_comments') ? 'checked="checked"' : '');
	$serialize_options['relevanssi_highlight_docs'] = get_option('relevanssi_highlight_docs');
	$serialize_options['relevanssi_highlight_comments'] = get_option('relevanssi_highlight_comments');

	$respect_exclude = ('on' == get_option('relevanssi_respect_exclude') ? 'checked="checked"' : '');
	$serialize_options['relevanssi_respect_exclude'] = get_option('relevanssi_respect_exclude');

	$min_word_length = get_option('relevanssi_min_word_length');
	$serialize_options['relevanssi_min_word_length'] = $min_word_length;

	$index_author = ('on' == get_option('relevanssi_index_author') ? 'checked="checked"' : '');
	$serialize_options['relevanssi_index_author'] = get_option('relevanssi_index_author');
	$index_excerpt = ('on' == get_option('relevanssi_index_excerpt') ? 'checked="checked"' : '');
	$serialize_options['relevanssi_index_excerpt'] = get_option('relevanssi_index_excerpt');

	$show_matches = ('on' == get_option('relevanssi_show_matches') ? 'checked="checked"' : '');
	$serialize_options['relevanssi_show_matches'] = get_option('relevanssi_show_matches');
	$show_matches_text = stripslashes(get_option('relevanssi_show_matches_text'));
	$serialize_options['relevanssi_show_matches_text'] = get_option('relevanssi_show_matches_text');

	$wpml_only_current = ('on' == get_option('relevanssi_wpml_only_current') ? 'checked="checked"' : '');
	$serialize_options['relevanssi_wpml_only_current'] = get_option('relevanssi_wpml_only_current');

	$word_boundaries = ('on' == get_option('relevanssi_word_boundaries') ? 'checked="checked"' : '');
	$serialize_options['relevanssi_word_boundaries'] = get_option('relevanssi_word_boundaries');

	$post_type_weights = get_option('relevanssi_post_type_weights');
	$serialize_options['relevanssi_post_type_weights'] = $post_type_weights;

	$index_post_types = get_option('relevanssi_index_post_types');
	if (empty($index_post_types)) $index_post_types = array();
	$serialize_options['relevanssi_index_post_types'] = $index_post_types;

	$index_taxonomies_list = get_option('relevanssi_index_taxonomies_list');
	if (empty($index_taxonomies_list)) $index_taxonomies_list = array();
	$serialize_options['relevanssi_index_taxonomies_list'] = $index_taxonomies_list;

	$orderby = get_option('relevanssi_default_orderby');
	$serialize_options['relevanssi_default_orderby'] = $orderby;
	$orderby_relevance = ('relevance' == $orderby ? 'selected="selected"' : '');
	$orderby_date = ('post_date' == $orderby ? 'selected="selected"' : '');

	if (RELEVANSSI_PREMIUM) {
		$api_key = get_option('relevanssi_api_key');
		$serialize_options['relevanssi_api_key'] = $api_key;

		$link_boost = get_option('relevanssi_link_boost');
		$serialize_options['relevanssi_link_boost'] = $link_boost;

		$intlinks = get_option('relevanssi_internal_links');
		$serialize_options['relevanssi_internal_links'] = $intlinks;
		$intlinks_strip = ('strip' == $intlinks ? 'selected="selected"' : '');
		$intlinks_nostrip = ('nostrip' == $intlinks ? 'selected="selected"' : '');
		$intlinks_noindex = ('noindex' == $intlinks ? 'selected="selected"' : '');

		$highlight_docs_ext = ('on' == get_option('relevanssi_highlight_docs_external') ? 'checked="checked"' : '');
		$serialize_options['relevanssi_highlight_docs_external'] = get_option('relevanssi_highlight_docs_external');

		$thousand_separator = get_option('relevanssi_thousand_separator');
		$serialize_options['relevanssi_thousand_separator'] = $thousand_separator;

		$disable_shortcodes = get_option('relevanssi_disable_shortcodes');
		$serialize_options['relevanssi_disable_shortcodes'] = $disable_shortcodes;

		$index_users = ('on' == get_option('relevanssi_index_users') ? 'checked="checked"' : '');
		$serialize_options['relevanssi_index_users'] = get_option('relevanssi_index_users');

		$index_user_fields = get_option('relevanssi_index_user_fields');
		$serialize_options['relevanssi_index_user_fields'] = $index_user_fields;

		$index_subscribers = ('on' == get_option('relevanssi_index_subscribers') ? 'checked="checked"' : '');
		$serialize_options['relevanssi_index_subscribers'] = get_option('relevanssi_index_subscribers');

		$index_synonyms = ('on' == get_option('relevanssi_index_synonyms') ? 'checked="checked"' : '');
		$serialize_options['relevanssi_index_synonyms'] = get_option('relevanssi_index_synonyms');

		$index_taxonomies = ('on' == get_option('relevanssi_index_taxonomies') ? 'checked="checked"' : '');
		$serialize_options['relevanssi_index_taxonomies'] = get_option('relevanssi_index_taxonomies');

		$index_terms = get_option('relevanssi_index_terms');
		if (empty($index_terms)) $index_terms = array();
		$serialize_options['relevanssi_index_terms'] = $index_terms;

		$hide_post_controls = ('on' == get_option('relevanssi_hide_post_controls') ? 'checked="checked"' : '');
		$serialize_options['relevanssi_hide_post_controls'] = get_option('relevanssi_hide_post_controls');

		$recency_bonus_array = get_option('relevanssi_recency_bonus');
		$serialize_options['recency_bonus'] = $recency_bonus_array;
		$recency_bonus = $recency_bonus_array['bonus'];
		$recency_bonus_days = $recency_bonus_array['days'];

		$mysql_columns = get_option('relevanssi_mysql_columns');
		$serialize_options['relevanssi_mysql_columns'] = $mysql_columns;

		$serialized_options = json_encode($serialize_options);
	}

	echo "<div class='postbox-container' style='width:70%;'>";

	if (RELEVANSSI_PREMIUM) {
		echo "<form method='post' action='options-general.php?page=relevanssi-premium/relevanssi.php'>";
	}
	else {
		echo "<form method='post'>";
	}

	wp_nonce_field(plugin_basename($relevanssi_variables['file']), 'relevanssi_options'); ?>

    <p><a href="#basic"><?php _e("Basic options", "relevanssi"); ?></a> |
	<a href="#weights"><?php _e("Weights", "relevanssi"); ?></a> |
	<a href="#logs"><?php _e("Logs", "relevanssi"); ?></a> |
    <a href="#exclusions"><?php _e("Exclusions and restrictions", "relevanssi"); ?></a> |
    <a href="#excerpts"><?php _e("Custom excerpts", "relevanssi"); ?></a> |
    <a href="#highlighting"><?php _e("Highlighting search results", "relevanssi"); ?></a> |
    <a href="#indexing"><?php _e("Indexing options", "relevanssi"); ?></a> |
    <a href="#synonyms"><?php _e("Synonyms", "relevanssi"); ?></a> |
    <a href="#stopwords"><?php _e("Stopwords", "relevanssi"); ?></a> |
<?php
	if (RELEVANSSI_PREMIUM) {
    	echo '<a href="#options">' . __("Import/export options", "relevanssi") . '</a>';
    }
    else {
		echo '<strong><a href="https://www.relevanssi.com/buy-premium/?utm_source=plugin&utm_medium=link&utm_campaign=buy">' . __('Buy Relevanssi Premium', 'relevanssi') . '</a></strong>';
    }
?>
    </p>

	<h3><?php _e('Quick tools', 'relevanssi') ?></h3>
	<p>
	<input type='submit' name='submit' value='<?php esc_attr_e('Save options', 'relevanssi'); ?>' class='button-primary' />
	<input type="submit" name="index" value="<?php esc_attr_e('Build the index', 'relevanssi'); ?>" class='button-primary' />
	<input type="submit" name="index_extend" value="<?php esc_attr_e('Continue indexing', 'relevanssi'); ?>" class='button-secondary' />, <?php _e('add', 'relevanssi'); ?> <input type="text" size="4" name="relevanssi_index_limit" value="<?php echo $index_limit ?>" /> <?php _e('documents.', 'relevanssi'); ?></p>

<?php
	if (empty($index_post_types)) {
		echo "<p><strong>" . __("WARNING: You've chosen no post types to index. Nothing will be indexed. <a href='#indexing'>Choose some post types to index</a>.", 'relevanssi') . "</strong></p>";
	}
?>

	<p><?php _e("Use 'Build the index' to build the index with current <a href='#indexing'>indexing options</a>. If you can't finish indexing with one go, use 'Continue indexing' to finish the job. You can change the number of documents to add until you find the largest amount you can add with one go. See 'State of the Index' below to find out how many documents actually go into the index.", 'relevanssi') ?></p>

	<h3><?php _e("State of the Index", "relevanssi"); ?></h3>
	<p>
	<?php _e("Documents in the index", "relevanssi"); ?>: <strong><?php echo $docs_count ?></strong><br />
	<?php _e("Terms in the index", "relevanssi"); ?>: <strong><?php echo $terms_count ?></strong><br />
	<?php _e("Highest post ID indexed", "relevanssi"); ?>: <strong><?php echo $biggest_doc ?></strong>
	</p>

	<h3 id="basic"><?php _e("Basic options", "relevanssi"); ?></h3>

<?php
	if (function_exists('relevanssi_form_api_key')) relevanssi_form_api_key($api_key);
?>

	<label for='relevanssi_admin_search'><?php _e('Use search for admin:', 'relevanssi'); ?>
	<input type='checkbox' name='relevanssi_admin_search' id='relevanssi_admin_search' <?php echo $admin_search ?> /></label>
	<small><?php _e('If checked, Relevanssi will be used for searches in the admin interface', 'relevanssi'); ?></small>

	<br /><br />

	<label for='relevanssi_implicit_operator'><?php _e("Default operator for the search?", "relevanssi"); ?>
	<select name='relevanssi_implicit_operator' id='relevanssi_implicit_operator'>
	<option value='AND' <?php echo $implicit_and ?>><?php _e("AND - require all terms", "relevanssi"); ?></option>
	<option value='OR' <?php echo $implicit_or ?>><?php _e("OR - any term present is enough", "relevanssi"); ?></option>
	</select></label><br />
	<small><?php _e("If you choose AND and the search finds no matches, it will automatically do an OR search.", "relevanssi"); ?></small>

	<br /><br />

	<label for='relevanssi_disable_or_fallback'><?php _e("Disable OR fallback:", "relevanssi"); ?>
	<input type='checkbox' name='relevanssi_disable_or_fallback' id='relevanssi_disable_or_fallback' <?php echo $disablefallback ?> /></label><br />
	<small><?php _e("If you don't want Relevanssi to fall back to OR search when AND search gets no hits, check this option. For most cases, leave this one unchecked.", 'relevanssi'); ?></small>

	<br /><br />

	<label for='relevanssi_default_orderby'><?php _e('Default order for results:', 'relevanssi'); ?>
	<select name='relevanssi_default_orderby' id='relevanssi_default_orderby'>
	<option value='relevance' <?php echo $orderby_relevance ?>><?php _e("Relevance (highly recommended)", "relevanssi"); ?></option>
	<option value='post_date' <?php echo $orderby_date ?>><?php _e("Post date", "relevanssi"); ?></option>
	</select></label><br />
	<small><?php _e("If you want date-based results, see the recent post bonus in the Weights section.", "relevanssi"); ?></small>

	<br /><br />

	<label for='relevanssi_fuzzy'><?php _e('When to use fuzzy matching?', 'relevanssi'); ?>
	<select name='relevanssi_fuzzy' id='relevanssi_fuzzy'>
	<option value='sometimes' <?php echo $fuzzy_sometimes ?>><?php _e("When straight search gets no hits", "relevanssi"); ?></option>
	<option value='always' <?php echo $fuzzy_always ?>><?php _e("Always", "relevanssi"); ?></option>
	<option value='never' <?php echo $fuzzy_never ?>><?php _e("Don't use fuzzy search", "relevanssi"); ?></option>
	</select></label><br />
	<small><?php _e("Straight search matches just the term. Fuzzy search matches everything that begins or ends with the search term.", "relevanssi"); ?></small>

	<br /><br />

<?php
	if (function_exists('relevanssi_form_internal_links')) relevanssi_form_internal_links($intlinks_noindex, $intlinks_strip, $intlinks_nostrip);
?>

	<label for='relevanssi_throttle'><?php _e("Limit searches:", "relevanssi"); ?>
	<input type='checkbox' name='relevanssi_throttle' id='relevanssi_throttle' <?php echo $throttle ?> /></label><br />
	<small><?php _e("If this option is checked, Relevanssi will limit search results to at most 500 results per term (this number can be adjusted by changing the 'relevanssi_throttle_limit' option). This will improve performance, but may cause some relevant documents to go unfound. However, Relevanssi tries to prioritize the most relevant documents. <strong>This does not work when sorting results by date.</strong> The throttle can end up cutting off recent posts to favour more relevant posts.", 'relevanssi'); ?></small>

	<br /><br />

<?php
	if (function_exists('relevanssi_form_hide_post_controls')) relevanssi_form_hide_post_controls($hide_post_controls);
?>

	<h3 id="weights"><?php _e('Weights', 'relevanssi'); ?></h3>

	<p><?php _e('These values affect the weights of the documents. These are all multipliers, so 1 means no change in weight, less than 1 means less weight, and more than 1 means more weight. Setting something to zero makes that worthless. For example, if title weight is more than 1, words in titles are more significant than words elsewhere. If title weight is 0, words in titles won\'t make any difference to the search results.', 'relevanssi'); ?></p>

	<table class="widefat">
	<thead>
		<tr>
			<th><?php _e('Element', 'relevanssi'); ?></th>
			<th><?php _e('Weight', 'relevanssi'); ?></th>
			<th><?php _e('Default weight', 'relevanssi'); ?></th>
		</tr>
	</thead>
	<tr>
		<td>
			<?php _e('Post titles', 'relevanssi'); ?>
		</td>
		<td>
			<input type='text' name='relevanssi_title_boost' id='relevanssi_title_boost' size='4' value='<?php echo $title_boost ?>' />
		</td>
		<td>
			<?php echo $relevanssi_variables['title_boost_default']; ?>
		</td>
	</tr>
	<?php if (function_exists('relevanssi_form_link_weight')) relevanssi_form_link_weight($link_boost); ?>
	<tr>
		<td>
			<?php _e('Comment text', 'relevanssi'); ?>
		</td>
		<td>
			<input type='text' name='relevanssi_comment_boost' id='relevanssi_comment_boost' size='4' value='<?php echo $comment_boost ?>' />
		</td>
		<td>
			<?php echo $relevanssi_variables['comment_boost_default']; ?>
		</td>
	</tr>
	<?php
		if (function_exists('relevanssi_form_post_type_weights')) relevanssi_form_post_type_weights($post_type_weights);
		if (function_exists('relevanssi_form_taxonomy_weights')) relevanssi_form_taxonomy_weights($post_type_weights);
		if (function_exists('relevanssi_form_tag_weight')) relevanssi_form_tag_weight($post_type_weights);
	?>
	</table>

	<br /><br />

	<?php if (function_exists('relevanssi_form_recency')) relevanssi_form_recency($recency_bonus, $recency_bonus_days); ?>

	<?php if (function_exists('icl_object_id')) : ?>
	<h3 id="wpml"><?php _e('WPML/Polylang compatibility', 'relevanssi'); ?></h3>

	<label for='relevanssi_wpml_only_current'><?php _e("Limit results to current language:", "relevanssi"); ?>
	<input type='checkbox' name='relevanssi_wpml_only_current' id='relevanssi_wpml_only_current' <?php echo $wpml_only_current ?> /></label>
	<small><?php _e("If this option is checked, Relevanssi will only return results in the current active language. Otherwise results will include posts in every language.", "relevanssi");?></small>

	<?php endif; ?>

	<h3 id="logs"><?php _e('Logs', 'relevanssi'); ?></h3>

	<label for='relevanssi_log_queries'><?php _e("Keep a log of user queries:", "relevanssi"); ?>
	<input type='checkbox' name='relevanssi_log_queries' id='relevanssi_log_queries' <?php echo $log_queries ?> /></label>
	<small><?php _e("If checked, Relevanssi will log user queries. The log appears in 'User searches' on the Dashboard admin menu.", 'relevanssi'); ?></small>

	<br /><br />

	<label for='relevanssi_log_queries_with_ip'><?php _e("Log the user's IP with the queries:", "relevanssi"); ?>
	<input type='checkbox' name='relevanssi_log_queries_with_ip' id='relevanssi_log_queries_with_ip' <?php echo $log_queries_with_ip ?> /></label>
	<small><?php _e("If checked, Relevanssi will log user's IP-Adress with the queries.", 'relevanssi'); ?></small>

	<br /><br />

	<label for='relevanssi_omit_from_logs'><?php _e("Don't log queries from these users:", "relevanssi"); ?>
	<input type='text' name='relevanssi_omit_from_logs' id='relevanssi_omit_from_logs' size='20' value='<?php echo esc_attr($omit_from_logs); ?>' /></label>
	<small><?php _e("Comma-separated list of numeric user IDs or user login names that will not be logged.", "relevanssi"); ?></small>

<?php
	echo "<p>" . __("If you enable logs, you can see what your users are searching for. You can prevent your own searches from getting in the logs with the omit feature.", "relevanssi");
	if (!RELEVANSSI_PREMIUM) {
		echo " " . __("Logs are also needed to use the 'Did you mean?' feature.", "relevanssi");
	}
	echo "</p>";
?>

	<?php if (function_exists('relevanssi_form_hide_branding')) relevanssi_form_hide_branding($hide_branding); ?>

	<h3 id="exclusions"><?php _e("Exclusions and restrictions", "relevanssi"); ?></h3>

	<label for='relevanssi_cat'><?php _e('Restrict search to these categories and tags:', 'relevanssi'); ?>
	<input type='text' name='relevanssi_cat' id='relevanssi_cat' size='20' value='<?php echo esc_attr($cat); ?>' /></label><br />
	<small><?php _e("Enter a comma-separated list of category and tag IDs to restrict search to those categories or tags. You can also use <code>&lt;input type='hidden' name='cats' value='list of cats and tags' /&gt;</code> in your search form. The input field will 	overrun this setting.", 'relevanssi'); ?></small>

	<br /><br />

	<label for='relevanssi_excat'><?php _e('Exclude these categories from search:', 'relevanssi'); ?>
	<input type='text' name='relevanssi_excat' id='relevanssi_excat' size='20' value='<?php echo esc_attr($excat); ?>' /></label><br />
	<small><?php _e("Enter a comma-separated list of category IDs that are excluded from search results.", 'relevanssi'); ?></small>

	<br /><br />

	<label for='relevanssi_extag'><?php _e('Exclude these tags from search:', 'relevanssi'); ?>
	<input type='text' name='relevanssi_extag' id='relevanssi_extag' size='20' value='<?php echo esc_attr($extag); ?>' /></label><br />
	<small><?php _e("Enter a comma-separated list of tag IDs that are excluded from search results.", 'relevanssi'); ?></small>

	<br /><br />

	<label for='relevanssi_expst'><?php _e('Exclude these posts/pages from search:', 'relevanssi'); ?>
	<input type='text'  name='relevanssi_expst' id='relevanssi_expst' size='20' value='<?php echo esc_attr($expst); ?>' /></label><br />
<?php
	echo "<small>" . __("Enter a comma-separated list of post/page IDs that are excluded from search results. This only works here, you can't use the input field option (WordPress doesn't pass custom parameters there).", 'relevanssi');
	if (RELEVANSSI_PREMIUM) {
		echo " " . __("You can also use a checkbox on post/page edit pages to remove posts from index. This setting doesn't work in multisite searches, but the checkbox does.", 'relevanssi');
	}
	echo "</small>";
?>

	<br /><br />

	<label for='relevanssi_respect_exclude'><?php _e('Respect exclude_from_search for custom post types:', 'relevanssi'); ?>
	<input type='checkbox' name='relevanssi_respect_exclude' id='relevanssi_respect_exclude' <?php echo $respect_exclude ?> /></label><br />
	<small><?php _e("If checked, Relevanssi won't display posts of custom post types that have 'exclude_from_search' set to true. If not checked, Relevanssi will display anything that is indexed.", 'relevanssi'); ?></small>

	<h3 id="excerpts"><?php _e("Custom excerpts/snippets", "relevanssi"); ?></h3>

	<label for='relevanssi_excerpts'><?php _e("Create custom search result snippets:", "relevanssi"); ?>
	<input type='checkbox' name='relevanssi_excerpts' id='relevanssi_excerpts' <?php echo $excerpts ?> /></label><br />
	<small><?php _e("If checked, Relevanssi will create excerpts that contain the search term hits. To make them work, make sure your search result template uses the_excerpt() to display post excerpts.", 'relevanssi'); ?></small>

	<p><?php _e('Note: Building custom excerpts can be slow. If you are not actually using the excerpts, make sure you disable the option.', 'relevanssi'); ?></p>

	<label for='relevanssi_excerpt_length'><?php _e("Length of the snippet:", "relevanssi"); ?>
	<input type='text' name='relevanssi_excerpt_length' id='relevanssi_excerpt_length' size='4' value='<?php echo esc_attr($excerpt_length); ?>' /></label>
	<select name='relevanssi_excerpt_type' id='relevanssi_excerpt_type'>
	<option value='chars' <?php echo $excerpt_chars ?>><?php _e("characters", "relevanssi"); ?></option>
	<option value='words' <?php echo $excerpt_words ?>><?php _e("words", "relevanssi"); ?></option>
	</select><br />
	<small><?php _e("This must be an integer.", "relevanssi"); ?></small>

	<br /><br />

	<label for='relevanssi_excerpt_allowable_tags'><?php _e("Allowable tags in excerpts:", "relevanssi"); ?>
	<input type='text' name='relevanssi_excerpt_allowable_tags' id='relevanssi_excerpt_allowable_tags' size='20' value='<?php echo esc_attr($excerpt_allowable_tags); ?>' /></label>
	<br />
	<small><?php _e("List all tags you want to allow in excerpts, without any whitespace. For example: '&lt;p&gt;&lt;a&gt;&lt;strong&gt;'.", "relevanssi"); ?></small>

	<br /><br />

	<label for='relevanssi_show_matches'><?php _e("Show breakdown of search hits in excerpts:", "relevanssi"); ?>
	<input type='checkbox' name='relevanssi_show_matches' id='relevanssi_show_matches' <?php echo $show_matches ?> /></label>
	<small><?php _e("Check this to show more information on where the search hits were made. Requires custom snippets to work.", "relevanssi"); ?></small>

	<br /><br />

	<label for='relevanssi_show_matches_text'><?php _e("The breakdown format:", "relevanssi"); ?>
	<input type='text' name='relevanssi_show_matches_text' id='relevanssi_show_matches_text' value="<?php echo esc_attr($show_matches_text) ?>" size='20' /></label>
	<small><?php _e("Use %body%, %title%, %tags% and %comments% to display the number of hits (in different parts of the post), %total% for total hits, %score% to display the document weight and %terms% to show how many hits each search term got. No double quotes (\") allowed!", "relevanssi"); ?></small>

	<h3 id="highlighting"><?php _e("Search hit highlighting", "relevanssi"); ?></h3>

	<?php _e("First, choose the type of highlighting used:", "relevanssi"); ?><br />

	<div style='margin-left: 2em'>
	<label for='relevanssi_highlight'><?php _e("Highlight query terms in search results:", 'relevanssi'); ?>
	<select name='relevanssi_highlight' id='relevanssi_highlight'>
	<option value='no' <?php echo $highlight_none ?>><?php _e('No highlighting', 'relevanssi'); ?></option>
	<option value='mark' <?php echo $highlight_mark ?>>&lt;mark&gt;</option>
	<option value='em' <?php echo $highlight_em ?>>&lt;em&gt;</option>
	<option value='strong' <?php echo $highlight_strong ?>>&lt;strong&gt;</option>
	<option value='col' <?php echo $highlight_col ?>><?php _e('Text color', 'relevanssi'); ?></option>
	<option value='bgcol' <?php echo $highlight_bgcol ?>><?php _e('Background color', 'relevanssi'); ?></option>
	<option value='css' <?php echo $highlight_style ?>><?php _e("CSS Style", 'relevanssi'); ?></option>
	<option value='class' <?php echo $highlight_class ?>><?php _e("CSS Class", 'relevanssi'); ?></option>
	</select></label>
	<small><?php _e("Highlighting isn't available unless you use custom snippets", 'relevanssi'); ?></small>

	<br />

	<label for='relevanssi_hilite_title'><?php _e("Highlight query terms in result titles too:", 'relevanssi'); ?>
	<input type='checkbox' name='relevanssi_hilite_title' id='relevanssi_hilite_title' <?php echo $hititle ?> /></label>
	<small><?php _e("Highlight hits in titles of the search results. This doesn't work automatically but requires you to replace the_title() on the template with relevanssi_the_title().", 'relevanssi'); ?></small>

	<br />

	<label for='relevanssi_highlight_docs'><?php _e("Highlight query terms in documents from local searches:", 'relevanssi'); ?>
	<input type='checkbox' name='relevanssi_highlight_docs' id='relevanssi_highlight_docs' <?php echo $highlight_docs ?> /></label>
	<small><?php _e("Highlights hits when user opens the post from search results. This is based on HTTP referrer, so if that's blocked, there'll be no highlights.", "relevanssi"); ?></small>

	<br />

	<?php if (function_exists('relevanssi_form_highlight_external')) relevanssi_form_highlight_external($highlight_docs_ext); ?>

	<label for='relevanssi_highlight_comments'><?php _e("Highlight query terms in comments:", 'relevanssi'); ?>
	<input type='checkbox' name='relevanssi_highlight_comments' id='relevanssi_highlight_comments' <?php echo $highlight_coms ?> /></label>
	<small><?php _e("Highlights hits in comments when user opens the post from search results.", "relevanssi"); ?></small>

	<br />

	<label for='relevanssi_word_boundaries'><?php _e("Uncheck this if you use non-ASCII characters:", 'relevanssi'); ?>
	<input type='checkbox' name='relevanssi_word_boundaries' id='relevanssi_word_boundaries' <?php echo $word_boundaries ?> /></label>
	<small><?php _e("If you use non-ASCII characters (like Cyrillic alphabet) and the highlights don't work, uncheck this option to make highlights work.", "relevanssi"); ?></small>

	<br /><br />
	</div>

	<?php _e("Then adjust the settings for your chosen type:", "relevanssi"); ?><br />

	<div style='margin-left: 2em'>

	<label for='relevanssi_txt_col'><?php _e("Text color for highlights:", "relevanssi"); ?>
	<input type='text' name='relevanssi_txt_col' id='relevanssi_txt_col' size='7' value='<?php echo esc_attr($txt_col); ?>' /></label>
	<small><?php _e("Use HTML color codes (#rgb or #rrggbb)", "relevanssi"); ?></small>

	<br />

	<label for='relevanssi_bg_col'><?php _e("Background color for highlights:", "relevanssi"); ?>
	<input type='text' name='relevanssi_bg_col' id='relevanssi_bg_col' size='7' value='<?php echo esc_attr($bg_col); ?>' /></label>
	<small><?php _e("Use HTML color codes (#rgb or #rrggbb)", "relevanssi"); ?></small>

	<br />

	<label for='relevanssi_css'><?php _e("CSS style for highlights:", "relevanssi"); ?>
	<input type='text' name='relevanssi_css' id='relevanssi_css' size='30' value='<?php echo esc_attr($css); ?>' /></label>
	<small><?php _e("You can use any CSS styling here, style will be inserted with a &lt;span&gt;", "relevanssi"); ?></small>

	<br />

	<label for='relevanssi_class'><?php _e("CSS class for highlights:", "relevanssi"); ?>
	<input type='text' name='relevanssi_class' id='relevanssi_class' size='10' value='<?php echo esc_attr($class); ?>' /></label>
	<small><?php _e("Name a class here, search results will be wrapped in a &lt;span&gt; with the class", "relevanssi"); ?></small>

	</div>

	<br />
	<br />

	<input type='submit' name='submit' value='<?php esc_attr_e('Save the options', 'relevanssi'); ?>' class='button button-primary' />

	<h3 id="indexing"><?php _e('Indexing options', 'relevanssi'); ?></h3>

	<p><?php _e('Choose post types to index:', 'relevanssi'); ?></p>

	<table class="widefat" id="index_post_types_table">
	<thead>
		<tr>
			<th><?php _e('Type', 'relevanssi'); ?></th>
			<th><?php _e('Index', 'relevanssi'); ?></th>
			<th><?php _e('Excluded from search?', 'relevanssi'); ?></th>
		</tr>
	</thead>
	<?php
		$pt_1 = get_post_types(array('exclude_from_search' => '0'));
		$pt_2 = get_post_types(array('exclude_from_search' => false));
		$public_types = array_merge($pt_1, $pt_2);
		$post_types = get_post_types();
		foreach ($post_types as $type) {
			if ('nav_menu_item' == $type) continue;
			if ('revision' == $type) continue;
			if (in_array($type, $index_post_types)) {
				$checked = 'checked="checked"';
			}
			else {
				$checked = '';
			}
			$label = sprintf("%s", $type);
			in_array($type, $public_types) ? $public = __('no', 'relevanssi') : $public = __('yes', 'relevanssi');

			echo <<<EOH
	<tr>
		<td>
			$label
		</td>
		<td>
			<input type='checkbox' name='relevanssi_index_type_$type' id='relevanssi_index_type_$type' $checked />
		</td>
		<td>
			$public
		</td>
	</tr>
EOH;
		}
	?>
	<tr style="display:none">
		<td>
			Helpful little control field
		</td>
		<td>
			<input type='checkbox' name='relevanssi_index_type_bogus' id='relevanssi_index_type_bogus' checked="checked" />
		</td>
		<td>
			This is our little secret, just for you and me
		</td>
	</tr>
	</table>

	<p><?php printf(__('If you choose to index a post type that is excluded from the search, you may need to uncheck the "%s" option.', 'relevanssi'), __('Respect exclude_from_search for custom post types', 'relevanssi')); ?></p>
	<br /><br />

	<p><?php _e('Choose taxonomies to index:', 'relevanssi'); ?></p>

	<table class="widefat" id="custom_taxonomies_table">
	<thead>
		<tr>
			<th><?php _e('Taxonomy', 'relevanssi'); ?></th>
			<th><?php _e('Index', 'relevanssi'); ?></th>
			<th><?php _e('Public?', 'relevanssi'); ?></th>
		</tr>
	</thead>
	<?php
		$taxos = get_taxonomies('', 'objects');
		foreach ($taxos as $taxonomy) {
			if ($taxonomy->name == 'nav_menu') continue;
			if ($taxonomy->name == 'link_category') continue;
			if (in_array($taxonomy->name, $index_taxonomies_list)) {
				$checked = 'checked="checked"';
			}
			else {
				$checked = '';
			}
			$label = sprintf("%s", $taxonomy->name);
			$taxonomy->public ? $public = __('yes', 'relevanssi') : $public = __('no', 'relevanssi');
			$type = $taxonomy->name;

			echo <<<EOH
	<tr>
		<td>
			$label
		</td>
		<td>
			<input type='checkbox' name='relevanssi_index_taxonomy_$type' id='relevanssi_index_taxonomy_$type' $checked />
		</td>
		<td>
			$public
		</td>
	</tr>
EOH;
		}
	?>
	</table>

	<p><?php _e('If you check a taxonomy here, the terms for that taxonomy are indexed with the posts. If you for example choose "post_tag", searching for tags will find all posts that have the tag.', 'relevanssi'); ?>

	<br /><br />

	<label for='relevanssi_min_word_length'><?php _e("Minimum word length to index", "relevanssi"); ?>:
	<input type='text' name='relevanssi_min_word_length' id='relevanssi_min_word_length' size='30' value='<?php echo esc_attr($min_word_length); ?>' /></label><br />
	<small><?php _e("Words shorter than this number will not be indexed.", "relevanssi"); ?></small>

	<br /><br />

	<?php if (function_exists('relevanssi_form_thousep')) relevanssi_form_thousep($thousand_separator); ?>

	<label for='relevanssi_expand_shortcodes'><?php _e("Expand shortcodes in post content:", "relevanssi"); ?>
	<input type='checkbox' name='relevanssi_expand_shortcodes' id='relevanssi_expand_shortcodes' <?php echo $expand_shortcodes ?> /></label><br />
	<small><?php _e("If checked, Relevanssi will expand shortcodes in post content before indexing. Otherwise shortcodes will be stripped. If you use shortcodes to include dynamic content, Relevanssi will not keep the index updated, the index will reflect the status of the shortcode content at the moment of indexing.", "relevanssi"); ?></small>

	<br /><br />

<?php if (function_exists('relevanssi_form_disable_shortcodes')) relevanssi_form_disable_shortcodes($disable_shortcodes); ?>

	<label for='relevanssi_index_author'><?php _e('Index and search your posts\' authors:', 'relevanssi'); ?>
	<input type='checkbox' name='relevanssi_index_author' id='relevanssi_index_author' <?php echo $index_author ?> /></label><br />
	<small><?php _e("If checked, Relevanssi will also index and search the authors of your posts. Author display name will be indexed. Remember to rebuild the index if you change this option!", 'relevanssi'); ?></small>

	<br /><br />

	<label for='relevanssi_index_excerpt'><?php _e('Index and search post excerpts:', 'relevanssi'); ?>
	<input type='checkbox' name='relevanssi_index_excerpt' id='relevanssi_index_excerpt' <?php echo $index_excerpt ?> /></label><br />
	<small><?php _e("If checked, Relevanssi will also index and search the excerpts of your posts. Remember to rebuild the index if you change this option!", 'relevanssi'); ?></small>

	<br /><br />

	<label for='relevanssi_index_comments'><?php _e("Index and search these comments:", "relevanssi"); ?>
	<select name='relevanssi_index_comments' id='relevanssi_index_comments'>
	<option value='none' <?php echo $incom_type_none ?>><?php _e("none", "relevanssi"); ?></option>
	<option value='normal' <?php echo $incom_type_normal ?>><?php _e("normal", "relevanssi"); ?></option>
	<option value='all' <?php echo $incom_type_all ?>><?php _e("all", "relevanssi"); ?></option>
	</select></label><br />
	<small><?php _e("Relevanssi will index and search ALL (all comments including track- &amp; pingbacks and custom comment types), NONE (no comments) or NORMAL (manually posted comments on your blog).<br />Remember to rebuild the index if you change this option!", 'relevanssi'); ?></small>

	<br /><br />

	<label for='relevanssi_index_fields'><?php _e("Custom fields to index:", "relevanssi"); ?>
	<input type='text' name='relevanssi_index_fields' id='relevanssi_index_fields' size='30' value='<?php echo esc_attr($index_fields) ?>' /></label><br />
	<small><?php _e("A comma-separated list of custom fields to include in the index. Set to 'visible' to index all visible custom fields and to 'all' to index all custom fields, also those starting with a '_' character. With Relevanssi Premium, you can also use 'fieldname_%_subfieldname' notation for ACF repeater fields. You can use 'relevanssi_index_custom_fields' filter hook to adjust which custom fields are indexed.", "relevanssi"); ?></small>

	<br /><br />

<?php if (function_exists('relevanssi_form_mysql_columns')) relevanssi_form_mysql_columns($mysql_columns); ?>

<?php if (function_exists('relevanssi_form_index_users')) relevanssi_form_index_users($index_users, $index_subscribers, $index_user_fields); ?>

<?php if (function_exists('relevanssi_form_index_taxonomies')) relevanssi_form_index_taxonomies($index_taxonomies, $index_terms); ?>

	<input type='submit' name='index' value='<?php esc_attr_e("Save indexing options, erase index and rebuild the index", 'relevanssi'); ?>' class='button button-primary' />

	<input type='submit' name='index_extend' value='<?php esc_attr_e("Continue indexing", 'relevanssi'); ?>' class='button' />

	<h3 id="synonyms"><?php _e("Synonyms", "relevanssi"); ?></h3>

	<p><textarea name='relevanssi_synonyms' id='relevanssi_synonyms' rows='9' cols='60'><?php echo htmlspecialchars($synonyms); ?></textarea></p>

	<p><small><?php _e("Add synonyms here in 'key = value' format. When searching with the OR operator, any search of 'key' will be expanded to include 'value' as well. Using phrases is possible. The key-value pairs work in one direction only, but you can of course repeat the same pair reversed.", "relevanssi"); ?></small></p>

<?php if (function_exists('relevanssi_form_index_synonyms')) relevanssi_form_index_synonyms($index_synonyms); ?>

	<input type='submit' name='submit' value='<?php esc_attr_e('Save the options', 'relevanssi'); ?>' class='button' />

	<h3 id="stopwords"><?php _e("Stopwords", "relevanssi"); ?></h3>

	<?php relevanssi_show_stopwords(); ?>

<?php if (function_exists('relevanssi_form_importexport')) relevanssi_form_importexport($serialized_options); ?>

	</form>
</div>

	<?php

	relevanssi_sidebar();
}

function relevanssi_show_stopwords() {
	global $wpdb, $relevanssi_variables, $wp_version;

	RELEVANSSI_PREMIUM ? $plugin = 'relevanssi-premium' : $plugin = 'relevanssi';

	echo "<p>";
	_e("Enter a word here to add it to the list of stopwords. The word will automatically be removed from the index, so re-indexing is not necessary. You can enter many words at the same time, separate words with commas.", 'relevanssi');
	echo "</p>";

?><label for="addstopword"><p><?php _e("Stopword(s) to add: ", 'relevanssi'); ?><textarea name="addstopword" id="addstopword" rows="2" cols="40"></textarea>
<input type="submit" value="<?php esc_attr_e("Add", 'relevanssi'); ?>" class='button' /></p></label>
<p><?php

	_e("Here's a list of stopwords in the database. Click a word to remove it from stopwords. Removing stopwords won't automatically return them to index, so you need to re-index all posts after removing stopwords to get those words back to index.", 'relevanssi');

	if (function_exists("plugins_url")) {
		if (version_compare($wp_version, '2.8dev', '>' )) {
			$src = plugins_url('delete.png', $relevanssi_variables['file']);
		}
		else {
			$src = plugins_url($plugin . '/delete.png');
		}
	}
	else {
		// We can't check, so let's assume something sensible
		$src = '/wp-content/plugins/' . $plugin . '/delete.png';
	}

	echo "</p><ul>";
	$results = $wpdb->get_results("SELECT * FROM " . $relevanssi_variables['stopword_table']);
	$exportlist = array();
	foreach ($results as $stopword) {
		$sw = stripslashes($stopword->stopword);
		printf('<li style="display: inline;"><input type="submit" name="removestopword" value="%s"/></li>', esc_attr($sw));
		array_push($exportlist, $sw);
	}
	echo "</ul>";

?>
<p><input type="submit" name="removeallstopwords" value="<?php esc_attr_e('Remove all stopwords', 'relevanssi'); ?>" class='button' /></p>
<?php

	$exportlist = htmlspecialchars(implode(", ", $exportlist));

?>
<p><?php _e("Here's a list of stopwords you can use to export the stopwords to another blog.", "relevanssi"); ?></p>

<textarea name="stopwords" id="stopwords" rows="2" cols="40"><?php echo $exportlist; ?></textarea>
<?php

}
?>
