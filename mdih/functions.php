<?php
/*
	10May16 - zig add embed_oembed_html filter
*/
	require_once(get_stylesheet_directory().'/inc/mdih_shortcodes.php');
	require_once(get_stylesheet_directory().'/custom/woo.php');
	require_once(get_stylesheet_directory().'/custom/job-manager.php');
	require_once(get_stylesheet_directory().'/custom/clinico.php');
	require_once(get_stylesheet_directory().'/custom/ourteam.php');
	require_once(get_stylesheet_directory().'/custom/billpay.php');
	//require_once (get_template_directory(). '/core/portfolio-cols.php'); // want to use these functions
	add_action('after_setup_theme', ea_setup);
	/**  ea_setup
	*  init stuff that we have to init after the main theme is setup.
	*
	*/
	function ea_setup() {
	 /* do stuff ehre. */
	 	mdih_woo_archive();
	}

	//wp_enque_script('jquery'); // zig 15Jan16  jquery not loaded????

	add_filter('widget_text', 'do_shortcode'); // make text widget do shortcodes....

	/* image size for facebook */
	add_image_size( 'facebook_share', 470, 246, true );
	add_image_size('facebook_share_vert', 246, 470, true);
	add_filter('wpseo_opengraph_image_size', 'mysite_opengraph_image_size');
	function mysite_opengraph_image_size($val) {
		return 'facebook_share';
	}
	/* change text */
	if ( !function_exists('zig_change_theme_text') ){
		function zig_change_theme_text( $translated_text, $text, $domain ) {
			$theme_text_domain = 'clinico';
		    switch ( $translated_text ) {
	            case 'Select treatment' :
	                $translated_text = __( 'Select Specialty', $theme_text_domain);
	                break;
	            case 'treatments':
	            	$translated_text = __( 'Specialty', $theme_text_domain);
	            	break;
	            case 'Doctors':
	            	$translated_text = __( 'Providers', $theme_text_domain);
	            	break;

	            /* case 'Type here...':
	            	$translated_text = __( 'Search...', $theme_slug );
	            	break;
	            case 'BLOG CATEGORIES':
	            	//$translated_text = __( 'Found in', $theme_slug );
	            	break; */
	        }

	    	return $translated_text;
		}
		add_filter( 'gettext', 'zig_change_theme_text', 20, 3 );
	}
	// contact form 7 fallback for date field
	add_filter( 'wpcf7_support_html5_fallback', '__return_true' );

	// add search shortcode
	function mdih_searchform( $form ) {

	    $form = '<form role="search" method="get" id="searchform" class="search-form" action="' . home_url( '/' ) . '" >';
	    $form .= '<label >';
	    $form .= '<input type="search" class="search-field" Placeholder="Search..." value="' . get_search_query() . '" name="s" id="s" required/>';
	    $form .= '</label>';
		$form .= '<input type="submit" id="searchsubmit" class="search-submit" value="'. esc_attr_x('Search', 'submit button') .'" />';
	    $form .= '</form>';
	    	    return $form;
	}

	add_shortcode('mdih_search', 'mdih_searchform');

	if ( function_exists('register_sidebar') ){
		register_sidebar( array(
		'name' => 'Home Bottom widget',
		'id' => 'homebottom',
		'description' => 'Widget bottom of the home page',
		'before_widget' => '<div class="cws-widget"><div>',
		'after_widget' => '</div></div>',
		'before_title' => '<div class="widget-title"><span>',
		'after_title' => '</span></div>',
		));
	}
	/*****  change the login screen logo ****/
	function my_login_logo() { ?>
		<style type="text/css">
			body.login div#login h1 a {
				background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/images/mdih-admin-img.png);
				padding-bottom: 30px;
				background-size: contain;
				margin-left: 0px;
				margin-bottom: 0px;
				margin-right: 0px;
				height: 60px;
				width: 100%;
			}
		</style>
	<?php }
	add_action( 'login_enqueue_scripts', 'my_login_logo' );
	/*****  end custom login screen logo ****/

	add_filter( 'get_the_archive_title', function ( $title ) {

    if( is_category() ) {

        $title = single_cat_title( '', false );

    }
    $title = str_replace('Product Category:','',$title);
    $title = str_replace('Archives:','', $title);
    return $title;

});
/**
 * Temporary fix
 * OpenGraph add a og:image:width and og:image:height for FB async load of og:image issues
 *
 * https://github.com/Yoast/wordpress-seo/issues/2151
*  https://developers.facebook.com/docs/sharing/webmasters/optimizing#cachingimages
 */
function WPSEO_OpenGraph_Image() {
  global $wpseo_og;

  // will get a array with images
  $opengraph_images = new WPSEO_OpenGraph_Image( $wpseo_og->options );

  foreach ( $opengraph_images->get_images() as $img ) {
    // this block of code will first convert url of image to local path
    // for faster process of image sizes later
    $upload_dir = wp_upload_dir();
    $img_src = str_replace($upload_dir['url'], $upload_dir['path'], $img);
    $size = getimagesize($img_src);

    // display of this tags with Yoast SEO plugin
    $wpseo_og->og_tag( 'og:image:width', $size[0] );
    $wpseo_og->og_tag( 'og:image:height', $size[1] );
  }

}
if(class_exists('WPSEO_OpenGraph_Image')) {
  add_filter("wpseo_opengraph", "WPSEO_OpenGraph_Image");
}

// filter to wrap embedded videos (youtube) in div ss.t. we can style it
add_filter('embed_oembed_html', 'wrap_embed_with_div', 10, 3);
function wrap_embed_with_div($html, $url, $attr) {
        return '<div class="ea-responsive-container">'.$html.'</div>';
}

// WCAG complicance
// add the javascript for the expand_image stuff
	function ea_enqueue_styles() {
	    wp_enqueue_script('mdih_wcag', get_stylesheet_directory_uri().'/custom/mdih_wcag.js', array( 'jquery' ) );
	}
	add_action( 'wp_enqueue_scripts', 'ea_enqueue_styles' );
?>
