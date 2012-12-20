<?php
/**
 * The template for displaying a detailed view of individual aspects of the API documentation.
 *
 * The content displayed by this template is managed in a GitHub repository at:
 * https://github.com/jquery/api.jquery.com
 **/
 ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>
		<hr>
		<?php if ( 'post' == get_post_type() ) : ?>
		<div class="entry-meta">
			<?php echo jq_categories_and_parents() ?>
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
	</div><!-- .entry-content -->

</article><!-- #post-<?php the_ID(); ?> -->
