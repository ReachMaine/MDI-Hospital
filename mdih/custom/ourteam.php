<?php /* customizations for our_team plugin by woothemes */


// add toogle around content if there is some.
add_filter('woothemes_our_team_content', 'reach_filter_content', 9, 2);
function reach_filter_content($in_content, $post) {
  $out_content = "";
  if ($in_content) {
      $out_content .= '[cws-widget type=accs title="" toggle=1  items=1][item type=accs title="More Info"]';
      $out_content .= $in_content;
      $out_content .= '[/item][/cws-widget]';
      //$out_content .= do_shortcode($out_content);
  }
  return $out_content;
}

// trying to get the images to be rectangle since all cropped nicely to rectangle.
add_image_size('team-member-pic', 250, 350, false);

add_filter('woothemes_get_our_team_args', 'reach_img_size');
function reach_img_size($args) {
  $args['size'] = array(250, 350);
  return ($args);
}
