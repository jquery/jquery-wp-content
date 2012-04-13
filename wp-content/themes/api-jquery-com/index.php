<?php get_header(); ?>
		
  <!-- body -->
  <?php global $sidebar; ?>
  <div id="body" class="clearfix <?php echo $sidebar; ?>">
    
    <!-- inner -->
    <div class="inner">
    
<?php
$pageposts = $wpdb->get_results("SELECT *, REPLACE(post_name, 'jQuery.', '') as trimname FROM wp_2_posts as p WHERE p.post_status='publish' AND p.post_type = 'post' AND p.post_date < NOW() ORDER BY trimname;");

if ($pageposts):
  $corelist = array();
  $pluginlist = array();

  foreach ($pageposts as $post):
    setup_postdata($post);

    $isplugin = ks_in_plugins_category();
    $itemclass = $isplugin ? 'plugin' : 'core';

    $editlink = get_edit_post_link();
    if ($editlink) {
      $editlink = ' | <span class="edit-link"><a href="' . $editlink . '">Edit</a></span>';
    }

    $betaflag = ks_beta_flag($post->ID, 'Banner');

    $tentry = '<li id="post-' . $post->ID . '" class="keynav ' . sandbox_post_class(false) . ' ' . $itemclass . '">';
      $tentry .= '<h2 class="entry-title">';
        $tentry .= '<a class="title-link" href="' . get_permalink() . '" rel="bookmark" title="' .  sprintf( __('Permalink to %s', 'sandbox'), the_title_attribute('echo=0') ) . '">';
          $tentry .= $post->post_title;
        $tentry .= '</a>';
      $tentry .= '</h2>';
      $tentry .= '<span class="entry-meta">';
        $tentry .= $betaflag;
        if ( $cats_meow = sandbox_cats_meow(', ') ) : // Returns categories other than the one queried
          $tentry .= '<span class="cat-links">' . sprintf( __( '%s', 'sandbox' ), $cats_meow ) . '</span>';
        endif;
        $tentry .= $editlink;
      $tentry .= '</span>';

      $nosig = preg_replace( "/<signature>.*?<\/signature>/s", "", get_the_content() );
      $match = array();
      preg_match( "/<desc>(.*?)<\/desc>/", $nosig, $match );

      $tentry .= '<p class="desc">' . $match[1] . '</p>';

    $tentry .= '</li>';

    if ( $isplugin ):
      $pluginlist[] = $tentry;
    else:
      $corelist[] = $tentry;
    endif;
  endforeach;

  // echo entry lists
?>

  <ul id="method-list" class="method-list">
    <?php echo implode("\n", $corelist); ?>
  </ul>

  <?php if ( count($pluginlist) && ks_can_show_plugins() ): ?>
    <h2 class="plugin-list-hdr">Plugins</h2>
    <ul id="plugin-list" class="method-list">
      <?php echo implode("\n", $pluginlist); ?>
    </ul>
  <?php endif; ?>
<?php
endif;
?>
	       
    </div>
    <!-- /inner -->
    
    <?php if($sidebar): ?>
    	<?php get_sidebar(); ?>
    <?php endif; ?>
    
  </div>
  <!-- /body -->

<?php get_footer(); ?>
