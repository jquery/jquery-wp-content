<?php

/** EXCERPTS **/

function relevanssi_the_excerpt() {
    global $post;
    if (!post_password_required($post)) {
	    echo "<p>" . $post->post_excerpt . "</p>";
	}
	else {
		echo __('There is no excerpt because this is a protected post.');
	}
}

function relevanssi_do_excerpt($t_post, $query) {
	global $post;
	$old_global_post = NULL;
	if ($post != NULL) $old_global_post = $post;
	$post = $t_post;

	$remove_stopwords = true;
	$terms = relevanssi_tokenize($query, $remove_stopwords, -1);

	// These shortcodes cause problems with Relevanssi excerpts
    $problem_shortcodes = apply_filters('relevanssi_disable_shortcodes_excerpt',
        array('layerslider', 'responsive-flipbook', 'breadcrumb', 'maxmegamenu', 'robogallery')
    );
    foreach ($problem_shortcodes as $shortcode) {
        remove_shortcode($shortcode);
    }

	$content = apply_filters('relevanssi_pre_excerpt_content', $post->post_content, $post, $query);
	$content = apply_filters('the_content', $content);
	$content = apply_filters('relevanssi_excerpt_content', $content, $post, $query);

	$content = relevanssi_strip_invisibles($content); // removes <script>, <embed> &c with content
	$content = preg_replace('/(<\/[^>]+?>)(<[^>\/][^>]*?>)/', '$1 $2', $content); // add spaces between tags to avoid getting words stuck together
	$content = strip_tags($content, get_option('relevanssi_excerpt_allowable_tags', '')); // this removes the tags, but leaves the content

	$content = preg_replace("/\n\r|\r\n|\n|\r/", " ", $content);
//	$content = trim(preg_replace("/\s\s+/", " ", $content));

	$query = relevanssi_add_synonyms($query);

	$excerpt_data = relevanssi_create_excerpt($content, $terms, $query);

	if (get_option("relevanssi_index_comments") != 'none') {
		$comment_content = relevanssi_get_comments($post->ID);
		$comment_content = preg_replace('/(<\/[^>]+?>)(<[^>\/][^>]*?>)/', '$1 $2', $comment_content); // add spaces between tags to avoid getting words stuck together
		$comment_content = strip_tags($comment_content, get_option('relevanssi_excerpt_allowable_tags', '')); // this removes the tags, but leaves the content
		if (!empty($comment_content)) {
			$comment_excerpts = relevanssi_create_excerpt($comment_content, $terms, $query);
			if ($comment_excerpts[1] > $excerpt_data[1]) {
				$excerpt_data = $comment_excerpts;
			}
		}
	}

	if (get_option("relevanssi_index_excerpt") != 'off') {
		$excerpt_content = $post->post_excerpt;
		if (!empty($excerpt_content)) {
			$excerpt_excerpts = relevanssi_create_excerpt($excerpt_content, $terms, $query);
			if ($excerpt_excerpts[1] > $excerpt_data[1]) {
				$excerpt_data = $excerpt_excerpts;
			}
		}
	}

	$start = $excerpt_data[2];

	$excerpt = $excerpt_data[0];
	$excerpt = trim($excerpt);
	$excerpt = apply_filters('relevanssi_excerpt', $excerpt);

	if (empty($excerpt) && !empty($post->post_excerpt)) $excerpt = $post->post_excerpt;
	$excerpt == $post->post_content ? $whole_post_excerpted = true : $whole_post_excerpted = false;

	$ellipsis = apply_filters('relevanssi_ellipsis', '...');

	$highlight = get_option('relevanssi_highlight');
	if ("none" != $highlight) {
		if ( !is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
			$query = relevanssi_add_synonyms($query);
			$excerpt = relevanssi_highlight_terms($excerpt, $query);
		}
	}

	$excerpt = relevanssi_close_tags($excerpt);

	if (!$whole_post_excerpted) {
		if (!$start && !empty($excerpt)) {
			$excerpt = $ellipsis . $excerpt;
			// do not add three dots to the beginning of the post
		}

		if (!empty($excerpt))
			$excerpt = $excerpt . $ellipsis;
	}

	if (relevanssi_s2member_level($post->ID) == 1) $excerpt = $post->post_excerpt;

	if ($old_global_post != NULL) $post = $old_global_post;

	return $excerpt;
}

