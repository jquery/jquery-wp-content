<?php get_header(); ?>

<div id="body" class="sidebar-left clearfix">
	<div class="inner" role="main">

		<?php the_post(); ?>

		<?php get_template_part( 'content', 'page' ); ?>

	</div>
	<?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>
