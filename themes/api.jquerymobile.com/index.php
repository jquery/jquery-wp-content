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

		<p>If you are new to jQuery Mobile, the introduction to the framework in the 
			<a href="http://demos.jquerymobile.com/">Demos</a>
			would be a good place to start.</p>

		<p>This site provides API documentation for jQuery Mobile <?php echo $thisVersion; ?></p>

		<?php } else { ?>

		<p>This site provides API documentation for jQuery Mobile <?php echo $thisVersion; ?>.
			We encourage you to <a href="http://jquerymobile.com/download/">upgrade to the latest stable version</a> of jQuery Mobile
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

		<p>See the <a href="http://jquerymobile.com/gbs/<?php echo $thisVersion; ?>">supported platforms</a> page for a list of all
			operating systems and browsers that are supported by this version of jQuery Mobile.</p> 

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
			<li><a href="http://api.jquery.com/">jQuery Core API Documentation</a></li>
			<li><a href="http://api.jqueryui.com/">jQuery UI API Documentation</a></li>
			<li><a href="http://learn.jquery.com/">jQuery Learning Center</a></li>
		</ul>

	</div>
	<?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>
