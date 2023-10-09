<?php
/**
 * The template for displaying search forms
 */
if ( jq_search_get_provider() === 'typesense' ) :
	$typesenseKey = get_option( 'jquery_typesense_key' );
	$typesenseCollection = get_option( 'jquery_typesense_collection' );
?>
<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="searchform tsmb-form" data-origin="https://typesense.jquery.com" data-collection="<?php echo esc_attr( $typesenseCollection ); ?>" data-key="<?php echo esc_attr($typesenseKey ); ?>" data-foot="true" data-group="true">
	<input type="search" name="s" aria-label="Search <?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" value="<?php echo get_search_query(); ?>" placeholder="Search" autocomplete="off">
	<button type="submit" class="visuallyhidden"></button>
</form>
<?php

else :

?>
<form method="get" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<button type="submit" class="icon-search"><span class="visuallyhidden">search</span></button>
	<label>
		<span class="visuallyhidden">Search <?php bloginfo( 'name' ); ?></span>
		<input type="text" name="s" value="<?php echo get_search_query(); ?>"
			placeholder="Search">
	</label>
</form>
<?php

endif;
