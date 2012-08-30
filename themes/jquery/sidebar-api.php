<?php
/**
 * The Sidebar containing the main widget area.
 */
?>
		<div id="sidebar" class="widget-area" role="complementary">

			<?php if ( ! dynamic_sidebar( 'sidebar-1' ) ) : ?>
        <?php $cat_args = array( 'depth' => '1', 'title_li' => '' ); ?>
				<aside id="categories" class="widget">
					<ul>
						<?php wp_list_categories( $cat_args ); ?>
					</ul>
				</aside>

			<?php endif; // end sidebar widget area ?>
		</div><!-- #sidebar .widget-area -->
