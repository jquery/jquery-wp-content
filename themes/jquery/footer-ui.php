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
        <input value="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.19/themes/base/jquery-ui.css">
      </div>
      <div class="cdn">
        <strong>CDN <em>JS</em></strong>
        <input value="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js">
      </div>
      <div class="cdn">
        <strong>CDN <em>JS</em></strong>
        <input value="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.19/jquery-ui.min.js">
      </div>
      <div class="download">
        <strong>Download jQuery UI 1.8.19 (for jQuery 1.3.2+):</strong>
        <span><a href="http://jqueryui.com/download/jquery-ui-1.8.21.custom.zip">Development Bundle</a>
        <a href="http://jqueryui.com/download#stable-themes">Themes</a>
        <a href="http://jqueryui.com/download">Download Builder →</a></span>
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
          <a href="#">
            <span class="bottom"><img src="<?php echo get_template_directory_uri(); ?>/content/books/jquery-ui-1.8.jpg" alt="jQuery UI 1.8: The User Interface Library for jQuery by Dan Wellman" width="92" height="114" /></span>
            <strong>jQuery UI 1.8: The User Interface Library for jQuery</strong><br />
            <cite>Dan Wellman</cite>
          </a>
        </li>
        <li>
          <a href="#">
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

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script>!window.jQuery && document.write(unescape('%3Cscript src="<?php echo get_template_directory_uri(); ?>/js/jquery-1.7.2.min.js"%3E%3C/script%3E'))</script>

<script src="<?php echo get_template_directory_uri(); ?>/js/plugins.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/scripts.js"></script>

<!-- /scripts -->

<?php wp_footer(); ?>

</body>
</html>