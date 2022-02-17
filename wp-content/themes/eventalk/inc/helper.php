<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.1
 */

namespace radiustheme\Eventalk;

use \WP_Query;

class Helper {	

	public static function get_speaker(){
			$prefix = THEME_PREFIX_VAR;
			$args = array(
				'posts_per_page'   => -1,
				'orderby'          => 'title',
				'order'            => 'ASC',
				'post_type'        => "{$prefix}_speaker",
			);
			$posts = get_posts( $args );			
			foreach ( $posts as $post ) {
				$speakers[$post->ID] = $post->post_title;
			}
			return $speakers;
		}

	public static function requires( $filename, $dir = false ){
		if ( $dir) {
			$child_file = get_stylesheet_directory() . '/' . $dir . '/' . $filename;

			if ( file_exists( $child_file ) ) {
				$file = $child_file;
			}
			else {
				$file = get_template_directory() . '/' . $dir . '/' . $filename;
			}
		}
		else {
			$child_file = get_stylesheet_directory() . '/inc/' . $filename;

			if ( file_exists( $child_file ) ) {
				$file = $child_file;
			}
			else {
				$file = THEME_INC_DIR . $filename;
			}
		}

		require_once $file;
	}

	public static function get_img( $img ){
		$img = get_template_directory_uri() . '/assets/img/' . $img;
		return $img;
	}

	public static function get_css( $file ){
		$file = get_template_directory_uri() . '/assets/css/' . $file . '.css';
		return $file;
	}

	public static function get_js( $file ){
		$file = get_template_directory_uri() . '/assets/js/' . $file . '.js';
		return $file;
	}

	public static function filter_content( $content ){
		// wp filters
		$content = wptexturize( $content );
		$content = convert_smilies( $content );
		$content = convert_chars( $content );
		$content = wpautop( $content );
		$content = shortcode_unautop( $content );

		// remove shortcodes
		$pattern= '/\[(.+?)\]/';
		$content = preg_replace( $pattern,'',$content );

		// remove tags
		$content = strip_tags( $content );

		return $content;
	}

	public static function get_current_post_content( $post = false ) {
		if ( !$post ) {
			$post = get_post();				
		}
		$content = has_excerpt( $post->ID ) ? $post->post_excerpt : $post->post_content;
		$content = self::filter_content( $content );
		return $content;
	}

	public static function pagination( $max_num_pages = false ) {
		global $wp_query;

		$max = $max_num_pages ? $max_num_pages : $wp_query->max_num_pages;
		$max = intval( $max );

		/** Stop execution if there's only 1 page */
		if( $max <= 1 ) return;

		$paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;

		/**	Add current page to the array */
		if ( $paged >= 1 )
			$links[] = $paged;

		/**	Add the pages around the current page to the array */
		if ( $paged >= 3 ) {
			$links[] = $paged - 1;
			$links[] = $paged - 2;
		}

		if ( ( $paged + 2 ) <= $max ) {
			$links[] = $paged + 2;
			$links[] = $paged + 1;
		}
		include THEME_VIEW_DIR . 'pagination.php';
	}

	public static function comments_callback( $comment, $args, $depth ){
		include THEME_VIEW_DIR . 'comments-callback.php';
	}

	public static function hex2rgb($hex) {
		$hex = str_replace("#", "", $hex);
		if(strlen($hex) == 3) {
			$r = hexdec(substr($hex,0,1).substr($hex,0,1));
			$g = hexdec(substr($hex,1,1).substr($hex,1,1));
			$b = hexdec(substr($hex,2,1).substr($hex,2,1));
		} else {
			$r = hexdec(substr($hex,0,2));
			$g = hexdec(substr($hex,2,2));
			$b = hexdec(substr($hex,4,2));
		}
		$rgb = "$r, $g, $b";
		return $rgb;
	}

