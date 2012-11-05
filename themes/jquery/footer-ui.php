</div>
<!-- /container -->

<!-- footer -->
<footer id="site-footer" class="clearfix">

  <div class="constrain">

  	<?php // get_sidebar( 'footer' ); ?>

    <div class="col3-2 col">
      <h3><span>Quick Access</span></h3>
      <div class="cdn">
        <strong>CDN <em>CSS</em></strong>
        <input value="http://code.jquery.com/ui/1.9.1/themes/base/jquery-ui.css">
      </div>
      <div class="cdn">
        <strong>CDN <em>JS</em></strong>
        <input value="http://code.jquery.com/jquery-1.8.2.js">
      </div>
      <div class="cdn">
        <strong>CDN <em>JS</em></strong>
        <input value="http://code.jquery.com/ui/1.9.1/jquery-ui.js">
      </div>
      <div class="download">
        <div><strong>Download jQuery UI 1.9.1 (for jQuery 1.6+):</strong></div>
        <span><a href="https://github.com/downloads/jquery/jquery-ui/jquery-ui-1.9.1.zip">Development Bundle</a>
        <a href="https://github.com/downloads/jquery/jquery-ui/jquery-ui-themes-1.9.1.zip">Themes</a>
        <a href="http://jqueryui.com/download/">Download Builder →</a></span>
      </div>
      <ul class="footer-icon-links">
        <li class="footer-icon icon-github"><a href="http://github.com/jquery/jquery-ui">GitHub <small>jQuery UI <br>Source</small></a></li>
        <li class="footer-icon icon-forum"><a href="http://forum.jquery.com/using-jquery-ui">Forum <small>Community <br>Support</small></a></li>
        <li class="footer-icon icon-bugs"><a href="http://bugs.jqueryui.com">Bugs <small>Issue <br>Tracker</small></a></li>
      </ul>
    </div>

    <div class="col3-1 col">
      <h3><span>Books</span></h3>
      <ul class="books">
        <li>
          <a href="http://link.packtpub.com/SHnqUf">
            <span class="bottom"><img src="<?php echo get_template_directory_uri(); ?>/content/books/jquery-ui-1.8.jpg" alt="jQuery UI 1.8: The User Interface Library for jQuery by Dan Wellman" width="92" height="114" /></span>
            <strong>jQuery UI 1.8: The User Interface Library for jQuery</strong><br />
            <cite>Dan Wellman</cite>
          </a>
        </li>
        <li>
          <a href="http://link.packtpub.com/PG9pAC">
            <span><img src="<?php echo get_template_directory_uri(); ?>/content/books/jquery-ui-themes.jpg" alt="jQuery UI Themes by Adam Boduch" width="92" height="114" /></span>
            <strong>jQuery UI Themes</strong><br />
            <cite>Adam Boduch</cite>
          </a>
        </li>
      </ul>
    </div>
    <div id="legal">
      <ul class="footer-site-links">
        <li class="icon-learning-center icon"><a href="http://learn.jquery.com/">Learning Center</a></li>
        <li class="icon-forum icon"><a href="http://forum.jquery.com/using-jquery-ui">Forum</a></li>
        <li class="icon-api icon"><a href="http://api.jqueryui.com/">API</a></li>
        <li class="icon-twitter icon"><a href="http://twitter.com/jqueryui">Twitter</a></li>
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