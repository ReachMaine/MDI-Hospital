<?php
/*
add [mdih_services] shortcode
 and [mdih_staff] shortcode to show individualt staff member.
*/
//require_once (get_template_directory(). '/core/portfolio-cols.php'); // want to use these functions
function mdi_shortcode_services($attr) {
	extract(shortcode_atts(
		array(
			'filter' => '',
			'open' => '',
	), $attr));
	$incs = !empty($filter) ? explode(',', $filter) : array();
	$include = array();
	foreach ($incs as $inc) {
		$include[] = get_term_by('slug', $inc, 'cws-staff-dept')->term_id;
	}
	$opened = !empty($open) ? explode(',', $open) : array();
	$terms_args = array('hide_empty' => 0,
						'parent' => 0,
						'include' => $include);
	$depts = get_terms('cws-staff-dept', $terms_args);
	$out = '';
	if (0 !== count($depts) ) {
		$out .= "<div class='services'>";
		foreach ($depts as $dept=>$v) {
			$open = in_array($v->slug, $opened);
			$morelink =  get_term_meta( $v->term_id ,'cws-clinico-dept-more', true );
			$out .= '<div class="accordion_section'. ( $open ? ' active' : '' ) .'">';
			$fa_widget = get_option_value( 'cws-clinico-dept-fa', $v->term_id );
			$fa_check = sprintf('<i class="service_icon fa fa-2x fa-%s"></i>',  !empty($fa_widget) ? $fa_widget : 'check');
			$out .= sprintf('<div id="cws-service-%s" class="accordion_title">%s%s<button class="accordion_icon"></i></div>', $v->term_id, $fa_check, $v->name);
			$out .= '<div class="accordion_content"' . ( $open ? '' : ' style="display:none;"' ) . '>';
			$out .= '<div class="details">';
				$title_img = wp_get_attachment_image_src(get_option_value( 'cws-clinico-dept-img', $v->term_id), 'full');
				if ($title_img) {
					$out .= '<div class="img_part"><img src="' . bfi_thumb($title_img[0], array( 'width'=>'191', 'height'=>'116' )) . '" alt=""></div>';
				}
				$out .= '<div class="description_part"><div class="description_part_container">';
				if (strlen($v->description) > 0) {
					// extract first line, wrap it with strong, the rest convert newlines into brakes
					$out .= '<div>';
					$ar = explode("\r\n", $v->description);
					if (count($ar) == 1) {
						$ar = explode("\n", $v->description);
						if (count($ar) == 1) {
							$ar = explode("\r", $v->description);
						}
					}
					if (count($ar) > 1) {
						$out .= '<div class="desc_title">' . $ar[0] . '</div>';
						array_splice($ar,0,1);
					}
					$out .= nl2br(implode("\r\n", $ar));
					//$out .= '<a class="eai-more more fa fa-long-arrow-right" href='.$morelink."></a>"; // zig
					$out .= '</div>';
				}
				$org = get_option_value( 'cws-clinico-dept-org', $v->term_id );
				if (strlen($org) > 0) {
					// extract first line, wrap it with strong, the rest convert newlines into brakes
					$out .= '<div>';
					$ar = explode("\r\n", $org);
					if (count($ar) == 1) {
						$ar = explode("\n", $org);
						if (count($ar) == 1) {
							$ar = explode("\r", $org);
						}
					}
					if (count($ar) > 1) {
						$out .= '<div class="desc_title">' . $ar[0] . '</div>';
						array_splice($ar,0,1);
					}
					$out .= nl2br(implode("\r\n", $ar));
					$out .= '</div>';
				}
				/*if (in_array( 'the-events-calendar/the-events-calendar.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ){
					$events = get_option_value( 'cws-clinico-dept-events', $v->term_id );
					if (!empty($events)) {
						$events = explode(',',$events);
						// displying events
						if ( count($events) > 0 ):
							$out .= '<div>';
							$out .= '<div class="events desc_title">' . __( 'Events: ', THEME_SLUG ) . '</div>';
							$homeurl = home_url();
							for( $i=0; $i<count($events); $i++ ) {
								if (!empty($events[$i])) {
									$ev_obj = get_term($events[$i], 'tribe_events_cat');
									if($ev_obj) {
										if ( $i!=0 ) $out .= ', ';
										$out .= '<a href="' . $homeurl . '/?tribe_events_cat=' . $ev_obj->slug . '">' . $ev_obj->name . '</a>';
									}
								}
							}
							$out .= '</div>';
						endif;
					}
				} */
				if ($morelink) {
					$out .= '<div>';
					$out .= '<a class="eai-more more fa fa-long-arrow-right" href="'.$morelink.'"></a>';
					$out .= '</div>';
				}
				$out .= '</div></div>';
			$out .= '</div>'; // end description part
			$out .= '<div class="row clearfix zigfix2">';
			$procedures = get_option_value( 'cws-clinico-dept-procedures', $v->term_id);
			if (!empty($procedures)) {
				$procedures = explode(',',$procedures);
				// displying procedures
				foreach($procedures as $proc) {
					$out .= '<div class="col">';
					$proc_obj = get_term($proc, 'cws-staff-procedures');
					if ($proc_obj) {
						$out .= '<div class="col_title">' . $proc_obj->name . '</div>';
						$out .= !empty($proc_obj->description) ? '<div class="desc_row">' . $proc_obj->description . '</div>' : '';
					}
					$proc_children = get_terms('cws-staff-procedures', 'hide_empty=0&parent=' . $proc);
					if (!empty($proc_children)) {
						foreach($proc_children as $proc_proc) {
							$proc_obj = get_term($proc_proc, 'cws-staff-procedures');
							$out .= '<div class="service_row"><dl>';
							$out .= '<dt><span>'. $proc_proc->name .'</span></dt>';
							if ($proc_proc->description) {// zig - dont output dd if nothing there.
								$out .= '<dd><span>'. $proc_proc->description .'</span></dd>';
							}
							$out .= '</dl></div>';
						}
					}

					$out .= '<a class="eai-moreptr" href="'.$morelink.'">Full list of services<i class="fa fa-angle-right"></i></a>';
					$out .= '</div>';
				}
			}  else {
				//$out .= "<p> no procedures</p>";// debugging
			}  // end if procedures
			if (($v->count > 0) && false) { // zig xout per oka

				$out .= '<div class="col">';
				$out .= '<div class="col_title">' . __('Doctors',THEME_SLUG) . '</div>'; // !!!

				$tax_query_arr = array(
							'taxonomy' => 'cws-staff-dept',
							'field' => 'slug',
							'terms' => array($v->slug)
						);

				$arr = array(
					'post_type' => 'staff',
					'ignore_sticky_posts' => true,
					'tax_query' => array( $tax_query_arr )
				);
				$p = new WP_Query($arr);
				if ($p->have_posts()) {
					$out2 = "";
					while ($p->have_posts()) : $p->the_post();
						$is_doc = false;
						$positions = wp_get_post_terms(get_the_ID(), 'cws-staff-position');
						$i = count($positions);
						$name = '';
						foreach ($positions as $pos=>$n) {
							$i--;
							if ($n->name == 'Physician') {
								$is_doc = true;
							}
							$name .= $i ? $n->name . ', ' : $n->name;
						}
						if ($is_doc) {
							$out .= '<div class="service_row"><dl><dt><span>';
							//$out .= get_the_title() . '</span></dt><dd><span>' . $name;
							//$out .= '</span></dd></dl></div>';
							$out .= get_the_title() . '</span></dt></dl></div>';
						} else {
							$out2 .= '<div class="service_row"><dl><dt><span>';
							//$out2 .= get_the_title() . '</span></dt><dd><span>' . $name;
							//$out2 .= '</span></dd></dl></div>';
							$out2 .= get_the_title() . '</span></dt></dl></div>';
						}
					endwhile;
					$out .= $out2;
				} else {
					//$out .= "<p> no posts for staff </p>";
				}
				// print 'full doctors list' ?
				$out .= '</div>';
			}
			if ($morelink) {
					$out .= '<div>';
					$out .= '<a class="href='.$morelink.">Read more</a>";
					$out .= '<a class="eai-more more fa fa-long-arrow-right" href='.$morelink."></a>";
					$out .= '</div>';
				}
			$out .= "</div></div></div>";
		}
		$out .= '</div>';
	}
	return $out;
}
add_shortcode('mdih_services', 'mdi_shortcode_services');

