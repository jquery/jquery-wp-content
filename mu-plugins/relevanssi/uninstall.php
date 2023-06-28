<?php

if (!defined('WP_UNINSTALL_PLUGIN'))
	exit();

global $wpdb;
require_once('lib/uninstall.php');

if (!defined('RELEVANSSI_PREMIUM')) relevanssi_uninstall();
// if Relevanssi Premium is installed, options will not be deleted

function relevanssi_uninstall() {
	delete_option('relevanssi_title_boost');
	delete_option('relevanssi_tag_boost');
	delete_option('relevanssi_comment_boost');
	delete_option('relevanssi_admin_search');
	delete_option('relevanssi_highlight');
	delete_option('relevanssi_txt_col');
	delete_option('relevanssi_bg_col');
	delete_option('relevanssi_css');
	delete_option('relevanssi_class');
	delete_option('relevanssi_excerpts');
	delete_option('relevanssi_excerpt_length');
	delete_option('relevanssi_excerpt_type');
	delete_option('relevanssi_excerpt_allowable_tags');
	delete_option('relevanssi_log_queries');
	delete_option('relevanssi_log_queries_with_ip');
	delete_option('relevanssi_excat');
	delete_option('relevanssi_extag');
	delete_option('relevanssi_cat');
	delete_option('relevanssi_index_fields');
	delete_option('relevanssi_exclude_posts'); 	//added by OdditY
	delete_option('relevanssi_hilite_title'); 	//added by OdditY 
	delete_option('relevanssi_index_comments');	//added by OdditY
	delete_option('relevanssi_show_matches');
	delete_option('relevanssi_show_matches_text');
	delete_option('relevanssi_fuzzy');
	delete_option('relevanssi_index');
	delete_option('relevanssi_indexed');
	delete_option('relevanssi_expand_shortcodes');
	delete_option('relevanssi_index_author');
	delete_option('relevanssi_implicit_operator');
	delete_option('relevanssi_omit_from_logs');
	delete_option('relevanssi_synonyms');
	delete_option('relevanssi_index_excerpt');
	delete_option('relevanssi_highlight_docs');
	delete_option('relevanssi_highlight_comments');
	delete_option('relevanssi_index_limit');
	delete_option('relevanssi_disable_or_fallback');
	delete_option('relevanssi_respect_exclude');
	delete_option('relevanssi_min_word_length');
	delete_option('relevanssi_options');
	delete_option('relevanssi_wpml_only_current');
	delete_option('relevanssi_word_boundaries');
	delete_option('relevanssi_default_orderby');
	delete_option('relevanssi_db_version');
	delete_option('relevanssi_throttle');
	delete_option('relevanssi_throttle_limit');
	delete_option('relevanssi_index_post_types');
	delete_option('relevanssi_post_type_weights');
	delete_option('relevanssi_index_taxonomies_list');
	delete_option('relevanssi_doc_count');

	// Unused options, removed in case they are still left
	delete_option('relevanssi_custom_types');
	delete_option('relevanssi_hidesponsor');
	delete_option('relevanssi_index_attachments');
	delete_option('relevanssi_index_type');
	delete_option('relevanssi_show_matches_txt');
	delete_option('relevanssi_tag_boost');
	delete_option('relevanssi_include_tags'); 	//added by OdditY	
	delete_option('relevanssi_custom_taxonomies');
	delete_option('relevanssi_include_cats');
	delete_option('relevanssi_cache_seconds');
	delete_option('relevanssi_enable_cache');
	delete_option('relevanssi_show_matches_txt');

	relevanssi_clear_database_tables();
}
	
?>