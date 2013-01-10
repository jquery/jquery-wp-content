<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>jQuery: The Write Less, Do More, JavaScript Library</title>
	<link rel="stylesheet" href="http://static.jquery.com/files/rocker/css/reset.css">
	<link rel="stylesheet" href="http://static.jquery.com/files/rocker/css/screen.css">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
	<script>!window.jQuery && document.write('<script src="http://code.jquery.com/jquery-1.4.2.min.js"><\/script>');</script>
	<script src="http://static.jquery.com/files/rocker/scripts/custom.js"></script>
	<link rel="alternate" type="application/rss+xml" title="jQuery Blog" href="http://jquery.com/blog/feed/">
	<link rel="shortcut icon" href="http://static.jquery.com/favicon.ico" type="image/x-icon">
	<style>
	#jq-footer #jq-credits{
		width: 290px;
	}
	#jq-secondaryNavigation ul,
	#jq-footerNavigation ul {
		width: 38em;
	}
	</style>
</head>
<body>

<div id="jq-siteContain">
	<div id="jq-header">
		<a id="jq-siteLogo" href="http://jquery.com" title="jQuery Home"><img src="http://static.jquery.com/files/rocker/images/logo_jquery_215x53.gif" width="215" height="53" alt="jQuery: Write Less, Do More."></a>

		<div id="jq-primaryNavigation">
			<ul>
				<li class="jq-jquery jq-current"><a href="http://jquery.com/" title="jQuery Home">jQuery</a></li>
				<li class="jq-ui"><a href="http://jqueryui.com/" title="jQuery UI">UI</a></li>
				<li class="jq-mobile"><a href="http://jquerymobile.com/" title="jQuery Mobile">Mobile</a></li>
				<li class="jq-plugins"><a href="http://plugins.jquery.com/" title="jQuery Plugins">Plugins</a></li>
				<li class="jq-meetup"><a href="http://meetups.jquery.com/" title="jQuery Meetups">Meetups</a></li>
				<li class="jq-forum"><a href="http://forum.jquery.com/" title="jQuery Forum">Forum</a></li>
				<li class="jq-blog"><a href="http://blog.jquery.com/" title="jQuery Blog">Blog</a></li>
				<li class="jq-about"><a href="http://jquery.org/about" title="About jQuery">About</a></li>
				<li class="jq-donate"><a href="http://jquery.org/donate" title="Donate to jQuery">Donate</a></li>
			</ul>
		</div>

		<div id="jq-secondaryNavigation">
			<ul>
				<li class="jq-download jq-first"><a href="http://jquery.com/download/">Download</a></li>

				<li class="jq-documentation"><a href="http://api.jquery.com/">Documentation</a></li>
				<li class="jq-bugTracker"><a href="http://bugs.jquery.com/">Bug Tracker</a></li>
				<li class="jq-discussion jq-last"><a href="http://forum.jquery.com/">Discussion</a></li>
			</ul>
		</div>
	</div>

	<div id="jq-content" class="jq-clearfix"><?php
		the_post();
		the_content();
	?></div>

	<div id="jq-footer" class="jq-clearfix">
		<div id="jq-credits">
			<p id="jq-copyright">&copy; 2012 <a href="http://jquery.org/">The jQuery Foundation</a></p>
			<p id="jq-hosting"><a href="http://mediatemple.net" class="jq-mediaTemple">Web hosting by Media Temple</a> | <a href="http://jquery.org/sponsors">View sponsors</a></p>
		</div>

		<div id="jq-footerNavigation">
			<ul>
				<li class="jq-download jq-first"><a href="http://docs.jquery.com/Downloading_jQuery">Download</a></li>
				<li class="jq-documentation"><a href="http://docs.jquery.com">Documentation</a></li>

				<li class="jq-bugTracker"><a href="http://dev.jquery.com/">Bug Tracker</a></li>
				<li class="jq-discussion jq-last"><a href="http://docs.jquery.com/Discussion">Discussion</a></li>
			</ul>
		</div>
	</div>
</div>

<script src="http://static.jquery.com/donate/donate.js" type="text/javascript"></script>
<script type="text/javascript">
var _gaq = _gaq || []; _gaq.push(['_setAccount', 'UA-1076265-1']); _gaq.push(['_trackPageview']); _gaq.push(['_setDomainName', '.jquery.com']);
(function() {var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(ga);})();
</script>

</body>
</html>
