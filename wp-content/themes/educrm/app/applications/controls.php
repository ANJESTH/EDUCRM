<?php
if ( ! function_exists( 'educrm_get_applications' ) ) {

	function educrm_get_applications( $consultancy_id ) {
		$agent_id = ( 'agent' === educrm_get_current_user_type() ) ? educrm_get_current_profile_id() : null;

		$applications_args = array(
			'post_type' => 'application',
			'posts_per_page' => -1,
			'post_status' => 'publish',
		);
		// If consultancy login.
		if ( is_null( $agent_id ) ) {
			$applications_args = array_merge( $applications_args , array( 'meta_key' => 'consultancy', 'meta_value' => $consultancy_id ) );
		} else { // If agent is logged in
			$applications_args = array_merge( $applications_args , array( 'meta_key' => 'added_by_agent_id', 'meta_value' => $agent_id ) );
		}
		return  get_posts( $applications_args );
	}
}

add_filter( 'acf/pre_save_post', 'educrm_add_new_application' );

function educrm_add_new_application( $application_id ) {

	if ( 'new_application' != $application_id ) {
		return $application_id;
	}

	// bail early if editing in admin
	if ( is_admin() ) {
		return $application_id;
	}

	// 'field_58a1af7f70e07', //applicant_name
	// 'field_58a1af8a70e08', //applicant_email
	// 'field_58a1af9670e09', //applicant_phone
	// 'field_58a1af9c70e0a', //applicant_academic_qualificatin
	// 'field_58a1afa770e0b', //applicant_academic_score
	// 'field_58a1b09170e0c', //applicant_english_languate
	// 'field_58a1b0d470e0d', //applicant_english_test_score,
	// 'field_58a1b0f970e0e', // applied_to_institute
	// 'field_58a1b11070e0f', // choosen_application
	// 'field_58a1b39266378', // application_status
	//
	$applicant_name      = $_POST['acf']['field_58a1af7f70e07'];
	$applicant_email     = $_POST['acf']['field_58a1af8a70e08'];

	// Create a new application post
	$post = array(
		'post_status'  => 'publish',
		'post_title'  => "{$applicant_name} [{$applicant_email}]",
		'post_type'  => 'application',
	);

	// insert the post
	$application_post_id = wp_insert_post( $post, true );

	if ( $application_post_id instanceof WP_Error ) {
		wp_die( $application_post_id->get_error_message() );
	}

	$consultancy = educrm_get_the_consultancy();

	if ( $consultancy instanceof WP_Error ) {
		wp_delete_post( $application_post_id );
		wp_die( 'You are not authorized to do this action.' );
	}

	// Assgin new application post meta to consultancy.
	update_post_meta( $application_post_id, 'consultancy', $consultancy->ID );

	if ( 'agent' == educrm_get_current_user_type() ) {
		update_post_meta( $application_post_id, 'added_by_agent_id', educrm_get_current_profile_id() );
	}

	return $application_post_id;
}

if ( ! function_exists( 'educrm_maybe_add_application' ) ) {
	function educrm_maybe_add_application() {
		if ( ! empty( $_GET['action'] ) && 'new' == $_GET['action'] ) {
			include_once get_parent_theme_file_path( '/app/applications/new.php' );
			exit;
		}
	}
}

if ( ! function_exists( 'educrm_maybe_delete_application' ) ) {

	// Check if application is deleteable
	function educrm_maybe_delete_application() {

		if ( empty( $_GET['action'] ) || 'delete' != $_GET['action'] ) {
			return;
		}

		if ( empty( $_GET['application_id'] ) && ! is_numeric( $_POST['application_id'] ) ) {
			return;
		}

		if ( educrm_get_current_profile_id() != get_post_meta( $_GET['application_id'], 'consultancy', true ) ) {
			return;
		}

		wp_delete_post( $_GET['application_id'] );

	}
}

if ( ! function_exists( 'educrm_send_email_on_status_update' ) ) {

	/**
	 * ACF form update from frontend.
	 * @var [type]
	 */

	add_filter( 'acf/update_value/name=application_status', 'educrm_send_email_on_status_update', 10, 3 );

	function educrm_send_email_on_status_update( $value, $application_id, $field ) {

		if ( is_admin() ) {
			return $value;
		}

		$old_status 		= get_post_meta( $application_id, 'application_status', true );
		$student_email 		= get_post_meta( $application_id, 'applicant_email', true );
		$applicant_name 	= get_post_meta( $application_id, 'applicant_name', true );
		$consultancy_name 	= educrm_get_the_consultancy_title();
		$course_name 		= ( ! empty( get_post_meta( $application_id, 'choosen_course', true ) ) ) ? get_post( get_post_meta( $application_id, 'choosen_course', true ) )->post_title : 'N/A';
		$application_status = ucfirst( $value );

		$message = null;

		if ( 'rejected' == $application_status ) {
			$status_greet = 'Sorry, ';
		} else {
			$status_greet = 'Congratulations, ';
		}

		$message = "<p> Hi {$applicant_name},</p>";
		$message .= "<p>Thank you for choosing our firm {$consultancy_name} for your overseas education venture. </p>";
		$message .= "<p>{$status_greet}Your application to <b>{$course_name}</b> institution is <b>{$application_status}</b>.";

		if ( 'rejected' != $application_status ) {
			$message .= "<p>Your application for overseas education is under supervision of our firm {$consultancy_name}. </p>";
			$message .= '<p>We will let you know as your application passes through different stages to success. </p>';
		}

		$message .= '<p>Thanks,</p>';
		$message .= '<p>Team EDUCRM</p>';

		send_html_email( $student_email, EDUCRM_SYSTEM_EMAIL, EDUCRM_SENDER_NAME, 'Your education application status changed.', $message );

		return $value;
	}
}

