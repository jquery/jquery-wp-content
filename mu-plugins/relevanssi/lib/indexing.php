<?php

function relevanssi_build_index($extend = false, $verbose = true, $post_limit = null) {
	if (function_exists('wp_suspend_cache_addition'))
		wp_suspend_cache_addition(true);	// Thanks to Julien Mession

	global $wpdb, $relevanssi_variables;
	$relevanssi_table = $relevanssi_variables['relevanssi_table'];

	set_time_limit(0);

	$post_types = array();
	$types = get_option("relevanssi_index_post_types");
	if (!is_array($types)) $types = array();
	foreach ($types as $type) {
	 	if ($type == "bogus") continue;
		array_push($post_types, "'$type'");
	}

	if (count($post_types) > 0) {
		$restriction = " AND post.post_type IN (" . implode(', ', $post_types) . ') ';
	}
	else {
		$restriction = "";
	}

	$valid_status_array = apply_filters('relevanssi_valid_status', array('publish', 'draft', 'private', 'pending', 'future'));
	if (is_array($valid_status_array) && count($valid_status_array) > 0) {
		$valid_status = array();
		foreach ($valid_status_array as $status) {
			$valid_status[] = "'$status'";
		}
		$valid_status = implode(',', $valid_status);
	}
	else {
		// this really should never happen
		$valid_status = "'publish', 'draft', 'private', 'pending', 'future'";
	}

	$n = 0;
	$size = 0;

	if (!$extend) {
		// truncate table first
		relevanssi_truncate_index();

		if (function_exists('relevanssi_index_taxonomies')) {
			if (get_option('relevanssi_index_taxonomies') == 'on') {
				relevanssi_index_taxonomies();
			}
		}

		if (function_exists('relevanssi_index_users')) {
			if (get_option('relevanssi_index_users') == 'on') {
				relevanssi_index_users();
			}
		}

		// if post limit parameter is present, numeric and > 0, use that
		$limit = "";
		if (isset($post_limit) && is_numeric($post_limit) && $post_limit > 0) {
			$size = $post_limit;
			$limit = " LIMIT $post_limit";
		}

        $q = "SELECT post.ID
		FROM $wpdb->posts post
		LEFT JOIN $wpdb->posts parent ON (post.post_parent=parent.ID)
		WHERE
			(post.post_status IN ($valid_status)
			OR
			(post.post_status='inherit'
				AND(
					(parent.ID is not null AND (parent.post_status IN ($valid_status)))
					OR (post.post_parent=0)
				)
			))
		$restriction $limit";

		update_option('relevanssi_index', '');
	}
	else {
		// extending, so no truncate and skip the posts already in the index
		$limit = get_option('relevanssi_index_limit', 200);

		// if post limit parameter is present, numeric and > 0, use that
		if (isset($post_limit) && is_numeric($post_limit) && $post_limit > 0) $limit = $post_limit;

		if (is_numeric($limit) && $limit > 0) {
			$size = $limit;
			$limit = " LIMIT $limit";
		}
		else {
			$limit = "";
		}
        $q = "SELECT post.ID
		FROM $wpdb->posts post
		LEFT JOIN $wpdb->posts parent ON (post.post_parent=parent.ID)
		LEFT JOIN $relevanssi_table r ON (post.ID=r.doc)
		WHERE
		r.doc is null
		AND
			(post.post_status IN ($valid_status)
			OR
			(post.post_status='inherit'
				AND(
					(parent.ID is not null AND (parent.post_status IN ($valid_status)))
					OR (post.post_parent=0)
				)
			)
		)
		$restriction $limit";
	}

	$custom_fields = relevanssi_get_custom_fields();

	do_action('relevanssi_pre_indexing_query');
	$content = $wpdb->get_results($q);

	if ( defined( 'WP_CLI' ) && WP_CLI && function_exists('relevanssi_generate_progress_bar') ) $progress = relevanssi_generate_progress_bar( 'Indexing posts', count($content) );
	foreach ($content as $post) {
		$result = relevanssi_index_doc($post->ID, false, $custom_fields, true);
		if (is_numeric($result) && $result > 0) $n++;
		// n calculates the number of posts indexed
		// $bypassglobalpost set to true, because at this point global $post should be NULL, but in some cases it is not

		if ( defined( 'WP_CLI' ) && WP_CLI && $progress ) $progress->tick();
	}
	if ( defined( 'WP_CLI' ) && WP_CLI && $progress ) $progress->finish();

	$wpdb->query("ANALYZE TABLE $relevanssi_table");
	// To prevent empty indices

	$complete = false;
	if ($verbose) {
			if (($size == 0) || (count($content) < $size)) {
				$message = __("Indexing complete!", "relevanssi");
			}
			else {
				$message = __("More to index...", "relevanssi");
			}
	    echo '<div id="message" class="updated fade"><p>' . $message . '</p></div>';
	}
	else {
		if (($size == 0) || (count($content) < $size)) $complete = true;
	}

	update_option('relevanssi_indexed', 'done');

	// We always want to run this on init, if the index is finished building.
	$D = $wpdb->get_var("SELECT COUNT(DISTINCT(relevanssi.doc)) FROM $relevanssi_table AS relevanssi");
	update_option( 'relevanssi_doc_count', $D);

	if (function_exists('wp_suspend_cache_addition'))
		wp_suspend_cache_addition(false);	// Thanks to Julien Mession

	return array($complete, $n);
}

