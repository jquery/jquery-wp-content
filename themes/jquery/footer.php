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

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script>!window.jQuery && document.write(unescape('%3Cscript src="<?php echo get_template_directory_uri(); ?>/js/jquery-1.7.2.min.js"%3E%3C/script%3E'))</script>

<script src="<?php echo get_template_directory_uri(); ?>/js/plugins.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/scripts.js"></script>

<!-- /scripts -->

<?php wp_footer(); ?>

</body>
</html>
