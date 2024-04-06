<div id="comments">
	<?php if ( post_password_required() ) : ?>
		<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'twentyeleven' ); ?></p>
		</div><!-- #comments -->
		<?php
		// Skip the rest of comments.php.
		return;
	endif;
	?>
	<?php if ( have_comments() ) : ?>
		<h2 id="comments-title">
			<?php
				printf( _n( 'One thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', get_comments_number(), 'twentyeleven' ),
					number_format_i18n( get_comments_number() ), '<span>' . get_the_title() . '</span>' );
			?>
		</h2>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
		<nav class="pagination">
			<h1 class="assistive-text"><?php _e( 'Comment navigation', 'twentyeleven' ); ?></h1>
			<?php previous_comments_link( __( '&larr; Older Comments', 'twentyeleven' ) ); ?>
			<?php next_comments_link( __( 'Newer Comments &rarr;', 'twentyeleven' ) ); ?>
			<hr>
		</nav>
		<?php endif; ?>

		<ol class="commentlist">
			<?php wp_list_comments( array( 'callback' => 'jq_comment' ) ); ?>
		</ol>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
		<nav class="pagination">
			<?php previous_comments_link( __( '&larr; Older Comments', 'twentyeleven' ) ); ?>
			<?php next_comments_link( __( 'Newer Comments &rarr;', 'twentyeleven' ) ); ?>
		</nav>
		<?php endif; // check for comment navigation ?>

	<?php
		/* If there are no comments and comments are closed, let's leave a little note, shall we?
		 * But we don't want the note on pages or post types that do not support comments.
		 */
		elseif ( ! comments_open() && ! is_page() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="nocomments"><?php _e( 'Comments are closed.', 'twentyeleven' ); ?></p>
	<?php endif; ?>

	<?php comment_form(); ?>
</div>