<?php
/*
 * Template Name: Blank Page
 */
the_post();
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title><?php the_title(); ?></title>

	<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/i/favicon.ico">

	<meta name="author" content="jQuery Foundation - jquery.org">
	<?php
		$meta = get_post_custom_values( 'meta' );
		if ( $meta ) {
			foreach( unserialize( $meta[ 0 ] ) as $key => $value ) {
				echo '<meta name="' . $key . '" content="' . $value . '">' . "\n";
			}
		}
	?>

	<?php
		$stylesheets = get_post_custom_values( 'stylesheets' );
		if ( $stylesheets ) {
			foreach( unserialize( $stylesheets[ 0 ] ) as $value ) {
				echo '<link rel="stylesheet" href="' . $value . '">' . "\n";
			}
		}
	?>

	<?php
		$scripts = get_post_custom_values( 'scripts' );
		if ( $scripts ) {
			foreach( unserialize( $scripts[ 0 ] ) as $value ) {
				echo '<script src="' . $value . '"></script>' . "\n";
			}
		}
	?>
</head>
<body>

<?php the_content(); ?>

</body>
</html>
