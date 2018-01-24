<?php

if ( ! function_exists( 'educrm_get_institutions' ) ) {

	function educrm_get_institutions( $consultancy_id ) {
		$institutions_args = array(
			'post_type' => 'institution',
			'posts_per_page' => -1,
			'post_status' => 'publish',
			'meta_key' => 'consultancy',
			'meta_value' => $consultancy_id,
		);

		return  get_posts( $institutions_args );
	}
}

add_filter( 'acf/pre_save_post', 'educrm_add_new_institution' );

function educrm_add_new_institution( $institution_id ) {

	if ( 'new_institution' != $institution_id ) {
		return $institution_id;
	}

	if ( 'agent' == educrm_get_current_user_type() ) {
		return false;
	}

	// bail early if editing in admin or an agent user type
	if ( is_admin() ) {
		return $institution_id;
	}

	$title      = $_POST['acf']['_post_title'];
	$address    = $_POST['acf']['field_589fc0244efcb'];
	$website    = $_POST['acf']['field_589fc02e4efcc'];
	$email      = $_POST['acf']['field_589fc0414efcd'];
	$phone      = $_POST['acf']['field_589fc0504efce'];

	// Create a new institution post
	$post = array(
	   'post_status'  => 'publish',
	   'post_title'  => $title,
	   'post_type'  => 'institution',
	);

	// insert the post
	$institution_post_id = wp_insert_post( $post, true );

	if ( $institution_post_id instanceof WP_Error ) {
		wp_die( $institution_post_id->get_error_message() );
	}

	$consultancy = educrm_get_the_consultancy();

	if ( $consultancy instanceof WP_Error ) {
		wp_delete_post( $institution_post_id );
		wp_die( 'You are not authorized to do this action.' );
	}

	// Assgin new institution post meta to consultancy.
	update_post_meta( $institution_post_id, 'consultancy', $consultancy->ID );

	return $institution_post_id;
}

if ( ! function_exists( 'educrm_maybe_add_institution' ) ) {
	function educrm_maybe_add_institution() {
		if ( ! empty( $_GET['action'] ) && 'new' == $_GET['action'] ) {
			include_once get_parent_theme_file_path( '/app/institutions/new.php' );
			exit;
		}
	}
}

if ( ! function_exists( 'educrm_maybe_delete_institution' ) ) {

	// Check if institution is deleteable
	function educrm_maybe_delete_institution() {

		if ( empty( $_GET['action'] ) || 'delete' != $_GET['action'] ) {
			return;
		}

		if ( empty( $_GET['institution_id'] ) && ! is_numeric( $_GET['institution_id'] ) ) {
			return;
		}

		// consultancy authority check.
		if ( 'consultancy' == educrm_get_current_user_type() && educrm_get_current_profile_id() != get_post_meta( $_GET['institution_id'], 'consultancy', true ) ) {
			return;
		}

		$deleted = wp_delete_post( $_GET['institution_id'] );

		if ( ! empty( $deleted ) ) {
			educrm_change_all_course_status( $_GET['institution_id'], educrm_get_the_consultancy_id() );
		}

	}
}
