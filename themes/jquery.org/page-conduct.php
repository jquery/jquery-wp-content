<?php
/**
 * Template Name: Code of Conduct Sidebar Template
 * Description: A Page Template that adds a sidebar to pages
 */

get_header(); ?>

		<div id="primary" class="content-right twelve columns">
			<div id="content" role="main">

				<?php the_post(); ?>
				<?php if ( !count( get_post_meta( $post->ID, "hide_title" ) ) ) : ?>
				<h1 class="entry-title"><?php the_title(); ?></h1>
				<hr>
				<?php endif; ?>

				<?php get_template_part( 'content', 'page' ); ?>

				<?php comments_template( '', true ); ?>

			</div><!-- #content -->

			<div id="sidebar" class="widget-area" role="complementary">
				<aside class="widget">
					<h3>jQuery Foundation Code of Conduct</h3>
					<ul>
						<li><a href="https://jquery.org/conduct/">Code of Conduct</a></li>
						<li><a href="https://jquery.org/conduct/committee/">Committee</a></li>
						<li><a href="https://jquery.org/conduct/faq/">Frequently Asked Questions</a></li>
						<li><a href="https://jquery.org/conduct/reporting/">Reporting Guide</a></li>
						<li><a href="https://jquery.org/conduct/enforcement-manual/">Enforcement Manual</a></li>
						<li><a href="https://jquery.org/conduct/changes/">Changes</a></li>
					</ul>
				</aside>

				<h2>License</h2>
				<p>
					All content on this page is licensed under a
					<a href="http://creativecommons.org/licenses/by/3.0/">Creative Commons Attribution</a> license.
				</p>
				<p>
					<a href="http://creativecommons.org/licenses/by/3.0/">
						<img src="//i.creativecommons.org/l/by/3.0/88x31.png" height="31" width="88" alt="CC-by">
					</a>
				</p>
			</div>
		</div><!-- #primary -->

<?php get_footer(); ?>
