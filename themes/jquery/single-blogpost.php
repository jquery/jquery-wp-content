<?php
/**
 * The Template for displaying all single posts.
 */

get_header(); ?>

<?php the_post(); ?>

<div class="content-right twelve columns">
	<div id="content">
		<h1 class="entry-title"><?php the_title(); ?></h1>
		<?php if ( 'post' == get_post_type() ) : ?>
		<div class="entry-posted">
			<?php
				jq_posted_on();
				edit_post_link( __( 'Edit', 'twentyeleven' ), ' &bull; <span class="edit-link">', '</span>' );
			?>
		</div>
		<?php endif; ?>
		<hr>

		<?php get_template_part( 'content', 'page' ); ?>
		<?php comments_template( '', true ); ?>
	</div>
	<?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>