/**
 * Creates an excerpt from content.
 *
 * @return array - element 0 is the excerpt, element 1 the number of term hits, element 2 is
 * true, if the excerpt is from the start of the content.
 */
function relevanssi_create_excerpt($content, $terms, $query) {
	// If you need to modify these on the go, use 'pre_option_relevanssi_excerpt_length' filter.
	$excerpt_length = get_option("relevanssi_excerpt_length");
	$type = get_option("relevanssi_excerpt_type");

	$best_excerpt_term_hits = -1;
	$excerpt = "";

	$content = preg_replace('/\s+/u', ' ', $content);
	$content = " $content";

	$phrases = relevanssi_extract_phrases(stripslashes($query));

	$non_phrase_terms = array();
	foreach ($phrases as $phrase) {
		$phrase_terms = array_keys(relevanssi_tokenize($phrase, $remove_stopwords = false));
		foreach (array_keys($terms) as $term) {
			if (!in_array($term, $phrase_terms)) {
				$non_phrase_terms[] = $term;
			}
		}

		$terms = $non_phrase_terms;
		$terms[$phrase] = 1;
	}

	// longest search terms first, because those are generally more significant
	uksort($terms, 'relevanssi_strlen_sort');

	$start = false;
	if ("chars" == $type) {
		$prev_count = floor($excerpt_length / 2);
		list($excerpt, $best_excerpt_term_hits, $start) = relevanssi_extract_relevant(array_keys($terms), $content, $excerpt_length, $prev_count);
	}
	else {
		$words = explode(' ', $content);
		$i = 0;

		while ($i < count($words)) {
			if ($i + $excerpt_length > count($words)) {
				$i = count($words) - $excerpt_length;
				if ($i < 0) $i = 0;
			}

			$excerpt_slice = array_slice($words, $i, $excerpt_length);
			$excerpt_slice = implode(' ', $excerpt_slice);

			$excerpt_slice = " $excerpt_slice";
			$term_hits = 0;
			$count = relevanssi_count_matches(array_keys($terms), $excerpt_slice);

			if ($count > 0 && $count > $best_excerpt_term_hits) {
				$best_excerpt_term_hits = $count;
				$excerpt = $excerpt_slice;
			}

			$i += $excerpt_length;
		}

		if ("" == $excerpt) {
			$excerpt = explode(' ', $content, $excerpt_length);
			array_pop($excerpt);
			$excerpt = implode(' ', $excerpt);
			$start = true;
		}
	}

	return array($excerpt, $best_excerpt_term_hits, $start);
}

/** HIGHLIGHTING **/

function relevanssi_highlight_in_docs($content) {
	global $wp_query;
	if (is_singular() && in_the_loop()) {
		if (isset($_SERVER['HTTP_REFERER'])) {
			$referrer = preg_replace('@(http|https)://@', '', stripslashes(urldecode($_SERVER['HTTP_REFERER'])));
			$args     = explode('?', $referrer);
			$query    = array();

			if ( count( $args ) > 1 )
				parse_str( $args[1], $query );

			if (stripos($referrer, $_SERVER['SERVER_NAME']) !== false) {
				// Local search
				if (isset($query['s'])) {
					$q = relevanssi_add_synonyms($query['s']);
					$in_docs = true;
					$highlighted_content = relevanssi_highlight_terms($content, $q, $in_docs);
					if (!empty($highlighted_content)) $content = $highlighted_content;
					// Sometimes the content comes back empty; until I figure out why, this tries to be a solution.
				}
			}
			if (function_exists('relevanssi_nonlocal_highlighting')) {
				$content = relevanssi_nonlocal_highlighting($referrer, $content, $query);
			}
		}
	}

	return $content;
}

