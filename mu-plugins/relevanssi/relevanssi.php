<?php
/*
Plugin Name: Relevanssi
Plugin URI: http://www.relevanssi.com/
Description: This plugin replaces WordPress search with a relevance-sorting search.
Version: 3.3.8
Author: Mikko Saari
Author URI: http://www.mikkosaari.fi/
*/

/*  Copyright 2014 Mikko Saari  (email: mikko@mikkosaari.fi)

    This file is part of Relevanssi, a search plugin for WordPress.

    Relevanssi is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Relevanssi is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Relevanssi.  If not, see <http://www.gnu.org/licenses/>.
*/

// For debugging purposes
//error_reporting(E_ALL);
//ini_set("display_errors", 1); 
//define('WP-DEBUG', true);
global $wpdb;
//$wpdb->show_errors();

define('RELEVANSSI_PREMIUM', false);

global $relevanssi_variables;

$relevanssi_variables['relevanssi_table'] = $wpdb->prefix . "relevanssi";
$relevanssi_variables['stopword_table'] = $wpdb->prefix . "relevanssi_stopwords";
$relevanssi_variables['log_table'] = $wpdb->prefix . "relevanssi_log";
$relevanssi_variables['title_boost_default'] = 5;
$relevanssi_variables['comment_boost_default'] = 0.75;
$relevanssi_variables['post_type_weight_defaults']['post_tag'] = 0.75;
$relevanssi_variables['post_type_weight_defaults']['category'] = 0.75;
$relevanssi_variables['post_type_index_defaults'] = array('post', 'page');
$relevanssi_variables['database_version'] = 5;
$relevanssi_variables['file'] = __FILE__;
$relevanssi_variables['plugin_dir'] = plugin_dir_path(__FILE__);

require_once( 'lib/init.php' );
require_once( 'lib/interface.php' );
require_once( 'lib/indexing.php' );
require_once( 'lib/stopwords.php' );
require_once( 'lib/search.php' );
require_once( 'lib/excerpts-highlights.php' );
require_once( 'lib/shortcodes.php' );
require_once( 'lib/common.php' );

function relevanssi_didyoumean($query, $pre, $post, $n = 5) {
	global $wpdb, $relevanssi_variables, $wp_query;
	
	$total_results = $wp_query->found_posts;	
	
	if ($total_results > $n) return;

	$q = "SELECT query, count(query) as c, AVG(hits) as a FROM " . $relevanssi_variables['log_table'] . " WHERE hits > 1 GROUP BY query ORDER BY count(query) DESC";
	$q = apply_filters('relevanssi_didyoumean_query', $q);

	$data = $wpdb->get_results($q);
		
	$distance = -1;
	$closest = "";
	
	foreach ($data as $row) {
		if ($row->c < 2) break;
		$lev = levenshtein($query, $row->query);
		
		if ($lev < $distance || $distance < 0) {
			if ($row->a > 0) {
				$distance = $lev;
				$closest = $row->query;
				if ($lev == 1) break; // get the first with distance of 1 and go
			}
		}
	}
	
	if ($distance > 0) {

 		$url = get_bloginfo('url');
		$url = esc_attr(add_query_arg(array(
			's' => urlencode($closest)

			), $url ));
		$url = apply_filters('relevanssi_didyoumean_url', $url);
		echo "$pre<a href='$url'>$closest</a>$post";
 	}
 

}