if ( ! function_exists( 'educrm_send_email_on_status_change' ) ) {

	add_action( 'save_post', 'educrm_send_email_on_status_change' );

	function educrm_send_email_on_status_change( $application_id ) {

		if ( wp_is_post_revision( $application_id ) || 'application' != get_post_type( $application_id ) ) {
			return false;
		}
		$old_status = get_post_meta( $application_id, 'application_status', true );

		//field_58a1b39266378 is application_status
		if ( ! empty( $_POST['acf']['field_58a1b39266378'] ) && ( 'draft' != $_POST['acf']['field_58a1b39266378'] || $old_status != $_POST['acf']['field_58a1b39266378'] ) ) {

			$student_email 		= get_post_meta( $application_id, 'applicant_email', true );
			$applicant_name 	= get_post_meta( $application_id, 'applicant_name', true );
			$consultancy_name 	= educrm_get_the_consultancy_title();
			$course_name 		= ( ! empty( get_post_meta( $application_id, 'choosen_course', true ) ) ) ? get_post( get_post_meta( $application_id, 'choosen_course', true ) )->post_title : 'N/A';
			$application_status = ucfirst( $_POST['acf']['field_58a1b39266378'] );

			$message = null;

			if ( 'rejected' == $application_status ) {
				$status_greet = 'Sorry, ';
			} else {
				$status_greet = 'Congratulations, ';
			}

			$message = "<p> Hi {$applicant_name},</p>";
			$message .= "<p>Thank you for choosing our firm {$consultancy_name} for your overseas education venture. </p>";
			$message .= "<p>{$status_greet}Your application to <b>{$course_name}</b> institution is <b>{$application_status}</b>.";

			if ( 'rejected' != $application_status ) {
				$message .= "<p>Your application for overseas education is under supervision of our firm {$consultancy_name}. </p>";
				$message .= '<p>We will let you know as your application passes through different stages to success. </p>';
			}

			$message .= '<p>Thanks,</p>';
			$message .= '<p>Team EDUCRM</p>';

			send_html_email( $student_email, EDUCRM_SYSTEM_EMAIL, EDUCRM_SENDER_NAME, 'Your education application status changed.', $message );
		}
	}
}


if ( ! function_exists( 'educrm_applied_institution_postobject_filter' ) ) {

	add_filter( 'acf/fields/post_object/query/name=applied_institution', 'educrm_applied_institution_postobject_filter', 10, 3 );
	/**
	 * Filter institute select dropdown items
	 *
	 * @param  [array] $args    [description]
	 * @param  [type]  $field   [description]
	 * @param  [type]  $post_id [description]
	 * @return array          [description]
	 */
	function educrm_applied_institution_postobject_filter( $args, $field, $post_id ) {

		$consultancy_id = educrm_get_the_consultancy_id();

		// since each institution is within a consultancy
		$args['meta_key'] = 'consultancy';
		$args['meta_value'] = $consultancy_id;
		// return
		return $args;

	}
}


if ( ! function_exists( 'educrm_choosen_course_postobject_filter' ) ) {

	add_filter( 'acf/fields/post_object/query/name=choosen_course', 'educrm_choosen_course_postobject_filter', 10, 3 );
	/**
	 * Filter institute select dropdown items
	 *
	 * @param  [array] $args    [description]
	 * @param  [type]  $field   [description]
	 * @param  [type]  $post_id [description]
	 * @return array          [description]
	 */
	function educrm_choosen_course_postobject_filter( $args, $field, $post_id ) {

		$institution_id = $_POST['institution_id'];

		if ( empty( $institution_id ) ) {
			return false;
		}

		$consultancy_id = educrm_get_the_consultancy_id();

		// Since each course is within an instution
		$args['meta_query'] = array(
			'relation' => 'AND',
			array(
				'key' => 'institution',
				'value' => $institution_id,
			),
			array(
				'key' => 'consultancy',
				'value' => $consultancy_id,
			),
		);
		// return
		return $args;

	}
}
