<?php
/**
 * The default sidebar lists categories, up to 2 levels deep.
 */
?>
<div id="sidebar" class="widget-area" role="complementary">
	<aside id="categories" class="widget">
		<ul>
			<?php wp_list_categories( array(
				'depth' => 2,
				'title_li' => '',
				'current_category' => jq_post_category(),
				'use_desc_for_title' => false
			) ); ?>
		</ul>
	</aside>
</div>
