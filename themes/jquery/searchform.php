<?php
/**
 * The template for displaying search forms
 */
?>
<form method="get" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<span class="icon-search"></span>
	<label>
		<span>Search <?php bloginfo( 'name' ); ?></span>
		<input type="text" name="s" value="<?php echo get_search_query(); ?>"
			placeholder="Search <?php bloginfo( 'name' ); ?>">
	</label>
</form>
