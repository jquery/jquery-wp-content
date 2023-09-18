<?php

add_action('admin_menu', 'relevanssi_menu');
add_filter('the_posts', 'relevanssi_query', 99, 2);
add_action('delete_post', 'relevanssi_delete');
add_action('comment_post', 'relevanssi_comment_index'); 	//added by OdditY
add_action('edit_comment', 'relevanssi_comment_edit'); 		//added by OdditY
add_action('delete_comment', 'relevanssi_comment_remove'); 	//added by OdditY
add_action('wp_insert_post', 'relevanssi_insert_edit', 99, 1 ); // added by lumpysimon
// BEGIN added by renaissancehack
// *_page and *_post hooks do not trigger on attachments
add_action('delete_attachment', 'relevanssi_delete');
add_action('add_attachment', 'relevanssi_publish');
add_action('edit_attachment', 'relevanssi_edit');
// When a post status changes, check child posts that inherit their status from parent
add_action('transition_post_status', 'relevanssi_update_child_posts',99,3);
// END added by renaissancehack
add_action('init', 'relevanssi_init');
add_action('admin_head', 'relevanssi_check_old_data', 99);
add_filter('relevanssi_hits_filter', 'relevanssi_wpml_filter');
add_filter('posts_request', 'relevanssi_prevent_default_request', 10, 2 );
add_filter('relevanssi_remove_punctuation', 'relevanssi_remove_punct');
add_filter('relevanssi_post_ok', 'relevanssi_default_post_ok', 9, 2);
add_filter('relevanssi_query_filter', 'relevanssi_limit_filter');
add_filter('query_vars', 'relevanssi_query_vars');
add_filter('relevanssi_indexing_values', 'relevanssi_update_doc_count', 98, 2);

global $relevanssi_variables;
register_activation_hook($relevanssi_variables['file'], 'relevanssi_install');

function relevanssi_init() {
	global $pagenow, $relevanssi_variables, $wpdb;
	$plugin_dir = dirname(plugin_basename($relevanssi_variables['file']));
	load_plugin_textdomain('relevanssi', false, $plugin_dir);

	isset($_POST['index']) ? $index = true : $index = false;
	if (!get_option('relevanssi_indexed') && !$index) {
		function relevanssi_warning() {
			RELEVANSSI_PREMIUM ? $plugin = 'relevanssi-premium' : $plugin = 'relevanssi';
			echo "<div id='relevanssi-warning' class='update-nag'><p><strong>"
			   . __('You do not have an index! Remember to build the index (click the "Build the index" button), otherwise searching won\'t work.', 'relevanssi')
			   . "</strong></p></div>";
		}
		if ( 'options-general.php' == $pagenow and isset( $_GET['page'] ) and plugin_basename($relevanssi_variables['file']) == $_GET['page'] ) {
			add_action('admin_notices', 'relevanssi_warning');
		} else {
			// We always want to run this on init, if the index is finishd building.
			$relevanssi_table = $relevanssi_variables['relevanssi_table'];
			$D = $wpdb->get_var("SELECT COUNT(DISTINCT(relevanssi.doc)) FROM $relevanssi_table AS relevanssi");
			update_option( 'relevanssi_doc_count', $D);
 		}
 	}

	if (!function_exists('mb_internal_encoding')) {
		function relevanssi_mb_warning() {
			echo "<div id='relevanssi-warning' class='error'><p><strong>"
			   . __("Multibyte string functions are not available. Relevanssi may not work well without them. Please install (or ask your host to install) the mbstring extension.", 'relevanssi')
			   . "</strong></p></div>";
		}
		if ( 'options-general.php' == $pagenow and isset( $_GET['page'] ) and plugin_basename($relevanssi_variables['file']) == $_GET['page'] )
			add_action('admin_notices', 'relevanssi_mb_warning');
	}

	if (get_option('relevanssi_highlight_docs', 'off') != 'off') {
		add_filter('the_content', 'relevanssi_highlight_in_docs', 11);
	}
	if (get_option('relevanssi_highlight_comments', 'off') != 'off') {
		add_filter('comment_text', 'relevanssi_highlight_in_docs', 11);
	}

	return;
}

