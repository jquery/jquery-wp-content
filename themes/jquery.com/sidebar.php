<?php
if ( get_option( 'jquery_is_blog' ) ):
	get_template_part( 'sidebar-blogpost' );
else:
?><div id="sidebar" class="widget-area" role="complementary">
	<aside class="widget">
		<h3 class="widget-title">Project</h3>
		<ul>
			<?php echo jq_page_links_for_category( 'meta', 'menu_order title' ); ?>
		</ul>
	</aside>
</div>
<?php
endif;