function relevanssi_check_old_data() {
	if (is_admin()) {
		// Version 3.3 removes the cache feature
		$cache = get_option('relevanssi_enable_cache', 'nothing');
		if ($cache != 'nothing') {
			global $wpdb;
			$relevanssi_cache = $wpdb->prefix . "relevanssi_cache";
			$relevanssi_excerpt_cache = $wpdb->prefix . "relevanssi_excerpt_cache";

			$wpdb->query("DROP TABLE $relevanssi_cache");
			$wpdb->query("DROP TABLE $relevanssi_excerpt_cache");

			delete_option('relevanssi_enable_cache');
			delete_option('relevanssi_cache_seconds');
			wp_clear_scheduled_hook('relevanssi_truncate_cache');
		}	
	
		// Version 3.1.4 combined taxonomy indexing options
		$inctags = get_option('relevanssi_include_tags', 'nothing');
		if ($inctags == 'on') {
			$taxonomies = get_option('relevanssi_index_taxonomies_list');
			if (!is_array($taxonomies)) $taxonomies = array();
			$taxonomies[] = 'post_tag';
			update_option('relevanssi_index_taxonomies_list', $taxonomies);
			delete_option('relevanssi_include_tags');
		}
		$inccats = get_option('relevanssi_include_cats', 'nothing');
		if ($inccats == 'on') {
			$taxonomies = get_option('relevanssi_index_taxonomies_list');
			if (!is_array($taxonomies)) $taxonomies = array();
			$taxonomies[] = 'category';
			update_option('relevanssi_index_taxonomies_list', $taxonomies);
			delete_option('relevanssi_include_cats');
		}
		$custom = get_option('relevanssi_custom_taxonomies', 'nothing');
		if ($custom != 'nothing') {
			$cts = explode(",", $custom);
			$taxonomies = get_option('relevanssi_index_taxonomies_list');
			if (!is_array($taxonomies)) $taxonomies = array();
			foreach ($cts as $taxonomy) {
				$taxonomy = trim($taxonomy);
				$taxonomies[] = $taxonomy;
			}
			update_option('relevanssi_index_taxonomies_list', $taxonomies);
			delete_option('relevanssi_custom_taxonomies');
		}
		
		$limit = get_option('relevanssi_throttle_limit');
		if (empty($limit)) update_option('relevanssi_throttle_limit', 500);

		global $wpdb, $relevanssi_variables;

		if ($relevanssi_variables['database_version'] == 3) {
			$res = $wpdb->query("SHOW INDEX FROM " . $relevanssi_variables['relevanssi_table'] . " WHERE Key_name = 'typeitem'");
			if ($res == 0) $wpdb->query("ALTER TABLE " . $relevanssi_variables['relevanssi_table'] . " ADD INDEX `typeitem` (`type`, `item`)");
		}

		// Version 3.0 removed relevanssi_tag_boost
		$tag_boost = get_option('relevanssi_tag_boost', 'nothing');
		if ($tag_boost != 'nothing') {
			$post_type_weights = get_option('relevanssi_post_type_weights');
			if (!is_array($post_type_weights)) {
				$post_type_weights = array();
			}
			$post_type_weights['post_tag'] = $tag_boost;
			delete_option('relevanssi_tag_boost');
			update_option('relevanssi_post_type_weights', $post_type_weights);
		}
	
		$index_type = get_option('relevanssi_index_type', 'nothing');
		if ($index_type != 'nothing') {
			// Delete unused options from versions < 3
			$post_types = get_option('relevanssi_index_post_types');
			
			if (!is_array($post_types)) $post_types = array();
			
			switch ($index_type) {
				case "posts":
					array_push($post_types, 'post');
					break;
				case "pages":
					array_push($post_types, 'page');
					break;
				case 'public':
					if (function_exists('get_post_types')) {
						$pt_1 = get_post_types(array('exclude_from_search' => '0'));
						$pt_2 = get_post_types(array('exclude_from_search' => false));
						foreach (array_merge($pt_1, $pt_2) as $type) {
							array_push($post_types, $type);				
						}
					}
					break;
				case "both": 								// really should be "everything"
					$pt = get_post_types();
					foreach ($pt as $type) {
						array_push($post_types, $type);				
					}
					break;
			}
			
			$attachments = get_option('relevanssi_index_attachments');
			if ('on' == $attachments) array_push($post_types, 'attachment');
			
			$custom_types = get_option('relevanssi_custom_types');
			$custom_types = explode(',', $custom_types);
			if (is_array($custom_types)) {
				foreach ($custom_types as $type) {
					$type = trim($type);
					if (substr($type, 0, 1) != '-') {
						array_push($post_types, $type);
					}
				}
			}
			
			update_option('relevanssi_index_post_types', $post_types);
			
			delete_option('relevanssi_index_type');
			delete_option('relevanssi_index_attachments');
			delete_option('relevanssi_custom_types');
		}
	}
}

