<?php
/**
 * The template used for displaying page content in page.php
 */
?>

<?php $next_prev_arr = get_next_prev_post(); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'twentyeleven' ) . '</span>', 'after' => '</div>' ) ); ?>
		<?php if (!is_subpage()) { ?>
			<ul>
				<?php wp_list_pages("title_li=&sort_column=menu_order&child_of=" . $post->ID); ?>
			</ul>
		<?php }?>
	</div><!-- .entry-content -->

	<hr/>

	<footer class="entry-meta">
		<aside class="meta-details">
			<h3>Details</h3>
			<ul>
				<li class="icon-calendar icon-large" title="Last Updated"><?php the_modified_time('F j, Y'); ?></li>
			</ul>
		</aside>
		<aside class="github-feedback">
			<h3>Suggestions, Problems, Feedback?</h3>
			<a class="btn" href="<?php echo jq_get_github_url(); ?>"><i class="icon-github"></i>  Open an Issue or Submit a Pull Request on GitHub</a>
		</aside>

		<?php if (isset($next_prev_arr['prev']) || isset($next_prev_arr['next']) ): ?>
		<div class='bottom-links'>
		    <?php if (isset($next_prev_arr['prev'])): ?>
			   <div class='prev'>
			       <a href="<?php echo $next_prev_arr['prev']->guid; ?>">
				   <i class="icon-chevron-left"></i>
				   <?php echo $next_prev_arr['prev']->post_title; ?>
			       </a>
			   </div>
		    <?php endif; ?>
		    <?php if (isset($next_prev_arr['next'])): ?>
			   <div class='next'>
			       <a href="<?php echo $next_prev_arr['next']->guid; ?>">
				   <?php echo $next_prev_arr['next']->post_title; ?>
				   <i class="icon-chevron-right"></i>
			       </a>
			   </div>
		    <?php endif; ?>
		</div>
		<?php endif; ?>
	</footer><!-- .entry-meta -->
</article><!-- #post-<?php the_ID(); ?> -->
