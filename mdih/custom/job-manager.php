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

// use action to add text at bottom of every job listings
// so ronda doesnt have to copy & paste it every time.
add_action('single_job_listing_end', 'mdih_add_company_blur_to_bottom'); // this puts it after the apply now.
function mdih_add_company_blur_to_bottom () {
	echo '<div class="mdih-career-blurb">';
	echo 		'<p>MDI Hospital offers a competitive salary, medical/dental/life insurance, matching retirement plan, paid vacation and sick time, wellness program, tuition reimbursement, and continuing education benefits. </p>';
	echo    '<p>Equal Opportunity Employer/Protected Veterans/Individuals with Disabilities.</p>';
	echo '</div>';
}
