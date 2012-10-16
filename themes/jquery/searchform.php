<?php
/**
 * The template for displaying search forms
 */
?>
<form method="get" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<i class="icon-search"></i>
	<input type="text" name="s" onclick="this.value='Search jQuery'?'':this.value;" onfocus="this.select()" onblur="this.value=!this.value?'Search jQuery':this.value;" value="Search jQuery" />
</form>
