<?php
/**
 * Runs installation of Relevanssi plugin for current blog if it was not already installed.
 */

if (!get_current_blog_id() || get_option('jquery_relevanssi_installed', false)) {
	return;
}
relevanssi_install();

update_option( 'relevanssi_implicit_operator', 'AND' );
update_option( 'relevanssi_index_post_types', array( 'post', 'page' ) );
update_option( 'relevanssi_min_word_length', 2 );
// Search terms highlighting may be buggy (try to search "wrap html" and see a page crash). Disabling it.
update_option( 'relevanssi_excerpts', 'off' );

// Will output success message. We don't want it to display.
ob_start();
relevanssi_build_index();
ob_end_clean();

update_option('jquery_relevanssi_installed', true);
