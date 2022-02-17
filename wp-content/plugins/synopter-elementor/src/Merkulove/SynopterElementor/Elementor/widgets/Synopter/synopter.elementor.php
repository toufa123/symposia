<?php /** @noinspection PhpUndefinedClassInspection */
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

/** Exit if accessed directly. */
if ( ! defined( 'ABSPATH' ) ) {
    header( 'Status: 403 Forbidden' );
    header( 'HTTP/1.1 403 Forbidden' );
    exit;
}

use Merkulove\Exception;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use JsonMachine\JsonMachine;
use Merkulove\SynopterElementor\Unity\Plugin as UnityPlugin;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Css_Filter;
use Elementor\Core\Schemes\Color;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Border;
use Merkulove\SynopterElementor\Unity\Settings;

/** @noinspection PhpUnused */
/**
 * Synopter - Custom Elementor Widget.
 **/
class synopter_elementor extends Widget_Base
{

    /**
     * Use this to sort widgets.
     * A smaller value means earlier initialization of the widget.
     * Can take negative values.
     * Default widgets and widgets from 3rd party developers have 0 $mdp_order
     **/
    public $mdp_order = 1;

    /**
     * Widget base constructor.
     * Initializing the widget base class.
     *
     * @access public
     * @param array $data Widget data. Default is an empty array.
     * @param array|null $args Optional. Widget default arguments. Default is null.
     *
     * @return void
     **@throws Exception|Exception If arguments are missing when initializing a full widget instance.
     */
    public function __construct($data = [], $args = null){

        parent::__construct($data, $args);

        /** Register plugin styles. */
        wp_register_style(
    'mdp-synopter-elementor',
        UnityPlugin::get_url() . 'css/synopter-elementor' . UnityPlugin::get_suffix() . '.css',
            [],
            UnityPlugin::get_version()
        );

        /** Register the plugin styles for the admin part. */
        wp_register_style(
    'mdp-synopter-elementor-admin',
        UnityPlugin::get_url() . 'src/Merkulove/Unity/assets/css/elementor-admin' . UnityPlugin::get_suffix() . '.css',
            [],
            UnityPlugin::get_version()
        );

        /** Register jquery plugin (https://jqueryui.com). */
        wp_register_script(
    'jquery-ui',
        UnityPlugin::get_url() . 'js/jquery-ui' . UnityPlugin::get_suffix() . '.js', ['jquery'],
            UnityPlugin::get_version(),
    true
        );

    }

    /**
     * Return a widget name.
     *
     * @return string
     **/
    public function get_name(){ return 'mdp-synopter-elementor'; }

    /**
     * Return the widget title that will be displayed as the widget label.
     *
     * @return string
     **/
    public function get_title(){ return esc_html__('Synopter', 'synopter-elementor'); }

    /**
     * Set the widget icon.
     *
     * @return string
     */
    public function get_icon(){ return 'mdp-synopter-elementor-widget-icon'; }

    /**
     * Set the category of the widget.
     *
     * @return array with category names
     **/
    public function get_categories(){ return ['general']; }

    /**
     * Get widget keywords. Retrieve the list of keywords the widget belongs to.
     *
     * @access public
     *
     * @return array Widget keywords.
     **/
    public function get_keywords(){ return ['Merkulove', 'Synopter', 'weather', 'forecast', 'synoptic']; }

    /**
     * Get style dependencies.
     * Retrieve the list of style dependencies the widget requires.
     *
     * @access public
     *
     * @return array Widget styles dependencies.
     **/
    public function get_style_depends(){ return ['mdp-synopter-elementor', 'mdp-synopter-elementor-admin']; }

    /**
     * Get script dependencies.
     * Retrieve the list of script dependencies the element requires.
     *
     * @access public
     *
     * @return array Element scripts dependencies.
     **/
    public function get_script_depends(){ return []; }

