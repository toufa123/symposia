<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Eventalk;

class General_Setup {

	public function __construct() {
		add_action( 'after_setup_theme',   array( $this, 'theme_setup' ) );		
		add_action( 'widgets_init',        array( $this, 'register_sidebars' ) );
 		add_action( 'wp_head',             array( $this, 'pingback_url_for_singularly_identifiable' ) );
		add_filter( 'body_class',          array( $this, 'body_classes' ) );
		add_action( 'wp_head',             array( $this, 'noscript_hide_preloader' ), 1 );
		add_action( 'wp_footer',           array( $this, 'scroll_to_top_html' ), 1 );
		add_filter( 'get_search_form',     array( $this, 'search_form' ) );
		add_filter( 'comment_form_fields', array( $this, 'move_textarea_to_bottom' ) );
		add_filter( 'excerpt_more',        array( $this, 'excerpt_more' ) );		
		add_filter( 'elementor/widgets/wordpress/widget_args', array( $this, 'elementor_widget_args' ) );
		add_action( 'pre_get_posts', array($this, 'wp_speaker_query' ), 999);
		
	}

	public function wp_speaker_query( $query ) {	
		if ( ! is_admin() ) {	
				if ( is_post_type_archive( "eventalk_speaker" ) || is_tax( "eventalk_speaker_category" ) ) {
				$query->set( 'posts_per_page', RDTheme::$options['speakers_arc_number']);;
				}
			}
		}

	 /**
	  * Add a pingback url auto-discovery header for singularly identifiable articles.
	  * @return void
	  */
	 public function pingback_url_for_singularly_identifiable() {
	   if ( is_singular() && pings_open() ) {
	     printf( '<link rel="pingback" href="%s">' . "\n", esc_url( get_bloginfo( 'pingback_url' ) ) );
	   }
	 }

	public function theme_setup() {
		$prefix = THEME_PREFIX;		
		// Theme supports
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );
		add_editor_style();
		
		// for gutenberg support
		add_theme_support( 'align-wide' );
		add_theme_support( 'editor-color-palette', array(
			array(
				'name' => esc_html__( 'strong magenta', 'eventalk' ),
				'slug' => 'strong-magenta',
				'color' => '#a156b4',
			),
			array(
				'name' => esc_html__('light grayish magenta', 'eventalk' ),
				'slug' => 'light-grayish-magenta',
				'color' => '#d0a5db',
			),
			array(
				'name' => esc_html__('very light gray', 'eventalk' ),
				'slug' => 'very-light-gray',
				'color' => '#eee',
			),
			array(
				'name' => esc_html__('very dark gray', 'eventalk' ),
				'slug' => 'very-dark-gray',
				'color' => '#444',
			),
		) );
		add_theme_support( 'editor-font-sizes', array(
			array(
				'name' => esc_html__('Small', 'eventalk' ),
				'size' => 12,
				'slug' => 'small'
			),
			array(
				'name' => esc_html__('Normal', 'eventalk' ),
				'size' => 16,
				'slug' => 'normal'
			),
			array(
				'name' => esc_html__('Large', 'eventalk' ),
				'size' => 36,
				'slug' => 'large'
			),
			array(
				'name' => esc_html__('Huge', 'eventalk' ),
				'size' => 50,
				'slug' => 'huge'
			)
		) );

		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support('editor-styles');	

		// Image sizes
		add_image_size( "{$prefix}-size1",  1200, 438, true ); // Single page thumbnail
		add_image_size( "{$prefix}-size2",  900,  700, true ); // Blog 3
		add_image_size( "{$prefix}-size3",  570,  380, true ); // Blog 2
		add_image_size( "{$prefix}-size4",  400,  400, true ); // Team 1 2
		add_image_size( "{$prefix}-size5",  270,  192, true ); // Blog 1
		add_image_size( "{$prefix}-size6",  420,  456, true ); // Team 3
		add_image_size( "{$prefix}-size7",  460,  526, true ); // schedule 4
		add_image_size( "{$prefix}-size8",  600,  713, true ); // blog new lg
		add_image_size( "{$prefix}-size9",  600,  497, true ); // blog new sm
		add_image_size( "{$prefix}-size10", 281,  359, true ); // team-5 new sm
		
