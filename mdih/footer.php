<?php
/* 3march19 zig - accessibility widget code */ 
	// $gen_sets = theme_get_option('general', 'gen_sets');
?>
	<!--Start Footer-->
	<?php
		global $sidebars_widgets;
		if ( (!is_404()) && (!empty($post)) ) {
			$cws_stored_meta = get_post_meta( $post->ID, 'cws-mb' );
			if (isset( $cws_stored_meta[0]['cws-mb-sb_foot_override'] )) {
				$footer_sb_top = $cws_stored_meta[0]['cws-mb-footer-sidebar-top'];
				$footer_sb_bottom = $cws_stored_meta[0]['cws-mb-footer-sidebar-bottom'];
			} else {
				$footer_sb_top = cws_get_option('footer-sidebar-top');
				$footer_sb_bottom = cws_get_option('footer-sidebar-bottom');
			}
		} else {
			$footer_sb_top = cws_get_option('footer-sidebar-top');
			$footer_sb_bottom = cws_get_option('footer-sidebar-bottom');
		}
	?>
	<footer class="page_footer">
		<div id="scrollup"><i class='fa fa-angle-double-up'></i></div>
		<?php
		$footer_section_class = "footer_part";
		$sidebar_area_class = "footer_sidebar_area";
		if ($footer_sb_top){
		 	echo "<div class='footer-top-part $footer_section_class'><div class='container'><div class='footer_sb_container'><div class='$sidebar_area_class'>";
		 	dynamic_sidebar($footer_sb_top);
		 	echo "</div></div></div></div>";
 		}
		?>
		<?php
		$ret = '';
		if ( is_plugin_active('sitepress-multilingual-cms/sitepress.php') ) {
			global $wpml_language_switcher;
			$slot = $wpml_language_switcher->get_slot( 'statics', 'footer' );
			$template = $slot->get_model();
			$ret = $slot->is_enabled();
		}

 		if ($footer_sb_bottom){
 		 	echo "<div class='footer-bottom-part $footer_section_class'>";
 		 		echo "<div class='container'><div class='$sidebar_area_class" . ( cws_is_wpml_active() ?  " with_flags" : "" ) . " clearfix'>";
					$class_wpml = '';
					if(isset($template['template']) && !empty($template['template'])){
						if($template['template'] == 'wpml-legacy-vertical-list'){
							$class_wpml = 'wpml_language_switch lang_bar '. $template['template'];
						}
						else{
							$class_wpml = 'wpml_language_switch horizontal_bar '.$template['template'];
						}
					}else{
						$class_wpml = 'lang_bar';
					}

		 		 	if ( cws_is_wpml_active() && !empty($ret) ){
		 		 		echo "<div class='" . esc_attr($class_wpml) . "'>";
		 		 			do_action( 'wpml_footer_language_selector');
		 		 		echo "</div>";
		 		 	}
 		 			dynamic_sidebar($footer_sb_bottom);
 		 			echo "</div>";
 		 		echo "</div>";
 		 	echo "</div>";
 		}
 		?>
	</footer>
	<!--End Footer-->
	<?php
		// Google Analytics' code
		$ga_event = cws_get_option('ga-event-tracking');
		echo !empty($ga_event) ? '<script type="text/javascript">' . $ga_event . '</script>' : '';
		$boxed_layout = ('0' != cws_get_option('boxed-layout') ) ? 'boxed' : '';
		echo $boxed_layout ? "</div>" : "";
		wp_footer();
	?>
	</body>
	<?php /* accessibility widget code */ ?>
	<script type="text/javascript">
			var _userway_config = {
			// uncomment the following line to override default position
			 position: '1',
			// uncomment the following line to override default size (values: small, large)
			// size: 'large',
			// uncomment the following line to override default language (e.g., fr, de, es, he, nl, etc.)
			// language: null,
			// uncomment the following line to override color set via widget
			// color: 'null',
			// uncomment the following line to override type set via widget(1=man, 2=chair, 3=eye)
			// type: '2',
			account: 'xbounfqXOr'
			};
	</script>
<script type="text/javascript" src="https://cdn.userway.org/widget.js"></script>
</html>
