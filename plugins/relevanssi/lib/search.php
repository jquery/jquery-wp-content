<?php

function relevanssi_query($posts, $query = false) {
	$admin_search = get_option('relevanssi_admin_search');
	($admin_search == 'on') ? $admin_search = true : $admin_search = false;

	global $relevanssi_active;

	if (!$query) return $posts;

	$search_ok = true; 							// we will search!
	if (!$query->is_search()) {
		$search_ok = false;						// no, we can't
	}
	if (!$query->is_main_query()) {
		$search_ok = false;						// no, we can't
	}

	// Uses $wp_query->is_admin instead of is_admin() to help with Ajax queries that
	// use 'admin_ajax' hook (which sets is_admin() to true whether it's an admin search
	// or not.
	if ($query->is_search() && $query->is_admin) {
		$search_ok = false; 					// but if this is an admin search, reconsider
		if ($admin_search) $search_ok = true; 	// yes, we can search!
	}

	if ($query->is_admin && empty($query->query_vars['s'])) {
		$search_ok = false;
	}

	// Disable search in media library search
	if ($search_ok) {
		if ($query->query_vars['post_type'] == 'attachment' && $query->query_vars['post_status'] == 'inherit,private') {
			$search_ok = false;
		}
	}

	$search_ok = apply_filters('relevanssi_search_ok', $search_ok);

	if ($relevanssi_active) {
		$search_ok = false;						// Relevanssi is already in action
	}

	if ($search_ok) {
		$query = apply_filters('relevanssi_modify_wp_query', $query);
		$posts = relevanssi_do_query($query);
	}
	
	return $posts;
}


