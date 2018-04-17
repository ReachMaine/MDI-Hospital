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



	if ( ! function_exists( 'cws_output_media_part' ) ) {
		function cws_output_media_part ($blogtype, $pinterest_layout, $sb_block, $post = null){
			$pid = $post ? $post->ID : get_the_id();
			$post_format = get_post_format( $pid );
			$media_meta = $post_format ? get_post_meta( $pid, 'cws-mb' ) : get_post_meta( $pid, 'cws-portfolio' );
			$media_meta = isset($media_meta[0]) ? $media_meta[0] : null;
			$thumbnail = has_post_thumbnail( $pid ) ? wp_get_attachment_image_src(get_post_thumbnail_id( $pid ),'full') : null;
			$thumbnail = $thumbnail ? $thumbnail[0] : null;
			$single = ( isset($post) && $post_format != 'gallery' ) ? true : false;
			$thumbnail_dims = cws_get_post_tmumbnail_dims($blogtype, $pinterest_layout, $sb_block, $single);

			$some_media = false;
			ob_start();
			?>
				<div class="wrapper <?php echo ($post_format); ?>">
					<?php
						switch ($post_format) {
							case 'image':
								if ( $thumbnail ){
									$image_data = wp_get_attachment_metadata( get_post_thumbnail_id( $pid ) );
									$post_img = (($image_data['width'] < $thumbnail_dims['width'] ) && is_single()) ? $thumbnail : null;
									$emb_video = isset ($media_meta['cws-portfolio-video']) ? $media_meta['cws-portfolio-video'] : false;
									$emb_video = $emb_video ? "<a class='fancy fa fa-magic' data-fancybox-type='iframe' href='$emb_video'></a>" : "<a class='fancy fa fa-eye' href='$thumbnail'></a>";
									echo "<div class='pic'><img src='". ($post_img ? $post_img : bfi_thumb($thumbnail,$thumbnail_dims)) ."' alt='" . get_post_meta(get_post_thumbnail_id(), "_wp_attachment_image_alt", true) . "' />". ($post_img ? "</div>" : "<div class='hover-effect'></div><div class='links'>$emb_video</div></div>");
									$some_media = true;
								} break;
							case 'link':
								$link = $media_meta["cws-mb-link"];
								if ($thumbnail){
									?>
									<div class="pic">
										<img src="<?php echo bfi_thumb($thumbnail,$thumbnail_dims); $some_media = true; ?>" alt />
										<div class="hover-effect"></div>
										<?php echo $link ? "<div class='links'><a href='$link' class='fa fa-link' title='$link'></a></div>" : "<div class='links'><a href='$thumbnail' class='fancy fa fa-eye'></a></div>"; ?>
									</div>
									<?php
								}
								else{
									echo $link ? "<div class='link_url'>$link</div>" : "";
								}
								$some_media = true;
								break;
							case 'video':
								if ( $media_meta['cws-mb-video'] ){
									echo "<div class='video'>" . apply_filters('the_content',"[embed width='" . $thumbnail_dims['width'] . "']" . $media_meta['cws-mb-video'] . "[/embed]") . "</div>";
									$some_media = true;
								}
								break;
							case 'audio':
								if ( $media_meta['cws-mb-audio'] ){
									echo "<div class='audio'>" . apply_filters('the_content','[audio mp3="' . $media_meta['cws-mb-audio'] . '"]') . "</div>";
									$some_media = true;
								}
								break;
							case 'quote':
								if ($media_meta["cws-mb-quote"]){
									$text = $media_meta["cws-mb-quote"];
									$author = $media_meta["cws-mb-quote-author"];
									echo cws_testimonial_renderer( $thumbnail, $text, $author );
									$some_media = true;
								}
								break;
							case 'gallery':
								if ($media_meta["cws-mb-gallery"]){
									$gallery = $media_meta["cws-mb-gallery"];
									$match = preg_match_all("/\d+/",$gallery,$images);
									if ($match){
										$images = $images[0];
										$image_srcs = array();
										foreach ( $images as $image ){
											$image_src = wp_get_attachment_image_src($image,'full');
											$image_url = $image_src[0];
											array_push( $image_srcs, $image_url );

										}
										$some_media = count( $image_srcs ) > 0 ? true : false;
										$carousel = count($image_srcs) > 1 ? true : false;
										$gallery_id = uniqid( 'cws-gallery-' );
										echo  $carousel ? "<div class='gallery_carousel_nav'>
															<i class='prev fa fa-angle-left'></i>
															<i class='next fa fa-angle-right'></i>
															<div class='clearfix'></div></div>
															<div class='gallery_post_carousel'>" : "";
										foreach ( $image_srcs as $image_src ){
											?>
											<div class='pic'>
												<img src="<?php echo bfi_thumb($image_src,$thumbnail_dims); ?>" alt />
												<div class="hover-effect"></div>
												<div class="links">
													<a href="<?php echo $image_src; ?>" <?php echo $carousel ? " data-fancybox-group='$gallery_id'" : ""; ?> class="<?php echo $carousel ? 'fancy fancy_gallery fa fa-photo' : 'fancy fa fa-eye'; ?>" <?php echo $carousel ? "data-thumbnail='" . bfi_thumb( $image_src, array( 'width' => 50, 'height' => 50, 'crop' => true ) ) . "'" : ""; ?>></a>
												</div>
											</div>
											<?php
										}
										echo  $carousel ? "</div>" : "";
									}
								}
								break;
							default:
								if ( $thumbnail ){
									$image_data = wp_get_attachment_metadata( get_post_thumbnail_id( $pid ) );
									$post_img = (($image_data['width'] < $thumbnail_dims['width'] ) && is_single()) ? $thumbnail : null;
									if (is_single()) {
										echo "<div class='pic'><img src='". ($post_img ? $post_img : bfi_thumb($thumbnail,$thumbnail_dims)) ."' alt='" . get_post_meta(get_post_thumbnail_id(), "_wp_attachment_image_alt", true) . "' />";
									 	// display the caption 0 zig 16Apr18
										if (get_post(get_post_thumbnail_id($pid))->post_excerpt) { // search for if the image has caption added on it
									    echo '<div class="featured-image-caption">';
									         echo wp_kses_post(get_post(get_post_thumbnail_id($pid))->post_excerpt); // displays the image caption
									    echo '</div>';
										}
										echo "</div>";
									} else {
										echo "<div class='pic'><a href='" . get_the_permalink() ."'><img src='". ($post_img ? $post_img : bfi_thumb($thumbnail,$thumbnail_dims)) ."' alt='" . get_post_meta(get_post_thumbnail_id(), "_wp_attachment_image_alt", true) . "' /></a></div>";
									}
									$some_media = true;
								} break;
						}
					?>
				</div>
			<?php
			$some_media ? ob_end_flush() : ob_end_clean();
		}
	}

/* **** end custom taxonomy meta for cws-staff-dept ***** */
