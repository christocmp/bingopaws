<?php

add_action( 'admin_head', 'flytonic_customcode_dropdown' );
function flytonic_customcode_dropdown() {
    global $typenow;

    // only on Post Type: post and page
    if( ! in_array( $typenow, array( 'post', 'page' ) ) )
        return ;

    add_filter( 'mce_external_plugins', 'flytonic_register_customcode_dropdown' );
    // Add to line 1 form WP TinyMCE
    add_filter( 'mce_buttons', 'flytonic_add_customcode_dropdown' );
}

// inlcude the js for tinymce
function flytonic_register_customcode_dropdown( $plugin_array ) {

    $plugin_array['flytonic_shortcodes'] = get_template_directory_uri() . '/includes/shortcodes/editor-dropdown.js';
    // Print all plugin js path
    //var_dump( $plugin_array );
    return $plugin_array;
}

// Add the button key for address via JS
function flytonic_add_customcode_dropdown( $buttons ) {

    array_push( $buttons, 'flytonic_shortcodes_btn' );
    // Print all buttons
    //var_dump( $buttons );
    return $buttons;
}



function flytonic_register_customcode_dropdown2( $buttons ) {
   array_push( $buttons, "flytonic_shortcodes" );
   return $buttons;
}

function flytonic_add_customcode_dropdown2( $plugin_array ) {
   $plugin_array['flytonic_shortcodes'] = get_template_directory_uri() . '/includes/shortcodes/editor-dropdown.js';
   return $plugin_array;
}

function flytonic_customcode_dropdown2() {

   if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
      return;
   }

   if ( get_user_option('rich_editing') == 'true' ) {
      add_filter( 'mce_external_plugins', 'flytonic_add_customcode_dropdown2' );
      add_filter( 'mce_buttons', 'flytonic_register_customcode_dropdown2' );
   }

}

add_action('admin_head', 'flytonic_customcode_dropdown2');

