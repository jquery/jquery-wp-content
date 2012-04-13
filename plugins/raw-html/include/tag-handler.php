<?php

/**********************************************
	Filter inline blocks of raw HTML
***********************************************/
global $wsh_raw_parts;
$wsh_raw_parts=array();

/**
 * Extract content surrounded by [raw] or other supported tags 
 * and replace it with placeholder text. 
 * 
 * @global $wsh_raw_parts Used to store the extracted content blocks.
 * 
 * @param string $text The input content to filter.
 * @param bool $keep_tags Store both the tagged content and the tags themselves. Defaults to false - storing only the content. 
 * @return string Filtered content.
 */
function wsh_extract_exclusions($text, $keep_tags = false){
	global $wsh_raw_parts, $wp_current_filter;
	//Note to self: The regexp version was much shorter, but it had problems with big posts.
	
	$tags = array(array('<!--start_raw-->', '<!--end_raw-->'), array('[raw]', '[/raw]'), array('<!--raw-->', '<!--/raw-->'));

	foreach ($tags as $tag_pair){
		list($start_tag, $end_tag) = $tag_pair;
		
		//Find the start tag
		$start = stripos($text, $start_tag, 0);
		while($start !== false){
			$content_start = $start + strlen($start_tag);
			
			//find the end tag
			$fin = stripos($text, $end_tag, $content_start);
			
			//break if there's no end tag
			if ($fin == false) break;
			
			//extract the content between the tags
			$content = substr($text, $content_start,$fin-$content_start);
			
			if ( (array_search('get_the_excerpt', $wp_current_filter) !== false) || (array_search('the_excerpt', $wp_current_filter) !== false) ){
				//Strip out the raw blocks when displaying an excerpt
				$replacement = '';
			} else {
				//Store the content and replace it with a marker
				if ( $keep_tags ){
					$wsh_raw_parts[]=$start_tag.$content.$end_tag;
				} else {
					$wsh_raw_parts[]=$content;
				}				
				$replacement = "!RAWBLOCK".(count($wsh_raw_parts)-1)."!";				
			}
			$text = substr_replace($text, $replacement, $start, 
				$fin+strlen($end_tag)-$start
			);
			
			//Have we reached the end of the string yet?
			if ($start + strlen($replacement) > strlen($text)) break;
			
			//Find the next start tag
			$start = stripos($text, $start_tag, $start + strlen($replacement));
		}
	}
	return $text;
}

/**
 * Replace the placeholders created by wsh_extract_exclusions() with the original content.
 * 
 * @global $wsh_raw_parts Used to check if there is anything to insert.
 * 
 * @param string $text The input content to filter.
 * @param callback $placholder_callback Optional. The callback that will be used to process each placeholder. 
 * @return string Filtered content.
 */
function wsh_insert_exclusions($text, $placeholder_callback = 'wsh_insertion_callback'){
	global $wsh_raw_parts;
	if(!isset($wsh_raw_parts)) return $text;
	return preg_replace_callback("/!RAWBLOCK(\d+?)!/", $placeholder_callback, $text);		
}

/**
 * Regex callback for wsh_insert_exclusions. Returns the extracted content 
 * corresponding to a matched placeholder.
 * 
 * @global $wsh_raw_parts
 * 
 * @param array $matches Regex matches.
 * @return string Replacement string for this match.
 */
function wsh_insertion_callback($matches){
	global $wsh_raw_parts;
	return $wsh_raw_parts[intval($matches[1])];
}

//Extract the tagged content before WP can get to it, then re-insert it later.
add_filter('the_content', 'wsh_extract_exclusions', 2);
add_filter('the_content', 'wsh_insert_exclusions', 1001);


/* 
 * WordPress can also mangle code when initializing the post/page editor.
 * To prevent this, we override the the_editor_content filter in almost 
 * the same way that we did the_content.
 */
  
function wsh_extract_exclusions_for_editor($text){
	return wsh_extract_exclusions($text, true);
}

function wsh_insert_exclusions_for_editor($text){
	return wsh_insert_exclusions($text, 'wsh_insertion_callback_for_editor');
}

function wsh_insertion_callback_for_editor($matches){
	global $wsh_raw_parts;
	return htmlspecialchars($wsh_raw_parts[intval($matches[1])], ENT_NOQUOTES);
}

add_filter('the_editor_content', 'wsh_extract_exclusions_for_editor', 2);
add_filter('the_editor_content', 'wsh_insert_exclusions_for_editor', 1001);

?>