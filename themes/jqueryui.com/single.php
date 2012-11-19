<?php get_header(); ?>

<div class="content-right">
	<div id="content" class="sidebar-left clearfix">
		<?php the_post(); ?>

		<?php get_template_part( 'content', 'page' ); ?>

	</div>
	<?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>