		// Register menus
		register_nav_menus( array(
			'primary'  => esc_html__( 'Primary', 'eventalk' ),
			'topright' => esc_html__( 'Header Right', 'eventalk' ),
		) );

	}

	public function register_sidebars() {		
		$footer_widget_titles = array(
			'1' => esc_html__( 'Footer 1', 'eventalk' ),
			'2' => esc_html__( 'Footer 2', 'eventalk' ),
			'3' => esc_html__( 'Footer 3', 'eventalk' ),
			'4' => esc_html__( 'Footer 4', 'eventalk' ),
		);

		register_sidebar( array(
			'name'          => esc_html__( 'Sidebar', 'eventalk' ),
			'id'            => 'sidebar',
			'before_widget' => '<div id="%1$s" class="widget %2$s single-sidebar padding-bottom1">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widgettitle">',
			'after_title'   => '</h3>',
		) ); 

		for ( $i = 1; $i <= RDTheme::$options['footer_column']; $i++ ) {
			register_sidebar( array(
				'name'          => $footer_widget_titles[$i],
				'id'            => 'footer-'. $i,
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widgettitle">',
				'after_title'   => '</h3>',
			) );		
		}
	}

	public function body_classes( $classes ) {
    	// Header
		$classes[] = 'non-stick';

		$classes[] = 'header-style-'. RDTheme::$header_style;
		
		if ( RDTheme::$tr_header == 1 || RDTheme::$tr_header == 'on' ){
			$classes[] = 'trheader';
		}
		if (RDTheme::$options['mobile_sticky_menu'] == 1 || RDTheme::$options['mobile_sticky_menu'] == 'on' ){
			$classes[] = 'mobile-stick';
		}
        // Sidebar
		$classes[] = ( RDTheme::$layout == 'full-width' ) ? 'no-sidebar' : 'has-sidebar';

        // WooCommerce
		if( isset( $_COOKIE["shopview"] ) && $_COOKIE["shopview"] == 'list' ) {
			$classes[] = 'product-list-view';
		} else {
			$classes[] = 'product-grid-view';
		}

		return $classes;
	}

	public function noscript_hide_preloader(){
		// Hide preloader if js is disabled
		echo '<noscript><style>#preloader{display:none;}</style></noscript>';
	}

	public function scroll_to_top_html(){
		// Back-to-top link
		if ( RDTheme::$options['back_to_top'] ){
			echo '<a href="#" class="scrollToTop"><i class="fa fa-angle-double-up"></i></a>';
		}
	}

	public function search_form(){
		$output =  '
		<form role="search" method="get" class="search-form" action="' . esc_url( home_url( '/' ) ) . '">
		<div class="custom-search-input">
		<div class="input-group col-md-12">
		<input type="text" class="search-query form-control" placeholder="' . esc_attr__( 'Search here ...', 'eventalk' ) . '" value="' . get_search_query() . '" name="s" />
		<span class="input-group-btn">
		<button class="btn" type="submit">
		<span class="fa fa-search" aria-hidden="true"></span>
		</button>
		</span>
		</div>
		</div>
		</form>
		';
		return $output;
	}

	public function move_textarea_to_bottom( $fields ) {
		$temp = $fields['comment'];
		unset( $fields['comment'] );
		$fields['comment'] = $temp;
		return $fields;
	}

	public function excerpt_more() {
		return '...';
	}
	
	function elementor_widget_args( $args ) {
		$args['before_widget'] = '<div class="widget single-sidebar padding-bottom1">';
		$args['after_widget']  = '</div>';
		$args['before_title']  = '<h3>';
		$args['after_title']   = '</h3>';
		return $args;
	}
}

new General_Setup;