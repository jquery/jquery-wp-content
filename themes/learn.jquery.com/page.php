<?php get_header(); ?>

<div class="content-right">
	<div id="content">
		<div class="beta-ribbon-wrapper"><div class="beta-ribbon"><a href="/about/#beta">Beta</a></div></div>
		<?php the_post(); ?>

		<?php get_template_part( 'content', 'page' ); ?>
	</div>

	<?php get_sidebar(); ?>

</div>
<!-- /body -->

<?php get_footer(); ?>
