<?php get_header(); ?>

<div class="content-full full-width listing twelve columns">
	<div id="content">
		<div id="banner-secondary" class="large-banner">
			<h1>The jQuery Plugin Registry</h1>
			<?php get_search_form(); ?>
		</div>

		<div class="three columns">
			<aside class="widget">
				<h3><i class="icon-tags"></i>Popular Tags</h3>
				<?php jq_popular_tags(); ?>
			</aside>
		</div>
		<div class="nine columns">
			<h2 class="center-txt">The jQuery Plugin Registry is in read-only mode.</h2>
			<h2 class="center-txt">New plugin releases will not be processed.</h2>

			<hr>

			<p>We recommend moving to npm, using "<a href="https://www.npmjs.org/browse/keyword/jquery-plugin">jquery-plugin</a>" as the keyword in your package.json. The npm blog has <a href="http://blog.npmjs.org/post/111475741445/publishing-your-jquery-plugin-to-npm-the-quick">instructions for publishing your plugin to npm</a>.</p>
		</div>
	</div>
</div>

<?php get_footer(); ?>
