<?php

// attach custom header and footer tags
function header_append() {
	echo '
	<link rel="stylesheet" href="' . get_template_directory_uri() . '/css/hint.2.0.0.min.css" />
	<link rel="stylesheet" href="' . get_template_directory_uri() . '/css/jquery-ui.1.11.4.min.css" />
	<link rel="stylesheet" href="' . get_template_directory_uri() . '/css/modal.css" />
';
}

function footer_append() {
	echo '
	<script src="https://code.jquery.com/jquery-2.2.0.min.js" integrity="sha384-K+ctZQ+LL8q6tP7I94W+qzQsfRV2a+AfHIi9k8z8l9ggpc8X+Ytst4yBo/hH+8Fk" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js" integrity="sha384-YWP9O4NjmcGo4oEJFXvvYSEzuHIvey+LbXkBNJ1Kd0yfugEZN9NCQNpRYBVC1RvA" crossorigin="anonymous"></script>
	<script src="' . get_template_directory_uri() . '/js/clipboard.1.5.5.min.js"></script>
	<script src="' . get_template_directory_uri() . '/js/sri-modal.js"></script>
';
}

?>

<?php get_header(); ?>

<?php the_post(); ?>

<div id="sri-modal-template" title="Code Integration">
	<div class="sri-modal-link">
		<code>
			&lt;script
			&nbsp;&nbsp;<span>src="{{link}}"</span>
			&nbsp;&nbsp;<span>integrity="{{hash}}"</span>
			&nbsp;&nbsp;<span>crossorigin="anonymous"&gt;&lt;/script&gt;</span>
		</code>

		<button class="sri-modal-copy-btn hint--top hint-rounded hint--always"
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
