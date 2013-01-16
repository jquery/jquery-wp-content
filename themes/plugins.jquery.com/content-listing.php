<article class="hentry clearfix">
	<header class="entry-header">
		<div class="entry-meta">
			Version <?php $latest = get_post_meta( $post->ID, 'latest' ); echo $latest[0]; ?><br>
			Released <?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago'; ?>
		</div>
		<h2 class="entry-title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h2>
	</header>
	<div class="entry-summary row collapse">
		<div class="eight columns">
			<?php the_excerpt(); ?>
		</div>
		<div class="four columns">
			<a class="button" href="<?php the_permalink(); ?>">View Plugin</a>
		</div>
	</div>
</article>
