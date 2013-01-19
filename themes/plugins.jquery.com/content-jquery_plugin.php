<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<h1 class="entry-title"><?php the_title(); ?></h1>
	<p class="attribution">by <?php echo jq_release_author(); ?></p>
	<div class="block description">
		<?php echo htmlspecialchars(get_the_content()); ?>
	</div>

	<hr>

	<?php if ( $keywords = jq_release_keywords() ) { ?>
		<div class="block tags">
			<h2>Tags</h2>
			<?php echo $keywords; ?>
		</div>
		<hr>
	<?php } ?>

	<div class="block versions">
		<h2>Versions</h2>
		<table>
			<thead>
				<tr>
					<th class="version-head">Version</th>
					<th class="release-date-head">Date</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach( jq_plugin_versions() as $version ) : ?>
				<tr>
					<td class="version"><?php echo $version[ 'link' ]; ?></td>
					<td class="release-date"><?php echo date_format(new DateTime($version[ 'date' ]), "M j Y"); ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</article>
