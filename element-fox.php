<?php
/*
 * Plugin Name:       Element Fox
 * Description:       Adds an after submit action to Elementor Pro Form that links to a Sendfox email list.
 * Version:           1.0.0
 * Requires at least: 6.3.2
 * Requires PHP:      8.2.8
 * Author:            Josh Bickford
 */

 /**
 * Elementor tested up to: 3.16.6
 * Elementor Pro tested up to: 3.16.2
 */

/* Main Plugin File */
...
function element_fox_activate() {

  add_option( 'Activated_Plugin', 'Plugin-Slug' );

  /* activation code here */
}
register_activation_hook( __FILE__, 'my_plugin_activate' );

function load_plugin() {

    if ( is_admin() && get_option( 'Activated_Plugin' ) == 'Plugin-Slug' ) {

        delete_option( 'Activated_Plugin' );

        /* do stuff once right after activation */
        // example: add_action( 'init', 'my_init_function' );
    }
}
add_action( 'admin_init', 'load_plugin' );