    /**
     * We get a list of available countries.
     *
     * @since 1.0.0
     * @access public
     *
     * @return array
     */
    public function get_country_options(){

        /** A variable that will store an array of countries. */
        $country_code = [
            'IR' => 'IR',
            'CY' => 'CY',
            'SO' => 'SO',
            'YE' => 'YE',
            'LY' => 'LY',
            'IQ' => 'IQ',
            'SA' => 'SA',
            'AO' => 'AO',
            'AZ' => 'AZ',
            'TZ' => 'TZ',
            'TM' => 'TM',
            'SY' => 'SY',
            'AM' => 'AM',
            'ZM' => 'ZM',
            'KE' => 'KE',
            'RW' => 'RW',
            'CD' => 'CD',
            'DJ' => 'DJ',
            'UG' => 'UG',
            'MW' => 'MW',
            'CF' => 'CF',
            'SC' => 'SC',
            'TD' => 'TD',
            'JO' => 'JO',
            'GR' => 'GR',
            'LB' => 'LB',
            'PS' => 'PS',
            'IL' => 'IL',
            'KW' => 'KW',
            'OM' => 'OM',
            'QA' => 'QA',
            'BH' => 'BH',
            'AE' => 'AE',
            'TR' => 'TR',
            'ET' => 'ET',
            'ER' => 'ER',
            'EG' => 'EG',
            'AL' => 'AL',
            'SD' => 'SD',
            'SS' => 'SS',
            'BI' => 'BI',
            'RU' => 'RU',
            'LV' => 'LV',
            'EE' => 'EE',
            'LT' => 'LT',
            'UZ' => 'UZ',
            'SE' => 'SE',
            'KZ' => 'KZ',
            'GE' => 'GE',
            'UA' => 'UA',
            'MD' => 'MD',
            'BY' => 'BY',
            'FI' => 'FI',
            'RO' => 'RO',
            'HU' => 'HU',
            'SK' => 'SK',
            'BG' => 'BG',
            'PL' => 'PL',
            'RS' => 'RS',
            'MK' => 'MK',
            'XK' => 'XK',
            'NA' => 'NA',
            'ZW' => 'ZW',
            'KM' => 'KM',
            'YT' => 'YT',
            'LS' => 'LS',
            'BW' => 'BW',
            'MU' => 'MU',
            'SZ' => 'SZ',
            'RE' => 'RE',
            'ZA' => 'ZA',
            'MZ' => 'MZ',
            'MG' => 'MG',
            'PK' => 'PK',
            'TH' => 'TH',
            'AF' => 'AF',
            'IN' => 'IN',
            'BD' => 'BD',
            'ID' => 'ID',
            'TJ' => 'TJ',
            'MY' => 'MY',
            'KG' => 'KG',
            'LK' => 'LK',
            'BT' => 'BT',
            'CN' => 'CN',
            'MV' => 'MV',
            'NP' => 'NP',
            'MM' => 'MM',
            'MN' => 'MN',
            'TF' => 'TF',
            'VN' => 'VN',
            'TL' => 'TL',
            'LA' => 'LA',
            'TW' => 'TW',
            'PH' => 'PH',
            'HK' => 'HK',
            'BN' => 'BN',
            'MO' => 'MO',
            'KH' => 'KH',
            'KR' => 'KR',
            'JP' => 'JP',
            'KP' => 'KP',
            'SG' => 'SG',
            'AU' => 'AU',
            'CX' => 'CX',
            'FM' => 'FM',
            'PG' => 'PG',
            'SB' => 'SB',
            'KI' => 'KI',
            'TV' => 'TV',
            'MH' => 'MH',
            'VU' => 'VU',
            'NC' => 'NC',
            'NF' => 'NF',
            'NZ' => 'NZ',
            'FJ' => 'FJ',
            'CM' => 'CM',
            'SN' => 'SN',
            'CG' => 'CG',
            'PT' => 'PT',
            'LR' => 'LR',
            'CI' => 'CI',
            'GH' => 'GH',
            'GQ' => 'GQ',
            'NG' => 'NG',
            'BF' => 'BF',
            'TG' => 'TG',
            'GW' => 'GW',
            'MR' => 'MR',
            'BJ' => 'BJ',
            'GA' => 'GA',
            'SL' => 'SL',
            'ST' => 'ST',
            'GI' => 'GI',
            'GM' => 'GM',
            'GN' => 'GN',
            'NE' => 'NE',
            'ML' => 'ML',
            'EH' => 'EH',
            'TN' => 'TN',
            'DZ' => 'DZ',
            'ES' => 'ES',
            'IT' => 'IT',
            'MA' => 'MA',
            'MT' => 'MT',
            'DK' => 'DK',
            'FO' => 'FO',
            'IS' => 'IS',
            'GB' => 'GB',
            'CH' => 'CH',
            'SJ' => 'SJ',
            'NL' => 'NL',
            'AT' => 'AT',
            'BE' => 'BE',
            'DE' => 'DE',
            'LU' => 'LU',
            'IE' => 'IE',
            'FR' => 'FR',
            'MC' => 'MC',
            'AD' => 'AD',
            'AX' => 'AX',
            'LI' => 'LI',
            'JE' => 'JE',
            'IM' => 'IM',
            'GG' => 'GG',
            'CZ' => 'CZ',
            'NO' => 'NO',
            'SM' => 'SM',
            'BA' => 'BA',
            'HR' => 'HR',
            'SI' => 'SI',
            'ME' => 'ME',
            'SH' => 'SH',
            'BB' => 'BB',
            'CV' => 'CV',
            'GY' => 'GY',
            'GF' => 'GF',
            'SR' => 'SR',
            'BR' => 'BR',
            'GL' => 'GL',
            'PM' => 'PM',
            'GS' => 'GS',
            'FK' => 'FK',
            'AR' => 'AR',
            'PY' => 'PY',
            'UY' => 'UY',
            'VE' => 'VE',
            'MX' => 'MX',
            'JM' => 'JM',
            'DO' => 'DO',
            'CW' => 'CW',
            'SX' => 'SX',
            'CU' => 'CU',
            'MQ' => 'MQ',
            'BS' => 'BS',
            'BM' => 'BM',
            'AI' => 'AI',
            'TT' => 'TT',
            'KN' => 'KN',
            'DM' => 'DM',
            'AG' => 'AG',
            'LC' => 'LC',
            'TC' => 'TC',
            'AW' => 'AW',
            'VG' => 'VG',
            'VC' => 'VC',
            'MS' => 'MS',
            'GP' => 'GP',
            'MF' => 'MF',
            'BL' => 'BL',
            'GD' => 'GD',
            'KY' => 'KY',
            'BZ' => 'BZ',
            'SV' => 'SV',
            'GT' => 'GT',
            'HN' => 'HN',
            'NI' => 'NI',
            'CR' => 'CR',
            'EC' => 'EC',
            'CO' => 'CO',
            'PE' => 'PE',
            'PA' => 'PA',
            'HT' => 'HT',
            'CL' => 'CL',
            'BO' => 'BO',
            'PN' => 'PN',
            'TO' => 'TO',
            'PF' => 'PF',
            'WF' => 'WF',
            'WS' => 'WS',
            'CK' => 'CK',
            'NU' => 'NU',
            'GU' => 'GU',
            'US' => 'US',
            'PR' => 'PR',
            'VI' => 'VI',
            'AS' => 'AS',
            'CA' => 'CA',
            'VA' => 'VA',
            'PW' => 'PW',
            'CC' => 'CC',
            'NR' => 'NR',
            'MP' => 'MP',
            'BQ' => 'BQ'
        ];

        /** Json with the full name of the countries. */
        $FullCountryNameJson = file_get_contents( UnityPlugin::get_url() . 'json/country.list.json' );
        $FullCountryName = json_decode( $FullCountryNameJson, true, 10 );

        /** Will save an array of countries with full name. */
        $country_name = null;

        /** Create an array of countries. */
        foreach ( $FullCountryName as $name){ $country_name[$name['Code']] = $name['Name']; }

        /** Combine the arrays by country code into one array. */
        return array_merge( $country_code, $country_name);

    }

    /**
     * Returns a list of languages.
     *
     * @since 1.0.0
     * @access public
     *
     * @return array
     */
    public function get_language_options() {

        /** We are connected to the file which contains the list of countries. */
        $json = file_get_contents( UnityPlugin::get_url() . 'json/language.list.json' );
        $CodeLanguage = json_decode( $json, true );

        /** A variable that will store an array of countries. */
        $result = null;

        /** Save an array of countries into a variable. */
        foreach ($CodeLanguage as $i => $iValue ) {
            $result[ $iValue['code']] = $CodeLanguage[$i]['name'];
        }

        return $result;

    }

    /**
     * A group of field settings with a background and a border around an element.
     *
     * @param $type - Layout type.
     * @param $slug - The name of the item for which the settings will apply.
     * @param $heading - Title for the settings group.
     *
     * @since 1.0.0
     * @access public
     *
     */
    public function controls_text_background_box( $type, $slug ) {

        /** We generate the class name for setting styles. */
        $class = '{{WRAPPER}} .mdp-' . $type . '-'. str_replace('_', '-', $slug );

        /** Width */
        $this->add_responsive_control(
            'style_' . $type . '_width' . $slug,
            [
                'label' => esc_html__( 'Width', 'synopter-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ '%', 'px' ],
                'default' => [
                    'unit' => '%',
                    'size' => '',
                ],
                'selectors' => [
                    $class => 'width: {{SIZE}}{{UNIT}};',
                ]
            ]
        );

        /** Margin. */
        $this->add_responsive_control(
            'style_'.$type.'_margin_'. $slug,
            [
                'label' => esc_html__( 'Margin', 'synopter-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    $class => 'margin: {{top}}{{unit}} {{right}}{{unit}} {{bottom}}{{unit}} {{left}}{{unit}};',
                ],
                'toggle' => true,
            ]
        );

        /** Padding. */
        $this->add_responsive_control(
            'style_'.$type.'_padding_'. $slug,
            [
                'label' => esc_html__( 'Padding', 'synopter-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    $class => 'padding: {{top}}{{unit}} {{right}}{{unit}} {{bottom}}{{unit}} {{left}}{{unit}};',
                ],
                'toggle' => true,
            ]
        );

        /** Color. */
        /** @noinspection PhpUndefinedClassInspection */
        $this->add_control(
            "style_{$type}_color_{$slug}",
            [
                'label' => esc_html__( 'Color', 'synopter-elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Color::get_type(),
                    'value' => Color::COLOR_3,
                ],
                'selectors' => [$class => 'color: {{VALUE}}']
            ]
        );

        /** Text alignment */
        $this->add_responsive_control(
            'style_'.$type.'_align_'. $slug,
            [
                'label' => esc_html__( 'Alignment', 'synopter-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'synopter-elementor' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'synopter-elementor' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'synopter-elementor' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    $class => 'text-align: {{VALUE}}',
                ],
                'toggle' => true,
            ]
        );

        /** Typography. */
        /** @noinspection PhpUndefinedClassInspection */
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'style_'.$type.'_typography_' . $slug,
                'label' => esc_html__( 'Typography', 'synopter-elementor' ),
                'scheme' => Typography::TYPOGRAPHY_3,
                'selector' => $class,
            ]
        );

