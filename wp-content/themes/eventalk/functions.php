<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.4
 */
if ( !isset( $content_width ) ) {
	$content_width = 1200;
}

class Eventalk_Main {

	public $theme   = 'eventalk';
	public $action  = 'eventalk_theme_init';

	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'load_textdomain' ), 15);

		if ( version_compare( PHP_VERSION, '7', '>=' ) ) {
			$this->includes();
		}
		else {
			$this->fallback();
		}		
	}

	public function load_textdomain(){
		load_theme_textdomain( $this->theme, get_template_directory() . '/languages' );
	}

	public function includes(){
		
		do_action( $this->action );
		require_once get_template_directory() . '/inc/constants.php';
		require_once get_template_directory() . '/inc/includes.php';
		require_once get_template_directory() . '/inc/lc-helper.php';
		require_once get_template_directory() . '/inc/lc-utility.php';
	}

	public function fallback() {
		add_action( 'admin_notices', array( $this, 'phpfail_notice' ) );
		add_action( 'template_include', array( $this, 'fallback_template' ), 99 );
	}

	public function phpfail_notice() {
		$theme_data = wp_get_theme( $this->theme );
		$theme_name = $theme_data->get( 'Name' );
		$msg = sprintf( esc_html__( 'Error: Your current PHP version is %1$s. You need at least PHP version 7+ for theme "%2$s" to work. Please ask your hosting provider to upgrade your PHP version into 7+', 'eventalk' ), PHP_VERSION, $theme_name );
		echo '<div class="error"><p>' . $msg . '</p></div>';
	}

	public function fallback_template( $template ) {
		$template = locate_template( array( 'fallback.php' ) );
		return $template;
	}
}

new Eventalk_Main;

add_editor_style( 'style-editor.css' );



define( 'THEME_BASE_DIR',    get_template_directory(). '/' );
define( 'THEME_INC_DIR',     THEME_BASE_DIR . 'inc/' );

// Widgets fallback
if ( !defined( 'EVENTALK_CORE_UPDATE_1' ) ) {	
	 add_action( 'admin_notices', 'eventalk_widgets_fallback_notice' );
}


function eventalk_widgets_fallback_notice() {
	$notice = '<div class="error"><p>' . sprintf( __( "Please update plugin <b><i>Eventalk Core</b></i> to the latest version otherwise some functionalities will not work properly. You can update it from <a href='%s'>here</a>", 'eventalk' ), menu_page_url( 'eventalk-install-plugins', false ) ) . '</p></div>';
	echo wp_kses_post( $notice );
}