// BEGIN modified by renaissancehack
//  recieve $post argument as $indexpost, so we can make it the $post global.  This will allow shortcodes
//  that need to know what post is calling them to access $post->ID
/*
	Different cases:

	- 	Build index:
		global $post is NULL, $indexpost is a post object.

	-	Update post:
		global $post has the original $post, $indexpost is the ID of revision.

	-	Quick edit:
		global $post is an array, $indexpost is the ID of current revision.
*/
function relevanssi_index_doc($indexpost, $remove_first = false, $custom_fields = false, $bypassglobalpost = false, $debug = false) {
	global $wpdb, $post, $relevanssi_variables;
	$relevanssi_table = $relevanssi_variables['relevanssi_table'];
	$post_was_null = false;
	$previous_post = NULL;

	// Check if this is a Jetpack Contact Form entry
	if (isset($_REQUEST['contact-form-id'])) return;

	if ($bypassglobalpost) {
		// if $bypassglobalpost is set, relevanssi_index_doc() will index the post object or post
		// ID as specified in $indexpost
		isset($post) ?
			$previous_post = $post : $post_was_null = true;
		is_object($indexpost) ?
			$post = $indexpost : $post = get_post($indexpost);
	}
	else {
		// Quick edit has an array in the global $post, so fetch the post ID for the post to edit.
		if (is_array($post)) {
			$post = get_post($post['ID']);
		}

		if (empty($post)) {
			// No $post set, so we need to use $indexpost, if it's a post object
			$post_was_null = true;
			if (is_object($indexpost)) {
				$post = $indexpost;
			}
			else {
				$post = get_post($indexpost);
			}
		}
		else {
			// $post was set, let's grab the previous value in case we need it
			$previous_post = $post;
		}
	}

	if ($post == NULL) {
		// At this point we should have something in $post; if not, quit.
		if ($post_was_null) $post = null;
		if ($previous_post) $post = $previous_post;
		return -1;
	}

	// Finally fetch the post again by ID. Complicated, yes, but unless we do this, we might end
	// up indexing the post before the updates come in.
	$post = get_post($post->ID);

	if (function_exists('relevanssi_hide_post')) {
		if (relevanssi_hide_post($post->ID)) {
			if ($debug) relevanssi_debug_echo("relevanssi_hide_post() returned true.");
			if ($post_was_null) $post = null;
			if ($previous_post) $post = $previous_post;
			return "hide";
		}
	}

	$index_this_post = false;

	$post->indexing_content = true;
	$index_types = get_option('relevanssi_index_post_types');
	if (!is_array($index_types)) $index_types = array();
	if (in_array($post->post_type, $index_types)) $index_this_post = true;

	if (true == apply_filters('relevanssi_do_not_index', false, $post->ID)) {
		// filter says no
		if ($debug) relevanssi_debug_echo("relevanssi_do_not_index returned true.");
		$index_this_post = false;
	}

	if ($remove_first) {
		// we are updating a post, so remove the old stuff first
		relevanssi_remove_doc($post->ID, true);
		if (function_exists('relevanssi_remove_item')) {
			relevanssi_remove_item($post->ID, 'post');
		}
		if ($debug) relevanssi_debug_echo("Removed the post from the index.");
	}

	// This needs to be here, after the call to relevanssi_remove_doc(), because otherwise
	// a post that's in the index but shouldn't be there won't get removed.
	if (!$index_this_post) {
		if ($post_was_null) $post = null;
		if ($previous_post) $post = $previous_post;
		return "donotindex";
	}

	$n = 0;

	// The second parameter is useless here, but used elsewhere
	$post = apply_filters('relevanssi_post_to_index', $post, $post);

	$min_word_length = get_option('relevanssi_min_word_length', 3);
	$insert_data = array();

	//Added by OdditY - INDEX COMMENTS of the POST ->
	if ("none" != get_option("relevanssi_index_comments")) {
		if ($debug) relevanssi_debug_echo("Indexing comments.");
		$pcoms = relevanssi_get_comments($post->ID);
		if ($pcoms != "") {
			$pcoms = relevanssi_strip_invisibles($pcoms);
			$pcoms = preg_replace('/<[a-zA-Z\/][^>]*>/', ' ', $pcoms);
			$pcoms = strip_tags($pcoms);
			if ($debug) relevanssi_debug_echo("Comment content: $pcoms");
			$pcoms = relevanssi_tokenize($pcoms, true, $min_word_length);
			if (count($pcoms) > 0) {
				foreach ($pcoms as $pcom => $count) {
					$n++;
					$insert_data[$pcom]['comment'] = $count;
				}
			}
		}
	} //Added by OdditY END <-


	$taxonomies = get_option("relevanssi_index_taxonomies_list");

	// Then process all taxonomies, if any.
	foreach ($taxonomies as $taxonomy) {
		if ($debug) relevanssi_debug_echo("Indexing taxonomy terms for $taxonomy");
		$insert_data = relevanssi_index_taxonomy_terms($post, $taxonomy, $insert_data);
	}

	// index author
	if ("on" == get_option("relevanssi_index_author")) {
		$auth = $post->post_author;
		$display_name = $wpdb->get_var("SELECT display_name FROM $wpdb->users WHERE ID=$auth");
		$names = relevanssi_tokenize($display_name, false, $min_word_length);
		if ($debug) relevanssi_debug_echo("Indexing post author as: " . implode(" ", array_keys($names)));
		foreach($names as $name => $count) {
			isset($insert_data[$name]['author']) ? $insert_data[$name]['author'] += $count : $insert_data[$name]['author'] = $count;
		}
	}

	$remove_underscore_fields = false;
	if (isset($custom_fields) && $custom_fields == 'all')
		$custom_fields = get_post_custom_keys($post->ID);
	if (isset($custom_fields) && $custom_fields == 'visible') {
		$custom_fields = get_post_custom_keys($post->ID);
		$remove_underscore_fields = true;
	}
	$custom_fields = apply_filters('relevanssi_index_custom_fields', $custom_fields);

	if (is_array($custom_fields)) {
		if ($debug) relevanssi_debug_echo("Custom fields to index: " . implode(", ", $custom_fields));
		$custom_fields = array_unique($custom_fields);	// no reason to index duplicates

		$repeater_fields = array();
		if (function_exists('relevanssi_add_repeater_fields')) relevanssi_add_repeater_fields($custom_fields, $post->ID);

		foreach ($custom_fields as $field) {
			if ($remove_underscore_fields) {
				if (substr($field, 0, 1) == '_') continue;
			}
			$values = get_post_meta($post->ID, $field, false);
			if ("" == $values) continue;
			foreach ($values as $value) {
				// Quick hack : allow indexing of PODS relationship custom fields // TMV
				if (is_array($value) && isset($value['post_title'])) $value = $value['post_title'];
				relevanssi_index_acf($insert_data, $post->ID, $field, $value);
				if ($debug) relevanssi_debug_echo("\tKey: " . $field . " – value: " . $value);

				$value_tokens = relevanssi_tokenize($value, true, $min_word_length);
				foreach ($value_tokens as $token => $count) {
					isset($insert_data[$token]['customfield']) ? $insert_data[$token]['customfield'] += $count : $insert_data[$token]['customfield'] = $count;
					if (function_exists('relevanssi_customfield_detail')) {
						$insert_data = relevanssi_customfield_detail($insert_data, $token, $count, $field);
					}
				}
			}
		}
	}

	if (isset($post->post_excerpt) && ("on" == get_option("relevanssi_index_excerpt") || "attachment" == $post->post_type)) { // include excerpt for attachments which use post_excerpt for captions - modified by renaissancehack
		if ($debug) relevanssi_debug_echo("Indexing post excerpt: $post->post_excerpt");
		$excerpt_tokens = relevanssi_tokenize($post->post_excerpt, true, $min_word_length);
		foreach ($excerpt_tokens as $token => $count) {
			isset($insert_data[$token]['excerpt']) ? $insert_data[$token]['excerpt'] += $count : $insert_data[$token]['excerpt'] = $count;
		}
	}

	if (function_exists('relevanssi_index_mysql_columns')) {
		if ($debug) relevanssi_debug_echo("Indexing MySQL columns.");
		$insert_data = relevanssi_index_mysql_columns($insert_data, $post->ID);
	}

	$index_titles = true;
	if (!empty($post->post_title)) {
		if (apply_filters('relevanssi_index_titles', $index_titles)) {
			if ($debug) relevanssi_debug_echo("Indexing post title.");
			$filtered_title = apply_filters('relevanssi_post_title_before_tokenize', $post->post_title, $post);
			$titles = relevanssi_tokenize(apply_filters('the_title', $filtered_title, $post->ID), apply_filters('relevanssi_remove_stopwords_in_titles', true), $min_word_length);
			if ($debug) relevanssi_debug_echo("\tTitle, tokenized: " . implode(" ", array_keys($titles)));

			if (count($titles) > 0) {
				foreach ($titles as $title => $count) {
					$n++;
					isset($insert_data[$title]['title']) ? $insert_data[$title]['title'] += $count : $insert_data[$title]['title'] = $count;
				}
			}
		}
	}

	$index_content = true;
	if (apply_filters('relevanssi_index_content', $index_content)) {
		if ($debug) relevanssi_debug_echo("Indexing post content.");
		remove_shortcode('noindex');
		add_shortcode('noindex', 'relevanssi_noindex_shortcode_indexing');

		$contents = apply_filters('relevanssi_post_content', $post->post_content, $post);
		if ($debug) relevanssi_debug_echo("\tPost content after relevanssi_post_content:\n$contents");

		// Allow user to add extra content for Relevanssi to index
		// Thanks to Alexander Gieg
		$additional_content = trim(apply_filters('relevanssi_content_to_index', '', $post));
		if ('' != $additional_content) {
			$contents .= ' '.$additional_content;
			if ($debug) relevanssi_debug_echo("\tAdditional content from relevanssi_content_to_index:\n$contents");
		}

		if ('on' == get_option('relevanssi_expand_shortcodes')) {
			if (function_exists("do_shortcode")) {
				// WP Table Reloaded support
				if (defined('WP_TABLE_RELOADED_ABSPATH')) {
					include_once(WP_TABLE_RELOADED_ABSPATH . 'controllers/controller-frontend.php');
					$My_WP_Table_Reloaded = new WP_Table_Reloaded_Controller_Frontend();
				}
				// TablePress support
				if (defined('TABLEPRESS_ABSPATH')) {
					if (!isset(TablePress::$model_options)) {
						include_once(TABLEPRESS_ABSPATH . 'classes/class-model.php');
						include_once(TABLEPRESS_ABSPATH . 'models/model-options.php');
						TablePress::$model_options = new TablePress_Options_Model();
					}
					$My_TablePress_Controller = TablePress::load_controller( 'frontend' );
					$My_TablePress_Controller->init_shortcodes();
				}

				$disable_shortcodes = get_option('relevanssi_disable_shortcodes');
				$shortcodes = explode(',', $disable_shortcodes);
				foreach ($shortcodes as $shortcode) {
					remove_shortcode(trim($shortcode));
				}
				remove_shortcode('contact-form');			// Jetpack Contact Form causes an error message
				remove_shortcode('starrater');				// GD Star Rating rater shortcode causes problems
				remove_shortcode('responsive-flipbook');	// Responsive Flipbook causes problems
				remove_shortcode('avatar_upload');			// WP User Avatar is incompatible
				remove_shortcode('product_categories');		// A problematic WooCommerce shortcode
				remove_shortcode('recent_products');		// A problematic WooCommerce shortcode
				remove_shortcode('php');					// PHP Code for Posts
				remove_shortcode('watupro');				// Watu PRO doesn't co-operate
				remove_shortcode('starbox');				// Starbox shortcode breaks Relevanssi
				remove_shortcode('cfdb-save-form-post');	// Contact Form DB
				remove_shortcode('cfdb-datatable');
				remove_shortcode('cfdb-table');
				remove_shortcode('cfdb-json');
				remove_shortcode('cfdb-value');
				remove_shortcode('cfdb-count');
				remove_shortcode('cfdb-html');
				remove_shortcode('woocommerce_cart');		// WooCommerce
				remove_shortcode('woocommerce_checkout');
				remove_shortcode('woocommerce_order_tracking');
				remove_shortcode('woocommerce_my_account');
				remove_shortcode('woocommerce_edit_account');
				remove_shortcode('woocommerce_change_password');
				remove_shortcode('woocommerce_view_order');
				remove_shortcode('woocommerce_logout');
				remove_shortcode('woocommerce_pay');
				remove_shortcode('woocommerce_thankyou');
				remove_shortcode('woocommerce_lost_password');
				remove_shortcode('woocommerce_edit_address');
				remove_shortcode('tc_process_payment');
				remove_shortcode('maxmegamenu');			// Max Mega Menu
				remove_shortcode('searchandfilter');		// Search and Filter
				remove_shortcode('downloads');				// Easy Digital Downloads
				remove_shortcode('download_history');
				remove_shortcode('purchase_history');
				remove_shortcode('download_checkout');
				remove_shortcode('purchase_link');
				remove_shortcode('download_cart');
				remove_shortcode('edd_profile_editor');
				remove_shortcode('edd_login');
				remove_shortcode('edd_register');
				remove_shortcode('swpm_protected');			// Simple Membership Partially Protected content

				$post_before_shortcode = $post;
				$contents = do_shortcode($contents);
				$post = $post_before_shortcode;

				if (defined('TABLEPRESS_ABSPATH')) {
					unset($My_TablePress_Controller);
				}
				if (defined('WP_TABLE_RELOADED_ABSPATH')) {
					unset($My_WP_Table_Reloaded);
				}
			}
		}
		else {
			$contents = strip_shortcodes($contents);
		}

		remove_shortcode('noindex');
		add_shortcode('noindex', 'relevanssi_noindex_shortcode');

		$contents = relevanssi_strip_invisibles($contents);

		if (function_exists('relevanssi_process_internal_links')) {
			$contents = relevanssi_process_internal_links($contents, $post->ID);
		}

		$contents = preg_replace('/<[a-zA-Z\/][^>]*>/', ' ', $contents);
		$contents = strip_tags($contents);
		if (function_exists('wp_encode_emoji')) $contents = wp_encode_emoji($contents);
		$contents = apply_filters('relevanssi_post_content_before_tokenize', $contents, $post);
		$contents = relevanssi_tokenize($contents, true, $min_word_length);

		if ($debug) relevanssi_debug_echo("\tContent, tokenized:\n" . implode(" ", array_keys($contents)));

		if (count($contents) > 0) {
			foreach ($contents as $content => $count) {
		 		$n++;
				isset($insert_data[$content]['content']) ? $insert_data[$content]['content'] += $count : $insert_data[$content]['content'] = $count;
			}
		}
	}

	$type = 'post';
	if ($post->post_type == 'attachment') $type = 'attachment';

	$insert_data = apply_filters('relevanssi_indexing_data', $insert_data, $post);

	$values = array();
	foreach ($insert_data as $term => $data) {
		$content = 0;
		$title = 0;
		$comment = 0;
		$tag = 0;
		$link = 0;
		$author = 0;
		$category = 0;
		$excerpt = 0;
		$taxonomy = 0;
		$customfield = 0;
		$taxonomy_detail = '';
		$customfield_detail = '';
		$mysqlcolumn = 0;
		extract($data);

		$term = trim($term);

		$value = $wpdb->prepare("(%d, %s, REVERSE(%s), %d, %d, %d, %d, %d, %d, %d, %d, %d, %d, %s, %s, %s, %d)",
			$post->ID, $term, $term, $content, $title, $comment, $tag, $link, $author, $category, $excerpt, $taxonomy, $customfield, $type, $taxonomy_detail, $customfield_detail, $mysqlcolumn);

		array_push($values, $value);
	}

	$values = apply_filters('relevanssi_indexing_values', $values, $post);

	if (!empty($values)) {
		$values = implode(', ', $values);
		$query = "INSERT IGNORE INTO $relevanssi_table (doc, term, term_reverse, content, title, comment, tag, link, author, category, excerpt, taxonomy, customfield, type, taxonomy_detail, customfield_detail, mysqlcolumn)
			VALUES $values";
		if ($debug) relevanssi_debug_echo("Final indexing query:\n\t$query");
		$wpdb->query($query);
	}

	if ($post_was_null) $post = null;
	if ($previous_post) $post = $previous_post;

	return $n;
}

