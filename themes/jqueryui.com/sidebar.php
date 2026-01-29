<?php
if ( get_option( 'jquery_is_blog' ) ):
	get_template_part( 'sidebar-blogpost' );
else:
?><div id="sidebar" class="widget-area" role="complementary">
	<aside class="widget">
		<h3 class="widget-title">Interactions</h3>
		<ul>
			<?php echo jq_page_links_for_category( 'interactions' ); ?>
		</ul>
	</aside>
	<aside class="widget">
		<h3 class="widget-title">Widgets</h3>
		<ul>
			<?php echo jq_page_links_for_category( 'widgets' ); ?>
		</ul>
	</aside>
	<aside class="widget">
		<h3 class="widget-title">Effects</h3>
		<ul>
			<?php echo jq_page_links_for_category( 'effects' ); ?>
		</ul>
	</aside>
	<aside class="widget">
		<h3 class="widget-title">Utilities</h3>
		<ul>
			<?php echo jq_page_links_for_category( 'utilities' ); ?>
		</ul>
	</aside>
	<aside class="widget">
		<h3 class="widget-title">Meta</h3>
		<ul>
			<?php echo jq_page_links_for_category( 'meta' ); ?>
		</ul>
	</aside>
</div>
<?php
endif;