function relevanssi_highlight_terms($excerpt, $query, $in_docs = false) {
	$type = get_option("relevanssi_highlight");
	if ("none" == $type) {
		return $excerpt;
	}

	switch ($type) {
		case "mark":						// thanks to Jeff Byrnes
			$start_emp = "<mark>";
			$end_emp = "</mark>";
			break;
		case "strong":
			$start_emp = "<strong>";
			$end_emp = "</strong>";
			break;
		case "em":
			$start_emp = "<em>";
			$end_emp = "</em>";
			break;
		case "col":
			$col = get_option("relevanssi_txt_col");
			if (!$col) $col = "#ff0000";
			$start_emp = "<span style='color: $col'>";
			$end_emp = "</span>";
			break;
		case "bgcol":
			$col = get_option("relevanssi_bg_col");
			if (!$col) $col = "#ff0000";
			$start_emp = "<span style='background-color: $col'>";
			$end_emp = "</span>";
			break;
		case "css":
			$css = get_option("relevanssi_css");
			if (!$css) $css = "color: #ff0000";
			$start_emp = "<span style='$css'>";
			$end_emp = "</span>";
			break;
		case "class":
			$css = get_option("relevanssi_class");
			if (!$css) $css = "relevanssi-query-term";
			$start_emp = "<span class='$css'>";
			$end_emp = "</span>";
			break;
		default:
			return $excerpt;
	}

	$start_emp_token = "**{}[";
	$end_emp_token = "]}**";

	if ( function_exists('mb_internal_encoding') )
		mb_internal_encoding("UTF-8");

	do_action('relevanssi_highlight_tokenize');

    // Setting min_word_length to 2, in order to avoid 1-letter highlights.
	$min_word_length = 2;
	if (apply_filters('relevanssi_allow_one_letter_highlights', false)) $min_word_length = 1;

	$terms = array_keys(relevanssi_tokenize($query, $remove_stopwords = true, $min_word_length));

	if (is_array($query)) $query = implode(' ', $query); // just in case
	$phrases = relevanssi_extract_phrases(stripslashes($query));

	$non_phrase_terms = array();
	foreach ($phrases as $phrase) {
		$phrase_terms = array_keys(relevanssi_tokenize($phrase, $remove_stopwords = false));
		foreach ($terms as $term) {
			if (!in_array($term, $phrase_terms)) {
				$non_phrase_terms[] = $term;
			}
		}
		$terms = $non_phrase_terms;
		$terms[] = $phrase;
	}

	uksort($terms, 'relevanssi_strlen_sort');

	get_option('relevanssi_word_boundaries', 'on') == 'on' ? $word_boundaries = true : $word_boundaries = false;
	foreach ($terms as $term) {
//		$pr_term = relevanssi_replace_punctuation(preg_quote($term, '/'));
		$pr_term = preg_quote($term, '/');
		$pr_term = relevanssi_add_accent_variations($pr_term);

		$undecoded_excerpt = $excerpt;
		$excerpt = html_entity_decode($excerpt, ENT_QUOTES, 'UTF-8');

		if ($word_boundaries) {
//			get_option('relevanssi_fuzzy') != 'none' ? $regex = "/($pr_term)(?!(^&+)?(;))/iu" : $regex = "/(\b$pr_term|$pr_term\b)(?!(^&+)?(;))/iu";
//			get_option('relevanssi_fuzzy') != 'none' ? $regex = "/(\b$pr_term|$pr_term\b)(?!(^&+)?(;))/iu" : $regex = "/(\b$pr_term\b)(?!(^&+)?(;))/iu";
			get_option('relevanssi_fuzzy') != 'none' ? $regex = "/(\b$pr_term|$pr_term\b)/iu" : $regex = "/(\b$pr_term\b)/iu";

			$excerpt = preg_replace($regex, $start_emp_token . '\\1' . $end_emp_token, $excerpt);
			if (empty($excerpt)) $excerpt = preg_replace($regex, $start_emp_token . '\\1' . $end_emp_token, $undecoded_excerpt);
		}
		else {
//            $excerpt = preg_replace("/($pr_term)(?!(^&+)?(;))/iu", $start_emp_token . '\\1' . $end_emp_token, $excerpt);
			$excerpt = preg_replace("/($pr_term)/iu", $start_emp_token . '\\1' . $end_emp_token, $excerpt);
			if (empty($excerpt)) $excerpt = preg_replace("/($pr_term)/iu", $start_emp_token . '\\1' . $end_emp_token, $undecoded_excerpt);
		}

		$preg_start = preg_quote($start_emp_token);
		$preg_end = preg_quote($end_emp_token);

		if (preg_match_all('/<.*>/U', $excerpt, $matches) > 0) {
			// Remove highlights from inside HTML tags
			foreach ($matches as $match) {
				$new_match = str_replace($start_emp_token, '', $match);
				$new_match = str_replace($end_emp_token, '', $new_match);
				$excerpt = str_replace($match, $new_match, $excerpt);
			}
		}

        if (preg_match_all('/&.*;/U', $excerpt, $matches) > 0) {
			// Remove highlights from inside HTML entities
			foreach ($matches as $match) {
				$new_match = str_replace($start_emp_token, '', $match);
				$new_match = str_replace($end_emp_token, '', $new_match);
				$excerpt = str_replace($match, $new_match, $excerpt);
			}
		}

		if (preg_match_all('/<(style|script|object|embed)>.*<\/(style|script|object|embed)>/U', $excerpt, $matches) > 0) {
			// Remove highlights in style, object, embed and script tags
			foreach ($matches as $match) {
				$new_match = str_replace($start_emp_token, '', $match);
				$new_match = str_replace($end_emp_token, '', $new_match);
				$excerpt = str_replace($match, $new_match, $excerpt);
			}
		}
	}

	$excerpt = relevanssi_remove_nested_highlights($excerpt, $start_emp_token, $end_emp_token);
	$excerpt = relevanssi_fix_entities($excerpt, $in_docs);

	$excerpt = str_replace($start_emp_token, $start_emp, $excerpt);
	$excerpt = str_replace($end_emp_token, $end_emp, $excerpt);
	$excerpt = str_replace($end_emp . $start_emp, "", $excerpt);
	if (function_exists('mb_ereg_replace')) {
		$pattern = $end_emp . '\s*' . $start_emp;
		$excerpt = mb_ereg_replace($pattern, " ", $excerpt);
	}

	return $excerpt;
}


