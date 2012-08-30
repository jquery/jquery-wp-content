<?php
/**
 * The main template file.
 */

get_header();
?>

<!-- body -->
<?php global $sidebar; ?>
<div id="body" class="clearfix <?php echo $sidebar; ?>">
	<div class="inner" role="main">
		<h1>jQuery UI API Documentation</h1>
		<hr class="dots">

		<p>jQuery UI is a curated set of user interface interactions, effects,
			widgets, and themes built on top of the jQuery JavaScript Library.
			If you're new to jQuery UI, you might want to check out our
			<a href="http://jqueryui.com/" title="jQuery UI">main site</a> for
			more information and full demos. If you're new to jQuery, you might
			also be interested in the <a href="http://learn.jquery.com/">Learn jQuery</a>
			tutorials.</p>

		<p>This site provides API documentation for jQuery UI 1.9. If
			you're working with jQuery UI 1.8, you can find the API documentation
			on <a href="http://1-8.api.jqueryui.com/">1-8.api.jqueryui.com</a>.
			However, we would encourage you to upgrade to jQuery UI 1.9 in order
			to receive the best support and take advantage of recent bug fixes
			and enhancements. Check out the <a href="#">release announcement</a>
			and <a href="http://jqueryui.com/upgrade-guide/1.9/">upgrade guide</a>
			to find out more about jQuery UI 1.9.</p>

		<p>To get started, use the search at the top of the page, view the
			<a href="/category/all/">full listing of entries</a>, or browse by
			category:</p>

		<ul>
		<?php wp_list_categories( array( 'title_li' => null ) ); ?>
		</ul>

		<p>jQuery UI 1.9 supports jQuery 1.6 and newer.</p>
	</div><!-- .inner -->

	<?php if($sidebar): ?>
		<?php get_sidebar( 'api' ); ?>
	<?php endif; ?>

</div><!-- #body -->

<?php get_footer(); ?>
