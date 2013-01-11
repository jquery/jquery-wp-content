<?php get_header(); ?>

<?php the_post(); ?>

<div class="content-right twelve columns">
	<div id="content">
		<?php if ( !count( get_post_meta( $post->ID, "hide_title" ) ) ) : ?>
		<h1 class="entry-title"><?php the_title(); ?></h1>
		<hr>
		<?php endif; ?>

		<?php get_template_part( 'content', 'page' ); ?>
	</div>
	<?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>
