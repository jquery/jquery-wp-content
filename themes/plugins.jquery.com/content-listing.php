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
		<div class="github-activity entry-meta">
			<div class="info-block watchers">
				<div class="number"><?php echo jq_plugin_watchers(); ?></div>
				<div class="caption">Watchers</div>
			</div>
			<div class="info-block forks">
				<div class="number"><?php echo jq_plugin_forks(); ?></div>
				<div class="caption">Forks</div>
			</div>
		</div>
		<div class="eight coulmns">
			<?php the_excerpt(); ?>
		</div>
	</div>
</article>
