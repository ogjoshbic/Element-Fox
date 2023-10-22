<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor form Sendfox action.
 *
 * Custom Elementor form action which adds new subscriber to Sendfox after form submission.
 *
 * @since 1.0.0
 */

 class Sendfox_Action_After_Submit extends \ElementorPro\Modules\Forms\Classes\Action_Base { 

    /**
	 * Get action name.
	 *
	 * Retrieve Sendfox action name.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string
	 */
    public function get_name() {
		return 'sendfox';
	}

    /**
	 * Get action label.
	 *
	 * Retrieve Sendfox action label.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string
	 */
	public function get_label() {
		return esc_html__( 'Sendfox', 'element-fox' );
	}

    /**
	 * Register action controls.
	 *
	 * Add input fields to allow the user to customize the action settings.
	 *
	 * @since 1.0.0
	 * @access public
	 * @param \Elementor\Widget_Base $widget
	 */
	public function register_settings_section( $widget ) {

        $widget->start_controls_section(
			'section_sendfox',
			[
				'label' => esc_html__( 'Sendfox', 'element-fox' ),
				'condition' => [
					'submit_actions' => $this->get_name(),
				],
			]
		);

		$widget->add_control(
			'Sendfox_url',
			[
				'label' => esc_html__( 'Sendfox URL', 'element-fox' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => 'https://your_Sendfox_installation/',
				'description' => esc_html__( 'Enter the URL where you have Sendfox installed.', 'element-fox' ),
			]
		);

		$widget->add_control(
			'Sendfox_list',
			[
				'label' => esc_html__( 'Sendfox List ID', 'element-fox' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'description' => esc_html__( 'The list ID you want to subscribe a user to. This encrypted & hashed ID can be found under "View all lists" section.', 'element-fox' ),
			]
		);

		$widget->add_control(
			'Sendfox_email_field',
			[
				'label' => esc_html__( 'Email Field ID', 'element-fox' ),
				'type' => \Elementor\Controls_Manager::TEXT,
			]
		);

		$widget->add_control(
			'Sendfox_name_field',
			[
				'label' => esc_html__( 'Name Field ID', 'element-fox' ),
				'type' => \Elementor\Controls_Manager::TEXT,
			]
		);

		$widget->end_controls_section();

	}

	/**
	 * Run action.
	 *
	 * Runs the Sendfox action after form submission.
	 *
	 * @since 1.0.0
	 * @access public
	 * @param \ElementorPro\Modules\Forms\Classes\Form_Record  $record
	 * @param \ElementorPro\Modules\Forms\Classes\Ajax_Handler $ajax_handler
	 */
	public function run( $record, $ajax_handler ) {

		$settings = $record->get( 'form_settings' );

		//  Make sure that there is a Sendfox installation URL.
		if ( empty( $settings['Sendfox_url'] ) ) {
			return;
		}

		//  Make sure that there is a Sendfox list ID.
		if ( empty( $settings['Sendfox_list'] ) ) {
			return;
		}

		// Make sure that there is a Sendfox email field ID (required by Sendfox to subscribe users).
		if ( empty( $settings['Sendfox_email_field'] ) ) {
			return;
		}

		// Get submitted form data.
		$raw_fields = $record->get( 'fields' );

		// Normalize form data.
		$fields = [];
		foreach ( $raw_fields as $id => $field ) {
			$fields[ $id ] = $field['value'];
		}

		// Make sure the user entered an email (required by Sendfox to subscribe users).
		if ( empty( $fields[ $settings['Sendfox_email_field'] ] ) ) {
			return;
		}

		// Request data based on the param list at https://Sendfox.co/api
		$Sendfox_data = [
			'email' => $fields[ $settings['Sendfox_email_field'] ],
			'list' => $settings['Sendfox_list'],
			'ipaddress' => \ElementorPro\Core\Utils::get_client_ip(),
			'referrer' => isset( $_POST['referrer'] ) ? $_POST['referrer'] : '',
		];

		// Add name if field is mapped.
		if ( empty( $fields[ $settings['Sendfox_name_field'] ] ) ) {
			$Sendfox_data['name'] = $fields[ $settings['Sendfox_name_field'] ];
		}

		// Send the request.
		wp_remote_post(
			$settings['Sendfox_url'] . 'subscribe',
			[
				'body' => $Sendfox_data,
			]
		);

	}

	/**
	 * On export.
	 *
	 * Clears Sendfox form settings/fields when exporting.
	 *
	 * @since 1.0.0
	 * @access public
	 * @param array $element
	 */
	public function on_export( $element ) {

		unset(
			$element['Sendfox_url'],
			$element['Sendfox_list'],
			$element['Sendfox_email_field'],
			$element['Sendfox_name_field']
		);

		return $element;

	}

}
