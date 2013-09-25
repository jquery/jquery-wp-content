<?php
	get_header();
	the_post();

	$versions = jq_mobile_api_versions();
	$latestVersion = jq_mobile_api_version_latest();
	$thisVersion = jq_mobile_api_version_current();
	$rootUrl = $thisVersion === $latestVersion ? '' : "/$thisVersion";
?>

<div class="content-right twelve columns">
	<div id="content">
		<h1 class="entry-title">jQuery Mobile <?php echo $thisVersion; ?> API Documentation</h1>
		<hr>

		<?php if ( !$rootUrl ) { ?>

		<p>jQuery Mobile is the easiest way to build sites and apps that are accessible on all
			popular smartphone, tablet and desktop devices.</p>

		<p>If you are new to jQuery Mobile, our tutorial on
			<a href="http://learn.jquery.com/jquery-mobile/getting-started/">Getting Started
			with jQuery Mobile</a> or our
			<a href="http://view.jquerymobile.com/1.3.2/dist/demos/intro/">Introduction</a> to the
			framework would be the perfect place to start.</p>

		<p>You can contribute to the project by reporting issues, suggesting new features,
			or submitting pull requests. Please read our
			<a href="https://github.com/jquery/jquery-mobile/blob/master/CONTRIBUTING.md">Contributing
			Guidelines</a> before submitting.</p>

		<p>This site provides API documentation for jQuery Mobile 1.3 </p>

		<?php } else { ?>

		<p>This site provides API documentation for jQuery Mobile <?php echo $thisVersion; ?>.
			We encourage you to upgrade to
			<a href="http://api.jquerymobile.com/">the latest stable version of jQuery Mobile</a>
			in order to receive the best support and take advantage of recent bug
			fixes and enhancements. Check out the
			<a href="http://jquerymobile.com/upgrade-guide/">upgrade guides</a>
			and <a href="http://jquerymobile.com/changelog/">changelogs</a>
			to find out more about upgrading.</p>

		<?php } ?>

		<p>To get started, use the search at the top of the page, view the
			<a href="<?php echo "$rootUrl/category/all"; ?>">full listing of entries</a>, or browse by
			category from the sidebar.</p>

		<p>jQuery Mobile <?php echo $thisVersion; ?>
			supports jQuery <?php echo $versions[ $thisVersion ]; ?>.</p>

		<hr>

		<h2>Can't find what you're looking for?</h2>

		<p>Perhaps one of the following sites will help:</p>

		<ul>
			<?php foreach( $versions as $version => $_ ) {
				if ( $version === $thisVersion ) {
					continue;
				}

				$url = $version === $latestVersion ? '/' : "/$version/";
				echo "<li><a href=\"$url\">jQuery Mobile $version API Documentation</a></li>";
			} ?>
			<li><a href="http://jquerymobile.com/demos/1.2.0/">jQuery Mobile 1.2.0 Documentation</a></li>
			<li><a href="http://api.jquery.com/">jQuery Core API Documentation</a></li>
			<li><a href="http://api.jqueryui.com/">jQuery UI API Documentation</a></li>
			<li><a href="http://learn.jquery.com/">jQuery Learning Center</a></li>
		</ul>

	</div>
	<?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>
