<?php get_header(); ?>
<div class="content-right listing twelve columns">
	<div id="content">
		<h1 class="page-title">Globalize API</h1>
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
