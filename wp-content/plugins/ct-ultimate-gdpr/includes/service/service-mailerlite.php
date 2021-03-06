<?php
	
	/**
	 * Class CT_Ultimate_GDPR_Service_Mailerlite
	 */
	class CT_Ultimate_GDPR_Service_Mailerlite extends CT_Ultimate_GDPR_Service_Abstract {
		
		/**
		 * @return void
		 */
		public function init() {
			add_filter( 'ct_ultimate_gdpr_controller_plugins_compatible_official-mailerlite-sign-up-forms/mailerlite.php', '__return_true' );
			add_filter( 'ct_ultimate_gdpr_controller_plugins_collects_data_official-mailerlite-sign-up-forms/mailerlite.php', '__return_true' );
			
		}
		
		/**
		 * @return $this
		 */
		public function collect() {
			$this->set_collected( array() );
			
			return $this;
		}
		
		/**
		 * @return mixed
		 */
		public function get_name() {
			return apply_filters( "ct_ultimate_gdpr_service_{$this->get_id()}_name", 'Mailerlite' );
		}
		
		/**
		 * @return bool
		 */
		public function is_active() {
			return function_exists( 'mailerlite_install' );
		}
		
		/**
		 * @return bool
		 */
		public function is_forgettable() {
			return false;
		}
		
		/**
		 * @return bool
		 */
		public function is_subscribeable() {
			return true;
		}
		
		/**
		 * @throws Exception
		 * @return void
		 */
		public function forget() {
		}
		
		/**
		 * @return mixed
		 */
		public function add_option_fields() {
			
			add_settings_section(
				'ct-ultimate-gdpr-services-mailerlite_accordion-mailerlite', // ID
				esc_html( $this->get_name() ), // Title
				null, // callback
				$this->front_controller->find_controller('services')->get_id() // Page
			);

			add_settings_field(
				'services_mailerlite_consent_field', // ID
				sprintf(
					esc_html__( "[%s] Inject consent checkbox to all forms", 'ct-ultimate-gdpr' ),
					$this->get_name()
				),
				array( $this, 'render_field_services_mailerlite_consent_field' ), // Callback
				$this->front_controller->find_controller('services')->get_id(), // Page
				'ct-ultimate-gdpr-services-mailerlite_accordion-mailerlite'
			);

            add_settings_field(
                "services_{$this->get_id()}_hide_from_forgetme_form", // ID
                sprintf( esc_html__( "[%s] Hide from Forget Me Form", 'ct-ultimate-gdpr' ), $this->get_name() ), // Title
                array( $this, "render_hide_from_forgetme_form" ), // Callback
                $this->front_controller->find_controller('services')->get_id(), // Page
                'ct-ultimate-gdpr-services-mailerlite_accordion-mailerlite'
            );
			
		}
		
		/**
		 *
		 */
		public function render_field_services_mailerlite_consent_field() {
			
			$admin = $this->get_admin_controller();
			
			$field_name = $admin->get_field_name( __FUNCTION__ );
			printf(
				"<input class='ct-ultimate-gdpr-field' type='checkbox' id='%s' name='%s' %s />",
				$admin->get_field_name( __FUNCTION__ ),
				$admin->get_field_name_prefixed( $field_name ),
				$admin->get_option_value_escaped( $field_name ) ? 'checked' : ''
			);
			
		}
		
		
		
		/**
		 * @return mixed
		 */
		public function front_action() {
			$inject = $this->get_admin_controller()->get_option_value( 'services_mailerlite_consent_field', false, $this->front_controller->find_controller('services')->get_id() );
			if ( $inject ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
			}
		}
		
		/**
		 * @param $original_fields
		 *
		 * @return mixed
		 */
		
		public function wp_enqueue_scripts(  ) {
			
			wp_enqueue_script( 'ct-ultimate-gdpr-service-mailerlite', ct_ultimate_gdpr_url( 'assets/js/service-mailerlite.js' ) );
			wp_localize_script( 'ct-ultimate-gdpr-service-mailerlite', 'ct_ultimate_gdpr_mailerlite', array(
				'checkbox' => ct_ultimate_gdpr_render_template( ct_ultimate_gdpr_locate_template( 'service/service-mailerlite-consent-field', false ) ),
			) );
			
		}
		
		/**
		 * @throws Mailerlite API Key
		 */
		public function unsubscribe() {
			
			global $wpdb;

			$mailerlite_api_key = "";
			
			$results = $wpdb->get_results("
			SELECT *
			FROM {$wpdb->options}
			WHERE option_name = 'mailerlite_api_key'
			",
				ARRAY_A
			);
			
			if($results) {
				$mailerlite_api_key = $results[0]['option_value'];
				if( class_exists('ML_Subscribers', true) ) {
					$ML_Subscribers = new ML_Subscribers( $mailerlite_api_key );
					$subscriber     = $ML_Subscribers->unsubscribe( $this->user->get_email() );
				}
			}
		}
		
		/**
		 * @return string
		 */
		protected function get_default_description() {
			return esc_html__( 'Mailer Lite gathers data entered by users in forms', 'ct-ultimate-gdpr' );
		}
	}