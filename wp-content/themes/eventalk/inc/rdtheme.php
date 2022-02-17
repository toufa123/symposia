<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.4
 */

namespace radiustheme\Eventalk;

use \Redux;
use \ReduxFrameworkPlugin;

if ( !class_exists( NS . 'RDTheme' ) ) {

	class RDTheme {
		protected static $instance = null;
		// Sitewide static variables
		public static $options = null;
		// Template specific variables
		public static $layout = null;
		public static $sidebar = null;
		public static $tr_header = null;
		public static $top_bar = null;	
		public static $header_style = null;
		public static $footer_style = null;
		public static $padding_top = null;
		public static $padding_bottom = null;
		public static $has_banner = null;
		public static $has_breadcrumb = null;
		public static $bgtype = null;
		public static $bgimg = null;
		public static $bgcolor = null;

		private function __construct() {
			$this->redux_init();
			add_action( 'after_setup_theme', array( $this, 'set_options' ) );
			add_action( 'after_setup_theme', array( $this, 'set_redux_compability_options' ) );
		}

		public static function instance() {
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}

		public function redux_init() { 
			$prefix = THEME_PREFIX_VAR;
			add_action( 'admin_menu', array( $this, 'remove_redux_menu' ), 12 ); // Remove Redux Menu
			add_filter( "redux/{$prefix}/aURL_filter", '__return_empty_string' ); // Remove Redux Ads
			add_action( 'redux/loaded', array( $this, 'remove_redux_demo' ) ); // If Redux is running as a plugin, this will remove the demo notice and links
			add_action( "redux/page/{$prefix}/enqueue", array( $this, 'redux_admin_style' ) ); // Redux Admin CSS

		}

		public function set_options(){
			include THEME_INC_DIR . 'predefined-data.php';
			$options = json_decode( $predefined_data, true );
			if ( class_exists( 'Redux' ) ) {
				$options = wp_parse_args( $GLOBALS['eventalk'], $options );
			}
			self::$options = $options;
		}

		public function remove_redux_menu() {
			remove_submenu_page( 'tools.php','redux-about' );
		}

		public function remove_redux_demo() {
			if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
			    add_filter( 'plugin_row_meta', array( $this, 'redux_remove_extra_meta' ), 12, 2 );
			    remove_action( 'admin_notices', array( ReduxFrameworkPlugin::instance(), 'admin_notices' ) );
			}
		}

		public function redux_remove_extra_meta( $links, $file ){
			if ( strpos( $file, 'redux-framework.php' ) !== false) {
			    $links = array_slice( $links, 0, 3 );
			}

			return $links;
		}

		// Backward compability for newly added options
		public function set_redux_compability_options(){
			$new_options = array(
				'single_event_socials'      		=> true,
				'single_event_btn'       			=> true,
				'single_event_btn_text' 			=> 'Buy Now Ticket',
				'error_buttonlink' 					=> '',
				'header_new_window' 				=> false,
				'mobile_sticky_menu' 				=> false,
				'mobile_menu_bgcolor' 				=> '#ffffff',
				'speakers_elementor_content' 		=> false,
				'single_event_table' 				=> true,
				'single_schedule_info' 				=> true,
				'single_img_slider' 				=> true,
				'single_speaker_style' 				=> 'style1',
				'schedule_label_display' 			=> 'label_meta',
			);

			foreach ( $new_options as $key => $value ) {
				if ( !isset( self::$options[$key] ) ) {
					self::$options[$key] = $value;
				}
			}
		}

		public function redux_admin_style() {
			$prefix = THEME_PREFIX;
			wp_enqueue_style( "{$prefix}-redux-admin", Helper::get_css( 'redux-admin' ), array( 'redux-admin-css' ), THEME_VERSION );
		}
	}
}
RDTheme::instance();
