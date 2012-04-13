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
        <input value="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js">
      </div>
      <div class="download">
        <strong>Download jQuery 1.7.1:</strong>
        <span><a href="http://code.jquery.com/jquery-1.7.1.min.js">Minified <em>(31KB)</em></a>
        <a href="http://code.jquery.com/jquery-1.7.1.js">Unminified <em>(229KB)</em></a></span>
      </div>
      <ul class="footer-icon-links">
        <li class="footer-icon icon-github"><a href="http://github.com/jquery/jquery">Github <small>jQuery <br>Source</small></a></li>
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
          <a href="#">
            <span class="bottom"><img src="<?php echo get_template_directory_uri(); ?>/content/books/learning-jquery-1.3.jpg" alt="Learning jQuery 1.3 by Karl Swedberg and Jonathan Chaffer" width="92" height="114" /></span>
            <strong>Learning jQuery 1.3</strong><br />
            <cite>Karl Swedberg and Jonathan Chaffer</cite>
          </a>
        </li>
        <li>
          <a href="#">
            <span><img src="<?php echo get_template_directory_uri(); ?>/content/books/jquery-in-action.jpg" alt="jQuery in Action by Bear Bibeault and Yehuda Katz" width="92" height="114" /></span>
            <strong>jQuery in Action</strong><br />
            <cite>Bear Bibeault and Yehuda Katz</cite>
          </a>
        </li>
        <li>
          <a href="#">
            <span><img src="<?php echo get_template_directory_uri(); ?>/content/books/jquery-enlightenment.jpg" alt="jQuery Enlightenment by Cody Lindley" width="92" height="114" /></span>
            <strong>jQuery Enlightenment</strong><br />
            <cite>Cody Lindley</cite>
          </a>
        </li>
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
<script src="<?php echo content_url(); ?>/base/js/plugins.js"></script>
<script src="<?php echo content_url(); ?>/base/js/scripts.js"></script>

<!-- /scripts -->

<?php wp_footer(); ?>

</body>
</html>
