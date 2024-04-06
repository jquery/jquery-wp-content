<div id="sidebar" class="widget-area" role="complementary">
	<aside class="widget">
		<h3 class="widget-title">Categories</h3>
		<ul>
			<?php wp_list_categories(array(
				'depth' => 1,
				'title_li' => '',
				'exclude' => '1'
			)); ?>
		</ul>
	</aside>
	<aside class="widget">
		<h3 class="widget-title">Recent Posts</h3>
		<ul>
			<?php
			$recent_posts = wp_get_recent_posts(array(
				'post_status' => 'publish'
			));
			foreach( $recent_posts as $recent ){
				echo '<li><a href="' . get_permalink($recent["ID"]) . '">' . $recent["post_title"].'</a></li>';
			}
			?>
		</ul>
	</aside>
	<aside class="widget">
		<h3 class="widget-title">Archives</h3>
		<ul>
			<?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
		</ul>
	</aside>
</div>
