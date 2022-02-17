<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Eventalk;
	class Layouts {
		public $prefix = THEME_PREFIX;
		public $cpt    = THEME_CPT_PREFIX;
		public function __construct() {
			add_action( 'template_redirect', array( $this, 'layout_settings' ) );
		}
		public function layout_settings() {
			// Single Pages
			if( is_single() || is_page() ) {
				$post_type = get_post_type();
				$post_id   = get_the_id();
				switch( $post_type ) {
					case 'page':
					$prefix = 'page';
					break;
					case 'post':
					$prefix = 'single_post';
					break;
	                case "{$this->cpt}_speaker":
	                $prefix = 'speaker';
	                //RDTheme::$options[$prefix . '_layout'] = 'full-width';
	                break;
	                case "{$this->cpt}_event":
	                $prefix = 'event';
	               // RDTheme::$options[$prefix . '_layout'] = 'full-width';
	                break;                
					case 'product':
					$prefix = 'product';
					break;
					default:
					$prefix = 'single_post';
					break;
				}

				$layout         = get_post_meta( $post_id, "{$this->cpt}_layout", true );
				$sidebar        = get_post_meta( $post_id, "{$this->cpt}_sidebar", true );
				$tr_header      = get_post_meta( $post_id, "{$this->cpt}_tr_header", true );								
				$header_style   = get_post_meta( $post_id, "{$this->cpt}_header", true );
				$footer_style   = get_post_meta( $post_id, "{$this->cpt}_footer", true );
				$padding_top    = get_post_meta( $post_id, "{$this->cpt}_top_padding", true );
				$padding_bottom = get_post_meta( $post_id, "{$this->cpt}_bottom_padding", true );
				$has_banner     = get_post_meta( $post_id, "{$this->cpt}_banner", true );
				$has_breadcrumb = get_post_meta( $post_id, "{$this->cpt}_breadcrumb", true );
				$bgtype         = get_post_meta( $post_id, "{$this->cpt}_banner_type", true );
				$bgcolor        = get_post_meta( $post_id, "{$this->cpt}_banner_bgcolor", true );
				$bgimg          = get_post_meta( $post_id, "{$this->cpt}_banner_bgimg", true );

				RDTheme::$layout 			= ( empty( $layout ) || $layout == 'default' ) ? RDTheme::$options[$prefix . '_layout'] : $layout;
				RDTheme::$sidebar 			= ( empty( $sidebar ) || $sidebar == 'default' ) ? RDTheme::$options[$prefix . '_sidebar'] : $sidebar;
				RDTheme::$tr_header 		= ( empty( $tr_header ) || $tr_header == 'default' ) ? RDTheme::$options['tr_header'] : $tr_header;							
				RDTheme::$header_style 		= ( empty( $header_style ) || $header_style == 'default' ) ? RDTheme::$options['header_style'] : $header_style;
				RDTheme::$footer_style 		= ( empty( $footer_style ) || $footer_style == 'default' ) ? RDTheme::$options['footer_style'] : $footer_style;
				$padding_top          		= ( empty( $padding_top ) || $padding_top == 'default' ) ? RDTheme::$options[$prefix . '_padding_top'] : $padding_top;
				RDTheme::$padding_top 		= (int) $padding_top;
				$padding_bottom          	= ( empty( $padding_bottom ) || $padding_bottom == 'default' ) ? RDTheme::$options[$prefix . '_padding_bottom'] : $padding_bottom;
				RDTheme::$padding_bottom 	= (int) $padding_bottom;
				RDTheme::$has_banner 		= ( empty( $has_banner ) || $has_banner == 'default' ) ? RDTheme::$options[$prefix . '_banner'] : $has_banner;
				RDTheme::$has_breadcrumb 	= ( empty( $has_breadcrumb ) || $has_breadcrumb == 'default' ) ? RDTheme::$options[$prefix . '_breadcrumb'] : $has_breadcrumb;
				RDTheme::$bgtype 			= ( empty( $bgtype ) || $bgtype == 'default' ) ? RDTheme::$options[$prefix . '_bgtype'] : $bgtype;
				RDTheme::$bgcolor 			= empty( $bgcolor ) ? RDTheme::$options[$prefix . '_bgcolor'] : $bgcolor;
				if( !empty( $bgimg ) ) {
					$attch_url      = wp_get_attachment_image_src( $bgimg, 'full', true );
					RDTheme::$bgimg = $attch_url[0];
				}
				elseif( !empty( RDTheme::$options[$prefix . '_bgimg']['id'] ) ) {
					$attch_url      = wp_get_attachment_image_src( RDTheme::$options[$prefix . '_bgimg']['id'], 'full', true );
					RDTheme::$bgimg = $attch_url[0];
				}
				else {
					RDTheme::$bgimg = Helper::get_img( 'banner.jpg' );
				}
				if ( !is_active_sidebar( 'sidebar' ) ){
					RDTheme::$layout = 'full-width';
				}
			}

			// Blog and Archive
			elseif( is_home() || is_archive() || is_search() || is_404() ) {
				if( is_search() ) {
					$prefix = 'search';
				}
				elseif( is_404() ) {
					$prefix = 'error';
					RDTheme::$options[$prefix . '_layout'] = 'full-width';
				}
				elseif( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
					$prefix = 'shop';
				}
				elseif( is_post_type_archive( "{$this->cpt}_speaker" ) || is_tax( "{$this->cpt}_speaker_category" ) ) {
					$prefix = 'speakers_archive';
				}
				elseif( is_post_type_archive( "{$this->cpt}_event" ) || is_tax( "{$this->cpt}_event_category" ) ) {
					$prefix = 'event_archive';
				}				
				else {
					$prefix = 'blog';
				}
				RDTheme::$layout         = RDTheme::$options[$prefix . '_layout'];
				RDTheme::$sidebar        = RDTheme::$options[$prefix . '_sidebar'];
				RDTheme::$tr_header      = RDTheme::$options['tr_header'];				
				RDTheme::$header_style   = RDTheme::$options['header_style'];
				RDTheme::$footer_style   = RDTheme::$options['footer_style'];
				RDTheme::$padding_top    = RDTheme::$options[$prefix . '_padding_top'];
				RDTheme::$padding_bottom = RDTheme::$options[$prefix . '_padding_bottom'];
				RDTheme::$has_banner     = RDTheme::$options[$prefix . '_banner'];
				RDTheme::$has_breadcrumb = RDTheme::$options[$prefix . '_breadcrumb'];
				RDTheme::$bgtype         = RDTheme::$options[$prefix . '_bgtype'];
				RDTheme::$bgcolor        = RDTheme::$options[$prefix . '_bgcolor'];
				if( !empty( RDTheme::$options[$prefix . '_bgimg']['id'] ) ) {
					$attch_url      = wp_get_attachment_image_src( RDTheme::$options[$prefix . '_bgimg']['id'], 'full', true );
					RDTheme::$bgimg = $attch_url[0];
				} else {
					RDTheme::$bgimg = Helper::get_img( 'banner.jpg' );
				}
				if ( !is_active_sidebar( 'sidebar' ) ){
					RDTheme::$layout = 'full-width';
				}
			}
		}
	}
new Layouts;
