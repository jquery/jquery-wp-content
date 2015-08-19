	</div>
</div>

<footer class="clearfix simple">
	<div class="constrain">
		<div class="row">
			<div class="six columns offset-by-three">
				<h3><span>Books</span></h3>
				<ul class="books">
					<li>
						<a href="https://www.packtpub.com/web-development/mastering-jquery-ui">
							<span><img src="<?php echo get_template_directory_uri(); ?>/content/books/mastering-jquery-ui.jpg" alt="Mastering jQuery UI by Vijay Joshi" width="92" height="114"></span>
							<strong>Mastering jQuery UI</strong><br>
							<cite>Vijay Joshi</cite>
						</a>
					</li>
					<li>
						<a href="https://www.packtpub.com/application-development/creating-mobile-apps-jquery-mobile-update">
							<img src="<?php echo get_template_directory_uri(); ?>/content/books/creating-mobile-apps-jquery-mobile.jpg" alt="Creating Mobile Apps with jQuery Mobile by Andy Matthews" width="92" height="114">
							<span class="book-title">Creating Mobile Apps with jQuery Mobile - Second Edition</span>
							<cite>Andy Matthews</cite>
						</a>
					</li>
					<li>
						<a href="https://www.packtpub.com/web-development/mastering-jquery">
							<img src="<?php echo get_template_directory_uri(); ?>/content/books/mastering-jquery.jpg" alt="Mastering jQuery by Alex Libby" width="92" height="114">
							<span class="book-title">Mastering jQuery</span>
							<cite>Alex Libby</cite>
						</a>
					</li>
				</ul>
			</div>
		</div>

		<?php get_template_part( 'footer', 'bottom' ); ?>
	</div>
</footer>

<?php wp_footer(); ?>

</body>
</html>