/**
 * Index taxonomy terms for given post and given taxonomy.
 *
 * @since 1.8
 * @param object $post Post object.
 * @param string $taxonomy Taxonomy name.
 * @param array $insert_data Insert query data array.
 * @return array Updated insert query data array.
 */
function relevanssi_index_taxonomy_terms($post = null, $taxonomy = "", $insert_data) {
	global $wpdb, $relevanssi_variables;
	$relevanssi_table = $relevanssi_variables['relevanssi_table'];

	$n = 0;

	if (null == $post) return $insert_data;
	if ("" == $taxonomy) return $insert_data;

	$min_word_length = get_option('relevanssi_min_word_length', 3);
	$ptagobj = get_the_terms($post->ID, $taxonomy);
	if ($ptagobj !== FALSE) {
		$tagstr = "";
		foreach ($ptagobj as $ptag) {
			if (is_object($ptag)) {
				$tagstr .= $ptag->name . ' ';
			}
		}
		$tagstr = apply_filters('relevanssi_tag_before_tokenize', trim($tagstr));
		$ptags = relevanssi_tokenize($tagstr, true, $min_word_length);
		if (count($ptags) > 0) {
			foreach ($ptags as $ptag => $count) {
				$n++;

				if ('post_tags' == $taxonomy) {
					$insert_data[$ptag]['tag'] = $count;
				}
				else if ('category' == $taxonomy) {
					$insert_data[$ptag]['category'] = $count;
				}
				else {
					if (isset($insert_data[$ptag]['taxonomy'])) {
						$insert_data[$ptag]['taxonomy'] += $count;
					}
					else {
						$insert_data[$ptag]['taxonomy'] = $count;
					}
				}
				if (isset($insert_data[$ptag]['taxonomy_detail'])) {
					$tax_detail = unserialize($insert_data[$ptag]['taxonomy_detail']);
				}
				else {
					$tax_detail = array();
				}
				if (isset($tax_detail[$taxonomy])) {
					$tax_detail[$taxonomy] += $count;
				}
				else {
					$tax_detail[$taxonomy] = $count;
				}
				$insert_data[$ptag]['taxonomy_detail'] = serialize($tax_detail);
			}
		}
	}
	return $insert_data;
}

