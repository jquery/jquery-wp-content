<?php
/**
 * The template for displaying image attachments.
 */

get_header(); ?>

<?php the_post(); ?>

<div class="content-right twelve columns">
	<div id="content">
		<header class="entry-header">
			<h1 class="entry-title"><?php the_title(); ?></h1>
			<div class="entry-posted">
				<?php
					jq_posted_on();
					edit_post_link( __( 'Edit', 'twentyeleven' ), ' &bull; <span class="edit-link">', '</span>' );
				?>
			</div>
		</header><!-- .entry-header -->

		<nav class="pagination">
			<h3 class="assistive-text"><?php _e( 'Image navigation', 'twentyeleven' ); ?></h3>
			<?php previous_image_link( false, __( '&larr; Previous' , 'twentyeleven' ) ); ?>
			<?php next_image_link( false, __( 'Next &rarr;' , 'twentyeleven' ) ); ?>
		</nav>

		<div class="entry-content">

			<div class="entry-attachment">
				<div class="attachment">
<?php
	/**
	 * Grab the IDs of all the image attachments in a gallery so we can get the URL of the next adjacent image in a gallery,
	 * or the first image (if we're looking at the last image in a gallery), or, in a gallery of one, just the link to that image file
	 */
	$attachments = array_values( get_children( array( 'post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID' ) ) );
	foreach ( $attachments as $k => $attachment ) {
		if ( $attachment->ID == $post->ID )
			break;
	}
	$k++;
	// If there is more than 1 attachment in a gallery
	if ( count( $attachments ) > 1 ) {
		if ( isset( $attachments[ $k ] ) )
			// get the URL of the next image attachment
			$next_attachment_url = get_attachment_link( $attachments[ $k ]->ID );
		else
			// or get the URL of the first image attachment
			$next_attachment_url = get_attachment_link( $attachments[ 0 ]->ID );
	} else {
		// or, if there's only 1 image, get the URL of the image
		$next_attachment_url = wp_get_attachment_url();
	}
?>
					<a href="<?php echo esc_url( $next_attachment_url ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="attachment"><?php
					$attachment_size = 848;
					echo wp_get_attachment_image( $post->ID, array( $attachment_size, 1024 ) ); // filterable image width with 1024px limit for image height.
					?></a>

					<?php if ( ! empty( $post->post_excerpt ) ) : ?>
					<div class="entry-caption">
						<?php the_excerpt(); ?>
					</div>
					<?php endif; ?>
				</div><!-- .attachment -->

			</div><!-- .entry-attachment -->

			<div class="entry-description">
				<?php the_content(); ?>
				<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'twentyeleven' ) . '</span>', 'after' => '</div>' ) ); ?>
			</div><!-- .entry-description -->

		</div><!-- .entry-content -->

	</div><!-- #content -->
	<?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>
