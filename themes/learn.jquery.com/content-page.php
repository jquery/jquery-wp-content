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
		<?php if ( has_children($post) ) { ?>
			<ul>
				<?php wp_list_pages("title_li=&sort_column=menu_order&child_of=" . $post->ID); ?>
			</ul>
		<?php }?>
	</div><!-- .entry-content -->

	<hr>

	<div class="entry-meta row">
		<aside class="meta-details six columns">
			<h3>Details</h3>
			<ul>
				<li class="icon-calendar icon-large" title="Last Updated"><?php the_modified_time('F j, Y'); ?></li>
			</ul>
			<?php if ( get_post_meta( $post->ID, "contributors" ) ) : ?>
				<?php $contributors = json_decode(get_post_meta( $post->ID, "contributors", true)) ?>
				<h3>Contributors</h3>
				<ul class="contributor-list">
				<?php foreach ($contributors as $contrib) { ?>
					<li>
						<?php if ( isset( $contrib ->source ) ) : ?>
							<a href="<?php echo $contrib->source ?>">
						<?php endif ?>
						<?php if ( isset( $contrib ->email ) ) : ?>
							<?php echo get_avatar( $contrib->email, 24) ?>
						<?php endif ?>
						<?php echo $contrib->name ?>
						<?php if ( isset( $contrib ->source ) ) : ?>
							</a>
						<?php endif ?>

					</li>
				<?php } ?>
				</ul>
			<?php endif; ?>
		</aside>
		<aside class="github-feedback six columns">
			<h3>Suggestions, Problems, Feedback?</h3>
			<a class="button dark" href="<?php echo jq_get_github_url(); ?>"><i class="icon-github"></i>  Open an Issue or Submit a Pull Request on GitHub</a>
		</aside>

		<?php if (isset($next_prev_arr['prev']) || isset($next_prev_arr['next']) ): ?>
		<div class="bottom-links row">
		    <?php if (isset($next_prev_arr['prev'])): ?>
			   <div class="prev six columns">
			       <a href="<?php echo $next_prev_arr['prev']->guid; ?>">
				   <i class="icon-chevron-left"></i>
				   <?php echo $next_prev_arr['prev']->post_title; ?>
			       </a>
			   </div>
		    <?php endif; ?>
		    <?php if (isset($next_prev_arr['next'])): ?>
			   <div class="next six columns">
			       <a href="<?php echo $next_prev_arr['next']->guid; ?>">
				   <?php echo $next_prev_arr['next']->post_title; ?>
				   <i class="icon-chevron-right"></i>
			       </a>
			   </div>
		    <?php endif; ?>
		</div>
		<?php endif; ?>
	</div><!-- .entry-meta -->
</article><!-- #post-<?php the_ID(); ?> -->