// BEGIN added by renaissancehack
function relevanssi_update_child_posts($new_status, $old_status, $post) {
// called by 'transition_post_status' action hook when a post is edited/published/deleted
//  and calls appropriate indexing function on child posts/attachments
    global $wpdb;

	// Safety check, for WordPress Editorial Calendar incompatibility
	if (!isset($post) || !isset($post->ID)) return;

	$index_statuses = apply_filters('relevanssi_valid_status', array('publish', 'private', 'draft', 'pending', 'future'));
    if (($new_status == $old_status)
          || (in_array($new_status, $index_statuses) && in_array($old_status, $index_statuses))
          || (in_array($post->post_type, array('attachment', 'revision')))) {
        return;
    }

    $q = "SELECT * FROM $wpdb->posts WHERE post_parent=$post->ID AND post_type!='revision'";
    $child_posts = $wpdb->get_results($q);
    if ($child_posts) {
        if (!in_array($new_status, $index_statuses)) {
            foreach ($child_posts as $post) {
                relevanssi_delete($post->ID);
            }
        } else {
            foreach ($child_posts as $post) {
                relevanssi_publish($post->ID);
            }
        }
    }
}
// END added by renaissancehack

function relevanssi_edit($post) {
	// Check if the post is public
	global $wpdb;

	$post_status = get_post_status($post);
	if ('auto-draft' == $post_status) return;

// BEGIN added by renaissancehack
    //  if post_status is "inherit", get post_status from parent
    if ($post_status == 'inherit') {
        $post_type = $wpdb->get_var("SELECT post_type FROM $wpdb->posts WHERE ID=$post");
    	$post_status = $wpdb->get_var("SELECT p.post_status FROM $wpdb->posts p, $wpdb->posts c WHERE c.ID=$post AND c.post_parent=p.ID");
    }
// END added by renaissancehack

	$index_statuses = apply_filters('relevanssi_valid_status', array('publish', 'private', 'draft', 'pending', 'future'));
	if (!in_array($post_status, $index_statuses)) {
 		// The post isn't supposed to be indexed anymore, remove it from index
 		relevanssi_remove_doc($post);
	}
	else {
		relevanssi_publish($post);
	}
}

