<?php

/**
 * Custom post types register.
 */
require get_parent_theme_file_path( '/inc/register-post-types.php' );
/**
 * ACF inclusion
 */
require get_parent_theme_file_path( '/inc/acf_inclusion.php' );
/**
 * ACF json field sync path.
 */
require get_parent_theme_file_path( '/inc/acf_save_json.php' );

add_filter( 'show_admin_bar', '__return_false' );

if ( ! function_exists( 'educrm_after_theme_setup' ) ) {

	add_action( 'after_setup_theme', 'educrm_after_theme_setup' );

	function educrm_after_theme_setup() {
		/**
		 * Enqueue scripts.
		 */
		add_action( 'wp_enqueue_scripts', 'educrm_theme_scripts' );
		/**
		 * Redirects frontend landing page to "account" page after logged in.
		 */
		 add_action(
			 'template_redirect', function () {
				if ( current_user_can( 'administrator' ) || current_user_can( 'super-admin' ) ) {
					wp_safe_redirect( admin_url() );
					exit;
				} elseif ( is_user_logged_in() && ( is_home() || is_front_page() ) ) {
					wp_safe_redirect( home_url( '/account/' ) );
					exit;
				}
			 }
		 );
		/**
		 * Include app script
		 */
		include_once 'app/app.php';

	}
}

if ( ! function_exists( 'educrm_admin_enqueue_scripts' ) ) {

	add_action( 'admin_enqueue_scripts', 'educrm_admin_enqueue_scripts' );

	function educrm_admin_enqueue_scripts() {

		wp_enqueue_script( 'educrm-acf-custom', get_theme_file_uri( '/assets/js/acf-customs.js' ), array( 'jquery' ), '', true );

	}
}

if ( ! function_exists( 'educrm_theme_scripts' ) ) {
	/**
	 * Enqueue scripts and styles.
	 */
	function educrm_theme_scripts() {

		wp_enqueue_style( 'educrm-style', get_stylesheet_uri() );

		wp_enqueue_style( 'educrm-bootstrap-css', get_theme_file_uri( '/assets/css/bootstrap.css' ) );

		wp_enqueue_style( 'google-fonts', 'http://fonts.googleapis.com/css?family=Roboto:400,300,700|Open+Sans:700' );

		wp_enqueue_script( 'educrm-bootstrap-script', get_theme_file_uri( '/assets/js/bootstrap.min.js' ), array( 'jquery' ), '', true );

		wp_enqueue_script( 'educrm-my-script', get_theme_file_uri( '/assets/js/my-scripts.js' ), array( 'jquery' ), '', true );

		wp_enqueue_script( 'educrm-acf-custom', get_theme_file_uri( '/assets/js/acf-customs.js' ), array( 'jquery' ), '', true );

	}
}

if ( ! function_exists( 'send_html_email' ) ) {

	function send_html_email( $to, $from_mail, $from_name, $subject, $message ) {

		$header = array();
		$header[] = 'MIME-Version: 1.0';
		$header[] = "From: {$from_name}<{$from_mail}>";

		/* Set message content type HTML*/
		$header[] = 'Content-type:text/html; charset=iso-8859-1';
		$header[] = 'Content-Transfer-Encoding: 7bit';

		if ( mail( $to, $subject, $message, implode( "\r\n", $header ) ) ) {
			return true;
		}
	}
}

if ( ! function_exists( 'educrm_add_custom_roles' ) ) {

	add_action( 'init', 'educrm_add_custom_roles' );
	function educrm_add_custom_roles() {

		add_role(
			'super-admin',
			'Super Admin',
			array(
				'read' => true,
				'edit_posts' => true,
				'delete_posts' => true,
			)
		);

		add_role(
			'consultancy',
			'Consultancy',
			array(
				'read' => true,
				'edit_posts' => true,
				'delete_posts' => true,
			)
		);

		add_role(
			'agent',
			'Agent',
			array(
				'read' => true,
				'delete_posts' => false,
			)
		);
	}
}

