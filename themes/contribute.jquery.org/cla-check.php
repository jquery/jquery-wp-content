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
	// 'jquery' is the owner for legacy URLs that were created
	// before multi-repo support existed
	$owner = empty( $_GET[ 'owner' ] ) ? 'jquery' : $_GET[ 'owner' ];
	$repo = empty( $_GET[ 'repo' ] ) ? null : $_GET[ 'repo' ];
	$sha = empty( $_GET[ 'sha' ] ) ? null : $_GET[ 'sha' ];

	if ( is_null( $repo ) || is_null( $sha ) ) {
		return null;
	}

	$path = "$owner/$repo/" . substr( $sha, 0, 2 ) . "/$sha.json";

	if ( strpos( $path, '..' ) !== FALSE ) {
		return null;
	}

	$data = @file_get_contents( JQUERY_CLA_SERVER_URL . "/$path" );

	if ( !$data ) {
		return null;
	}

	$data = json_decode( $data );
	$data = normalizeData( $data );
	$data->owner = $owner;
	$data->repo = $repo;
	return $data;
}

// The web hook used to only log the audit result, not the full data.
// So we normalize old data to the new format
function normalizeData( $data ) {

	// Since we didn't store error info before, errors got logged as undefined,
	// which come across as null in PHP
	if ( is_null( $data ) ) {
		return (object) array(
			'error' => 'Unknown error'
		);
	}

	// If we're missing the fully structured data, just move the audit result
	// to the proper location
	if ( !empty( $data->commits ) ) {
		return (object) array( 'data' => $data );
	}

	// We have proper data, just return it as is
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

	if ( !empty( $data->error ) ) {
		$content = preg_replace( '/<!-- ifsuccess -->.*<!-- endifsuccess -->/s', '', $content );
		$content = preg_replace( '/<!-- iffailure -->.*<!-- endiffailure -->/s', '', $content );
		$content = preg_replace(
			'/<!-- error -->/',
			'<pre>' . htmlspecialchars( $data->error ) . '</pre>',
			$content
		);

		return $content;
	}

	if ( count( $data->data->neglectedAuthors ) ) {
		$content = preg_replace( '/<!-- ifsuccess -->.*<!-- endifsuccess -->/s', '', $content );
		$content = preg_replace( '/<!-- neglected-authors -->/', neglectedAuthors( $data ), $content );
	} else {
		$content = preg_replace( '/<!-- iffailure -->.*<!-- endiffailure -->/s', '', $content );
	}

	$content = preg_replace( '/<!-- iferror -->.*<!-- endiferror -->/s', '', $content );
	$content = preg_replace( '/<!-- commit-log -->/', commitLog( $data ), $content );

	return $content;
}

function neglectedAuthors( $data ) {
	$html = "<ul>\n";
	foreach ( $data->data->neglectedAuthors as $author ) {
		$html .= "<li>" . htmlspecialchars( "$author->name <$author->email>" );
		if ( count( $author->errors ) ) {
			$html .= ": ";
			foreach( $author->errors as $error ) {
				$html .= htmlspecialchars( " $error" );
			}
		}
		$html .= "</li>\n";
	}
	$html .= "</ul>\n";
	return $html;
}

function commitLog( $data ) {
	$commitPrefix = "https://github.com/$data->owner/$data->repo/commit/";

	$html = "<dl>\n";
	foreach ( $data->data->commits as $commit ) {
		$html .= "<dt><a href='$commitPrefix$commit->hash'>$commit->hash</a></dt>\n";
		$html .= "<dd>" . htmlspecialchars( "$commit->name <$commit->email>" ) . "</dd\n";
	}
	$html .= "</dl>\n";
	return $html;
}
