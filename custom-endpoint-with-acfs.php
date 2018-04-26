<?php

///  Create a custom endpoint and output custom ACF's attached to that post/page in JSON
///  Custom Post Type 'Partners' Custom Endpoint | http://one20.com/one20/wp-json/v1/mobile_partners
///
function mobile_partners_get( $request_data ) {

	// setup query argument
	$args = array(
		'post_type' => 'partners',
		'posts_per_page' => '100',
	);

	// get posts
	$posts = get_posts($args);

	// add custom field data to posts array

	$data = array();
	foreach ($posts as $post) {

		$acf = get_fields($post->ID);

		if ($acf["mobile_show_in_menu"] == true) {
			//error_log( print_r( $acf["mobile_show_in_menu"],true ) );
			$item = new stdClass(); // needed to get rid of error
			$item->acf = new stdClass(); // needed to get rid of error
			//	$item->acf->mobile_show_in_menu = $acf["mobile_show_in_menu"];
			if ( ! empty($acf["mobile_active_for"])) {
				$item->acf->mobile_active_for = $acf["mobile_active_for"];
			} else {
				$item->acf->mobile_active_for = [];
			}
			if ( ! empty($acf["mobile_image_ios_pdf"])) {
				$item->acf->mobile_image_ios_pdf = $acf["mobile_image_ios_pdf"];
			} else {
				$item->acf->mobile_image_ios_pdf = '';
			}
			if ( ! empty($acf["mobile_image_android_png"])) {
				$item->acf->mobile_image_android_png = $acf["mobile_image_android_png"];
			} else {
				$item->acf->mobile_image_android_png = '';
			}
			// $item->acf->mobile_image_adroid_svg = ($acf["mobile_image_adroid_svg"] !== false) ? $acf["mobile_image_adroid_svg"] : '';
			$item->acf->mobile_url_link = $acf["mobile_url_link"];
			$item->acf->mobile_section = $acf["mobile_section"];
			$item->acf->mobile_label = $acf["mobile_label"];
			$item->acf->mobile_event_name = $acf["mobile_event_name"];
			$item->acf->mobile_sequence_number = $acf["mobile_sequence_number"];
			$data[] = $item;
		}

	}
	return new WP_REST_Response( $data, 200 );
}

// Register the endpoint
add_action( 'rest_api_init', function () {
//	error_log( print_r('called register routes' ),true );
	register_rest_route( 'one20/v1', '/mobile_partners', array(
			'methods' => 'GET',
			'callback' => 'mobile_partners_get',
		)
	);
});