<?php /* custom for wp-job-manager */
	// order the jobs by date descending.
	function modify_wpjm_query_args( $query_args ) {
		if ( ! empty( $query_args['orderby'] ) ) {
			$query_args['orderby'] = array(
				'menu_order' => 'DESC',
				'date'       => 'DESC',
			);
		}
		return $query_args;
	}

add_filter( 'job_manager_get_listings', 'modify_wpjm_query_args' ); 