if ( ! function_exists( 'educrm_remove_admin_pages' ) ) {

	add_action( 'admin_menu', 'educrm_remove_admin_pages' );

	function educrm_remove_admin_pages() {

		if ( ! current_user_can( 'super-admin' ) ) {
			remove_menu_page( 'edit.php?post_type=acf-field-group' ); // Posts
			remove_menu_page( 'index.php' );                  // Dashboard
			remove_menu_page( 'jetpack' );                    // Jetpack*
			remove_menu_page( 'edit.php' );                   // Posts
			remove_menu_page( 'upload.php' );                 // Media
			remove_menu_page( 'edit.php?post_type=page' );    // Pages
			remove_menu_page( 'edit-comments.php' );          // Comments
			remove_menu_page( 'themes.php' );                 // Appearance
			remove_menu_page( 'plugins.php' );                // Plugins
			remove_menu_page( 'users.php' );                  // Users
			remove_menu_page( 'tools.php' );                  // Tools
			remove_menu_page( 'options-general.php' );        // Settings
		}

	}
}

if ( ! function_exists( 'educrm_remove_admin_nodes' ) ) {

	add_action( 'admin_bar_menu', 'educrm_remove_admin_nodes', 999 );

	function educrm_remove_admin_nodes( $wp_admin_bar ) {

		$wp_admin_bar->remove_node( 'wp-logo' );
		$wp_admin_bar->remove_node( 'comments' );
		$wp_admin_bar->remove_node( 'new-content' );
		$wp_admin_bar->remove_node( 'updates' );

	}
}

if ( ! function_exists( 'educrm_admin_landing_page' ) ) {

	add_action( 'load-index.php', 'educrm_admin_landing_page' );

	function educrm_admin_landing_page() {

		wp_safe_redirect( admin_url( 'edit.php?post_type=consultancy' ) );

	}
}

if ( ! function_exists( 'educrm_admin_dashboard_page' ) ) {

	add_filter( 'login_redirect', 'educrm_admin_dashboard_page', 10, 1 );

	function educrm_admin_dashboard_page( $url ) {

		if ( current_user_can( 'administrator' ) || current_user_can( 'super-admin' ) ) {
			return add_query_arg( 'post_type', 'consultancy', admin_url( 'edit.php' ) );
		} elseif ( in_array( educrm_get_current_user_type(), array( 'consultancy', 'agent' ) ) && is_user_logged_in() && ( is_home() || is_front_page() ) ) {
			return home_url( '/account/' );
		}

		return $url;
	}
}

if ( ! function_exists( 'educrm_user_logout_url' ) ) {

	add_filter( 'logout_url', 'educrm_user_logout_url', 10 , 2 );
	/**
	 * Logout redirects to specified url
	 *
	 * @return string
	 */
	function educrm_user_logout_url( $logout_url, $redirect_to ) {

		return add_query_arg( 'redirect_to', home_url(), $logout_url );

	}
}

if ( ! function_exists( 'educrm_remove_core_updates' ) ) {

	add_filter( 'pre_site_transient_update_core', 'remove_core_updates' );
	add_filter( 'pre_site_transient_update_plugins', 'remove_core_updates' );
	add_filter( 'pre_site_transient_update_themes', 'remove_core_updates' );
	/**
	 * Removes admin all nags and notices.
	 *
	 * @return [type] [description]
	 */
	function remove_core_updates() {

		global $wp_version;

		return (object) array(
		'last_checked' => time(),
		'version_checked' => $wp_version,
		);

	}
}

if ( ! function_exists( 'educrm_remove_update_menu' ) ) {

	add_action( 'admin_menu', 'educrm_remove_update_menu' );
	/**
	 * Removes admin page "updates" menu
	 *
	 * @return [type] [description]
	 */
	function educrm_remove_update_menu() {

		global $submenu;

		if ( isset( $submenu['index.php'][10] ) ) {
			unset( $submenu['index.php'][10] ); // Removes 'Updates'.
		}

	}
}