	public static function socials(){
		$rdtheme_socials = array(
			'social_facebook' => array(
				'icon' => 'fa-facebook',
				'url'  => RDTheme::$options['social_facebook'],
			),
			'social_twitter' => array(
				'icon' => 'fa-twitter',
				'url'  => RDTheme::$options['social_twitter'],
			),
			'social_gplus' => array(
				'icon' => 'fa-google-plus',
				'url'  => RDTheme::$options['social_gplus'],
			),
			'social_linkedin' => array(
				'icon' => 'fa-linkedin',
				'url'  => RDTheme::$options['social_linkedin'],
			),
			'social_youtube' => array(
				'icon' => 'fa-youtube',
				'url'  => RDTheme::$options['social_youtube'],
			),
			'social_pinterest' => array(
				'icon' => 'fa-pinterest',
				'url'  => RDTheme::$options['social_pinterest'],
			),
			'social_instagram' => array(
				'icon' => 'fa-instagram',
				'url'  => RDTheme::$options['social_instagram'],
			),
			'social_rss' => array(
				'icon' => 'fa-rss',
				'url'  => RDTheme::$options['social_rss'],
			),
		);
		return array_filter( $rdtheme_socials, array( __CLASS__ , 'filter_social' ) );
	}	

	public static function filter_social( $args ){
		return ( $args['url'] != '' );
	}

	//@rtl
		public static function maybe_rtl( $file ){
			if ( is_rtl() ) {
				$file =get_template_directory_uri() . '/assets/css-auto-rtl/' . $file . '.css';
				return $file;
			}
			else {
				$file = get_template_directory_uri() . '/assets/css/' . $file . '.css';
				return $file;
			}
		}



	public static function speakers_socials(){
		$speakers_socials = array(
			'facebook' => array(
				'label' => esc_html__( 'Facebook', 'eventalk' ),
				'type'  => 'text',
				'icon'  => 'fa-facebook',
			),
			'twitter' => array(
				'label' => esc_html__( 'Twitter', 'eventalk' ),
				'type'  => 'text',
				'icon'  => 'fa-twitter',
			),
			'linkedin' => array(
				'label' =>esc_html__( 'Linkedin', 'eventalk' ),
				'type'  => 'text',
				'icon'  => 'fa-linkedin',
			),
			'gplus' => array(
				'label' =>esc_html__( 'Google Plus', 'eventalk' ),
				'type'  => 'text',
				'icon'  => 'fa-google-plus',
			),
			'youtube' => array(
				'label' =>esc_html__( 'Youtube', 'eventalk' ),
				'type'  => 'text',
				'icon'  => 'fa-youtube-play',
			),
			'pinterest' => array(
				'label' =>esc_html__( 'Pinterest', 'eventalk' ),
				'type'  => 'text',
				'icon'  => 'fa-pinterest',
			),
			'instagram' => array(
				'label' =>esc_html__( 'Instagram', 'eventalk' ),
				'type'  => 'text',
				'icon'  => 'fa-instagram',
			),
			'github' => array(
				'label' =>esc_html__( 'Github', 'eventalk' ),
				'type'  => 'text',
				'icon'  => 'fa-github',
			),
			'stackoverflow' => array(
				'label' =>esc_html__( 'Stackoverflow', 'eventalk' ),
				'type'  => 'text',
				'icon'  => 'fa-stack-overflow',
			),
		);
		
		return apply_filters( 'speakers_socials', $speakers_socials );
	}	

	public static function event_socials(){
		$event_socials = array(
			'facebook' => array(
				'label' => esc_html__( 'Facebook', 'eventalk' ),
				'type'  => 'text',
				'icon'  => 'fa-facebook',
			),
			'twitter' => array(
				'label' => esc_html__( 'Twitter', 'eventalk' ),
				'type'  => 'text',
				'icon'  => 'fa-twitter',
			),
			'linkedin' => array(
				'label' => esc_html__( 'Linkedin', 'eventalk' ),
				'type'  => 'text',
				'icon'  => 'fa-linkedin',
			),
			'gplus' => array(
				'label' => esc_html__( 'Google Plus', 'eventalk' ),
				'type'  => 'text',
				'icon'  => 'fa-google-plus',
			),
			'youtube' => array(
				'label' => esc_html__( 'Youtube', 'eventalk' ),
				'type'  => 'text',
				'icon'  => 'fa-youtube-play',
			),
			'pinterest' => array(
				'label' => esc_html__( 'Pinterest', 'eventalk' ),
				'type'  => 'text',
				'icon'  => 'fa-pinterest-p',
			),
			'instagram' => array(
				'label' => esc_html__( 'Instagram', 'eventalk' ),
				'type'  => 'text',
				'icon'  => 'fa-instagram',
			),
			'github' => array(
				'label' => esc_html__( 'Github', 'eventalk' ),
				'type'  => 'text',
				'icon'  => 'fa-github',
			),
			'stackoverflow' => array(
				'label' => esc_html__( 'Stackoverflow', 'eventalk' ),
				'type'  => 'text',
				'icon'  => 'fa-stack-overflow',
			),
		);
		
		return apply_filters( 'event_socials', $event_socials );
	}	


