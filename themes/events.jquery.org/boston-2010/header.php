<!DOCTYPE html class="no-js">
<html>
<head>
  <meta charset="utf-8">
  <title>jQuery Conference: Boston 2010</title>
  <link rel="stylesheet" href="/resources/2010/boston/css/layout-20101220.css" type="text/css">
  <link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/i/favicon.ico">
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
<body class="<?php echo $post->post_name === 'boston' ? 'home overview' : $post->post_name . ' sub'; ?>">
  <div id="wrapper">
    <header class="container_24" id="m-head">
      <a href="/2010/boston/" id="logo">jQuery Conference</a>
    </header>
    <section id="content" class="container_24">
      <nav id="primary">
        <ul>
          <li <?php if ( $post->post_name === 'boston' ) echo 'class="active"'; ?>><a href="/2010/boston/">Overview</a></li>
          <li <?php if ( $post->post_name === 'speakers' ) echo 'class="active"'; ?>><a href="/2010/boston/speakers/">Speakers</a></li>
          <li <?php if ( $post->post_name === 'schedule' ) echo 'class="active"'; ?>><a href="/2010/boston/schedule/">Schedule</a></li>
          <li <?php if ( $post->post_name === 'sponsors' ) echo 'class="active"'; ?>><a href="/2010/boston/sponsors/">Sponsors</a></li>
          <li <?php if ( $post->post_name === 'video' ) echo 'class="active"'; ?>><a href="/2010/boston/video/">Videos</a></li>
        </ul>
      </nav>

      <?php if ( !$noSidebar ) : ?>
      <div id="main-column">
      <?php endif; ?>