<?php
/*
Page Tagger wordpress plugin
Copyright (C) 2009-2012 Ramesh Nair

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

/*
Plugin Name: Page Tagger
Plugin URI: http://www.hiddentao.com/code/wordpress-page-tagger-plugin/
Description: Enables tagging for pages. PHP 5 required.
Version: 0.3.7
Author: Ramesh Nair
Author URI: http://www.hiddentao.com/
*/


// name of my parent folder
define('PAGE_TAGGER_PARENT_DIR', basename(dirname(__FILE__)) );


/**
 * Inform user of the minimum PHP version requird for Page Tagger.
 */
function _page_tagger_min_version_notice()
{
	echo "<div class='updated' style='background-color:#f99;'><p><strong>WARNING:</strong> Page Tagger plugin requires PHP 5 or above to work.</p></div>";
}


// need atleast PHP 5
if (5 > intval(phpversion()))
{
	add_action('admin_notices', '_page_tagger_min_version_notice');
}
else
{
	require_once('page-tagger-class.php');
	add_action('plugins_loaded',array('PageTagger','init'));
}



