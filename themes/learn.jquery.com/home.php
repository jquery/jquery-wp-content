<?php
/*
* Template Name: Home
*/
get_header(); ?>

  <!-- body -->
  <div id="body" class="clearfix">

    <!-- inner -->
    <div class="inner">

	<?php the_post(); ?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		<div class="entry-content">
			<?php the_content(); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'twentyeleven' ) . '</span>', 'after' => '</div>' ) ); ?>
			<?php if (!is_subpage()) { ?>
				<ul>
					<?php wp_list_pages("title_li=&sort_column=menu_order&child_of=" . $post->ID); ?>
				</ul>
			<?php }?>
		</div><!-- .entry-content -->
		<footer class="entry-meta">
			<?php edit_post_link( __( 'Edit', 'twentyeleven' ), '<span class="edit-link">', '</span>' ); ?>
		</footer><!-- .entry-meta -->
	</article><!-- #post-<?php the_ID(); ?> -->

    </div>
    <!-- /inner -->

  </div>
  <!-- /body -->

<?php get_footer(); ?>

