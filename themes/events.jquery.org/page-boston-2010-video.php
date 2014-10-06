<?php
/*
 * Template Name: jQuery Boston 2010 Video
 */

$noSidebar = true;
$speakers = array(
	"paul-irish" => array(
		"name" => "Paul Irish",
		"title"  => "Inaugural State of HTML5",
		"slides" => "http://stateofhtml5.appspot.com/",
	),

	"doug-neiner" => array(
		"name" => "Dough Neiner",
		"title"  => "Contextual jQuery",
		"slides" => "http://www.slideshare.net/dcneiner/contextual-jquery",
	),

	"brian-crescimanno" => array(
		"name" => "Brian Crescimanno",
		"title"  => "jQuery UI and HTML5 Video Play Nice",
		"slides" => "http://www.slideshare.net/bcrescimanno/j-query-conference-2010",
	),

	"john-resig" => array(
		"name" => "John Resig",
		"title"  => "Keynote - jQuery Core & jQuery Mobile",
		"slides" => "http://www.slideshare.net/jeresig/jquery-keynote-fall-2010",
		"note"   => "Despite equipment testing, the primary audio connection failed right before John gave the keynote this year. The audio quality of this video is severly degraded. However, without this video, we may never have known what John Resig would sound like as a robot."
	),

	"todd-parker" => array(
		"name" => "Todd Parker",
		"title"  => "Mobile UI",
		"slides" => "http://www.slideshare.net/ToddParker1/jquery-mobile-overview-boston",
	),

	"mike-hostetler" => array(
		"name" => "Mike Hostetler",
		"title"  => "jQuery('#knowledge').appendTo('#you');",
		"slides" => "http://www.slideshare.net/mikehostetler/jqueryknowledgeappendtoyou",
	),

	"john-hann" => array(
		"name" => "John Hann",
		"title"  => "OOCSS for JavaScript Pirates",
		"slides" => "http://www.slideshare.net/unscriptable/oocss-for-javascript-pirates-jqcon-boston",
	),

	"elijah-manor" => array(
		"name" => "Elijah Manor",
		"title"  => "Introduction to jQuery UI",
		"slides" => "http://elijahmanor.com/talks/jq-ui-intro.html#slide1",
	),

	"mike-taylor" => array(
		"name" => "Mike Taylor",
		"title"  => "Is these a bug?, or how to contribute to the jQuery project through better bug reporting.",
		"slides" => "http://www.slideshare.net/miketaylr/is-these-a-bug",
	),

	"paul-elliott" => array(
		"name" => "Paul Elliott",
		"title"  => "TDD your jQuery Plugins",
		"slides" => "http://www.slideshare.net/paulelliott99/tdd-your-jquery-plugins",
	),

	"brian-moschel" => array(
		"name" => "Brian Moschel",
		"title"  => "A Crash Course in JavaScript Application Testing with FuncUnit",
		"slides" => "http://www.slideshare.net/moschel/funcunit",
	),

	"menno-van-slooten" => array(
		"name" => "Menno van Slooten",
		"title"  => "Rapid testing, rapid development - Increase your development speed by reducing your feedback loops",
		"slides" => "http://www.slideshare.net/mennovanslooten/rapid-testing-rapid-development",
	),

	"rebecca-murphey" => array(
		"name" => "Rebecca Murphey",
		"title"  => "Beyond the DOM: Functionality-Focused Code Organization",
		"slides" => "http://www.slideshare.net/rmurphey/functionality-basedorg",
	),

	"richard-worth" => array(
		"name" => "Richard Worth",
		"title"  => "Keynote - jQuery UI",
	),

	"alex-sexton" => array(
		"name" => "Alex Sexton",
		"title"  => "jQuery's Best Friends",
		"slides" => "http://jquerysbestfriends.com/",
	),

	"garann-means" => array(
		"name" => "Garann Means",
		"title"  => "Using templates to achieve awesomer architechture",
		"slides" => "http://www.slideshare.net/garann/using-templates-to-achieve-awesomer-architecture",
	),

	"boaz-sender" => array(
		"name" => "Boaz Sender",
		"title"  => "Exploding the internet with jQuery and Couch DB",
		"slides" => "http://htmlten.com/slides/jqcon",
	),

	"ben-vinegar" => array(
		"name" => "Ben Vinegar",
		"title"  => "Building Distributed JavaScript Widgets with jQuery",
		"slides" => "http://www.slideshare.net/benvinegar/building-distributed-java-script-widgets-sat",
	),

	"chad-pytel" => array(
		"name" => "Chad Pytel",
		"title"  => "Mobile Web Applications with jQuery",
		"slides" => "http://www.slideshare.net/ChadPytel/mobile-web-applications-with-jquery",
	),

	"karl-swedberg" => array(
		"name" => "Karl Swedberg",
		"title"  => "jQuery Effects: Beyond the basics",
		"slides" => "http://pres.learningjquery.com/jqcon2010/",
	),

	"chris-bannon" => array(
		"name" => "Chris Bannon",
		"title"  => "Theming jQuery UI like an Aristocrat",
		"slides" => "http://www.slideshare.net/banzor/theming-j-query-ui-like-an-aristocrat",
	),

	"yehuda-katz" => array(
		"name" => "Yehuda Katz",
		"title"  => "Moving to jQuery",
	),

	"dave-artz" => array(
		"name" => "Dave Artz",
		"title"  => "jQuery in the [Aol.] Enterprise",
		"slides" => "http://www.slideshare.net/daveartz/jquery-in-the-aol-enterprise",
	),

	"thomas-reynolds" => array(
		"name" => "Thomas Reynolds",
		"title"  => "Organizing Code with JavaScriptMVC",
		"slides" => "http://www.slideshare.net/tdreyno/jqconf",
	),

	"jonathan-sharp" => array(
		"name" => "Jonathan Sharp",
		"title"  => "App in a Browser",
		"slides" => "http://www.slideshare.net/jdsharp/app-in-a-browser",
	),

	"scott-gonzalez" => array(
		"name" => "Scott González",
		"title"  => "Building Extensible Widgets",
		"slides" => "http://nemikor.com/presentations/Building-Extensible-Widgets.pdf",
	),

	"ralph-whitbeck" => array(
		"name" => "Ralph Whitbeck",
		"title"  => "Getting Involved",
		"slides" => "http://www.slideshare.net/rwhitbeck/jquery-conference-2010-getting-involved",
	),

	"matt-kelly" => array(
		"name" => "Matt Kelly",
		"title"  => "Super Awesome Interactions with jQuery",
		"slides" => "http://www.zurb.com/jqconf",
	),

	"discussion-panel" => array(
		"name" => "Discussion Panel",
		"title"  => "jQuery Team Panel Discussion",
	)
);