// This is my own magic working.
function relevanssi_search($args) {
	global $wpdb, $relevanssi_variables;
	$relevanssi_table = $relevanssi_variables['relevanssi_table'];

	$filtered_args = apply_filters( 'relevanssi_search_filters', $args );
	$q = $filtered_args['q'];
	$tax_query = $filtered_args['tax_query'];
	$tax_query_relation = $filtered_args['tax_query_relation'];
	$post_query = $filtered_args['post_query'];
	$parent_query = $filtered_args['parent_query'];
	$meta_query = $filtered_args['meta_query'];
	$date_query = $filtered_args['date_query'];
	$expost = $filtered_args['expost'];
	$post_type = $filtered_args['post_type'];
	$post_status = $filtered_args['post_status'];
	$operator = $filtered_args['operator'];
	$search_blogs = $filtered_args['search_blogs'];
	$author = $filtered_args['author'];
	$orderby = $filtered_args['orderby'];
	$order = $filtered_args['order'];
	$fields = $filtered_args['fields'];
	$sentence = $filtered_args['sentence'];

	$hits = array();

	$query_restrictions = "";
	if (!isset($tax_query_relation)) $tax_query_relation = "or";
	$tax_query_relation = strtolower($tax_query_relation);
	$term_tax_id = array();
	$term_tax_ids = array();
	$not_term_tax_ids = array();
	$and_term_tax_ids = array();

	if (is_array($tax_query)) {
		$is_sub_row = false;
		foreach ($tax_query as $row) {
			if (isset($row['terms'])) {
				list($query_restrictions, $term_tax_ids, $not_term_tax_ids, $and_term_tax_ids) = relevanssi_process_tax_query_row($row, $is_sub_row, $tax_query_relation, $query_restrictions, $tax_query_relation, $term_tax_ids, $not_term_tax_ids, $and_term_tax_ids);
			}
			else {
				$row_tax_query_relation = $tax_query_relation;
				if (isset($row['relation'])) $row_tax_query_relation = strtolower($row['relation']);
				foreach ($row as $subrow) {
					$is_sub_row = true;
					if (isset($subrow['terms'])) {
						list($query_restrictions, $term_tax_ids, $not_term_tax_ids, $and_term_tax_ids) = relevanssi_process_tax_query_row($subrow, $is_sub_row, $tax_query_relation, $query_restrictions, $tax_query_relation, $term_tax_ids, $not_term_tax_ids, $and_term_tax_ids);
						// For subrows, we only want the query_restrictions
					}
				}
			}
		}

		if ($tax_query_relation == 'or') {
			$term_tax_ids = array_unique($term_tax_ids);
			if (count($term_tax_ids) > 0) {
				$term_tax_ids = implode(',', $term_tax_ids);
				$query_restrictions .= " AND relevanssi.doc IN (SELECT DISTINCT(tr.object_id) FROM $wpdb->term_relationships AS tr
			    	WHERE tr.term_taxonomy_id IN ($term_tax_ids))";
			    // Clean: all variables are Relevanssi-generated
			}
			if (count($not_term_tax_ids) > 0) {
				$not_term_tax_ids = implode(',', $not_term_tax_ids);
				$query_restrictions .= " AND relevanssi.doc NOT IN (SELECT DISTINCT(tr.object_id) FROM $wpdb->term_relationships AS tr
			    	WHERE tr.term_taxonomy_id IN ($not_term_tax_ids))";
			    // Clean: all variables are Relevanssi-generated
			}
			if (count($and_term_tax_ids) > 0) {
				$and_term_tax_ids = implode(',', $and_term_tax_ids);
				$n = count(explode(',', $and_term_tax_ids));
				$query_restrictions .= " AND relevanssi.doc IN (
					SELECT ID FROM $wpdb->posts WHERE 1=1
					AND (
						SELECT COUNT(1)
						FROM $wpdb->term_relationships AS tr
						WHERE tr.term_taxonomy_id IN ($and_term_tax_ids)
						AND tr.object_id = $wpdb->posts.ID ) = $n
					)";
			    // Clean: all variables are Relevanssi-generated
			}
		}
	}

	if (is_array($post_query)) {
		if (!empty($post_query['in'])) {
			$valid_values = array();
			foreach($post_query['in'] as $post_in_id) {
				if (is_numeric($post_in_id)) $valid_values[] = $post_in_id;
			}
			$posts = implode(',', $valid_values);
			if (!empty($posts)) $query_restrictions .= " AND relevanssi.doc IN ($posts)";
			// Clean: $posts is checked to be integers
		}
		if (!empty($post_query['not in'])) {
			$valid_values = array();
			foreach($post_query['not in'] as $post_not_in_id) {
				if (is_numeric($post_not_in_id)) $valid_values[] = $post_not_in_id;
			}
			$posts = implode(',', $valid_values);
			if (!empty($posts)) $query_restrictions .= " AND relevanssi.doc NOT IN ($posts)";
			// Clean: $posts is checked to be integers
		}
	}

	if (is_array($parent_query)) {
		if (!empty($parent_query['parent in'])) {
			$valid_values = array();
			foreach($parent_query['parent in'] as $post_in_id) {
				if (is_numeric($post_in_id)) $valid_values[] = $post_in_id;
			}
			$posts = implode(',', $valid_values);
			if (!empty($posts)) $query_restrictions .= " AND relevanssi.doc IN (SELECT ID FROM $wpdb->posts WHERE post_parent IN ($posts))";
			// Clean: $posts is checked to be integers
		}
		if (!empty($parent_query['parent not in'])) {
			$valid_values = array();
			foreach($parent_query['parent not in'] as $post_not_in_id) {
				if (is_numeric($post_not_in_id)) $valid_values[] = $post_not_in_id;
			}
			$posts = implode(',', $valid_values);
			if (!empty($posts)) $query_restrictions .= " AND relevanssi.doc NOT IN (SELECT ID FROM $wpdb->posts WHERE post_parent IN ($posts))";
			// Clean: $posts is checked to be integers
		}
	}

	if (is_array($meta_query)) {
		$meta_query_restrictions = "";

		$mq_vars = array('meta_query' => $meta_query );

		$mq = new WP_Meta_Query();
		$mq->parse_query_vars( $mq_vars );
		$meta_sql = $mq->get_sql('post', 'relevanssi', 'doc');
		$meta_join = "";
		$meta_where = "";
		if ($meta_sql) {
			$meta_join = $meta_sql['join'];
			$meta_where = $meta_sql['where'];
		}

		$query_restrictions .= $meta_where;
	}

	if (!empty($date_query)) {
		if (is_object($date_query) && method_exists($date_query, 'get_sql')) {
			$sql = $date_query->get_sql(); // AND ( the query itself )
			$query_restrictions .= " AND relevanssi.doc IN ( SELECT DISTINCT(ID) FROM $wpdb->posts WHERE 1 $sql )";
			// Clean: $sql generated by $date_query->get_sql() query
		}
	}

	// If $post_type is not set, see if there are post types to exclude from the search.
	// If $post_type is set, there's no need to exclude, as we only include.
	!$post_type ? $negative_post_type = relevanssi_get_negative_post_type() : $negative_post_type = NULL;

	$non_post_post_type = NULL;
	$non_post_post_types_array = array();
	if (function_exists('relevanssi_get_non_post_post_types')) {
		$non_post_post_types_array = relevanssi_get_non_post_post_types();
	}

	if ($post_type) {
		if ($post_type == -1) $post_type = null; // Facetious sets post_type to -1 if not selected
		if (!is_array($post_type)) {
			$post_types = explode(',', $post_type);
		}
		else {
			$post_types = $post_type;
		}
		// This array will contain all regular post types involved in the search parameters.
		$post_post_types = array_diff($post_types, $non_post_post_types_array);

		// This array has the non-post post types involved.
		$non_post_post_types = array_intersect($post_types, $non_post_post_types_array);

		// Escape both for SQL queries, just in case.
		$non_post_post_types = esc_sql($non_post_post_types);
		$post_types = esc_sql($post_post_types);

		// Implode to a parameter string, or set to NULL if empty.
		$non_post_post_type = count($non_post_post_types) ? "'" . implode( "', '", $non_post_post_types) . "'" : NULL;
		$post_type = count($post_types) ? "'" . implode( "', '", $post_types) . "'" : NULL;
	}

	if ($post_status) {
		if (!is_array($post_status)) {
			$post_statuses = esc_sql(explode(',', $post_status));
		}
		else {
			$post_statuses = esc_sql($post_status);
		}

		$post_status = count($post_statuses) ? "'" . implode( "', '", $post_statuses) . "'" : NULL;
	}

	//Added by OdditY:
	//Exclude Post_IDs (Pages) for non-admin search ->
	$postex = '';
	if (!empty($expost)) {
		if ($expost != "") {
			$aexpids = explode(",",$expost);
			foreach ($aexpids as $exid){
				$exid = esc_sql(trim($exid, ' -'));
				$postex .= " AND relevanssi.doc != '$exid'";
				// Clean: escaped
			}
		}
	}
	// <- OdditY End

	if ($expost) { //added by OdditY
		$query_restrictions .= $postex;
	}

	$remove_stopwords = apply_filters('relevanssi_remove_stopwords_in_titles', true);
	if (function_exists('wp_encode_emoji')) $q = wp_encode_emoji($q);

	if ($sentence) {
		$q = str_replace('"', '', $q);
		$q = '"' . $q . '"';
	}

	$phrases = relevanssi_recognize_phrases($q);

	if (function_exists('relevanssi_recognize_negatives')) {
		$negative_terms = relevanssi_recognize_negatives($q);
	}
	else {
		$negative_terms = false;
	}

	if (function_exists('relevanssi_recognize_positives')) {
		$positive_terms = relevanssi_recognize_positives($q);
	}
	else {
		$positive_terms = false;
	}

	$terms = relevanssi_tokenize($q, $remove_stopwords);

	if (count($terms) < 1) {
		// Tokenizer killed all the search terms.
		return $hits;
	}
	$terms = array_keys($terms); // don't care about tf in query

	if ($negative_terms) {
		$terms = array_diff($terms, $negative_terms);
/*		if (count($terms) < 1) {
			return $hits;
		}
*/	}

	// Go get the count from the options table, but keep running the full query if it's not available
	$D = get_option('relevanssi_doc_count');
	if (!$D || $D < 1) {
		$D = $wpdb->get_var("SELECT COUNT(DISTINCT(relevanssi.doc)) FROM $relevanssi_table AS relevanssi");
		// Clean: no external inputs
		update_option('relevanssi_doc_count', $D);
	}

	$total_hits = 0;

	$title_matches = array();
	$tag_matches = array();
	$comment_matches = array();
	$link_matches = array();
	$body_matches = array();
	$category_matches = array();
	$taxonomy_matches = array();
	$scores = array();
	$term_hits = array();

	$fuzzy = get_option('relevanssi_fuzzy');

	if (function_exists('relevanssi_negatives_positives')) {
		$query_restrictions .= relevanssi_negatives_positives($negative_terms, $positive_terms, $relevanssi_table);
		// Clean: escaped in the function
	}

	if (!empty($author)) {
		$author_in = array();
		$author_not_in = array();
		foreach ($author as $id) {
			if (!is_numeric($id)) continue;
			if ($id > 0) {
				$author_in[] = $id;
			}
			else {
				$author_not_in[] = abs($id);
			}
		}
		if (count($author_in) > 0) {
			$authors = implode(',', $author_in);
			$query_restrictions .= " AND relevanssi.doc IN (SELECT DISTINCT(posts.ID) FROM $wpdb->posts AS posts
			    WHERE posts.post_author IN ($authors))";
			// Clean: $authors is always just numbers
		}
		if (count($author_not_in) > 0) {
			$authors = implode(',', $author_not_in);
			$query_restrictions .= " AND relevanssi.doc NOT IN (SELECT DISTINCT(posts.ID) FROM $wpdb->posts AS posts
			    WHERE posts.post_author IN ($authors))";
			// Clean: $authors is always just numbers
		}
	}

	if ($post_type) {
		// A post type is set: add a restriction
		$restriction = " AND (
			relevanssi.doc IN (
				SELECT DISTINCT(posts.ID) FROM $wpdb->posts AS posts
				WHERE posts.post_type IN ($post_type)
			) *np*
		)";
		// Clean: $post_type is escaped

		// There are post types involved that are taxonomies or users, so can't
		// match to wp_posts. Add a relevanssi.type restriction.
		if ($non_post_post_type) {
			$restriction = str_replace('*np*', "OR (relevanssi.type IN ($non_post_post_type))", $restriction);
			// Clean: $non_post_post_types is escaped
		} else {
			// No non-post post types, so remove the placeholder.
			$restriction = str_replace('*np*', '', $restriction);
		}
		$query_restrictions .= $restriction;
	}
	else {
		// No regular post types
		if ($non_post_post_type) {
			// But there is a non-post post type restriction.
			$query_restrictions .= " AND (relevanssi.type IN ($non_post_post_type))";
			// Clean: $non_post_post_types is escaped
		}
	}

	if ($negative_post_type) {
		$query_restrictions .= " AND ((relevanssi.doc IN (SELECT DISTINCT(posts.ID) FROM $wpdb->posts AS posts
			WHERE posts.post_type NOT IN ($negative_post_type))) OR (doc = -1))";
		// Clean: $negative_post_type is escaped
	}

	if ($post_status) {
		global $wp_query;
		if ($wp_query->is_admin) {
			$query_restrictions .= " AND ((relevanssi.doc IN (SELECT DISTINCT(posts.ID) FROM $wpdb->posts AS posts
				WHERE posts.post_status IN ($post_status))))";
		}
		else {
			// the -1 is there to get user profiles and category pages
			$query_restrictions .= " AND ((relevanssi.doc IN (SELECT DISTINCT(posts.ID) FROM $wpdb->posts AS posts
				WHERE posts.post_status IN ($post_status))) OR (doc = -1))";
		}
		// Clean: $post_status is escaped
	}

	if ($phrases) {
		$query_restrictions .= " $phrases";
		// Clean: $phrases is escaped earlier
	}

	if (isset($_REQUEST['by_date'])) {
		$n = $_REQUEST['by_date'];

		$u = substr($n, -1, 1);
		switch ($u) {
			case 'h':
				$unit = "HOUR";
				break;
			case 'd':
				$unit = "DAY";
				break;
			case 'm':
				$unit = "MONTH";
				break;
			case 'y':
				$unit = "YEAR";
				break;
			case 'w':
				$unit = "WEEK";
				break;
			default:
				$unit = "DAY";
		}

		$n = preg_replace('/[hdmyw]/', '', $n);

		if (is_numeric($n)) {
			$query_restrictions .= " AND relevanssi.doc IN (SELECT DISTINCT(posts.ID) FROM $wpdb->posts AS posts
				WHERE posts.post_date > DATE_SUB(NOW(), INTERVAL $n $unit))";
			// Clean: $n is always numeric, $unit is Relevanssi-generated
		}
	}

	$query_restrictions = apply_filters('relevanssi_where', $query_restrictions); // Charles St-Pierre
	$query_join = "";
	if (!empty($meta_join)) $query_join = $meta_join;
	$query_join = apply_filters('relevanssi_join', $query_join);

	$no_matches = true;
	if ("always" == $fuzzy) {
		$o_term_cond = apply_filters('relevanssi_fuzzy_query', "(relevanssi.term LIKE '#term#%' OR relevanssi.term_reverse LIKE CONCAT(REVERSE('#term#'), '%')) ");
	}
	else {
		$o_term_cond = " relevanssi.term = '#term#' ";
	}

	if (count($terms) < 1) {
		$o_term_cond = " relevanssi.term = relevanssi.term ";
		$terms[] = "term";
	}

	$post_type_weights = get_option('relevanssi_post_type_weights');
	if (function_exists('relevanssi_get_recency_bonus')) {
		list($recency_bonus, $recency_cutoff_date) = relevanssi_get_recency_bonus();
	}
	else {
		$recency_bonus = false;
		$recency_cutoff_date = false;
	}
	$min_length = get_option('relevanssi_min_word_length');
	$search_again = false;

	$title_boost = floatval(get_option('relevanssi_title_boost'));
	$link_boost = floatval(get_option('relevanssi_link_boost'));
	$comment_boost = floatval(get_option('relevanssi_comment_boost'));

	$include_these_posts = array();

	do {
		foreach ($terms as $term) {
			$term = trim($term);	// numeric search terms will start with a space
			if (apply_filters('relevanssi_block_one_letter_searches', relevanssi_strlen($term) < 2)) continue;
			$term = esc_sql($term);

			if (strpos($o_term_cond, 'LIKE') !== false) {
				// only like_escape() if necessary, otherwise _ in search terms will not work
				if (method_exists($wpdb, 'esc_like')) {
					$term = $wpdb->esc_like($term);
				}
				else {
					// Compatibility for pre-4.0 WordPress
					$term = like_escape($term);
				}
			}

			$term_cond = str_replace('#term#', $term, $o_term_cond);

			!empty($post_type_weights['post_tag']) ? $tag = $post_type_weights['post_tag'] : $tag = $relevanssi_variables['post_type_weight_defaults']['post_tag'];
			!empty($post_type_weights['category']) ? $cat = $post_type_weights['category'] : $cat = $relevanssi_variables['post_type_weight_defaults']['category'];

			$query = "SELECT DISTINCT(relevanssi.doc), relevanssi.*, relevanssi.title * $title_boost +
				relevanssi.content + relevanssi.comment * $comment_boost +
				relevanssi.tag * $tag + relevanssi.link * $link_boost +
				relevanssi.author + relevanssi.category * $cat + relevanssi.excerpt +
				relevanssi.taxonomy + relevanssi.customfield + relevanssi.mysqlcolumn AS tf
				FROM $relevanssi_table AS relevanssi $query_join WHERE $term_cond $query_restrictions";
			// Clean: $query_restrictions is escaped, $term_cond is escaped

			$query = apply_filters('relevanssi_query_filter', $query);
			$matches = $wpdb->get_results($query);

			if (count($matches) < 1) {
				continue;
			}
			else {
				$no_matches = false;
				if (count($include_these_posts) > 0) {
					$post_ids_to_add = implode(',', array_keys($include_these_posts));
					$existing_ids = array();
					foreach ($matches as $match) {
						$existing_ids[] = $match->doc;
					}
					$existing_ids = implode(',', $existing_ids);
					$query = "SELECT relevanssi.*, relevanssi.title * $title_boost +
					relevanssi.content + relevanssi.comment * $comment_boost +
					relevanssi.tag * $tag + relevanssi.link * $link_boost +
					relevanssi.author + relevanssi.category * $cat + relevanssi.excerpt +
					relevanssi.taxonomy + relevanssi.customfield + relevanssi.mysqlcolumn AS tf
					FROM $relevanssi_table AS relevanssi WHERE relevanssi.doc IN ($post_ids_to_add)
					AND relevanssi.doc NOT IN ($existing_ids) AND $term_cond";
					// Clean: no unescaped user inputs
					$matches_to_add = $wpdb->get_results($query);
					$matches = array_merge($matches, $matches_to_add);
				}
			}

			relevanssi_populate_array($matches);
			global $relevanssi_post_types;

			$total_hits += count($matches);

			$query = "SELECT COUNT(DISTINCT(relevanssi.doc)) FROM $relevanssi_table AS relevanssi
				$query_join WHERE $term_cond $query_restrictions";
			// Clean: $query_restrictions is escaped, $term_cond is escaped
			$query = apply_filters('relevanssi_df_query_filter', $query);

			$df = $wpdb->get_var($query);

			if ($df < 1 && "sometimes" == $fuzzy) {
				$query = "SELECT COUNT(DISTINCT(relevanssi.doc)) FROM $relevanssi_table AS relevanssi
					$query_join WHERE (relevanssi.term LIKE '$term%'
					OR relevanssi.term_reverse LIKE CONCAT(REVERSE('$term), %')) $query_restrictions";
				// Clean: $query_restrictions is escaped, $term is escaped
				$query = apply_filters('relevanssi_df_query_filter', $query);
				$df = $wpdb->get_var($query);
			}

			$idf = log($D + 1 / (1 + $df));
			$idf = $idf * $idf;
			if ($idf < 1) $idf = 1;
			foreach ($matches as $match) {
				if ('user' == $match->type) {
					$match->doc = 'u_' . $match->item;
				}
				else if (!in_array($match->type, array('post', 'attachment'))) {
					$match->doc = '**' . $match->type . '**' . $match->item;
				}

				if (isset($match->taxonomy_detail)) {
					$match->taxonomy_score = 0;
					$match->taxonomy_detail = unserialize($match->taxonomy_detail);
					if (is_array($match->taxonomy_detail)) {
						foreach ($match->taxonomy_detail as $tax => $count) {
							if ($tax == 'post_tag') {
								$match->tag = $count;
							}
							if (empty($post_type_weights[$tax])) {
								$match->taxonomy_score += $count * 1;
							}
							else {
								$match->taxonomy_score += $count * $post_type_weights[$tax];
							}
						}
					}
				}

				$match->tf =
					$match->title * $title_boost +
					$match->content +
					$match->comment * $comment_boost +
					$match->link * $link_boost +
					$match->author +
					$match->excerpt +
					$match->taxonomy_score +
					$match->customfield +
					$match->mysqlcolumn;

				$term_hits[$match->doc][$term] =
					$match->title +
					$match->content +
					$match->comment +
					$match->tag +
					$match->link +
					$match->author +
					$match->category +
					$match->excerpt +
					$match->taxonomy +
					$match->customfield +
					$match->mysqlcolumn;

				$match->weight = $match->tf * $idf;

				if ($recency_bonus) {
					$post = relevanssi_get_post($match->doc);
					if (strtotime($post->post_date) > $recency_cutoff_date)
						$match->weight = $match->weight * $recency_bonus['bonus'];
				}

				isset($body_matches[$match->doc]) ? $body_matches[$match->doc] += $match->content : $body_matches[$match->doc] = $match->content;
				isset($title_matches[$match->doc]) ? $title_matches[$match->doc] += $match->title : $title_matches[$match->doc] = $match->title;
				isset($link_matches[$match->doc]) ? $link_matches[$match->doc] += $match->link : $link_matches[$match->doc] = $match->link;
				isset($tag_matches[$match->doc]) ? $tag_matches[$match->doc] += $match->tag : $tag_matches[$match->doc] = $match->tag;
				isset($category_matches[$match->doc]) ? $category_matches[$match->doc] += $match->category : $category_matches[$match->doc] = $match->category;
				isset($taxonomy_matches[$match->doc]) ? $taxonomy_matches[$match->doc] += $match->taxonomy : $taxonomy_matches[$match->doc] = $match->taxonomy;
				isset($comment_matches[$match->doc]) ? $comment_matches[$match->doc] += $match->comment : $comment_matches[$match->doc] = $match->comment;

				isset($relevanssi_post_types[$match->doc]) ? $type = $relevanssi_post_types[$match->doc] : $type = null;
				if (!empty($post_type_weights[$type])) {
					$match->weight = $match->weight * $post_type_weights[$type];
				}

				$match = apply_filters('relevanssi_match', $match, $idf, $term);

				if ($match->weight == 0) continue; // the filters killed the match

				$post_ok = true;
				$post_ok = apply_filters('relevanssi_post_ok', $post_ok, $match->doc);

				if ($post_ok) {
					$doc_terms[$match->doc][$term] = true; // count how many terms are matched to a doc
					isset($doc_weight[$match->doc]) ? $doc_weight[$match->doc] += $match->weight : $doc_weight[$match->doc] = $match->weight;
					isset($scores[$match->doc]) ? $scores[$match->doc] += $match->weight : $scores[$match->doc] = $match->weight;
					if (is_numeric($match->doc)) {
						// this is to weed out taxonomies and users (t_XXX, u_XXX)
						$include_these_posts[$match->doc] = true;
					}
				}
			}
		}

		if (!isset($doc_weight)) $no_matches = true;
		if ($no_matches) {
			if ($search_again) {
				// no hits even with fuzzy search!
				$search_again = false;
			}
			else {
				if ("sometimes" == $fuzzy) {
					$search_again = true;
					$o_term_cond = "(term LIKE '%#term#' OR term LIKE '#term#%') ";
				}
			}
		}
		else {
			$search_again = false;
		}
	} while ($search_again);

	$strip_stops = true;
	$temp_terms_without_stops = array_keys(relevanssi_tokenize(implode(' ', $terms), $strip_stops));
	$terms_without_stops = array();
	foreach ($temp_terms_without_stops as $temp_term) {
		if (strlen($temp_term) >= $min_length)
			array_push($terms_without_stops, $temp_term);
	}
	$total_terms = count($terms_without_stops);

	if (isset($doc_weight))
		$doc_weight = apply_filters('relevanssi_results', $doc_weight);

	if (isset($doc_weight) && count($doc_weight) > 0) {
		arsort($doc_weight);
		$i = 0;
		foreach ($doc_weight as $doc => $weight) {
			if (count($doc_terms[$doc]) < $total_terms && $operator == "AND") {
				// AND operator in action:
				// doc didn't match all terms, so it's discarded
				continue;
			}

			if (!empty($fields)) {
				if ($fields == 'ids') {
					$hits[intval($i)] = $doc;
				}
				if ($fields == 'id=>parent') {
					$object = new StdClass();
					$object->ID = $doc;
					$object->post_parent = wp_get_post_parent_id($doc);

					$hits[intval($i)] = $object;
				}
			}
			else {
				$hits[intval($i)] = relevanssi_get_post($doc);
				$hits[intval($i)]->relevance_score = round($weight, 2);
			}
			$i++;
		}
	}

	if (count($hits) < 1) {
		if ($operator == "AND" AND get_option('relevanssi_disable_or_fallback') != 'on') {
			$or_args = $args;
			$or_args['operator'] = "OR";
			global $wp_query;
			$wp_query->set("operator", "OR");

			$or_args['q'] = relevanssi_add_synonyms($q);
			$return = relevanssi_search($or_args);
			extract($return);
		}
	}

	global $wp;
	$default_order = get_option('relevanssi_default_orderby', 'relevance');
	if (empty($orderby)) $orderby = $default_order;
	// the sorting function checks for non-existing keys, cannot whitelist here

	if (is_array($orderby)) {
		$orderby = apply_filters('relevanssi_orderby', $orderby);
		
		relevanssi_object_sort($hits, $orderby);
	}
	else {
		if (empty($order)) $order = 'desc';
		$order = strtolower($order);
		$order_accepted_values = array('asc', 'desc');
		if (!in_array($order, $order_accepted_values)) $order = 'desc';

		$orderby = apply_filters('relevanssi_orderby', $orderby);
		$order   = apply_filters('relevanssi_order', $order);

		if ($orderby != 'relevance') {
			$orderby_array = array($orderby => $order);
			relevanssi_object_sort($hits, $orderby_array);
		}
	}

	$return = array('hits' => $hits, 'body_matches' => $body_matches, 'title_matches' => $title_matches,
		'tag_matches' => $tag_matches, 'category_matches' => $category_matches, 'taxonomy_matches' => $taxonomy_matches,
		'comment_matches' => $comment_matches, 'scores' => $scores,
		'term_hits' => $term_hits, 'query' => $q, 'link_matches' => $link_matches);

	return $return;
}

