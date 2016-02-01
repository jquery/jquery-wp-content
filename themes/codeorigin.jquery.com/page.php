<?php
wp_enqueue_style('jquery-ui', 'https://code.jquery.com/ui/1.11.4/themes/ui-lightness/jquery-ui.css');
wp_enqueue_style('sri-modal', get_template_directory_uri() . '/css/sri-modal.css');

wp_enqueue_script('jquery-ui', 'https://code.jquery.com/ui/1.11.4/jquery-ui.min.js');
wp_enqueue_script('clipboard', get_template_directory_uri() . '/js/clipboard.1.5.5.min.js');
wp_enqueue_script('sri-modal', get_template_directory_uri() . '/js/sri-modal.js');
?>

<?php get_header(); ?>

<?php the_post(); ?>

<div id="sri-modal-template" title="Code Integration">
	<div class="sri-modal-link">
		<code>&lt;script
			&nbsp;&nbsp;<span>src="{{link}}"</span>
			&nbsp;&nbsp;<span>integrity="{{hash}}"</span>
			&nbsp;&nbsp;<span>crossorigin="anonymous"&gt;&lt;/script&gt;</span>
		</code>

		<button class="sri-modal-copy-btn" title="Copy to clipboard!"
			data-clipboard-text='&lt;script src="{{link}}" integrity="{{hash}}" crossorigin="anonymous"&gt;&lt;/script&gt;'>
			<i class="sri-modal-icon-copy icon-copy"></i>
		</button>
	</div>

	<div class="sri-modal-info">
		The <code>integrity</code> and <code>crossorigin</code> attributes are used for
		<a href="https://www.w3.org/TR/SRI/" target="_blank">Subresource Integrity (SRI) checking</a>.
		This allows browsers to ensure that resources hosted on third-party servers have
		not been tampered with. Use of SRI is recommended as a best-practice, whenever
		libraries are loaded from a third-party source. Read more at <a href="https://www.srihash.org/" target="_blank">srihash.org</a>
	</div>
</div>

<div class="content-full twelve columns">
	<div id="content">
		<?php if ( !count( get_post_meta( $post->ID, "hide_title" ) ) ) : ?>
		<h1 class="entry-title"><?php the_title(); ?></h1>
		<div class="powered-by">Powered by <a href="http://www.maxcdn.com">MaxCDN</a></div>
		<hr>
		<?php endif; ?>

		<?php get_template_part( 'content', 'page' ); ?>
	</div>
</div>

<?php get_footer(); ?>
