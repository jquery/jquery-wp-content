<?php
	echo "################################# NOTICE ##################################\n";
	echo "# This script will clone and setup > 20 sites and may take over an hour.  #\n";
	echo "#     If a site already exists any local changes will be overwritten.     #\n";
	echo "###########################################################################\n";
	echo "Are you sure you want to continue? (yes/no) : ";

	$stdin = fopen ( "php://stdin","r" );
	$line = fgets( $stdin );

	if( trim( $line ) != "yes" ){
		echo "ABORTING!\n";
		exit;
	}
	require( "sites.php" );

	$username = readline("Username: ");
    readline_add_history($username);
    $pass = readline("Password: ");
    readline_add_history($pass);
    $prefix = ( readline("Local Prefix (Default vagrant) : ") === " " ) ? $prefix : "vagrant";
    readline_add_history($prefix);
    $location = ( readline("Location : (Default ../) : ") === " " ) ? $prefix : "../";
    readline_add_history($location);

    $prefix = ( $prefix === "" ) ? $prefix : "vagrant";
	foreach( jquery_sites() as $name=>$info ) {
		$cloneargs = "";
		$folder = $name;
		$reponame = $name;
		$branch = "master";
		$json = '{
	"url": "'.$prefix.'.'.$name.'",
	"username": "'.$username.'",
	"password": "'.$pass.'"
}';
		if( preg_match( "`/`", $name ) ) {
			$parts = explode( "/", $name );
			$branch = preg_replace( "`\.`", "-", $parts[ 1 ] );
			$cloneargs = " ".$parts[ 0 ]."-".$parts[ 1 ]." -b $branch";
			$folder = $parts[ 0 ]."-".$parts[ 1 ];
			$reponame = $parts[ 0 ];
		}
		if( !file_exists( $location.$folder ) ){
			system( "cd $location; git clone git@github.com:jquery/$reponame.git $cloneargs;" );
		}
		if( file_exists( $location.$folder ) ) {
			system( "cd $location"."$folder; git checkout $branch; git reset --hard origin/$branch; git pull origin $branch; rm -rf node_modules; npm install;" );
			$handle = fopen( "$location"."$folder/config.json", "w" );
			fwrite( $handle, $json );
			fclose( $handle );
			system( "cd $location"."$folder/; grunt deploy" );
		}
	}
