<?php
/**
 * The Template for displaying single posts.
 */

get_header();

<div id="support-warning-box" class="warning"><svg width="20" height="20" viewBox="0 0 24 24">
  <circle cx="12" cy="12" r="10" fill="none" stroke="black" stroke-width="2" />
  <text x="50%" y="57%" dominant-baseline="middle" text-anchor="middle">i</text>
  </svg>&nbsp;&nbsp;This version is End-of-Life. Read more about support options&nbsp;<a href="https://jquery.com/support/">here</a>.
</div>

get_template_part( 'single', 'api' );
get_footer();
