<?php /** @noinspection PhpUndefinedClassInspection */
/**
 * Menuar for Elementor
 * Customizable menu for Elementor editor
 * Exclusively on https://1.envato.market/menuar-elementor
 *
 * @encoding        UTF-8
 * @version         1.0.1
 * @copyright       (C) 2018 - 2021 Merkulove ( https://merkulov.design/ ). All rights reserved.
 * @license         Envato License https://1.envato.market/KYbje
 * @contributors    Nemirovskiy Vitaliy (nemirovskiyvitaliy@gmail.com), Dmitry Merkulov (dmitry@merkulov.design), Cherviakov Vlad (vladchervjakov@gmail.com)
 * @support         help@merkulov.design
 **/

namespace Merkulove\MenuarElementor;

/** Exit if accessed directly. */
if ( ! defined( 'ABSPATH' ) ) {
    header( 'Status: 403 Forbidden' );
    header( 'HTTP/1.1 403 Forbidden' );
    exit;
}

use Exception;
use Elementor\Icons_Manager;
use Elementor\Widget_Base;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Controls_Manager;
use Merkulove\MenuarElementor\Unity\Plugin as UnityPlugin;
use Elementor\Utils;

/** @noinspection PhpUnused */
/**
 * Menuar - Custom Elementor Widget.
 **/
class menuar_elementor extends Widget_Base {

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
     * @throws Exception If arguments are missing when initializing a full widget instance.
     * @param array      $data Widget data. Default is an empty array.
     * @param array|null $args Optional. Widget default arguments. Default is null.
     *
     * @return void
     **/
    public function __construct( $data = [], $args = null ) {

        parent::__construct( $data, $args );

        wp_register_style( 'mdp-menuar-elementor-admin', UnityPlugin::get_url() . 'src/Merkulove/Unity/assets/css/elementor-admin' . UnityPlugin::get_suffix() . '.css', [], UnityPlugin::get_version() );
        wp_register_style( 'mdp-menuar-elementor', UnityPlugin::get_url() . 'css/menuar-elementor' . UnityPlugin::get_suffix() . '.css', [], UnityPlugin::get_version() );
        wp_register_script( 'mdp-menuar-elementor', UnityPlugin::get_url() . 'js/menuar-elementor' . UnityPlugin::get_suffix() . '.js', [ 'elementor-frontend' ], UnityPlugin::get_version(), true );

    }

    /**
     * Return a widget name.
     *
     * @return string
     **/
    public function get_name() {

        return 'mdp-menuar-elementor';

    }

    /**
     * Return the widget title that will be displayed as the widget label.
     *
     * @return string
     **/
    public function get_title() {

        return esc_html__( 'Menuar', 'menuar-elementor' );

    }

    /**
     * Set the widget icon.
     *
     * @return string
     */
    public function get_icon() {

        return 'mdp-menuar-elementor-widget-icon';

    }

    /**
     * Set the category of the widget.
     *
     * @return array with category names
     **/
    public function get_categories() {

        return [ 'general' ];

    }

    /**
     * Get widget keywords. Retrieve the list of keywords the widget belongs to.
     *
     * @access public
     *
     * @return array Widget keywords.
     **/
    public function get_keywords() {

        return [ 'Merkulove', 'Menuar' ];

    }

    /**
     * Get style dependencies.
     * Retrieve the list of style dependencies the widget requires.
     *
     * @access public
     *
     * @return array Widget styles dependencies.
     **/
    public function get_style_depends() {

        return [ 'mdp-menuar-elementor', 'mdp-menuar-elementor-admin' ];

    }

    /**
     * Get script dependencies.
     * Retrieve the list of script dependencies the element requires.
     *
     * @access public
     *
     * @return array Element scripts dependencies.
     **/
    public function get_script_depends() {

        return [ 'mdp-menuar-elementor' ];

    }

    /**
     * Add the widget controls.
     *
     * @access protected
     * @return void with category names
     **/
    protected function _register_controls() {

        /** Content Tab. */
        $this->tab_content();

        /** Style Tab. */
        $this->tab_style();

    }

    /**
     * Add widget controls on Content tab.
     *
     * @since 1.0.0
     * @access private
     *
     * @return void
     **/
    private function tab_content() {

        /** General settings -> General Settings Section. */
        $this->section_general_settings();

        /** Mobile menu settings -> Mobile Menu Settings Section */
        $this->section_mobile_menu_settings();

        /** Submenu settings -> Submenu Settings Section */
        $this->section_submenu_settings();

        /** Logo mobile menu settings -> Logo Mobile Menu Settings Section */
        $this->section_logo_mobile_menu_settings();
    }

    /**
     * Add widget controls on Style tab.
     *
     * @since 1.0.0
     * @access private
     *
     * @return void
     **/
    private function tab_style() {

        /** Style -> Section Menu Items Styles */
        $this->section_menu_items_styles();

        /** Style -> Section Submenu Styles */
        $this->section_submenu_styles();

        /** Style -> Section Submenu Items Styles */
        $this->section_submenu_item_styles();

        /** Style -> Section Toggle Button Styles */
        $this->section_toggle_button_styles();

        /** Style -> Section Toggle Close Button Styles */
        $this->section_toggle_close_button_styles();

        /** Style -> Section Mobile Logo Styles */
        $this->section_mobile_logo_styles();

        /** Style -> Section Mobile Menu Styles */
        $this->section_mobile_menu_styles();
    }

    /**
     * Get menus from wp
     *
     * @since 1.0.0
     * @access private
     *
     * @return array
     * */
    private function get_menus() {
        $menus = wp_get_nav_menus();

        $options = [];

        foreach ( $menus as $menu ) {
            $options[ $menu->slug ] = $menu->name;
        }

        return $options;
    }

    /**
     * Add widget controls: Content -> General Settings Section.
     *
     * @since 1.0.0
     * @access private
     *
     * @return void
     **/
    private function section_general_settings() {

        $this->start_controls_section( 'section_general_settings', [
            'label' => esc_html__( 'General settings', 'menuar-elementor' ),
            'tab'   => Controls_Manager::TAB_CONTENT
        ] );


        // get menus from wp
        $menus = $this->get_menus();

        if( !empty( $menus ) ) {
            $this->add_control(
                'select_menu',
                [
                    'label' => esc_html__( 'Select menu', 'menuar-elementor' ),
                    'type' => Controls_Manager::SELECT,
                    'options' => $menus,
                    'default' => array_keys( $menus )[0],
                    'save_default' => true,
                ]
            );
        } else {
            $this->add_control(
                'menu_link',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => '<strong>' . esc_html__( 'There are no menus in your site.', 'menuar-elementor' ) .
                        '</strong><br>' . sprintf( wp_kses_post( 'Go to the <a href="%s" target="_blank">Menus screen</a> to create one.' ),
                            admin_url( 'nav-menus.php?action=edit&menu=0' ) ),
                    'separator' => 'after',
                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                ]
            );
        }

