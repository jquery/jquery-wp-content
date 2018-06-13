<?php get_header(); ?>
<div class="content-right listing twelve columns">
	<div id="content">
		<h1 class="page-title">jQuery API</h1>
		<p>jQuery is a fast, small, and feature-rich JavaScript library. It makes things like HTML document traversal and manipulation, event handling, animation, and Ajax much simpler with an easy-to-use API that works across a multitude of browsers. If you're new to jQuery, we recommend that you check out the <a href="https://learn.jquery.com/">jQuery Learning Center</a>.</p>
		<p>If you're updating to a newer version of jQuery, be sure to read the release notes published on <a href="https://blog.jquery.com/">our blog</a>. If you're coming from a version prior 1.9, you should check out the <a href="https://jquery.com/upgrade-guide/1.9/">1.9 Upgrade Guide</a> as well.</p>
		<p>Note that this is the API documentation for jQuery core. Other projects have API docs in other locations:</p>
		<ul>
			<li><a href="https://api.jqueryui.com/">jQuery UI API docs</a></li>
			<li><a href="https://api.jquerymobile.com">jQuery Mobile API docs</a></li>
			<li><a href="https://api.qunitjs.com">QUnit API docs</a></li>
		</ul>
		<hr>

		<?php
			while ( have_posts() ) : the_post();
				get_template_part( 'content', 'api' );
			endwhile;
		?>
	</div>

	<?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>
