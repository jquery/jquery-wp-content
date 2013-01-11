<?php
/**
 * The default sidebar lists categories, up to 2 levels deep.
 */
?>
<div id="sidebar" class="widget-area" role="complementary">
	<?php $cat_args = array( 'depth' => 2, 'title_li' => '', 'current_category' => jq_post_category() ); ?>
	<aside id="categories" class="widget">
		<ul>
			<?php wp_list_categories( $cat_args ); ?>
		</ul>
	</aside>
</div>
