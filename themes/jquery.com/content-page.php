<?php
/**
 * The template used for displaying page content in page.php
 */
?><div id="jq-content" class="jq-clearfix">
<?php
$jqpc = get_post_meta( $post->ID, "jq-primaryContent" );
if ($jqpc[0]) { ?>
	<style>
	#jq-primaryContent ul ul { margin-left: 1.5em; }
	.visuallyhidden  { display: none; }
	</style>
	<div id="jq-primaryContent" style="width:auto;">
		<?php the_content(); ?>
	</div>
<?php } else { ?>
	<?php the_content(); ?>
<?php } ?>
</div><!-- /#content -->