function relevanssi_delete($post) {
	relevanssi_remove_doc($post);
}

function relevanssi_publish($post, $bypassglobalpost = false) {
	global $relevanssi_publish_doc;

	$post_status = get_post_status($post);
	if ('auto-draft' == $post_status) return;

	$custom_fields = relevanssi_get_custom_fields();
	relevanssi_index_doc($post, true, $custom_fields, $bypassglobalpost);
}

// added by lumpysimon
// when we're using wp_insert_post to update a post,
// we don't want to use the global $post object
function relevanssi_insert_edit($post_id) {
	global $wpdb;

	$post_status = get_post_status( $post_id );
	if ( 'auto-draft' == $post_status ) return;

    if ( $post_status == 'inherit' ) {
        $post_type = $wpdb->get_var( "SELECT post_type FROM $wpdb->posts WHERE ID=$post_id" );
	    $post_status = $wpdb->get_var( "SELECT p.post_status FROM $wpdb->posts p, $wpdb->posts c WHERE c.ID=$post_id AND c.post_parent=p.ID" );
    }

	$index_statuses = apply_filters('relevanssi_valid_status', array('publish', 'private', 'draft', 'future', 'pending'));
	if ( !in_array( $post_status, $index_statuses ) ) {
		// The post isn't supposed to be indexed anymore, remove it from index
		relevanssi_remove_doc( $post_id );
	}
	else {
		$bypassglobalpost = true;
		relevanssi_publish($post_id, $bypassglobalpost);
	}
}

