<?php $next_prev_arr = get_next_prev_post(); ?>
<?php echo jq_post_heirarchy(); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>
	</header>

	<div class="entry-content">
		<?php the_content(); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'twentyeleven' ) . '</span>', 'after' => '</div>' ) ); ?>
		<?php if ( has_children($post) ) { ?>
			<ul>
				<?php wp_list_pages("title_li=&sort_column=menu_order&child_of=" . $post->ID); ?>
			</ul>
		<?php }?>
	</div>

	<div class="entry-meta row">
		<?php if (isset($next_prev_arr['prev']) || isset($next_prev_arr['next']) ): ?>
		<div class="bottom-links row">
			<?php if (isset($next_prev_arr['prev'])): ?>
				<div class="prev six columns">
					<a href="<?php echo get_page_link( $next_prev_arr['prev']->ID ); ?>">
					<i class="icon-chevron-left"></i>
					<?php echo $next_prev_arr['prev']->post_title; ?>
					</a>
				</div>
			<?php endif; ?>
			<?php if (isset($next_prev_arr['next'])): ?>
				<div class="next six columns">
					<a href="<?php echo get_page_link( $next_prev_arr['next']->ID ); ?>">
					<?php echo $next_prev_arr['next']->post_title; ?>
					<i class="icon-chevron-right"></i>
					</a>
				</div>
			<?php endif; ?>
		</div>
		<?php else: ?>
		<hr>
		<?php endif; ?>

		<aside class="meta-details six columns">
			<h3>Last Updated</h3>
			<ul>
				<li class="icon-calendar icon-large" title="Last Updated"><span><?php the_modified_time('F j, Y'); ?></span></li>
			</ul>
		</aside>
		<aside class="github-feedback six columns">
			<h3>Suggestions, Problems, Feedback?</h3>
			<a class="button dark" href="<?php echo jq_get_github_url(); ?>"><i class="icon-github"></i> Open an Issue or Submit a Pull Request on GitHub</a>
		</aside>

	</div>
</article>
