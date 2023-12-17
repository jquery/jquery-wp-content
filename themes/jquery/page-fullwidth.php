<?php
/*
 * Template Name: Full Width
 */
?>
<?php get_header(); ?>

<?php the_post(); ?>

<div class="content-full full-width twelve columns">
	<div id="content">
		<?php if ( get_post_meta( $post->ID, "subtitle" ) ) : ?>
		<div id="banner-secondary" class="large-banner entry-header">
			<h1 class="entry-title"><?php the_title(); ?></h1>
			<p><?php echo get_post_meta( $post->ID, "subtitle", true ); ?></p>
		</div>
		<?php elseif ( !get_post_meta( $post->ID, "hide_title" ) ) : ?>
		<h1 class="entry-title"><?php the_title(); ?></h1>
		<hr>
		<?php endif; ?>

		<?php get_template_part( 'content', 'page' ); ?>
	</div>
</div>

<?php get_footer(); ?>
