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

 if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Add new subscriber to Sendfox email list.
 * 
 * @since 1.0.0
 * @param ElementorPro\Modules\Forms\Registrars\Form_Actions_Registrar $form_actions_registrar
 * @return void
 */
function add_new_sendfox_form_action ($form_actions_registrar){

    include_once( __DIR__ .  '/form-actions/sendfox.php' );

    $form_actions_registrar->register( new Sendfox_Action_After_Submit() );

}
add_action( 'elementor_pro/forms/actions/register', 'add_new_sendfox_form_action' );
