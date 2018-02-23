<?php /* custom for clinico */
	function job_post_output ($sb_block, $blogtype="large", $pinterest_layout="2", $post = null){
		$pid = $post ? $post->ID : get_the_id();
		if ( get_the_title() && get_post_format() != "aside" && !is_single() ){
			?>
				<div class="widget-title">
				<?php echo ( !isset($post) ? "<a href='" . get_permalink($pid) . "'>" : "" ) . get_the_title() . ( !isset($post) ? "</a>" : "" ); ?>
				</div>
			<?php
		}

		/* if ( get_post_type($pid) == 'post'):
			?>
			<div class="date clearfix">
				<?php if (get_comments_number() > 0 ) : ?>
				<i class="fa fa-comment">
					<a href="<?php comments_link(); ?>">
					<span><?php echo comments_number('0','1','%') ?></span>
					</a>
				</i>
				<?php endif; ?>
				<?php the_time(get_option('date_format')); ?>
			</div>
			<?php
		endif; */
		//cws_output_media_part($blogtype, $pinterest_layout, $sb_block, $post);
		$content = "";
		if (null != $post) {
			$content .= apply_filters('the_content', get_the_content($post->ID));
		} else {
			$chars_count = cws_get_content_chars_count( $blogtype, $pinterest_layout );
			$content .= cws_post_content_output( $chars_count, $blogtype );
		}
		echo $content;
		if ( get_post_type($pid) == 'post' ):
			echo "<div class='cats'>" . __("Posted", THEME_SLUG);
			$categories = get_the_category($pid);
			$show_author = cws_get_option("blog_author");
			$tags = wp_get_post_tags($pid);
			if ( !empty($categories) || $show_author || !empty($tags) ){
				if ( !empty($categories) ){
					echo " " . __("in", THEME_SLUG) . " ";
					for($i=0; $i<count($categories); $i++) {
						echo "<a href='" . get_category_link($categories[$i]->cat_ID) . "'>" . $categories[$i]->name . "</a>";
						echo $i<count($categories)-1 ? ", " : "";
					}
				}
				if ( $show_author ){
					echo " " . __("by", THEME_SLUG) . " ";
					$author = get_the_author();
					echo !empty($author) ? $author : "";
				}
				if ( !empty($tags) ){
					//echo "";
					echo get_the_tag_list(' | Tags: ', ', ');
					/*for ($i=0; $i<count($tags) ;$i++) {
						echo "<a href='" . get_tag_link($tags[$i]->term_id) . "'>" . $tags[$i]->name . "</a>";
						echo $i<count($tags)-1 ? ", " : "";
					}*/
				}
			}
			echo ( ($blogtype != 'pinterest') && (!is_single()) ) ? "<a href='" . get_permalink($pid) . "' class='more fa fa-long-arrow-right'></a>" : "";
			echo "</div>";
		endif;
	}

/* *** custom taxonomy meta for cws-staff-dept ****/
	// display the more link to taxonomy for cws-staff-dept ADD
	add_action( 'cws-staff-dept_add_form_fields', 'add_staffmore_meta', 10, 2 );
	function add_staffmore_meta($taxonomy) {
			?>
			<div class="form-field">
				<label for="cws-clinico-dept-more"><?php _e('More Link', THEME_SLUG)?></label>
				<textarea name="cws-clinico-dept-more" id="cws-clinico-dept-more" class="postform" rows="1" cols="40"></textarea>
			</div>
			<?php
	}

	// save the term meta when create the taxonomy - ADD
	add_action( 'created_cws-staff-dept', 'save_staffmore_meta', 10, 2 );
	function save_staffmore_meta( $term_id, $tt_id ){
	    if( isset( $_POST['cws-clinico-dept-more']  ) && '' !== $_POST['cws-clinico-dept-more']  ){
	        $moretxt =  $_POST['cws-clinico-dept-more'] ;
	        add_term_meta( $term_id, 'cws-clinico-dept-more', $moretxt, true );
	    }
	}

	//display  the field for update the term meta - UPDATE
	add_action( 'cws-staff-dept_edit_form_fields', 'edit_staffmore_meta', 10, 2 );
	function edit_staffmore_meta( $term, $taxonomy ){

    // get current value
     $morelink = get_term_meta( $term->term_id, 'cws-clinico-dept-more', true );

    ?>
			<tr class="form-field">
					<th scope="row" valign="top">
						<label for="cws-clinico-dept-more"><?php _e('More Link', THEME_SLUG)?></label>
					</th><td>
						<textarea name="cws-clinico-dept-more" id="cws-clinico-dept-more" class="postform" rows="1" cols="40"><?php echo $morelink; ?></textarea>
					</td>
				</tr>
			<?php
		}

	// save the value for edited taxonomy meta
	add_action( 'edited_cws-staff-dept', 'update_staffmore_meta', 10, 2 );
	function update_staffmore_meta( $term_id, $tt_id ){
			if( isset( $_POST['cws-clinico-dept-more']  ) && '' !== $_POST['cws-clinico-dept-more']  ){
				 $moretxt =  $_POST['cws-clinico-dept-more'];
				 update_term_meta( $term_id, 'cws-clinico-dept-more', $moretxt );
		 } 
	}



/* **** end custom taxonomy meta for cws-staff-dept ***** */
