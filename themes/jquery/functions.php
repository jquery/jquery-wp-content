<?php
/**
 * Functions and definitions
 *
 * For more information on hooks, actions, and filters, see https://codex.wordpress.org/Plugin_API.
 */

require_once 'functions.jquery.php';

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( !isset( $content_width ) ) {
	$content_width = 584;
}

add_action('init', function () {
	// Remove unused stuff from wp_head
	add_action('wp_enqueue_scripts', function () {
		// Disable default styles that we don't use
		wp_dequeue_style('wp-block-library');
		wp_dequeue_style('classic-theme-styles');
		wp_dequeue_style('global-styles');
	});
});

add_action( 'after_setup_theme', function () {
	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Add default posts and comments RSS feed links to <head>.
	add_theme_support( 'automatic-feed-links' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menu( 'primary', __( 'Primary Menu', 'twentyeleven' ) );
} );

/**
 * Returns a "Continue Reading" link for excerpts
 */
function jq_continue_reading_link() {
	return ' <a href="'. esc_url( get_permalink() ) . '">' . __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'twentyeleven' ) . '</a>';
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis
 * and jq_continue_reading_link().
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 */
add_filter( 'excerpt_more', function ( $more ) {
	return ' &hellip;' . jq_continue_reading_link();
} );

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 */
add_filter( 'wp_page_menu_args', function ( $args ) {
	$args['show_home'] = true;
	return $args;
} );

/**
 * Register our sidebars and widgetized areas. Also register the default Epherma widget.
 */
add_action( 'widgets_init', function () {
	register_sidebar( array(
		'name' => __( 'Main Sidebar', 'twentyeleven' ),
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Showcase Sidebar', 'twentyeleven' ),
		'id' => 'sidebar-2',
		'description' => __( 'The sidebar for the optional Showcase Template', 'twentyeleven' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer Area One', 'twentyeleven' ),
		'id' => 'sidebar-3',
		'description' => __( 'An optional widget area for your site footer', 'twentyeleven' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer Area Two', 'twentyeleven' ),
		'id' => 'sidebar-4',
		'description' => __( 'An optional widget area for your site footer', 'twentyeleven' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer Area Three', 'twentyeleven' ),
		'id' => 'sidebar-5',
		'description' => __( 'An optional widget area for your site footer', 'twentyeleven' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
} );

/**
 * Count the number of footer sidebars to enable dynamic classes for the footer
 */
function jq_footer_sidebar_class() {
	$count = 0;

	if ( is_active_sidebar( 'sidebar-3' ) )
		$count++;

	if ( is_active_sidebar( 'sidebar-4' ) )
		$count++;

	if ( is_active_sidebar( 'sidebar-5' ) )
		$count++;

	$class = '';

	switch ( $count ) {
		case '1':
			$class = 'one';
			break;
		case '2':
			$class = 'two';
			break;
		case '3':
			$class = 'three';
			break;
	}

	if ( $class )
		echo 'class="' . $class . '"';
}

/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own jq_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 */
function jq_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<p><span class="icon-link"></span> Pingback: <?php comment_author_link(); ?><?php edit_comment_link( 'Edit', ' &bull; <span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<div class="comment-meta">
				<div class="comment-author vcard">
					<?php
						$avatar_size = 68;
						if ( '0' != $comment->comment_parent )
							$avatar_size = 39;

						echo get_avatar( $comment, $avatar_size );

						echo sprintf( '%1s on %2s:',
							sprintf( '<span class="fn">%s</span>', esc_html( get_comment_author() ) ),
							sprintf( '<a href="%1s"><time pubdate datetime="%2s">%3s</time></a>',
								esc_url( get_comment_link() ),
								get_comment_time( 'c' ),
								sprintf( '%1s at %2s', get_comment_date(), get_comment_time() )
							)
						);

						edit_comment_link( __( 'Edit', 'twentyeleven' ), ' &bull; <span class="edit-link">', '</span>' ); ?>
				</div>
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'twentyeleven' ); ?></em>
					<br>
				<?php endif; ?>
			</div>
			<div class="comment-content"><?php comment_text(); ?></div>
			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply <span>&darr;</span>', 'twentyeleven' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div>
		</article>

	<?php
			break;
	endswitch;
}

/**
 * Prints HTML with meta information for the current post-date/time and author.
 * Create your own jq_posted_on to override in a child theme
 */
function jq_posted_on() {
	printf( __( '<span class="sep">Posted on </span><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s" pubdate>%4$s</time></a><span class="by-author"> <span class="sep"> by </span> <span class="author vcard"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span></span>', 'twentyeleven' ),
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		sprintf( esc_attr__( 'View all posts by %s', 'twentyeleven' ), get_the_author() ),
		esc_html( get_the_author() )
	);
}

function jq_image_posted_on() {
	printf( __( '<span class="sep">Published in </span><a href="%1$s" title="Return to %2$s" rel="gallery">%2$s</a>' ),
		esc_url( get_permalink( wp_get_post_parent_id() ) ),
		get_the_title( wp_get_post_parent_id() )
	);
	$metadata = wp_get_attachment_metadata();
	printf( __( '<a href="%1$s" title="Link to full-size image">%2$s &times; %3$s</a>' ),
		esc_url( wp_get_attachment_url() ),
		$metadata['width'],
		$metadata['height'],
	);
}

/**
 * Adds two classes to the array of body classes.
 * The first is if the site has only had one author with published posts.
 * The second is if a singular post being displayed
 */
add_filter( 'body_class', function ( $classes ) {

	if ( ! is_multi_author() ) {
		$classes[] = 'single-author';
	}

	if ( is_singular() && ! is_home() )
		$classes[] = 'singular';

	return $classes;
} );

/**
 * Content Security Policy
 */
function jq_content_security_policy() {
	if ( !JQUERY_STAGING ) {
		return;
	}
	$nonce = bin2hex( random_bytes( 8 ) );
	$policy = array(
		'default-src' => "'self'",
		'script-src' => "'self' 'nonce-$nonce' code.jquery.com",
		// The nonce is here so inline scripts can be used in the theme
		'style-src' => "'self' 'nonce-$nonce'",
		// data: SVG images are used in typesense
		'img-src' => "'self' data:",
		'connect-src' => "'self' typesense.jquery.com",
		'font-src' => "'self'",
		'object-src' => "'none'",
		'media-src' => "'self'",
		'frame-src' => "'self'",
		'child-src' => "'self'",
		'form-action' => "'self'",
		'frame-ancestors' => "'none'",
		'base-uri' => "'self'",
		'block-all-mixed-content' => '',
		'report-to' => 'https://csp-report-api.openjs-foundation.workers.dev/',
	);

	$policy = apply_filters( 'jq_content_security_policy', $policy );

	$policy_string = '';
	foreach ( $policy as $key => $value ) {
		$policy_string .= $key . ' ' . $value . '; ';
	}

	header( 'Content-Security-Policy-Report-Only: ' . $policy_string );
}

add_action( 'send_headers', 'jq_content_security_policy' );
