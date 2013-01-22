<?php
	get_header();
	the_post();

	// Must be listed with newest first
	$versions = array(
		"1.10" => "1.6 and newer",
		"1.9" => "1.6 and newer",
		"1.8" => "1.3.2 and newer",
	);

	$latestVersion = key( $versions );
	$thisVersion = explode( "/", JQUERY_LIVE_SITE );
	$thisVersion = $thisVersion[1];
	$rootUrl = $thisVersion === $latestVersion ? '' : "/$thisVersion";
?>

<div class="content-right twelve columns">
	<div id="content">
		<h1 class="entry-title">jQuery UI <?php echo $thisVersion; ?> API Documentation</h1>
		<hr>

		<p>This site provides API documentation for jQuery UI <?php echo $thisVersion; ?>.
			We encourage you to upgrade to
			<a href="http://api.jqueryui.com/">the latest stable version of jQuery UI</a>
			in order to receive the best support and take advantage of recent bug
			fixes and enhancements. Check out the
			<a href="http://jqueryui.com/upgrade-guide/">upgrade guides</a>
			and <a href="http://jqueryui.com/changelog/">changelogs</a>
			to find out more about upgrading.</p>

		<p>To get started, use the search at the top of the page, view the
			<a href="<?php echo "$rootUrl/category/all"; ?>">full listing of entries</a>, or browse by
			category from the sidebar.</p>

		<p>jQuery UI <?php echo $thisVersion; ?>
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
				echo "<li><a href=\"$url\">jQuery UI $version API Documentation</a></li>";
			} ?>
			<li><a href="http://api.jquery.com/">jQuery Core API Documentation</a></li>
			<li><a href="http://api.jquerymobile.com/">jQuery Mobile API Documentation</a></li>
			<li><a href="http://learn.jquery.com/">jQuery Learning Center</a></li>
		</ul>

	</div>
	<?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>
