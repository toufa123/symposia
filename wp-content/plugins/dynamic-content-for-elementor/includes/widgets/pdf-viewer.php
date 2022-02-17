<?php

namespace DynamicContentForElementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Icons_Manager;
use DynamicContentForElementor\Helper;
if (!\defined('ABSPATH')) {
    exit;
    // Exit if accessed directly
}
class PdfViewer extends \DynamicContentForElementor\Widgets\WidgetPrototype
{
    public function get_script_depends()
    {
        return ['dce-pdf-viewer'];
    }
    public function get_style_depends()
    {
        return ['dce-pdf-js-viewer', 'dce-pdf-viewer'];
    }
    protected function _register_controls()
    {
        $this->start_controls_section('section_pdfviewer', ['label' => $this->get_title()]);
        $this->add_control('source', ['label' => __('Source', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SELECT, 'default' => 'media_file', 'options' => ['media_file' => __('Media File', 'dynamic-content-for-elementor'), 'url' => __('URL', 'dynamic-content-for-elementor')], 'frontend_available' => \true]);
        $this->add_control('source_url', ['label' => __('PDF URL', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::URL, 'description' => __('You can only enter URL from your own domain', 'dynamic-content-for-elementor'), 'show_external' => \false, 'condition' => ['source' => 'url'], 'frontend_available' => \true]);
        $this->add_control('source_media', ['label' => __('Upload PDF File', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::MEDIA, 'media_type' => 'application/pdf', 'frontend_available' => \true, 'condition' => ['source' => 'media_file']]);
        $this->add_control('navigation_controls', ['frontend_available' => \true, 'label' => __('Navigation Controls', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes']);
        $this->add_control('previous_text', ['label' => __('Text for "Previous" Button', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::TEXT, 'default' => __('Previous', 'dynamic-content-for-elementor'), 'condition' => ['navigation_controls!' => '']]);
        $this->add_control('next_text', ['label' => __('Text for "Next" Button', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::TEXT, 'default' => __('Next', 'dynamic-content-for-elementor'), 'condition' => ['navigation_controls!' => '']]);
        $this->add_control('zoom_controls', ['frontend_available' => \true, 'label' => __('Zoom Controls', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes']);
        $this->end_controls_section();
        $this->start_controls_section('section_global', ['label' => $this->get_title(), 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_responsive_control('pdf_align', ['label' => __('PDF Alignment', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::CHOOSE, 'options' => ['left' => ['title' => __('Left', 'dynamic-content-for-elementor'), 'icon' => 'eicon-text-align-left'], 'center' => ['title' => __('Center', 'dynamic-content-for-elementor'), 'icon' => 'eicon-text-align-center'], 'right' => ['title' => __('Right', 'dynamic-content-for-elementor'), 'icon' => 'eicon-text-align-right']], 'prefix_class' => 'elementor%s-align-', 'default' => '']);
        $this->add_responsive_control('controls_align', ['label' => __('Controls Alignment', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::CHOOSE, 'options' => ['flex-start' => ['title' => __('Left', 'dynamic-content-for-elementor'), 'icon' => 'eicon-text-align-left'], 'center' => ['title' => __('Center', 'dynamic-content-for-elementor'), 'icon' => 'eicon-text-align-center'], 'flex-end' => ['title' => __('Right', 'dynamic-content-for-elementor'), 'icon' => 'eicon-text-align-right']], 'selectors' => ['{{WRAPPER}} .dce-pdf-controls' => 'justify-content: {{VALUE}};'], 'default' => 'flex-start']);
        $this->add_responsive_control('pdf_space', ['label' => __('Space between controls and PDF', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SLIDER, 'default' => ['size' => 20], 'range' => ['px' => ['max' => 100, 'min' => 0, 'step' => 1]], 'selectors' => ['{{WRAPPER}} .dce-pdf-canvas-container' => 'padding-top: {{SIZE}}{{UNIT}};']]);
        $this->add_responsive_control('controls_space', ['label' => __('Space between controls', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SLIDER, 'default' => ['size' => 20], 'range' => ['px' => ['max' => 100, 'min' => 0, 'step' => 1]], 'selectors' => ['{{WRAPPER}} .dce-pdf-zoom-controls' => 'padding-left: {{SIZE}}{{UNIT}};'], 'condition' => ['navigation_controls!' => '']]);
        $this->add_group_control(Group_Control_Border::get_type(), ['name' => 'canvas_border', 'selector' => '{{WRAPPER}} canvas.dce-pdf-renderer', 'separator' => 'before']);
        $this->add_control('canvas_border_radius', ['label' => __('Border Radius', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => ['px', '%'], 'selectors' => ['{{WRAPPER}} canvas.dce-pdf-renderer' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']]);
        $this->end_controls_section();
        $this->start_controls_section('section_navigation_style', ['label' => __('Navigation Controls', 'dynamic-content-for-elementor'), 'tab' => Controls_Manager::TAB_STYLE, 'condition' => ['navigation_controls!' => '']]);
        $this->add_responsive_control('navigation_controls_space', ['label' => __('Space between Navigation Controls', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SLIDER, 'default' => ['size' => 5, 'unit' => 'px'], 'range' => ['px' => ['max' => 100, 'min' => 0, 'step' => 1]], 'selectors' => ['{{WRAPPER}} .dce-pdf-go-previous' => 'margin-right: {{SIZE}}{{UNIT}};', '{{WRAPPER}} .dce-pdf-go-next' => 'margin-left: {{SIZE}}{{UNIT}};', '{{WRAPPER}} .dce-pdf-current-page' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};']]);
        $this->start_controls_tabs('tabs_navigation_controls_style');
        $this->start_controls_tab('tab_navigation_controls_normal', ['label' => __('Normal', 'dynamic-content-for-elementor')]);
        $this->add_group_control(Group_Control_Typography::get_type(), ['name' => 'navigation_typography', 'selector' => '{{WRAPPER}} .dce-pdf-navigation-controls button, {{WRAPPER}} .dce-pdf-navigation-controls input']);
        $this->add_group_control(Group_Control_Text_Shadow::get_type(), ['name' => 'navigation_text_shadow', 'selector' => '{{WRAPPER}} .dce-pdf-navigation-controls button, {{WRAPPER}} .dce-pdf-navigation-controls input']);
        $this->add_control('navigation_controls_text_color', ['label' => __('Text Color', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::COLOR, 'default' => '', 'selectors' => ['{{WRAPPER}} .dce-pdf-navigation-controls button, {{WRAPPER}} .dce-pdf-navigation-controls input' => 'color: {{VALUE}};']]);
        $this->add_control('navigation_controls_background_color', ['label' => __('Background Color', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .dce-pdf-navigation-controls button, {{WRAPPER}} .dce-pdf-navigation-controls input' => 'background-color: {{VALUE}};']]);
        $this->add_responsive_control('current_page_width', ['label' => __('Width of the Current Page Indicator', 'dynamic-content-for-elementor'), 'type' => \Elementor\Controls_Manager::SLIDER, 'size_units' => ['px', 'em', '%'], 'default' => ['unit' => 'em', 'size' => 5], 'range' => ['px' => ['min' => 1, 'max' => 800, 'step' => 5], 'em' => ['min' => 1, 'max' => 10, 'step' => 0.1], '%' => ['min' => 1, 'max' => 100, 'step' => 1]], 'selectors' => ['{{WRAPPER}} .dce-pdf-current-page' => 'width: {{SIZE}}{{UNIT}};']]);
        $this->end_controls_tab();
        $this->start_controls_tab('tab_navigation_controls_hover', ['label' => __('Hover', 'dynamic-content-for-elementor')]);
        $this->add_control('navigation_controls_hover_color', ['label' => __('Text Color', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .dce-pdf-navigation-controls button:hover, {{WRAPPER}} .dce-pdf-navigation-controls input:hover, {{WRAPPER}} .dce-pdf-navigation-controls button:focus, {{WRAPPER}} .dce-pdf-navigation-controls input:focus' => 'color: {{VALUE}};']]);
        $this->add_control('navigation_controls_background_hover_color', ['label' => __('Background Color', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .dce-pdf-navigation-controls button:hover, {{WRAPPER}} .dce-pdf-navigation-controls input:hover, {{WRAPPER}} .dce-pdf-navigation-controls button:focus, {{WRAPPER}} .dce-pdf-navigation-controls input:focus' => 'background-color: {{VALUE}};']]);
        $this->add_control('navigation_controls_hover_border_color', ['label' => __('Border Color', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::COLOR, 'condition' => ['border_border!' => ''], 'selectors' => ['{{WRAPPER}} .dce-pdf-navigation-controls button:hover, {{WRAPPER}} .dce-pdf-navigation-controls input:hover, {{WRAPPER}} .dce-pdf-navigation-controls button:focus, {{WRAPPER}} .dce-pdf-navigation-controls input:focus' => 'border-color: {{VALUE}};']]);
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_group_control(Group_Control_Border::get_type(), ['name' => 'navigation_controls_border', 'selector' => '{{WRAPPER}} .dce-pdf-navigation-controls button, {{WRAPPER}} .dce-pdf-navigation-controls input', 'separator' => 'before']);
        $this->add_control('navigation_controls_border_radius', ['label' => __('Border Radius', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => ['px', '%'], 'selectors' => ['{{WRAPPER}} .dce-pdf-navigation-controls button, {{WRAPPER}} .dce-pdf-navigation-controls input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']]);
        $this->add_group_control(Group_Control_Box_Shadow::get_type(), ['name' => 'navigation_controls_box_shadow', 'selector' => '{{WRAPPER}} .dce-pdf-navigation-controls button:hover']);
        $this->add_responsive_control('navigation_controls_text_padding', ['label' => __('Padding', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => ['px', 'em', '%'], 'selectors' => ['{{WRAPPER}} .dce-pdf-navigation-controls button, {{WRAPPER}} .dce-pdf-navigation-controls input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'], 'separator' => 'before']);
        $this->end_controls_section();
        $this->start_controls_section('section_zoom_style', ['label' => __('Zoom Controls', 'dynamic-content-for-elementor'), 'tab' => Controls_Manager::TAB_STYLE, 'condition' => ['zoom_controls!' => '']]);
        $this->add_responsive_control('zoom_controls_space', ['label' => __('Space between Zoom Controls', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SLIDER, 'default' => ['size' => 5, 'unit' => 'px'], 'range' => ['px' => ['max' => 100, 'min' => 0, 'step' => 1]], 'selectors' => ['{{WRAPPER}} .dce-pdf-zoom-in' => 'margin-right: {{SIZE}}{{UNIT}};', '{{WRAPPER}} .dce-pdf-zoom-out' => 'margin-left: {{SIZE}}{{UNIT}};']]);
        $this->start_controls_tabs('tabs_zoom_controls_style');
        $this->start_controls_tab('tab_zoom_controls_normal', ['label' => __('Normal', 'dynamic-content-for-elementor')]);
        $this->add_group_control(Group_Control_Typography::get_type(), ['name' => 'zoom_typography', 'selector' => '{{WRAPPER}} .dce-pdf-zoom-controls button']);
        $this->add_group_control(Group_Control_Text_Shadow::get_type(), ['name' => 'zoom_text_shadow', 'selector' => '{{WRAPPER}} .dce-pdf-zoom-controls button']);
        $this->add_control('zoom_controls_text_color', ['label' => __('Text Color', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::COLOR, 'default' => '', 'selectors' => ['{{WRAPPER}} .dce-pdf-zoom-controls button' => 'color: {{VALUE}};']]);
        $this->add_control('zoom_controls_background_color', ['label' => __('Background Color', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .dce-pdf-zoom-controls button' => 'background-color: {{VALUE}};']]);
        $this->end_controls_tab();
        $this->start_controls_tab('tab_zoom_controls_hover', ['label' => __('Hover', 'dynamic-content-for-elementor')]);
        $this->add_control('zoom_controls_hover_color', ['label' => __('Text Color', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .dce-pdf-zoom-controls button:hover, {{WRAPPER}} .dce-pdf-zoom-controls button:focus' => 'color: {{VALUE}};']]);
        $this->add_control('zoom_controls_background_hover_color', ['label' => __('Background Color', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .dce-pdf-zoom-controls button:hover, {{WRAPPER}} .dce-pdf-zoom-controls button:focus' => 'background-color: {{VALUE}};']]);
        $this->add_control('zoom_controls_hover_border_color', ['label' => __('Border Color', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::COLOR, 'condition' => ['border_border!' => ''], 'selectors' => ['{{WRAPPER}} .dce-pdf-zoom-controls button:hover, {{WRAPPER}} .dce-pdf-zoom-controls button:focus' => 'border-color: {{VALUE}};']]);
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_group_control(Group_Control_Border::get_type(), ['name' => 'zoom_controls_border', 'selector' => '{{WRAPPER}} .dce-pdf-zoom-controls button', 'separator' => 'before']);
        $this->add_control('zoom_controls_border_radius', ['label' => __('Border Radius', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => ['px', '%'], 'selectors' => ['{{WRAPPER}} .dce-pdf-zoom-controls button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']]);
        $this->add_group_control(Group_Control_Box_Shadow::get_type(), ['name' => 'zoom_controls_box_shadow', 'selector' => '{{WRAPPER}} .dce-pdf-zoom-controls button:hover']);
        $this->add_responsive_control('zoom_controls_text_padding', ['label' => __('Padding', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => ['px', 'em', '%'], 'selectors' => ['{{WRAPPER}} .dce-pdf-zoom-controls button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'], 'separator' => 'before']);
        $this->end_controls_section();
    }
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        if (empty($settings)) {
            return;
        }
        if ('url' === $settings['source'] && !$settings['source_url']['url'] || 'media_file' === $settings['source'] && !$settings['source_media']['url']) {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                Helper::notice('', __('Select a PDF', 'dynamic-content-for-elementor'));
            }
            return;
        }
        ?>

		<?php 
        if ($settings['navigation_controls'] || $settings['zoom_controls']) {
            ?>
		<div class="dce-pdf-controls">
			<?php 
            if ($settings['navigation_controls']) {
                ?>
			<div class="dce-pdf-navigation-controls">
				<button class="dce-pdf-go-previous"><?php 
                echo sanitize_text_field($settings['previous_text']);
                ?></button>
				<input class="dce-pdf-current-page" value="1" type="number" />
				<button class="dce-pdf-go-next"><?php 
                echo sanitize_text_field($settings['next_text']);
                ?></button>
			</div>
			<?php 
            }
            ?>

			<?php 
            if ($settings['zoom_controls']) {
                ?>
			<div class="dce-pdf-zoom-controls">
				<button class="dce-pdf-zoom-in">+</button>
				<button class="dce-pdf-zoom-out">-</button>
			</div>
			<?php 
            }
            ?>
		</div>
		<?php 
        }
        ?>

		<div class="dce-pdf-canvas-container">
			<canvas class="dce-pdf-renderer"></canvas>
		</div>
<?php 
    }
}