//Added by OdditY ->
function relevanssi_comment_edit($comID) {
	relevanssi_comment_index($comID,$action="update");
}

function relevanssi_comment_remove($comID) {
	relevanssi_comment_index($comID,$action="remove");
}

function relevanssi_comment_index($comID,$action="add") {
	global $wpdb;
	$comtype = get_option("relevanssi_index_comments");
	switch ($comtype) {
		case "all":
			// all (incl. customs, track-&pingbacks)
			break;
		case "normal":
			// normal (excl. customs, track-&pingbacks)
			$restriction=" AND comment_type='' ";
			break;
		default:
			// none (don't index)
			return ;
	}
	switch ($action) {
		case "update":
			//(update) comment status changed:
			$cpostID = $wpdb->get_var("SELECT comment_post_ID FROM $wpdb->comments WHERE comment_ID='$comID'".$restriction);
			break;
		case "remove":
			//(remove) approved comment will be deleted (if not approved, its not in index):
			$cpostID = $wpdb->get_var("SELECT comment_post_ID FROM $wpdb->comments WHERE comment_ID='$comID' AND comment_approved='1'".$restriction);
			if($cpostID!=NULL) {
				//empty comment_content & reindex, then let WP delete the empty comment
				$wpdb->query("UPDATE $wpdb->comments SET comment_content='' WHERE comment_ID='$comID'");
			}
			break;
		default:
			// (add) new comment:
			$cpostID = $wpdb->get_var("SELECT comment_post_ID FROM $wpdb->comments WHERE comment_ID='$comID' AND comment_approved='1'".$restriction);
			break;
	}
	if($cpostID!=NULL) relevanssi_publish($cpostID);
}
//Added by OdditY END <-

