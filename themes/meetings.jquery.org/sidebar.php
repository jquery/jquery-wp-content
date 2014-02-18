<div id="sidebar" class="widget-area" role="complementary">
	<aside id="categories" class="widget">
		<h2>Internal Minutes</h2>
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
