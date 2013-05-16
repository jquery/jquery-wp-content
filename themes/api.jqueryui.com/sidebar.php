<?php
	$versions = jq_ui_api_versions();
	$thisVersion = jq_ui_api_version_current();
	$latestVersion = jq_ui_api_version_latest();
?>

<div id="sidebar" class="widget-area" role="complementary">
	<aside id="categories" class="widget">
		<h3 class="widget-title">jQuery UI <?php echo $thisVersion; ?></h3>
		<ul>
			<?php wp_list_categories( array(
				'depth' => 2,
				'title_li' => '',
				'current_category' => jq_post_category(),
				'use_desc_for_title' => false
			) ); ?>
		</ul>
	</aside>

	<aside id="categories" class="widget">
		<h3 class="widget-title">Other Versions</h3>
		<ul>
			<?php foreach( $versions as $version => $_ ) {
				if ( $version === $thisVersion ) {
					continue;
				}

				$url = $version === $latestVersion ? '/' : "/$version/";
				echo "<li><a href=\"$url\">jQuery UI $version</a></li>";
			} ?>
		</ul>
	</aside>
</div>