if ( ! function_exists( 'educrm_include_all_files' ) ) {
	/**
	 * Function to include post type helper files.
	 * "/custom-post-types-helper" folder contains cpt helper files
	 * eg. {post_type}.php
	 */
	function educrm_include_all_files( $folder_path = null ) {
		if ( empty( $folder_path ) ) { return;
		}

		foreach ( new DirectoryIterator( $folder_path ) as $fileinfo ) {

			if ( $fileinfo->isDot() ) { continue;
			}

			if ( 'php' === $fileinfo->getExtension() ) {
				include_once $folder_path . '/' . $fileinfo->getFilename();
			}
		}
	}
}

if ( ! function_exists( 'educrm_get_custom_pages' ) ) {
	/**
	 * Array of custom pages with name and template paths.
	 *
	 * @return [type] [description]
	 */
	function educrm_get_custom_pages() {

		return array(
			 'account' => array(
				'post_title'    => 'Account',
				'post_name'     => 'account',
				'template_path' => 'app/account/page-account.php',
			 ),
		);

	}
}

if ( ! function_exists( 'educrm_custom_template_paths' ) ) {

	add_filter( 'theme_page_templates', 'educrm_custom_template_paths', 10, 4 );
	/**
	 * Assigns custom templates paths for the provided custom pages
	 *
	 * @param  [type] $post_templates [description]
	 * @param  [type] $wp_theme       [description]
	 * @param  [type] $post           [description]
	 * @param  [type] $post_type      [description]
	 * @return [array]                [description]
	 */
	function educrm_custom_template_paths( $post_templates, $wp_theme, $post, $post_type ) {

		$custom_pages = educrm_custom_pages();

		foreach ( $custom_pages as $custom_page ) {

			if ( ! empty( $post_templates[ $custom_page['template_path'] ] ) ) {
				$post_templates[ $custom_page['template_path'] ] = $custom_page['post_title'];
			}
		}

		return $post_templates;
	}
}

if ( ! function_exists( 'educrm_create_custom_pages' ) ) {

	 add_action( 'init', 'educrm_create_custom_pages' );

	 /**
	  * Check if custom pages exist else create them.
	  */
	function educrm_create_custom_pages() {

		$custom_pages = educrm_get_custom_pages();

		foreach ( $custom_pages as $slug => $args ) {

			$page = get_page_by_path( $slug, OBJECT, 'page' );

			// page is missing create it
			if ( ! ( $page instanceof WP_Post ) ) {

				$args['post_type'] = 'page';
				$args['post_status'] = 'publish';

				$post_id = wp_insert_post( $args, true );

				// If page created and custom page has custom template_path assigned.
				if ( ! $post_id instanceof WP_Error && ! empty( $args['template_path'] ) ) {
					update_post_meta( $post_id, '_wp_page_template', $args['template_path'] );
					update_option( 'page_account_id', $post_id );
				}
			}
		}
	}
}

if ( ! function_exists( 'educrm_super_users' ) ) {

	add_action( 'init', 'educrm_super_users' );

	function educrm_super_users() {

		$current_user = wp_get_current_user();

		$super_admin_user_emails = array( 'anjesth17@gmail.com' );
		$administrator_user_email = array( 'anje12sth@gmail.com' );

		// Setting roles "super-admin" for users who are choosen to be super admin
		if ( $current_user instanceof WP_User && in_array( $current_user->user_email, $super_admin_user_emails, true ) ) {
			$current_user->set_role( 'super-admin' );
		}
		if ( $current_user instanceof WP_User && in_array( $current_user->user_email, $administrator_user_email, true ) ) {
			$current_user->set_role( 'administrator' );
		}

	}
}

if ( ! function_exists( 'educrm_change_email_from' ) ) {

	add_filter( 'wp_mail_from', 'new_mail_from' );
	add_filter( 'wp_mail_from_name', 'new_mail_from_name' );

	/**
	 * Changes default WP from email id.
	 *
	 * @param  [type] $old [description]
	 * @return [type]      [description]
	 */
	function new_mail_from( $old ) {
		return EDUCRM_SYSTEM_EMAIL;
	}

	/**
	 * Changes default WP from name
	 *
	 * @param  [type] $old [description]
	 * @return [type]      [description]
	 */
	function new_mail_from_name( $old ) {
		return EDUCRM_SENDER_NAME;
	}
}

add_action( 'init', function() {
	$educrm_cache = array();
	global $educrm_cache;
});
