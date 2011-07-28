<?php	

/***************************************************************************/	
/* 	Custom Write Panels
/***************************************************************************/

	///////////////////////////////////////
	// Setup Write Panel Options
	///////////////////////////////////////
	
	// Post Meta Box Options
	$post_meta_box_options = array();	
			
	
	///////////////////////////////////////
	// Build Write Panels
	///////////////////////////////////////
	themify_build_write_panels(array(
									array(
										 "name"		=> "Post Options",																 "options"	=> $post_meta_box_options, 														 "pages"	=> "post"										 							)
								));
	
/***************************************************************************/
/* 	Custom Functions
/***************************************************************************/
	
	///////////////////////////////////
	// Page navigation
	///////////////////////////////////
	function pagenav($before = '', $after = '') {
		global $wpdb, $wp_query;
	
		$request = $wp_query->request;
		$posts_per_page = intval(get_query_var('posts_per_page'));
		$paged = intval(get_query_var('paged'));
		$numposts = $wp_query->found_posts;
		$max_page = $wp_query->max_num_pages;
	
		if(empty($paged) || $paged == 0) {
			$paged = 1;
		}
		$pages_to_show = 8;
		$pages_to_show_minus_1 = $pages_to_show-1;
		$half_page_start = floor($pages_to_show_minus_1/2);
		$half_page_end = ceil($pages_to_show_minus_1/2);
		$start_page = $paged - $half_page_start;
		if($start_page <= 0) {
			$start_page = 1;
		}
		$end_page = $paged + $half_page_end;
		if(($end_page - $start_page) != $pages_to_show_minus_1) {
			$end_page = $start_page + $pages_to_show_minus_1;
		}
		if($end_page > $max_page) {
			$start_page = $max_page - $pages_to_show_minus_1;
			$end_page = $max_page;
		}
		if($start_page <= 0) {
			$start_page = 1;
		}
	
		if ($max_page > 1) {
			echo $before.'<div class="pagenav clearfix">';
			if ($start_page >= 2 && $pages_to_show < $max_page) {
				$first_page_text = "&laquo;";
				echo '<a href="'.get_pagenum_link().'" title="'.$first_page_text.'" class="number">'.$first_page_text.'</a>';
			}
			//previous_posts_link('&lt;');
			for($i = $start_page; $i  <= $end_page; $i++) {
				if($i == $paged) {
					echo ' <span class="number current">'.$i.'</span> ';
				} else {
					echo ' <a href="'.get_pagenum_link($i).'" class="number">'.$i.'</a> ';
				}
			}
			//next_posts_link('&gt;');
			if ($end_page < $max_page) {
				$last_page_text = "&raquo;";
				echo '<a href="'.get_pagenum_link($max_page).'" title="'.$last_page_text.'" class="number">'.$last_page_text.'</a>';
			}
			echo '</div>'.$after;
		}
	}
	
	
	///////////////////////////////////
	// Title Tag Function
	///////////////////////////////////
	function title_tag() {
		if (is_home() || is_front_page()) {
			echo bloginfo('name');
		} elseif (is_404()) {
			_e('404 Not Found','themify');
		} elseif (is_category()) {
			_e('Category:','themify'); wp_title('');
		} elseif (is_tag()) {
			_e('Tag:','themify'); wp_title('');
		} elseif (is_search()) {
			_e('Search Results','themify');
		} elseif ( is_day() || is_month() || is_year() ) {
			_e('Archives:','themify'); wp_title('');
		} else {
			echo wp_title('');
		}
	}
	
	///////////////////////////////////
	// Register Custom Menu Function
	///////////////////////////////////
	function register_custom_nav() {
		if (function_exists('register_nav_menus')) {
			register_nav_menus( array(
				'main-nav' => __( 'Main Navigation', 'themify' ),
				'footer-nav' => __( 'Footer Navigation', 'themify' ),
			) );
		}
	}
	
	///////////////////////////////////
	// Register Custom Menu Function - Action
	///////////////////////////////////
	add_action('init', 'register_custom_nav');
	
	///////////////////////////////////
	// Default Main Nav Function
	///////////////////////////////////
	function default_main_nav() {
		echo '<ul id="main-nav" class="clearfix">';
		wp_list_pages('title_li=');
		echo '</ul>';
	}
	
	///////////////////////////////////
	// Register Widgets
	///////////////////////////////////
	if ( function_exists('register_sidebar') ) {
		register_sidebar(array(
			'name' => 'Header_Widget',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<strong class="widgettitle">',
			'after_title' => '</strong>',
		));
		register_sidebar(array(
			'name' => 'Social_Widget',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<strong class="widgettitle">',
			'after_title' => '</strong>',
		));
		register_sidebar(array(
			'name' => 'Sidebar_Narrow',
			'before_widget' => '<div class="widgetwrap"><div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div></div>',
			'before_title' => '<h4 class="widgettitle">',
			'after_title' => '</h4>',
		));
		register_sidebar(array(
			'name' => 'Sidebar_Wide',
			'before_widget' => '<div class="widgetwrap"><div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div></div>',
			'before_title' => '<h4 class="widgettitle">',
			'after_title' => '</h4>',
		));
		register_sidebar(array(
			'name' => 'Sidebar_Wide_2A',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="widgettitle">',
			'after_title' => '</h4>',
		));
		register_sidebar(array(
			'name' => 'Sidebar_Wide_2B',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="widgettitle">',
			'after_title' => '</h4>',
		));
		register_sidebar(array(
			'name' => 'Sidebar_Wide_3',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="widgettitle">',
			'after_title' => '</h4>',
		));
		register_sidebar(array(
				'name' => 'Footer_Widget_1',
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h4 class="widgettitle">',
				'after_title' => '</h4>',
			));	
		register_sidebar(array(
				'name' => 'Footer_Widget_2',
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h4 class="widgettitle">',
				'after_title' => '</h4>',
			));	
		register_sidebar(array(
				'name' => 'Footer_Widget_3',
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h4 class="widgettitle">',
				'after_title' => '</h4>',
			));	
	}
	
	
	///////////////////////////////////
	// Check if paged > 1
	///////////////////////////////////
	function show_posts_nav() {
		global $wp_query;
		return ($wp_query->max_num_pages > 1);
	}
	
	///////////////////////////////////
	// Custom Theme Comment
	///////////////////////////////////
	function custom_theme_comment($comment, $args, $depth) {
	   $GLOBALS['comment'] = $comment; 
	   ?>
		<li id="comment-<?php comment_ID() ?>">
			<p class="comment-author">
				<?php echo get_avatar($comment,$size='36'); ?>
				<?php printf(__('<cite>%s</cite>'), get_comment_author_link()) ?><br />
				<small class="comment-time"><strong><?php comment_date('M d, Y'); ?></strong> @ <?php comment_time('H:i:s'); ?><?php edit_comment_link('Edit',' [',']') ?></small>
			</p>
			<div class="commententry">
				<?php if ($comment->comment_approved == '0') : ?>
				<p><em><?php _e('Your comment is awaiting moderation.') ?></em></p>
				<?php endif; ?>
			
				<?php comment_text() ?>
			</div>
			<p class="reply"><?php comment_reply_link(array_merge( $args, array('add_below' => 'comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?></p>
		<?php
	}

?>