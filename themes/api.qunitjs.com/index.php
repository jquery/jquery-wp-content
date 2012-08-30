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
		<h1>QUnit API Documentation</h1>
		<hr class="dots">

		<p>QUnit is a powerful, easy-to-use JavaScript unit test suite.
			If you're new to QUnit or even just new to unit testing, you might
			want to check out the <a href="http://qunitjs.com/intro/">Introduction
			to JavaScript Unit Testing</a>. There's also a
			<a href="http://qunitjs.com/cookbook/">QUnit Cookbook</a> on the
			<a href="http://qunitjs.com/">main site</a>.</p>

		<p>To get started, use the search at the top of the page, view the
			<a href="/category/all/">full listing of entries</a>, or browse by
			category:</p>

		<ul>
		<?php wp_list_categories( array( 'title_li' => null ) ); ?>
		</ul>

		<p>QUnit has no dependencies and works in all browsers.</p>
	</div><!-- .inner -->

	<?php if($sidebar): ?>
		<?php get_sidebar( 'api' ); ?>
	<?php endif; ?>

</div><!-- #body -->

<?php get_footer(); ?>
