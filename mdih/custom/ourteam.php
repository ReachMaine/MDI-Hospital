<?php /* customizations for our_team plugin by woothemes */


// add toogle around content in widget or shortcode.
add_filter( 'woothemes_our_team_item_template', 'reach_team_member_template' );
function reach_team_member_template( $tpl ) {
    // default template:  '<div itemscope itemtype="http://schema.org/Person" class="%%CLASS%%">%%AVATAR%% %%TITLE%% <div id="team-member-%%ID%%"  class="team-member-text" itemprop="description">%%TEXT%% %%AUTHOR%%</div></div>'
      $tpl = '<div itemscope itemtype="http://schema.org/Person" class="%%CLASS%%">%%AVATAR%% %%TITLE%% <div id="team-member-%%ID%%"  class="team-member-text" itemprop="description">';
      $tpl .= '[cws-widget type=accs title="" toggle=1  items=1][item type=accs title="See More"]';
      $tpl .= '%%TEXT%%';
      $tpl .= '[/item][/cws-widget]';
      $tpl .= '</div></div>';
      $tpl = do_shortcode($tpl);
    return $tpl;
}
