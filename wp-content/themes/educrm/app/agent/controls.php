<?php

if ( ! function_exists( 'educrm_get_agents' ) ) {

	function educrm_get_agents( $consultancy_id ) {
		$agents_args = array(
			'post_type' => 'agent',
			'posts_per_page' => -1,
			'post_status' => 'publish',
			'meta_key' => 'consultancy',
			'meta_value' => $consultancy_id,
		);

		return  get_posts( $agents_args );
	}
}

add_filter( 'acf/pre_save_post', 'educrm_add_new_agent' );

function educrm_add_new_agent( $agent_id ) {

	if ( 'new_agent' != $agent_id ) {
		return $agent_id;
	}

	// bail early if editing in admin
	if ( is_admin() ) {
		return $agent_id;
	}

	$user_email = $_POST['acf']['field_589fbd08aa823'];
	$user_pass  = $_POST['acf']['field_58a4256b41d6e'];

	$first_name = $_POST['acf']['field_589fa0a8aa821'];
	$last_name  = $_POST['acf']['field_589fbcffaa822'];

	// Create a new agent post
	$post = array(
		'post_status'  => 'publish',
		'post_title'  => "{$first_name} {$last_name}",
		'post_type'  => 'agent',
	);

	// insert the post
	$agent_post_id = wp_insert_post( $post, true );

	if ( $agent_post_id instanceof WP_Error ) {
		wp_die( $agent_post_id->get_error_message() );
	}

	// Assgin new agent post meta to consultancy.
	update_post_meta( $agent_post_id, 'consultancy', educrm_get_current_profile_id() );

	// Now syncing agent post type with eqv. wp user
	$userdata = array(
		'user_login' => $user_email,
		'user_email' => $user_email,
		'user_pass' => $user_pass,
		'first_name' => $first_name,
		'last_name' => $last_name,
	);

	$sync_agent_user_id = sync_educrm_post_user( $agent_post_id, 'agent', $userdata );

	return $agent_post_id;
}

if ( ! function_exists( 'sync_agent_posttype_user' ) ) {

	add_action( 'acf/save_post', 'sync_agent_posttype_user' );

	/**
	 * Admin action for updating agent post type.
	 * Syncs agent post type to eqv. wp user
	 *
	 * @param  [type] $agent_id [description]
	 * @return int agent_post_id
	 */
	function sync_agent_posttype_user( $agent_id ) {

		if ( ! is_admin() ) {
			return;
		}

		if ( 'agent' != $_POST['post_type'] ||  wp_is_post_revision( $agent_id ) ) {
			return;
		}

		$new_email  = $_POST['acf']['field_589fbd08aa823'];
		$new_pass   = $_POST['acf']['field_58a4256b41d6e'];

		$first_name = $_POST['acf']['field_589fa0a8aa821'];
		$last_name  = $_POST['acf']['field_589fbcffaa822'];

		// Now syncing agent post type with eqv. wp user
		$userdata = array(
			'user_login' => $new_email,
			'user_email' => $new_email,
			'user_pass' => $new_pass,
			'first_name' => $first_name,
			'last_name' => $last_name,
		);

		$sync_agent_user_id = sync_educrm_post_user( $agent_id, 'agent', $userdata );

	}
}

if ( ! function_exists( 'educrm_maybe_add_agent' ) ) {
	function educrm_maybe_add_agent() {
		if ( ! empty( $_GET['action'] ) && 'new' == $_GET['action'] ) {
			include_once get_parent_theme_file_path( '/app/agent/new.php' );
			exit;
		}
	}
}

if ( ! function_exists( 'educrm_maybe_delete_agent' ) ) {

	// Check if agent is deleteable
	function educrm_maybe_delete_agent() {

		if ( empty( $_GET['action'] ) || 'delete' != $_GET['action'] ) {
			return;
		}

		if ( empty( $_GET['agent_id'] ) && ! is_numeric( $_POST['agent_id'] ) ) {
			return;
		}

		educrm_delete_agent( $_GET['agent_id'] );

	}
}


if ( ! function_exists( 'educrm_delete_agent' ) ) {
	/**
	 * Deletes agent and associated user
	 *
	 * @param  [type] $agent_id [description]
	 * @return null
	 */
	function educrm_delete_agent( $agent_id ) {

		if ( educrm_get_current_profile_id() != get_post_meta( $agent_id, 'consultancy', true ) ) {
			return;
		}

		wp_delete_post( $agent_id );

		$agent_users = get_users(
			array(
				'meta_key' => 'agent_post_id',
				'meta_value' => $agent_id,
			)
		);

		// Just to delete user from frontend.
		require_once( ABSPATH . 'wp-admin/includes/user.php' );
		if ( ! empty( $agent_users ) ) {
			foreach ( $agent_users as $key => $agent ) {
				wp_delete_user( $agent->ID );
			}
		}
	}
}
