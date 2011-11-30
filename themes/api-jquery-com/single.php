<?php
$site = str_replace(".", "-", str_replace("https://", "", str_replace("http://", "", get_site_url())));
?>
<?php get_header(); ?>
                
  <!-- body -->
  <?php global $sidebar; ?>
  <div id="body" class="clearfix <?php echo $sidebar; ?>">
    
    <!-- inner -->
    <div class="inner">
    
<?php the_post() ?>
<?php
$title = the_title('','',false);
?>
<h2 class="title"><?php echo $title; ?></h2>
<?php

$xp = new XsltProcessor();

// create a DOM document and load the XSL stylesheet
$xsl = new DomDocument;
$xsl->load(get_stylesheet_directory().'/entries2html.xsl');

// import the XSL styelsheet into the XSLT process
$xp->importStylesheet($xsl);

// create a DOM document and load the XML data
$xml_doc = new DomDocument;
$xml_doc->loadXML(get_the_content());

// transform the XML into HTML using the XSL file
if ($html = $xp->transformToXML($xml_doc)) {
  $html = preg_replace('@</?html>@', '', $html);

  $htmlparts = explode('<h3>Example', $html);
  $firstpart = array_shift($htmlparts);

  $meta_values = get_post_meta($post->ID, 'Notemeta');
  $note_values = get_post_meta($post->ID, 'Note');
  $reus_vars = $reus_title;
  $notes = '';
  if (!empty($note_values) && function_exists('get_reus')) :

    if ( !empty($meta_values) ):
      foreach ($meta_values as $meta) {
        $meta_parts = explode('=', $meta);
        $meta_key = array_shift($meta_parts);
        $meta_val = implode('=', $meta_parts);
        $meta = $meta_key . '=' . str_replace('=', '%3D', $meta_val);

        $reus_vars .= '&' . $meta;
      }
    endif;

    $notes .= '<ul>';
    foreach ($note_values as $value) {
      $note = get_reus($value, $reus_vars);
      $note = str_replace('%3D', '=', $note);
      $notes .= '<li>' .  $note . '</li>';
    }
    $notes .= '</ul>';
  endif;


  // print out the html ...

  echo $firstpart;

  if ( count($htmlparts) ) :
    for ($i=0; $i < count($htmlparts); $i++) {
      if ($notes) :
        echo '<h3 id="notes-' . $i . '">Additional Notes:</h3>';
        echo '<div class="longdesc">' . $notes . '</div>';

      endif;
      echo '<h3>Example' . $htmlparts[$i];
    }

  endif;

} else {
  trigger_error('XSL transformation failed.', E_USER_ERROR);
} // if

?>

               
    </div>
    <!-- /inner -->
    
    <?php if($sidebar): ?>
        <?php get_sidebar(); ?>
    <?php endif; ?>
    
  </div>
  <!-- /body -->

<?php get_footer(); ?>
