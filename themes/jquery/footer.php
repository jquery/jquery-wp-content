	</div>
</div>

<footer class="clearfix simple">
	<div class="constrain">
		<div class="row">
			<div id="books" class="six columns offset-by-three"></div>
		</div>
		<?php get_template_part( 'footer', 'bottom' ); ?>
	</div>
</footer>
<script>
	$(function() {
		var urlPrefix = '<?php echo get_template_directory_uri(); ?>';
		$.when($.getJSON(urlPrefix + '/content/books/jquery-book-data.json')).then(function (data) {
			randomImageSelector.init(data.books, { urlPrefix: urlPrefix });
		});
	});
</script>
<?php wp_footer(); ?>

</body>
</html>