/* Short code to show individual staff as "archive listing"/excerpt.
*/
function mdi_shortcode_staff($attr) {
	extract(shortcode_atts(
		array(
			'staff' => '' // which staff member
	), $attr));
	$htmlreturn = '';

	// build the query
	$args = array('post_status' => 'publish',
					'post_type' => 'staff',
					'p' => $staff

					);
	$staffq = new WP_Query($args);
	if ($staffq->have_posts()) {
		$htmlreturn .= '<div class="staff_member">';
		while ($staffq->have_posts()) {
			$staffq->the_post();
				$link = get_permalink();
				$img = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );
				$output = ''; // start fresh
				$output .= '<div class="item mdih-staff">';
				$output .= 	  '<div class="wrapper"';
				$output .=        '<div class="pic">';
				$output .=            '<a class="zhover-effect" href="'.$link.'">'; // zig
				$output .=              '<img src="' . bfi_thumb($img[0], array('width' => 270, 'height' => 270) ) . '" alt="">';
				$output .=            '</a>';
				$output .=            '<div class="links">';

				$cws_stored_meta = get_post_meta( get_the_ID(), 'cws-staff');
				$cws_stored_meta = isset( $cws_stored_meta[0]['social'] ) ? $cws_stored_meta[0]['social'] : array();

				if (count($cws_stored_meta)>0) {
					foreach ($cws_stored_meta as $social_item) {
						$url = $social_item['cws-mb-socialgroup-url'];
						$title = $social_item['cws-mb-socialgroup-title'];
						$fa =  $social_item['cws-mb-socialgroup-fa'];
						$output .= '<a ' . ( $url ? "href='$url' " : "" ) . ( $title ? "title='$title' " : "" ) . ( $fa ? "class='fa fa-$fa' " : "" ) . '></a>';
					}
				}
				$outptu .=        '</div>';
				$output .= "</div></div><div class='team_member_info'>";

				$name = get_the_title();
				$output .= $name ?  "<a href='$link'><div class='name'>" . $name . "</div></a>" : "";

				$terms = wp_get_post_terms(get_the_ID(), 'cws-staff-position');
				if ( count($terms) ):
					$output .= "<div class='positions'>";
					$i = 0;
					foreach ($terms as $k=>$v) {
						$i++;
						$output .= $v->name;
						if ($i < count($terms)) {
							$output .= ', ';
						}
					}
					$output .= "</div>";
				endif;
				$output .= "</div></div>";
				$thumbnail_dims = cws_get_post_tmumbnail_dims( 'pinterest', 2, 'none' );
				$chars_count = cws_get_content_chars_count( 'pinterest', 4 );
				//$output .= build_portfolio_item (get_the_ID(), 4 , false, $thumbnail_dims, $chars_count, 'staff');

		} // end while posts
		$htmlreturn .= $output.'</div>'; // end staff member;
	}
	return $htmlreturn;
} // end shortcode mdih_staff

add_shortcode('mdih_staff', 'mdi_shortcode_staff');
