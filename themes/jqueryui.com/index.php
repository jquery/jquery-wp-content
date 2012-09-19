<?php get_header(); ?>

<div id="body" class="clearfix sidebar-left">
	<div class="inner">

		<div id="banner"><div class="glow clearfix">
			<p class="intro">jQuery UI is a curated set of user interface interactions,
			effects, widgets, and themes built on top of the jQuery JavaScript Library.
			Whether you're building highly interactive web applications or you just
			need to add a date picker to a form control, jQuery UI is the perfect
			choice.</p>

			<div class="download-box">
				<h2>Download jQuery UI 1.9.0</h2>
				<a href="/download/" class="btn">Custom Download</a>
				<p>Quick Downloads:</p>
				<div class="download-option">
					<a href="#" class="btn">Stable</a>
					<span>v1.9.0</span>
					<span>jQuery 1.6+</span>
				</div>
				<div class="download-option download-legacy">
					<a href="#" class="btn">Legacy</a>
					<span>v1.8.23</span>
					<span>jQuery 1.3.2+</span>
				</div>
			</div>
		</div></div>



		<div class="dev-links">
			<h3>Developer Links</h3>
			<ul>
				<li><a href="https://github.com/jquery/jquery-ui">Source Code (GitHub)</a></li>
				<li><a href="http://code.jquery.com/ui/jquery-ui-git.js">jQuery UI Git (WIP Build)</a>
					<ul>
						<li><a href="http://code.jquery.com/ui/jquery-ui-git.css">Theme (WIP Build)</a></li>
					</ul>
				</li>
				<li><a href="http://bugs.jqueryui.com/">Bug Tracker</a>
					<ul>
						<li><a href="http://bugs.jqueryui.com/newticket/">Submit a New Bug Report</a></li>
					</ul>
				</li>
				<li><a href="http://forum.jquery.com/">Discussion Forum</a>
					<ul>
						<li><a href="http://forum.jquery.com/using-jquery-ui/">Using jQuery UI</a></li>
						<li><a href="http://forum.jquery.com/developing-jquery-ui/">Developing jQuery UI</a></li>
					</ul>
				</li>
				<li><a href="http://wiki.jqueryui.com/">Development Planning Wiki</a></li>
				<li><a href="http://wiki.jqueryui.com/Roadmap/">Roadmap</a></li>
			</ul>
		</div>



		<h2>What's New in jQuery UI 1.9?</h2>

		<p>The <a href="/menu/">menu widget</a> creates nestable menus, great for
		inline menus, popup menus, or as a building block for complex menu systems.</p>

		<p>The <a href="/spinner/">spinner widget</a> displays buttons to easily
		input numberic values via the keyboard or mouse. When used in combination
		with <a href="https://github.com/jquery/globalize">Globalize</a>, the
		spinner supports numbers, currencies, and dates in hundreds of locales.</p>

		<p>The <a href="/tooltip/">tooltip widget</a> shows additional information
		for any element on hover or focus, with support for static content and
		remote content loaded via Ajax.</p>

		<p>The accordion, autocomplete, and tabs widgets have also received huge
		accessibility improvements. In addition, we've
		<a href="http://blog.jqueryui.com/2011/03/api-redesigns-the-past-present-and-future/">
		improved the APIs</a> of several widgets, making them easier to use and
		creating more consistency across plugins.</p>

		<p>Interested in the full details of what changed? Check out the
		<a href="/changelog/1.9.0/">changelog</a> and
		<a href="/upgrade-guide/1.9/">upgrade guide</a>.</p>


		<hr class="dots">
		<h2>Dive In!</h2>

		<p>jQuery UI is built for designers and developers alike. We've designed
		all of our plugins to get you up and running quickly while being flexible
		enough to evolve with your needs and solve a plethora of use cases. Play
		around with the <a href="/demos/">demos</a> and read through the
		<a href="http://api.jqueryui.com/">API documentation</a> to get an idea
		of what's possible.</p>

		<p>Stay informed about what's going on with jQuery UI by subscribing to
		our <a href="http://blog.jqueryui.com/">blog</a>.</p>

	</div>

	<?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>
