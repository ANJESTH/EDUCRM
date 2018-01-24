<?php

if ( ! function_exists( 'educrm_authenticate_app_pages' ) ) {

	function educrm_authenticate_app_pages( $agent = false ) {

		$authentic_users = array( 'consultancy' );

		if ( $agent ) {
			array_push( $authentic_users, 'agent' );
		}

		if ( ! in_array( educrm_get_current_user_type(), $authentic_users ) ) {
			wp_safe_redirect( home_url() );
		}

		$consultancy = educrm_get_the_consultancy();

		if ( $consultancy instanceof WP_Error ) {
			wp_die( 'This user is not associated with consultancy user.' );
			exit;
		}

	}
}

if ( ! function_exists( 'educrm_get_the_consultancy' ) ) {
	/**
	 *  Get consultancy post of the current logged in user
	 */
	function educrm_get_the_consultancy() {
		global $educrm_cache;
		if ( ! empty( $educrm_cache['current_consultancy'] ) ) {
			return $educrm_cache['current_consultancy'];
		}

		if ( 'consultancy' == educrm_get_current_user_type() ) {
			$consultancy = get_post( educrm_get_current_profile_id() );
		} elseif ( 'agent' == educrm_get_current_user_type() ) {
			$consultancy = get_post( get_post_meta( educrm_get_current_profile_id(), 'consultancy', true ) );
		} else {
			$consultancy = null;
		}

		if ( ! $consultancy instanceof WP_Post ) {
			return new WP_Error();
		}
		$educrm_cache['current_consultancy'] = $consultancy;
		return $consultancy;
	}
}

if ( ! function_exists( 'educrm_get_the_consultancy_id' ) ) {
	function educrm_get_the_consultancy_id() {
		$consultancy = educrm_get_the_consultancy();
		return ( $consultancy instanceof WP_Error ) ? null : $consultancy->ID;
	}
}

if ( ! function_exists( 'educrm_get_the_consultancy_title' ) ) {
	function educrm_get_the_consultancy_title() {
		$consultancy = educrm_get_the_consultancy();
		return ( $consultancy instanceof WP_Error ) ? null : $consultancy->post_title;
	}
}

if ( ! function_exists( 'educrm_get_current_profile_id' ) ) {
	/**
	 * Returns either consultancy_post_id or agent_post_id of,
	 * current logged in user
	 *
	 * @return [type] [description]
	 */
	function educrm_get_current_profile_id() {

		if ( ! is_user_logged_in() ) {
			return null;
		}

		if ( current_user_can( 'consultancy' ) ) {
			return get_user_meta( get_current_user_id(), 'consultancy_post_id', true );
		}

		if ( current_user_can( 'agent' ) ) {
			return get_user_meta( get_current_user_id(), 'agent_post_id', true );
		}

		return null;
	}
}

if ( ! function_exists( 'educrm_get_current_user_type' ) ) {
	/**
	 * Returns current user type
	 *
	 * @return [type] [description]
	 */
	function educrm_get_current_user_type() {

		if ( ! is_user_logged_in() ) {
			return null;
		}

		if ( current_user_can( 'consultancy' ) ) {
			return 'consultancy';
		}

		if ( current_user_can( 'agent' ) ) {
			return 'agent';
		}

		return null;
	}
}

if ( ! function_exists( 'sync_educrm_post_user' ) ) {
	/**
	 * Sync post type with its respective wp user.
	 *
	 * @param  [type] $consultancy_id consultancy post id
	 * @return [type]                 [description]
	 */
	function sync_educrm_post_user( $post_id, $post_type, $userdata ) {

		$user_id = get_post_meta( $post_id, "{$post_type}_user_id", true );
		$wp_user = get_user_by( 'ID', $user_id );

		// create user if not exists.
		if ( empty( $wp_user ) ) {

			$sync_user_id = educrm_create_posttype_user(
				array_merge( $userdata,
					array(
						'post_type' => $post_type, // "consultancy" or "agent"
						'post_id' => $post_id,  // consultancy_id or agent_id
					)
				)
			);

		} // update user
		else {

			$sync_user_id = educrm_update_posttype_user(
				array_merge( $userdata,
					array(
						'user_id' => $user_id,// consultancy_user_id or agent_user_id
					)
				)
			);

		}

		return $sync_user_id;
	}
}