function _relevanssi_install() {
	global $relevanssi_variables;
	
	add_option('relevanssi_title_boost', $relevanssi_variables['title_boost_default']);
	add_option('relevanssi_comment_boost', $relevanssi_variables['comment_boost_default']);
	add_option('relevanssi_admin_search', 'off');
	add_option('relevanssi_highlight', 'strong');
	add_option('relevanssi_txt_col', '#ff0000');
	add_option('relevanssi_bg_col', '#ffaf75');
	add_option('relevanssi_css', 'text-decoration: underline; text-color: #ff0000');
	add_option('relevanssi_class', 'relevanssi-query-term');
	add_option('relevanssi_excerpts', 'on');
	add_option('relevanssi_excerpt_length', '30');
	add_option('relevanssi_excerpt_type', 'words');
	add_option('relevanssi_excerpt_allowable_tags', '');
	add_option('relevanssi_log_queries', 'off');
	add_option('relevanssi_log_queries_with_ip', 'off');
	add_option('relevanssi_cat', '0');
	add_option('relevanssi_excat', '0');
	add_option('relevanssi_extag', '0');
	add_option('relevanssi_index_fields', '');
	add_option('relevanssi_exclude_posts', ''); 		//added by OdditY
	add_option('relevanssi_hilite_title', ''); 			//added by OdditY	
	add_option('relevanssi_highlight_docs', 'off');
	add_option('relevanssi_highlight_comments', 'off');
	add_option('relevanssi_index_comments', 'none');	//added by OdditY
	add_option('relevanssi_show_matches', '');
	add_option('relevanssi_show_matches_text', '(Search hits: %body% in body, %title% in title, %tags% in tags, %comments% in comments. Score: %score%)');
	add_option('relevanssi_fuzzy', 'sometimes');
	add_option('relevanssi_indexed', '');
	add_option('relevanssi_expand_shortcodes', 'on');
	add_option('relevanssi_custom_taxonomies', '');
	add_option('relevanssi_index_author', '');
	add_option('relevanssi_implicit_operator', 'OR');
	add_option('relevanssi_omit_from_logs', '');
	add_option('relevanssi_synonyms', '');
	add_option('relevanssi_index_excerpt', 'off');
	add_option('relevanssi_index_limit', '500');
	add_option('relevanssi_disable_or_fallback', 'off');
	add_option('relevanssi_respect_exclude', 'on');
	add_option('relevanssi_min_word_length', '3');
	add_option('relevanssi_wpml_only_current', 'on');
	add_option('relevanssi_word_boundaries', 'on');
	add_option('relevanssi_default_orderby', 'relevance');
	add_option('relevanssi_db_version', '0');
	add_option('relevanssi_post_type_weights', $relevanssi_variables['post_type_weight_defaults']);
	add_option('relevanssi_throttle', 'on');
	add_option('relevanssi_throttle_limit', '500');
	add_option('relevanssi_index_post_types', $relevanssi_variables['post_type_index_defaults']);
	add_option('relevanssi_index_taxonomies_list', array());
	
	relevanssi_create_database_tables($relevanssi_variables['database_version']);
}

if (function_exists('register_uninstall_hook')) {
	register_uninstall_hook(__FILE__, 'relevanssi_uninstall');
	// this doesn't seem to work
}

function relevanssi_get_post($id) {
	global $relevanssi_post_array;
	
	if (isset($relevanssi_post_array[$id])) {
		$post = $relevanssi_post_array[$id];
	}
	else {
		$post = get_post($id);
	}
	return $post;
}

function relevanssi_remove_doc($id) {
	global $wpdb, $relevanssi_variables;
	
	$D = get_option( 'relevanssi_doc_count');

 	$q = "DELETE FROM " . $relevanssi_variables['relevanssi_table'] . " WHERE doc=$id";
	$wpdb->query($q);
	$rows_updated = $wpdb->query($q);

	if($rows_updated && $rows_updated > 0) {
		update_option('relevanssi_doc_count', $D - $rows_updated);
	}
}

/*****
 * Interface functions
 */
 
function relevanssi_form_tag_weight($post_type_weights) {
	$label = __("Tag weight:", 'relevanssi');
	$value = $post_type_weights['post_tag'];

	echo <<<EOH
	<tr>
		<td>
			$label 
		</td>
		<td>
			<input type='text' name='relevanssi_weight_post_tag' id='relevanssi_weight_post_tag' size='4' value='$value' />
		</td>
		<td>&nbsp;</td>
	</tr>
EOH;

	$label = __("Category weight:", 'relevanssi');
	$value = $post_type_weights['category'];

	echo <<<EOH
	<tr>
		<td>
			$label 
		</td>
		<td>
			<input type='text' id='relevanssi_weight_category' name='relevanssi_weight_category' size='4' value='$value' />
		</td>
		<td>&nbsp;</td>
	</tr>
EOH;
}

