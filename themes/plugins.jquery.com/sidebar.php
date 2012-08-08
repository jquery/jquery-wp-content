<div class="plugin-metadata sidebar right">
  <h3><i class="icon-star"></i>New Plugins</h3>
  <?php jq_new_plugins(); ?>
  <h3><i class="icon-calendar"></i>Recent Updates</h3>
  <?php jq_updated_plugins(); ?>

  <h3><i class="icon-tags"></i>Popular Tags</h3>
  <ul>
  <?php
  $tags_args = array('orderby' => 'count', 'order' => 'DESC', 'number' => 10);
  $tags = get_tags( $tags_args );
  $tag_html = '';
  foreach ($tags as $tag){
    $tag_link = get_tag_link($tag->term_id);
    $tag_html .= '<li class="icon-caret-right">';
    $tag_html .= '<a href="' . $tag_link . '">';
      $tag_html .= $tag->name;
    $tag_html .= '</a>';
     $tag_html .= ' (';
    $tag_html .=  $tag->count;
     $tag_html .= ')';
    $tag_html .= '</li>';
  }

  echo $tag_html;
  ?>
  </ul>
</div><!--End Sidebar-->