	public static function nav_menu_args(){
		$prefix   = THEME_PREFIX_VAR;
		$pagemenu = false;
		if ( ( is_single() || is_page() ) ) {
			$menuid = get_post_meta( get_the_id(), "{$prefix}_page_menu", true );
			if ( !empty( $menuid ) && $menuid != 'default' ) {
				$pagemenu = $menuid;
			}
		}
		if ( $pagemenu ) {
			$nav_menu_args = array( 'menu' => $pagemenu,'container' => 'nav' );
		}
		else {
				$nav_menu_args = array( 'theme_location' => 'primary','container' => 'nav', 'fallback_cb' => false );
		}
		return $nav_menu_args;
	}	

	public static function has_footer(){
		if ( !RDTheme::$options['footer_area'] ) {
			return false;
		}
		$footer_column = RDTheme::$options['footer_column'];
		for ( $i = 1; $i <= $footer_column; $i++ ) {
			if ( is_active_sidebar( 'footer-'. $i ) ) {
				return true;
			}
		}
		return false;
	}

	public static function custom_sidebar_fields() {
		$prefix = THEME_PREFIX_VAR;
		$sidebar_fields = array();

		$sidebar_fields['sidebar'] = esc_html__( 'Sidebar', 'eventalk' );

		$sidebars = get_option( "{$prefix}_custom_sidebars", array() );
		if ( $sidebars ) {
			foreach ( $sidebars as $sidebar ) {
				$sidebar_fields[$sidebar['id']] = $sidebar['name'];
			}
		}

		return $sidebar_fields;
	}

	public static function speakers_query() {
		$cpt = THEME_CPT_PREFIX;
		$args = array(
			'post_type'      => "{$cpt}_speaker",
			'posts_per_page' => RDTheme::$options['speakers_arc_number'],
		);

		$orderby = '';
		switch ( RDTheme::$options['speakers_arc_orderby'] ) {
			case 'title':
			case 'menu_order':
			$orderby = RDTheme::$options['speakers_arc_orderby'];
			$order = 'ASC';
			break;
		}
		if ( $orderby ) {
			$args['orderby'] = $orderby;
			$args['order'] = $order;
		}

		if ( get_query_var('paged') ) {
			$args['paged'] = get_query_var('paged');
		}
		elseif ( get_query_var('page') ) {
			$args['paged'] = get_query_var('page');
		}
		else {
			$args['paged'] = 1;
		}

		$query = new WP_Query( $args );

		return $query;
	}


	public static function wp_set_temp_query( $query ) {
		global $wp_query;
		$temp = $wp_query;
		$wp_query = $query;
		return $temp;
	}

	public static function wp_reset_temp_query( $temp ) {
		global $wp_query;
		$wp_query = $temp;
		wp_reset_postdata();
	}

	public static function custom_date_format($string, $format = 'Y-m-d') {
		return date($format, strtotime($string) );
	}
	public static function vd($args) {
		echo '<pre>';
		var_dump( $args );
		echo '</pre>';
	}

	public static function get_label_by_day_number($array, $needle) {
		$expected_schedules = array_filter($array, function ($schedule) use ($needle) {
			return $schedule['day_number'] == $needle;
		});

		if (count($expected_schedules)){
			return $expected_schedules[0]['schedule_label'];
		}
		return null;


	}



public static function eventalk_get_primary_category() {
		if( get_post_type() != 'post' ) {
			return;
		}
		# Get the first assigned category ----------
			$get_the_category = get_the_category();
			$primary_category = array( $get_the_category[0] );

		if( ! empty( $primary_category[0] )) {
			return $primary_category;
		}
	}

	public static function eventalk_category_prepare() {	?>
		<?php if ( self::eventalk_get_primary_category()  ): ?>
			<a class="item-tag" href="<?php echo get_category_link( self::eventalk_get_primary_category()[0]->term_id ); ?>">
				
				<?php echo esc_html( self::eventalk_get_primary_category()[0]->name ); ?>				
			</a>	
		<?php endif ?>
	<?php 
	}



}