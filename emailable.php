<?php

/**
 * Plugin Name: Emailable API
 * Plugin URI: https://wordpress.org/plugins/emailable
 * Description: This WP plugin aims to validate emails on all WP forms to make sure they actually exist before submission. It will use Emailable's API and must work on all the major WordPress forms.
 * Version: 1.0.0
 * Author: Emailable, LLC
 * Author URI: https://emailable.com/
 * License: GPL2 or later
 * Text Domain: emlvld
 * Tested up to: 5.8.2
 * Domain Path: languages
 */

/**
 * Basic plugin definitions
 *
 * @package Emailable
 * @since 1.0.0
 */
if( !defined( 'EMLVLD_DIR' ) ) {
	define( 'EMLVLD_DIR', dirname( __FILE__ ) );      // Plugin dir
}
if( !defined( 'EMLVLD_VERSION' ) ) {
	define( 'EMLVLD_VERSION', '1.0.0' );      // Plugin Version
}
if( !defined( 'EMLVLD_URL' ) ) {
	define( 'EMLVLD_URL', plugin_dir_url( __FILE__ ) );   // Plugin url
}
if( !defined( 'EMLVLD_INC_DIR' ) ) {
	define( 'EMLVLD_INC_DIR', EMLVLD_DIR.'/includes' );   // Plugin include dir
}
if( !defined( 'EMLVLD_INC_URL' ) ) {
	define( 'EMLVLD_INC_URL', EMLVLD_URL.'includes' );    // Plugin include url
}
if( !defined( 'EMLVLD_ADMIN_DIR' ) ) {
	define( 'EMLVLD_ADMIN_DIR', EMLVLD_INC_DIR.'/admin' );  // Plugin admin dir
}
if(!defined('EMLVLD_PREFIX')) {
	define('EMLVLD_PREFIX', 'emlvld'); // Plugin Prefix
}

/**
 * Load Text Domain
 *
 * This gets the plugin ready for translation.
 *
 * @package Emailable
 * @since 1.0.0
 */
load_plugin_textdomain( 'emlvld', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

/**
 * Activation Hook
 *
 * Register plugin activation hook.
 *
 * @package Emailable
 * @since 1.0.0
 */
register_activation_hook( __FILE__, 'emlvld_install' );

if( !function_exists('emlvld_install') ) {

	function emlvld_install() {

		global $emlvld_settings;
		if ( $emlvld_settings ) {

			$emlvld_settings = get_option( 'emlvld_settings');

			$emlvld_settings['states_deliverable'] = 1;
			$emlvld_settings['free_email'] = 1;
			$emlvld_settings['role_email'] = 1;

			update_option( 'emlvld_settings', $emlvld_settings );
		}
	}
}


// Global variables
global $emlvld_scripts, $emlvld_admin, $emlvld_settings, $emlvld_public, $emlvld_api, $emlvld_msg;

$emlvld_msg = array(
	'undeliverable' => esc_html__('Please enter another valid email address.','emlvld'),
	'disposable' => esc_html__('We do not accept disposable emails.','emlvld'),
	'unknown' => esc_html__('Please enter another valid email address','emlvld'),
	'free' => esc_html__('Please enter your business email address.','emlvld'),
	'role' => esc_html__('We do not accept a role or group email address.','emlvld'),
	'did_you_mean' => esc_html__('Did you mean [EMAIL]?','emlvld')
);

$emlvld_settings = get_option( 'emlvld_settings');

// Script class handles most of script functionalities of plugin
include_once( EMLVLD_INC_DIR.'/class-emlvld-scripts.php' );
$emlvld_scripts = new Emlvld_Scripts();
$emlvld_scripts->add_hooks();

// Admin class handles most of admin panel functionalities of plugin
include_once( EMLVLD_ADMIN_DIR.'/class-emlvld-admin.php' );
$emlvld_admin = new Emlvld_Admin();
$emlvld_admin->add_hooks();

//Api class handles most of Request functionalities of plugin
include_once( EMLVLD_INC_DIR.'/class-emlvld-api.php' );
$emlvld_api = new Emlvld_API();
$emlvld_settings['api_key'] = !empty($emlvld_settings['api_key']) ? esc_html($emlvld_settings['api_key']) : '';
$emlvld_api->set_apikey( $emlvld_settings['api_key'] );

//Public class handles most of front functionalities of plugin
include_once( EMLVLD_INC_DIR.'/class-emlvld-public.php' );
$emlvld_public = new Emlvld_Public();
$emlvld_public->add_hooks();