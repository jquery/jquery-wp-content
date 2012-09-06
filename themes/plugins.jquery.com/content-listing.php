<article class="clearfix">
	<h2 class="plugin-title">
		<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
	</h2>
	<p class="info">
		Version <?php $latest = get_post_meta( $post->ID, 'latest' ); echo $latest[0]; ?> -
		Released <?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago.'; ?>
	</p>
	<?php the_excerpt(); ?>
	<div class="action">
		<a class="button" href="<?php the_permalink(); ?>">View Plugin</a>
	</div>
</article>
