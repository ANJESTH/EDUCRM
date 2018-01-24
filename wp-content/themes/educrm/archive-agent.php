<?php
if ( 'consultancy' == educrm_get_current_user_type() ) {
	include_once get_parent_theme_file_path( '/app/agent/list.php' );
} else {
	include_once get_parent_theme_file_path( '404.php' );
}
