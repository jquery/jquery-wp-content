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
        <span>//code.jquery.com/mobile/1.0/jquery.mobile-1.0.min.css</span>
      </div>
      <div class="cdn">
        <strong>CDN <em>JS</em></strong>
        <span>//code.jquery.com/jquery-1.6.4.min.js</span>
      </div>
      <div class="cdn">
        <strong>CDN <em>JS</em></strong>
        <span>//code.jquery.com/mobile/1.0/jquery.mobile-1.0.min.js</span>
      </div>
      <div class="download">
        <strong>Download jQuery Mobile 1.0:</strong>
        <span><a href="#">Zip File <em>(JavaScript, CSS, and images)</em></a></span>
      </div>
      <ul class="footer-icon-links">
        <li class="footer-icon icon-github"><a href="http://github.com/jquery/jquery-mobile">Github <small>jQuery Mobile <br>Source</small></a></li>
        <li class="footer-icon icon-forum"><a href="http://forum.jquery.com/jquery-mobile">Forum <small>Community <br>Support</small></a></li>
        <li class="footer-icon icon-bugs"><a href="http://github.com/jquery/jquery-mobile/issues">Bugs <small>Issue <br>Tracker</small></a></li>
      </ul>
    </div>
    
    <div id="legal">
      <ul class="footer-site-links">
        <li class="icon-learning-center icon"><a href="#">Learning Center</a></li>
        <li class="icon-forum icon"><a href="http://forum.jquery.com/jquery-mobile">Forum</a></li>
        <li class="icon-api icon"><a href="#">API</a></li>
        <li class="icon-twitter icon"><a href="http://twitter.com/jquerymobile">Twitter</a></li>
        <li class="icon-irc icon"><a href="#">IRC</a></li>
      </ul>
        <p class="copyright">Copyright &copy; 2011 by <a href="http://jquery.org/team/">The jQuery Project</a>.<br /><span class="sponsor-line">Sponsored by <a href="http://mediatemple.net" class="mt-link">Media Temple</a> and <a href="http://jquery.org/sponsors/">others</a>.</span></p>
    </div>
  </div>
</footer>
<!-- /footer -->

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script>!window.jQuery && document.write(unescape('%3Cscript src="<?php echo content_url(); ?>/base/js/jquery-1.7.1.min.js"%3E%3C/script%3E'))</script>

<script id="tooltip-template" type="text/html"> 
  <div class="tooltip">
    <a href="${url}" title="${title}" class="jq-tooltip-branding"><img src="${preview}" /></a>
    <ul>{{each(i,link) links}}<li><a href="${link[1]}">${link[0]}</a></li>{{/each}}</ul>
  </div>
</script> 

<script src="<?php echo content_url(); ?>/base/js/plugins/jquery.ba-outside-events.min.js"></script>
<script src="<?php echo content_url(); ?>/base/js/plugins/syntaxhighlighter.min.js"></script>
<script src="<?php echo content_url(); ?>/base/js/plugins/jquery.infieldlabel.min.js"></script>
<script src="<?php echo content_url(); ?>/base/js/plugins/app.base.js"></script>
<script src="<?php echo content_url(); ?>/base/js/plugins.js"></script>
<script src="<?php echo content_url(); ?>/base/js/scripts.js"></script>

<!-- /scripts -->

<?php wp_footer(); ?>

</body>
</html>