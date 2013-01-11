<?php
/**
 * The template for displaying 404 pages (Not Found).
 */

get_header(); ?>
<div class="content-full full-width twelve columns">
	<div id="content">
		<article id="post-0" class="post error404 not-found">
			<header class="entry-header">
				<h1 class="entry-title"><?php _e( 'This is somewhat embarrassing, isn&rsquo;t it?', 'twentyeleven' ); ?></h1>
				<hr>
			</header>

			<div class="entry-content">
				<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for.', 'twentyeleven' ); ?></p>
			</div>
		</article>
	</div>
</div>
<?php get_footer(); ?>
