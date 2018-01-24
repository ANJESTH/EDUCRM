<?php
/**
 * ACF extras
 */
// ACF local json file location
// https://www.advancedcustomfields.com/resources/local-json/

add_filter('acf/settings/save_json', 'twr_acf_save_json_path');

function twr_acf_save_json_path ( $path ) {
    // update path
    $path = get_stylesheet_directory() . '/inc/acf-fields-json';

    return $path;

}

add_filter('acf/settings/load_json', 'twr_acf_load_fields_from_json');

function twr_acf_load_fields_from_json( $paths ) {
    // remove original path (optional)
    unset($paths[0]);


    // append path
    $paths[] = get_stylesheet_directory() . '/inc/acf-fields-json';


    // return
    return $paths;

}
