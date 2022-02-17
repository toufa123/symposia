<?php

namespace DynamicContentForElementor\Extensions;

use Elementor\Controls_Manager;
use DynamicContentForElementor\Helper;
if (!\defined('ABSPATH')) {
    exit;
    // Exit if accessed directly
}
class Counter extends \ElementorPro\Modules\Forms\Fields\Field_Base
{
    private $is_common = \false;
    public $has_action = \false;
    public $depended_scripts = [];
    public $depended_styles = [];
    public function run_once()
    {
        // low priority because we want to update the counter after payments have been processed:
        add_action('elementor_pro/forms/process', [$this, 'update_counters'], 1000, 2);
    }
    public function get_script_depends()
    {
        return $this->depended_scripts;
    }
    public function get_style_depends()
    {
        return $this->depended_styles;
    }
    public function get_name()
    {
        return __('Counter', 'dynamic-content-for-elementor');
    }
    public function get_type()
    {
        return 'dce_counter';
    }
    public function update_controls($widget)
    {
        $elementor = \ElementorPro\Plugin::elementor();
        $control_data = $elementor->controls_manager->get_control_from_stack($widget->get_unique_name(), 'form_fields');
        if (is_wp_error($control_data)) {
            return;
        }
        $field_controls = ['html_notice' => ['name' => 'html_notice', 'type' => Controls_Manager::RAW_HTML, 'raw' => __('The Counter Field is still in beta. You are welcome to try it and help us by sharing your feedback.', 'dynamic-content-for-elementor'), 'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning', 'tab' => 'content', 'inner_tab' => 'form_fields_content_tab', 'tabs_wrapper' => 'form_fields_tabs', 'condition' => ['field_type' => $this->get_type()]], 'html_notice_value' => ['name' => 'html_notice_value', 'type' => Controls_Manager::RAW_HTML, 'raw' => \sprintf(esc_html__('Notice that the counter field is only increased after submit. The value shown in the page is not necessarily unique and %1$s does not represent %2$s the value the field will have while doing the Form Actions.', 'dynamic-content-for-elementor'), '<strong>', '</strong>'), 'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning', 'tab' => 'content', 'inner_tab' => 'form_fields_content_tab', 'tabs_wrapper' => 'form_fields_tabs', 'condition' => ['field_type' => $this->get_type()]], 'dce_counter_start' => ['name' => 'dce_counter_start', 'type' => Controls_Manager::NUMBER, 'label' => esc_html__('Start counter at', 'dynamic-content-for-elementor'), 'default' => 0, 'tab' => 'content', 'inner_tab' => 'form_fields_content_tab', 'tabs_wrapper' => 'form_fields_tabs', 'condition' => ['field_type' => $this->get_type()]], 'dce_counter_step' => ['name' => 'dce_counter_step', 'type' => Controls_Manager::TEXT, 'label' => __('Counter Step', 'dynamic-content-for-elementor'), 'default' => '1', 'tab' => 'content', 'description' => esc_html__('A token or shortcode for another field can also be used.', 'dynamic-content-for-elementor'), 'inner_tab' => 'form_fields_content_tab', 'tabs_wrapper' => 'form_fields_tabs', 'condition' => ['field_type' => $this->get_type()], 'dynamic' => ['active' => \true]], 'dce_counter_hide' => ['name' => 'dce_counter_hide', 'type' => Controls_Manager::SWITCHER, 'label' => esc_html__('Hide the counter', 'dynamic-content-for-elementor'), 'tab' => 'content', 'inner_tab' => 'form_fields_content_tab', 'tabs_wrapper' => 'form_fields_tabs', 'condition' => ['field_type' => $this->get_type()]]];
        $control_data['fields'] = $this->inject_field_controls($control_data['fields'], $field_controls);
        $widget->update_control('form_fields', $control_data);
    }
    private function get_meta_key($form_id, $field__id)
    {
        return "dce_counter_{$form_id}_{$field__id}";
    }
    public function render($item, $item_index, $form)
    {
        $post_id = \ElementorPro\Core\Utils::get_current_post_id();
        $meta = $this->get_meta_key($form->get_id(), $item['_id']);
        $value = get_post_meta($post_id, $meta, \true);
        $is_edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();
        if ($value === '') {
            if ($is_edit_mode) {
                $msg = esc_html__('Counter not initialized. You need to view the form at least once in the frontend page to set the counter to the initial value. After that the value can no longer be changed. But you have the option of deleting the counter and creating a new one with the same field name.', 'dynamic-content-for-elementor');
                Helper::notice('', $msg);
            } else {
                $value = $item['dce_counter_start'];
                if (!\is_numeric($value)) {
                    Helper::notice(__('Error: Counter Value is not numeric.', 'dynamic-content-for-elementor'));
                    return;
                }
                update_post_meta($post_id, $meta, $value);
            }
        }
        if ($item['dce_counter_hide'] === 'yes') {
            $form->add_render_attribute('input' . $item_index, 'type', 'hidden', \true);
        } else {
            $form->add_render_attribute('input' . $item_index, 'class', 'elementor-field-textual');
            $form->add_render_attribute('input' . $item_index, 'readonly', \true);
        }
        $form->add_render_attribute('input' . $item_index, 'value', $value, \true);
        echo '<input ' . $form->get_render_attribute_string('input' . $item_index) . '>';
    }
    public function validation($field, $record, $ajax_handler)
    {
        $post_id = $_POST['post_id'];
        $field__id = \DynamicContentForElementor\Helper::get_form_field_settings($field['id'], $record)['_id'];
        $form_id = $record->get_form_settings('id');
        $meta = $this->get_meta_key($form_id, $field__id);
        $value = get_post_meta($post_id, $meta, \true);
        if ($value === \false) {
            $ajax_handler->add_admin_error_message(\sprintf(esc_html__('Counter Field %$1s value not found', 'dynamic-content-for-elementor'), $field['id']));
        }
        $record->update_field($field['id'], 'value', $value);
        $record->update_field($field['id'], 'raw_value', $value);
    }
    public function update_counter($post_id, $meta_key, $step)
    {
        global $wpdb;
        $wpdb->query('start transaction');
        $curr = $wpdb->get_var($wpdb->prepare("select meta_value from {$wpdb->postmeta} where post_id = %d and meta_key = %s for update", $post_id, $meta_key));
        $wpdb->update($wpdb->postmeta, ['meta_value' => $curr + $step], ['post_id' => $post_id, 'meta_key' => $meta_key]);
        $wpdb->query('commit');
    }
    public function get_step($step, $record)
    {
        $match = \false;
        if (\preg_match('/\\[\\s*field\\s*id\\s*=\\s*"([^"]+)"\\s*\\]/', $step, $matches)) {
            $match = $matches[1];
        } elseif (\preg_match('/\\[form:([^\\]]+)\\]/', $step, $matches)) {
            $match = $matches[1];
        }
        if ($match) {
            return $record->get_field(['id' => $match])[$match]['value'];
        }
        return $step;
    }
    public function update_counters($record, $ajax_handler)
    {
        $post_id = $_POST['post_id'];
        $form_id = $record->get_form_settings('id');
        $fields = $record->get_form_settings('form_fields');
        foreach ($fields as $field) {
            if ($field['field_type'] !== $this->get_type()) {
                continue;
            }
            $meta_key = $this->get_meta_key($form_id, $field['_id']);
            $step = $this->get_step($field['dce_counter_step'], $record);
            if (!\is_numeric($step)) {
                $ajax_handler->add_admin_error_message(__('Counter Step is not numeric.', 'dynamic-content-for-elementor'));
                return;
            }
            $this->update_counter($post_id, $meta_key, $step);
        }
    }
}
