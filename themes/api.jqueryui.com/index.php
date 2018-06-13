<?php
	get_header();
	the_post();

	$versions = jq_ui_api_versions();
	$latestVersion = jq_ui_api_version_latest();
	$thisVersion = jq_ui_api_version_current();
	$rootUrl = $thisVersion === $latestVersion ? '' : "/$thisVersion";
?>

<div class="content-right twelve columns">
	<div id="content">
		<h1 class="entry-title">jQuery UI <?php echo $thisVersion; ?> API Documentation</h1>
		<hr>

		<?php if ( !$rootUrl ) { ?>

		<p>jQuery UI is a curated set of user interface interactions, effects,
			widgets, and themes built on top of the jQuery JavaScript Library.
			If you're new to jQuery UI, you might want to check out our
			<a href="https://jqueryui.com/" title="jQuery UI">main site</a> for
			more information and full demos. If you're new to jQuery, you might
			also be interested in the <a href="https://learn.jquery.com/">jQuery
			Learning Center</a> tutorials.</p>

		<p>This site provides API documentation for jQuery UI <?php echo $thisVersion; ?>. If
			you're working with an older version, you can find the API documentation
			at the links below.
			However, we would encourage you to upgrade to jQuery UI <?php echo $thisVersion; ?> in order
			to receive the best support and take advantage of recent bug fixes
			and enhancements. Check out the
			<a href="https://jqueryui.com/upgrade-guide/<?php echo $thisVersion; ?>/">upgrade guide</a>
			to find out more about jQuery UI <?php echo $thisVersion; ?>.</p>

		<?php } else { ?>

		<p>This site provides API documentation for jQuery UI <?php echo $thisVersion; ?>.
			We encourage you to upgrade to
			<a href="https://api.jqueryui.com/">the latest stable version of jQuery UI</a>
			in order to receive the best support and take advantage of recent bug
			fixes and enhancements. Check out the
			<a href="https://jqueryui.com/upgrade-guide/">upgrade guides</a>
			and <a href="https://jqueryui.com/changelog/">changelogs</a>
			to find out more about upgrading.</p>

		<?php } ?>

		<p>To get started, use the search at the top of the page, view the
			<a href="<?php echo "$rootUrl/category/all/"; ?>">full listing of entries</a>, or browse by
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
			<li><a href="https://api.jquery.com/">jQuery Core API Documentation</a></li>
			<li><a href="https://api.jquerymobile.com/">jQuery Mobile API Documentation</a></li>
			<li><a href="https://learn.jquery.com/">jQuery Learning Center</a></li>
		</ul>

	</div>
	<?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>
