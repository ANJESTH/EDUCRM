<?php 
if( ! function_exists( 'sync_consultancy_posttype_user') ) {
    // Admin save
    add_action('acf/save_post', 'sync_consultancy_posttype_user');
    // Frontend post submit
    add_filter( 'acf/pre_save_post', 'sync_consultancy_posttype_user' );
    
    function sync_consultancy_posttype_user( $consultancy_id ) {
        
        if( 'consultancy' != get_post_type( $consultancy_id ) ||  wp_is_post_revision( $consultancy_id ) ) 
            return $consultancy_id;
        
        $new_email  = $_POST['acf']['field_589fa037521e1'];
        $new_pass   = $_POST['acf']['field_58a446c6c2f8c'];
        
        $first_name = $_POST['acf']['field_58a05595bfadf'];
        $last_name  = $_POST['acf']['field_58a0559bbfae0'];
        
        $userdata = array(
            'user_login' => $new_email,
            'user_email' => $new_email,
            'user_pass' => $new_pass,
            'first_name' => $first_name,
            'last_name' => $last_name,
        );
        
        $sync_consultancy_user_id = sync_educrm_post_user( $consultancy_id, 'consultancy', $userdata );
        
        // acf/pre_save_post needs post_id returned.
        return $consultancy_id;   
    } 
}