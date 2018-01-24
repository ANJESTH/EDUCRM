<?php
/**
 * Account registration and login
 *
 * @var [type]
 */
$login_message = $registration_message = '';
if ( isset( $_POST['user_login'] ) && 'true' == $_POST['user_login'] ) {

	$email 		= $_POST['email'];
	$password 	= $_POST['password'];
	$logged_in  = educrm_email_login( $email, $password );

	$login_message = $user->get_error_message();

} elseif (  isset( $_POST['user_registration'] ) && 'true' == $_POST['user_registration'] ) {

	// Consultants registration
	$first_name = $_POST['first_name'];
	$last_name = $_POST['last_name'];
	$email = $_POST['email'];
	$phone = $_POST['phone'];
	$address = $_POST['address'];
	$password = $_POST['password'];
	$consultancy_name = $_POST['consultancy_name'];

	if ( empty( $first_name ) ||
		empty( $last_name ) ||
		empty( $email ) ||
		empty( $phone ) ||
		empty( $address ) ||
		empty( $password ) ||
		empty( $consultancy_name )  ) {
		$registration_message = 'Please fill in all fields.';
	} else {
		// Insert users.
		$user_args = array(
			'user_login' => $email,
			'user_pass' => $password,
			'user_email' => $email,
			'first_name' => $first_name,
			'last_name' => $last_name,
			'role' => 'consultancy',
		);

		$consultancy_user_id = wp_insert_user( $user_args, true );

		if (  $consultancy_user_id instanceof WP_Error ) {
			$registration_message = str_replace( 'username', 'email', $consultancy_user_id->get_error_message() );
		} else {
			// Insert Consultancy details.
			$consultancy_args = array(
				'post_type' => 'consultancy',
				'post_status' => 'publish',
				'post_title' => $consultancy_name,
				'meta_input' => array(
					'first_name' => $first_name,
					'last_name' => $last_name,
					'email' => $email,
					'phone' => $phone,
					'address' => $address,
					'consultancy_user_id' => $consultancy_user_id,
				),
			);

			$consultancy_post_id = wp_insert_post( $consultancy_args, true );

			if ( ! $consultancy_post_id instanceof WP_Error ) {

				update_user_meta( $consultancy_user_id, 'consultancy_post_id', $consultancy_post_id );
				update_post_meta( $consultancy_post_id, 'consultancy_user_id', $consultancy_user_id );

				$registration_message = ' Registration successful, you can now login to access your account page.';
				$message = "<p>Hi, {$first_name} {$last_name},</p>";
				$message .= '<p>You have successfully created a Consultancy account on our site.</p>';
				$message .= '<p>You can log into the system using your email address and your password.</p>';
				$message .= '<p>Thanks,</p>';
				$message .= '<p>Team EDUCRM</p>';
				send_html_email( $email, 'no-reply@educrm.com.au', 'EduCRM', 'Registration Success!', $message );
				$_POST = array();

				educrm_email_login( $email, $password );

			} else {
				// Just to delete user from frontend.
		        require_once( ABSPATH . 'wp-admin/includes/user.php' );
				wp_delete_user( $consultancy_user_id );
				$registration_message = str_replace( 'username', 'email', $consultancy_user_id->get_error_message() );
			}
	 	}
	}
}

function educrm_email_login( $email, $password ) {

	$creds = array();
	$creds['user_login'] = $email;
	$creds['user_password'] = $password;
	$creds['remember'] = false;

	$user = wp_signon( $creds, false );

	if ( is_wp_error( $user ) ) {

		return $login_message = $user->get_error_message();

	} else {

		wp_set_current_user( $user->ID, $user->user_login );
		wp_set_auth_cookie( $user->ID );
		do_action( 'wp_login', $user->user_login );

		wp_safe_redirect( educrm_admin_dashboard_page( home_url() ) );
	}
}
