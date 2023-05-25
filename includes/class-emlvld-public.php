<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Public Class
 *
 * Handles adding  functionality to the front pages
 *
 * @package Emailable
 * @since 1.0.0
 */

if( !class_exists('Emlvld_Public') ) {
	
	class Emlvld_Public {
		
		public $api;

		//class constructor
		function __construct(){
			global $emlvld_api;
			$this->api = $emlvld_api;
		}
		
		/**
		 * Validate is_email functions hook
		 * 
		 * @package Emailable
		 * @since 1.0.0
		 */
		public function emlvld_is_email_validate( $is_valid, $email, $context ) {

			// Contact form 7 create the rest request
			if ( empty($_POST) || wp_doing_ajax() || defined('REST_REQUEST') ) {
				return $is_valid;
			}

			if ( ! $is_valid ) {
				return FALSE;
			}

			$this->api->set_email( $email );
			$response = $this->api->request();
			if ( !empty($response->valid) ) {
				return TRUE;
			} elseif (!empty($response->msg) ) {
				return FALSE;
			} else {
				return $is_valid;
			}
		}

		/**
		 * Validate On Registration of WP
		 * 
		 * @package Emailable
		 * @since 1.0.0
		 */
		public function emlvld_wp_signup_validate($errors, $sanitized_user_login, $email) {

			if( email_exists( $email ) ) {
				return $errors;
			}

			$this->api->set_email( $email );
			$response = $this->api->request();

			if ( !empty($response->valid) ) {
				return $errors;
			} elseif ( !empty($response->msg) ) {
				$errors->add( 'invalid_email', sprintf( esc_html__("%sERROR%s: %s", 'emlvld'), "<strong>", "</strong>", $response->msg ) );
			} else {
				return $errors;
			}
			
			return $errors;
		}

		/**
		 * Add the is_email Filter
		 * 
		 * @package Emailable
		 * @since 1.0.0
		 */
		public function emlvld_hook_is_email_filter(){
			add_filter( 'is_email', array( $this, 'emlvld_comment_is_email_validate' ), 10, 3 );
		}

		/**
		 * Remove the is_email Filter
		 * 
		 * @package Emailable
		 * @since 1.0.0
		 */
		public function emlvld_dehook_is_email_filter(){
			add_filter( 'is_email', array( $this, 'emlvld_comment_is_email_validate' ), 10, 3 );
		}

		/**
		 * Add Only for comment the is_email Filter
		 * 
		 * @package Emailable
		 * @since 1.0.0
		 */
		public function emlvld_comment_is_email_validate($is_valid, $email, $context){
			if ( ! $is_valid ) {
				return FALSE;
			}
			$this->api->set_email( $email );
			$response = $this->api->request();
			if( !empty($response->valid) ){
				return TRUE;
			}elseif(!empty($response->msg)){
				return FALSE;
			}else{
				return $is_valid;
			}
		}

		/**
		 * Validate email for Gravity Forms
		 * 
		 * @package Emailable
		 * @since 1.0.0
		 */
		public function emlvld_gform_validate( $result, $value, $form, $field ) {

			if($field->type == 'email' && $field->isRequired == '0' && $value==''){
				$result['is_valid'] = true;
				return $result;
			}

		   	if ($field->type == 'email' && $result['is_valid']) {

			   	$this->api->set_email( $value );
			   	$response = $this->api->request();
				if( !empty($response->valid) ){
					$result['is_valid'] = true;
				}elseif(!empty($response->msg)){
					$result['is_valid'] = false;
					$result['message'] = $response->msg;
				}else{
					return $result;		
				}
			}
		     return $result;
		}

		/**
		 * Validate email for Contact Form 7
		 * 
		 * @package Emailable
		 * @since 1.0.0
		 */
		public function emlvld_cform7_validate($result, $tag){
			$tag = new WPCF7_FormTag( $tag );
	     	$type = $tag->type;
	      	$name = $tag->name;
	      	if ('email' == $type || 'email*' == $type) {
				$this->api->set_email( sanitize_email($_POST[$name]) );
			   	$response = $this->api->request();
				if( !empty($response->msg) ){
					$result->invalidate( $tag, $response->msg );
				}
			}
	      	return $result;
		}

		/**
		 * Validate email for Ninja Forms
		 * 
		 * @package Emailable
		 * @since 1.0.0
		 */
		public function emlvld_nform_validate($form_data) {
			
			foreach( $form_data[ 'fields' ] as $key => $field ) {
		  	   $value = $field['value'];	
		  	   // ignore multi-line strings / textareas
			   if ( preg_match('/@.+\./', $value) && strpos($value, "\n") === false && strpos($value, '\n') === false ) {
			    
				$this->api->set_email( $value );
				$response = $this->api->request();	
			    	if ( !empty($response->msg) ) {
					$field_id = $field['id'];
			    		$form_data['errors']['fields'][$field_id] = $response->msg;
			    	}		    		
			   }
			}
			return $form_data;
		}

		/**
		 * Adding Hooks
		 *
		 * Adding hooks for the styles and scripts.
		 *
		 * @package Emailable
		 * @since 1.0.0
		 */
		function add_hooks() {
			
			// WP Is_email function add validate support
			add_filter( 'is_email', array( $this, 'emlvld_is_email_validate' ), 10, 3 );
			
			// WP Register add validate support
			add_action( 'registration_errors', array( $this, 'emlvld_wp_signup_validate' ), 10, 3 );
			
			// WP Comments add validate support
			add_action( 'pre_comment_on_post', array( $this, 'emlvld_hook_is_email_filter' ) );
			add_action( 'comment_post', array( $this, 'emlvld_dehook_is_email_filter' ) );
			
			// Gravity form support
			add_filter( 'gform_field_validation', array( $this, 'emlvld_gform_validate' ), 10, 4 );
			
			// Contact Form7 support
			add_filter( 'wpcf7_validate_email', array( $this, 'emlvld_cform7_validate' ), 20, 2 );
			add_filter( 'wpcf7_validate_email*', array( $this, 'emlvld_cform7_validate' ), 20, 2 );
			
			// Ninja form support
			add_filter( 'ninja_forms_submit_data', array( $this, 'emlvld_nform_validate' ), 10, 1 );
		}
	}
}