function relevanssi_get_comments($postID) {
	global $wpdb;

	if (apply_filters('relevanssi_index_comments_exclude', false, $postID))
		return "";

	$comtype = get_option("relevanssi_index_comments");
	$restriction = "";
	$comment_string = "";
	switch ($comtype) {
		case "all":
			// all (incl. customs, track- & pingbacks)
			break;
		case "normal":
			// normal (excl. customs, track- & pingbacks)
			$restriction=" AND comment_type='' ";
			break;
		default:
			// none (don't index)
			return "";
	}

	$to = 20;
	$from = 0;

	while ( true ) {
		$sql = "SELECT 	comment_ID, comment_content, comment_author
				FROM 	$wpdb->comments
				WHERE 	comment_post_ID = '$postID'
				AND 	comment_approved = '1'
				".$restriction."
				LIMIT 	$from, $to";
		$comments = $wpdb->get_results($sql);
		if (sizeof($comments) == 0) break;
		foreach($comments as $comment) {
			$comment_string .= apply_filters('relevanssi_comment_content_to_index', $comment->comment_author . ' ' . $comment->comment_content . ' ', $comment->comment_ID);
		}
		$from += $to;
	}

	return $comment_string;
}

/**
 * Indexes the human-readable value of "choice" options list ACF
 * Droz Raphaël, June 2016
 */
function relevanssi_index_acf(&$insert_data, $post_id, $field_name, $field_value) {
	if (!is_admin() ) include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); // otherwise is_plugin_active will cause a fatal error
	if (!is_plugin_active('advanced-custom-fields/acf.php') &&
		!is_plugin_active('advanced-custom-fields-pro/acf.php')) return;

	if (!function_exists('get_field_object')) return; // ACF is active, but not loaded.

	$field_object = get_field_object($field_name, $post_id);
	if (!isset($field_object['choices'])) return; // not a "select" field
	if (is_array($field_value)) return; // not handled (currently)
	if (!isset($field_object['choices'][$field_value])) return; // value does not exist

	if ( ($value = $field_object['choices'][$field_value]) ) {
		if (!isset($insert_data[$value]['customfield']) ) $insert_data[$value]['customfield'] = 0;
		$insert_data[$value]['customfield']++;
	}
}

/**
 * Truncates the Relevanssi index.
 */
function relevanssi_truncate_index() {
	global $wpdb, $relevanssi_variables;
	$relevanssi_table = $relevanssi_variables['relevanssi_table'];
	return $wpdb->query("TRUNCATE TABLE $relevanssi_table");
}

?>
