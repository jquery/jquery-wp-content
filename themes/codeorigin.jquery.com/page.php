<?php get_header(); ?>

<?php the_post(); ?>

<div class="content-full twelve columns">
	<div id="content">
		<?php if ( !count( get_post_meta( $post->ID, "hide_title" ) ) ) : ?>
		<h1 class="entry-title"><?php the_title(); ?></h1>
		<div class="powered-by">Powered by <a href="http://www.maxcdn.com">MaxCDN</a></div>
		<hr>
		<?php endif; ?>

		<?php get_template_part( 'content', 'page' ); ?>
	</div>
</div>

<?php get_footer(); ?>