$speaker_slug = isset( $_GET[ 'talk' ] ) ? $_GET[ 'talk' ] : '';

if ( !$speaker_slug || !isset( $speakers[ $speaker_slug ] ) ) {
	header( 'Location: /2010/boston/video/' );
	die();
}

$speaker = $speakers[ $speaker_slug ];

the_post();
include( 'boston-2010/header.php' );
?>

<script src="/resources/2010/boston/js/video-js/video.js"></script>
<link rel="stylesheet" href="/resources/2010/boston/js/video-js/video-js.css" type="text/css" media="screen">
<style media="screen">
  .call_out_special h1 { line-height: 120%; margin-top: 30px;}
  .call_out_special h1 em { white-space: nowrap; padding-right: 10px; }
  #video-container { height: 500px;}
  #video-container > p { padding-top: 5px; text-align: center;}
  .sub-details em { font-style: italic;}
  .all-videos {
    position: absolute;
    left: -20px;
    top: -35px;
    text-decoration: none; float: left; padding: 5px 10px; background: #0a49ae; color: #fff !important; font-weight: normal;
    -webkit-border-radius: 5px;
       -moz-border-radius: 5px;
            border-radius: 5px;
  }
  .all-videos:hover { background: #111;}

  .details-note { color: #551111; border: none; font-size: 1.1em;}
</style>
<section class="call_out call_out_special">
  <article>
    <header>
      <hgroup>
        <a href="/2010/boston/video/" class="all-videos">&larr; All Videos</a>
        <h1><?php echo $speaker['title']; ?> <em><?php echo $speaker['name']; ?></em></h1>
        <h2 class="sub-details">
          The conference videos are primarily of the presenter and the slides are <strong>not</strong> integrated into the video.
          <?php if(isset($speaker['slides'])): ?>
            As annoying as it might be, please <strong><a href="<?php echo $speaker['slides']; ?>" target="_blank">grab the presentation here</a></strong> and follow along as you watch. <em>Next time we shoot video, we'll plan to integrate the slides.</em>
          <?php else: ?>
            No slides are currently availible for this presentation.
          <?php endif; ?>
        </h2>
        <?php if (isset($speaker['note'])): ?>
          <h2 class="sub-details details-note"><? echo $speaker['note']; ?></h2>
        <?php endif ?>
      </hgroup>
    </header>
    <div id="video-container">
      <div class="video-js-box">
          <?php
            $url =  "http://content.jquery.com/2010/boston/$speaker_slug/$speaker_slug";
            $poster = $url . '-poster.jpg';
            $mp4 = $url . '-desktop.m4v';
            $mov = $url . '.mov';
            $ogg = $url . '-desktop.ogv'; ?>
          <!-- Using the Video for Everybody Embed Code http://camendesign.com/code/video_for_everybody -->
          <video class="video-js" width="852" height="480" controls preload poster="<?php echo $poster; ?>">
            <source src="<?php echo $mov ?>" type='video/mp4' />
            <source src="<?php echo $mp4 ?>" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"' />
            <!-- Flash Fallback. Use any flash video player here. Make sure to keep the vjs-flash-fallback class. -->
            <object class="vjs-flash-fallback" width="852" height="480" type="application/x-shockwave-flash"
              data="http://releases.flowplayer.org/swf/flowplayer-3.2.1.swf">
              <param name="movie" value="http://releases.flowplayer.org/swf/flowplayer-3.2.1.swf" />
              <param name="allowfullscreen" value="true" />
              <param name="flashvars" value='config={"playlist":["<?php echo $poster ?>", {"url": "<?php echo $mp4 ?>","autoPlay":false,"autoBuffering":true}]}' />
              <!-- Image Fallback. Typically the same as the poster image. -->
              <img src="<?php echo $poster ?>" width="852" height="480" alt="Poster Image"
                title="No video playback capabilities." />
            </object>
          </video>
          <!-- Download links provided for devices that can't play video in the browser. -->
          <p class="vjs-no-video"><strong>Download Video:</strong>
            <a href="<?php echo $mp4 ?>">MP4</a>,
            <!-- Support VideoJS by keeping this link. -->
            <a href="http://videojs.com">HTML5 Video Player</a> by VideoJS
          </p>
        </div>
        <!-- End VideoJS -->
        <p><em>This video is licensed under a <a href="http://creativecommons.org/licenses/by/3.0/">Creative Commons Attribution 3.0 Unported License.</a>.</em></p>
    </div>
    <span class="clear"></span>
  </article>
</section>

<script>
  $("video").VideoJS();
</script>

<?php include( 'boston-2010/footer.php' ); ?>