function relevanssi_do_query(&$query) {
	// this all is basically lifted from Kenny Katzgrau's wpSearch
	// thanks, Kenny!
	global $relevanssi_active;

	$relevanssi_active = true;
	$posts = array();

	if ( function_exists( 'mb_strtolower' ) )
		$q = trim(stripslashes(mb_strtolower($query->query_vars["s"])));
	else
		$q = trim(stripslashes(strtolower($query->query_vars["s"])));

	$search_multisite = false;
	if (isset($query->query_vars['searchblogs']) && (string) get_current_blog_id() != $query->query_vars['searchblogs']) {
		$search_multisite =	true;
	}

	if (isset($query->query_vars['searchblogs']) && $search_multisite) {
		$multi_args['search_blogs'] = $query->query_vars['searchblogs'];
		$multi_args['q'] = $q;

		$post_type = false;
		if (isset($query->query_vars["post_type"]) && $query->query_vars["post_type"] != 'any') {
			$multi_args['post_type'] = $query->query_vars["post_type"];
		}
		if (isset($query->query_vars["post_types"]) && $query->query_vars["post_types"] != 'any') {
			$multi_args['post_type'] = $query->query_vars["post_types"];
		}

		$operator = "";
		if (function_exists('relevanssi_set_operator')) {
			$operator = relevanssi_set_operator($query);
			$operator = strtoupper($operator);	// just in case
		}
		if ($operator != "OR" && $operator != "AND") $operator = get_option("relevanssi_implicit_operator");
		$multi_args['operator'] = $operator;

		$meta_query = array();
		if ( ! empty( $query->query_vars["meta_query"] ) ) {
 			$meta_query = $query->query_vars["meta_query"];
 		}

		if ( isset( $query->query_vars["customfield_key"] ) ) {
			$build_meta_query = array();

			// Use meta key
			$build_meta_query['key'] = $query->query_vars["customfield_key"];

			/**
			 * Check the value is not empty for ordering purpose,
			 * Set it or not for the current meta query
			 */
			if ( ! empty( $query->query_vars["customfield_value"] ) ) {
				$build_meta_query['value'] = $query->query_vars["customfield_value"];
			}

			// Set the compare
			$build_meta_query['compare'] = '=';

			$meta_query[] = $build_meta_query;
 		}

		if ( ! empty($query->query_vars["meta_key"] ) || ! empty($query->query_vars["meta_value"] ) || ! empty( $query->query_vars["meta_value_num"] ) ) {

			$build_meta_query = array();

			// Use meta key
			$build_meta_query['key'] = $query->query_vars["meta_key"];

 			$value = null;
			if ( ! empty( $query->query_vars["meta_value"] ) ) {
				$value = $query->query_vars["meta_value"];
			} elseif ( ! empty( $query->query_vars["meta_value_num"] ) ) {
				$value = $query->query_vars["meta_value_num"];
			}

			/**
			 * Check the meta value, as it could be not set for ordering purpose
			 * set it or not for the current meta query
			 */
			if ( ! empty( $value ) ) {
				$build_meta_query['value'] = $value;
			}

			// Set meta compare
			$build_meta_query['compare'] = ! empty( $query->query_vars["meta_compare"] ) ? $query->query_vars["meta_compare"] : '=';

			$meta_query[] = $build_meta_query;
 		}

		$multi_args['meta_query'] = $meta_query;
		if (function_exists('relevanssi_search_multi')) {
			$return = relevanssi_search_multi($multi_args);
		}
	}
	else {
		$tax_query = array();
		$tax_query_relation = apply_filters('relevanssi_default_tax_query_relation', 'OR');
		if (isset($query->tax_query) && empty($query->tax_query->queries)) {
			// Tax query is empty, let's get rid of it.
			$query->tax_query = null;
		}
		if (isset($query->query_vars['tax_query'])) {
			// This is user-created tax_query array as described in WP Codex
			foreach ($query->query_vars['tax_query'] as $type => $item) {
				if (is_string($type) && $type == 'relation') {
					$tax_query_relation = $item;
				}
				else {
					$tax_query[] = $item;
				}
			}
		}
		else if (isset($query->tax_query)) {
			// This is the WP-created Tax_Query object, which is different from above
			foreach ($query->tax_query as $type => $item) {
				if (is_string($type) && $type == 'relation') {
					$tax_query_relation = $item;
				}
				if (is_string($type) && $type == 'queries') {
					foreach ($item as $tax_query_row) {
						$tax_query[] = $tax_query_row;
					}
				}
			}
		}
		else {
			$cat = false;
			if (isset($query->query_vars["cats"])) {
				$cat = $query->query_vars["cats"];
			}
			if (empty($cat)) {
				$cat = get_option('relevanssi_cat');
				if (0 == $cat) {
					$cat = false;
				}
			}
			if ($cat) {
				$cat = explode(',', $cat);
				$tax_query[] = array('taxonomy' => 'category', 'field' => 'id', 'terms' => $cat);
			}
			if (!empty($query->query_vars['category_name']) && empty($query->query_vars['category__in'])) {
				$cat = explode(',', $query->query_vars['category_name']);
				$tax_query[] = array('taxonomy' => 'category', 'field' => 'slug', 'terms' => $cat);
			}
			if (!empty($query->query_vars['category__in'])) {
				$tax_query[] = array('taxonomy' => 'category', 'field' => 'id', 'terms' => $query->query_vars['category__in']);
			}
			if (!empty($query->query_vars['category__not_in'])) {
				$tax_query[] = array('taxonomy' => 'category', 'field' => 'id', 'terms' => $query->query_vars['category__not_in'], 'operator' => 'NOT IN');
			}
			if (!empty($query->query_vars['category__and'])) {
				$tax_query[] = array('taxonomy' => 'category', 'field' => 'id', 'terms' => $query->query_vars['category__and'], 'operator' => 'AND', 'include_children' => false);
			}
			$excat = get_option('relevanssi_excat');
			if (isset($excat) && $excat != 0) {
				$tax_query[] = array('taxonomy' => 'category', 'field' => 'id', 'terms' => $excat, 'operator' => 'NOT IN');
			}

			$tag = false;
			if (isset($query->query_vars["tags"])) {
				$tag = $query->query_vars["tags"];
			}
			if ($tag) {
				if (strpos($tag, '+') !== false) {
					$tag = explode('+', $tag);
					$operator = 'and';
				}
				else {
					$tag = explode(',', $tag);
					$operator = 'or';
				}
				$tax_query[] = array('taxonomy' => 'post_tag', 'field' => 'id', 'terms' => $tag, 'operator' => $operator);
			}
			if (!empty($query->query_vars['tag_id'])) {
				$tax_query[] = array('taxonomy' => 'post_tag', 'field' => 'id', 'terms' => $query->query_vars['tag_id']);
			}
			if (!empty($query->query_vars['tag_id'])) {
				$tax_query[] = array('taxonomy' => 'post_tag', 'field' => 'id', 'terms' => $query->query_vars['tag_id']);
			}
			if (!empty($query->query_vars['tag__in'])) {
				$tax_query[] = array('taxonomy' => 'post_tag', 'field' => 'id', 'terms' => $query->query_vars['tag__in']);
			}
			if (!empty($query->query_vars['tag__not_in'])) {
				$tax_query[] = array('taxonomy' => 'post_tag', 'field' => 'id', 'terms' => $query->query_vars['tag__not_in'], 'operator' => 'NOT IN');
			}
			if (!empty($query->query_vars['tag__and'])) {
				$tax_query[] = array('taxonomy' => 'post_tag', 'field' => 'id', 'terms' => $query->query_vars['tag__and'], 'operator' => 'AND');
			}
			if (!empty($query->query_vars['tag__not_in'])) {
				$tax_query[] = array('taxonomy' => 'post_tag', 'field' => 'id', 'terms' => $query->query_vars['tag__not_in'], 'operator' => 'NOT IN');
			}
			if (!empty($query->query_vars['tag_slug__in'])) {
				$tax_query[] = array('taxonomy' => 'post_tag', 'field' => 'slug', 'terms' => $query->query_vars['tag_slug__in']);
			}
			if (!empty($query->query_vars['tag_slug__not_in'])) {
				$tax_query[] = array('taxonomy' => 'post_tag', 'field' => 'slug', 'terms' => $query->query_vars['tag_slug__not_in'], 'operator' => 'NOT IN');
			}
			if (!empty($query->query_vars['tag_slug__and'])) {
				$tax_query[] = array('taxonomy' => 'post_tag', 'field' => 'slug', 'terms' => $query->query_vars['tag_slug__and'], 'operator' => 'AND');
			}
			$extag = get_option('relevanssi_extag');
			if (isset($extag) && $extag != 0) {
				$tax_query[] = array('taxonomy' => 'post_tag', 'field' => 'id', 'terms' => $extag, 'operator' => 'NOT IN');
			}

			if (isset($query->query_vars["taxonomy"])) {
				if (function_exists('relevanssi_process_taxonomies')) {
					$tax_query = relevanssi_process_taxonomies($query->query_vars["taxonomy"], $query->query_vars["term"], $tax_query);
				}
				else {
					if (!empty($query->query_vars["term"])) $term = $query->query_vars["term"];

					$tax_query[] = array('taxonomy' => $query->query_vars["taxonomy"], 'field' => 'slug', 'terms' => $term);
				}
			}
		}

		$author = false;
		if (!empty($query->query_vars["author"])) {
			$author = explode(',', $query->query_vars["author"]);
		}
		if (!empty($query->query_vars["author_name"])) {
			$author_object = get_user_by('slug', $query->query_vars["author_name"]);
			$author[] = $author_object->ID;
		}

		$post_query = array();
		if (!empty($query->query_vars['p'])) {
			$post_query = array('in' => array($query->query_vars['p']));
		}
		if (!empty($query->query_vars['page_id'])) {
			$post_query = array('in' => array($query->query_vars['page_id']));
		}
		if (!empty($query->query_vars['post__in'])) {
			$post_query = array('in' => $query->query_vars['post__in']);
		}
		if (!empty($query->query_vars['post__not_in'])) {
			$post_query = array('not in' => $query->query_vars['post__not_in']);
		}

		$parent_query = array();
		if (!empty($query->query_vars['post_parent'])) {
			$parent_query = array('parent in' => array($query->query_vars['post_parent']));
		}
		if (!empty($query->query_vars['post_parent__in'])) {
			$parent_query = array('parent in' => $query->query_vars['post_parent__in']);
		}
		if (!empty($query->query_vars['post_parent__not_in'])) {
			$parent_query = array('parent not in' => $query->query_vars['post_parent__not_in']);
		}

 		$meta_query_relation = apply_filters('relevanssi_default_meta_query_relation', 'AND');
		$meta_query = array();
		if ( ! empty( $query->query_vars["meta_query"] ) ) {
 			$meta_query = $query->query_vars["meta_query"];
 		}

		if ( isset( $query->query_vars["customfield_key"] ) ) {
			$build_meta_query = array();

			// Use meta key
			$build_meta_query['key'] = $query->query_vars["customfield_key"];

			/**
		 	 * Check the value is not empty for ordering purpose,
		 	 * Set it or not for the current meta query
		 	 */
		 	if ( ! empty( $query->query_vars["customfield_value"] ) ) {
				$build_meta_query['value'] = $query->query_vars["customfield_value"];
			}

			// Set the compare
			$build_meta_query['compare'] = '=';
			$meta_query[] = $build_meta_query;
 		}

		if ( ! empty($query->query_vars["meta_key"] ) || ! empty($query->query_vars["meta_value"] ) || ! empty( $query->query_vars["meta_value_num"] ) ) {
			$build_meta_query = array();

			// Use meta key
			$build_meta_query['key'] = $query->query_vars["meta_key"];

 			$value = null;
			if ( ! empty( $query->query_vars["meta_value"] ) ) {
				$value = $query->query_vars["meta_value"];
			} elseif ( ! empty( $query->query_vars["meta_value_num"] ) ) {
				$value = $query->query_vars["meta_value_num"];
			}

			/**
		 	 * Check the meta value, as it could be not set for ordering purpose
			 * set it or not for the current meta query
			 */
			if ( ! empty( $value ) ) {
				$build_meta_query['value'] = $value;
			}

			// Set meta compare
			$build_meta_query['compare'] = ! empty( $query->query_vars["meta_compare"] ) ? $query->query_vars["meta_compare"] : '=';

			$meta_query[] = $build_meta_query;
 		}

		$date_query = false;
		if (!empty($query->date_query)) {
			if (is_object($query->date_query) && get_class($query->date_query) == 'WP_Date_Query') { // there is no is_WP_Date_Query_Object() function
				$date_query = $query->date_query;
			} else {
				$date_query = new WP_Date_Query($query->date_query);
			}
		}

		$search_blogs = false;
		if (isset($query->query_vars["search_blogs"])) {
			$search_blogs = $query->query_vars["search_blogs"];
		}

		$post_type = false;
		if (isset($query->query_vars["post_type"]) && $query->query_vars["post_type"] != 'any') {
			$post_type = $query->query_vars["post_type"];
		}
		if (isset($query->query_vars["post_types"]) && $query->query_vars["post_types"] != 'any') {
			$post_type = $query->query_vars["post_types"];
		}

		if ($post_type == -1) $post_type = false;

		$post_status = false;
		if (isset($query->query_vars["post_status"]) && $query->query_vars["post_status"] != 'any') {
			$post_status = $query->query_vars["post_status"];
		}

		$expost = get_option("relevanssi_exclude_posts");

		// In admin (and when not AJAX), search everything
		if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
			$excat = null;
			$extag = null;
			$expost = null;
		}

		$sentence = false;
		if (isset($query->query_vars['sentence']) && !empty($query->query_vars['sentence'])) {
			$sentence = true;
		}

		$operator = "";
		if (function_exists('relevanssi_set_operator')) {
			$operator = relevanssi_set_operator($query);
			$operator = strtoupper($operator);	// just in case
		}
		if ($operator != "OR" && $operator != "AND") $operator = get_option("relevanssi_implicit_operator");
		$query->query_vars["operator"] = $operator;

		isset($query->query_vars['orderby']) ? $orderby = $query->query_vars['orderby'] : $orderby = null;
		isset($query->query_vars['order']) ? $order = $query->query_vars['order'] : $order = null;

		$fields = "";
		if (!empty($query->query_vars['fields'])) {
			if ($query->query_vars['fields'] == 'ids') {
				$fields = 'ids';
			}
			if ($query->query_vars['fields'] == 'id=>parent') {
				$fields = 'id=>parent';
			}
		}

		// Add synonyms
		// This is done here so the new terms will get highlighting
		if ("OR" == $operator) {
			// Synonyms are only used in OR queries
			$q = relevanssi_add_synonyms($q);
		}

		$search_params = array(
			'q' => $q,
			'tax_query' => $tax_query,
			'tax_query_relation' => $tax_query_relation,
			'post_query' => $post_query,
			'parent_query' => $parent_query,
			'meta_query' => $meta_query,
			'date_query' => $date_query,
			'expost' => $expost,
			'post_type' => $post_type,
			'post_status' => $post_status,
			'operator' => $operator,
			'search_blogs' => $search_blogs,
			'author' => $author,
			'orderby' => $orderby,
			'order' => $order,
			'fields' => $fields,
			'sentence' => $sentence);

		$return = relevanssi_search($search_params);
	}

	isset($return['hits']) ? $hits = $return['hits'] : $hits = array();
	isset($return['query']) ? $q = $return['query'] : $q = "";

	$filter_data = array($hits, $q);
	$hits_filters_applied = apply_filters('relevanssi_hits_filter', $filter_data);
	$hits = array_values($hits_filters_applied[0]);
	// array_values() to make sure the $hits array is indexed in numerical order
	// Manipulating the array with array_unique() for example may mess with that.

	$query->found_posts = sizeof($hits);
	if (!isset($query->query_vars["posts_per_page"]) || $query->query_vars["posts_per_page"] == 0) {
		// assume something sensible to prevent "division by zero error";
		$query->query_vars["posts_per_page"] = -1;
	}
	if ($query->query_vars["posts_per_page"] == -1) {
		$query->max_num_pages = sizeof($hits);
	}
	else {
		$query->max_num_pages = ceil(sizeof($hits) / $query->query_vars["posts_per_page"]);
	}

	$update_log = get_option('relevanssi_log_queries');
	if ('on' == $update_log) {
		relevanssi_update_log($q, sizeof($hits));
	}

	$make_excerpts = get_option('relevanssi_excerpts');
	if ($query->is_admin) $make_excerpts = false;

	if (isset($query->query_vars['paged']) && $query->query_vars['paged'] > 0) {
		$wpSearch_low = ($query->query_vars['paged'] - 1) * $query->query_vars["posts_per_page"];
	}
	else {
		$wpSearch_low = 0;
	}

	if (!isset($query->query_vars["posts_per_page"]) || $query->query_vars["posts_per_page"] == -1) {
		$wpSearch_high = sizeof($hits);
	}
	else {
		$wpSearch_high = $wpSearch_low + $query->query_vars["posts_per_page"] - 1;
	}

	if (isset($query->query_vars['offset']) && $query->query_vars['offset'] > 0) {
		$wpSearch_high += $query->query_vars['offset'];
		$wpSearch_low += $query->query_vars['offset'];
	}

	if ($wpSearch_high > sizeof($hits)) $wpSearch_high = sizeof($hits);

	for ($i = $wpSearch_low; $i <= $wpSearch_high; $i++) {
		if (isset($hits[intval($i)])) {
			$post = $hits[intval($i)];
		}
		else {
			continue;
		}

		if ($post == NULL) {
			// apparently sometimes you can get a null object
			continue;
		}

		//Added by OdditY - Highlight Result Title too ->
		if("on" == get_option('relevanssi_hilite_title') && empty($fields)){
			if (function_exists('qtrans_useCurrentLanguageIfNotFoundUseDefaultLanguage')) {
				$post->post_highlighted_title = strip_tags(qtrans_useCurrentLanguageIfNotFoundUseDefaultLanguage($post->post_title));
			}
			else {
				$post->post_highlighted_title = strip_tags($post->post_title);
			}
			$highlight = get_option('relevanssi_highlight');
			if ("none" != $highlight) {
				if (!is_admin()) {
					$post->post_highlighted_title = relevanssi_highlight_terms($post->post_highlighted_title, $q);
				}
			}
		}
		// OdditY end <-

		if ('on' == $make_excerpts && empty($fields)) {
			$post->original_excerpt = $post->post_excerpt;
			$post->post_excerpt = relevanssi_do_excerpt($post, $q);
		}

		if ('on' == get_option('relevanssi_show_matches') && empty($fields)) {
			$post_id = $post->ID;
			if ($post->post_type == 'user') {
				$post_id = "u_" . $post->user_id;
			}
			else if (isset($post->term_id)) {
				$post_id = '**' . $post->post_type . '**' . $post->term_id;
			}
			$post->post_excerpt .= relevanssi_show_matches($return, $post_id);
		}

		if (empty($fields) && isset($return['scores'][$post->ID])) $post->relevance_score = round($return['scores'][$post->ID], 2);

		$posts[] = $post;
	}

	$query->posts = $posts;
	$query->post_count = count($posts);

	return $posts;
}