if ( ! function_exists( 'educrm_update_posttype_user' ) ) {

	/**
	 * Updates user details
	 *
	 * @param  array $explicit_args [description]
	 * @return user_id|WP_Error
	 */
	function educrm_update_posttype_user( $explicit_args = array() ) {

		extract( $explicit_args );

		$userdata = array(
			'ID' => $user_id,
			'user_login' => $user_login,
			'user_email' => $user_email,
			'user_pass' => $user_pass,
			'first_name' => $first_name,
			'last_name' => $last_name,
		);

		$user_id = wp_update_user( $userdata );

		if ( $user_id instanceof WP_Error ) {
			wp_die( $user_id->get_error_message() );
		}

		// to change user_Login
		global $wpdb;
		$wpdb->update( $wpdb->users, array( 'user_login' => $user_email ), array( 'ID' => $user_id ) );

		return $user_id;

	}
}

if ( ! function_exists( 'educrm_create_posttype_user' ) ) {
	/**
	 * Creates post type users
	 *
	 * @param  array $explicit_args [description]
	 * @return [type]                [description]
	 */
	function educrm_create_posttype_user( $explicit_args = array() ) {

		extract( $explicit_args );
		// NOW creating user
		$userdata = array(
			'user_login' => $user_email,
			'user_email' => $user_email,
			'user_pass' => $user_pass,
			'role' => 'agent',
			'meta_input' => array(
				'first_name' => $first_name,
				'last_name' => $last_name,
			),
		);

		$user_id = wp_insert_user( $userdata, true );

		if ( $user_id instanceof WP_Error ) {
			// Deletes agent post if user is not created.
			// wp_delete_post( $agent_id );
			wp_die( $user_id->get_error_message() );
		}

		// store consultancy/agent post id in agent user meta.
		update_user_meta( $user_id, "{$post_type}_post_id", $post_id );

		// store consultancy/agent user id in agent post meta.
		update_post_meta( $post_id, "{$post_type}_user_id", $user_id );

		return $user_id;
	}
}

if ( ! function_exists( 'check_wp_xss_validation' ) ) {

	add_filter( 'acf/update_value', 'my_kses_post', 10, 1 );

	function my_kses_post( $value ) {

		// is array
		if ( is_array( $value ) ) {

			return array_map( 'my_kses_post', $value );

		}

		// return
		return wp_kses_post( $value );

	}
}

if ( ! function_exists( 'educrm_change_all_course_status' ) ) {
	/**
	 * Updates courses status to Draft of the given institutions
	 * @param  [type] $institution_id [description]
	 * @param  [type] $consultancy_id [description]
	 * @return [type]                 [description]
	 */
	function educrm_change_all_course_status( $institution_id, $consultancy_id ) {

		$course_args = array(
			'post_type' => 'course',
			'post_status' => 'publish',
			'fields' => 'ids',
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key' => 'consultancy',
					'value' => $consultancy_id
				),
				array(
					'key' => 'institution',
					'value' => $institution_id
				)
			)
		);

		$course_ids = get_posts( $course_args );

		if ( ! empty( $course_ids ) ) {

			foreach( $course_ids as $course_id ) {

				$deleted = wp_delete_post( $course_id );

				if ( ! empty( $deleted ) ) {
					// Updates application status.
					educrm_change_all_application_status( $course_id, $consultancy_id );

				}

			}

		}

	}
}


if ( ! function_exists( 'educrm_change_all_application_status' ) ) {
	/**
	 * Updates courses status to Draft of the given institutions
	 * @param  [type] $institution_id [description]
	 * @param  [type] $consultancy_id [description]
	 * @return [type]                 [description]
	 */
	function educrm_change_all_application_status( $course_id, $consultancy_id ) {

		$application_args = array(
			'post_type' => 'application',
			'post_status' => 'publish',
			'fields' => 'ids',
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key' => 'consultancy',
					'value' => $consultancy_id
				),
				array(
					'key' => 'choosen_course',
					'value' => $course_id
				)
			)
		);

		$application_ids = get_posts( $application_args );

		if ( ! empty( $application_ids ) ) {

			foreach( $application_ids as $application_id ) {
				wp_delete_post( $application_id );
			}

		}

	}
}


// giving controls to consultancy user only
if ( 'consultancy' == educrm_get_current_user_type() ) {
	include( get_parent_theme_file_path( '/app/consultancy/controls.php' ) );
	include( get_parent_theme_file_path( '/app/agent/controls.php' ) );
	include( get_parent_theme_file_path( '/app/institutions/controls.php' ) );
	include( get_parent_theme_file_path( '/app/courses/controls.php' ) );
}
include( get_parent_theme_file_path( '/app/applications/controls.php' ) );
