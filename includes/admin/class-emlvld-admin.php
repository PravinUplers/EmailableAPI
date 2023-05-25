<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Admin Class
 *
 * Manage Admin Panel Class
 *
 * @package Emailable
 * @since 1.0.0
 */

if( !class_exists('Emlvld_Admin') ) {

	class Emlvld_Admin {

		public $scripts;

		//class constructor
		function __construct() {

			global $emlvld_scripts;

			$this->scripts = $emlvld_scripts;
		}

		/**
		 * Create menu page
		 *
		 * Adding required menu pages and submenu pages
		 * to manage the plugin functionality
		 *
		 * @package Emailable
		 * @since 1.0.0
		 */
		 public function emlvld_add_menu_page() {
			$emlvld_emailable = add_menu_page( esc_html__( 'Emailable', 'emlvld' ), esc_html__( 'Emailable', 'emlvld' ), 'manage_options', 'wp-emailable', array( $this, 'emlvld_setting_page'), plugin_dir_url( __FILE__ ) . 'images/emailable.png' );
		}

		/**
		 * Including File for Emailable Menu
		 *
		 * @package Emailable
		 * @since 1.0.0
		 */
		public function emlvld_setting_page() {
			include_once( EMLVLD_ADMIN_DIR . '/forms/emlvld-settings.php' );
		}

		/**
		 * Register settings for Emailable plugin
		 *
		 * @package Emailable
		 * @since 1.0.0
		 */
		public function emlvld_register_settings() {

			global $emlvld_api;

			register_setting( 'emlvld_settings', 'emlvld_settings', array($this, 'emlvld_settings_validate_options') );
		}

		/**
		 * Validate settings for Emailable plugin
		 *
		 * @package Emailable
		 * @since 1.0.0
		 */
		public function emlvld_settings_validate_options($input) {
			return $input;
		}

		/**
		 * Adding Hooks
		 *
		 * @package Emailable
		 * @since 1.0.0
		 */
		function add_hooks(){

			add_action( 'admin_menu', array( $this, 'emlvld_add_menu_page' ) );
			add_action( 'admin_init', array( $this, 'emlvld_register_settings') );
		}
	}
}