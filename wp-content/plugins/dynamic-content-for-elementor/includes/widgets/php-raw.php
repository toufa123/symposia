<?php

namespace DynamicContentForElementor\Widgets;

use Elementor\Controls_Manager;
use DynamicContentForElementor\Helper;
if (!\defined('ABSPATH')) {
    exit;
}
// Exit if accessed directly
class PhpRaw extends \DynamicContentForElementor\Widgets\WidgetPrototype
{
    public function show_in_panel()
    {
        if (!current_user_can('administrator')) {
            return \false;
        }
        return \true;
    }
    protected function _register_controls()
    {
        if (Helper::can_register_unsafe_controls()) {
            $this->register_controls_content();
        } else {
            $this->register_controls_non_admin_notice();
        }
    }
    protected function register_controls_content()
    {
        $this->start_controls_section('section_rawphp', ['label' => __('PHP Raw', 'dynamic-content-for-elementor')]);
        $this->add_control('custom_php', ['label' => __('PHP Code', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::CODE, 'language' => 'php']);
        $this->end_controls_section();
    }
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        if (!\DynamicContentForElementor\Helper::can_register_unsafe_controls()) {
            $this->render_non_admin_notice();
        } else {
            $evalError = \false;
            // The following is needed because if the code echoes only a
            // '0', Elementor will not render the widget at all.
            echo '<!-- Dynamic PHP Raw -->';
            try {
                @eval($settings['custom_php']);
            } catch (\ParseError $e) {
                $evalError = \true;
            } catch (\Throwable $e) {
                $evalError = \true;
            }
            if ($evalError && \Elementor\Plugin::$instance->editor->is_edit_mode()) {
                echo '<strong>';
                echo __('Please check your PHP code', 'dynamic-content-for-elementor');
                echo '</strong><br />';
                echo 'ERROR: ', $e->getMessage(), "\n";
            }
        }
    }
}
