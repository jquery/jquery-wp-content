<?php get_header(); ?>

<div id="content" class="sidebar-left clearfix">
	<?php the_post(); ?>

	<?php get_template_part( 'content', 'page' ); ?>

</div>
<?php get_sidebar(); ?>

<?php get_footer(); ?>
