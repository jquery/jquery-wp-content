	</div>
</div>

<footer class="clearfix simple">
	<div class="constrain">
		<div class="row">
			<div class="six columns offset-by-three">
				<h3><span>Books</span></h3>
				<ul class="books">
					<li>
						<a href="https://www.packtpub.com/web-development/learning-jquery-fourth-edition">
							<img src="<?php echo get_template_directory_uri(); ?>/content/books/learning-jquery-4th-ed.jpg" alt="Learning jQuery 4th Edition by Karl Swedberg and Jonathan Chaffer" width="92" height="114">
							Learning jQuery Fourth Edition
							<cite>Karl Swedberg and Jonathan Chaffer</cite>
						</a>
					</li>
					<li>
						<a href="https://www.manning.com/books/jquery-in-action-third-edition">
							<img src="<?php echo get_template_directory_uri(); ?>/content/books/jquery-in-action.jpg" alt="jQuery in Action by Bear Bibeault, Yehuda Katz, and Aurelio De Rosa" width="92" height="114">
							jQuery in Action
							<cite>Bear Bibeault, Yehuda Katz, and Aurelio De Rosa</cite>
						</a>
					</li>
					<li>
						<a href="https://www.syncfusion.com/ebooks/jquery">
							<img src="<?php echo get_template_directory_uri(); ?>/content/books/jquery-succinctly.jpg" alt="jQuery Succinctly by Cody Lindley" width="92" height="114">
							jQuery Succinctly
							<cite>Cody Lindley</cite>
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
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/docsearch.js@2/dist/cdn/docsearch.min.js"  onload="document.querySelector('input[name=\'s\']') && docsearch({apiKey: '3cfde9aca378c8aab554d5bf1b23489b',
		indexName: 'jquery',
		inputSelector: 'input[name=\'s\']',
		debug: true // Set debug to true if you want to inspect the dropdown
	})" async></script>
</body>
</html>
