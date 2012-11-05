</div>
<!-- /container -->

<!-- footer -->
<footer id="site-footer" class="clearfix">

  <div class="constrain">

  	<?php // get_sidebar( 'footer' ); ?>

    <div class="col7-3 col">
      <h3><span>Quick Access</span></h3>
      <div class="cdn">
        <strong>CDN</strong>
        <input value="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js">
      </div>
      <div class="download">
        <strong>Download jQuery 1.7.2:</strong>
        <span><a href="http://code.jquery.com/jquery-1.7.2.min.js">Minified <em>(32KB)</em></a>
        <a href="http://code.jquery.com/jquery-1.7.2.js">Unminified <em>(247KB)</em></a></span>
      </div>
      <ul class="footer-icon-links">
        <li class="footer-icon icon-github"><a href="http://github.com/jquery/jquery">GitHub <small>jQuery <br>Source</small></a></li>
        <li class="footer-icon icon-forum"><a href="http://forum.jquery.com">Forum <small>Community <br>Support</small></a></li>
        <li class="footer-icon icon-bugs"><a href="http://bugs.jquery.com">Bugs <small>Issue <br>Tracker</small></a></li>
      </ul>
    </div>

    <div class="col7-2 col">
      <h3><span>Presentations</span></h3>
      <ul class="presentations">
        <li>
          <a href="#">
            <span><img src="<?php echo get_template_directory_uri(); ?>/content/presentations/building-spas-jquerys-best-friends.jpg" alt="Building Single Page Applications With jQuery's Best Friends by Addy Osmoni" width="142" height="92" /></span>
            <strong>Building Single Page Applications With jQuery's Best Friends</strong><br />
            <cite>Addy Osmoni</cite>
          </a>
        </li>
        <li>
          <a href="#">
            <span><img src="<?php echo get_template_directory_uri(); ?>/content/presentations/addyosmani-2.jpg" alt="jQuery Performance Tips &amp; Tricks by Addy Osmoni" width="142" height="92" /></span>
            <strong>jQuery Performance<br />Tips &amp; Tricks</strong><br />
            <cite>Addy Osmoni</cite>
          </a>
        </li>
      </ul>
    </div>

    <div class="col7-2 col">
      <h3><span>Books</span></h3>
      <ul class="books">
        <li>
          <a href="http://link.packtpub.com/S3Fr9Q">
            <span class="bottom"><img src="<?php echo get_template_directory_uri(); ?>/content/books/learning-jquery-3rd-ed.jpg" alt="Learning jQuery 3rd Edition by Karl Swedberg and Jonathan Chaffer" width="92" height="114" /></span>
            <strong>Learning jQuery Third Edition</strong><br />
            <cite>Karl Swedberg and Jonathan Chaffer</cite>
          </a>
        </li>
        <li>
          <a href="http://www.manning.com/affiliate/idevaffiliate.php?id=648_176">
            <span><img src="<?php echo get_template_directory_uri(); ?>/content/books/jquery-in-action.jpg" alt="jQuery in Action by Bear Bibeault and Yehuda Katz" width="92" height="114" /></span>
            <strong>jQuery in Action</strong><br />
            <cite>Bear Bibeault and Yehuda Katz</cite>
          </a>
        </li>
        <li>
          <a href="http://jqueryenlightenment.com/">
            <span><img src="<?php echo get_template_directory_uri(); ?>/content/books/jquery-enlightenment.jpg" alt="jQuery Enlightenment by Cody Lindley" width="92" height="114" /></span>
            <strong>jQuery Enlightenment</strong><br />
            <cite>Cody Lindley</cite>
          </a>
        </li>
      </ul>
    </div>
    <div id="legal">
      <ul class="footer-site-links">
        <li class="icon-learning-center icon"><a href="http://learn.jquery.com/">Learning Center</a></li>
        <li class="icon-forum icon"><a href="http://forum.jquery.com/">Forum</a></li>
        <li class="icon-api icon"><a href="http://api.jquery.com/">API</a></li>
        <li class="icon-twitter icon"><a href="http://twitter.com/jquery">Twitter</a></li>
        <li class="icon-irc icon"><a href="http://irc.jquery.com/">IRC</a></li>
      </ul>
        <p class="copyright">Copyright <?php echo date('Y'); ?> <a href="http://jquery.org/team/">The jQuery Foundation</a>.<br /><span class="sponsor-line"><a href="http://mediatemple.net" rel="noindex,nofollow" class="mt-link">Web hosting by Media Temple</a> | <a href="http://jquery.org/sponsors/">View sponsors</a></span></p>
    </div>
  </div>
</footer>
<!-- /footer -->

<?php get_template_part( 'footer', 'analytics' ); ?>

<?php wp_footer(); ?>

</body>
</html>
