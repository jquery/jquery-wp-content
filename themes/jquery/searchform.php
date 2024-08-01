<?php
/**
 * The template for displaying search forms
 */
?>
<typesense-minibar<?php
	if ( jq_search_get_provider() === 'typesense' ) :
		$typesenseKey = get_option( 'jquery_typesense_key' );
		$typesenseCollection = get_option( 'jquery_typesense_collection' );
?>
	data-origin="https://typesense.jquery.com"
	data-collection="<?php echo esc_attr( $typesenseCollection ); ?>"
	data-key="<?php echo esc_attr($typesenseKey ); ?>"
	data-foot="true" data-group="true"
<?php
	endif;
?>>
	<form role="search" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get">
	<input type="search" name="s" aria-label="Search <?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" value="<?php echo get_search_query(); ?>" placeholder="Search" autocomplete="off">
	<button type="submit" class="visuallyhidden"></button>
</form>
</typesense-minibar>
