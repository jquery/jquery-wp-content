<?php
/**
 * The template for displaying search forms in Twenty Eleven
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
?>
      <form method="get" id="searchform" class="search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
        <input type="text" id="search" name="s">
        <label for="search" class="text">Search <?php bloginfo( 'name' ); ?></label>
        <a href="#" class="icon icon-search" title="Submit Search">Submit Search</a>
      </form>
