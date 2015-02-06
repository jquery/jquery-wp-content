<?php
/*
Template Name: CLA Verification Results
*/

$claData = getData();
validateData( $claData );

get_header();

?>
<div class="content-right twelve columns">
	<div id="content">
		<h1 class="entry-title"><?php the_title(); ?></h1>
		<hr>

		<?php echo getProcessedPost( $claData ); ?>
	</div>
	<?php get_sidebar(); ?>
</div>
<?php

get_footer();




function getData() {
	$repo = empty( $_GET[ 'repo' ] ) ? null : $_GET[ 'repo' ];
	$sha = empty( $_GET[ 'sha' ] ) ? null : $_GET[ 'sha' ];

	if ( is_null( $repo ) || is_null( $sha ) ) {
		return null;
	}

	$path = "$repo/" . substr( $sha, 0, 2 ) . "/$sha.json";
	$data = @file_get_contents( JQUERY_CLA_SERVER_URL . "/$path" );
	$data = json_decode( $data );
	$data->repo = $repo;
	return $data;
}

function validateData( $data ) {
	if ( is_null( $data ) ) {
		global $wp_query;
		$wp_query->set_404();
		status_header(404);
		get_template_part( 404 );
		exit();
	}
}

function getProcessedPost( $data ) {
	the_post();
	$content = get_the_content();

	if ( count( $data->neglectedAuthors ) ) {
		$content = preg_replace( "/<!-- ifsuccess -->.*<!-- endifsuccess -->/s", '', $content );
		$content = preg_replace( "/<!-- neglected-authors -->/", neglectedAuthors( $data ), $content );
	} else {
		$content = preg_replace( "/<!-- iferror -->.*<!-- endiferror -->/s", '', $content );
	}

	$content = preg_replace( "/<!-- commit-log -->/", commitLog( $data ), $content );

	return $content;
}

function neglectedAuthors( $data ) {
	$html = "<ul>\n";
	foreach ( $data->neglectedAuthors as $author ) {
		$html .= "<li>" . htmlspecialchars( "$author->name <$author->email>" ) . "</li>\n";
	}
	$html .= "</ul>\n";
	return $html;
}

function commitLog( $data ) {
	$commitPrefix = "http://github.com/jquery/$data->repo/commit/";

	$html = "<dl>\n";
	foreach ( $data->commits as $commit ) {
		$html .= "<dt><a href='$commitPrefix$commit->hash'>$commit->hash</a></dt>\n";
		$html .= "<dd>" . htmlspecialchars( "$commit->name <$commit->email>" ) . "</dd\n";
	}
	$html .= "</dl>\n";
	return $html;
}
