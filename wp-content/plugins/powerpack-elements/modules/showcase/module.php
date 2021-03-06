<?php
namespace PowerpackElements\Modules\Showcase;

use PowerpackElements\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	/**
	 * Module is active or not.
	 *
	 * @since 1.3.3
	 *
	 * @access public
	 *
	 * @return bool true|false.
	 */
	public static function is_active() {
		return true;
	}

	/**
	 * Get Module Name.
	 *
	 * @since 1.3.3
	 *
	 * @access public
	 *
	 * @return string Module name.
	 */
	public function get_name() {
		return 'pp-showcase';
	}

	/**
	 * Get Widgets.
	 *
	 * @since 1.3.3
	 *
	 * @access public
	 *
	 * @return array Widgets.
	 */
	public function get_widgets() {
		return [
			'Showcase',
		];
	}

	/**
	 * Get Image Caption.
	 *
	 * @since 1.3.3
	 *
	 * @access public
	 *
	 * @return string image caption.
	 */
	public static function get_image_caption( $id, $caption_type = 'caption' ) {

		$attachment = get_post( $id );

		if ( 'title' === $caption_type ) {
			$attachment_caption = $attachment->post_title;
		} elseif ( 'caption' === $caption_type ) {
			$attachment_caption = wp_get_attachment_caption( $id );
		}

		return $attachment_caption;

	}

	/**
	 * Get Image Filters.
	 *
	 * @since 1.3.3
	 *
	 * @access public
	 *
	 * @return array image filters.
	 */
	public static function get_image_filters() {

		$pp_image_filters = [
			'normal'            => __( 'Normal', 'powerpack' ),
			'filter-1977'       => __( '1977', 'powerpack' ),
			'filter-aden'       => __( 'Aden', 'powerpack' ),
			'filter-amaro'      => __( 'Amaro', 'powerpack' ),
			'filter-ashby'      => __( 'Ashby', 'powerpack' ),
			'filter-brannan'    => __( 'Brannan', 'powerpack' ),
			'filter-brooklyn'   => __( 'Brooklyn', 'powerpack' ),
			'filter-charmes'    => __( 'Charmes', 'powerpack' ),
			'filter-clarendon'  => __( 'Clarendon', 'powerpack' ),
			'filter-crema'      => __( 'Crema', 'powerpack' ),
			'filter-dogpatch'   => __( 'Dogpatch', 'powerpack' ),
			'filter-earlybird'  => __( 'Earlybird', 'powerpack' ),
			'filter-gingham'    => __( 'Gingham', 'powerpack' ),
			'filter-ginza'      => __( 'Ginza', 'powerpack' ),
			'filter-hefe'       => __( 'Hefe', 'powerpack' ),
			'filter-helena'     => __( 'Helena', 'powerpack' ),
			'filter-hudson'     => __( 'Hudson', 'powerpack' ),
			'filter-inkwell'    => __( 'Inkwell', 'powerpack' ),
			'filter-juno'       => __( 'Juno', 'powerpack' ),
			'filter-kelvin'     => __( 'Kelvin', 'powerpack' ),
			'filter-lark'       => __( 'Lark', 'powerpack' ),
			'filter-lofi'       => __( 'Lofi', 'powerpack' ),
			'filter-ludwig'     => __( 'Ludwig', 'powerpack' ),
			'filter-maven'      => __( 'Maven', 'powerpack' ),
			'filter-mayfair'    => __( 'Mayfair', 'powerpack' ),
			'filter-moon'       => __( 'Moon', 'powerpack' ),
		];

		return $pp_image_filters;
	}
}
