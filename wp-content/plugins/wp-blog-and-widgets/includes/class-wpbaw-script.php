<?php
/**
 * Script Class
 *
 * Handles the script and style functionality of plugin
 *
 * @package WP Blog and Widgets
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Wpbaw_Script {

	function __construct() {

		// Action to add style at front side
		add_action( 'wp_enqueue_scripts', array($this, 'wpbaw_front_style') );
	}

	/**
	 * Function to add style at front side
	 * 
	 * @package WP Blog and Widgets
	 * @since 1.0.0
	 */
	function wpbaw_front_style() {

		// Register Style
		wp_register_style( 'wpbaw-public-style', WPBAW_URL.'assets/css/wpbaw-public.css', array(), WPBAW_VERSION );
		wp_enqueue_style( 'wpbaw-public-style' );

	}
}

$wpbaw_script = new Wpbaw_Script();