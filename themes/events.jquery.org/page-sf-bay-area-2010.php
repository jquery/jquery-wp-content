<?php
/*
 * Template Name: jQuery SF Bay Area 2010
 */

the_post();
$home = $post->post_name === "sf-bay-area";
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>jQuery Conference: San Francisco Bay Area</title>
  <link rel="stylesheet" href="/resources/2010/sf-bay-area/css/layout_20100419.css" type="text/css">
  <link rel="shortcut icon" href="http://static.jquery.com/favicon.ico" type="image/x-icon">
  <!--[if IE]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
  <!--[if lte IE 6]>
    <script src="/resources/2010/sf-bay-area/css/ie6.css"></script>
  <![endif]-->
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" type="text/javascript" charset="utf-8"></script>
  <?php if( $home ): ?>
  <script src="/resources/2010/sf-bay-area/js/jquery.nivo.slider.min.js" type="text/javascript" charset="utf-8"></script>
  <script src="/resources/2010/sf-bay-area/js/home20100406.js" type="text/javascript" charset="utf-8"></script>
  <?php endif; ?>
</head>

<body class="<?php echo $post->post_name === 'sf-bay-area' ? 'home details' : $post->post_name . ' sub'; ?>">
  <div id="wrapper">
    <header class="container_24" id="m-head">
      <<?php echo $home ? "h1" : "div" ?> id="nameplate">
        <a href="/2010/sf-bay-area/">jQuery Conference:</a> San Francisco Bay Area
        <time datetime="2010-04-24"><em>April 24 &ndash; 25, 2010</em></time>
      </<?php echo $home ? "h1" : "div" ?>>
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
    <?php if ( $home ): ?>
    <section id="intro" class="container_24">
      <ul class="grid_23 prefix_1 alpha omega">
        <li class="photo grid_5 alpha">
          <img src="/resources/2010/sf-bay-area/images/promo/johnr.jpg" width="220" height="174" alt="John Resig" />
          <img src="/resources/2010/sf-bay-area/images/promo/food.jpg" width="220" height="174" alt="Microsoft Building" />
          <img src="/resources/2010/sf-bay-area/images/promo/jon.jpg" width="220" height="174" alt="Jon Clark" />
          <img src="/resources/2010/sf-bay-area/images/promo/mikeh.jpg" width="220" height="174" alt="Mike Hostettler" />
        </li>
        <li class="photo grid_5">
          <img src="/resources/2010/sf-bay-area/images/promo/rworth.jpg" width="220" height="174" alt="Richard Worth on Q &amp; A panel" />
          <img src="/resources/2010/sf-bay-area/images/promo/john_lecture.jpg" width="220" height="174" alt="John Resig" />
          <img src="/resources/2010/sf-bay-area/images/promo/jqueryui.jpg" width="220" height="174" alt="jQuery UI Keynote" />
        </li>
        <li class="photo grid_5">
          <img src="/resources/2010/sf-bay-area/images/promo/audience.jpg" width="220" height="174" alt="Audience" />
          <img src="/resources/2010/sf-bay-area/images/promo/karls.jpg" width="220" height="174" alt="Karl Swedberg + Ariel Flesler" />
          <img src="/resources/2010/sf-bay-area/images/promo/paul-irish.jpg" width="220" height="174" alt="Paul Irish" />
        </li>
        <li id="call_to_action" class="grid_5 omega">
          <img src="/resources/2010/sf-bay-area/images/sold_out.png" width="215" height="135" alt="Sold Out" />
        </li>
      </ul>
    </section>
    <?php endif; ?>
    <nav id="primary" class="container_24 prefix_1">
     <ul>
        <li class="grid_4 alpha <? echo $post->post_name === 'sf-bay-area' ? 'active' : ''; ?>"><a href="/2010/sf-bay-area/">Details</a></li>
         <li class="grid_4 <? echo $post->post_name === 'schedule' || $post->post_name === 'training' ? 'active' : ''; ?>"><a href="/2010/sf-bay-area/schedule/">Schedule</a></li>
         <li class="grid_4 <? echo $post->post_name === 'speakers' ? 'active' : ''; ?>"><a href="/2010/sf-bay-area/speakers/">Speakers</a></li>
         <li class="grid_4 <? echo $post->post_name === 'sponsors' ? 'active' : ''; ?>"><a href="/2010/sf-bay-area/sponsors/">Sponsors</a></li>
         <li class="helper"></li>
     </ul>
    </nav>
    <section id="content" class="container_24">

    <?php the_content(); ?>

    </section>
    <footer class="container_24">
      <section id="flickr" class="grid_9 prefix_1">
        <h3>Photo Stream<br /><small>via Flickr</small></h3>
        <ul>
            <li><a target="jqueryflickr" href="http://www.flickr.com/photos/dokas/3951657335/" title="jQuery Conference 2009"><img src="http://farm3.static.flickr.com/2613/3951657335_bbd54e8769_s.jpg" width="75" height="75" /></a></li>
            <li><a target="jqueryflickr" href="http://www.flickr.com/photos/arielflesler/3949052468/" title="3946867790_b0172eabac"><img src="http://farm3.static.flickr.com/2664/3949052468_4459eeafea_s.jpg" width="75" height="75" /></a></li>
            <li><a target="jqueryflickr" href="http://www.flickr.com/photos/63654979@N00/3946874910/" title="Rey Bango"><img src="http://farm4.static.flickr.com/3435/3946874910_eab916e9bf_s.jpg" width="75" height="75" /></a></li>
            <li><a target="jqueryflickr" href="http://www.flickr.com/photos/63654979@N00/3946878170/" title="John Resig"><img src="http://farm4.static.flickr.com/3454/3946878170_a7c28aa7db_s.jpg" width="75" height="75" /></a></li>
            <li><a target="jqueryflickr" href="http://www.flickr.com/photos/63654979@N00/3946097791/" title="Jörn Zaefferer"><img src="http://farm4.static.flickr.com/3465/3946097791_c885550d70_s.jpg" width="75" height="75" /></a></li>
            <li><a target="jqueryflickr" href="http://www.flickr.com/photos/63654979@N00/3946877536/" title="Brandon Aaron"><img src="http://farm3.static.flickr.com/2429/3946877536_5a29b333bb_s.jpg" width="75" height="75" /></a></li>
            <li><a target="jqueryflickr" href="http://www.flickr.com/photos/63654979@N00/3946883114/" title="Jonathan and Mike H."><img src="http://farm3.static.flickr.com/2555/3946883114_a9d739be45_s.jpg" width="75" height="75" /></a></li>
            <li><a target="jqueryflickr" href="http://www.flickr.com/photos/63654979@N00/3946100303/" title="Ariel, Ralph, and Scott G."><img src="http://farm4.static.flickr.com/3445/3946100303_689e96ff69_s.jpg" width="75" height="75" /></a></li>
            <li><a target="jqueryflickr" href="http://www.flickr.com/photos/63654979@N00/3946094939/" title="Delicious Food at jQuery Conference 2009"><img src="http://farm3.static.flickr.com/2535/3946094939_0ce2a90d8c_s.jpg" width="75" height="75" /></a></li>
            <li><a target="jqueryflickr" href="http://www.flickr.com/photos/63654979@N00/3946093967/" title="jQuery Conference 2009 Breakout Sessions"><img src="http://farm4.static.flickr.com/3577/3946093967_d8d6462d5d_s.jpg" width="75" height="75" /></a></li>
            <li><a target="jqueryflickr" href="http://www.flickr.com/photos/63654979@N00/3946099705/" title="jQuery Project Team Meeting 2009"><img src="http://farm3.static.flickr.com/2588/3946099705_72b481fecd_s.jpg" width="75" height="75" /></a></li>
            <li><a target="jqueryflickr" href="http://www.flickr.com/photos/63654979@N00/3946097147/" title="jQuery Project Team Meeting"><img src="http://farm4.static.flickr.com/3462/3946097147_f6151d4da8_s.jpg" width="75" height="75" /></a></li>
        </ul>
      </section>
      <section id="best_talks" class="grid_6 prefix_1">
        <h3>Top Rated Sessions<br /><small>from jQuery Conference 2009</small></h3>
        <ul>
          <li>
              <a href="http://speakerrate.com/talks/1428">jQuery Keynote</a><br />
              <span class="author">by <a href="http://speakerrate.com/speakers/128">John Resig</a></span>
            </li>
          <li>
              <a href="http://speakerrate.com/talks/1404">Beginning jQuery UI</a><br />
              <span class="author">by <a href="http://speakerrate.com/speakers/1439">Richard D. Worth</a></span>
            </li>
          <li>
              <a href="http://speakerrate.com/talks/1399">Recent Changes to jQuery's Internals</a><br />
              <span class="author">by <a href="http://speakerrate.com/speakers/128">John Resig</a></span>
            </li>
          <li>
              <a href="http://speakerrate.com/talks/1424">Building Evented Single Page Applications</a><br />
              <span class="author">by <a href="http://speakerrate.com/speakers/1605">John Nunemaker</a></span>
            </li>
          <li>
              <a href="http://speakerrate.com/talks/1409">The jQuery UI CSS Framework and ThemeRoller: An In-Depth Overview</a><br />
              <span class="author">by <a href="http://speakerrate.com/speakers/3221">Todd Parker</a></span>
            </li>
          </ul>
      </section>
      <section id="upcoming_conferences" class="grid_5 prefix_1">
        <h3>Upcoming</h3>
        <ul>
          <li>
            <a href="/2010/boston/">jQuery Conference<br />
            Boston 2010</a>
          </li>
        </ul>
        <h3>Past</h3>
        <ul>
          <li>
            <a href="http://jquery14.com">14 Days of jQuery</a>
          </li>
          <li>
            <a href="http://events.jquery.com/jquery-conference-2009/">jQuery Conference 2009</a>
          </li>
        </ul>
        <h3>Photo Credits</h3>
        <p>Most of the photos on this site are CC images from Flickr and are copyrighted by their respective owners. The header image is the work of <a href="http://www.flickr.com/photos/mike_miley/4281332394/">Mike Miley</a>. The rotating images are from <a href="http://www.flickr.com/photos/rj3/">&ldquo;Cowboy&rdquo; Ben Alman</a>, <a href="http://www.flickr.com/photos/arielflesler/">Ariel Flesler</a>, <a href="http://www.flickr.com/photos/mikeyboydotcom/">Mike</a> and <a href="http://twitter.com/lukeb">Luke Brookhart</a>.</p>
      </section>
      <hr class="clear"/>
      <section id="jquery_footer" class="container_24">
        <p id="jq-copyright">&copy; 2010 <a href="http://jquery.org/">The jQuery Project</a></p>
        <p id="jq-hosting">Sponsored by <a href="http://mediatemple.net" class="jq-mediaTemple">Media Temple</a> and <a href="http://jquery.org/sponsors">others</a>.</p>

        <nav id="footer_navigation">
         <ul>
             <li class="alpha"><a href="/2010/sf-bay-area/">Home</a></li>
             <li><a href="/2010/sf-bay-area/schedule/">Schedule</a></li>
             <li><a href="/2010/sf-bay-area/speakers/">Speakers</a></li>
             <li class="omega"><a href="/2010/sf-bay-area/sponsors/">Sponsors</a></li>
         </ul>
        </nav>
      </section>
    </footer>
    <hr class="clear"/>
  </div>
  <script>
  var _gaq = _gaq || []; _gaq.push(['_setAccount', 'UA-1076265-1']); _gaq.push(['_trackPageview']); _gaq.push(['_setDomainName', '.jquery.org']);
  (function() {var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
  ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
  (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(ga);})();
  </script>
</body>
</html>
