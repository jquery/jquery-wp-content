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

<?php if ( !count( get_post_meta( $post->ID, "hide_title" ) ) ) : ?>
<div id="banner-secondary" class="large-banner entry-header">
	<h1 class="entry-title"><?php the_title(); ?></h1>
</div>
<?php endif; ?>

<div id="content">

	<?php get_template_part( 'content', 'page' ); ?>

	<?php comments_template( '', true ); ?>

</div>
<?php get_sidebar(); ?>

<?php get_footer(); ?>