function relevanssi_sidebar() {
	$tweet = 'http://twitter.com/home?status=' . urlencode("I'm using Relevanssi, a better search for WordPress. http://wordpress.org/extend/plugins/relevanssi/ #relevanssi #wordpress");
	if (function_exists("plugins_url")) {
		global $wp_version;
		if (version_compare($wp_version, '2.8dev', '>' )) {
			$facebooklogo = plugins_url('facebooklogo.jpg', __FILE__);
		}
		else {
			$facebooklogo = plugins_url('relevanssi/facebooklogo.jpg');
		}
	}
	else {
		// We can't check, so let's assume something sensible
		$facebooklogo = '/wp-content/plugins/relevanssi/facebooklogo.jpg';
	}

	echo <<<EOH
<div class="postbox-container" style="width:20%; margin-top: 35px; margin-left: 15px;">
	<div class="metabox-holder">	
		<div class="meta-box-sortables" style="min-height: 0">
			<div id="relevanssi_buy" class="postbox">
			<h3 class="hndle"><span>Buy Relevanssi Premium!</span></h3>
			<div class="inside">
<p>Do you want more features? Support Relevanssi development? Get a
better search experience for your users?</p>

<p><strong>Go Premium!</strong> Buy Relevanssi Premium. See <a href="http://www.relevanssi.com/features/?utm_source=plugin&utm_medium=link&utm_campaign=features">feature
comparison</a> and <a href="http://www.relevanssi.com/buy-premium/?utm_source=plugin&utm_medium=link&utm_campaign=license">license prices</a>.</p>

<p><strong><a href="http://sites.fastspring.com/painavasana/product/relevanssipremium?source=p">Buy Premium here &raquo;</a></strong></p>
			</div>
		</div>
	</div>

		<div class="meta-box-sortables" style="min-height: 0">
			<div id="relevanssi_premium" class="postbox">
			<h3 class="hndle"><span>Sample Premium features</span></h3>
			<div class="inside">
<p>With Relevanssi Premium, you would have more options:</p>

<p>- Internal link anchors are search terms for the target posts, if you wish<br />
- Hiding Relevanssi branding from the User Searches page on a client installation<br />
- Adjust weights separately for each post type and taxonomy<br />
- Give extra weight to recent posts<br />
- Highlight search terms for visitors from Google and other external search sources<br />
- Make Relevanssi understand thousand separators to handle big numbers better<br />
- Index and search any columns in the wp_posts database<br />
- Index and search user profile pages<br />
- Index and search taxonomy term pages<br />
- Import and export options<br />
- And more!
</p>
			</div>
		</div>
	</div>
		
		<div class="meta-box-sortables" style="min-height: 0">
			<div id="relevanssi_facebook" class="postbox">
			<h3 class="hndle"><span>Relevanssi on Facebook</span></h3>
			<div class="inside">
			<div style="float: left; margin-right: 5px"><img src="$facebooklogo" width="45" height="43" alt="Facebook" /></div>
			<p><a href="http://www.facebook.com/relevanssi">Check
			out the Relevanssi page on Facebook</a> for news and updates about your favourite plugin.</p>
			</div>
		</div>
	</div>

		<div class="meta-box-sortables" style="min-height: 0">
			<div id="relevanssi_help" class="postbox">
			<h3 class="hndle"><span>Help and support</span></h3>
			<div class="inside">
			<p>For Relevanssi support, see:</p>
			
			<p>- <a href="http://wordpress.org/tags/relevanssi?forum_id=10">WordPress.org forum</a><br />
			- <a href="http://www.relevanssi.com/category/knowledge-base/?utm_source=plugin&utm_medium=link&utm_campaign=kb">Knowledge base</a></p>
			</div>
		</div>
	</div>
	
</div>
</div>
EOH;
}

/**
 * Wrapper function for Premium compatibility.
 */
function relevanssi_install() {
	_relevanssi_install();
}

?>