function relevanssi_replace_punctuation($a) {
    $a = preg_replace('/[[:punct:]]/u', '.', $a);
    return $a;
}

function relevanssi_fix_entities($excerpt, $in_docs) {
	if (!$in_docs) {
		// For excerpts, use htmlentities()
		$excerpt = htmlentities($excerpt, ENT_NOQUOTES, 'UTF-8'); // ENT_QUOTES or ENT_NOQUOTES?

		// Except for allowed tags, which are turned back into tags.
		$tags = get_option('relevanssi_excerpt_allowable_tags', '');
		$tags = trim(str_replace("<", " <", $tags));
        $tags = explode(" ", $tags);
		$closing_tags = relevanssi_generate_closing_tags($tags);

		$tags_entitied = htmlentities(implode(" ", $tags), ENT_NOQUOTES, 'UTF-8');  // ENT_QUOTES or ENT_NOQUOTES?
		$tags_entitied = explode(" ", $tags_entitied);

        $closing_tags_entitied = htmlentities(implode(" ", $closing_tags), ENT_NOQUOTES, 'UTF-8');  // ENT_QUOTES or ENT_NOQUOTES?
		$closing_tags_entitied = explode(" ", $closing_tags_entitied);

        $tags_entitied_regexped = array();
        $i = 0;
        foreach ($tags_entitied as $tag) {
            $tag = str_replace("&gt;", "(.*?)&gt;", $tag);
            $pattern = "~$tag~";
            $tags_entitied_regexped[] = $pattern;

            $matching_tag = $tags[$i];
            $matching_tag = str_replace(">", '\1>', $matching_tag);
            $tags[$i] = $matching_tag;
            $i++;
        }

        $closing_tags_entitied_regexped = array();
        foreach ($closing_tags_entitied as $tag) {
            $pattern = "~" . preg_quote($tag) . "~";
            $closing_tags_entitied_regexped[] = $pattern;
        }

        $tags = array_merge($tags, $closing_tags);
        $tags_entitied = array_merge($tags_entitied_regexped, $closing_tags_entitied_regexped);

		$excerpt = preg_replace($tags_entitied, $tags, $excerpt);

        // In case there are attributes. This is the easiest solution, as
        // using quotes and apostrophes un-entitied can't really break
        // anything.
        $excerpt = str_replace('&quot;', '"', $excerpt);
        $excerpt = str_replace('&#039;', "'", $excerpt);
	}
	else {
		// Running htmlentities() for whole posts tends to ruin things.
		// However, we want to run htmlentities() for anything inside
		// <pre> and <code> tags.
		$excerpt = relevanssi_entities_inside($excerpt, "code");
		$excerpt = relevanssi_entities_inside($excerpt, "pre");
	}
	return $excerpt;
}

