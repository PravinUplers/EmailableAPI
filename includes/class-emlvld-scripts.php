<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Scripts Class
 *
 * Handles adding scripts functionality to the admin pages
 * as well as the front pages.
 *
 * @package Emailable
 * @since 1.0.0
 */
if( !class_exists('Emlvld_Scripts') ) {
	
	class Emlvld_Scripts {

		//class constructor
		function __construct()
		{
			
		}
		
		/**
		 * Enqueue Scripts on Admin Side
		 * 
		 * @package Emailable
		 * @since 1.0.0
		 */
		public function emlvld_admin_scripts(){
			wp_register_style( 'emlvld-admin-style', EMLVLD_URL.'includes/css/emlvld-admin.css', '1.0.0');
			wp_enqueue_style( 'emlvld-admin-style' );
		}
		
		/**
		 * Adding Hooks
		 *
		 * Adding hooks for the styles and scripts.
		 *
		 * @package Emailable
		 * @since 1.0.0
		 */
		function add_hooks(){
			//add admin scripts
			add_action('admin_enqueue_scripts', array($this, 'emlvld_admin_scripts'));
		}
	}
}