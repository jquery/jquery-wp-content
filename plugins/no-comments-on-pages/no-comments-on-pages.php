<?php
/*
Plugin Name: No Comments On Pages
Plugin URI: http://jaka.kubje.org/wp/software/no-comments-on-pages/
Description: When activated, disables posting of comments to pages and hides all existing ones.
Version: 1.0.2
Author: Jaka Jancar
Author URI: http://jaka.kubje.org/
*/

/*
Copyright (c) 2009 Jaka Jancar

Permission is hereby granted, free of charge, to any person
obtaining a copy of this software and associated documentation
files (the "Software"), to deal in the Software without
restriction, including without limitation the rights to use,
copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the
Software is furnished to do so, subject to the following
conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
OTHER DEALINGS IN THE SOFTWARE.
*/

function ncop_comments_open_filter($open, $post_id=null)
{
    $post = get_post($post_id);
    return $open && $post->post_type !== 'page';
}

function ncop_comments_template_filter($file)
{
    return is_page() ? dirname(__FILE__).'/empty' : $file;
}

add_filter('comments_open', 'ncop_comments_open_filter', 10, 2);
add_filter('comments_template', 'ncop_comments_template_filter', 10, 1);
