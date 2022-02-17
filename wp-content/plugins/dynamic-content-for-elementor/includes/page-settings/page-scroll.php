<?php

namespace DynamicContentForElementor\PageSettings;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use DynamicContentForElementor\Helper;
if (!\defined('ABSPATH')) {
    exit;
    // Exit if accessed directly
}
class PageScroll extends \DynamicContentForElementor\PageSettings\DCE_Document_Prototype
{
    public $name = 'Scrolling';
    protected $is_common = \true;
    public function get_script_depends()
    {
        return ['imagesloaded', 'velocity', 'dce-gsap-lib', 'dce-inertia-scroll', 'jquery-easing', 'dce-scrollify', 'dce-lax-lib', 'dce-scrolling'];
    }
    public function get_style_depends()
    {
        return ['dce-pageScroll'];
    }
    protected function add_common_sections_actions()
    {
        // Activate sections for document
        add_action('elementor/documents/register_controls', function ($element) {
            $this->add_common_sections($element);
        }, 10, 2);
    }
    private function add_controls($document, $args)
    {
        $element_type = $document->get_type();
        $id_page = Helper::get_the_id();
        $document->add_control('enable_dceScrolling', ['label' => __('Scrolling settings', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SWITCHER, 'render_type' => 'template', 'frontend_available' => \true]);
        $document->add_control('scroll_id_page', ['label' => __('ID Page', 'dynamic-content-for-elementor'), 'type' => \Elementor\Controls_Manager::HIDDEN, 'default' => $id_page, 'frontend_available' => \true, 'condition' => ['enable_dceScrolling!' => '']]);
        // ----------------------------------- EFFECTS --------------------------
        $document->add_control('enable_scrollEffects', ['label' => __('Effects Scroll', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SWITCHER, 'render_type' => 'template', 'frontend_available' => \true, 'separator' => 'before', 'condition' => ['enable_dceScrolling!' => '']]);
        $document->add_control('animation_effects', ['label' => __('Animation Effects', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SELECT2, 'multiple' => \true, 'label_block' => \true, 'options' => [
            '' => __('None', 'dynamic-content-for-elementor'),
            'scaleDown' => __('Scale Down', 'dynamic-content-for-elementor'),
            // 'gallery' => __('Gallery', 'dynamic-content-for-elementor'),
            'opacity' => __('Opacity', 'dynamic-content-for-elementor'),
            'fixed' => __('Fixed', 'dynamic-content-for-elementor'),
            //'parallax' => __('Parallax', 'dynamic-content-for-elementor'),
            'rotation' => __('Rotation', 'dynamic-content-for-elementor'),
            //'linger' => __('Linger', 'dynamic-content-for-elementor'),
            'lazy' => __('Lazy', 'dynamic-content-for-elementor'),
            'eager' => __('Eger', 'dynamic-content-for-elementor'),
            'slalom' => __('Slalom', 'dynamic-content-for-elementor'),
            // 'crazy' => __('Crazy', 'dynamic-content-for-elementor'),
            'spin' => __('Spin', 'dynamic-content-for-elementor'),
            'spinRev' => __('SpinRev', 'dynamic-content-for-elementor'),
            // 'spinIn' => __('SpinIn', 'dynamic-content-for-elementor'),
            // 'spinOut' => __('SpinOut', 'dynamic-content-for-elementor'),
            // 'blurInOut' => __('BlurInOut', 'dynamic-content-for-elementor'),
            // 'blurIn' => __('BlurIn', 'dynamic-content-for-elementor'),
            // 'blurOut' => __('BlurOut', 'dynamic-content-for-elementor'),
            // 'fadeInOut' => __('FadeInOut', 'dynamic-content-for-elementor'),
            // 'fadeIn' => __('FadeIn', 'dynamic-content-for-elementor'),
            // 'fadeOut' => __('FadeOut', 'dynamic-content-for-elementor'),
            'driftLeft' => __('DriftLeft', 'dynamic-content-for-elementor'),
            'driftRight' => __('DriftRight', 'dynamic-content-for-elementor'),
            'leftToRight' => __('LeftToRight', 'dynamic-content-for-elementor'),
            'rightToLeft' => __('RightToLeft', 'dynamic-content-for-elementor'),
            'zoomInOut' => __('ZoomInOut', 'dynamic-content-for-elementor'),
            'zoomIn' => __('ZoomIn', 'dynamic-content-for-elementor'),
            'zoomOut' => __('ZoomOut', 'dynamic-content-for-elementor'),
            'swing' => __('Swing', 'dynamic-content-for-elementor'),
            'speedy' => __('Speedy', 'dynamic-content-for-elementor'),
        ], 'default' => ['scaleDown'], 'frontend_available' => \true, 'render_type' => 'template', 'condition' => ['enable_scrollEffects!' => '', 'enable_dceScrolling!' => '']]);
        $document->add_control('remove_first_scrollEffects', ['label' => __('Remove Effect on first row', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SWITCHER, 'render_type' => 'template', 'frontend_available' => \true, 'condition' => ['enable_scrollEffects!' => '', 'enable_dceScrolling!' => '']]);
        $document->add_control('custom_class_section', ['label' => __('Custom section class', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::TEXT, 'default' => '', 'frontend_available' => \true, 'separator' => 'before', 'dynamic' => ['active' => \false], 'condition' => ['enable_scrollEffects!' => '', 'enable_dceScrolling!' => '']]);
        $document->add_control('responsive_scrollEffects', ['label' => __('Apply Scroll Effects', 'dynamic-content-for-elementor'), 'description' => __('Responsive mode will take place on preview or live pages only, not while editing in Elementor.', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SELECT2, 'multiple' => \true, 'separator' => 'before', 'label_block' => \true, 'options' => \array_combine(Helper::get_active_devices_list(), Helper::get_active_devices_list()), 'default' => ['desktop', 'tablet', 'mobile'], 'frontend_available' => \true, 'render_type' => 'template', 'condition' => ['enable_dceScrolling!' => '', 'enable_scrollEffects!' => '']]);
        // ----------------------------------- SNAP --------------------------
        $document->add_control('enable_scrollify', ['label' => __('Snap Scroll', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SWITCHER, 'frontend_available' => \true, 'separator' => 'before', 'condition' => ['enable_dceScrolling!' => '']]);
        $document->add_control('custom_class_section_sfy', ['label' => __('Custom section class', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::TEXT, 'default' => '', 'frontend_available' => \true, 'label_block' => \true, 'dynamic' => ['active' => \false], 'condition' => ['enable_scrollify!' => '', 'enable_dceScrolling!' => '']]);
        $document->add_control('interstitialSection', ['label' => __('Interstitial Section', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::TEXT, 'placeholder' => 'header, footer', 'frontend_available' => \true, 'label_block' => \true, 'dynamic' => ['active' => \false], 'condition' => ['enable_scrollify!' => '', 'enable_dceScrolling!' => '']]);
        $document->add_control('scrollSpeed', ['label' => __('Scroll Speed', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SLIDER, 'default' => ['size' => 1000], 'range' => ['px' => ['min' => 500, 'max' => 2400, 'step' => 10]], 'size_units' => ['ms'], 'frontend_available' => \true, 'condition' => ['enable_scrollify!' => '', 'enable_dceScrolling!' => '']]);
        $document->add_control('offset', ['label' => __('Offset', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SLIDER, 'default' => ['size' => 0], 'range' => ['px' => ['min' => -500, 'max' => 500, 'step' => 1]], 'size_units' => ['px'], 'frontend_available' => \true, 'condition' => ['enable_scrollify!' => '', 'enable_dceScrolling!' => '']]);
        $document->add_control('setHeights', ['label' => __('Set section height to full screen', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SWITCHER, 'frontend_available' => \true, 'default' => 'yes', 'condition' => ['enable_scrollify!' => '', 'enable_dceScrolling!' => '']]);
        $document->add_control('overflowScroll', ['label' => __('Overflow Scroll', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SWITCHER, 'frontend_available' => \true, 'default' => 'yes', 'condition' => ['enable_scrollify!' => '', 'enable_dceScrolling!' => '']]);
        $document->add_control('updateHash', ['label' => __('Update Hash', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SWITCHER, 'frontend_available' => \true, 'condition' => ['enable_scrollify!' => '', 'enable_dceScrolling!' => '']]);
        $document->add_control('scrollBars', ['label' => __('Show scrollbar', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'frontend_available' => \true, 'condition' => ['enable_scrollify!' => '', 'enable_dceScrolling!' => '']]);
        $document->add_control('touchScroll', ['label' => __('Touch Scroll', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'frontend_available' => \true, 'condition' => ['enable_scrollify!' => '', 'enable_dceScrolling!' => '']]);
        $document->add_control('enable_scrollify_nav', ['label' => __('Enable navigation', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'frontend_available' => \true, 'render_type' => 'template', 'separator' => 'before', 'condition' => ['enable_scrollify!' => '', 'enable_dceScrolling!' => '']]);
        $document->add_control('snapscroll_nav_style', ['label' => __('Navigation style', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SELECT, 'default' => 'default', 'options' => [
            'default' => __('Dynamic', 'dynamic-content-for-elementor'),
            // quello che c'è già, simile a Maxamed
            'shamso' => __('Dots', 'dynamic-content-for-elementor'),
            // Shamso
            'xusni' => __('Bars', 'dynamic-content-for-elementor'),
            // Xusni oppure Beca
            'etefu' => __('Vertical Bars', 'dynamic-content-for-elementor'),
            // Etefu
            'magool' => __('Lines (without title)', 'dynamic-content-for-elementor'),
            // Magool
            'ubax' => __('Squares', 'dynamic-content-for-elementor'),
            // Ubax
            'timiro' => __('Circles', 'dynamic-content-for-elementor'),
            // Timiro
            'ayana' => __('Circles line (SVG)', 'dynamic-content-for-elementor'),
            // Ayana
            'desta' => __('Triangles', 'dynamic-content-for-elementor'),
            // Desta
            'totit' => __('Icons', 'dynamic-content-for-elementor'),
        ], 'render_type' => 'template', 'frontend_available' => \true, 'condition' => ['enable_scrollify!' => '', 'enable_dceScrolling!' => '', 'enable_scrollify_nav!' => '']]);
        $document->add_control('snapscroll_nav_title_style', ['label' => __('Show section\'s title', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SELECT, 'separator' => 'before', 'default' => 'none', 'options' => ['none' => __('None', 'dynamic-content-for-elementor'), 'number' => __('Number', 'dynamic-content-for-elementor'), 'classid' => __('Section CSS ID', 'dynamic-content-for-elementor')], 'frontend_available' => \true, 'condition' => ['enable_scrollify!' => '', 'enable_dceScrolling!' => '', 'enable_scrollify_nav!' => '', 'snapscroll_nav_style!' => ['magool', 'timiro']]]);
        $document->add_control('sectionid_info', ['label' => __('Section class-id info', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::RAW_HTML, 'show_label' => \false, 'default' => '', 'raw' => __('You should write the class ID on the sections first and then apply this option to see the result. The name on the ID must not contain spaces or use (-) or (_) to separate words, in the result they will be converted into spaces.', 'dynamic-content-for-elementor'), 'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning', 'separator' => 'after', 'condition' => ['enable_scrollify!' => '', 'enable_dceScrolling!' => '', 'enable_scrollify_nav!' => '', 'snapscroll_nav_title_style' => 'classid']]);
        $document->add_group_control(Group_Control_Typography::get_type(), ['name' => 'nav_title_typography', 'label' => __('Title Typography', 'dynamic-content-for-elementor'), 'selector' => '{{WRAPPER}} .nav__item-title', 'condition' => ['enable_scrollify!' => '', 'enable_dceScrolling!' => '', 'enable_scrollify_nav!' => '', 'snapscroll_nav_title_style!' => 'none']]);
        $document->add_control('nav_title_color', ['label' => __('Title Color', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::COLOR, 'default' => '', 'selectors' => ['{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination .nav__item--current .nav__item-title' => 'color: {{VALUE}}'], 'condition' => ['enable_scrollify!' => '', 'enable_dceScrolling!' => '', 'enable_scrollify_nav!' => '', 'snapscroll_nav_title_style!' => 'none']]);
        $document->add_control('scrollify_nav_icon', ['label' => __('Icon', 'dynamic-content-for-elementor'), 'type' => 'icons', 'default' => ['value' => 'fas fa-star', 'library' => 'solid'], 'frontend_available' => \true, 'condition' => ['enable_scrollify!' => '', 'enable_dceScrolling!' => '', 'enable_scrollify_nav!' => '', 'snapscroll_nav_style' => 'totit']]);
        $document->add_responsive_control('scrollify_nav_size', ['label' => __('Size', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SLIDER, 'separator' => 'before', 'default' => ['size' => 10], 'range' => ['px' => ['min' => 0, 'max' => 80, 'step' => 1]], 'size_units' => ['px'], 'selectors' => ['{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--default a:after, {{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--magool .nav__item, {{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--shamso .nav__item, {{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--xusni .nav__item, {{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--etefu .nav__item, {{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--ayana .nav__item,
                      {{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--totit .nav__item,
                      {{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--totit .nav__item-title,.dce-scrollify-pagination.nav--ubax .nav__item,
                      {{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--desta .nav__icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};', '{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--totit .nav__item .fas' => 'font-size: {{SIZE}}{{UNIT}};', '{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--desta .nav__item-title' => 'padding-right: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};', '{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--totit .nav__item-title,.dce-scrollify-pagination.nav--ubax .nav__item-title' => 'right: {{SIZE}}{{UNIT}};'], 'render-type' => 'ui', 'condition' => ['enable_scrollify!' => '', 'enable_dceScrolling!' => '', 'enable_scrollify_nav!' => '']]);
        $document->add_responsive_control('scrollify_nav_iconsize', ['label' => __('Icon size', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SLIDER, 'default' => ['size' => ''], 'range' => ['px' => ['min' => 0, 'max' => 80, 'step' => 1]], 'size_units' => ['px'], 'selectors' => ['{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--totit .nav__item .fas' => 'font-size: {{SIZE}}{{UNIT}};'], 'condition' => ['enable_scrollify!' => '', 'enable_dceScrolling!' => '', 'enable_scrollify_nav!' => '', 'snapscroll_nav_style' => 'totit']]);
        $document->add_responsive_control('scrollify_nav_space', ['label' => __('Space', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SLIDER, 'default' => ['size' => 10], 'range' => ['px' => ['min' => 0, 'max' => 100, 'step' => 1]], 'size_units' => ['px'], 'selectors' => ['{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination li:not(first-child)' => 'padding-top: {{SIZE}}{{UNIT}}'], 'condition' => ['enable_scrollify!' => '', 'enable_dceScrolling!' => '', 'enable_scrollify_nav!' => '']]);
        $document->add_responsive_control('scrollify_nav_side', ['label' => __('Side space', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SLIDER, 'default' => ['size' => 10, 'unit' => 'px'], 'size_units' => ['px', '%', 'vw'], 'range' => ['px' => ['min' => 0, 'max' => 80, 'step' => 1], '%' => ['min' => 0, 'max' => 100, 'step' => 1], 'vw' => ['min' => 0, 'max' => 100, 'step' => 1]], 'selectors' => ['{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination' => 'right: {{SIZE}}{{UNIT}};'], 'condition' => ['enable_scrollify!' => '', 'enable_dceScrolling!' => '', 'enable_scrollify_nav!' => '']]);
        $document->add_control('scrollify_nav_style_color', ['label' => __('Color', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::COLOR, 'separator' => 'before', 'default' => '', 'selectors' => ['{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--shamso .nav__item::before, {{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--xusni .nav__item::before, {{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--etefu .nav__item-inner, {{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--magool .nav__item::after, {{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--ubax .nav__item::after, {{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--timiro .nav__item, {{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--totit .nav__item::before' => 'background: {{VALUE}};', '{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--shamso .nav__item::after' => 'box-shadow: inset 0 0 0 3px {{VALUE}};', '{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--ayana .nav__icon' => 'stroke: {{VALUE}};', '{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--desta .nav__icon' => 'fill: {{VALUE}}', '{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--totit .nav__item--current .fas' => 'color: {{VALUE}}'], 'condition' => ['enable_scrollify!' => '', 'enable_dceScrolling!' => '', 'enable_scrollify_nav!' => '', 'snapscroll_nav_style!' => 'default']]);
        $document->add_control('scrollify_nav_style_active_color', ['label' => __('Active Color', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::COLOR, 'separator' => 'before', 'default' => '', 'selectors' => ['{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--shamso .nav__item--current::after' => 'box-shadow: inset 0 0 0 3px {{VALUE}};', '{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--xusni .nav__item--current::before' => 'background-color: {{VALUE}};', '{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination .nav__item--current' => 'color: {{VALUE}};', '{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--ubax .nav__item--current::after' => 'border-color: {{VALUE}}', '{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--etefu .nav__item-inner::before, {{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--magool .nav__item--current::after,
                        {{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--ubax .nav__item--current::after,
                        {{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--ubax .nav__item:not(.nav__item--current):focus::after, {{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--ubax .nav__item:not(.nav__item--current):hover::after, {{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--timiro .nav__item::before, {{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--ayana .nav__item::before' => 'background-color: {{VALUE}};', '{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--desta .nav__item--current .nav__icon, {{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--desta .nav__item:not(.nav__item--current):focus .nav__icon, {{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--desta .nav__item:not(.nav__item--current):hover .nav__icon ' => 'fill: {{VALUE}};', '{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--totit .nav__item--current .fas' => 'color: {{VALUE}};'], 'condition' => ['enable_scrollify!' => '', 'enable_dceScrolling!' => '', 'enable_scrollify_nav!' => '', 'snapscroll_nav_style!' => 'default']]);
        $document->add_control('scrollify_nav_style_active_bordercolor', ['label' => __('Active Border Color', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::COLOR, 'separator' => 'before', 'default' => '', 'selectors' => ['{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--ubax .nav__item--current::after' => 'border-color: {{VALUE}}'], 'condition' => ['enable_scrollify!' => '', 'enable_dceScrolling!' => '', 'enable_scrollify_nav!' => '', 'snapscroll_nav_style' => 'ubax']]);
        $document->start_controls_tabs('nav_colors');
        $document->start_controls_tab('nav_colors_normal', ['label' => __('Normal', 'dynamic-content-for-elementor'), 'condition' => ['enable_scrollify!' => '', 'enable_dceScrolling!' => '', 'enable_scrollify_nav!' => '', 'snapscroll_nav_style' => 'default']]);
        $document->add_control('scrollify_nav_bgcolor', ['label' => __('Color', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::COLOR, 'default' => '', 'selectors' => ['{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--default a:after' => 'background-color: {{VALUE}};'], 'condition' => ['enable_scrollify!' => '', 'enable_dceScrolling!' => '', 'enable_scrollify_nav!' => '', 'snapscroll_nav_style' => 'default']]);
        $document->add_control('scrollify_nav_color', ['label' => __('Border color', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::COLOR, 'default' => '#444444', 'selectors' => ['{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--default a:after' => 'border-color: {{VALUE}};'], 'condition' => ['enable_scrollify!' => '', 'enable_dceScrolling!' => '', 'enable_scrollify_nav!' => '', 'snapscroll_nav_style' => 'default']]);
        $document->add_control('scrollify_nav_border_size', ['label' => __('Border size', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SLIDER, 'default' => ['size' => ''], 'range' => ['px' => ['min' => 0, 'max' => 20, 'step' => 1]], 'size_units' => ['px'], 'selectors' => ['{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--default a:after' => 'border-width: {{SIZE}}{{UNIT}};'], 'condition' => ['enable_scrollify!' => '', 'enable_dceScrolling!' => '', 'enable_scrollify_nav!' => '', 'snapscroll_nav_style' => 'default']]);
        $document->end_controls_tab();
        $document->start_controls_tab('nav_colors_hover', ['label' => __('Hover', 'dynamic-content-for-elementor'), 'condition' => ['enable_scrollify!' => '', 'enable_dceScrolling!' => '', 'enable_scrollify_nav!' => '', 'snapscroll_nav_style' => 'default']]);
        $document->add_control('scrollify_nav_bgcolor_hover', ['label' => __('Color', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::COLOR, 'default' => '', 'selectors' => ['{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--default a:hover:after' => 'background-color: {{VALUE}};'], 'condition' => ['enable_scrollify!' => '', 'enable_dceScrolling!' => '', 'enable_scrollify_nav!' => '', 'snapscroll_nav_style' => 'default']]);
        $document->add_control('scrollify_nav_color_hover', ['label' => __('Border color', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::COLOR, 'default' => '', 'selectors' => ['{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--default a:hover:after' => 'border-color: {{VALUE}};'], 'condition' => ['enable_scrollify!' => '', 'enable_dceScrolling!' => '', 'enable_scrollify_nav!' => '', 'snapscroll_nav_style' => 'default']]);
        $document->add_responsive_control('scrollify_nav_hover_size', ['label' => __('Size (&)', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SLIDER, 'default' => ['size' => ''], 'range' => ['px' => ['min' => 0, 'max' => 10, 'step' => 0.1]], 'size_units' => ['px'], 'selectors' => ['{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--default a:hover:after' => 'transform: scale({{SIZE}});'], 'condition' => ['enable_scrollify!' => '', 'enable_dceScrolling!' => '', 'enable_scrollify_nav!' => '', 'snapscroll_nav_style' => 'default']]);
        $document->add_control('scrollify_nav_border_hover_size', ['label' => __('Border size', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SLIDER, 'default' => ['size' => ''], 'range' => ['px' => ['min' => 0, 'max' => 20, 'step' => 1]], 'size_units' => ['px'], 'selectors' => ['{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--default a:hover:after' => 'border-width: {{SIZE}}{{UNIT}};'], 'condition' => ['enable_scrollify!' => '', 'enable_dceScrolling!' => '', 'enable_scrollify_nav!' => '', 'snapscroll_nav_style' => 'default']]);
        $document->end_controls_tab();
        $document->start_controls_tab('nav_colors_active', ['label' => __('Active', 'dynamic-content-for-elementor'), 'condition' => ['enable_scrollify!' => '', 'enable_dceScrolling!' => '', 'enable_scrollify_nav!' => '', 'snapscroll_nav_style' => 'default']]);
        $document->add_control('scrollify_nav_bgcolor_active', ['label' => __('Color', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::COLOR, 'default' => '', 'selectors' => ['{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--default a.nav__item--current:after' => 'background-color: {{VALUE}};'], 'condition' => ['enable_scrollify!' => '', 'enable_dceScrolling!' => '', 'enable_scrollify_nav!' => '', 'snapscroll_nav_style' => 'default']]);
        $document->add_control('scrollify_nav_color_active', ['label' => __('Border color', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::COLOR, 'default' => '', 'selectors' => ['{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--default a.nav__item--current:after' => 'border-color: {{VALUE}};'], 'condition' => ['enable_scrollify!' => '', 'enable_dceScrolling!' => '', 'enable_scrollify_nav!' => '', 'snapscroll_nav_style' => 'default']]);
        $document->add_responsive_control('scrollify_nav_active_size', ['label' => __('Size (%)', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SLIDER, 'default' => ['size' => ''], 'range' => ['px' => ['min' => 0, 'max' => 10, 'step' => 0.1]], 'size_units' => ['px'], 'selectors' => ['{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--default a.nav__item--current:after' => 'transform: scale({{SIZE}});'], 'condition' => ['enable_scrollify!' => '', 'enable_dceScrolling!' => '', 'enable_scrollify_nav!' => '', 'snapscroll_nav_style' => 'default']]);
        $document->add_control('scrollify_nav_border_active_size', ['label' => __('Border size', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SLIDER, 'default' => ['size' => ''], 'range' => ['px' => ['min' => 0, 'max' => 20, 'step' => 1]], 'size_units' => ['px'], 'selectors' => ['{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--default a.nav__item--current:after' => 'border-width: {{SIZE}}{{UNIT}};'], 'condition' => ['enable_scrollify!' => '', 'enable_dceScrolling!' => '', 'enable_scrollify_nav!' => '', 'snapscroll_nav_style' => 'default']]);
        $document->end_controls_tab();
        $document->end_controls_tabs();
        $document->add_control('responsive_snapScroll', ['label' => __('Apply Snap Scroll on', 'dynamic-content-for-elementor'), 'description' => __('Responsive mode will take place on preview or live pages only, not while editing in Elementor.', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SELECT2, 'multiple' => \true, 'separator' => 'before', 'label_block' => \true, 'options' => \array_combine(Helper::get_active_devices_list(), Helper::get_active_devices_list()), 'default' => ['desktop', 'tablet', 'mobile'], 'frontend_available' => \true, 'render_type' => 'template', 'condition' => ['enable_dceScrolling!' => '', 'enable_scrollify!' => '']]);
        // ----------------------------------- INERTIA --------------------------
        $document->add_control('enable_inertiaScroll', ['label' => __('Inertia Scroll', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SWITCHER, 'separator' => 'before', 'frontend_available' => \true, 'condition' => ['enable_dceScrolling!' => '']]);
        $document->add_control('scroll_info', ['label' => __('Settings Scroll', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::RAW_HTML, 'show_label' => \false, 'default' => '', 'raw' => __('Scrolling management compromises various elements of the page (not just Elementor). In order to function correctly and obtain the transformations, it is necessary to indicate the CSS selectors of the theme used. By default we indicate the elements for OceanWP theme.', 'dynamic-content-for-elementor'), 'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning', 'separator' => 'after', 'condition' => ['enable_dceScrolling!' => '', 'enable_inertiaScroll!' => '']]);
        $document->add_control('automatic_wrapper', ['label' => __('Automatic Wrapper', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SWITCHER, 'frontend_available' => \true, 'render_type' => 'template', 'condition' => ['enable_dceScrolling!' => '', 'enable_inertiaScroll!' => '']]);
        $document->add_control('scroll_viewport', ['label' => __('Viewport', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::TEXT, 'default' => '#outer-wrap', 'frontend_available' => \true, 'dynamic' => ['active' => \false], 'condition' => ['enable_dceScrolling!' => '', 'enable_inertiaScroll!' => '', 'automatic_wrapper' => '']]);
        $document->add_control('scroll_contentScroll', ['label' => __('Content Scroll', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::TEXT, 'default' => '#wrap', 'frontend_available' => \true, 'dynamic' => ['active' => \false], 'condition' => ['enable_dceScrolling!' => '', 'enable_inertiaScroll!' => '', 'automatic_wrapper' => '']]);
        $document->add_control('coefSpeed_inertiaScroll', ['label' => __('Speed coefficient', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SLIDER, 'separator' => 'before', 'default' => ['size' => '0.05'], 'range' => ['px' => ['max' => 0.5, 'min' => 0, 'step' => 0.01]], 'frontend_available' => \true, 'condition' => ['enable_dceScrolling!' => '', 'enable_inertiaScroll!' => '']]);
        $document->add_control('bounce_inertiaScroll', ['label' => __('Bounce', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SLIDER, 'default' => ['size' => '0'], 'range' => ['px' => ['max' => 0.8, 'min' => 0, 'step' => 0.01]], 'frontend_available' => \true, 'condition' => ['enable_dceScrolling!' => '', 'enable_inertiaScroll!' => '']]);
        $document->add_control('skew_inertiaScroll', ['label' => __('Skew', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SLIDER, 'default' => ['size' => '10'], 'range' => ['px' => ['max' => 20, 'min' => 0, 'step' => 1]], 'frontend_available' => \true, 'condition' => ['enable_dceScrolling!' => '', 'enable_inertiaScroll!' => '']]);
        $document->add_control('directionScroll', ['label' => __('Scroll Direction', 'dynamic-content-for-elementor'), 'type' => \Elementor\Controls_Manager::SELECT, 'default' => 'vertical', 'options' => ['vertical' => __('Vertical', 'dynamic-content-for-elementor'), 'horizontal' => __('Horizontal', 'dynamic-content-for-elementor')], 'frontend_available' => \true, 'condition' => ['enable_dceScrolling!' => '', 'enable_inertiaScroll!' => '']]);
        $document->add_control('responsive_inertiaScroll', ['label' => __('Apply Inertia Scroll on', 'dynamic-content-for-elementor'), 'description' => __('Responsive mode will take place on preview or live pages only, not while editing in Elementor.', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SELECT2, 'multiple' => \true, 'separator' => 'before', 'label_block' => \true, 'options' => \array_combine(Helper::get_active_devices_list(), Helper::get_active_devices_list()), 'default' => ['desktop'], 'frontend_available' => \true, 'render_type' => 'template', 'condition' => ['enable_dceScrolling!' => '', 'enable_inertiaScroll!' => '']]);
    }
    protected function add_actions()
    {
        add_action('elementor/element/wp-post/section_dce_document_scroll/before_section_end', function ($element, $args) {
            $this->add_controls($element, $args);
        }, 10, 2);
        add_action('elementor/element/wp-page/section_dce_document_scroll/before_section_end', function ($element, $args) {
            $this->add_controls($element, $args);
        }, 10, 2);
        add_action('elementor/element/page/section_dce_document_scroll/before_section_end', function ($element, $args) {
            $this->add_controls($element, $args);
        }, 10, 2);
        add_action('elementor/element/section/section_dce_document_scroll/before_section_end', function ($element, $args) {
            $this->add_controls($element, $args);
        }, 10, 2);
        add_action('elementor/frontend/after_enqueue_scripts', function () {
            $post_id = isset($_GET['post']) ? \intval($_GET['post']) : get_the_ID();
            $settings = get_post_meta($post_id, '_elementor_page_settings', \true);
            if (empty($settings) || !\is_array($settings)) {
                return;
            }
            if (!empty($settings['enable_dceScrolling'])) {
                $this->enqueue_all();
            }
        });
    }
}
