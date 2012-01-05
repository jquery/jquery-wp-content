<?php
/*
Page Tagger wordpress plugin
Copyright (C) 2009-2012 Ramesh Nair

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/


/**
 * The class. Handles everything.
 */ 
class PageTagger
{
	static $instance = NULL;
	
	private $using_wordpress_3_or_above = FALSE;
	
	static function init()
	{
		if (NULL == self::$instance)
			self::$instance = new PageTagger();
	}
	
	function PageTagger()
	{
		global $wp_version;
		$this->using_wordpress_3_or_above = (3 <= substr($wp_version,0,1));

		// for WP 3 we will do the 'is editing page' check later
		global $pagenow;
		if ($this->using_wordpress_3_or_above || 
				(isset($pagenow) && in_array( $pagenow, array('page.php', 'page-new.php') )) )
		{
			add_action('admin_head',array(&$this, 'admin_head_hook') );
		}	
		
		add_action('pre_get_posts', array(&$this, 'setup_query_vars_for_posts'), 100);
		add_action('init',array(&$this,'setup_tag_post_count_callback'),100);
	}
	

	
	
	/**
	 * Stuff to do for the 'admin_head' hook.
	 */ 
	function admin_head_hook()
	{
		// WP 3
		if ($this->using_wordpress_3_or_above)
		{
			// check that we're editing a page
			global $current_screen, $editing;
			if (!$editing || empty($current_screen) || 'page' != $current_screen->post_type)
				return;			
		}
		
		wp_register_script('page-tagger', WP_PLUGIN_URL.'/'.PAGE_TAGGER_PARENT_DIR.'/page-tagger.js', array('jquery','suggest'), false);
		wp_localize_script('page-tagger','pageTaggerL10n', 
				array(
					'tagsUsed' =>  __('Tags used on this page:'),
					'addTag' => esc_attr(__('Add new tag')),
				)
		);
		wp_print_scripts('page-tagger');
		
		add_meta_box('tagsdiv-post_tag', 'Tags', array(&$this, 'add_tags_meta_box'), 'page', 'side', 'default');
	}

	

	/**
	 * Setup the callback that will get used for counting the no. of posts 
	 * associated with a tag.
	 * @return unknown_type
	 */	
	function setup_tag_post_count_callback()
	{
		// override default tag count update callback
		$tag = get_taxonomy('post_tag');
		if (!empty($tag))
		{
			$tag->update_count_callback = array(&$this, '_update_post_term_count');
		}
	}
	
	
	/**
	 * Set the Wordpress query variables (this determines what gets loaded 
	 * from the db).
	 */	 	
	function setup_query_vars_for_posts(&$query)
	{
		// if we're viewing tag archives
		if ($query->is_archive && $query->is_tag)
		{
			$q = &$query->query_vars;
			
			// set the post-type to 'any' so that pages are also included
			$q['post_type'] = 'any';
		}
	}
		

	
	
	/**
	 * Display page tags form fields.
	 * 
	 * This is coped from wp-admin/edit-form-advanced.php.  
	 *
	 * @param object $post
	 */
	function add_tags_meta_box($post, $box) 
	{
		$css = ($this->using_wordpress_3_or_above ? 'wp3' : '');
		
		$tax_name = esc_attr(substr($box['id'], 8));
		$taxonomy = get_taxonomy($tax_name);
		$helps = isset($taxonomy->helps) ? esc_attr($taxonomy->helps) : __('Separate tags with commas.');
	?>
		<div class="tagsdiv" id="<?php echo $tax_name ?>">
			<div class="jaxtag">
			<div class="nojs-tags hide-if-js">
			<p><?php _e('Add or remove tags'); ?></p>
			<textarea name="<?php echo "tax_input[$tax_name]"; ?>" class="the-tags" id="tax-input[<?php echo $tax_name; ?>]"><?php echo esc_attr(get_terms_to_edit( $post->ID, $tax_name )); ?></textarea></div>
		
			<span class="ajaxtag hide-if-no-js">
				<label class="screen-reader-text" for="new-tag-<?php echo $tax_name; ?>"><?php echo $box['title']; ?></label>
				<input type="text" id="new-tag-<?php echo $tax_name; ?>" name="newtag[<?php echo $tax_name; ?>]" class="newtag form-input-tip" size="16" autocomplete="off" value="<?php esc_attr_e('Add new tag'); ?>" />
				<input type="button" class="button tagadd" value="<?php esc_attr_e('Add'); ?>" tabindex="3" />
			</span></div>
			<p class="howto"><?php echo $helps; ?></p>
			<div class="tagchecklist"></div>
		</div>
		<p class="tagcloud-link hide-if-no-js"><a href="#titlediv" class="tagcloud-link <?php echo $css; ?>" id="link-<?php echo $tax_name; ?>"><?php printf( __('Choose from the most used tags in %s'), $box['title'] ); ?></a></p>
	<?php
	}
	

	
	
	/**
	 * Custom version of the Wordpress taxonomy 'update term count' callback method.
	 * 
	 * This will update term count pased on posts, pages and on custom post types.
	 * @param array $terms List of Term taxonomy IDs
	 */
	function _update_post_term_count( $terms )
	{
		global $wpdb;

        $cptSql = '' . implode(' OR ', $this->_get_custom_post_types_sql());
        if (0 < strlen($cptSql))
            $cptSql = ' OR ' . $cptSql;

		foreach ( (array) $terms as $term ) {
			$count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->term_relationships, $wpdb->posts WHERE $wpdb->posts.ID = $wpdb->term_relationships.object_id AND (post_type = 'post' OR post_type = 'page' $cptSql) AND term_taxonomy_id = %d", $term ) );
			$wpdb->update( $wpdb->term_taxonomy, compact( 'count' ), array( 'term_taxonomy_id' => $term ) );
		}
	}	


    /** Helper to  _update_post_term_count() which retrieves all custom post types which use the post_tag taxonomy */
    function _get_custom_post_types_sql() {
        static $cpt = null;

        if (is_null($cpt)) {
            $cpt = array();

            $args = array(
                'public'   => true,
                '_builtin' => false,
            );

            $post_types = get_post_types($args, 'names');
            foreach ($post_types as $post_type ) {
                if (is_object_in_taxonomy($post_type, 'post_tag')) {
                    $cpt[] = "post_type = '$post_type'";
                }
            }
        }

        return $cpt;
    }

}


