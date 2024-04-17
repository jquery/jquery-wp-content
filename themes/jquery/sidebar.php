<?php
/**
 * The default sidebar lists categories, up to 2 levels deep.
 */
if ( get_option( 'jquery_is_blog' ) ):
	require __DIR__ . '/sidebar-blogpost.php';
else:
?>
<div id="sidebar" class="widget-area" role="complementary">
	<aside id="categories" class="widget">
		<ul>
			<?php wp_list_categories( array(
				'orderby' => 'natural',
				'depth' => 2,
				'title_li' => '',
				'current_category' => jq_post_category(),
				'use_desc_for_title' => false
			) ); ?>
		</ul>
	</aside>
</div>
<?php
endif;