function relevanssi_entities_inside($excerpt, $tag) {
	$hits = preg_match_all('/<' . $tag . '>(.*?)<\/' . $tag . '>/im', $excerpt, $matches);
	if ($hits > 0) {
		$replacements = array();
		foreach ($matches[1] as $match) {
			if (!empty($match))	$replacements[] = "<xxx" . $tag . ">" . htmlentities($match, ENT_QUOTES, 'UTF-8') . "</xxx" . $tag . ">";
		}
		if (!empty($replacements)) {
			for ($i = 0; $i < count($replacements); $i++) {
				$patterns[] = "/<" . $tag . ">(.*?)<\/" . $tag . ">/im";
			}
			$excerpt = preg_replace($patterns, $replacements, $excerpt, 1);
		}
		$excerpt = str_replace("xxx" . $tag, $tag, $excerpt);
	}
	return $excerpt;
}

function relevanssi_generate_closing_tags($tags) {
	$closing_tags = array();
	foreach ($tags as $tag) {
		$a = str_replace("<", "</", $tag);
		$b = str_replace(">", "/>", $tag);
		$closing_tags[] = $a;
		$closing_tags[] = $b;
	}
	return $closing_tags;
}

function relevanssi_remove_nested_highlights($s, $a, $b) {
	$offset = 0;
	$string = "";
	$bits = explode($a, $s);
	$new_bits = array($bits[0]);
	$in = false;
	for ($i = 1; $i < count($bits); $i++) {
		if ($bits[$i] == '') continue;

		if (!$in) {
			array_push($new_bits, $a);
			$in = true;
		}
		if (substr_count($bits[$i], $b) > 0) {
			$in = false;
		}
		if (substr_count($bits[$i], $b) > 1) {
			$more_bits = explode($b, $bits[$i]);
			$j = 0;
			$k = count($more_bits) - 2;
			$whole_bit = "";
			foreach ($more_bits as $bit) {
				$whole_bit .= $bit;
				if ($j == $k) $whole_bit .= $b;
				$j++;
			}
			$bits[$i] = $whole_bit;
		}
		array_push($new_bits, $bits[$i]);
	}
	$whole = implode('', $new_bits);

	return $whole;
}

/******
 * This code originally written by Ben Boyter
 * http://www.boyter.org/2013/04/building-a-search-result-extract-generator-in-php/
 */

// find the locations of each of the words
// Nothing exciting here. The array_unique is required
// unless you decide to make the words unique before passing in
function relevanssi_extract_locations($words, $fulltext) {
    $locations = array();
    foreach($words as $word) {
        $wordlen = relevanssi_strlen($word);
        $loc = relevanssi_stripos($fulltext, $word, 0);
        while($loc !== FALSE) {
            $locations[] = $loc;
            $loc = relevanssi_stripos($fulltext, $word, $loc + $wordlen);
        }
    }
    $locations = array_unique($locations);
    sort($locations);

    return $locations;
}

