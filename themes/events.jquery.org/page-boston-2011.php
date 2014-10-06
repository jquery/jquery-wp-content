<?php
/*
 * Template Name: jQuery Boston 2011
 */

the_post();
$noSidebar = $post->post_name !== 'boston' && $post->post_name !== 'schedule';
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>jQuery Conference: Boston 2011</title>
  <link rel="stylesheet" href="/resources/2011/boston/css/layout_r05.css" type="text/css">
  <link rel="shortcut icon" href="http://static.jquery.com/favicon.ico" type="image/x-icon">
  <!--[if IE]>
    <style>
      .call_out_small .tag { border-top: none; border-right: none;}
      .signup form p input { outline: none; border: solid 1px #fff; position: relative; left: -10px }
    </style>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/jquery-ui.min.js"></script>
</head>

<body class="<?php echo $post->post_name === 'boston' ? 'home overview' : $post->post_name . ' sub'; ?>">
  <div id="wrapper">
    <header class="container_24" id="m-head">
      <a href="/2011/boston/" id="logo">jQuery Conference</a>
      <nav>
        <ul>
          <li class="alpha"><a href="http://jquery.com">jQuery</a></li>
          <li><a href="http://plugins.jquery.com">Plugins</a></li>
          <li><a href="http://jqueryui.com">UI</a></li>
          <li class="break"><a href="http://meetups.jquery.com">Meetups</a></li>
          <li><a href="http://forum.jquery.com">Forum</a></li>
          <li class="active"><a href="http://events.jquery.com">Events</a></li>
          <li class="break"><a href="http://jquery.org/about">About</a></li>
          <li class="omega"><a href="http://jquery.org/donate">Donate</a></li>
        </ul>
      </nav>
      <h1 id="site-title">Boston - October 1-2, 2011</h1>
    </header>
    <section id="content" class="container_24">
			<nav id="primary">
				<ul>
					<li <?php if ( $post->post_name === 'boston' ) echo 'class="active"'; ?>><a href="/2011/boston/">Home</a></li>
					<li <?php if ( $post->post_name === 'schedule' ) echo 'class="active"'; ?>><a href="/2011/boston/schedule/">Schedule</a></li>
					<li <?php if ( $post->post_name === 'hotel' ) echo 'class="active"'; ?>><a href="/2011/boston/hotel">Hotel</a></li>
					<li <?php if ( $post->post_name === 'training' ) echo 'class="active"'; ?>><a href="/2011/boston/training">Training</a></li>
					<li <?php if ( $post->post_name === 'sponsors' ) echo 'class="active"'; ?>><a href="/2011/boston/sponsors/">Sponsors</a></li>
					<li <?php if ( $post->post_name === 'register' ) echo 'class="active"'; ?>><a href="/2011/boston/register/">Register</a>
				</ul>
			</nav>
			<?php if ( !$noSidebar ) :?><div id="main-column" class="grid_16"><?php endif; ?>

      <?php the_content(); ?>

      <?php if ( !$noSidebar ) : ?>
      </div>
        <aside id="sidebar" class="grid_8">
          <section class="widget sponsors-widget"  >
            <header>
              <h3>Platinum Sponsors</h3>
            </header>
            <ul>
              <li>
                <a href="http://kendoui.com"><img src="/resources/2011/boston/images/logos/telerik.png" width="240"alt="Kendo UI" /></a>
              </li>
            </ul>
          </section>
          <section class="widget sponsors-widget"  >
            <header>
              <h3>Silver Sponsors</h3>
            </header>
            <ul>
              <li>
                <a href="http://nokia.com"><img src="/resources/2011/boston/images/logos/nokia.png" width="240"alt="Nokia" /></a>
              </li>
              <li>
                <a href="http://www.signazon.com"><img src="/resources/2011/boston/images/logos/customsigns.png" width="240" alt="Custom Signs" /></a>
              </li>
            </ul>
          </section>
          <section class="widget sponsors-widget other-sponsors"  >
            <header>
              <h3>Party Sponsors</h3>
            </header>
            <ul>
              <li>
                <a href="http://mediatemple.net"><img src="/resources/2011/boston/images/logos/mt.png" width="240" alt="MediaTemple" /></a>
              </li>
            </ul>
          </section>
          <section class="widget sponsors-widget"  >
            <header>
              <h3>Training Sponsors</h3>
            </header>
            <ul>
              <li>
                <a href="http://bocoup.com"><img src="/resources/2011/boston/images/logos/bocoup.png" width="240" height="71" alt="Bocoup" /></a>
              </li>
            </ul>
          </section>
          <section class="widget sponsors-widget">
            <header>
              <h3>Want to sponsor?</h3>
            </header>
            <ul>
              <li>If you or your company are interested in supporting the jQuery project and conference, email <a href="mailto:sponsorship@jquery.org">sponsorship@jquery.org</a> for more information.</p>
            </ul>
          </section>
        </aside>
      <?php endif; ?>
        </section>
        <footer class="container_24">
          <section id="flickr" class="grid_9 prefix_1">
            <h3>Photo Stream<br /><small>via Flickr</small></h3>
            <ul>
                <li><a target="jqueryflickr" href="http://www.flickr.com/photos/61800375@N07/5625065300/" title="Microsoft Campus"><img src="http://farm6.static.flickr.com/5025/5625065300_1e1776fdcc_s.jpg" width="75" height="75" /></a></li>
                <li><a target="jqueryflickr" href="http://www.flickr.com/photos/61800375@N07/5624475765/" title="Simulcast to the other room"><img src="http://farm6.static.flickr.com/5022/5624475765_12f9e543ff_s.jpg" width="75" height="75" /></a></li>
                <li><a target="jqueryflickr" href="http://www.flickr.com/photos/61800375@N07/5625065090/" title="Swaaaggggg..."><img src="http://farm6.static.flickr.com/5027/5625065090_2c9529138d_s.jpg" width="75" height="75" /></a></li>
                <li><a target="jqueryflickr" href="http://www.flickr.com/photos/61800375@N07/5625065504/" title="Microsoft campus"><img src="http://farm6.static.flickr.com/5309/5625065504_de8e37157d_s.jpg" width="75" height="75" /></a></li>
                <li><a target="jqueryflickr" href="http://www.flickr.com/photos/61800375@N07/5625065684/" title="Make sure you read"><img src="http://farm6.static.flickr.com/5061/5625065684_959453fa3e_s.jpg" width="75" height="75" /></a></li>
                <li><a target="jqueryflickr" href="http://www.flickr.com/photos/61800375@N07/5622668219/" title="jQuery Training Class"><img src="http://farm6.static.flickr.com/5266/5622668219_e2f18ce0ca_s.jpg" width="75" height="75" /></a></li>
                <li><a target="jqueryflickr" href="http://www.flickr.com/photos/61800375@N07/5623255154/" title="jQuery Training Class"><img src="http://farm6.static.flickr.com/5149/5623255154_a961e3236c_s.jpg" width="75" height="75" /></a></li>
                <li><a target="jqueryflickr" href="http://www.flickr.com/photos/61800375@N07/5623125296/" title="Microsoft Mountain View Campus"><img src="http://farm6.static.flickr.com/5027/5623125296_078a52993c_s.jpg" width="75" height="75" /></a></li>
                <li><a target="jqueryflickr" href="http://www.flickr.com/photos/61800375@N07/5623125050/" title="jQuery Conference Training Class"><img src="http://farm6.static.flickr.com/5022/5623125050_cb558bdf92_s.jpg" width="75" height="75" /></a></li>
                <li><a target="jqueryflickr" href="http://www.flickr.com/photos/61800375@N07/5623124944/" title="jQuery Training Class"><img src="http://farm6.static.flickr.com/5070/5623124944_1bbcc48148_s.jpg" width="75" height="75" /></a></li>
                <li><a target="jqueryflickr" href="http://www.flickr.com/photos/61800375@N07/5622538221/" title="MS Campus Mountain View"><img src="http://farm6.static.flickr.com/5144/5622538221_8c5d25ce44_s.jpg" width="75" height="75" /></a></li>
                <li><a target="jqueryflickr" href="http://www.flickr.com/photos/61800375@N07/5622538139/" title="jQuery Training Class"><img src="http://farm6.static.flickr.com/5221/5622538139_7bab2863aa_s.jpg" width="75" height="75" /></a></li>
              </ul>
          </section>
          <section id="best_talks" class="grid_7 prefix_1">
            <h3>Top Rated Sessions<br /><small>from jQuery SF Conference 2011</small></h3>
            <ul>
              <li>
                  <a href="http://speakerrate.com/talks/7213">Progressive Enhancement 2.0 - Because the Web isn't Print</a><br />
                  <span class="author">by <a href="http://speakerrate.com/speakers/5581">Nicholas C. Zakas</a></span>
                </li>
              <li>
                  <a href="http://speakerrate.com/talks/7214">Introduction to jQuery Mobile</a><br />
                  <span class="author">by <a href="http://speakerrate.com/speakers/15670">Raymond Camden</a></span>
                </li>
              <li>
                  <a href="http://speakerrate.com/talks/7238">jQuery Development Productivity++</a><br />
                  <span class="author">by <a href="http://speakerrate.com/speakers/1477">Paul Irish</a></span>
                </li>
              <li>
                  <a href="http://speakerrate.com/talks/7211">Mobile Performance</a><br />
                  <span class="author">by <a href="http://speakerrate.com/speakers/3147">Steve Souders</a></span>
                </li>
              <li>
                  <a href="http://speakerrate.com/talks/7215">jQuery + Popcorn.js = Interactive, Immersive HTML5 Video Experiences</a><br />
                  <span class="author">by <a href="http://speakerrate.com/speakers/5918">Rick Waldron</a></span>
                </li>
              </ul>
          </section>
          <section id="upcoming_conferences" class="grid_6 prefix_1">
            <h3>Upcoming Events</h3>
            <ul>
              <li>
                jQuery Conference<br />
                Boston 2011
              </li>
            </ul>
            <h3>Past Events</h3>
            <ul>
              <li>
                <a href="/2011/sf-bay-area">jQuery Conference San Francisco Bay Area 2011</a>
              </li>
              <li>
                <a href="/2010/boston/">jQuery Conference<br />
                Boston 2010</a>
              </li>
              <li>
                <a href="/2010/sf-bay-area/">jQuery Conference<br />
                SF Bay Area 2010</a>
              </li>
            </ul>
            <h3>Photo Credits</h3>
            <p>The header image is the work of <a href="http://www.flickr.com/photos/ensh/4769294947/">Emmanuel Huybrechts</a>. The background image is based off a photo by <a href="http://www.flickr.com/photos/brentdanley/254566508/in/photostream/">Brent Danley</a>. Both photos were released under Creative Commons licenses and are copyrighted by their respective owners.</p>
          </section>
          <hr class="clear"/>
          <section id="jquery_footer" class="container_24">
            <p id="jq-copyright">&copy; 2010 <a href="http://jquery.org/">The jQuery Project</a></p> 
            <p id="jq-hosting">Sponsored by <a href="http://mediatemple.net" class="jq-mediaTemple">Media Temple</a> and <a href="http://jquery.org/sponsors">others</a>.</p>
          </section>
        </footer>
      </div>
      <script type="text/javascript">
          var _gaq = _gaq || []; _gaq.push(['_setAccount', 'UA-1076265-1']); _gaq.push(['_trackPageview']); _gaq.push(['_setDomainName', '.jquery.org']);
          (function() {var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
          ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
          (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(ga);})();
      </script>
    </body>
    </html>
