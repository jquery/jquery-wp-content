<?php
/*
Plugin Name: Raw HTML
Plugin URI: http://w-shadow.com/blog/2007/12/13/raw-html-in-wordpress/
Description: Lets you enter raw HTML in your posts and disable automatic formatting on a per-post basis. <a href="http://wpplugins.com/plugin/850/raw-html-pro/">Upgrade to Pro Version</a>
Version: 1.4.4
Author: Janis Elsts
Author URI: http://w-shadow.com/blog/
*/

/*
Created by Janis Elsts (email : whiteshadow@w-shadow.com) 
Licensed under the LGPL.
*/

define('RAWHTML_PLUGIN_FILE', __FILE__);

require 'include/tag-handler.php';
require 'include/formatting-override.php'; 

if ( is_admin() && file_exists(dirname(__FILE__).'/editor-plugin/init.php') ){
	require 'editor-plugin/init.php';
}

?>