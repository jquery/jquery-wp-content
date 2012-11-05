</div>
<!-- /container -->

<!-- footer -->
<footer id="site-footer" class="clearfix">

  <div class="constrain">

  	<?php // get_sidebar( 'footer' ); ?>

    <div class="col"></div>
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