function relevanssi_menu() {
	global $relevanssi_variables;
	RELEVANSSI_PREMIUM ? $name = "Relevanssi Premium" : $name = "Relevanssi";
	add_options_page(
		$name,
		$name,
		apply_filters('relevanssi_options_capability', 'manage_options'),
		$relevanssi_variables['file'],
		'relevanssi_options'
	);
	add_dashboard_page(
		__('User searches', 'relevanssi'),
		__('User searches', 'relevanssi'),
		apply_filters('relevanssi_user_searches_capability', 'edit_pages'),
		$relevanssi_variables['file'],
		'relevanssi_search_stats'
	);
}

function relevanssi_query_vars($qv) {
	$qv[] = 'cats';
	$qv[] = 'tags';
	$qv[] = 'post_types';
	$qv[] = 'by_date';

	return $qv;
}

function relevanssi_create_database_tables($relevanssi_db_version) {
	global $wpdb;

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

	$charset_collate_bin_column = '';
	$charset_collate = '';

	if (!empty($wpdb->charset)) {
        $charset_collate_bin_column = "CHARACTER SET $wpdb->charset";
		$charset_collate = "DEFAULT $charset_collate_bin_column";
	}
	if (strpos($wpdb->collate, "_") > 0) {
        $charset_collate_bin_column .= " COLLATE " . substr($wpdb->collate, 0, strpos($wpdb->collate, '_')) . "_bin";
        $charset_collate .= " COLLATE $wpdb->collate";
    } else {
    	if ($wpdb->collate == '' && $wpdb->charset == "utf8") {
	        $charset_collate_bin_column .= " COLLATE utf8_bin";
	    }
    }

	$relevanssi_table = $wpdb->prefix . "relevanssi";
	$relevanssi_stopword_table = $wpdb->prefix . "relevanssi_stopwords";
	$relevanssi_log_table = $wpdb->prefix . "relevanssi_log";

	if(get_option('relevanssi_db_version') != $relevanssi_db_version) {
		if ($relevanssi_db_version == 1) {
			if($wpdb->get_var("SHOW TABLES LIKE '$relevanssi_table'") == $relevanssi_table) {
				$sql = "DROP TABLE $relevanssi_table";
				$wpdb->query($sql);
			}
			delete_option('relevanssi_indexed');
		}

		$sql = "CREATE TABLE " . $relevanssi_table . " (doc bigint(20) NOT NULL DEFAULT '0',
		term varchar(50) NOT NULL DEFAULT '0',
		term_reverse varchar(50) NOT NULL DEFAULT '0',
		content mediumint(9) NOT NULL DEFAULT '0',
		title mediumint(9) NOT NULL DEFAULT '0',
		comment mediumint(9) NOT NULL DEFAULT '0',
		tag mediumint(9) NOT NULL DEFAULT '0',
		link mediumint(9) NOT NULL DEFAULT '0',
		author mediumint(9) NOT NULL DEFAULT '0',
		category mediumint(9) NOT NULL DEFAULT '0',
		excerpt mediumint(9) NOT NULL DEFAULT '0',
		taxonomy mediumint(9) NOT NULL DEFAULT '0',
		customfield mediumint(9) NOT NULL DEFAULT '0',
		mysqlcolumn mediumint(9) NOT NULL DEFAULT '0',
		taxonomy_detail longtext NOT NULL,
		customfield_detail longtext NOT NULL,
		mysqlcolumn_detail longtext NOT NULL,
		type varchar(210) NOT NULL DEFAULT 'post',
		item bigint(20) NOT NULL DEFAULT '0',
	    UNIQUE KEY doctermitem (doc, term, item)) $charset_collate";

		dbDelta($sql);

		$sql = "SHOW INDEX FROM $relevanssi_table";
		$indices = $wpdb->get_results($sql);

		$terms_exists = false;
		$relevanssi_term_reverse_idx_exists = false;
		$docs_exists = false;
		$typeitem_exists = false;
		foreach ($indices as $index) {
			if ($index->Key_name == 'terms') $terms_exists = true;
			if ($index->Key_name == 'relevanssi_term_reverse_idx') $relevanssi_term_reverse_idx_exists = true;
			if ($index->Key_name == 'docs') $docs_exists = true;
			if ($index->Key_name == 'typeitem') $typeitem_exists = true;
		}

		if (!$terms_exists) {
			$sql = "CREATE INDEX terms ON $relevanssi_table (term(20))";
			$wpdb->query($sql);
		}

		if (!$relevanssi_term_reverse_idx_exists) {
			$sql = "CREATE INDEX relevanssi_term_reverse_idx ON $relevanssi_table (term_reverse(10))";
			$wpdb->query($sql);
		}

		if (!$docs_exists) {
			$sql = "CREATE INDEX docs ON $relevanssi_table (doc)";
			$wpdb->query($sql);
		}

		if (!$typeitem_exists) {
			$sql = "CREATE INDEX typeitem ON $relevanssi_table (type, item)";
			$wpdb->query($sql);
		}

		$sql = "CREATE TABLE " . $relevanssi_stopword_table . " (stopword varchar(50) $charset_collate_bin_column NOT NULL,
	    UNIQUE KEY stopword (stopword)) $charset_collate;";

		dbDelta($sql);

		$sql = "CREATE TABLE " . $relevanssi_log_table . " (id bigint(9) NOT NULL AUTO_INCREMENT,
		query varchar(200) NOT NULL,
		hits mediumint(9) NOT NULL DEFAULT '0',
		time timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		user_id bigint(20) NOT NULL DEFAULT '0',
		ip varchar(40) NOT NULL DEFAULT '',
	    UNIQUE KEY id (id)) $charset_collate;";

		dbDelta($sql);

		if (RELEVANSSI_PREMIUM && get_option('relevanssi_db_version') < 12) {
			$charset_collate_bin_column = '';
			$charset_collate = '';

			if (!empty($wpdb->charset)) {
				$charset_collate_bin_column = "CHARACTER SET $wpdb->charset";
				$charset_collate = "DEFAULT $charset_collate_bin_column";
			}
			if (strpos($wpdb->collate, "_") > 0) {
				$charset_collate_bin_column .= " COLLATE " . substr($wpdb->collate, 0, strpos($wpdb->collate, '_')) . "_bin";
				$charset_collate .= " COLLATE $wpdb->collate";
			} else {
				if ($wpdb->collate == '' && $wpdb->charset == "utf8") {
					$charset_collate_bin_column .= " COLLATE utf8_bin";
				}
			}

			$sql = "ALTER TABLE $relevanssi_stopword_table MODIFY COLUMN stopword varchar(50) $charset_collate_bin_column NOT NULL";
			$wpdb->query($sql);
			$sql = "ALTER TABLE $relevanssi_log_table ADD COLUMN user_id bigint(20) NOT NULL DEFAULT '0'";
			$wpdb->query($sql);
			$sql = "ALTER TABLE $relevanssi_log_table ADD COLUMN ip varchar(40) NOT NULL DEFAULT ''";
			$wpdb->query($sql);
		}

		if (get_option('relevanssi_db_version') < 16) {
			$sql = "ALTER TABLE $relevanssi_table ADD COLUMN term_reverse VARCHAR(50);";
			$wpdb->query($sql);
			$sql = "UPDATE $relevanssi_table SET term_reverse = REVERSE(term);";
			$wpdb->query($sql);
			$sql = "CREATE INDEX relevanssi_term_reverse_idx ON $relevanssi_table (term_reverse(10));";
			$wpdb->query($sql);
		}

		update_option('relevanssi_db_version', $relevanssi_db_version);
	}

	if ($wpdb->get_var("SELECT COUNT(*) FROM $relevanssi_stopword_table WHERE 1") < 1) {
		relevanssi_populate_stopwords();
	}
}

function relevanssi_action_links ($links) {
	$root = "relevanssi";
	if (RELEVANSSI_PREMIUM) $root = "relevanssi-premium";
	$relevanssi_links = array(
 		'<a href="' . admin_url( 'options-general.php?page=' . $root . '/relevanssi.php' ) . '">' . __('Settings', 'relevanssi') . '</a>',
 	);
	if (!RELEVANSSI_PREMIUM) {
		$relevanssi_links[] = '<a href="https://www.relevanssi.com/buy-premium/">' . __('Go Premium!', 'relevanssi') . '</a>';
	}
	return array_merge($links, $relevanssi_links);
}

?>
