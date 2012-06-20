</div>
<!-- /container -->

<!-- footer -->
<footer id="site-footer" class="clearfix">

  <div class="constrain">

	<div class="col7-1 col"></div>
    <div class="col3-2 col">
      <h3><span>Quick Access</span></h3>
      <div class="cdn">
        <strong>CDN</strong>
        <span>http://code.jquery.com/qunit/qunit-git.js</span>
      </div>
      <div class="download">
        <strong>Download QUnit 1.5.0:</strong>
        <span><a href="http://code.jquery.com/qunit/qunit-1.5.0.js">qunit.js</a><a href="http://code.jquery.com/qunit/qunit-1.5.0.css">qunit.css</a></span>
      </div>
      <ul class="footer-icon-links">
        <li class="footer-icon icon-github"><a href="http://github.com/jquery/qunit">GitHub <small>QUnit <br>Source</small></a></li>
        <li class="footer-icon icon-forum"><a href="http://forum.jquery.com/qunit-and-testing">Forum <small>Community <br>Support</small></a></li>
        <li class="footer-icon icon-bugs"><a href="http://github.com/jquery/qunit/issues">Bugs <small>Issue <br>Tracker</small></a></li>
      </ul>
    </div>

    <div id="legal">
      <ul class="footer-site-links">
        <li class="icon-learning-center icon"><a href="#">Learning Center</a></li>
        <li class="icon-forum icon"><a href="#">Forum</a></li>
        <li class="icon-api icon"><a href="#">API</a></li>
        <li class="icon-twitter icon"><a href="#">Twitter</a></li>
        <li class="icon-irc icon"><a href="#">IRC</a></li>
      </ul>
        <p class="copyright">Copyright <?php echo date('Y'); ?> <a href="http://jquery.org/team/">The jQuery Foundation</a>.<br /><span class="sponsor-line">Sponsored by <a href="http://mediatemple.net" class="mt-link">Media Temple</a> and <a href="http://jquery.org/sponsors/">others</a>.</span></p>
    </div>
  </div>
</footer>
<!-- /footer -->

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script>!window.jQuery && document.write(unescape('%3Cscript src="<?php echo get_template_directory_uri(); ?>/js/jquery-1.7.1.min.js"%3E%3C/script%3E'))</script>

<script id="tooltip-template" type="text/html">
  <div class="tooltip">
    <a href="${url}" title="${title}" class="jq-tooltip-branding"><img src="${preview}" /></a>
    <ul>{{each(i,link) links}}<li><a href="${link[1]}">${link[0]}</a></li>{{/each}}</ul>
  </div>
</script>

<script src="<?php echo get_template_directory_uri(); ?>/js/plugins/jquery.ba-outside-events.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/plugins/jquery.infieldlabel.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/plugins.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/scripts.js"></script>

<!-- /scripts -->

<?php wp_footer(); ?>

</body>
</html>