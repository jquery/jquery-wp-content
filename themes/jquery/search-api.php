<?php
/**
 * The template for displaying Search Results pages.
 */
?>

    <!-- body -->
    <?php global $sidebar; ?>
    <div id="body" class="clearfix <?php echo $sidebar; ?>">
      <div class="inner" role="main">
      <?php
         $searchquery = get_search_query();
         $searchquery = preg_replace('/\$/', 'jQuery', $searchquery);
         $featuredlist = array();
         $entrylist = array();

      ?>
      <?php if ( have_posts() ) : ?>

        <?php // twentyeleven_content_nav( 'nav-above' ); ?>

        <header class="page-header">
          <h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'twentyeleven' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
        </header>

        <?php
        while ( have_posts() ) :
          the_post();

          $tentry = '<article id="post-' . $post->ID . '" class="' . implode( ' ', get_post_class() ) . '">';
            $tentry .= '<header class="entry-header">';
              $tentry .= '<h1 class="entry-title">';
              $tentry .= '<a href="' . get_permalink() . '" rel="bookmark">';
                $tentry .= $post->post_title;
                $tentry .= '</a>';
              $tentry .= '</h1>';
            $tentry .= '</header>';
            $tentry .= '<div class="entry-summary">';
              $tentry .= $post->post_excerpt;
            $tentry .= '</div>';
          $tentry .= '</article>';

          if ( preg_match('@' . $searchquery . '@i', $post->post_name) ) :
            $featuredlist[$post->post_name] = $tentry;
          else :
            $entrylist[$post->post_name] = $tentry;
          endif;

        endwhile;

        echo implode("\n", $featuredlist);
        ksort($entrylist);
        echo implode("\n", $entrylist);

        ?>

      <?php else : ?>

        <article id="post-0" class="post no-results not-found">
          <header class="entry-header">
            <h1 class="entry-title"><?php _e( 'Nothing Found', 'twentyeleven' ); ?></h1>
          </header><!-- .entry-header -->

          <div class="entry-content">
            <p><?php _e( 'Apologies, but nothing matched your search criteria.', 'twentyeleven' ); ?></p>
          </div><!-- .entry-content -->
        </article><!-- #post-0 -->

      <?php endif; ?>

      </div><!-- .inner -->

      <?php if($sidebar): ?>
        <?php get_sidebar( 'api' ); ?>
      <?php endif; ?>

    </div><!-- #body -->