function relevanssi_limit_filter($query) {
	if (get_option('relevanssi_throttle', 'on') == 'on') {
		$limit = get_option('relevanssi_throttle_limit', 500);
		if (!is_numeric($limit)) $limit = 500; 		// Backup, if the option is set to something useless.
		if ($limit < 0) $limit = 500;
		return $query . " ORDER BY tf DESC LIMIT $limit";
	}
	else {
		return $query;
	}
}

function relevanssi_get_negative_post_type() {
	$negative_post_type = NULL;

	if (get_option('relevanssi_respect_exclude') == 'on') {
		// If Relevanssi is set to respect exclude_from_search, find out which
		// post types should be excluded from search.
		if (function_exists('get_post_types')) {
			$pt_1 = get_post_types(array('exclude_from_search' => '1'));
			$pt_2 = get_post_types(array('exclude_from_search' => true));
			$negative_post_type_list = implode(',', array_merge($pt_1, $pt_2));
		}

		// Post types to exclude.
		if ($negative_post_type_list) {
			if (!is_array($negative_post_type_list)) {
				$negative_post_types = esc_sql(explode(',', $negative_post_type_list));
			}
			else {
				$negative_post_types = esc_sql($negative_post_type_list);
			}
			$negative_post_type = count($negative_post_types) ? "'" . implode( "', '", $negative_post_types) . "'" : NULL;
		}
	}

	return $negative_post_type;
}