function relevanssi_count_matches($words, $fulltext) {
	$count = 0;
	foreach( $words as $word ) {
		$word = relevanssi_add_accent_variations($word);

		if (get_option('relevanssi_fuzzy') == 'never') {
			$pattern = '/([\s,\.:;\?!\']'.$word.'[\s,\.:;\?!\'])/i';
			if (preg_match($pattern, $fulltext, $matches, PREG_OFFSET_CAPTURE)) {
				$count += count($matches) - 1;
			}
		}
		else {
			$pattern = '/([\s,\.:;\?!\']'.$word.')/i';
			if (preg_match($pattern, $fulltext, $matches, PREG_OFFSET_CAPTURE)) {
				$count += count($matches) - 1;
			}
			$pattern = '/('.$word.'[\s,\.:;\?!\'])/i';
			if (preg_match($pattern, $fulltext, $matches, PREG_OFFSET_CAPTURE)) {
				$count += count($matches) - 1;
			}
		}
	}
	return $count;
}

// Work out which is the most relevant portion to display
// This is done by looping over each match and finding the smallest distance between two found
// strings. The idea being that the closer the terms are the better match the snippet would be.
// When checking for matches we only change the location if there is a better match.
// The only exception is where we have only two matches in which case we just take the
// first as will be equally distant.
function relevanssi_determine_snip_location($locations, $prevcount) {
    if (!is_array($locations) || empty($locations)) return 0;

    // If we only have 1 match we dont actually do the for loop so set to the first
    $startpos = $locations[0];
    $loccount = count($locations);
    $smallestdiff = PHP_INT_MAX;

    // If we only have 2 skip as its probably equally relevant
    if(count($locations) > 2) {
        // skip the first as we check 1 behind
        for($i=1; $i < $loccount; $i++) {
            if($i == $loccount-1) { // at the end
                $diff = $locations[$i] - $locations[$i-1];
            }
            else {
                $diff = $locations[$i+1] - $locations[$i];
            }

            if($smallestdiff > $diff) {
                $smallestdiff = $diff;
                $startpos = $locations[$i];
            }
        }
    }

    $startpos = $startpos > $prevcount ? $startpos - $prevcount : 0;
    return $startpos;
}

// 1/6 ratio on prevcount tends to work pretty well and puts the terms
// in the middle of the extract
function relevanssi_extract_relevant($words, $fulltext, $rellength=300, $prevcount=50) {
	$textlength = relevanssi_strlen($fulltext);

	if($textlength <= $rellength) {
        return array($fulltext, 1, 0);
    }

    $locations = relevanssi_extract_locations($words, $fulltext);
    $startpos  = relevanssi_determine_snip_location($locations,$prevcount);

    // if we are going to snip too much...
    if($textlength-$startpos < $rellength) {
        $startpos = $startpos - ($textlength-$startpos)/2;
    }

    function_exists('mb_substr') ? $substr = 'mb_substr' : $substr = 'substr';
    function_exists('mb_strrpos') ? $strrpos = 'mb_strrpos' : $strrpos = 'strrpos';

	$reltext = call_user_func($substr, $fulltext, $startpos, $rellength);

    // check to ensure we dont snip the last word if thats the match
    if( $startpos + $rellength < $textlength) {
        $reltext = call_user_func($substr, $reltext, 0, call_user_func($strrpos, $reltext, " ")); // remove last word
    }

	$start = false;
    if($startpos == 0) $start = true;

	$besthits = count(relevanssi_extract_locations($words, $reltext));

    return array($reltext, $besthits, $start);
}

function relevanssi_add_accent_variations($word) {
    $replacement_arrays = apply_filters('relevanssi_accents_replacement_arrays', array(
        "from" => array('a','c','e','i','o','u','n','ss'),
        "to" => array('(a|á|à|â)','(c|ç)', '(e|é|è|ê|ë)','(i|í|ì|î|ï)','(o|ó|ò|ô|õ)','(u|ú|ù|ü|û)','(n|ñ)','(ss|ß)'),
    ));

	$word = str_ireplace($replacement_arrays['from'], $replacement_arrays['to'], $word);

   	return $word;
}

?>
