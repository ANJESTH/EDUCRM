<?php

if ( ! function_exists( 'educrm_get_courses' ) ) {

	function educrm_get_courses( $consultancy_id ) {
		$courses_args = array(
			'post_type' => 'course',
			'posts_per_page' => -1,
			'post_status' => 'publish',
			'meta_key' => 'consultancy',
			'meta_value' => $consultancy_id,
		);

		return  get_posts( $courses_args );
	}
}

if ( ! function_exists( 'educrm_add_new_course' ) ) {

	add_filter( 'acf/pre_save_post', 'educrm_add_new_course' );

	function educrm_add_new_course( $course_id ) {

		if ( 'new_course' != $course_id  ) {
			return $course_id;
		}

		if ( 'agent' == educrm_get_current_user_type() ) {
			return false;
		}

			// bail early if editing in admin or an agent user type
		if ( is_admin() ) {
			return $course_id;
		}

		$title      	= $_POST['acf']['_post_title'];
		$course_code    = $_POST['acf']['field_589fc078e2772'];
		$faculty    	= $_POST['acf']['field_58a1ae8a0abfc'];
		$fee      		= $_POST['acf']['field_589fc088e2773'];
		$level      	= $_POST['acf']['field_589fc09be2774'];
		$duration      	= $_POST['acf']['field_589fc3c1e2775'];
		$institution    = $_POST['acf']['field_58a1aee322e63'];

		// Create a new course post
		$post = array(
		   'post_status'  => 'publish',
		   'post_title'  => $title,
		   'post_type'  => 'course',
		);

		// insert the post
		$course_post_id = wp_insert_post( $post, true );

		if ( $course_post_id instanceof WP_Error ) {
			wp_die( $course_post_id->get_error_message() );
		}

		$consultancy = educrm_get_the_consultancy();

		if ( $consultancy instanceof WP_Error ) {
			wp_delete_post( $course_post_id );
			wp_die( 'You are not authorized to do this action.' );
		}

		// Assgin new course post meta to consultancy.
		update_post_meta( $course_post_id, 'consultancy',  $consultancy->ID );

		return $course_post_id;
	}
}

if ( ! function_exists( 'educrm_maybe_add_course' ) ) {
	function educrm_maybe_add_course() {
		if ( ! empty( $_GET['action'] ) && 'new' == $_GET['action'] ) {
			include_once get_parent_theme_file_path( '/app/courses/new.php' );
			exit;
		}
	}
}


if ( ! function_exists( 'educrm_maybe_delete_course' ) ) {

	// Check if course is deleteable
	function educrm_maybe_delete_course() {

		if ( empty( $_GET['action'] ) || 'delete' != $_GET['action'] ) {
			return;
		}

		if ( empty( $_GET['course_id'] ) && ! is_numeric( $_POST['course_id'] ) ) {
			return;
		}

		if ( educrm_get_current_profile_id() != get_post_meta( $_GET['course_id'], 'consultancy', true ) ) {
			return;
		}

		$deleted = wp_delete_post( $_GET['course_id'] );

		if( ! empty( $deleted ) ) {
			// Delete all applications of this course
			educrm_change_all_application_status( $_GET['course_id'], educrm_get_the_consultancy_id() );

		}

	}
}

if ( ! function_exists( 'educrm_course_postobject_filter' ) ) {

	add_filter( 'acf/fields/post_object/query/name=institution', 'educrm_course_postobject_filter', 10, 3 );
	/**
	 * Filter institute select dropdown items
	 *
	 * @param  [array] $args    [description]
	 * @param  [type]  $field   [description]
	 * @param  [type]  $post_id [description]
	 * @return array          [description]
	 */
	function educrm_course_postobject_filter( $args, $field, $post_id ) {

		$consultancy_id = educrm_get_the_consultancy_id();
		// since courses are within consultancy
		$args['meta_key'] = 'consultancy';
		$args['meta_value'] = $consultancy_id;
		// return
		return $args;

	}
}
