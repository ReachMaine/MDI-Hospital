<?php /* custom for woocommerce */
	function mdih_woo_archive() {
		// remove ordering ddl at top of archive page
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
		// remove result count at top of page of archive page
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
	} 

	add_filter( 'woocommerce_page_title', 'bryce_change_wc_page_title' );
	function bryce_change_wc_page_title( $title ) {

		if ( is_search() ) {
			$title = sprintf( __( 'Search Results: &ldquo;%s&rdquo;', 'woocommerce' ), get_search_query() );
			if ( get_query_var( 'paged' ) ) {
				$title .= sprintf( __( '&nbsp;&ndash; Page %s', 'woocommerce' ), get_query_var( 'paged' ) );
			}
		} 
		if (is_product_category()) {
			$title = " MDI Shop";
		}

		return $title;
		
	}