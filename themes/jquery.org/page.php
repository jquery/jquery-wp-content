<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 */

get_header(); ?>

<?php the_post(); ?>

<div class="content-full twelve columns<?php if ( count( get_post_meta( $post->ID, "full-width" ) ) ) : ?> full-width<?php endif; ?>">
	<div id="content">
		<?php if ( count( get_post_meta( $post->ID, "banner-image" ) ) ) { ?>

		<div id="banner-large-image" class="large-banner entry-header">
			<div class="vertically-centered-black-bg">
				<h2 class="entry-title"><?php the_title(); ?></h2>
				<?php if ( count( get_post_meta( $post->ID, "subtitle" ) ) ) : ?>
					<p><?php echo get_post_meta( $post->ID, "subtitle", true ); ?></p>
				<?php endif; ?>
			</div>
		</div>

		<?php } else { ?>

		<div id="banner-secondary" class="large-banner entry-header">
			<h1 class="entry-title"><?php the_title(); ?></h1>
			<?php if ( count( get_post_meta( $post->ID, "subtitle" ) ) ) : ?>
				<p><?php echo get_post_meta( $post->ID, "subtitle", true ); ?></p>
			<?php endif; ?>
		</div>

		<?php } ?>

		<?php get_template_part( 'content', 'page' ); ?>

	</div>
</div>

<?php get_footer(); ?>
