<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.4
 */

namespace radiustheme\Eventalk;

use Elementor\Plugin;

class Scripts {

	public $prefix  = THEME_PREFIX;
	public $version = THEME_VERSION;
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ), 12 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 15 );
		add_action( 'after_setup_theme',     array($this, 'editor_style_support') );
	}
	public function register_scripts(){
		/* Deregister */
		wp_deregister_style( 'font-awesome' );
		wp_deregister_style( 'yith-wcwl-font-awesome' );		
		/*CSS*/
		// Owl carousel
		wp_register_style( 'owl-carousel',              Helper::get_css( 'owl.carousel.min' ), array(), $this->version );
		wp_register_style( 'owl-theme-default',         Helper::get_css( 'owl.theme.default.min' ), array(), $this->version );
		wp_register_style( 'animate',                   Helper::get_css( 'animate.min' ), array(), $this->version );
		// Slider
		wp_register_style( 'nivo-slider',               Helper::get_css( 'nivo-slider.min' ), array(), $this->version );	
		wp_register_style( 'magnific-popup',            Helper::maybe_rtl( 'magnific-popup' ), array(), $this->version );	
		/*JS*/
		// Owl Carousel
		wp_register_script( 'owl-carousel',             Helper::get_js( 'owl.carousel.min' ), array( 'jquery' ), $this->version, true );		
		// Isotope
		wp_register_script( 'isotope-pkgd',             Helper::get_js( 'isotope.pkgd.min' ), array( 'jquery' ), $this->version, true );
			
		// Slider
		wp_register_script( 'nivo-slider',              Helper::get_js( 'jquery.nivo.slider.min' ), array( 'jquery' ), $this->version, true );
		wp_register_script( 'countdown',              	Helper::get_js( 'jquery.countdown.min' ), array( 'jquery' ), $this->version );
		wp_register_script( 'magnific-popup',           Helper::get_js( 'jquery.magnific-popup.min' ), array( 'jquery' ), $this->version );
	}  
     
	public function enqueue_scripts() {
		/*CSS*/
		// Bootstrap		
		wp_enqueue_style( 'bootstrap',                   Helper::maybe_rtl( 'bootstrap.min' ), array(), $this->version );	
		// Font-awesome
		wp_enqueue_style( 'font-awesome',                Helper::get_css( 'font-awesome.min' ), array(), $this->version );		
		wp_enqueue_style(  'animate');	
		// Elementor Scripts in preview mode
		$this->elementor_scripts();
		// Conditional Scripts
		$this->conditional_scripts();
		// Style
		wp_enqueue_style( $this->prefix . '-style',      Helper::maybe_rtl( 'style' ), array(), $this->version );
		// Elementor
		wp_enqueue_style( $this->prefix . '-elementor',  Helper::maybe_rtl( 'elementor' ), array(), $this->version );
		wp_enqueue_style( $this->prefix . '-update-css',  Helper::maybe_rtl( 'update-css' ), array(), $this->version );
		// Dynamic style
		$this->dynamic_style();
		/*JS*/
		// Comments
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
		// popper js
		wp_enqueue_script( 'popper',                   	 Helper::get_js( 'popper' ), array(), $this->version );
		// bootstrap js
		wp_enqueue_script( 'bootstrap',                  Helper::get_js( 'bootstrap.min' ), array( 'jquery' ), $this->version, true );
		// Nav smooth scroll
		wp_enqueue_script( 'jquery-nav',                 Helper::get_js( 'jquery.nav.min' ), array( 'jquery' ), $this->version, true );
		// Cookie js
		wp_enqueue_script( 'js-cookie',                  Helper::get_js( 'js.cookie.min' ), array( 'jquery' ), $this->version, true );			
		// Main js
		wp_enqueue_script( $this->prefix . '-main',      Helper::get_js( 'main' ), array( 'jquery' ), $this->version, true );
		wp_enqueue_script( 'countdown' );	
		// Localization
		
		
		$localize_data = array(
			'stickyMenu'     => RDTheme::$options['sticky_menu'],
			'hasAdminBar'  => is_admin_bar_showing() ? 1 : 0,
			'headerStyle'  => RDTheme::$header_style,
			'meanWidth'    => RDTheme::$options['resmenu_width'],
			'primaryColor' => RDTheme::$options['primary_color'],
			'seconderyColor' => RDTheme::$options['secondery_color'],

			'day'	         => esc_html__('Day' , 'eventalk'),
			'hour'	         => esc_html__('Hour' , 'eventalk'),
			'minute'         => esc_html__('Minute' , 'eventalk'),
			'second'         => esc_html__('Second' , 'eventalk'),
			'close_text'     => esc_html__('close' , 'eventalk'),
			'details_text'   => esc_html__('Details' , 'eventalk'),
			'extraOffset'    => RDTheme::$options['sticky_menu'] ? 75 : 0,
			'extraOffsetMobile' => RDTheme::$options['sticky_menu'] ? 52 : 0,
			'rtl'            => is_rtl() ? 'yes' : 'no', //@rtl
		);
		wp_localize_script( $this->prefix . '-main', 'EventalkObj', $localize_data );
		// RTL
		if ( is_rtl() ) {	
			wp_enqueue_style( 'eventalk-rtl',                Helper::get_css( 'rtl' ), array(), $this->version );		
		}	

	}

	  public function editor_style_support()
	  {
	    add_theme_support( 'editor-styles' );	    
	  }


	public function elementor_scripts() {
		if ( !did_action( 'elementor/loaded' ) ) {
			return;
		}
		if ( Plugin::$instance->preview->is_preview_mode() ) {
			wp_enqueue_style(  'owl-carousel');
			wp_enqueue_style(  'owl-theme-default');
			wp_enqueue_script( 'owl-carousel' );
			wp_enqueue_style(  'bootstrap');
			wp_enqueue_style(  'animate');
			wp_enqueue_style(  'magnific-popup');
			wp_enqueue_script( 'bootstrap' );			
			wp_enqueue_script( 'isotope-pkgd' );
			wp_enqueue_script( 'imagesloaded' );	
			wp_enqueue_style(  'nivo-slider' );
			wp_enqueue_script( 'nivo-slider' );
			wp_enqueue_script( 'countdown' );	
			wp_enqueue_script( 'popper' );	
			wp_enqueue_script( 'magnific-popup' );	
		}
	}

	public function fonts_url(){
		$fonts_url = '';
		if ( 'off' !== _x( 'on', 'Google fonts - Roboto : on or off', 'eventalk' ) ) {
			$fonts_url = add_query_arg( 'family', urlencode( 'Roboto:400,500,700,900' ), "//fonts.googleapis.com/css" );				
		}
		return $fonts_url;
	}

	private function conditional_scripts(){
		$cpt = THEME_CPT_PREFIX;

		if ( !class_exists( 'Redux' ) ) {
			wp_enqueue_style( $this->prefix . '-gfonts',     $this->fonts_url(), array(), $this->version ); // Google fonts
		}
				
	}	


	private function dynamic_style(){
		$dynamic_css  = $this->template_style();
		ob_start();
		Helper::requires( 'dynamic-style.php' );
		Helper::requires( 'dynamic-style-elementor.php' );
		$dynamic_css .= ob_get_clean();
		$dynamic_css  = $this->minified_css( $dynamic_css );
		wp_register_style( $this->prefix . '-dynamic', false );
		wp_enqueue_style( $this->prefix . '-dynamic' );
		wp_add_inline_style( $this->prefix . '-dynamic', $dynamic_css );
	}

	private function minified_css( $css ) {
		/* remove comments */
		$css = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css );
		/* remove tabs, spaces, newlines, etc. */
		$css = str_replace( array( "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ), ' ', $css );
		return $css;
	}

	private function template_style(){
		$style = '';

		if ( RDTheme::$bgtype == 'bgcolor' ) {
			$style .= '.entry-banner{background-color: ' . RDTheme::$bgcolor . '}';
		}
		else {
			$style .= '.entry-banner{background-image: url(' . RDTheme::$bgimg . ')}';
		}

		if ( RDTheme::$padding_top == '0') {
			$style .= '.content-area {padding-top:'. RDTheme::$padding_top . 'px;}';
		}else {
			$style .= '.content-area {padding-top:'. RDTheme::$padding_top . 'px;}
			@media all and (max-width: 1199px) {.content-area {padding-top:100px;}}
			@media all and (max-width: 991px) {.content-area {padding-top:100px;}}';
		}
		
		if ( RDTheme::$padding_bottom == '0') {
			$style .= '.content-area {padding-bottom:'. RDTheme::$padding_bottom . 'px;}';
		}else {
			$style .= '.content-area {padding-bottom:'. RDTheme::$padding_bottom . 'px;}
			@media all and (max-width: 1199px) {.content-area {padding-bottom:100px;}}
			@media all and (max-width: 991px) {.content-area {padding-bottom:100px;}}';
		}
		if ( RDTheme::$options['inner_fix_banner']) {
			$style .= '.entry-banner {
				background-attachment: fixed;
			}';
		}
		return $style;
	}
}

new Scripts;