        $this->add_control(
            'menu_layout',
            [
                'label' => esc_html__( 'Layout', 'menuar-elementor' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'horizontal',
                'options' => [
                    'vertical' => esc_html__( 'Vertical', 'menuar-elementor' ),
                    'horizontal' => esc_html__( 'Horizontal', 'menuar-elementor' ),
                ],
            ]
        );

        $this->add_control(
            'submenu_indicator',
            [
                'label' => esc_html__( 'Submenu indicator', 'menuar-elementor' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'fa fa-chevron-down',
                'options' => [
                    'fa fa-chevron-down' => esc_html__( 'Chevron', 'menuar-elementor' ),
                    'fas fa-angle-down' => esc_html__( 'Angle', 'menuar-elementor' ),
                    'fas fa-plus' => esc_html__( 'Plus', 'menuar-elementor' ),
                    'fa fa-circle' => esc_html__( 'Circle', 'menuar-elementor' ),
                    'fa fa-minus' => esc_html__( 'Minus', 'menuar-elementor' ),
                    'fas fa-square' => esc_html__( 'Square', 'menuar-elementor' ),
                    'custom' => esc_html__( 'Custom', 'menuar-elementor' ),
                    '' => esc_html__( 'None', 'menuar-elementor' )
                ],
            ]
        );

        $this->add_control(
            'custom_submenu_indicator',
            [
                'label' => esc_html__( 'Custom indicator', 'menuar-elementor' ),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fa fa-chevron-down',
                    'library' => 'solid',
                ],
                'condition' => [ 'submenu_indicator' => 'custom' ]
            ]
        );

        $this->add_control(
            'pointer',
            [
                'label' => esc_html__( 'Pointer', 'menuar-elementor' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'mdp-menuar-elementor-main-menu--item-underline' => esc_html__('Underline', 'menuar-elementor'),
                    'mdp-menuar-elementor-main-menu--item-overline' => esc_html__( 'Overline', 'menuar-elementor' ),
                    'mdp-menuar-elementor-main-menu--item-double-line' => esc_html__( 'Double line', 'menuar-elementor' ),
                    'mdp-menuar-elementor-main-menu--item-framed' => esc_html__( 'Framed', 'menuar-elementor' ),
                    'none' => esc_html__( 'None', 'menuar-elementor' ),
                ],
            ]
        );

        $this->add_control(
            'show_quantity_posts_in_category',
            [
                'label' => esc_html__( 'Show category posts quantity', 'menuar-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'menuar-elementor' ),
                'label_off' => esc_html__( 'No', 'menuar-elementor' ),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_control(
            'count_of_posts_label_align',
            [
                'label' => esc_html__( 'Quantity of posts alignment', 'menuar-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'mdp-menuar-elementor-category-post-count-left' => [
                        'title' => esc_html__( 'Left', 'menuar-elementor' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'mdp-menuar-elementor-category-post-count-right' => [
                        'title' => esc_html__( 'Right', 'menuar-elementor' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default' => 'mdp-menuar-elementor-category-post-count-right',
                'toggle' => true,
                'condition' => [ 'show_quantity_posts_in_category' => 'yes' ]
            ]
        );


        $this->end_controls_section();

    }

    /**
     * Add widget controls: Content -> Mobile Menu Settings.
     *
     * @since 1.0.0
     * @access private
     *
     * @return void
     **/
    private function section_mobile_menu_settings() {

        $this->start_controls_section( 'section_mobile_menu_settings', [
            'label' => esc_html__( 'Mobile menu settings', 'menuar-elementor' ),
            'tab'   => Controls_Manager::TAB_CONTENT
        ] );

        $this->add_control(
            'responsive_breakpoint',
            [
                'label' => esc_html__( 'Responsive breakpoint', 'menuar-elementor' ),
                'type' => Controls_Manager::SELECT,
                'default' => '767px',
                'options' => [
                    '767px' => esc_html__( 'Mobile', 'menuar-elementor' ),
                    '1024px' => esc_html__( 'Tablet', 'menuar-elementor' ),
                    'custom' => esc_html__( 'Custom', 'menuar-elementor' ),
                ],
            ]
        );

        $this->add_control(
            'custom_breakpoint',
            [
                'label' => esc_html__( 'Breakpoint', 'menuar-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1600,
                        'step' => 10,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'condition' => [
                    'responsive_breakpoint' => 'custom'
                ],
            ]
        );

        $this->add_control(
            'full_width',
            [
                'label' => esc_html__( 'Full width', 'menuar-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'menuar-elementor' ),
                'label_off' => esc_html__( 'No', 'menuar-elementor' ),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_control(
            'show_mobile_logo',
            [
                'label' => esc_html__( 'Logo', 'menuar-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'menuar-elementor' ),
                'label_off' => esc_html__( 'No', 'menuar-elementor' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'menu_position',
            [
                'label' => esc_html__( 'Menu position', 'menuar-elementor' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'left',
                'separator' => 'after',
                'options' => [
                    'left' => esc_html__( 'Left', 'menuar-elementor' ),
                    'right' => esc_html__( 'Right', 'menuar-elementor' ),
                    'top' => esc_html__( 'Top', 'menuar-elementor' ),
                ],
            ]
        );

        $this->add_control(
            'toggle_icon',
            [
                'label' => esc_html__( 'Toggle icon', 'menuar-elementor' ),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fa fa-bars',
                    'library' => 'solid',
                ],
            ]
        );

        $this->add_control(
            'close_toggle_icon',
            [
                'label' => esc_html__( 'Close menu icon', 'menuar-elementor' ),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-times',
                    'library' => 'solid',
                ],
            ]
        );


        $this->add_control(
            'toggle_align',
            [
                'label' => esc_html__( 'Toggle align', 'menuar-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Left', 'menuar-elementor' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'menuar-elementor' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'Right', 'menuar-elementor' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-toggle-btn, {{WRAPPER}} .mdp-menuar-elementor-toggle-close-btn' => 'justify-content: {{VALUE}}'
                ],
                'default' => 'flex-start',
                'toggle' => true,
            ]
        );

        $this->add_control(
            'submenu_click_area',
            [
                'label' => esc_html__( 'Submenu click area', 'menuar-elementor' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'text',
                'options' => [
                    'icon' => esc_html__( 'Icon', 'menuar-elementor' ),
                    'text' => esc_html__( 'Text', 'menuar-elementor' ),
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Add widget controls: Content -> Submenu Settings.
     *
     * @since 1.0.0
     * @access private
     *
     * @return void
     **/
    private function section_submenu_settings() {
        $this->start_controls_section( 'section_submenu_settings', [
            'label' => esc_html__( 'Submenu', 'menuar-elementor' ),
            'tab'   => Controls_Manager::TAB_CONTENT
        ] );

        $this->add_control(
            'expand_menu_position_horizontal',
            [
                'label' => esc_html__( 'Expand menu position', 'menuar-elementor' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'bottom',
                'options' => [
                    'top' => esc_html__( 'Top', 'menuar-elementor' ),
                    'bottom' => esc_html__( 'Bottom', 'menuar-elementor' ),
                ],
                'condition' => [
                    'menu_layout' => 'horizontal'
                ],
            ]
        );

        $this->add_control(
            'expand_menu_position_vertical',
            [
                'label' => esc_html__( 'Expand menu position', 'menuar-elementor' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'right',
                'options' => [
                    'right' => esc_html__( 'Right', 'menuar-elementor' ),
                    'left' => esc_html__( 'Left', 'menuar-elementor' ),
                ],
                'condition' => [
                    'menu_layout' => 'vertical'
                ],
            ]
        );

        $this->add_control(
            'submenu_pointer',
            [
                'label' => esc_html__( 'Submenu pointer', 'menuar-elementor' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'separator' => 'before',
                'options' => [
                    'mdp-menuar-elementor-main-menu--item-underline' => esc_html__( 'Underline', 'menuar-elementor' ),
                    'mdp-menuar-elementor-main-menu--item-overline' => esc_html__( 'Overline', 'menuar-elementor' ),
                    'mdp-menuar-elementor-main-menu--item-double-line' => esc_html__( 'Double line', 'menuar-elementor' ),
                    'mdp-menuar-elementor-main-menu--item-framed' => esc_html__( 'Framed', 'menuar-elementor' ),
                    'none' => esc_html__( 'None', 'menuar-elementor' ),
                ],
            ]
        );

        $this->add_control(
            'submenu_animation',
            [
                'label' => esc_html__( 'Animation', 'menuar-elementor' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'grow' => esc_html__( 'Grow', 'menuar-elementor' ),
                    'shrink' => esc_html__( 'Shrink', 'menuar-elementor' ),
                    'slide-up' => esc_html__( 'Slide up', 'menuar-elementor' ),
                    'slide-down' => esc_html__( 'Slide down', 'menuar-elementor' ),
                    'fade' => esc_html__( 'Fade', 'menuar-elementor' ),
                    'none' => esc_html__( 'None', 'menuar-elementor' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu--dropdown' => 'animation: {{VALUE}} ',
                ],
            ]
        );

        $this->add_control(
            'submenu_animation_easing',
            [
                'label' => esc_html__( 'Easing', 'menuar-elementor' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'ease',
                'options' => [
                    'ease' => esc_html__( 'Ease', 'menuar-elementor' ),
                    'ease-in' => esc_html__( 'Ease-in', 'menuar-elementor' ),
                    'ease-out' => esc_html__( 'Ease-out', 'menuar-elementor' ),
                    'ease-in-out' => esc_html__( 'Ease-in-out', 'menuar-elementor' ),
                    'linear' => esc_html__( 'Linear', 'menuar-elementor' ),
                ],
                'condition' => [
                    'submenu_animation!' => 'none'
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu--dropdown' => 'animation-timing-function: {{VALUE}} ',
                ],
            ]
        );

        $this->add_control(
            'submenu_animation_duration',
            [
                'label' => esc_html__( 'Duration', 'menuar-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 's' ],
                'range' => [
                    's' => [
                        'min' => 0.1,
                        'max' => 5,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'unit' => 's',
                    'size' => 1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu--dropdown' => 'animation-duration: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'submenu_animation!' => 'none'
                ],
            ]
        );

        $this->add_control(
            'submenu_animation_delay',
            [
                'label' => esc_html__( 'Delay', 'menuar-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 's' ],
                'range' => [
                    's' => [
                        'min' => 0,
                        'max' => 5,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'unit' => 's',
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu--dropdown' => 'animation-delay: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'submenu_animation!' => 'none'
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Add widget controls: Content -> General  Section.
     *
     * @since 1.0.0
     * @access private
     *
     * @return void
     **/
    private function section_logo_mobile_menu_settings() {
        $this->start_controls_section( 'section_logo_mobile_menu_settings', [
            'label' => esc_html__( 'Logo Mobile menu', 'menuar-elementor' ),
            'tab'   => Controls_Manager::TAB_CONTENT,
            'condition' => [
                'show_mobile_logo' => 'yes'
            ]
        ] );

        $this->add_control(
            'logo_align',
            [
                'label' => esc_html__( 'Logo align', 'menuar-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'mdp-menuar-elementor-menu-logo-left' => [
                        'title' => esc_html__( 'Left', 'menuar-elementor' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'mdp-menuar-elementor-menu-logo-center' => [
                        'title' => esc_html__( 'Center', 'menuar-elementor' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'mdp-menuar-elementor-menu-logo-right' => [
                        'title' => esc_html__( 'Right', 'menuar-elementor' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default' => 'mdp-menuar-elementor-menu-logo-right',
                'toggle' => true,
            ]
        );

        $this->add_control(
            'mobile_logo',
            [
                'label' => esc_html__('Mobile menu logo', 'menuar-elementor' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );


        $this->add_control(
            'logo_link',
            [
                'label' => esc_html__( 'Link for logo', 'menuar-elementor' ),
                'type' => Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://your-link.com', 'menuar-elementor' ),
                'show_external' => true,
                'default' => [
                    'url' => '',
                    'is_external' => true,
                    'nofollow' => true,
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Add widget controls: Style -> Section Menu Items Styles.
     *
     * @since 1.0.0
     * @access private
     *
     * @return void
     **/
    private function section_menu_items_styles() {

        $this->start_controls_section( 'section_style_menu_items', [
            'label' => esc_html__( 'Menu item', 'menuar-elementor' ),
            'tab'   => Controls_Manager::TAB_STYLE
        ] );

        $this->add_responsive_control(
            'menu_items_margin',
            [
                'label' => esc_html__( 'Margin', 'menuar-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'devices' => ['desktop', 'tablet', 'mobile'],
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu > .mdp-menuar-elementor-main-menu--item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

                ],
            ]
        );

        $this->add_responsive_control(
            'menu_items_padding',
            [
                'label' => esc_html__( 'Padding', 'menuar-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'devices' => ['desktop', 'tablet', 'mobile'],
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu > .mdp-menuar-elementor-main-menu--item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'separate_menu_item_indicator',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            'submenu_indicator_color_in_menu',
            [
                'label' => esc_html__( 'Indicator color', 'menuar-elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Color::get_type(),
                    'value' => Color::COLOR_3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu > .mdp-menuar-elementor-main-menu--item > .mdp-menuar-submenu-indicator' => 'color: {{VALUE}}',
                ],
            ]
        );



        $this->add_responsive_control(
            'menu_indicator_spacing',
            [
                'label' => esc_html__( 'Indicator spacing', 'menuar-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'devices' => ['desktop', 'tablet', 'mobile'],
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu > .mdp-menuar-elementor-main-menu--item > .mdp-menuar-submenu-indicator' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'menu_indicator_click_area',
            [
                'label' => esc_html__( 'Indicator click area', 'menuar-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'devices' => ['desktop', 'tablet', 'mobile'],
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu > .mdp-menuar-elementor-main-menu--item > .mdp-menuar-submenu-indicator' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'submenu_indicator_size_in_menu',
            [
                'label' => esc_html__( 'Submenu indicator size', 'menuar-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 10,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'separator' => 'after',
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-submenu-indicator' => 'font-size: {{SIZE}}{{UNIT}}'
                ],
            ]
        );

        $this->add_control(
            'items_spacing',
            [
                'label' => esc_html__( 'Items spacing', 'menuar-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu > .mdp-menuar-elementor-main-menu--item' => 'margin-right: {{SIZE}}{{UNIT}};'
                ]
            ]
        );

        $this->add_control(
            'separate_item_spacing',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'item_title_typography',
                'label' => esc_html__( 'Typography', 'menuar-elementor' ),
                'scheme' =>  Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .mdp-menuar-elementor-main-menu > .mdp-menuar-elementor-main-menu--item > a, {{WRAPPER}} .mdp-menuar-elementor-main-menu >  .mdp-menuar-elementor-main-menu--item > .mdp-menuar-elementor-category-post-count',
            ]
        );

        $this->add_responsive_control(
            'menu_align',
            [
                'label' => esc_html__( 'Menu align', 'menuar-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Left', 'menuar-elementor' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'menuar-elementor' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'Right', 'menuar-elementor' ),
                        'icon' => 'fa fa-align-right',
                    ],
                    'space-between' => [
                        'title' => esc_html__( 'Justify', 'menuar-elementor' ),
                        'icon' => 'fas fa-align-justify',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu' => 'justify-content: {{VALUE}}',
                    '{{WRAPPER}} .mdp-menuar-elementor-main-wrapper--mobile .mdp-menuar-elementor-main-menu' => 'align-content: {{VALUE}}',
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu--vertical' => 'align-items: {{VALUE}}'
                ],
                'default' => 'space-between',
                'toggle' => true,
            ]
        );

        $this->start_controls_tabs( 'menu_items_control_style_tabs' );

        $this->start_controls_tab( 'menu_item_normal_style_tab', ['label' => esc_html__( 'NORMAL', 'menuar-elementor' )] );

        $this->add_control(
            'menu_item_text_color',
            [
                'label' => esc_html__( 'Color', 'menuar-elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Color::get_type(),
                    'value' => Color::COLOR_3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu > .mdp-menuar-elementor-main-menu--item > a' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu > .mdp-menuar-elementor-main-menu--item > .mdp-menuar-elementor-category-post-count' => 'color: {{VALUE}}'
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'background',
                'label' => esc_html__( 'Background type', 'menuar-elementor' ),
                'types' => [ 'classic', 'gradient', 'video' ],
                'selector' => '{{WRAPPER}} .mdp-menuar-elementor-main-menu > .mdp-menuar-elementor-main-menu--item',
            ]
        );

        $this->add_control(
            'separate_border_settings_menu_item_normal',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'menu_item_border_normal',
                'label' => esc_html__( 'Border Type', 'menuar-elementor' ),
                'selector' => '{{WRAPPER}} .mdp-menuar-elementor-main-menu > .mdp-menuar-elementor-main-menu--item',
            ]
        );

        $this->add_responsive_control(
            'menu_item_border_radius_normal',
            [
                'label' => esc_html__( 'Border radius', 'menuar-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu > .mdp-menuar-elementor-main-menu--item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'menu_item_box_shadow_normal',
                'label' => esc_html__( 'Box Shadow', 'menuar-elementor' ),
                'selector' => '{{WRAPPER}} .mdp-menuar-elementor-main-menu > .mdp-menuar-elementor-main-menu--item',
            ]
        );



        $this->end_controls_tab();

        $this->start_controls_tab( 'menu_item_hover_style_tab', ['label' => esc_html__( 'HOVER', 'menuar-elementor' )] );

        $this->add_control(
            'menu_item_text_hover_color',
            [
                'label' => esc_html__( 'Color', 'menuar-elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Color::get_type(),
                    'value' => Color::COLOR_3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu > .mdp-menuar-elementor-main-menu--item:hover > a' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu > .mdp-menuar-elementor-main-menu--item:hover > .mdp-menuar-elementor-category-post-count' => 'color: {{VALUE}}'
                ],
            ]
        );

        $this->add_control(
            'menu_item_pointer_color',
            [
                'label' => esc_html__( 'Pointer color', 'menuar-elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Color::get_type(),
                    'value' => Color::COLOR_3,
                ],
                'condition' => [
                    'pointer!' => [ 'none' ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu.mdp-menuar-elementor-main-menu--item-underline > li:hover::after' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu.mdp-menuar-elementor-main-menu--item-overline > li:hover::after' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu.mdp-menuar-elementor-main-menu--item-double-line > li:hover::after' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu.mdp-menuar-elementor-main-menu--item-double-line > li:hover::before' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu.mdp-menuar-elementor-main-menu--item-framed > li:hover::before' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'background_hover',
                'label' => esc_html__( 'Background type', 'menuar-elementor' ),
                'types' => [ 'classic', 'gradient', 'video' ],
                'selector' => '{{WRAPPER}} .mdp-menuar-elementor-main-menu > .mdp-menuar-elementor-main-menu--item:hover',
            ]
        );


        $this->add_control(
            'hover_transition_menu_items',
            [
                'label' => esc_html__( 'Hover transition duration', 'menuar-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 's' ],
                'range' => [
                    's' => [
                        'min' => 0.1,
                        'max' => 5,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'unit' => 's',
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu > .mdp-menuar-elementor-main-menu--item > a' => 'transition: color {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu > .mdp-menuar-elementor-main-menu--item' => 'transition: background {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->add_control(
            'separate_border_settings_menu_item_hover',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'menu_item_border_hover',
                'label' => esc_html__( 'Border Type', 'menuar-elementor' ),
                'selector' => '{{WRAPPER}} .mdp-menuar-elementor-main-menu > .mdp-menuar-elementor-main-menu--item:hover',
            ]
        );

        $this->add_responsive_control(
            'menu_item_border_radius_hover',
            [
                'label' => esc_html__( 'Border radius', 'menuar-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu > .mdp-menuar-elementor-main-menu--item:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'menu_item_box_shadow_hover',
                'label' => esc_html__( 'Box Shadow', 'menuar-elementor' ),
                'selector' => '{{WRAPPER}} .mdp-menuar-elementor-main-menu > .mdp-menuar-elementor-main-menu--item:hover',
            ]
        );



        $this->end_controls_tab();

        $this->start_controls_tab( 'menu_item_active_style_tab', ['label' => esc_html__( 'ACTIVE', 'menuar-elementor' )] );

        $this->add_control(
            'menu_item_active_text_color',
            [
                'label' => esc_html__( 'Color', 'menuar-elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Color::get_type(),
                    'value' => Color::COLOR_3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu > .mdp-menuar-elementor-main-menu--item-active > a' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu > .mdp-menuar-elementor-main-menu--item-active > .mdp-menuar-elementor-category-post-count' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu > .mdp-menuar-elementor-main-menu--item-active:hover > a' => 'color: {{VALUE}}'
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'background_active',
                'label' => esc_html__( 'Background type', 'menuar-elementor' ),
                'types' => [ 'classic', 'gradient', 'video' ],
                'selector' => '{{WRAPPER}} .mdp-menuar-elementor-main-menu > .mdp-menuar-elementor-main-menu--item-active, {{WRAPPER}} .mdp-menuar-elementor-main-menu > .mdp-menuar-elementor-main-menu--item-active:hover',
            ]
        );

        $this->add_control(
            'separate_controls_active',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'menu_item_border_active',
                'label' => esc_html__( 'Border Type', 'menuar-elementor' ),
                'selector' => '{{WRAPPER}} .mdp-menuar-elementor-main-menu > .mdp-menuar-elementor-main-menu--item-active, {{WRAPPER}} .mdp-menuar-elementor-main-menu > .mdp-menuar-elementor-main-menu--item-active:hover',
            ]
        );

        $this->add_responsive_control(
            'menu_item_border_radius',
            [
                'label' => esc_html__( 'Border radius', 'menuar-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu > .mdp-menuar-elementor-main-menu--item-active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu > .mdp-menuar-elementor-main-menu--item-active:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'menu_item_box_shadow_active',
                'label' => esc_html__( 'Box Shadow', 'menuar-elementor' ),
                'selector' => '{{WRAPPER}} .mdp-menuar-elementor-main-menu > .mdp-menuar-elementor-main-menu--item-active, {{WRAPPER}} .mdp-menuar-elementor-main-menu > .mdp-menuar-elementor-main-menu--item-active:hover',
            ]
        );



        $this->end_controls_tab();


        $this->end_controls_tabs();



        $this->end_controls_section();

    }

    /**
     * Add widget controls: Style -> Section Submenu Styles.
     *
     * @since 1.0.0
     * @access private
     *
     * @return void
     **/
    private function section_submenu_styles() {

        $this->start_controls_section('section_style_submenu', [
            'label' => esc_html__('Submenu', 'menuar-elementor'),
            'tab' => Controls_Manager::TAB_STYLE
        ]);

        $this->add_responsive_control(
            'submenu_margin',
            [
                'label' => esc_html__( 'Margin', 'menuar-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'devices' => ['desktop', 'tablet', 'mobile'],
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu--dropdown' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

                ],
            ]
        );

        $this->add_responsive_control(
            'submenu_padding',
            [
                'label' => esc_html__( 'Padding', 'menuar-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'devices' => ['desktop', 'tablet', 'mobile'],
                'separator' => 'after',
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu--dropdown' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'submenu_offset_x',
            [
                'label' => esc_html__( 'Offset - x', 'menuar-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => -500,
                        'max' => 500,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu.mdp-menuar-expand-position-top .mdp-menuar-elementor-main-menu--dropdown, {{WRAPPER}} .mdp-menuar-elementor-main-menu.mdp-menuar-expand-position-bottom .mdp-menuar-elementor-main-menu--dropdown' => 'left: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu--vertical.mdp-menuar-expand-position-left  .mdp-menuar-elementor-main-menu--dropdown' => 'left: {{SIZE}}{{UNIT}}; right: auto;',
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu--vertical.mdp-menuar-expand-position-right  .mdp-menuar-elementor-main-menu--dropdown' => 'right: {{SIZE}}{{UNIT}}; left: auto;'
                ],
            ]
        );

        $this->add_control(
            'submenu_offset_y',
            [
                'label' => esc_html__( 'Offset - y', 'menuar-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => -150,
                        'max' => 150,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu.mdp-menuar-expand-position-right  .mdp-menuar-elementor-main-menu--dropdown, {{WRAPPER}} .mdp-menuar-elementor-main-menu.mdp-menuar-expand-position-left  .mdp-menuar-elementor-main-menu--dropdown' => 'top: {{SIZE}}{{UNIT}}; bottom: auto;',
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu.mdp-menuar-expand-position-top  .mdp-menuar-elementor-main-menu--dropdown' => 'bottom: {{SIZE}}{{UNIT}}; top: auto;',
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu.mdp-menuar-expand-position-bottom  .mdp-menuar-elementor-main-menu--dropdown' => 'top: {{SIZE}}{{UNIT}}; bottom: auto',
                ],
            ]
        );

        $this->add_control(
            'separate_offsets',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'submenu_background',
                'label' => esc_html__( 'Background type', 'menuar-elementor' ),
                'types' => [ 'classic', 'gradient', 'video' ],
                'selector' => '{{WRAPPER}} .mdp-menuar-elementor-main-menu--dropdown',
            ]
        );

        $this->add_control(
            'separate_background',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'submenu_border',
                'label' => esc_html__( 'Border Type', 'menuar-elementor' ),
                'selector' => '{{WRAPPER}} .mdp-menuar-elementor-main-menu--dropdown',
            ]
        );

        $this->add_responsive_control(
            'submenu_border_radius',
            [
                'label' => esc_html__( 'Border radius', 'menuar-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu--dropdown' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'submenu_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'menuar-elementor' ),
                'selector' => '{{WRAPPER}} .mdp-menuar-elementor-main-menu--dropdown',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Add widget controls: Style -> Section Submenu Items Styles.
     *
     * @since 1.0.0
     * @access private
     *
     * @return void
     **/
    private function section_submenu_item_styles() {
        $this->start_controls_section('section_style_submenu_item', [
            'label' => esc_html__('Submenu item', 'menuar-elementor'),
            'tab' => Controls_Manager::TAB_STYLE
        ]);

        $this->add_responsive_control(
            'submenu_items_margin',
            [
                'label' => esc_html__( 'Margin', 'menuar-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'devices' => ['desktop', 'tablet', 'mobile'],
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu--dropdown .mdp-menuar-elementor-main-menu--item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

                ],
            ]
        );

        $this->add_responsive_control(
            'submenu_items_padding',
            [
                'label' => esc_html__( 'Padding', 'menuar-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'devices' => ['desktop', 'tablet', 'mobile'],
                'separator' => 'after',
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu--dropdown .mdp-menuar-elementor-main-menu--item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'submenu_indicator_color_in_submenu',
            [
                'label' => esc_html__( 'Indicator color', 'menuar-elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Color::get_type(),
                    'value' => Color::COLOR_3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu--dropdown .mdp-menuar-submenu-indicator' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'submenu_indicator_spacing',
            [
                'label' => esc_html__( 'Indicator spacing', 'menuar-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'devices' => ['desktop', 'tablet', 'mobile'],
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu--dropdown .mdp-menuar-submenu-indicator' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'submenu_indicator_click_area',
            [
                'label' => esc_html__( 'Indicator click area', 'menuar-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'devices' => ['desktop', 'tablet', 'mobile'],
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu--dropdown .mdp-menuar-submenu-indicator' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'submenu_indicator_size_in_submenu',
            [
                'label' => esc_html__( 'Submenu indicator size', 'menuar-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 10,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'separator' => 'after',
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu--dropdown .mdp-menuar-submenu-indicator' => 'font-size: {{SIZE}}{{UNIT}}'
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'submenu_item_title_typography',
                'label' => esc_html__( 'Typography', 'menuar-elementor' ),
                'scheme' =>  Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .mdp-menuar-elementor-main-menu--dropdown .mdp-menuar-elementor-main-menu--item a, {{WRAPPER}} .mdp-menuar-elementor-main-menu--dropdown .mdp-menuar-elementor-main-menu--item .mdp-menuar-elementor-category-post-count',
            ]
        );

        $this->start_controls_tabs( 'submenu_items_control_style_tabs' );

        $this->start_controls_tab( 'submenu_item_normal_style_tab', ['label' => esc_html__( 'NORMAL', 'menuar-elementor' )] );

        $this->add_control(
            'submenu_item_text_color',
            [
                'label' => esc_html__( 'Color', 'menuar-elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Color::get_type(),
                    'value' => Color::COLOR_3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu--dropdown .mdp-menuar-elementor-main-menu--item a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu--dropdown .mdp-menuar-elementor-main-menu--item .mdp-menuar-elementor-category-post-count ' => 'color: {{VALUE}};'
                ],
            ]
        );


        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'submenu_items_background',
                'label' => esc_html__( 'Background type', 'menuar-elementor' ),
                'types' => [ 'classic', 'gradient', 'video' ],
                'selector' => '{{WRAPPER}} .mdp-menuar-elementor-main-menu--dropdown .mdp-menuar-elementor-main-menu--item',
            ]
        );


        $this->add_control(
            'separate_border_settings_submenu_item_normal',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'submenu_item_border_normal',
                'label' => esc_html__( 'Border Type', 'menuar-elementor' ),
                'selector' => '{{WRAPPER}} .mdp-menuar-elementor-main-menu--dropdown .mdp-menuar-elementor-main-menu--item',
            ]
        );

        $this->add_responsive_control(
            'submenu_item_border_radius_normal',
            [
                'label' => esc_html__( 'Border radius', 'menuar-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu--dropdown .mdp-menuar-elementor-main-menu--item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'submenu_item_box_shadow_normal',
                'label' => esc_html__( 'Box Shadow', 'menuar-elementor' ),
                'selector' => '{{WRAPPER}} .mdp-menuar-elementor-main-menu--dropdown .mdp-menuar-elementor-main-menu--item',
            ]
        );


        $this->end_controls_tab();

        $this->start_controls_tab( 'submenu_item_hover_style_tab', ['label' => esc_html__( 'HOVER', 'menuar-elementor' )] );

        $this->add_control(
            'submenu_item_hover_text_color',
            [
                'label' => esc_html__( 'Color', 'menuar-elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Color::get_type(),
                    'value' => Color::COLOR_3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu--dropdown .mdp-menuar-elementor-main-menu--item:hover a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu--dropdown .mdp-menuar-elementor-main-menu--item:hover .mdp-menuar-elementor-category-post-count' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'submenu_item_pointer_color',
            [
                'label' => esc_html__( 'Pointer color', 'menuar-elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Color::get_type(),
                    'value' => Color::COLOR_3,
                ],
                'condition' => [
                    'submenu_pointer!' => [ 'none' ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu--dropdown.mdp-menuar-elementor-main-menu--item-underline > li:hover::after' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu--dropdown.mdp-menuar-elementor-main-menu--item-overline > li:hover::after' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu--dropdown.mdp-menuar-elementor-main-menu--item-double-line > li:hover::after' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu--dropdown.mdp-menuar-elementor-main-menu--item-double-line > li:hover::before' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu--dropdown.mdp-menuar-elementor-main-menu--item-framed > li:hover::before' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'submenu_items_background_hover',
                'label' => esc_html__( 'Background type', 'menuar-elementor' ),
                'types' => [ 'classic', 'gradient', 'video' ],
                'selector' => '{{WRAPPER}} .mdp-menuar-elementor-main-menu--dropdown .mdp-menuar-elementor-main-menu--item:hover',
            ]
        );


        $this->add_control(
            'hover_transition_submenu_items',
            [
                'label' => esc_html__( 'Hover transition duration', 'menuar-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 's' ],
                'range' => [
                    's' => [
                        'min' => 0.1,
                        'max' => 5,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'unit' => 's',
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu--dropdown .mdp-menuar-elementor-main-menu--item a' => 'transition: color {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu--dropdown .mdp-menuar-elementor-main-menu--item .mdp-menuar-elementor-category-post-count' => 'transition: color {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu--dropdown .mdp-menuar-elementor-main-menu--item' => 'transition: background {{SIZE}}{{UNIT}};',
                ],
            ]
        );



        $this->add_control(
            'separate_border_settings_submenu_item_hover',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'submenu_item_border_hover',
                'label' => esc_html__( 'Border Type', 'menuar-elementor' ),
                'selector' => '{{WRAPPER}} .mdp-menuar-elementor-main-menu--dropdown .mdp-menuar-elementor-main-menu--item:hover',
            ]
        );

        $this->add_responsive_control(
            'submenu_item_border_radius_hover',
            [
                'label' => esc_html__( 'Border radius', 'menuar-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu--dropdown .mdp-menuar-elementor-main-menu--item:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'submenu_item_box_shadow_hover',
                'label' => esc_html__( 'Box Shadow', 'menuar-elementor' ),
                'selector' => '{{WRAPPER}} .mdp-menuar-elementor-main-menu--dropdown .mdp-menuar-elementor-main-menu--item:hover',
            ]
        );


        $this->end_controls_tab();

        $this->start_controls_tab( 'submenu_item_active_style_tab', ['label' => esc_html__( 'ACTIVE', 'menuar-elementor' )] );

        $this->add_control(
            'submenu_item_active_text_color',
            [
                'label' => esc_html__( 'Color', 'menuar-elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Color::get_type(),
                    'value' => Color::COLOR_3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu--dropdown .mdp-menuar-elementor-main-menu--item-active > a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu--dropdown .mdp-menuar-elementor-main-menu--item-active:hover > a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu--dropdown .mdp-menuar-elementor-main-menu--item-active > .mdp-menuar-elementor-category-post-count' => 'color: {{VALUE}};',
                ],
            ]
        );


        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'submenu_items_active_background',
                'label' => esc_html__( 'Background type', 'menuar-elementor' ),
                'types' => [ 'classic', 'gradient', 'video' ],
                'selector' => '{{WRAPPER}} .mdp-menuar-elementor-main-menu--dropdown .mdp-menuar-elementor-main-menu--item-active, {{WRAPPER}} .mdp-menuar-elementor-main-menu--dropdown .mdp-menuar-elementor-main-menu--item-active:hover',
            ]
        );

        $this->add_control(
            'separate_border_settings_submenu_item_active',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'submenu_item_border_active',
                'label' => esc_html__( 'Border Type', 'menuar-elementor' ),
                'selector' => '{{WRAPPER}} .mdp-menuar-elementor-main-menu--dropdown .mdp-menuar-elementor-main-menu--item-active, {{WRAPPER}} .mdp-menuar-elementor-main-menu--dropdown .mdp-menuar-elementor-main-menu--item-active:hover',
            ]
        );

        $this->add_responsive_control(
            'submenu_item_border_radius_active',
            [
                'label' => esc_html__( 'Border radius', 'menuar-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu--dropdown .mdp-menuar-elementor-main-menu--item-active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .mdp-menuar-elementor-main-menu--dropdown .mdp-menuar-elementor-main-menu--item-active:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'submenu_item_box_shadow_active',
                'label' => esc_html__( 'Box Shadow', 'menuar-elementor' ),
                'selector' => '{{WRAPPER}} .mdp-menuar-elementor-main-menu--dropdown .mdp-menuar-elementor-main-menu--item-active, {{WRAPPER}} .mdp-menuar-elementor-main-menu--dropdown .mdp-menuar-elementor-main-menu--item-active:hover',
            ]
        );


        $this->end_controls_tab();

        $this->end_controls_tabs();


        $this->end_controls_section();
    }

    /**
     * Add widget controls: Style -> Section Toggle Button Styles.
     *
     * @since 1.0.0
     * @access private
     *
     * @return void
     **/
    private function section_toggle_button_styles() {

        $this->start_controls_section('section_style_toggle_buttons', [
            'label' => esc_html__('Toggle style', 'menuar-elementor'),
            'tab' => Controls_Manager::TAB_STYLE
        ]);

        $this->add_responsive_control(
            'toggle_button_margin',
            [
                'label' => esc_html__( 'Margin', 'menuar-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'devices' => ['desktop', 'tablet', 'mobile'],
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-toggle-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

                ],
            ]
        );

        $this->add_responsive_control(
            'toggle_button_padding',
            [
                'label' => esc_html__( 'Padding', 'menuar-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'devices' => ['desktop', 'tablet', 'mobile'],
                'separator' => 'after',
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-toggle-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'toggle_button_size',
            [
                'label' => esc_html__( 'Toggle size', 'menuar-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-toggle-icon' => 'font-size: {{SIZE}}{{UNIT}}'
                ],
            ]
        );

        $this->add_control(
            'separate_toggle_button_size',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->start_controls_tabs( 'menu_items_control_style_toggle_button' );

        $this->start_controls_tab( 'menu_item_normal_style_toggle_button', ['label' => esc_html__( 'NORMAL', 'menuar-elementor' )] );

        $this->add_control(
            'toggle_button_color',
            [
                'label' => esc_html__( 'Color', 'menuar-elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Color::get_type(),
                    'value' => Color::COLOR_3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-toggle-icon' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'background_toggle_button',
                'label' => esc_html__( 'Background type', 'menuar-elementor' ),
                'types' => [ 'classic', 'gradient', 'video' ],
                'selector' => '{{WRAPPER}} .mdp-menuar-elementor-toggle-icon',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 'menu_item_normal_style_toggle_button_hover', ['label' => esc_html__( 'HOVER', 'menuar-elementor' )] );

        $this->add_control(
            'toggle_button_color_hover',
            [
                'label' => esc_html__( 'Color', 'menuar-elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Color::get_type(),
                    'value' => Color::COLOR_3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-toggle-icon:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'background_toggle_button_hover',
                'label' => esc_html__( 'Background type', 'menuar-elementor' ),
                'types' => [ 'classic', 'gradient', 'video' ],
                'selector' => '{{WRAPPER}} .mdp-menuar-elementor-toggle-icon:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();


        $this->add_control(
            'separate_toggle_tabs',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'toggle_border',
                'label' => esc_html__( 'Border Type', 'menuar-elementor' ),
                'selector' => '{{WRAPPER}} .mdp-menuar-elementor-toggle-icon',
            ]
        );

        $this->add_responsive_control(
            'toggle_border_radius',
            [
                'label' => esc_html__( 'Border radius', 'menuar-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-toggle-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'toggle_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'menuar-elementor' ),
                'selector' => '{{WRAPPER}} .mdp-menuar-elementor-toggle-icon',
            ]
        );


        $this->end_controls_section();

    }

    /**
     * Add widget controls: Style -> Section Toggle Close Button Styles.
     *
     * @since 1.0.0
     * @access private
     *
     * @return void
     **/
    private function section_toggle_close_button_styles() {

        $this->start_controls_section('section_style_toggle_close_buttons', [
            'label' => esc_html__('Close toggle style', 'menuar-elementor'),
            'tab' => Controls_Manager::TAB_STYLE
        ]);

        $this->add_responsive_control(
            'toggle_close_button_margin',
            [
                'label' => esc_html__( 'Margin', 'menuar-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'devices' => ['desktop', 'tablet', 'mobile'],
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-toggle-close-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

                ],
            ]
        );

        $this->add_responsive_control(
            'toggle_close_button_padding',
            [
                'label' => esc_html__( 'Padding', 'menuar-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'devices' => ['desktop', 'tablet', 'mobile'],
                'separator' => 'after',
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-toggle-close-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'toggle_close_button_size',
            [
                'label' => esc_html__( 'Toggle size', 'menuar-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-toggle-close-icon' => 'font-size: {{SIZE}}{{UNIT}}'
                ],
            ]
        );

        $this->add_control(
            'separate_toggle_close_button_size',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->start_controls_tabs( 'menu_items_control_style_toggle_close_button' );

        $this->start_controls_tab( 'menu_item_normal_style_toggle_close_button', ['label' => esc_html__( 'NORMAL', 'menuar-elementor' )] );

        $this->add_control(
            'toggle_close_button_color',
            [
                'label' => esc_html__( 'Color', 'menuar-elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Color::get_type(),
                    'value' => Color::COLOR_3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-toggle-close-icon' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'background_toggle_close_button',
                'label' => esc_html__( 'Background type', 'menuar-elementor' ),
                'types' => [ 'classic', 'gradient', 'video' ],
                'selector' => '{{WRAPPER}} .mdp-menuar-elementor-toggle-close-icon',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 'menu_item_normal_style_toggle_close_button_hover', ['label' => esc_html__( 'HOVER', 'menuar-elementor' )] );

        $this->add_control(
            'toggle_close_button_color_hover',
            [
                'label' => esc_html__( 'Color', 'menuar-elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Color::get_type(),
                    'value' => Color::COLOR_3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-toggle-close-icon:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'background_toggle_close_button_hover',
                'label' => esc_html__( 'Background type', 'menuar-elementor' ),
                'types' => [ 'classic', 'gradient', 'video' ],
                'selector' => '{{WRAPPER}} .mdp-menuar-elementor-toggle-close-icon:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();


        $this->add_control(
            'separate_toggle_close_tabs',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'toggle_close_border',
                'label' => esc_html__( 'Border Type', 'menuar-elementor' ),
                'selector' => '{{WRAPPER}} .mdp-menuar-elementor-toggle-close-icon',
            ]
        );

        $this->add_responsive_control(
            'toggle_close_border_radius',
            [
                'label' => esc_html__( 'Border radius', 'menuar-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-toggle-close-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'toggle_close_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'menuar-elementor' ),
                'selector' => '{{WRAPPER}} .mdp-menuar-elementor-toggle-close-icon',
            ]
        );


        $this->end_controls_section();
    }

    /**
     * Add widget controls: Style -> Section Mobile Menu Styles.
     *
     * @since 1.0.0
     * @access private
     *
     * @return void
     **/
    private function section_mobile_menu_styles() {
        $this->start_controls_section('section_mobile_menu_styles', [
            'label' => esc_html__('Mobile menu', 'menuar-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control(
            'mobile_menu_padding',
            [
                'label' => esc_html__( 'Padding', 'menuar-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'devices' => ['desktop', 'tablet', 'mobile'],
                'separator' => 'after',
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-main-wrapper--mobile' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'mobile_menu_background',
                'label' => esc_html__( 'Background type', 'menuar-elementor' ),
                'types' => [ 'classic', 'gradient', 'video' ],
                'selector' => '{{WRAPPER}} .mdp-menuar-elementor-main-wrapper--mobile',
            ]
        );


        $this->add_control(
            'separate_background_mobile_menu',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'mobile_menu_item_border',
                'label' => esc_html__( 'Border Type', 'menuar-elementor' ),
                'selector' => '{{WRAPPER}} .mdp-menuar-elementor-main-wrapper--mobile',
            ]
        );

        $this->add_responsive_control(
            'mobile_menu_item_border_radius',
            [
                'label' => esc_html__( 'Border radius', 'menuar-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-main-wrapper--mobile'=> 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'mobile_menu_item_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'menuar-elementor' ),
                'selector' => '{{WRAPPER}} .mdp-menuar-elementor-main-wrapper--mobile',
            ]
        );


        $this->end_controls_section();
    }

    /**
     * Add widget controls: Style -> Section Mobile Logo Styles.
     *
     * @since 1.0.0
     * @access private
     *
     * @return void
     **/
    private function section_mobile_logo_styles() {

        $this->start_controls_section('section_style_mobile_logo', [
            'label' => esc_html__('Mobile menu logo', 'menuar-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                'show_mobile_logo' => 'yes'
            ]
        ]);

        $this->add_responsive_control(
            'mobile_logo_margin',
            [
                'label' => esc_html__( 'Margin', 'menuar-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'devices' => ['desktop', 'tablet', 'mobile'],
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-menu-logo' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'mobile_logo_padding',
            [
                'label' => esc_html__( 'Padding', 'menuar-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'devices' => ['desktop', 'tablet', 'mobile'],
                'separator' => 'after',
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-menu-logo' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'mobile_logo_height',
            [
                'label' => esc_html__( 'Height', 'menuar-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 300,
                        'step' => 5,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-menu-logo' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .mdp-menuar-elementor-menu-logo img' => 'height: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->add_control(
            'mobile_logo_width',
            [
                'label' => esc_html__( 'Width', 'menuar-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 300,
                        'step' => 5,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdp-menuar-elementor-menu-logo' => 'width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .mdp-menuar-elementor-menu-logo img' => 'width: {{SIZE}}{{UNIT}}'
                ],
            ]
        );


        $this->end_controls_section();

    }

    /**
     * Styles on breakpoints.
     *
     * @access private
     *
     * @param $settings
     * @return void
     */
    private function mobile_styles_render( $settings ) {
        ?>
        <?php if( $settings['responsive_breakpoint'] ): ?>
            <style>
                @media only screen and (max-width:  <?php $settings['responsive_breakpoint'] !='custom' ?
                               esc_html_e( $settings['responsive_breakpoint'] ) :
                               esc_html_e( $settings['custom_breakpoint']['size'].$settings['custom_breakpoint']['unit'] ) ?>) {
                    .elementor-element-<?php esc_html_e( $this->get_id() ); ?> .mdp-menuar-elementor-main-menu {
                        display: inline-flex;
                    }
                    .elementor-element-<?php esc_html_e( $this->get_id() ); ?> .mdp-menuar-expand-position-right .mdp-menuar-submenu-indicator {
                        transform: rotate(0deg);
                        margin-left: 10px;
                    }
                    .elementor-element-<?php esc_html_e( $this->get_id() ); ?> .mdp-menuar-expand-position-left .mdp-menuar-submenu-indicator {
                        transform: rotate(0deg);
                        order: 1;
                        margin-right: 0;
                        margin-left: 10px;
                    }
                    .elementor-element-<?php esc_html_e( $this->get_id() ); ?> .mdp-menuar-expand-position-top .mdp-menuar-elementor-main-menu--dropdown > .mdp-menuar-elementor-main-menu--item > .mdp-menuar-submenu-indicator,
                    .elementor-element-<?php esc_html_e( $this->get_id() ); ?> .mdp-menuar-expand-position-bottom .mdp-menuar-elementor-main-menu--dropdown > .mdp-menuar-elementor-main-menu--item > .mdp-menuar-submenu-indicator {
                        transform: rotate(0deg)
                    }
                    .elementor-element-<?php esc_html_e( $this->get_id() ); ?> .mdp-menuar-elementor-main-menu--dropdown {
                        animation: initial !important;
                    }
                    .elementor-element-<?php esc_html_e( $this->get_id() ); ?> .mdp-menuar-elementor-main-menu--dropdown.mdp-menuar-elementor-main-menu--dropdown-show {
                        display: block;
                    }
                    .elementor-element-<?php esc_html_e( $this->get_id() ); ?> .mdp-menuar-elementor-main-menu {
                        flex-direction: column;
                        padding-top: 50px;
                    }
                    .elementor-element-<?php esc_html_e( $this->get_id() ); ?> .mdp-menuar-elementor-toggle-btn {
                        display: flex !important;
                        align-items: center;
                        position: relative !important;
                    }
                    .elementor-element-<?php esc_html_e( $this->get_id() ); ?> .mdp-menuar-elementor-toggle-close-icon {
                        display: none;
                        cursor: pointer;
                    }
                    .elementor-element-<?php esc_html_e( $this->get_id() ); ?> .mdp-menuar-elementor-toggle-icon {
                        cursor: pointer;
                    }
                    .elementor-element-<?php esc_html_e( $this->get_id() ); ?> .mdp-menuar-elementor-menu-logo {
                        display: block !important;
                        z-index: 999;
                    }
                    .elementor-element-<?php esc_html_e( $this->get_id() ); ?> .mdp-menuar-elementor-menu-wrapper {
                        position: fixed;
                        overflow-y: auto;
                        top: 0;
                    <?php if( $settings['menu_position'] != 'top' ) {
                        esc_html_e( $settings['menu_position'] );
                    } ?>: -200%;
                    <?php if( $settings['menu_position'] == 'top' ): ?>
                        top: -200%;
                        left: 0;
                    <?php endif; ?>
                        z-index: 1000;
                        width: 85%;
                    <?php if( $settings['full_width'] == 'yes' ): ?>
                        width: 100%;
                    <?php endif; ?>
                        height: 100%;
                        background-color: #fff;
                        padding: 10px;
                        transition: all .3s ease;
                    }
                    .elementor-element-<?php esc_html_e( $this->get_id() ); ?>  .mdp-menuar-elementor-menu-wrapper.mdp-menuar-elementor-main-menu-wrapper--active{
                    <?php if( $settings['menu_position'] ) {
                        esc_html_e( $settings['menu_position'] );
                    } ?>: 0;
                    }
                    .elementor-element-<?php esc_html_e( $this->get_id() ); ?> .mdp-menuar-elementor-box ul {
                        padding-left: 0;
                    }
                    .elementor-element-<?php esc_html_e( $this->get_id() ); ?> .mdp-menuar-elementor-box ul li {
                        padding-top: 40px;
                    }
                    .elementor-element-<?php esc_html_e( $this->get_id() ); ?> .mdp-menuar-elementor-main-menu .mdp-menuar-elementor-main-menu--dropdown {
                        position: relative;
                        font-size: 20px;
                        left: 0 !important;
                    }
                    .elementor-element-<?php esc_html_e( $this->get_id() ); ?> .mdp-menuar-elementor-main-menu--item .mdp-menuar-elementor-main-menu--dropdown .mdp-menuar-elementor-main-menu--item .mdp-menuar-elementor-main-menu--dropdown {
                        position: relative;
                        font-size: 20px;
                        left: 0 !important;
                        top: 100%;
                    }
                    .elementor-element-<?php esc_html_e( $this->get_id() ); ?> .mdp-menuar-elementor-main-menu--item {
                        display: block;
                    }
                    .elementor-element-<?php esc_html_e( $this->get_id() ); ?> .mdp-menuar-elementor-main-menu--dropdown .mdp-menuar-elementor-main-menu--item > .mdp-menuar-elementor-main-menu--dropdown {
                        left: 0 !important;
                    }
                    .elementor-element-<?php esc_html_e( $this->get_id() ); ?> .mdp-menuar-elementor-main-menu.mdp-menuar-expand-position-bottom .mdp-menuar-elementor-main-menu--dropdown,
                    .elementor-element-<?php esc_html_e( $this->get_id() ); ?> .mdp-menuar-elementor-main-menu.mdp-menuar-expand-position-top .mdp-menuar-elementor-main-menu--dropdown {
                        top: 0 !important;
                    }

                    .elementor-element-<?php esc_html_e( $this->get_id() ); ?> .mdp-menuar-elementor-main-menu.mdp-menuar-expand-position-left .mdp-menuar-elementor-main-menu--dropdown{
                        top: 0 !important;
                        left: 0 !important;
                        right: auto !important;
                    }
                    .elementor-element-<?php esc_html_e( $this->get_id() ); ?> .mdp-menuar-elementor-main-menu.mdp-menuar-expand-position-right .mdp-menuar-elementor-main-menu--dropdown {
                        top: 0 !important;
                        right: 0 !important;
                        left: auto;
                    }
                }
            </style>
        <?php endif; ?>
        <?php
    }

    /**
     * Menu wrapper block.
     *
     * @access private
     *
     * @param $settings
     * @param $menu
     * @return void
     */
    private function menu_wrapper_render( $settings, $menu ) {
        ?>
        <div class="mdp-menuar-elementor-menu-wrapper <?php  esc_html_e( $settings['expand_menu_position_vertical'] )  ?> ">
            <?php  if ( $settings['responsive_breakpoint'] !== 'custom' || $settings['custom_breakpoint']['size'] > 0 ): ?>
                <div class="mdp-menuar-elementor-toggle-btn">
                    <div class="mdp-menuar-elementor-toggle-close-icon"><?php Icons_Manager::render_icon( $settings['close_toggle_icon'], ['aria-hidden' => true] ) ?></div>
                    <?php if( $settings['show_mobile_logo'] == 'yes' ): ?>
                        <a class="mdp-menuar-elementor-menu-logo <?php echo esc_attr( $settings['logo_align'] )?>" href="<?php echo esc_url( $settings['logo_link']['url'] )?>">
                            <img class="" src="<?php echo esc_url( $settings['mobile_logo']['url'] )?>" alt="">
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <?php echo wp_kses_post( $menu ); ?>
        </div>
        <?php
    }

    /**
     * Set menu arguments.
     *
     * @access private
     *
     * @param $settings
     * @return array
     */

    private function set_menu_arguments( $settings ) {
        $args = array(
            'menu' => $settings['select_menu'],
            'menu_class' => 'mdp-menuar-elementor-main-menu',
            'echo' => false,
            'container' => '',
        );

        //setting class for pointer
        if( $settings['pointer'] !== 'none' ) {
            $args['menu_class'] .= ' '.$settings['pointer'];
        }

        //setting classes for menu position and expand submenu position
        $args['menu_class'] .= ' mdp-menuar-expand-position-'.$settings['expand_menu_position_horizontal'];

        if( $settings['menu_layout'] === 'vertical' ) {
            $args['menu_class'] .= ' mdp-menuar-elementor-main-menu--vertical mdp-menuar-expand-position-'.$settings['expand_menu_position_vertical'];
        }

        return $args;
    }

    /**
     * Adding custom filters.
     *
     * @access private
     *
     * @return void
     */
    private function add_filter_menuar() {

        //adding custom filter classes on submenu
        add_filter( 'nav_menu_submenu_css_class', [ $this, 'add_sub_menu_classes' ] );

        //adding custom filter classes on links that contains dropdown
        add_filter( 'nav_menu_link_attributes', [ $this, 'add_class_to_items_link' ], 10, 3 );

        //adding custom filter classes on nav menu items
        add_filter( 'nav_menu_css_class', [ $this ,'custom_nav_item_classes' ], 10, 2 );

        //adding custom filter for adding quantity of posts in category
        add_filter( 'walker_nav_menu_start_el', [$this, 'menu_category_item_post_count'], 10, 4 );

        //adding custom filter adding menu indicator
        add_filter( 'walker_nav_menu_start_el', [$this, 'adding_menu_indicator'], 10, 4 );

    }

    /**
     * Removing custom filters.
     *
     * @access private
     *
     * @return void
     */
    private function remove_filter_menuar() {

        //delete custom filters
        remove_filter( 'nav_menu_link_attributes', [ $this, 'add_class_to_items_link' ] );
        remove_filter( 'nav_menu_submenu_css_class', [ $this, 'add_sub_menu_classes' ] );
        remove_filter( 'nav_menu_css_class', [$this, 'custom_nav_item_classes'] );
        remove_filter( 'walker_nav_menu_start_el', [$this, 'menu_category_item_post_count'] );
        remove_filter( 'walker_nav_menu_start_el', [$this, 'adding_menu_indicator'] );

    }

    /**
     * Render Frontend Output. Generate the final HTML on the frontend.
     *
     * @access protected
     *
     * @return void
     **/
    protected function render() {
        ?>
        <?php
        $settings = $this->get_settings_for_display();

        if ( !$this->get_menus() ) {
            return;
        }

        // adding filters
        $this->add_filter_menuar();

        //setting nav menu
        $menu = wp_nav_menu( $this->set_menu_arguments( $settings ) );

        //removing filters
        $this->remove_filter_menuar();

        ?>

        <!-- Start Menuar for Elementor WordPress Plugin -->
        <div class="mdp-menuar-elementor-box">
            <nav class="mdp-menuar-elementor-nav" data-breakpoint="<?php $settings['responsive_breakpoint'] != 'custom' ?
                esc_html_e( $settings['responsive_breakpoint'] ) :
                esc_html_e( $settings['custom_breakpoint']['size'].$settings['custom_breakpoint']['unit'] ) ?>" data-click-area="<?php echo esc_attr( $settings['submenu_click_area'] ); ?>">
                <?php  if ( $settings['responsive_breakpoint'] !== 'custom' || $settings['custom_breakpoint']['size'] > 0 ): ?>
                    <div class="mdp-menuar-elementor-toggle-btn">
                        <div class="mdp-menuar-elementor-toggle-icon"><?php Icons_Manager::render_icon( $settings['toggle_icon'], ['aria-hidden' => true] ) ?></div>
                    </div>
                <?php endif; ?>
                <?php $this->menu_wrapper_render( $settings, $menu ); ?>
            </nav>
            <?php $this->mobile_styles_render( $settings ); ?>
        </div>

        <!-- End Menuar for Elementor WordPress Plugin -->
        <?php
        if ( is_admin() ) {
            $widget_hash = substr( hash( 'ripemd160', date('l jS \of F Y h:i:s A') ), rand( 0 , 20 ), 3 ) . rand( 11 , 99 );
            ?>

            <!--suppress JSUnresolvedFunction -->
            <script>
                try {
                    menuarReady<?php esc_attr_e( $widget_hash ); ?>( menuarElementor.addMenu.bind( menuarElementor) );
                } catch ( msg ) {
                    const menuarReady<?php esc_attr_e( $widget_hash ); ?> = ( callback ) => {
                        'loading' !== document.readyState ?
                            callback() :
                            document.addEventListener( 'DOMContentLoaded', callback );
                    };
                    menuarReady<?php esc_attr_e( $widget_hash ); ?>( menuarElementor.addMenu.bind( menuarElementor ) );
                }
            </script>
            <?php
        }

    }

    /**
     * Adding classes to menu items links
     *
     * @access public
     *
     * @param $atts
     * @param $item
     * @return mixed
     */
    public function add_class_to_items_link( $atts, $item ) {

        // check if the item has children
        $hasChildren = ( in_array( 'menu-item-has-children', $item->classes ) );
        if ( $hasChildren ) {
            // add custom attributes:
            $atts['class'] = 'mdp-menuar-elementor-main-menu--dropdown-link';
            $atts['data-toggle'] = 'dropdown';
            $atts['data-target'] = '#';
        } else {
            $atts['class'] = 'mdp-menuar-elementor-main-menu--link';
        }
        return $atts;

    }

    /**
     * Adding classes to navigation menu items
     *
     * @access public
     *
     * @param $classes
     * @param $item
     * @return mixed
     */
    public function custom_nav_item_classes( $classes, $item ) {

        if ( in_array( 'current-menu-item', $item->classes ) ) {
            $classes[] .= ' mdp-menuar-elementor-main-menu--item-active';
        }

        $classes[] = 'mdp-menuar-elementor-main-menu--item';

        return $classes;
    }

    /**
     * Adding submenu classes method
     *
     * @access public
     *
     * @param $classes
     * @return mixed
     */
    public function add_sub_menu_classes( $classes ) {

        $settings = $this->get_settings_for_display();

        $classes[] = 'mdp-menuar-elementor-main-menu--dropdown';

        if( $settings['submenu_pointer'] != 'none' ) {
            $classes[] .= $settings['submenu_pointer'];
        }

        return $classes;
    }

    /**
     * Adding submenu menu indicator method
     *
     * @access public
     *
     * @param $item_output
     * @param $item
     * @return mixed|string|string[]
     */
    public function adding_menu_indicator($item_output, $item) {

        $settings = $this->get_settings_for_display();

        if (in_array('menu-item-has-children', $item->classes)) {
            if ( $settings['submenu_indicator'] === 'custom' ) {
                $arrow = '<i class="mdp-menuar-submenu-indicator '.esc_attr__( $settings['custom_submenu_indicator']['value'] ).'"></i>';
            } else {
                $arrow = '<i class="mdp-menuar-submenu-indicator '.esc_attr__( $settings['submenu_indicator'] ).'"></i>';
            }
            $item_output = str_replace('</a>', '</a>'. $arrow .'', $item_output);
        }
        return $item_output;
    }

    /**
     * Adding menu category post count
     *
     * @access public
     *
     * @param $output
     * @param $item
     * @return mixed|string|string[]
     */
    public function menu_category_item_post_count( $output, $item ) {

        $settings = $this->get_settings_for_display();

        if( $item->type == 'taxonomy' ) {
            $object = get_term($item->object_id, $item->object);

            if( $settings['show_quantity_posts_in_category'] === 'yes' ) {
                $output .= "<span class='mdp-menuar-elementor-category-post-count ".esc_attr( $settings['count_of_posts_label_align'] )." '>" . $object->count . "</span>";
            } else {
                $output .= '';
            }
        }

        return $output;
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

        return 'https://docs.merkulov.design/tag/menuar';

    }

}
