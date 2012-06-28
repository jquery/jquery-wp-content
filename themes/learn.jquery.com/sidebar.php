<?php
/**
 * The Sidebar containing the main widget area.
 */
?>
		<div id="sidebar" class="widget-area" role="complementary">
			<?php if ( ! dynamic_sidebar( 'sidebar-1' ) ) : ?>

				<aside id="archives" class="widget">
					<h3 class="widget-title"><?php _e( 'Chapters', 'twentyeleven' ); ?></h3>
					<ul>
						<?php wp_list_pages("depth=1&title_li=&sort_column=menu_order"); ?>
					</ul>
				</aside>


			<?php endif; // end sidebar widget area ?>
		</div><!-- #sidebar .widget-area -->