        /** Text Shadow. */
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'style_'.$type.'_shadow_'. $slug,
                'label' => esc_html__( 'Text Shadow', 'synopter-elementor' ),
                'selector' => $class,
            ]
        );

        /** Background. */
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'style_'.$type.'_text_background_box_'. $slug,
                'label' => esc_html__( 'Background', 'synopter-elementor' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => $class,
            ]
        );

        /** Border style. */
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'style_'.$type.'_box_border_style_'. $slug,
                'label' =>  esc_html__( 'Border', 'synopter-elementor' ),
                'selector' => $class,
            ]
        );

        /** Border radius. */
        $this->add_responsive_control(
            'style_'.$type.'_box_border_radius_'. $slug,
            [
                'label' => esc_html__( 'Border radius', 'synopter-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    $class => 'border-radius: {{top}}{{unit}} {{right}}{{unit}} {{bottom}}{{unit}} {{left}}{{unit}};',
                ],
                'toggle' => true,
            ]
        );

        /** Box Shadow. */
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'style_'.$type.'_box_shadow_'. $slug,
                'label' => esc_html__( 'Box Shadow', 'synopter-elementor' ),
                'selector' => $class,
            ]
        );

        /** Icon Padding */
        $this->add_responsive_control(
            'style_'.$type.'_icon_padding_'. $slug,
            [
                'label' => esc_html__( 'Icon Padding', 'synopter-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    $class . ' i' => 'padding: {{top}}{{unit}} {{right}}{{unit}} {{bottom}}{{unit}} {{left}}{{unit}};',
                ],
                'separator' => 'before',
                'toggle' => true,
            ]
        );

        /** Icon Margin */
        $this->add_responsive_control(
            'style_'.$type.'_icon_margin_'. $slug,
            [
                'label' => esc_html__( 'Icon Margin', 'synopter-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    $class . ' i' => 'margin: {{top}}{{unit}} {{right}}{{unit}} {{bottom}}{{unit}} {{left}}{{unit}};',
                ],
                'toggle' => true,
            ]
        );

        /** Icon Color */
        $this->add_control(
            'style_'.$type.'_icon_color_'. $slug,
            [
                'label' => esc_html__( 'Color', 'synopter-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    $class . ' i' => 'color: {{VALUE}}',
                ],
            ]
        );

        /** Icon Background. */
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'style_'.$type.'_icon_background_box_'. $slug,
                'label' => esc_html__( 'Background', 'synopter-elementor' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => $class . ' i',
            ]
        );

        /** Icon Border radius. */
        $this->add_responsive_control(
            'style_'.$type.'_icon_border_radius_'. $slug,
            [
                'label' => esc_html__( 'Icon Border radius', 'synopter-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    $class . ' i' => 'border-radius: {{top}}{{unit}} {{right}}{{unit}} {{bottom}}{{unit}} {{left}}{{unit}};',
                ],
                'toggle' => true,
            ]
        );

    }

    /**
     * Group of fields to set the image size.
     *
     * @param $type - Layout type.
     * @param $heading - Title for the settings group.
     */
    public function controls_img_size( $type, $heading ) {

        /** We generate the class name for setting styles. */
        $class = '{{WRAPPER}} .mdp-' . $type . '-img-icon';

        /** Header img width. */
        $this->add_control(
            'style_'.$type.'_img_heading',
            [
                'label' => esc_html__( $heading, 'synopter-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => ['synopter_' . $type . '_icon' => 'yes']
            ]
        );

        /** Image width. */
        $this->add_responsive_control(
            'style_' . $type . '_img_width',
            [
                'label' => esc_html__( 'Width', 'synopter-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    $class => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => ['synopter_' . $type . '_icon' => 'yes']
            ]
        );

        /** Image height. */
        $this->add_responsive_control(
            'style_' . $type . '_img_height',
            [
                'label' => esc_html__( 'Height', 'synopter-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    $class => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => ['synopter_' . $type . '_icon' => 'yes']
            ]
        );

        /** Box Shadow. */
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'style_'.$type.'_img_shadow',
                'label' => esc_html__( 'Box Shadow', 'synopter-elementor' ),
                'selector' => $class,
                'condition' => ['synopter_' . $type . '_icon' => 'yes']
            ]
        );

        /** Icon Padding */
        $this->add_responsive_control(
            'style_'.$type.'_icon_padding',
            [
                'label' => esc_html__( 'Icon Padding', 'synopter-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    $class => 'padding: {{top}}{{unit}} {{right}}{{unit}} {{bottom}}{{unit}} {{left}}{{unit}};',
                ],
                'condition' => ['synopter_' . $type . '_icon' => 'yes'],
                'separator' => 'before',
                'toggle' => true,
            ]
        );

        /** Icon Margin */
        $this->add_responsive_control(
            'style_'.$type.'_icon_margin',
            [
                'label' => esc_html__( 'Icon Margin', 'synopter-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    $class => 'margin: {{top}}{{unit}} {{right}}{{unit}} {{bottom}}{{unit}} {{left}}{{unit}};',
                ],
                'condition' => ['synopter_' . $type . '_icon' => 'yes'],
                'toggle' => true,
            ]
        );

        /** Icon Color */
        $this->add_control(
            'style_'.$type.'_icon_color',
            [
                'label' => esc_html__( 'Color', 'synopter-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    $class . ' i' => 'color: {{VALUE}}',
                ],
                'condition' => ['synopter_' . $type . '_icon' => 'yes']
            ]
        );

        /** CSS Filter */
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'style_'.$type.'_css_filter',
                'selector' => $class,
            ]
        );

        /** Alignment Icon */
        $this->add_responsive_control(
            'style_'.$type.'_icon_align',
            [
                'label' => esc_html__( 'Icon', 'synopter-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Left', 'synopter-elementor' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'synopter-elementor' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'Right', 'synopter-elementor' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'condition' => ['synopter_' . $type . '_icon' => 'yes'],
                'default' => 'center',
                'selectors' => [
                    $class => 'align-self: {{VALUE}};',
                ],
                'toggle' => true,
            ]
        );

        /** Icon Background. */
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'style_'.$type.'_icon_background_box',
                'label' => esc_html__( 'Background', 'synopter-elementor' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => $class,
                'condition' => ['synopter_' . $type . '_icon' => 'yes']
            ]
        );

        /** Icon Border radius. */
        $this->add_responsive_control(
            'style_'.$type.'_icon_border_radius',
            [
                'label' => esc_html__( 'Icon Border radius', 'synopter-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    $class => 'border-radius: {{top}}{{unit}} {{right}}{{unit}} {{bottom}}{{unit}} {{left}}{{unit}};',
                ],
                'toggle' => true,
                'condition' => ['synopter_' . $type . '_icon' => 'yes']
            ]
        );

    }

    /**
     * Section layout. Layout order settings.
     *
     * @since 1.0.0
     * @access public
     */
    public function section_layout() {

        /** Start section layout. */
        $this->start_controls_section( 'section_layout',
            [ 'label' => esc_html__( 'Layout', 'synopter-elementor' ) ] );

        $this->add_control(
            'synopter_auto_location',
            [
                'label' => esc_html__( 'Auto location', 'synopter-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
            ]
        );

        /** Country. */
        $this->add_control(
            'location_country',
            [
                'label' => esc_html__( 'Country', 'synopter-elementor' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => false,
                'options' => $this->get_country_options(),
                'default' => 'UA',
                'label_block' => true,
                'condition' => [ 'synopter_auto_location!' => 'yes' ]
            ]
        );

        /** City. */
        $this->add_control(
            'location_city_name',
            [
                'label' => esc_html__( 'City', 'synopter-elementor' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => esc_html__( 'Mykolayiv', 'synopter-elementor' ),
                'placeholder' => esc_html__( 'City', 'synopter-elementor' ),
                'separator' => 'after',
                'condition' => [ 'synopter_auto_location!' => 'yes' ]
            ]
        );

        /** Language country. */
        $this->add_control(
            'language_country',
            [
                'label' => esc_html__( 'Language', 'synopter-elementor' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => false,
                'options' => $this->get_language_options(),
                'default' => 'uk',
                'label_block' => true
            ]
        );

        /** Units. */
        $this->add_control(
            'location_units',
            [
                'label' => esc_html__( 'Units', 'synopter-elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'standard' => esc_html__( 'Kelvin', 'synopter-elementor' ),
                    'metric' => esc_html__( 'Celsius', 'synopter-elementor' ),
                    'imperial' => esc_html__( 'Fahrenheit', 'synopter-elementor' ),
                ],
                'default' => 'metric',
            ]
        );

        /** Round. */
        $this->add_control(
            'synopter_round',
            [
                'label' => esc_html__( 'Round', 'synopter-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes'
            ]
        );

        /** End section layout. */
        $this->end_controls_section();

    }

    /**
     * Section weather. Settings section for weather the next three hours.
     *
     * @since 1.0.0
     * @access public
     */
    public function section_weather() {

        $this->start_controls_section( 'section_weather', [
            'label' => esc_html__( 'Current weather', 'synopter-elementor' )]
        );

        /** Info header. */
        $this->add_control(
            'synopter_weather_info',
            [
                'label' => esc_html__( 'Location Info', 'synopter-elementor' ),
                'type' => Controls_Manager::HEADING,
            ]
        );

        /** Alignment Location. */
        $this->add_responsive_control(
            'style_weather_align_location-box',
            [
                'label' => esc_html__( 'Location', 'synopter-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Left', 'synopter-elementor' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'synopter-elementor' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'Right', 'synopter-elementor' ),
                        'icon' => 'fa fa-align-right',
                    ],
                    'space-around' => [
                        'title' => esc_html__( 'Space around', 'synopter-elementor' ),
                        'icon' => 'fa fa-compress-arrows-alt',
                    ],
                    'space-between' => [
                        'title' => esc_html__( 'Space between', 'synopter-elementor' ),
                        'icon' => 'fa fa-expand-arrows-alt',
                    ],
                ],
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'synopter_weather_country',
                            'operator' => '==',
                            'value' => 'yes'
                        ],
                        [
                            'name' => 'synopter_weather_location',
                            'operator' => '==',
                            'value' => 'yes'
                        ],
                        [
                            'name' => 'synopter_weather_population',
                            'operator' => '==',
                            'value' => 'yes'
                        ],
                    ]
                ],
                'default' => 'space-between',
                'selectors' => [
                    '{{WRAPPER}} .mdp-weather-location-box' => 'justify-content: {{VALUE}};',
                ],
                'toggle' => true,
            ]
        );

        /** Reflect Location */
        $this->add_control(
            'style_location_reflect',
            [
                'label' => esc_html__( 'Reflect Location', 'synopter-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'selectors' => [
                    '{{WRAPPER}} .mdp-weather-location-box' => 'flex-direction: row-reverse;',
                ],
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'synopter_weather_country',
                            'operator' => '==',
                            'value' => 'yes'
                        ],
                        [
                            'name' => 'synopter_weather_location',
                            'operator' => '==',
                            'value' => 'yes'
                        ],
                        [
                            'name' => 'synopter_weather_population',
                            'operator' => '==',
                            'value' => 'yes'
                        ],
                    ]
                ],
                'separator' => 'after',
            ]
        );

        /** City. */
        $this->add_control(
            'synopter_weather_location',
            [
                'label' => esc_html__( 'City', 'synopter-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        /** City caption */
        $this->add_control(
            'synopter_weather_location_caption',
            [
                'label' => esc_html__( 'City Caption', 'synopter-elementor' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Location:', 'synopter-elementor' ),
                'placeholder' => esc_html__( 'City caption', 'synopter-elementor' ),
                'condition' => ['synopter_weather_location' => 'yes']
            ]
        );

        /** City Icon. */
        $this->add_control(
            'synopter_weather_location_icon',
            [
                'label' => esc_html__( 'City Icon', 'synopter-elementor' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'condition' => ['synopter_weather_location' => 'yes']
            ]
        );

        /** Country */
        $this->add_control(
            'synopter_weather_country',
            [
                'label' => esc_html__( 'Country', 'synopter-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes'
            ]
        );

        /** Country caption */
        $this->add_control(
            'synopter_weather_country_caption',
            [
                'label' => esc_html__( 'Country Caption', 'synopter-elementor' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Country caption', 'synopter-elementor' ),
                'condition' => ['synopter_weather_country' => 'yes']
            ]
        );

        /** Country Icon. */
        $this->add_control(
            'synopter_weather_country_icon',
            [
                'label' => esc_html__( 'Country Icon', 'synopter-elementor' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'condition' => ['synopter_weather_country' => 'yes']
            ]
        );

        /** Population. */
        $this->add_control(
            'synopter_weather_population',
            [
                'label' => esc_html__( 'Population', 'synopter-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes'
            ]
        );

        /** Population caption */
        $this->add_control(
            'synopter_weather_population_caption',
            [
                'label' => esc_html__( 'Population Caption', 'synopter-elementor' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Population:', 'synopter-elementor' ),
                'placeholder' => esc_html__( 'Population caption', 'synopter-elementor' ),
                'condition' => ['synopter_weather_population' => 'yes']
            ]
        );

        /** Population Icon. */
        $this->add_control(
            'synopter_weather_population_icon',
            [
                'label' => esc_html__( 'Population Icon', 'synopter-elementor' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'condition' => ['synopter_weather_population' => 'yes']
            ]
        );

        /** Degrees header. */
        $this->add_control(
            'synopter_degrees_header',
            [
                'label' => esc_html__( 'Degrees', 'synopter-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        /** Degrees Alignment */
        $this->add_responsive_control(
            'style_weather_align_temp_',
            [
                'label' => esc_html__( 'Degrees', 'synopter-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Left', 'synopter-elementor' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'synopter-elementor' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'Right', 'synopter-elementor' ),
                        'icon' => 'fa fa-align-right',
                    ],
                    'space-around' => [
                        'title' => esc_html__( 'Space around', 'synopter-elementor' ),
                        'icon' => 'fa fa-compress-arrows-alt',
                    ],
                    'space-between' => [
                        'title' => esc_html__( 'Space between', 'synopter-elementor' ),
                        'icon' => 'fa fa-expand-arrows-alt',
                    ],
                ],
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'synopter_weather_temp',
                            'operator' => '==',
                            'value' => 'yes'
                        ],
                        [
                            'name' => 'synopter_weather_feels_like',
                            'operator' => '==',
                            'value' => 'yes'
                        ],
                    ]
                ],
                'default' => 'space-between',
                'selectors' => [
                    '{{WRAPPER}} .mdp-weather-temp-box' => 'justify-content: {{VALUE}};',
                ],
                'separator' => 'after',
                'toggle' => true,
            ]
        );

        /** Temp. */
        $this->add_control(
            'synopter_weather_temp',
            [
                'label' => esc_html__( 'Temperature', 'synopter-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        /** Temperature caption */
        $this->add_control(
            'synopter_weather_temp_caption',
            [
                'label' => esc_html__( 'Temperature Caption', 'synopter-elementor' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Temperature caption', 'synopter-elementor' ),
                'condition' => [ 'synopter_weather_temp' => 'yes' ]
            ]
        );

        /** Temp Icon */
        $this->add_control(
            'synopter_weather_temp_icon',
            [
                'label' => esc_html__( 'Temperature Icon', 'synopter-elementor' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'condition' => ['synopter_weather_temp' => 'yes']
            ]
        );

        /** Feels like. */
        $this->add_control(
            'synopter_weather_feels_like',
            [
                'label' => esc_html__( 'Feels like', 'synopter-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes'
            ]
        );

        /** Feels like caption */
        $this->add_control(
            'synopter_weather_feels_like_caption',
            [
                'label' => esc_html__( 'Feels like caption', 'synopter-elementor' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Feels like', 'synopter-elementor' ),
                'placeholder' => esc_html__( 'Feels like caption', 'synopter-elementor' ),
                'condition' => ['synopter_weather_feels_like' => 'yes']
            ]
        );

        /** Feels like Icon. */
        $this->add_control(
            'synopter_weather_feels_like_icon',
            [
                'label' => esc_html__( 'Feels like Icon', 'synopter-elementor' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'condition' => ['synopter_weather_feels_like' => 'yes']
            ]
        );

        /** Icon header. */
        $this->add_control(
            'synopter_icon_header',
            [
                'label' => esc_html__( 'Icon', 'synopter-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        /** Icon. */
        $this->add_control(
            'synopter_weather_icon',
            [
                'label' => esc_html__( 'Icon', 'synopter-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        /** Icon style */
        $this->add_control(
            'synopter_weather_icon_style',
            [
                'label' => esc_html__( 'Icon style', 'synopter-elementor' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'real/',
                'options' => [
                    'real/'     => esc_html__( 'Realistic', 'synopter-elementor' ),
                    'gradient/' => esc_html__( 'Gradient', 'synopter-elementor' ),
                    'outline/'  => esc_html__( 'Outline', 'synopter-elementor' ),
                ],
                'condition' => ['synopter_weather_icon' => 'yes']
            ]
        );

        /** Description. */
        $this->add_control(
            'synopter_weather_description',
            [
                'label' => esc_html__( 'Description', 'synopter-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
            ]
        );

        /** Conditions header. */
        $this->add_control(
            'synopter_weather_conditions',
            [
                'label' => esc_html__( 'Weather Conditions', 'synopter-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        /** Alignment Info. */
        $this->add_responsive_control(
            'style_weather_align_info',
            [
                'label' => esc_html__( 'Info', 'synopter-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Left', 'synopter-elementor' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'synopter-elementor' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'Right', 'synopter-elementor' ),
                        'icon' => 'fa fa-align-right',
                    ],
                    'space-around' => [
                        'title' => esc_html__( 'Space around', 'synopter-elementor' ),
                        'icon' => 'fa fa-compress-arrows-alt',
                    ],
                    'space-between' => [
                        'title' => esc_html__( 'Space between', 'synopter-elementor' ),
                        'icon' => 'fa fa-expand-arrows-alt',
                    ],
                ],
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'synopter_weather_pressure',
                            'operator' => '==',
                            'value' => 'yes'
                        ],
                        [
                            'name' => 'synopter_weather_humidity',
                            'operator' => '==',
                            'value' => 'yes'
                        ],
                        [
                            'name' => 'synopter_weather_clouds_all',
                            'operator' => '==',
                            'value' => 'yes'
                        ],
                        [
                            'name' => 'synopter_weather_wind_speed',
                            'operator' => '==',
                            'value' => 'yes'
                        ],
                        [
                            'name' => 'synopter_weather_wind_deg',
                            'operator' => '==',
                            'value' => 'yes'
                        ],
                    ]
                ],
                'default' => 'space-between',
                'selectors' => [
                    '{{WRAPPER}} .mdp-weather-info-box' => 'justify-content: {{VALUE}};',
                ],
                'toggle' => true,
            ]
        );

        /** Reflect Weather Conditions */
        $this->add_control(
            'style_conditions_reflect',
            [
                'label' => esc_html__( 'Reflect Weather Conditions', 'synopter-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'selectors' => [
                    '{{WRAPPER}} .mdp-weather-info-box' => 'flex-direction: row-reverse;',
                ],
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'synopter_weather_pressure',
                            'operator' => '==',
                            'value' => 'yes'
                        ],
                        [
                            'name' => 'synopter_weather_humidity',
                            'operator' => '==',
                            'value' => 'yes'
                        ],
                        [
                            'name' => 'synopter_weather_clouds_all',
                            'operator' => '==',
                            'value' => 'yes'
                        ],
                        [
                            'name' => 'synopter_weather_wind_speed',
                            'operator' => '==',
                            'value' => 'yes'
                        ],
                        [
                            'name' => 'synopter_weather_wind_deg',
                            'operator' => '==',
                            'value' => 'yes'
                        ],
                    ]
                ],
                'separator' => 'after',
            ]
        );

        /** Pressure. */
        $this->add_control(
            'synopter_weather_pressure',
            [
                'label' => esc_html__( 'Pressure', 'synopter-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes'
            ]
        );

        /** Pressure caption */
        $this->add_control(
            'synopter_weather_pressure_caption',
            [
                'label' => esc_html__( 'Pressure caption', 'synopter-elementor' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Pressure caption', 'synopter-elementor' ),
                'condition' => ['synopter_weather_pressure' => 'yes']
            ]
        );

        /** Pressure Icon. */
        $this->add_control(
            'synopter_weather_pressure_icon',
            [
                'label' => esc_html__( 'Pressure Icon', 'synopter-elementor' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'default' => [
                    'value' => 'fas fa-weight-hanging',
                    'library' => 'fa-solid',
                ],
                'condition' => ['synopter_weather_pressure' => 'yes']
            ]
        );

        /** Humidity. */
        $this->add_control(
            'synopter_weather_humidity',
            [
                'label' => esc_html__( 'Humidity', 'synopter-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes'
            ]
        );

        /** Humidity caption */
        $this->add_control(
            'synopter_weather_humidity_caption',
            [
                'label' => esc_html__( 'Humidity caption', 'synopter-elementor' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Humidity caption', 'synopter-elementor' ),
                'condition' => ['synopter_weather_humidity' => 'yes']
            ]
        );

        /** Humidity Icon. */
        $this->add_control(
            'synopter_weather_humidity_icon',
            [
                'label' => esc_html__( 'Humidity Icon', 'synopter-elementor' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'default' => [
                    'value' => 'fas fa-water',
                    'library' => 'fa-solid',
                ],
                'condition' => ['synopter_weather_humidity' => 'yes']
            ]
        );

        /** Cloudy. */
        $this->add_control(
            'synopter_weather_clouds_all',
            [
                'label' => esc_html__( 'Cloudy', 'synopter-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes'
            ]
        );

        /** Cloudy caption */
        $this->add_control(
            'synopter_weather_clouds_all_caption',
            [
                'label' => esc_html__( 'Cloudy caption', 'synopter-elementor' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Cloudy caption', 'synopter-elementor' ),
                'condition' => ['synopter_weather_clouds_all' => 'yes']
            ]
        );

        /** Cloudy Icon. */
        $this->add_control(
            'synopter_weather_clouds_all_icon',
            [
                'label' => esc_html__( 'Cloudy Icon', 'synopter-elementor' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'default' => [
                    'value' => 'fas fa-cloud',
                    'library' => 'fa-solid',
                ],
                'condition' => ['synopter_weather_clouds_all' => 'yes']
            ]
        );

        /** Wind speed. */
        $this->add_control(
            'synopter_weather_wind_speed',
            [
                'label' => esc_html__( 'Wind speed', 'synopter-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes'
            ]
        );

        /** Wind speed caption */
        $this->add_control(
            'synopter_weather_wind_speed_caption',
            [
                'label' => esc_html__( 'Wind speed caption', 'synopter-elementor' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Wind speed caption', 'synopter-elementor' ),
                'condition' => ['synopter_weather_wind_speed' => 'yes']
            ]
        );

        /** Wind speed Icon. */
        $this->add_control(
            'synopter_weather_wind_speed_icon',
            [
                'label' => esc_html__( 'Wind speed Icon', 'synopter-elementor' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'default' => [
                    'value' => 'fas fa-wind',
                    'library' => 'fa-solid',
                ],
                'condition' => ['synopter_weather_wind_speed' => 'yes']
            ]
        );

        /** Wind direction. */
        $this->add_control(
            'synopter_weather_wind_deg',
            [
                'label' => esc_html__( 'Wind direction', 'synopter-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes'
            ]
        );

        /** Wind direction caption */
        $this->add_control(
            'synopter_weather_wind_deg_caption',
            [
                'label' => esc_html__( 'Wind direction caption', 'synopter-elementor' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Wind direction caption', 'synopter-elementor' ),
                'condition' => ['synopter_weather_wind_deg' => 'yes']
            ]
        );

        /** Wind direction Icon. */
        $this->add_control(
            'synopter_weather_wind_deg_icon',
            [
                'label' => esc_html__( 'Wind direction Icon', 'synopter-elementor' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'default' => [
                    'value' => 'fas fa-location-arrow',
                    'library' => 'fa-solid',
                ],
                'condition' => ['synopter_weather_wind_deg' => 'yes']
            ]
        );

        $this->end_controls_section();

    }

    /**
     * Style settings for current weather.
     *
     * @since 1.0.0
     * @access public
     */
    public function style_current_weather(){

        $this->start_controls_section( 'style_weather',
            [
                'label' => esc_html__( 'Current weather', 'synopter-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        /** Reflect current weather */
        $this->add_control(
            'style_weather_rows',
            [
                'label' => esc_html__( 'Reflect rows', 'synopter-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'selectors' => [
                    '{{WRAPPER}} .mdp-weather-container' => 'flex-direction: column-reverse;',
                ],
            ]
        );

        $this->end_controls_section();

        /** Weather forecast - City Style */
        $this->style_as_section( 'weather', 'location', 'City' );

        /** Weather forecast - Country Style */
        $this->style_as_section( 'weather', 'country', 'Country' );

        /** Weather forecast - Population Style */
        $this->style_as_section( 'weather', 'population', 'Population' );

        /** Weather forecast - Temperature Style */
        $this->style_as_section( 'weather', 'temp', 'Temperature' );

        /** Weather forecast - Feels Style */
        $this->style_as_section( 'weather', 'feels_like', 'Feels like' );


        /** Weather forecast - Weather Image Style */
        $this->start_controls_section(
            'style_section_',
            [
                'label' => esc_html__( 'Weather Icon', 'synopter-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        /** Weather balance  */
        $this->add_responsive_control(
            'style_weather_balance',
            [
                'label' => esc_html__( 'Image/Temperature Width', 'synopter-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'default' => [
                    'unit' => '%',
                    'size' => '40',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdp-weather-icon-box' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .mdp-weather-temp-box' => 'width: calc( 100% - {{SIZE}}{{UNIT}} );',
                ],
            ]
        );

        /** Reflect current weather */
        $this->add_control(
            'style_weather_reflect',
            [
                'label' => esc_html__( 'Reflect weather', 'synopter-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'selectors' => [
                    '{{WRAPPER}} .mdp-weather-box' => 'flex-direction: row-reverse;',
                ],
            ]
        );

        $this->controls_img_size( 'weather', 'Weather Icon' );

        /** End section */
        $this->end_controls_section();


        /** Weather forecast - Description Style */
        $this->style_as_section( 'weather', 'description', 'Weather Description' );

        /** Weather forecast - Pressure Style */
        $this->style_as_section( 'weather', 'pressure', 'Pressure' );

        /** Weather forecast - Humidity Style */
        $this->style_as_section( 'weather', 'humidity', 'Humidity' );

        /** Weather forecast - Clouds Style */
        $this->style_as_section( 'weather', 'clouds_all', 'Cloudy' );

        /** Weather forecast - Wind Speed Style */
        $this->style_as_section( 'weather', 'wind_speed', 'Wind speed' );

        /** Weather forecast - Wind Direction Style */
        $this->style_as_section( 'weather', 'wind_deg', 'Wind direction' );

    }

    /**
     * Create a section for synopter data entity
     *
     * @param string $type Forecast type
     * @param string $slug Entity type
     * @param string $heading Section Heading
     *
     * @since 1.0.0
     * @access private
     */
    private function style_as_section( $type, $slug, $heading ) {

        /** Start section */
        $this->start_controls_section(
            'style_section_' . $type . '_' . $slug,
            [
                'label' => $heading,
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        /** Style controls */
        $this->controls_text_background_box( $type, $slug );

        /** End section */
        $this->end_controls_section();

    }

    /**
     * Add the widget controls.
     *
     * @since 1.0.0
     * @access protected
     *
     * @return void with category names
     **/
    protected function _register_controls() {

        /** Layout order settings. */
        $this->section_layout();

        /** Settings section for weather the next three hours. */
        $this->section_weather();

        /** Style current weather. */
        $this->style_current_weather();

    }

    /**
     * We return the current system to the unit of measure.
     *
     * @since 1.0.0
     * @access public
     *
     * @return array
     */
    public function weather_units( $settings ) {

        $units = [];

        switch ( $settings['location_units'] ) {
            case 'standard':
                $units = [  'temp' => 'K',
                            'humidity' => '%',
                            'pressure' => 'hPa',
                            'wind_speed' => 'm/s',
                            'wind_deg' => 'degrees',
                            'clouds_all' => '%',
                            'feels_like' => 'K' ];
            break;

            case 'metric':
                $units = [  'temp' => '&#176;C',
                            'humidity' => '%',
                            'pressure' => 'hPa',
                            'wind_speed' => 'm/s',
                            'wind_deg' => 'deg',
                            'clouds_all' => '%',
                            'feels_like' => '&#176;C' ];
            break;

            case 'imperial':
                $units = [  'temp' => '&#176;F',
                            'humidity' => '%',
                            'pressure' => 'hPa',
                            'wind_speed' => 'mil/h',
                            'wind_deg' => 'deg',
                            'clouds_all' => '%',
                            'feels_like' => '&#176;F' ];
            break;
        }

        return $units;

    }

    /**
     * Rounds to an integer.
     *
     * @param $value - The number to be rounded. Required.
     *
     * @return float
     * @since 1.0.0
     * @access public
     *
     */
    private function get_rounding_numbers( $value, $settings ){
        /** If we have value for the icon, prepare the markup for it. */
        $result = $value;
        if ( $settings['synopter_round'] === 'yes' ) { $result = round( $value, 0 ); }
        if ( $result == '-0' ){ $result = abs( $result ); }

        return $result;
    }

    /**
     * Returns weather information.
     *
     * @param $settings - Array with settings.
     * @param $type - Element name.
     * @param $title - Text HTML attribute.
     * @param $rounding_numbers - The numerical value of the weather.
     * @param null $icon - Icon for graphic designation of weather data.
     *
     * @return string
     * @since 1.0.0
     * @access private
     */
    private function get_whether_info( $settings, $type, $title, $rounding_numbers, $icon = null ){

        /** Get the current unit system. */
        $units = $this->weather_units( $settings );

        /** We generate class for setting styles. */
        $class = sprintf( 'mdp-weather-%s', str_replace('_', '-', $type ) );

        /** Check the variable for void. */
        $units_text = isset( $units[$type] ) ? $units[$type] : '';

        /** Determine whether to display the temp. */
        if ( $settings["synopter_weather_{$type}"] === 'yes' ) {
            return sprintf(
                '<span class="%s" title="%s">%s %s <span>%s</span>%s</span>',
                esc_attr($class),
                esc_attr($title),
                $icon,
                $settings[ "synopter_weather_{$type}_caption" ],
                esc_html($rounding_numbers),
                esc_html($units_text)
            );
        }

    }

    /**
     * Returns the icon for section weather.
     *
     * @param $value - FontAwesome icon name. Required.
     * @param $type_icon - Element name.
     *
     * @return string
     * @since 1.0.0
     * @access public
     *
     */
    private function get_icon_weather( $value, $type_icon ){
        if ( !empty( $value ) ) {
            return sprintf(
            '<i class="mdp-weather-padding-icon-%s %s"></i>',
                    esc_attr($type_icon),
                    esc_attr( $value )
            );
        }
    }

    /**
     * Returns a block with a description.
     *
     * @param $data
     * @param $icon
     * @param string $style Icon style
     * @param $description
     *
     * @return string
     */
    public function get_location_box( $data, $icon, $style, $description) {

        if ( ( !$data && !is_array( $data ) ) || $data['cod'] === '404' ) { return; }

        $result = '';

        /** Determine whether to display the icon. */
        if( $icon === 'yes' ) {
            $icon_url = UnityPlugin::get_url() . 'images/' . $style . $data['weather'][0]['icon'] . '.png';
            $result .= sprintf(
                '<div class="mdp-weather-img-icon"><img src="%s" alt=""></div>',
                esc_attr( $icon_url )
            );
        }

        /** Determine whether to display the description. */
        if( $description === 'yes' ) {
            $result .= sprintf(
                '<div class="mdp-weather-description">%s</div>',
                esc_html( $data['weather'][0]['description'] )
            );
        }

        return $result;

    }

    /**
     * Returns a block with temperatures.
     *
     * @param $data
     * @param $settings
     *
     * @return string
     */
    public function get_temp_box( $data, $settings ){
        if( $data['cod'] === '404' ){ return; }

        $result = '';

        /** Determine whether to display the temp. */
        $tempIcon = '';
        if( !empty($settings['synopter_weather_temp_icon']['value']) ){
            $tempIcon = $settings['synopter_weather_temp_icon']['value'];
        }

        $temp_icon = $this->get_icon_weather( $tempIcon, 'temp' );
        $temp_round = $this->get_rounding_numbers( $data['main']['temp'], $settings );
        $result .= $this->get_whether_info( $settings, 'temp', 'Temp', $temp_round, $temp_icon);

        /** Determine whether to display the feels like. */
        $feels_likeIcon = '';
        if( !empty($settings['synopter_weather_feels_like_icon']['value']) ){
            $feels_likeIcon = $settings['synopter_weather_feels_like_icon']['value'];
        }

        $feels_like_icon = $this->get_icon_weather( $feels_likeIcon, 'feels-like');
        $feels_like_round = $this->get_rounding_numbers( $data['main']['feels_like'], $settings );
        $result .= $this->get_whether_info( $settings, 'feels_like', 'Feels like', $feels_like_round, $feels_like_icon );

        return $result;

    }

    /**
     * @param $settings
     * @param $slug
     * @param $type_data
     * @param $data
     *
     * @return string|void
     */
    public function get_location_info( $settings, $slug, $type_data, $data ){
        if( $settings["{$slug}_{$type_data}"] !== 'yes' ){ return; }

        $icon = $settings["{$slug}_{$type_data}_icon"]['value'];
        $data_icon = !empty( $icon ) ? $icon : '';

        return sprintf(
            '<span class="mdp-weather-%s">%s %s %s</span>',
            $type_data,
            $this->get_icon_weather( $data_icon, $type_data ),
            $settings["{$slug}_{$type_data}_caption"],
            esc_html( $data )
        );
    }

    /**
     * Displays the weather that will be in the next three hours.
     *
     * @param $data - Array with weather data.
     * @param $settings - Array with settings.
     * @param $weather_data - Данные погоды которые обновляются каждый час.
     *
     * @return string
     * @since 1.0.0
     * @access public
     *
     */
    public function weather( $data, $settings, $weather_data ){

        if ( ( !$data && !is_array( $data ) ) || $data['cod'] === '404' ) {
            return  esc_html__( 'Data not found.', 'synopter-elementor' );
        }

        /** We save the settings in a variable that determine whether to display data or not. */
        $slug = 'synopter_weather';
        $country        = $settings["{$slug}_country"];
        $city           = $settings["{$slug}_location"];
        $population     = $settings["{$slug}_population"];
        $temp           = $settings["{$slug}_temp"];
        $feels_like     = $settings["{$slug}_feels_like"];
        $icon           = $settings["{$slug}_icon"];
        $description    = $settings["{$slug}_description"];
        $pressure       = $settings["{$slug}_pressure"];
        $humidity       = $settings["{$slug}_humidity"];
        $clouds_all     = $settings["{$slug}_clouds_all"];
        $wind_speed     = $settings["{$slug}_wind_speed"];
        $wind_deg       = $settings["{$slug}_wind_deg"];

        /** Start container. */
        $weather = '<div class="mdp-weather-container">';

        /** Start location box. */
        if( ( $city === 'yes' || $country === 'yes' || $population === 'yes' ) && $data['cod'] !== '404' ){
            $location_box = '<div class="mdp-weather-location-box">%s%s%s</div>';

            $weather .= sprintf(
                $location_box,
                $this->get_location_info( $settings, $slug, 'location', $data['city']['name'] ),
                $this->get_location_info( $settings, $slug, 'country', $data['city']['country'] ),
                $this->get_location_info( $settings, $slug, 'population', $data['city']['population'] )
            );
        }

        /** Start weather box. */
        if( $temp === 'yes' || $feels_like === 'yes' || $icon === 'yes' || $description === 'yes' ){

            $weather .= '<div class="mdp-weather-box">';

                /** Start icon box. */
                if( $icon === 'yes' || $description === 'yes' ){

                    $icon_box_html = '<div class="mdp-weather-icon-box">%s</div>';

                    /** Weather image. */
                    $location_box = $this->get_location_box(
                        $weather_data,
                        $icon,
                        $settings[ 'synopter_weather_icon_style' ],
                        $description
                    );

                    $weather .= sprintf( $icon_box_html, $location_box );
                }

                /** Start temp box. */
                if( $icon === 'yes' || $temp === 'yes' || $feels_like === 'yes' ){
                    $temp_box_html = '<div class="mdp-weather-temp-box">%s</div>';

                    /** Weather temp */
                    $temp_box = $this->get_temp_box( $weather_data, $settings);

                    $weather .= sprintf( $temp_box_html, $temp_box );
                }

            $weather .= '</div>';
        }

        /** Start info box. */
        if( $pressure === 'yes'   ||
            $humidity === 'yes'   ||
            $clouds_all === 'yes' ||
            $wind_speed === 'yes' ||
            $wind_deg === 'yes' ) {

            $info_box_html = '<div class="mdp-weather-info-box">%s %s %s %s %s</div>';

            /** Determine whether to display the pressure. */
            $pressureIcon = !empty($settings["{$slug}_pressure_icon"]['value']) ?
                $settings["{$slug}_pressure_icon"]['value'] : '';

            $pressure = $this->get_whether_info(
                $settings,
                'pressure',
                'Pressure',
                $this->get_rounding_numbers( $weather_data['main']['pressure'], $settings),
                $this->get_icon_weather( $pressureIcon, 'pressure' )
            );

            /** Determine whether to display the humidity. */
            $humidityIcon = !empty($settings["{$slug}_humidity_icon"]['value']) ?
                $settings["{$slug}_humidity_icon"]['value'] : '';

            $humidity = $this->get_whether_info(
                $settings,
                'humidity',
                'Humidity',
                $this->get_rounding_numbers( $weather_data['main']['humidity'], $settings),
                $this->get_icon_weather( $humidityIcon, 'humidity' )
            );

            /** Determine whether to display the cloudy. */
            $clouds_allIcon = !empty($settings["{$slug}_clouds_all_icon"]['value']) ?
                $settings["{$slug}_clouds_all_icon"]['value'] : '';

            $clouds_all = $this->get_whether_info(
                $settings,
                'clouds_all',
                'Cloudy',
                $this->get_rounding_numbers( $weather_data['clouds']['all'], $settings ),
                $this->get_icon_weather( $clouds_allIcon, 'clouds-all' )
            );

            /** Determine whether to display the wind speed. */
            $wind_speedIcon = !empty($settings["{$slug}_wind_speed_icon"]['value']) ?
                $settings["{$slug}_wind_speed_icon"]['value'] : '';

            $wind_speed = $this->get_whether_info(
                $settings,
                'wind_speed',
                'Wind speed',
                $this->get_rounding_numbers(  $weather_data['wind']['speed'], $settings ),
                $this->get_icon_weather( $wind_speedIcon, 'wind-speed' )
            );

            /** Determine whether to display the Wind direction. */
            $wind_degIcon = !empty($settings["{$slug}_wind_deg_icon"]['value']) ?
                $settings["{$slug}_wind_deg_icon"]['value'] : '';

            $wind_deg = $this->get_whether_info(
                $settings,
                'wind_deg',
                'Wind direction',
                $this->get_rounding_numbers( $weather_data['wind']['deg'], $settings ),
                $this->get_icon_weather( $wind_degIcon, 'wind-deg' )
            );

            $weather .= sprintf( $info_box_html, $pressure, $humidity, $clouds_all, $wind_speed, $wind_deg );

        }

        /** End container. */
        $weather .= '</div>';

        return $weather;

    }

    /**
     * Render Frontend Output. Generate the final HTML on the frontend.
     *
     * @since 1.0.0
     * @access protected
     **/
    protected function render() {
        $ip = file_get_contents('https://api.ipify.org');
        $json_auto_location = wp_remote_get( "http://ip-api.com/php/{$ip}?fields=status,country,countryCode,city,lat,lon" );

        $json_auto = unserialize( $json_auto_location['body'] );

        /** We get all the values from the admin panel. */
        $settings = $this->get_settings_for_display();

        /** Get Open Weather Key settings. */
        $OpenWeatherKey = Settings::get_instance()->options[ 'mdp_synopter_elementor_weather_settings' ];

        if ( ! empty( $OpenWeatherKey ) ) {

            /** We get weather data from an external source. Updated every 3 hours. */
            $openWeatherURL = sprintf(
                'https://api.openweathermap.org/data/2.5/forecast?q=%s,%s&units=%s&lang=%s&appid=%s',
                esc_attr( $settings[ 'location_city_name' ] ),
                esc_attr( $settings[ 'location_country' ] ),
                esc_attr( $settings[ 'location_units' ] ),
                esc_attr( $settings[ 'language_country' ] ),
                esc_attr( $OpenWeatherKey )
            );
            if ( $settings['synopter_auto_location'] === 'yes' ) {
                $openWeatherURL = sprintf(
                    'https://api.openweathermap.org/data/2.5/forecast?q=%s,%s&units=%s&lang=%s&appid=%s',
                    esc_attr( $json_auto[ 'city' ] ),
                    esc_attr( $json_auto[ 'countryCode' ] ),
                    esc_attr( $settings[ 'location_units' ] ),
                    esc_attr( $settings[ 'language_country' ] ),
                    esc_attr( $OpenWeatherKey )
                );
            }

            $json_openweathermap = wp_remote_get( $openWeatherURL );

            /** Decodes the JSON string. */
            $openweathermap = json_decode( $json_openweathermap['body'], true );

            /** The current weather. Updated every hour. */
            $currentWeatherURL = sprintf(
                'https://api.openweathermap.org/data/2.5/weather?q=%s,%s&units=%s&lang=%s&appid=%s',
                esc_attr($settings['location_city_name']),
                esc_attr($settings['location_country']),
                esc_attr($settings['location_units']),
                esc_attr($settings['language_country']),
                esc_attr($OpenWeatherKey)
            );
            if ( $settings['synopter_auto_location'] === 'yes' ) {
                $currentWeatherURL = sprintf(
                    'https://api.openweathermap.org/data/2.5/weather?q=%s,%s&units=%s&lang=%s&appid=%s',
                    esc_attr( $json_auto[ 'city' ] ),
                    esc_attr( $json_auto[ 'countryCode' ] ),
                    esc_attr($settings['location_units']),
                    esc_attr($settings['language_country']),
                    esc_attr($OpenWeatherKey)
                );
            }

            $json_current_weather = wp_remote_get( $currentWeatherURL );

            /** Decodes the JSON string. */
            $current_weather = json_decode( $json_current_weather['body'], true );

            echo wp_kses_post( $this->weather( $openweathermap, $settings, $current_weather) );

            return;
        }

        echo sprintf(
            '<p class="mpb-text-warning"><strong>%s</strong></p>',
            esc_html__('Warning: The Open Weather Key is not installed.', 'synopter-elementor' )
        );

    }

    /**
     * Return link for documentation
     * Used to add stuff after widget
     *
     * @access public
     *
     * @return string
     **/
    public function get_custom_help_url() {
        return 'https://docs.merkulov.design/tag/synopter';
    }

}
