<div id="sidebar" class="widget-area" role="complementary">
	<aside class="widget toolbox">
		<div class="inner">
			<header class="clearfix">
				<div class="version-info">
					<p class="version-number"><?php echo jq_release_version(); ?></p>
					<p class="caption">Version</p>
				</div>
				<div class="release-info">
					<p class="date"><?php echo jq_release_date(); ?></p>
					<p class="caption">Released</p>
				</div>
			</header>
			<div class="body">
				<a class="download" href="<?php echo jq_release_download_url(); ?>">
					<span class="inner-wrapper"><span class="icon-download-alt"></span>Download now</span>
				</a>
				<a class="other-link gh-fork" href="<?php echo jq_plugin_repo_url(); ?>"><span class="icon-github"></span>Fork on GitHub</a>
				<?php if ( jq_release_homepage() ) : ?>
					<a class="other-link view-homepage" href="<?php echo jq_release_homepage(); ?>"><span class="icon-external-link"></span>View Homepage</a>
				<?php endif; ?>
				<?php if ( jq_release_demo() ) : ?>
					<a class="other-link demo" href="<?php echo jq_release_demo(); ?>"><span class="icon-eye-open"></span>Try a Demo</a>
				<?php endif; ?>
				<?php if ( jq_release_docs() ) : ?>
					<a class="other-link read-docs" href="<?php echo jq_release_docs(); ?>"><span class="icon-file"></span>Read the Docs</a>
				<?php endif; ?>
				<?php if ( jq_plugin_bugs_url() ): ?>
					<a class="other-link bugs" href="<?php echo jq_plugin_bugs_url(); ?>"><span class="icon-flag"></span>Bug Reports</a>
				<?php endif ?>
			</div>
		</div>
	</aside>

	<aside class="widget github-activity group">
		<h3 class="widget-title"><span class="icon-github"></span>GitHub Activity</h3>
		<div class="info-block watchers">
			<div class="number"><?php echo jq_plugin_watchers(); ?></div>
			<div class="caption">Watchers</div>
		</div>
		<div class="info-block forks">
			<div class="number"><?php echo jq_plugin_forks(); ?></div>
			<div class="caption">Forks</div>
		</div>
	</aside>

	<aside class="widget author-info">
		<h3><span class="icon-user"></span>Author</h3>
		<ul>
			<li><?php echo jq_release_author(array('avatar' => true, 'size' => 80)); ?></li>
		</ul>
	</aside>

	<?php if ( $maintainers = jq_release_maintainers(array('avatar' => true, 'size' => 48)) ) { ?>
	<aside class="widget maintainer-info">
		<h3><span class="icon-wrench"></span>Maintainers</h3>
		<?php echo $maintainers; ?>
	</aside>
	<?php } ?>

	<aside class="widget licenses">
		<h3><span class="icon-book"></span>Licenses</h3>
		<?php echo jq_release_licenses(); ?>
	</aside>

	<aside class="widget dependencies">
		<h3><span class="icon-sitemap"></span>Dependencies</h3>
		<?php echo jq_release_dependencies(); ?>
	</aside>
</div>
