<?php get_header(); ?>

<?php global $sidebar; ?>
<div class="content-right">
	<div id="content">
		<div class="beta-ribbon-wrapper"><div class="beta-ribbon"><a href="/about/#beta">Beta</a></div></div>
		<?php the_post(); ?>

		<?php get_template_part( 'content', 'page' ); ?>
	</div>

	<?php if($sidebar): ?>
		<?php get_sidebar(); ?>
	<?php endif; ?>

</div>
<!-- /body -->

<?php get_footer(); ?>
