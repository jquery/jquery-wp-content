<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

get_header(); ?>

		<div id="body" class="clearfix">
			<div class="inner" role="main">
				<?php the_post(); ?>
				<?php if ( $post->post_parent ) : ?>
					<div class="notify">
						<p><i class="icon-warning-sign"></i>
						<?php if ( jq_release_is_stable() ) : ?>
							<span>This version is old school, check out the <a href="<?php echo get_permalink( $post->post_parent ); ?>">latest version</a>.</span>
						<?php else : ?>
							<span>This is a pre-release version, check out the <a href="<?php echo get_permalink( $post->post_parent ); ?>">latest stable version</a>.</span>
						<?php endif; ?>
						</p>
					</div>
				<?php endif; ?>

				<?php get_template_part( 'content', 'jquery_plugin' ); ?>

			</div><!-- .inner -->
		</div><!-- #body -->

<?php get_footer(); ?>
