<?php
/**
 * The template for displaying search forms
 */
?>
<form method="get" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<button type="submit" class="icon-search"><span class="visuallyhidden">search</span></button>
	<label>
		<span class="visuallyhidden">Search <?php bloginfo( 'name' ); ?></span>
		<input type="text" name="s" value="<?php echo get_search_query(); ?>"
			placeholder="Search <?php bloginfo( 'name' ); ?>" autofocus>
	</label>
</form>
