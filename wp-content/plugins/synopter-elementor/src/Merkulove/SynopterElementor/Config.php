<?php
/**
 * Synopter for Elementor
 * Weekly, Daily and Hourly weather forecast for Elementor
 * Exclusively on https://1.envato.market/synopter-elementor
 *
 * @encoding        UTF-8
 * @version         1.1.1
 * @copyright       (C) 2018 - 2021 Merkulove ( https://merkulov.design/ ). All rights reserved.
 * @license         Envato License https://1.envato.market/KYbje
 * @contributors    Vitaliy Nemirovskiy (nemirovskiyvitaliy@gmail.com), Dmitry Merkulov (dmitry@merkulov.design)
 * @support         help@merkulov.design
 **/

namespace Merkulove\SynopterElementor;

use Merkulove\SynopterElementor\Unity\Plugin;
use Merkulove\SynopterElementor\Unity\Settings;
use Merkulove\SynopterElementor\Unity\TabGeneral;

/** Exit if accessed directly. */
if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}

/**
 * SINGLETON: Settings class used to modify default plugin settings.
 *
 * @since 1.0.0
 *
 **/
final class Config {

	/**
	 * The one true Settings.
	 *
     * @since 1.0.0
     * @access private
	 * @var Config
	 **/
	private static $instance;

    /**
     * Prepare plugin settings by modifying the default one.
     *
     * @since 1.0.0
     * @access public
     *
     * @return void
     **/
    public function prepare_settings() {

        /** Get default plugin settings. */
        $tabs = Plugin::get_tabs();

        $offset = 0;
        $tabs = array_slice( $tabs, 0, $offset, true ) +
            [ 'integrations' => [
                'enabled'       => true,
                'class'         => TabGeneral::class,
                'label'         => esc_html__( 'Integrations', 'synopter-elementor' ),
                'title'         => esc_html__( 'OpenWeather Settings', 'synopter-elementor' ),
                'show_title'    => true,
                'icon'          => 'api',
                'fields'        => []
            ] ] +
            array_slice( $tabs, $offset, NULL, true );


        $tabs[ 'integrations' ][ 'fields' ][ 'mdp_synopter_elementor_weather_settings' ] = [
            'type'              => 'text',
            'label'             => esc_html__( 'OpenWeather Key', 'synopter-elementor' ),
            'placeholder'       => esc_html__( 'OpenWeather Key', 'synopter-elementor' ),
            'description'       => esc_html__( 'You can create key by clicking on the link', 'synopter-elementor' ) . ' <a href="https://home.openweathermap.org/users/sign_up" target="_blank">Open Weather</a>',
            'default'           => '',
            'attr'              => [
                'maxlength' => '250'
            ]
        ];

        /** Special config for Elementor plugins. */
        if ( 'elementor' === Plugin::get_type() ) {
            unset( $tabs['general'] );
        }

        /** Set updated tabs. */
        Plugin::set_tabs( $tabs );

        /** Refresh settings. */
        Settings::get_instance()->get_options();

    }

	/**
	 * Main Settings Instance.
	 * Insures that only one instance of Settings exists in memory at any one time.
	 *
	 * @static
     * @since 1.0.0
     * @access public
     *
	 * @return Config
	 **/
	public static function get_instance() {

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof self ) ) {

			self::$instance = new self;

		}

		return self::$instance;

	}

}
