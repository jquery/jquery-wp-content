<?php get_header(); ?>
		
  <!-- body -->
  <div id="body" class="clearfix">
    
    <!-- inner -->
    <div class="inner">
    

<div id="header">
<h1 class="entry-title">Sizzle JavaScript Selector Library</h1>
<p><span>A pure-JavaScript CSS selector engine<br> designed to be easily dropped in to a host library.</span></p>
</div>

<div id="content">

<p class="download link"><strong><a href="http://github.com/jquery/sizzle/zipball/master">Download .zip file</a></strong></p>
<p class="doc link"><a href="http://wiki.github.com/jquery/sizzle">Documentation</a></p>
<p class="github link"><a href="http://github.com/jquery/sizzle/tree/master">Github project (source code)</a></p>
<p class="group link"><a href="http://groups.google.com/group/sizzlejs">Sizzle discussion group</a></p>

<div class="features">
<div class="col3-1">
<h2 class="underline">Features</h2>
<ul>
<li>Completely standalone (no library dependencies)</li>
<li>Competitive performance for most frequently used selectors</li>
<li>Only 4KB minified and gzipped</li>
<li>Highly extensible with easy-to-use API</li>
<li>Designed for optimal performance with event delegation</li>
<li>Clear IP assignment (all code held by the Dojo Foundation, contributors sign CLAs)</li>
</ul>
</div>

<div class="col3-1">
<h2 class="underline">Selector Features</h2>
<ul>
<li>CSS 3 Selector support</li>
<li>Full Unicode support</li>
<li>Escaped selector support <code>#id\:value</code></li>
<li>Contains text <code>:contains(text)</code></li>
<li>Complex :not <code>:not(a#id)</code></li>
<li>Multiple :not <code>:not(div,p)</code></li>
<li>Not attribute value <code>[name!=value]</code></li>
<li>Has selector <code>:has(div)</code></li>
<li>Position selectors <code>:first</code>, <code>:last</code>, <code>:even</code>, <code>:odd</code>, <code>:gt</code>, <code>:lt</code>, <code>:eq</code></li>
<li>Easy Form selectors <code>:input</code>, <code>:text</code>, <code>:checkbox</code>, <code>:file</code>, <code>:password</code>, <code>:submit</code>, <code>:image</code>, <code>:reset</code>, <code>:button</code></li>
<li>Header selector <code>:header</code></li>
</ul>
</div>

<div class="col3-1">
<h2 class="underline">Code Features</h2>
<ul>
<li>Provides meaningful error messages for syntax problems</li>
<li>Uses a single code path (no XPath)</li>
<li>Uses no browser-sniffing</li>
<li>Caja-compatible code</li>
</ul>
</div>
</div>
</div>
	       
    </div>
    <!-- /inner -->
  </div>
  <!-- /body -->

<?php get_footer(); ?>
