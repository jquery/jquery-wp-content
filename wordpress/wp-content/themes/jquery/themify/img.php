<?php
/*
	-------------------------------------------------------------------------------------
	Credit
	-------------------------------------------------------------------------------------
	TimThumb script created by Tim McDaniels and Darren Hoyt with tweaks by Ben Gillbanks
	http://code.google.com/p/timthumb/
	
	MIT License: http://www.opensource.org/licenses/mit-license.php
	
	-------------------------------------------------------------------------------------
	Notes
	-------------------------------------------------------------------------------------
	- This version of TimThumb has been modified by Darcy Clarke of Themify.me
	- This version of TimThumb is not actively being developed on or released
	- This version of TimThumb *should* (does not currently) include request verification 
	
	-------------------------------------------------------------------------------------
	Modifications
	-------------------------------------------------------------------------------------
	- Now supports remote image generation from any website (aslong as the image is readable)
	- You can remove the "temp" folder by un-commenting line 432 "delete_directory(DIRECTORY_TEMP);"
	- Cleaned up markup and got rid of unnecessary comments / whitespace
	
	-------------------------------------------------------------------------------------
	Paramters
	-------------------------------------------------------------------------------------
	w: width
	h: height
	zc: zoom crop (0 or 1)
	q: quality (default is 100 and max is 100)

	$sizeLimits = array("100x100","150x150");
*/

	error_reporting(E_ERROR);
	
	if(!function_exists('imagecreatetruecolor')) {
		displayError('GD Library Error: imagecreatetruecolor does not exist - please contact your webhost and ask them to install the GD library');
	}
	
	define ('CACHE_SIZE', 250);					// number of files to store before clearing cache
	define ('CACHE_CLEAR', 5);					// maximum number of files to delete on each cache clear
	define ('VERSION', '1.14');					// version number (to force a cache refresh)
	define ('DIRECTORY_CACHE', '../cache');		// cache directory
	define ('DIRECTORY_TEMP', '../cache/temp');		// temp directory
	
	if(!is_dir(DIRECTORY_TEMP)){
		mkdir(DIRECTORY_TEMP, 0755, true);
	}
	
	if(!is_dir(DIRECTORY_CACHE)){
		mkdir(DIRECTORY_CACHE, 0755, true);	
	}
	
	if (function_exists('imagefilter') && defined('IMG_FILTER_NEGATE')) {
		$imageFilters = array(
			"1" => array(IMG_FILTER_NEGATE, 0),
			"2" => array(IMG_FILTER_GRAYSCALE, 0),
			"3" => array(IMG_FILTER_BRIGHTNESS, 1),
			"4" => array(IMG_FILTER_CONTRAST, 1),
			"5" => array(IMG_FILTER_COLORIZE, 4),
			"6" => array(IMG_FILTER_EDGEDETECT, 0),
			"7" => array(IMG_FILTER_EMBOSS, 0),
			"8" => array(IMG_FILTER_GAUSSIAN_BLUR, 0),
			"9" => array(IMG_FILTER_SELECTIVE_BLUR, 0),
			"10" => array(IMG_FILTER_MEAN_REMOVAL, 0),
			"11" => array(IMG_FILTER_SMOOTH, 0),
		);
	}
	
	$src = get_request("src", "");
	if($src == '' || strlen($src) <= 3) {
		displayError ('no image specified');
	}
	
	$src			= cleanSource($src);
	$lastModified 	= filemtime($src);
	$new_width      = preg_replace("/[^0-9]+/", '', get_request('w', 0));
	$new_height     = preg_replace("/[^0-9]+/", '', get_request('h', 0));
	$zoom_crop      = preg_replace("/[^0-9]+/", '', get_request('zc', 1));
	$quality        = preg_replace("/[^0-9]+/", '', get_request('q', 90));
	$filters        = get_request('f', '');
	$sharpen        = get_request('s', 0);
	
	if ($new_width == 0 && $new_height == 0) {
		list($new_width, $new_height) = getimagesize($src);
	}
	
	$mime_type = mime_type($src);
	check_cache ($mime_type);
	cleanCache();
	ini_set('memory_limit', '50M');
	
	if(!valid_src_mime_type($mime_type)) {
		displayError('Invalid src mime type: ' . $mime_type);
	}
	
	if(strlen($src) && fopen($src, "r")) {
	
		$image = open_image($mime_type, $src);
		if($image === false) {
			displayError('Unable to open image : ' . $src);
		}
	
		$width = imagesx($image);
		$height = imagesy($image);
		
		if( $new_width && !$new_height ) {
			$new_height = $height * ( $new_width / $width );
		} elseif($new_height && !$new_width) {
			$new_width = $width * ( $new_height / $height );
		} elseif(!$new_width && !$new_height) {
			$new_width = $width;
			$new_height = $height;
		}
		
		$canvas = imagecreatetruecolor( $new_width, $new_height );
		imagealphablending($canvas, false);
		$color = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
		imagefill($canvas, 0, 0, $color);
		imagesavealpha($canvas, true);
	
		if( $zoom_crop ) {
			$src_x = $src_y = 0;
			$src_w = $width;
			$src_h = $height;
			$cmp_x = $width  / $new_width;
			$cmp_y = $height / $new_height;
	
			if ( $cmp_x > $cmp_y ) {
				$src_w = round( ( $width / $cmp_x * $cmp_y ) );
				$src_x = round( ( $width - ( $width / $cmp_x * $cmp_y ) ) / 2 );
			} elseif ( $cmp_y > $cmp_x ) {
				$src_h = round( ( $height / $cmp_y * $cmp_x ) );
				$src_y = round( ( $height - ( $height / $cmp_y * $cmp_x ) ) / 2 );
			}
			
			imagecopyresampled( $canvas, $image, 0, 0, $src_x, $src_y, $new_width, $new_height, $src_w, $src_h );
			
		} else {
			imagecopyresampled( $canvas, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
		}
		
		if($filters != '' && function_exists('imagefilter') && defined('IMG_FILTER_NEGATE')) {
			$filterList = explode("|", $filters);
			foreach($filterList as $fl) {
				$filterSettings = explode(",", $fl);
				if(isset($imageFilters[$filterSettings[0]])) {
					for($i = 0; $i < 4; $i ++) {
						if(!isset($filterSettings[$i])) {
							$filterSettings[$i] = null;
						}
					}
					switch($imageFilters[$filterSettings[0]][1]) {
						case 1:
							imagefilter($canvas, $imageFilters[$filterSettings[0]][0], $filterSettings[1]);
							break;
						case 2:
							imagefilter($canvas, $imageFilters[$filterSettings[0]][0], $filterSettings[1], $filterSettings[2]);
							break;
						case 3:
							imagefilter($canvas, $imageFilters[$filterSettings[0]][0], $filterSettings[1], $filterSettings[2], $filterSettings[3]);
							break;
						default:
							imagefilter($canvas, $imageFilters[$filterSettings[0]][0]);
							break;
					}
				}
			}
		}
		
		if ($sharpen > 0 && function_exists('imageconvolution')) {
			$sharpenMatrix = array(
				array(-1,-1,-1),
				array(-1,16,-1),
				array(-1,-1,-1),
			);
			$divisor = 8;
			$offset = 0;
			imageconvolution($canvas, $sharpenMatrix, $divisor, $offset);
		}
		
		show_image($mime_type, $canvas);
		imagedestroy($canvas);
		
	} else {
		
		if (strlen($src)) {
			displayError ('image ' . $src . ' not found');
		} else {
			displayError ('no source specified');
		}
	}
	
	///////////////////////////////////////////
	// Show Image
	///////////////////////////////////////////
	function show_image($mime_type, $image_resized) {
		global $quality;
		$is_writable = 0;
		$cache_file_name = DIRECTORY_CACHE . '/' . get_cache_file();
		if (touch($cache_file_name)) {
			chmod ($cache_file_name, 0666);
			$is_writable = 1;
		} else {
			$cache_file_name = NULL;
			header ('Content-type: ' . $mime_type);
		}
		switch ($mime_type) {
			case 'image/jpeg':
				imagejpeg($image_resized, $cache_file_name, $quality);
				break;
			default :
				$quality = floor ($quality * 0.09);
				imagepng($image_resized, $cache_file_name, $quality);
		}
		if ($is_writable) {
			show_cache_file ($mime_type);
		}
		imagedestroy ($image_resized);
		displayError ('error showing image');
	}
	
	///////////////////////////////////////////
	// Get Request
	///////////////////////////////////////////
	function get_request( $property, $default = 0 ) {
		if( isset($_REQUEST[$property]) ) {
			return $_REQUEST[$property];
		} else {
			return $default;
		}
	}
	
	///////////////////////////////////////////
	// Open Image
	///////////////////////////////////////////
	function open_image($mime_type, $src) {
		$mime_type = strtolower($mime_type);
		if (stristr ($mime_type, 'gif')) {
			$image = imagecreatefromgif($src);
		} elseif (stristr($mime_type, 'jpeg')) {
			@ini_set ('gd.jpeg_ignore_warning', 1);
			$image = imagecreatefromjpeg($src);
		} elseif (stristr ($mime_type, 'png')) {
			$image = imagecreatefrompng($src);
		}
		return $image;
	}
	
	///////////////////////////////////////////
	// Clean Image Source
	///////////////////////////////////////////
	function cleanSource($src) {
		$host = str_replace('www.', '', $_SERVER['HTTP_HOST']); // get domain
		$regex = "/^((ht|f)tp(s|):\/\/)(www\.|)" . $host . "/i"; // remove http://www.
		$src = preg_replace ($regex, '', $src); // remove domain if it's in url
		$src = strip_tags ($src); // remove any kind of html or js
		$src = checkExternal ($src); 
		if (strpos($src, '/') === 0) {
			$src = substr ($src, -(strlen($src) - 1));
		}
		$src = preg_replace("/\.\.+\//", "", $src);
		$src = get_document_root($src) . '/' . $src;
		
		return $src;
	}
	
	///////////////////////////////////////////
	// Clean Cache - Params at top of file
	///////////////////////////////////////////
	function cleanCache() {
		$files = glob("cache/*", GLOB_BRACE);
		if (count($files) > 0) {
			$yesterday = time() - (24 * 60 * 60);
			usort($files, 'filemtime_compare');
			$i = 0;
			if (count($files) > CACHE_SIZE) {
				foreach ($files as $file) {
					$i ++;
					if ($i >= CACHE_CLEAR) {
						return;
					}
					if (@filemtime($file) > $yesterday) {
						return;
					}
					if (file_exists($file)) {
						unlink($file);
					}
				}
			}
		}
	}
	
	///////////////////////////////////////////
	// Compare File Time
	///////////////////////////////////////////
	function filemtime_compare($a, $b) {
		return filemtime($a) - filemtime($b);
	}
	
	///////////////////////////////////////////
	// Get File MIME type
	///////////////////////////////////////////
	function mime_type($file) {
		if (stristr(PHP_OS, 'WIN')) { 
			$os = 'WIN';
		} else { 
			$os = PHP_OS;
		}
		$mime_type = '';
		if (function_exists('mime_content_type') && $os != 'WIN') {
			$mime_type = mime_content_type($file);
		}
		if (!valid_src_mime_type($mime_type)) {
			if (function_exists('finfo_open')) {
				$finfo = @finfo_open(FILEINFO_MIME);
				if ($finfo != '') {
					$mime_type = finfo_file($finfo, $file);
					finfo_close($finfo);
				}
			}
		}
		if (!valid_src_mime_type($mime_type) && $os != "WIN") {
			if (preg_match("/FreeBSD|FREEBSD|LINUX/", $os)) {
				$mime_type = trim(@shell_exec('file -bi ' . escapeshellarg($file)));
			}
		}
		if (!valid_src_mime_type($mime_type)) {
			$mime_type = 'image/png';
			$fileDetails = pathinfo($file);
			$ext = strtolower($fileDetails["extension"]);
			$types = array(
				 'jpg'  => 'image/jpeg',
				 'jpeg' => 'image/jpeg',
				 'png'  => 'image/png',
				 'gif'  => 'image/gif'
			 );
			if (strlen($ext) && strlen($types[$ext])) {
				$mime_type = $types[$ext];
			}
		}
		return $mime_type;
	}
	
	///////////////////////////////////////////
	// Valid image source MIME type
	///////////////////////////////////////////
	function valid_src_mime_type($mime_type) {
		if (preg_match("/jpg|jpeg|gif|png/i", $mime_type)) {
			return true;
		}
		return false;
	}
	
	///////////////////////////////////////////
	// Check Cache
	///////////////////////////////////////////
	function check_cache ($mime_type) {
		if (!file_exists(DIRECTORY_CACHE)) {
			mkdir(DIRECTORY_CACHE);
			chmod(DIRECTORY_CACHE, 0777);
		}
		show_cache_file ($mime_type);
	}
	
	///////////////////////////////////////////
	// Show Cached File
	///////////////////////////////////////////
	function show_cache_file ($mime_type) {
		$cache_file = DIRECTORY_CACHE . '/' . get_cache_file();
		if (file_exists($cache_file)) {
			$gmdate_mod = gmdate("D, d M Y H:i:s", filemtime($cache_file));
			if(! strstr($gmdate_mod, "GMT")) {
				$gmdate_mod .= " GMT";
			}
			if (isset($_SERVER["HTTP_IF_MODIFIED_SINCE"])) {
				$if_modified_since = preg_replace ("/;.*$/", "", $_SERVER["HTTP_IF_MODIFIED_SINCE"]);
				if ($if_modified_since == $gmdate_mod) {
					header("HTTP/1.1 304 Not Modified");
					die();
				}
			}
			clearstatcache();
			$fileSize = filesize ($cache_file);
			header ('Content-Type: ' . $mime_type);
			header ('Accept-Ranges: bytes');
			header ('Last-Modified: ' . $gmdate_mod);
			header ('Content-Length: ' . $fileSize);
			header ('Cache-Control: max-age=9999, must-revalidate');
			header ('Expires: ' . $gmdate_mod);
			readfile ($cache_file);
			die();
		}
	}
	
	///////////////////////////////////////////
	// Get Cache File
	///////////////////////////////////////////
	function get_cache_file() {
		global $lastModified;
		static $cache_file;
		if (!$cache_file) {
			$cachename = $_SERVER['QUERY_STRING'] . VERSION . $lastModified;
			$cache_file = md5($cachename) . '.png';
		}
		return $cache_file;
	}
	
	///////////////////////////////////////////
	// Check if url is valid extension
	///////////////////////////////////////////
	function valid_extension ($ext) {
		if (preg_match("/jpg|jpeg|png|gif/i", $ext)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	///////////////////////////////////////////
	// Check remote file
	///////////////////////////////////////////
	function checkExternal ($src) {
		if (preg_match('/http:\/\//', $src) == true) {
			$url_info = parse_url ($src);
			$fileDetails = pathinfo($src);
			$ext = strtolower($fileDetails['extension']);
			$filename = md5($src);
			$local_filepath = DIRECTORY_TEMP . '/' . $filename . '.' . $ext;
			
			if (!file_exists($local_filepath)) {
				if (function_exists('curl_init')) {
				
					$fh = fopen($local_filepath, 'w');
					$ch = curl_init($src);
					curl_setopt($ch, CURLOPT_URL, $src);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
					curl_setopt($ch, CURLOPT_HEADER, 0);
					curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041107 Firefox/1.0');
					curl_setopt($ch, CURLOPT_FILE, $fh);
					
					if (curl_exec($ch) === FALSE) {
						if (fopen($local_filepath, "r")) {
							unlink($local_filepath);
						}
						displayError('error reading file ' . $src . ' from remote host: ' . curl_error($ch));
					}
					curl_close($ch);
					fclose($fh);
					// delete_directory(DIRECTORY_TEMP);
				
				} else {
					$img = fread($src, remote_filesize($src));
					if (file_put_contents($local_filepath, $img) == FALSE) {
						displayError('error writing temporary file');
					}
					
				}
				if (!file_exists($local_filepath)) {
					displayError('local file for ' . $src . ' can not be created');
				}
			}
			
			$src = $local_filepath;
		}
		return $src;
	}
	
	///////////////////////////////////////////
	// Get Document Root
	///////////////////////////////////////////
	function get_document_root ($src) {
		if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $src)) {
			return $_SERVER['DOCUMENT_ROOT'];
		}
		$parts = array_diff(explode('/', $_SERVER['SCRIPT_FILENAME']), explode('/', $_SERVER['DOCUMENT_ROOT']));
		$path = $_SERVER['DOCUMENT_ROOT'];
		foreach ($parts as $part) {
			$path .= '/' . $part;
			if (file_exists($path . '/' . $src)) {
				return $path;
			}
		}    
		$paths = array(
			".",
			"..",
			"../..",
			"../../..",
			"../../../..",
			"../../../../.."
		);
		foreach ($paths as $path) {
			if(file_exists($path . '/' . $src)) {
				return $path;
			}
		}
		if (!isset($_SERVER['DOCUMENT_ROOT'])) {
			$path = str_replace("/", "\\", $_SERVER['ORIG_PATH_INFO']);
			$path = str_replace($path, "", $_SERVER['SCRIPT_FILENAME']);
			if (file_exists($path . '/' . $src)) {
				return $path;
			}
		}    
		displayError('file not found ' . $src);
	}
	
	///////////////////////////////////////////
	// Default Error Message
	///////////////////////////////////////////
	function displayError ($errorString = '') {
		header('HTTP/1.1 400 Bad Request');
		echo '<pre>' . $errorString . '<br />TimThumb version : ' . VERSION . '</pre>';
		die();
	}
	
	///////////////////////////////////////////
	// Get Remote File Size
	///////////////////////////////////////////
	function remote_filesize($url, $user = "", $pw = "") { 
		$ch = curl_init($url); 
		curl_setopt($ch, CURLOPTHEADER, 1); 
		curl_setopt($ch, CURLOPTNOBODY, 1); 
		curl_setopt($ch, CURLOPTRETURNTRANSFER, 1);
		if (!empty($user) && !empty($pw)) { 
			$headers = array('Authorization: Basic ' . base64encode("$user:$pw")); 
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
		}
		$head = curl_exec($ch); 
		curl_close($ch);
		$regex = '/Content-Length:\s([0-9].+?)\s/'; 
		$count = preg_match($regex, $head, $matches);
		return isset($matches[1]) ? $matches[1] : false; 
	}
	
	///////////////////////////////////////////
	// Completely Remove Directory
	///////////////////////////////////////////
	function delete_directory($dirname) {
	   if (is_dir($dirname))
		  $dir_handle = opendir($dirname);
	   if (!$dir_handle)
		  return false;
	   while($file = readdir($dir_handle)) {
		  if ($file != "." && $file != "..") {
			 if (!is_dir($dirname."/".$file))
				unlink($dirname."/".$file);
			 else
				delete_directory($dirname.'/'.$file);    
		  }
	   }
	   closedir($dir_handle);
	   rmdir($dirname);
	   return true;
	}
	
?>