<?php
/*
 * Template Name: jQuery SF Bay Area 2011
 */

the_post();
$noSidebar = $post->post_name === 'hotels';
?>
<!DOCTYPE html class="no-js">
<html>
<head>
  <meta charset="utf-8" />
  <title>jQuery Conference: San Francisco Bay Area 2011</title>
  <link rel="stylesheet" href="/resources/2011/sf-bay-area/css/layout-2011-04-17.css" type="text/css">
  <link rel="shortcut icon" href="http://static.jquery.com/favicon.ico" type="image/x-icon">
  <!--[if IE]>
    <style>
      .call_out_small .tag { border-top: none; border-right: none;}
      .signup form p input { outline: none; border: solid 1px #fff; position: relative; left: -10px }
    </style>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
  <script>
    document.documentElement.className = "js";
  </script>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/jquery-ui.min.js"></script>
</head>

<body class="<?php echo $post->post_name === 'sf-bay-area' ? 'home overview' : $post->post_name . ' sub'; ?>">
  <div id="wrapper">
    <header class="container_24" id="m-head">
      <a href="/2011/sf-bay-area/" id="logo">jQuery Conference</a>
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
    </header>
    <section id="content" class="container_24">
      <nav id="primary">
        <ul>
          <li <?php if ( $post->post_name === 'sf-bay-area' ) echo 'class="active"'; ?>><a href="/2011/sf-bay-area/">Overview</a></li>
          <li <?php if ( $post->post_name === 'speakers' ) echo 'class="active"'; ?>><a href="/2011/sf-bay-area/speakers/">Speakers</a></li>
          <li <?php if ( $post->post_name === 'schedule' ) echo 'class="active"'; ?>><a href="/2011/sf-bay-area/schedule/">Schedule</a></li>
          <li <?php if ( $post->post_name === 'training' ) echo 'class="active"'; ?>><a href="/2011/sf-bay-area/training/">Training</a></li>
          <li <?php if ( $post->post_name === 'hotels' ) echo 'class="active"'; ?>><a href="/2011/sf-bay-area/hotels/">Hotels</a></li>
        </ul>
      </nav>

      <?php if ( !$noSidebar ) : ?><div id="main-column"><?php endif; ?>

      <?php the_content(); ?>

      <?php if ( !$noSidebar ) : ?>
      </div>
      <?php if ( $post->post_name === 'sf-bay-area' ) : ?>
          <aside id="sidebar">
            <section class="widget sponsors-widget"  >
              <header>
                <h3>Platinum Sponsor</h3>
              </header>
              <ul>
                <li>
                          <a href="http://www.microsoft.com/"><img src="/resources/2011/sf-bay-area/images/logos/microsoft.png" width="300" height="49" alt="Microsoft"></a>
                </li>
              </ul>
            </section>
            <section class="widget sponsors-widget"  >
              <header>
                <h3>Gold Sponsor</h3>
              </header>
              <ul>
                <li>
                  <a href="http://www.wijmo.com"><img src="/resources/2011/sf-bay-area/images/logos/wijmo.png" width="240" height="76" alt="Wijmo - jQuery UI Widgets"></a>
                </li>
              </ul>
            </section>
            <section class="widget sponsors-widget"  >
              <header>
                <h3>Party Sponsor</h3>
              </header>
              <ul>
                <li>
                  <a href="http://mediatemple.net"><img src="/resources/2011/sf-bay-area/images/logos/mt-logo.png" width="200" height="78" alt="MediaTemple" /></a>
                </li>
              </ul>
            </section>
            <footer class="sponsors-widget-footer">
                <p>If you or your company are interested in supporting the jQuery project and conference, email <a href="mailto:sponsorship@jquery.org">sponsorship@jquery.org</a> for more information.</p>
            </footer>
            <section class="widget" >
              <header>
                <h3>Testimonial</h3>
              </header>
              <div class="content">
                <p>&ldquo;The 2010 SF jQuery conference served as a propellant for me in my development career. The jQuery team excelled in delivering a solid education in everything from beginner to advanced techniques that got me excited to go back to work and use.</p>
                <p>Across the country at JQCon Boston the team again did a remarkable job in serving up both new techniques and new friendships with the people who help make my development life fun. Needless to say I am stoked for what the 2011 JQCon SF will bring!&rdquo;</p>
                <p><cite><img src="/resources/2011/sf-bay-area/images/avatars/ryan-olson.jpg" alt="Ryan Olson" title="" /><strong>Ryan Olson</strong><br /><a href="http://whosryan.com/">http://whosryan.com/</a></cite></p>
              </div>
            </section>
          </aside>
        <?php else : ?>
          <aside id="sidebar">
            <section class="widget sponsors-widget"  >
              <header>
                <h3>Platinum Sponsor</h3>
              </header>
              <ul>
                <li>
                  <a href="http://www.microsoft.com/"><img src="/resources/2011/sf-bay-area/images/logos/microsoft.png" width="300" height="49" alt="Microsoft"></a>
                </li>
              </ul>
            </section>
            <section class="widget sponsors-widget"  >
              <header>
                <h3>Gold Sponsor</h3>
              </header>
              <ul>
                <li>
                  <a href="http://www.wijmo.com"><img src="/resources/2011/sf-bay-area/images/logos/wijmo.png" width="240" height="76" alt="Wijmo - jQuery UI Widgets"></a>
                </li>
              </ul>
            </section>
            <section class="widget sponsors-widget"  >
              <header>
                <h3>Party Sponsor</h3>
              </header>
              <ul>
                <li>
                  <a href="http://mediatemple.net"><img src="/resources/2011/sf-bay-area/images/logos/mt-logo.png" width="200" height="78" alt="MediaTemple" /></a>
                </li>
              </ul>
            </section>
            <footer class="sponsors-widget-footer">
                <p>If you or your company are interested in supporting the jQuery project and conference, email <a href="mailto:sponsorship@jquery.org">sponsorship@jquery.org</a> for more information.</p>
            </footer>
          </aside>
        <?php endif; ?>
      <?php endif; ?>
    </section>
    <footer class="container_24">
      <section id="flickr" class="grid_9 prefix_1">
        <h3>Photo Stream<br /><small>via Flickr</small></h3>
        <ul>
            <li><a target="jqueryflickr" href="http://www.flickr.com/photos/jwalsh_/5102853142/" title="IMG_2316.JPG"><img src="http://farm5.static.flickr.com/4145/5102853142_d28dfe5fa6_s.jpg" width="75" height="75" /></a></li>
            <li><a target="jqueryflickr" href="http://www.flickr.com/photos/jdsharp-com/5103040651/" title="jQuery Team"><img src="http://farm2.static.flickr.com/1440/5103040651_f4bb6f60b8_s.jpg" width="75" height="75" /></a></li>
            <li><a target="jqueryflickr" href="http://www.flickr.com/photos/jwalsh_/5102853888/" title="IMG_2318.JPG"><img src="http://farm2.static.flickr.com/1370/5102853888_9195b90f20_s.jpg" width="75" height="75" /></a></li>
            <li><a target="jqueryflickr" href="http://www.flickr.com/photos/jwalsh_/5102260221/" title="IMG_2319.JPG"><img src="http://farm2.static.flickr.com/1381/5102260221_5e58576f1b_s.jpg" width="75" height="75" /></a></li>
            <li><a target="jqueryflickr" href="http://www.flickr.com/photos/jwalsh_/5102855096/" title="IMG_2325.JPG"><img src="http://farm2.static.flickr.com/1365/5102855096_a75385f69d_s.jpg" width="75" height="75" /></a></li>
            <li><a target="jqueryflickr" href="http://www.flickr.com/photos/jwalsh_/5102850384/" title="IMG_2309.JPG"><img src="http://farm5.static.flickr.com/4084/5102850384_49431f644d_s.jpg" width="75" height="75" /></a></li>
            <li><a target="jqueryflickr" href="http://www.flickr.com/photos/jwalsh_/5102850990/" title="IMG_2311.JPG"><img src="http://farm2.static.flickr.com/1402/5102850990_3818749bc7_s.jpg" width="75" height="75" /></a></li>
            <li><a target="jqueryflickr" href="http://www.flickr.com/photos/jwalsh_/5102255931/" title="IMG_2307.JPG"><img src="http://farm5.static.flickr.com/4048/5102255931_653bd20aac_s.jpg" width="75" height="75" /></a></li>
            <li><a target="jqueryflickr" href="http://www.flickr.com/photos/jwalsh_/5102258039/" title="IMG_2314.JPG"><img src="http://farm2.static.flickr.com/1046/5102258039_0e3b4e74b9_s.jpg" width="75" height="75" /></a></li>
            <li><a target="jqueryflickr" href="http://www.flickr.com/photos/jwalsh_/5102850714/" title="IMG_2310.JPG"><img src="http://farm2.static.flickr.com/1045/5102850714_6a46959584_s.jpg" width="75" height="75" /></a></li>
            <li><a target="jqueryflickr" href="http://www.flickr.com/photos/jwalsh_/5102260489/" title="IMG_2321.JPG"><img src="http://farm2.static.flickr.com/1390/5102260489_bcd6f02b69_s.jpg" width="75" height="75" /></a></li>
            <li><a target="jqueryflickr" href="http://www.flickr.com/photos/jwalsh_/5102850094/" title="IMG_2308.JPG"><img src="http://farm5.static.flickr.com/4110/5102850094_d3e3e3e8c6_s.jpg" width="75" height="75" /></a></li>
          </ul>
      </section>
      <section id="best_talks" class="grid_7 prefix_1">
        <h3>Top Rated Sessions<br /><small>from jQCon Boston 2010</small></h3>
        <ul>
          <li>
              <a href="http://speakerrate.com/talks/4638">Getting Involved in the jQuery Community</a><br />
              <span class="author">by <a href="http://speakerrate.com/speakers/3103">Ralph Whitbeck</a></span>
            </li>
          <li>
              <a href="http://speakerrate.com/talks/4651">Contextual jQuery</a><br />
              <span class="author">by <a href="http://speakerrate.com/speakers/3201">Doug Neiner</a></span>
            </li>
          <li>
              <a href="http://speakerrate.com/talks/4646">The State of HTML5 : Inaugural Address</a><br />
              <span class="author">by <a href="http://speakerrate.com/speakers/1477">Paul Irish</a></span>
            </li>
          <li>
              <a href="http://speakerrate.com/talks/4666">Super Awesome Interactions with jQuery</a><br />
              <span class="author">by <a href="http://speakerrate.com/speakers/9893">Matt Kelly</a></span>
            </li>
          <li>
              <a href="http://speakerrate.com/talks/4653">jQuery's Best Friends</a><br />
              <span class="author">by <a href="http://speakerrate.com/speakers/3193">Alex Sexton</a></span>
            </li>
          </ul>
      </section>
      <section id="upcoming_conferences" class="grid_6 prefix_1">
        <h3>Past Events</h3>
        <ul>
          <li>
            <a href="/2010/boston/">jQuery Conference<br />
            Boston 2010</a>
          </li>
          <li>
            <a href="/2010/sf-bay-area/">jQuery Conference<br />
            SF Bay Area 2010</a>
          </li>
          <li>
            <a href="http://jquery14.com">14 Days of jQuery</a>
          </li>
          <li>
            <a href="http://events.jquery.com/jquery-conference-2009/">jQuery Conference 2009</a>
          </li>
        </ul>
        <h3>Photo Credits</h3>
        <p>The background image is based off a photo by <a href="http://www.flickr.com/photos/brentdanley/254566508/in/photostream/">Brent Danley</a> and is used under a Creative Commons license and is copyrighted by Brent Danley.</p>
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
