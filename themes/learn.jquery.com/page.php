<?php get_header(); ?>
<?php the_post(); ?>

<div class="content-right twelve columns">
	<div id="content">
		<?php get_template_part( 'content', 'page' ); ?>
	</div>

	<?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>
