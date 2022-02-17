<?php

namespace DynamicContentForElementor\Widgets;

use Elementor\Controls_Manager;
use DynamicContentForElementor\Helper;
if (!\defined('ABSPATH')) {
    exit;
}
// Exit if accessed directly
class ShortcodeWithTokens extends \DynamicContentForElementor\Widgets\WidgetPrototype
{
    protected function _register_controls()
    {
        if (\DynamicContentForElementor\Helper::can_register_unsafe_controls()) {
            $this->_register_controls_content();
        } else {
            $this->register_controls_non_admin_notice();
        }
    }
    protected function _register_controls_content()
    {
        $this->start_controls_section('section_doshortcode', ['label' => $this->get_title()]);
        $this->add_control('doshortcode_string', ['label' => $this->get_title(), 'type' => Controls_Manager::TEXTAREA, 'description' => __('Example:', 'dynamic-content-for-elementor') . ' [gallery ids="[post:custom-meta]"]']);
        $this->end_controls_section();
    }
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        if (empty($settings) || empty($settings['doshortcode_string'])) {
            return;
        }
        $doshortcode_string = sanitize_text_field($settings['doshortcode_string']);
        echo do_shortcode(Helper::get_dynamic_value($doshortcode_string));
    }
}
