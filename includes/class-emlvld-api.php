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

if( !class_exists('Emlvld_API') ) {

	class Emlvld_API {

		/**
		 * The API endpoint
		 *
		 * @var string
		 */
		protected $endpoint = 'https://api.emailable.com/v1/verify';

		/**
		 * The email to validate.
		 *
		 * @var string
		 */
		protected $email = NULL;

		/**
		 * The API Key.
		 *
		 * @var string
		 */
		protected $apikey = NULL;

		/**
		 * The response object.
		 *
		 * @var object
		 */
		protected $response = NULL;
		
		/**
		 * Perform the request.
		 *
		 * @return null|object
		 */
		public function request() {
			global $emlvld_settings;

			$response = wp_cache_get( $this->get_email(), 'emailable' );
			if ( $response ) {
				return $this->set_response( $response );
			}

			if( !empty($this->get_apikey() )) {
				$args = array('timeout'  => 45,);
				$final_url = add_query_arg( array('email' => $this->get_email(), 'api_key' => $this->get_apikey()), $this->endpoint );
				$result = wp_remote_get( $final_url, $args );

				if ( ! is_wp_error( $result ) ) {
					$response =  $this->set_response( json_decode( wp_remote_retrieve_body( $result ) ) );
					
					wp_cache_set( $this->get_email(), $response, 'emailable', 86400 ); // cache 24h
			
					return $response;
				}
			}
			return NULL;
		}

		/**
		 * Get the endpoint.
		 *
		 * @return string
		 */
		public function get_endpoint() {
			return $this->endpoint;
		}

		/**
		 * Set the endpoint.
		 *
		 * @param string $endpoint The endpoint.
		 *
		 * @return string
		 */
		public function set_endpoint( $endpoint ) {
			$this->endpoint = (string) $endpoint;
			return $this->get_endpoint();
		}

		/**
		 * Get the email.
		 *
		 * @return string
		 */
		public function get_email() {
			return $this->email;
		}

		/**
		 * Set the email number.
		 *
		 * @param string $email The email address.
		 *
		 * @return string
		 */
		public function set_email( $email ) {
			$email = trim( (string) $email );
			$this->email = $email;
			return $this->get_email();
		}

		/**
		 * Get the API Key.
		 *
		 * @return string
		 */
		public function get_apikey() {
			return $this->apikey;
		}

		/**
		 * Get the message Key.
		 *
		 * @param string $stat The response.
		 *
		 * @return string
		 */
		public function get_error_msg($state) {
			global $emlvld_msg, $emlvld_settings;

			if ( !empty($emlvld_settings[$state]) ) {
				return $emlvld_settings[$state];
			} elseif ( !empty($emlvld_msg[$state]) ) {
				return $emlvld_msg[$state];
			} else {
				return false;
			}
		}

		/**
		 * Set the API Key.
		 *
		 * @param string $apikey The API Key.
		 *
		 * @return string
		 */
		public function set_apikey( $apikey ) {
			$this->apikey = (string) $apikey;
			return $this->get_apikey();
		}

		/**
		 * Get the Response Object.
		 *
		 * @return object
		 */
		public function get_response() {
			return $this->response;
		}

		/**
		 * Set the Response Object.
		 *
		 * @param  object $response The Response Object.
		 *
		 * @return object
		 */
		public function set_response( $response ) {

			global $emlvld_settings;

			$allowed_states = array();

			$allowed_states[] = 'deliverable';
			if( !empty($emlvld_settings['states_risky']) ) {
				$allowed_states[] = 'risky';
			}
			if( !empty($emlvld_settings['states_undeliverable']) ) {
				$allowed_states[] = 'undeliverable';
			}
			if ( !empty($emlvld_settings['states_unknown']) ) {
				$allowed_states[] = 'unknown';
			}

			if ( !empty($response->state) && in_array($response->state, $allowed_states) ) {
				if ( empty($emlvld_settings['free_email']) && !empty($response->free) ) {
					$response->valid = false;
					$response->msg = $this->get_error_msg('free');
				} elseif ( empty($emlvld_settings['role_email']) && !empty($response->role) ) {
					$response->valid = false;
					$response->msg = $this->get_error_msg('role');
				} elseif ( empty($emlvld_settings['accept_all']) && !empty($response->accept_all) ) {
					$response->valid = false;
					$response->msg = $this->get_error_msg('undeliverable');
				} elseif ( empty($emlvld_settings['disposable_email']) && !empty($response->disposable) ) {
					$response->valid = false;
					$response->msg = $this->get_error_msg('disposable');
				} elseif ( empty($emlvld_settings['states_undeliverable']) && $response->state == 'undeliverable' ) {
					$response->valid = false;
					$response->msg = $this->get_error_msg('undeliverable');
				} elseif ( empty($emlvld_settings['states_unknown']) && $response->state == 'unknown' ) {
					$response->valid = false;
					$response->msg = $this->get_error_msg('unknown');
				} else{
					$response->valid = true;
					$response->msg = false;
				}

			} elseif ( !empty($response->state) ) {
				$response->valid = false;

				$state = $response->state;
				$state = ( $state == 'risky' ) ? 'undeliverable' : $state;

				$response->msg = $this->get_error_msg( $state );
			}

			if ( $response->valid == false && !empty($response->did_you_mean) ) {
				$did_you_mean_msg = $emlvld_settings['did_you_mean'];
				$did_you_mean_msg = str_replace( '[EMAIL]', $response->did_you_mean, $did_you_mean_msg );
				$response->msg = $did_you_mean_msg;
			}

			$this->response = (object) $response;
			return $this->get_response();
		}
	}
}