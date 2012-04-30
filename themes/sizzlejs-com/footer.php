</div>
<!-- /container -->

<!-- footer -->
<footer id="site-footer" class="clearfix">
	  
  <div class="constrain">
  	
  	<?php // get_sidebar( 'footer' ); ?>
  	
    <div class="col3-2 col">
      <ul class="footer-icon-links">
        <li class="footer-icon icon-github"><a href="http://github.com/jquery/sizzle">Github <small>Sizzle <br>Source</small></a></li>
        <li class="footer-icon icon-forum"><a href="http://groups.google.com/group/sizzlejs">Mailing List <small>Community <br>Support</small></a></li>
        <li class="footer-icon icon-bugs"><a href="https://github.com/jquery/sizzle/issues">Bugs <small>Issue <br>Tracker</small></a></li>
      </ul>
    </div>
    
    <div id="legal">
      <ul class="footer-site-links">
        <li class="icon-learning-center icon"><a href="#">Learning Center</a></li>
        <li class="icon-forum icon"><a href="http://forum.jquery.com/using-jquery-ui">Forum</a></li>
        <li class="icon-api icon"><a href="http://li367-225.members.linode.com/">API</a></li>
        <li class="icon-twitter icon"><a href="http://twitter.com/jqueryui">Twitter</a></li>
        <li class="icon-irc icon"><a href="#">IRC</a></li>
      </ul>
        <p class="copyright">Copyright &copy; 2011 by <a href="http://jquery.org/team/">The jQuery Project</a>.<br /><span class="sponsor-line">Sponsored by <a href="http://mediatemple.net" class="mt-link">Media Temple</a> and <a href="http://jquery.org/sponsors/">others</a>.</span></p>
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
<script src="<?php echo get_template_directory_uri(); ?>/js/plugins/syntaxhighlighter.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/plugins/jquery.infieldlabel.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/plugins.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/scripts.js"></script>

<!-- /scripts -->

<?php wp_footer(); ?>

</body>
</html>