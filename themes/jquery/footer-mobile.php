</div>
<!-- /container -->

<!-- footer -->
<footer id="site-footer" class="clearfix">

  <div class="constrain">

  	<?php // get_sidebar( 'footer' ); ?>
  <div class="col7-1 col"></div>
    <div class="col3-2 col">
      <h3><span>Quick Access</span></h3>
      <div class="cdn">
        <strong>CDN <em>CSS</em></strong>
        <input value="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.css">
      </div>
      <div class="cdn">
        <strong>CDN <em>JS</em></strong>
        <input value="http://code.jquery.com/jquery-1.8.2.min.js">
      </div>
      <div class="cdn">
        <strong>CDN <em>JS</em></strong>
        <input value="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.js">
      </div>
      <div class="download">
        <strong>Download jQuery Mobile 1.2.0:</strong>
        <span><a href="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.zip">Zip File <em>(JavaScript, CSS, and images)</em></a></span>
      </div>
      <ul class="footer-icon-links">
        <li class="footer-icon icon-github"><a href="http://github.com/jquery/jquery-mobile">GitHub <small>jQuery Mobile <br>Source</small></a></li>
        <li class="footer-icon icon-forum"><a href="http://forum.jquery.com/jquery-mobile">Forum <small>Community <br>Support</small></a></li>
        <li class="footer-icon icon-bugs"><a href="http://github.com/jquery/jquery-mobile/issues">Bugs <small>Issue <br>Tracker</small></a></li>
      </ul>
    </div>

    <div id="legal">
      <ul class="footer-site-links">
        <li class="icon-learning-center icon"><a href="http://learn.jquery.com/">Learning Center</a></li>
        <li class="icon-forum icon"><a href="http://forum.jquery.com/jquery-mobile">Forum</a></li>
        <li class="icon-api icon"><a href="http://api.jquerymobile.com/">API</a></li>
        <li class="icon-twitter icon"><a href="http://twitter.com/jquerymobile">Twitter</a></li>
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
