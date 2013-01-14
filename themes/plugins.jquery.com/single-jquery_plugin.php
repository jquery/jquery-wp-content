<?php get_header(); ?>
<?php the_post(); ?>

<div class="content-left twelve columns jquery-plugin">
	<div id="content" class="jquery-plugin">
		<?php if ( $post->post_parent && (jq_release_version() !== jq_release_version( $post->post_parent )) ) : ?>
			<div class="notify">
				<p class="icon-warning-sign">
				<?php if ( jq_release_is_stable() ) : ?>
					This version is old school, check out the <a href="<?php echo get_permalink( $post->post_parent ); ?>">latest version</a>.
				<?php else : ?>
					This is a pre-release version, check out the <a href="<?php echo get_permalink( $post->post_parent ); ?>">latest stable version</a>.
				<?php endif; ?>
				</p>
			</div>
		<?php endif; ?>

		<?php get_template_part( 'content', 'jquery_plugin' ); ?>
	</div>
	<?php get_sidebar( 'jquery_plugin' ); ?>
</div>

<?php get_footer(); ?>
