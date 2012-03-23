<?php
/*
Template Name: Blank Page
*/

get_header();
the_post();
?>
<div id="body" class="clearfix">
	<div class="inner" role="main">
		<header class="entry-header">
			<h1 class="entry-title"><?php the_title(); ?></h1>
		</header><!-- .entry-header -->
		<?php the_content(); ?>
	</div>
</div>
<?php get_footer(); ?>

