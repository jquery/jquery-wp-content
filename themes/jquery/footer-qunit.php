	</div>
</div>
<footer class="clearfix simple">
	<div class="constrain">
		<div class="row">
			<div class="six columns offset-by-three">
				<h3><span>Books</span></h3>
				<ul class="books">
					<li>
						<a href="https://www.packtpub.com/application-development/instant-testing-qunit-instant">
							<img src="<?php echo get_template_directory_uri(); ?>/content/books/qunit-instant-testing.jpg" alt="Instant Testing with QUnit" width="91" height="114">
							<span class="book-title">Instant Testing with QUnit</span>
							<cite>Dmitry Sheiko</cite>
						</a>
					</li>
					<li>
						<a href="https://shop.oreilly.com/product/0636920024699.do">
							<img src="<?php echo get_template_directory_uri(); ?>/content/books/qunit-testable-javascript.gif" alt="Testable JavaScript" width="91" height="114">
							<span class="book-title">Testable JavaScript</span>
							<cite>Mark Ethan Trostler</cite>
						</a>
					</li>
					<li>
						<a href="https://www.tddjs.com/">
							<img src="<?php echo get_template_directory_uri(); ?>/content/books/qunit-tddjs.png" alt="Test-Driven JavaScript Development" width="91" height="114">
							<span class="book-title">Test-Driven JavaScript Development</span>
							<cite>Christian Johansen</cite>
						</a>
					</li>
				</ul>
			</div>
		</div>

		<?php get_template_part( 'footer', 'bottom' ); ?>
	</div>
</footer>

<?php wp_footer(); ?>
	<!-- at the end of the BODY -->
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/docsearch.js@2/dist/cdn/docsearch.min.js"  onload="document.querySelector('input[name=\'s\']') && docsearch({apiKey: 'e0955983cecd6e4582d3a91908d4d766',
		indexName: 'qunitjs',
		inputSelector: 'input[name=\'s\']',
		debug: true // Set debug to true if you want to inspect the dropdown
	})" async></script>

</body>
</html>
