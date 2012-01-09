<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>
	</header><!-- .entry-header -->

	<div class="entry-content">
	     <div class="col col3-2">
	          by <?php echo jq_release_author(); ?>
     	     	  <div class="block">
	     	       <?php the_content(); ?>
	     	  </div>
		  <div class="block">
		       <h2>Author</h2>
		       <?php echo jq_release_author(); ?>
		  </div>
		  <div class="block">
		       <h2>Maintainers</h2>
		       <ul>
		        <?php echo jq_release_maintainers(); ?>
		       </ul>
		  </div><!-- .block -->
		  <div class="block">
		       <h2>Versions</h2>
		       <ul>
		        <?php echo jq_plugin_versions(); ?>
		       </ul>
		  </div><!-- .block -->		  
	     </div><!-- .col -->

	     <div class="col col3-1">
	     	  <div class="block">
		       <h2>Get it now</h2>
		       <ul>
			<li><a href="<?php echo jq_release_download_url(); ?>"><b>Download</b> plugin</a></li>
			<li><a href="<?php echo jq_plugin_repo_url(); ?>"><b>Fork</b> on GitHub</a></li>
			<li><a href="<?php echo jq_release_homepage(); ?>"><b>View</b> homepage</a></li>
			<?php if ( jq_release_demo() ) { ?>
				<li><a href="<?php echo jq_release_demo(); ?>"><b>Try</b> a demo</a></li>
			<?php } ?>
			<?php if ( jq_release_docs() ) { ?>
				<li><a href="<?php echo jq_release_docs(); ?>"><b>Read</b> the docs</a></li>
			<?php } ?>
		       </ul>
		  </div>
		  <div class="block clearfix">
		       <div class="col col4-2">
		       	    <h2>Watchers</h2>
			    <p><b><?php echo jq_plugin_watchers(); ?></b></p>
		       </div>
		       <div class="col col4-2 right">
		       	    <h2>Forks</h2>
			    <p><b><?php echo jq_plugin_forks(); ?></b></p>
			</div>
		</div>
		<div class="block clearfix">
		     <div class="col col4-2">
		     	  <h2>Version</h2>
		     	  <p><b><?php echo jq_release_version(); ?></b></p>
		 </div>
		  <div class="col col4-2 right">
		     <h2>Released</h2>
		     <p><b><?php echo jq_release_date(); ?></b></p>
		</div>
	</div>
	<div class="block">
	     <h2>Licenses</h2>
	     <ul>
		<?php echo jq_release_licenses(); ?>
	     </ul>
	</div>
	<div class="block">
	     <h2>Dependencies</h2>
	     <ul>
		<?php echo jq_release_dependencies(); ?>
	     </ul>
	</div>
	<div class="block">
	     <h2>Tags</h2>
	     <ul>
		<?php echo jq_release_keywords(); ?>
	     </ul>
	</div>
</div>

	</div><!-- .entry-content -->
	<footer class="entry-meta">
	</footer><!-- .entry-meta -->
</article><!-- #post-<?php the_ID(); ?> -->