function relevanssi_process_tax_query_row($row, $is_sub_row, $global_relation, $query_restrictions, $tax_query_relation, $term_tax_ids, $not_term_tax_ids, $and_term_tax_ids) {
	global $wpdb;

	$local_term_tax_ids = array();
	$local_not_term_tax_ids = array();
	$local_and_term_tax_ids = array();

	$using_term_tax_id = false;
	if (!isset($row['field'])) $row['field'] = 'term_id'; 	// in case 'field' is not set, go with the WP default of "term_id"
	if ($row['field'] == 'slug') {
		$slug = $row['terms'];
		$numeric_slugs = array();
		$slug_in = null;
		if (is_array($slug)) {
			$slugs = array();
			$term_id = array();
			foreach ($slug as $t_slug) {
				$term = get_term_by('slug', $t_slug, $row['taxonomy']);
				if (!$term && is_numeric($t_slug)) {
					$numeric_slugs[] = "'$t_slug'";
				}
				else {
					if (isset($term->term_id)) {
						$t_slug = sanitize_title($t_slug);
						$term_id[] = $term->term_id;
						$slugs[] = "'$t_slug'";
					}
				}
			}
			if (!empty($slugs)) $slug_in = implode(',', $slugs);
		}
		else {
			$term = get_term_by('slug', $slug, $row['taxonomy'], OBJECT);
			if (!$term && is_numeric($slug)) {
				$numeric_slugs[] = $slug;
			}
			else {
				if (isset($term->term_id)) {
					$slug = sanitize_title($slug);
					$term_id = $term->term_id;
					$slug_in = "'$slug'";
				}
			}
		}
		if (!empty($slug_in)) {
			$row_taxonomy = sanitize_text_field($row['taxonomy']);
			$tt_q = "SELECT tt.term_taxonomy_id
				  	FROM $wpdb->term_taxonomy AS tt
				  	LEFT JOIN $wpdb->terms AS t ON (tt.term_id=t.term_id)
				  	WHERE tt.taxonomy = '$row_taxonomy' AND t.slug IN ($slug_in)";
			// Clean: $row_taxonomy is sanitized, each slug in $slug_in is sanitized
			$term_tax_id = $wpdb->get_col($tt_q);
		}
		if (!empty($numeric_slugs)) $row['field'] = 'term_id';
	}
	if ($row['field'] == 'name') {
		$name = $row['terms'];
		$numeric_names = array();
		$name_in = null;
		if (is_array($name)) {
			$names = array();
			$term_id = array();
			foreach ($name as $t_name) {
				$term = get_term_by('name', $t_name, $row['taxonomy']);
				if (!$term && is_numeric($t_names)) {
					$numeric_names[] = "'$t_name'";
				}
				else {
					if (isset($term->term_id)) {
						$t_name = sanitize_title($t_name);
						$term_id[] = $term->term_id;
						$names[] = "'$t_name'";
					}
				}
			}
			if (!empty($names)) $name_in = implode(',', $names);
		}
		else {
			$term = get_term_by('name', $name, $row['taxonomy']);
			if (!$term && is_numeric($name)) {
				$numeric_slugs[] = $name;
			}
			else {
				if (isset($term->term_id)) {
					$name = sanitize_title($name);
					$term_id = $term->term_id;
					$name_in = "'$name'";
				}
			}
		}
		if (!empty($name_in)) {
			$row_taxonomy = sanitize_text_field($row['taxonomy']);
			$tt_q = "SELECT tt.term_taxonomy_id
				  	FROM $wpdb->term_taxonomy AS tt
				  	LEFT JOIN $wpdb->terms AS t ON (tt.term_id=t.term_id)
				  	WHERE tt.taxonomy = '$row_taxonomy' AND t.name IN ($name_in)";
			// Clean: $row_taxonomy is sanitized, each name in $name_in is sanitized
			$term_tax_id = $wpdb->get_col($tt_q);
		}
		if (!empty($numeric_names)) $row['field'] = 'term_id';
	}
	if ($row['field'] == 'id' || $row['field'] == 'term_id') {
		$id = $row['terms'];
		$term_id = $id;
		if (is_array($id)) {
			$numeric_values = array();
			foreach ($id as $t_id) {
				if (is_numeric($t_id)) $numeric_values[] = $t_id;
			}
			$id = implode(',', $numeric_values);
		}
		$row_taxonomy = sanitize_text_field($row['taxonomy']);
		$tt_q = "SELECT tt.term_taxonomy_id
		  	FROM $wpdb->term_taxonomy AS tt
		  	LEFT JOIN $wpdb->terms AS t ON (tt.term_id=t.term_id)
		  	WHERE tt.taxonomy = '$row_taxonomy' AND t.term_id IN ($id)";
		// Clean: $row_taxonomy is sanitized, $id is checked to be numeric
		$id_term_tax_id = $wpdb->get_col($tt_q);
		if (!empty($term_tax_id) && is_array($term_tax_id)) {
			$term_tax_id = array_unique(array_merge($term_tax_id, $id_term_tax_id));
		}
		else {
			$term_tax_id = $id_term_tax_id;
		}
	}
	if ($row['field'] == 'term_taxonomy_id') {
		$using_term_tax_id = true;
		$id = $row['terms'];
		$term_tax_id = $id;
		if (is_array($id)) {
			$numeric_values = array();
			foreach ($id as $t_id) {
				if (is_numeric($t_id)) $numeric_values[] = $t_id;
			}
			$term_tax_id = implode(',', $numeric_values);
		}
	}

	if (!isset($row['include_children']) || $row['include_children'] == true) {
		if (!$using_term_tax_id && isset($term_id)) {
			if (!is_array($term_id)) {
				$term_id = array($term_id);
			}
		}
		else {
			if (!is_array($term_tax_id)) {
				$term_tax_id = array($term_tax_id);
				$term_id = $term_tax_id;
			}
		}
		if (empty($term_tax_id)) $term_tax_id = array();
		if (!is_array($term_tax_id)) $term_tax_id = array($term_tax_id);
		if (isset($term_id) && is_array($term_id)) {
			foreach ($term_id as $t_id) {
				if ($using_term_tax_id) {
					$t_term = get_term_by('term_taxonomy_id', $t_id, $row['taxonomy']);
					$t_id = $t_term->ID;
				}
				$kids = get_term_children($t_id, $row['taxonomy']);
				foreach ($kids as $kid) {
					$term = get_term_by('id', $kid, $row['taxonomy']);
					$kid_term_tax_id = relevanssi_get_term_tax_id('id', $kid, $row['taxonomy']);
					$term_tax_id[] = $kid_term_tax_id;
				}
			}
		}
	}

	$term_tax_id = array_unique($term_tax_id);
	if (!empty($term_tax_id)) {
		$n = count($term_tax_id);
		$term_tax_id = implode(',', $term_tax_id);

		$tq_operator = 'IN';	// Assuming the default operator "IN", unless something else is provided.
		if (isset($row['operator'])) $tq_operator = strtoupper($row['operator']);
		if ($tq_operator != 'IN' && $tq_operator != 'NOT IN' && $tq_operator != 'AND') $tq_operator = 'IN';
		if ($tax_query_relation == 'and') {
			if ($tq_operator == 'AND') {
				$query_restrictions .= " AND relevanssi.doc IN (
					SELECT ID FROM $wpdb->posts WHERE 1=1
					AND (
						SELECT COUNT(1)
						FROM $wpdb->term_relationships AS tr
						WHERE tr.term_taxonomy_id IN ($term_tax_id)
						AND tr.object_id = $wpdb->posts.ID ) = $n
					)";
				// Clean: $term_tax_id and $n are Relevanssi-generated
			}
			else {
				$query_restrictions .= " AND relevanssi.doc $tq_operator (SELECT DISTINCT(tr.object_id) FROM $wpdb->term_relationships AS tr
				WHERE tr.term_taxonomy_id IN ($term_tax_id))";
				// Clean: all variables are Relevanssi-generated
			}
		}
		else {
			if ($tq_operator == 'IN') $local_term_tax_ids[] = $term_tax_id;
			if ($tq_operator == 'NOT IN') $local_not_term_tax_ids[] = $term_tax_id;
			if ($tq_operator == 'AND') $local_and_term_tax_ids[] = $term_tax_id;
		}
	}
	else {
		global $wp_query;
		$wp_query->is_category = false;
	}
	
	if ($is_sub_row && $global_relation == 'and' && $tax_query_relation == 'or') {
		$local_term_tax_ids = array_unique($local_term_tax_ids);
		$local_not_term_tax_ids = array_unique($local_not_term_tax_ids);
		$local_and_term_tax_ids = array_unique($local_and_term_tax_ids);
		if (count($local_term_tax_ids) > 0) {
			$local_term_tax_ids = implode(',', $local_term_tax_ids);
			$query_restrictions .= " AND relevanssi.doc IN (SELECT DISTINCT(tr.object_id) FROM $wpdb->term_relationships AS tr
		    	WHERE tr.term_taxonomy_id IN ($local_term_tax_ids))";
		    // Clean: all variables are Relevanssi-generated
		}
		if (count($local_not_term_tax_ids) > 0) {
			$local_not_term_tax_ids = implode(',', $local_not_term_tax_ids);
			$query_restrictions .= " AND relevanssi.doc NOT IN (SELECT DISTINCT(tr.object_id) FROM $wpdb->term_relationships AS tr
		    	WHERE tr.term_taxonomy_id IN ($local_not_term_tax_ids))";
		    // Clean: all variables are Relevanssi-generated
		}
		if (count($local_and_term_tax_ids) > 0) {
			$local_and_term_tax_ids = implode(',', $local_and_term_tax_ids);
			$n = count(explode(',', $local_and_term_tax_ids));
			$query_restrictions .= " AND relevanssi.doc IN (
				SELECT ID FROM $wpdb->posts WHERE 1=1
				AND (
					SELECT COUNT(1)
					FROM $wpdb->term_relationships AS tr
					WHERE tr.term_taxonomy_id IN ($local_and_term_tax_ids)
					AND tr.object_id = $wpdb->posts.ID ) = $n
				)";
		    // Clean: all variables are Relevanssi-generated
		}
	}
	
	$copy_term_tax_ids = false;
	if (!$is_sub_row) $copy_term_tax_ids = true;
	if ($is_sub_row && $global_relation == 'or') $copy_term_tax_ids = true;

	if ($copy_term_tax_ids) {
		$term_tax_ids = array_merge($term_tax_ids, $local_term_tax_ids);
		$not_term_tax_ids = array_merge($not_term_tax_ids, $local_not_term_tax_ids);
		$and_term_tax_ids = array_merge($and_term_tax_ids, $local_and_term_tax_ids);
	}

	return array($query_restrictions, $term_tax_ids, $not_term_tax_ids, $and_term_tax_ids);
}


?>
