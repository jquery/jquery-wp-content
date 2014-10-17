<?php get_header(); ?>

<div class="content-full full-width listing twelve columns">
	<div id="banner-secondary" class="large-banner">
		<h1>The jQuery Plugin Registry</h1>
		<?php get_search_form(); ?>
	</div>

	<div id="content">
		<div class="three columns">
			<aside class="widget">
				<h3><i class="icon-tags"></i>Popular Tags</h3>
				<?php jq_popular_tags(); ?>
			</aside>
		</div>
		<div class="nine columns">
			<h2 class="center-txt">The jQuery Plugin Registry is in read-only mode.</h2>
			<h2 class="center-txt">New plugin releases will not be processed.</h2>
			<p class="center-txt">We recommend moving to npm, using "<a href="https://www.npmjs.org/browse/keyword/jquery-plugin">jquery-plugin</a>" as the keyword in your package.json.</p>
		</div>
	</div>
</div>

<?php get_footer(); ?>
