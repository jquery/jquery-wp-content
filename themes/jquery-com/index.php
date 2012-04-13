<?php get_header(); ?>
		
  <!-- body -->
  <div id="body" class="clearfix">
    
    <!-- inner -->
    <div class="inner">
    

<div id="jq-intro" class="col3-2 clearfix">
<h2 class="entry-title"><span class="jq-jquery"><span>jQuery</span></span> is a new kind of JavaScript Library.</h2>
<p>jQuery is a fast and concise JavaScript Library that simplifies HTML document traversing, event handling, animating, and Ajax interactions for rapid web development. <strong>jQuery is designed to change the way that you write JavaScript.</strong></p>
<ul class="jq-checkpoints clearfix">
<li class="col3-1"><a href="http://docs.jquery.com/Tutorials" title="Lightweight Footprint" class="jq-thickbox">Lightweight Footprint</a>
<div class="jq-checkpointSubhead">

<p>About 31KB in size <em>(Minified and Gzipped)</em></p>
</div>
</li>
<li class="col3-1"><a href="http://docs.jquery.com/Tutorials" title="CSS3 Compliant" class="jq-thickbox">CSS3 Compliant</a>
<div class="jq-checkpointSubhead">
<p>Supports CSS 1-3 selectors and more!</p>
</div>

</li>
<li class="col3-1"><a href="http://docs.jquery.com/Tutorials" title="Cross-browser" class="jq-thickbox">Cross-browser</a>
<div class="jq-checkpointSubhead">
<p>IE 6.0+, FF 2.0+, Safari 3.0+, Opera 9.0+, Chrome</p>
</div>
</li>
</ul>
</div><!-- /#intro -->

<div id="jq-download" class="col3-1">
<h2>Grab the latest version!</h2>
<form action="" method="get">
<fieldset>
<legend>Choose your compression level:</legend>
<div id="jq-compression" class="clearfix">
<input type="radio" name="name" value="http://code.jquery.com/jquery-1.7.1.min.js" id="jq-production" checked="checked">
<a class="jq-radioToggle name jq-checked" href="http://code.jquery.com/jquery-1.7.1.min.js">jquery-1.7.1.min.js</a>
<label for="jq-production">Production <em>(<strong>31KB</strong>, Minified and Gzipped)</em></label>
<input type="radio" name="name" value="http://code.jquery.com/jquery-1.7.1.js" id="jq-development">
<a class="jq-radioToggle name" href="http://code.jquery.com/jquery-1.7.1.js">jquery-1.7.1.js</a>
<label for="jq-development">Development <em>(<strong>229KB</strong>, Uncompressed Code)</em></label>
</div>
<button type="submit" name="downloadBtn" id="jq-downloadBtn"><span>Download</span></button>
<p class="jq-version"><strong>Current Release:</strong> v1.7.1</p>
</fieldset>
</form>
<script>
jQuery("#jq-download form").submit(function(){
window.location = jQuery(this).find("input:checked").val();
return false;
});
</script>
</div><!-- /#download -->

<div id="jq-whosUsing">
<h2 class="jq-whosUsing">Who's using jQuery?</h2>
<ul class="jq-whosUsing">
<li><a href="http://www.google.com" class="jq-google" title="Google">Google</a></li>
<li><a href="http://www.dell.com" class="jq-dell" title="Dell">Dell</a></li>
<li><a href="http://www.bankofamerica.com" class="jq-boa" title="Bank of America">Bank of America</a></li>
<li><a href="http://www.mlb.com" class="jq-mlb" title="Major League Baseball">Major League Baseball</a></li>
<li><a href="http://www.digg.com" class="jq-digg" title="Digg">Digg</a></li>
<li><a href="http://www.nbc.com" class="jq-nbc" title="NBC">NBC</a></li>
<li><a href="http://www.cbs.com" class="jq-cbs" title="CBS News">CBS News</a></li>
<li><a href="http://www.netflix.com" class="jq-netflix" title="Netflix">Netflix</a></li>
<li><a href="http://www.technorati.com" class="jq-technorati" title="Technorati">Technorati</a></li>
<li><a href="http://www.mozilla.org" class="jq-mozilla" title="Mozilla">Mozilla</a></li>
<li><a href="http://www.wordpress.org" class="jq-wordpress" title="Wordpress">Wordpress</a></li>
<li><a href="http://www.drupal.org" class="jq-drupal" title="Drupal">Drupal</a></li>
</ul>
</div><!-- /#jq-whosUsing -->


<div id="jq-learnjQuery" class="clearfix">

<div id="jq-learnNow" class="col3-1">
<h2>Learn <span class="jq-jquery"><span>jQuery</span></span> Now!</h2>
<p>What does jQuery code look like? Here's the quick and dirty:</p>
<div class="jq-codeDemo clearfix">
<pre><code>$("p.neat").addClass("ohmy").show("slow");</code></pre>
<a href="http://docs.jquery.com/Tutorials" class="jq-runCode">Run Code</a>

<p class="neat"><strong>Congratulations!</strong> You just ran a snippet of jQuery code. Wasn't that easy? There's lots of example code throughout the <strong><a href="http://docs.jquery.com/">documentation</a></strong> on this site. Be sure to give all the code a test run to see what happens.</p>
</div>
</div><!-- /#learnNow -->



<div id="jq-resources" class="col3-2 clearfix">
<h2>jQuery Resources</h2>

<div class="jq-gettingStarted col2-1">
<h3>Getting Started With jQuery</h3>
<ul>
<li><a href="http://docs.jquery.com/How_jQuery_Works">How jQuery Works</a></li>
<li><a href="http://docs.jquery.com/Tutorials">Tutorials</a></li>
<li><a href="http://docs.jquery.com/Using_jQuery_with_Other_Libraries">Using jQuery with other libraries</a></li>
<li><a href="http://docs.jquery.com/">jQuery Documentation</a></li>

</ul>
</div>
<div class="jq-devResources col2-1">
<h3>Developer Resources</h3>
<ul>
<li><a href="http://docs.jquery.com/Discussion">Mailing List</a></li>
<li><a href="http://docs.jquery.com/Downloading_jQuery">Source code / Git</a></li>

<li><a href="http://docs.jquery.com/Plugins/Authoring">Plugin Authoring</a></li>
<li><a href="http://dev.jquery.com/newticket/">Submit a New Bug Report</a></li>
</ul>
</div>
</div><!-- /#resources -->

</div><!-- /#learnjQuery -->

</div><!-- /#news -->


    </div>
    <!-- /inner -->
  </div>
  <!-- /body -->

<?php get_footer(); ?>
