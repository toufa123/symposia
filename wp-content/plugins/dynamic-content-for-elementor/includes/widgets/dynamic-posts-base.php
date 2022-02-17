<?php

namespace DynamicContentForElementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use DynamicContentForElementor\Helper;
use DynamicContentForElementor\Controls\DCE_Group_Control_Transform_Element;
use DynamicContentForElementor\Controls\DCE_Group_Control_Filters_CSS;
if (!\defined('ABSPATH')) {
    exit;
    // Exit if accessed directly
}
class DynamicPostsBase extends \DynamicContentForElementor\Widgets\WidgetPrototype
{
    protected $query = null;
    protected $query_args = null;
    protected $_has_template_content = \false;
    public function get_name()
    {
        return 'dce-dynamicposts-base';
    }
    protected $depended_scripts = ['dce-dynamicPosts-base'];
    protected $depended_styles = ['dce-dynamic-posts'];
    public function add_script_depends($handler)
    {
        if (!empty($handler) && \is_array($handler)) {
            $this->depended_scripts[] = \array_merge($this->depended_scripts, $handler);
        } elseif (!empty($handler) && \is_string($handler)) {
            $this->depended_scripts[] = $handler;
        }
    }
    public function add_style_depends($handler)
    {
        if (!empty($handler) && \is_array($handler)) {
            $this->depended_styles[] = \array_merge($this->depended_styles, $handler);
        } elseif (!empty($handler) && \is_string($handler)) {
            $this->depended_styles[] = $handler;
        }
    }
    public function get_script_depends()
    {
        if (\Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode()) {
            $all_scripts = [];
            foreach ($this->get_skins() as $skin => $value) {
                $all_scripts = \array_merge($all_scripts, $this->get_skin($skin)->get_script_depends());
            }
            return \array_merge($this->depended_scripts, $all_scripts);
        }
        return \array_merge($this->depended_scripts, $this->get_current_skin()->get_script_depends());
    }
    public function get_style_depends()
    {
        if (\Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode()) {
            $all_styles = [];
            foreach ($this->get_skins() as $skin => $value) {
                $all_styles = \array_merge($all_styles, $this->get_skin($skin)->get_style_depends());
            }
            return \array_merge($this->depended_styles, $all_styles);
        }
        return \array_merge($this->depended_styles, $this->get_current_skin()->get_style_depends());
    }
    protected function _enqueue_scripts()
    {
        $scripts = $this->get_script_depends();
        if (!empty($scripts)) {
            foreach ($scripts as $script) {
                wp_enqueue_script($script);
            }
        }
    }
    protected function _enqueue_styles()
    {
        $styles = $this->get_style_depends();
        if (!empty($styles)) {
            foreach ($styles as $style) {
                wp_enqueue_style($style);
            }
        }
    }
    protected function _register_controls()
    {
        $this->register_base_controls();
        $this->register_pagination_controls();
        $this->register_infinitescroll_controls();
        $this->register_query_controls();
    }
    protected function register_base_controls()
    {
        $taxonomies = Helper::get_taxonomies();
        $types = Helper::get_post_types();
        $this->start_controls_section('section_dynamicposts', ['label' => $this->get_title(), 'tab' => Controls_Manager::TAB_CONTENT]);
        // skin: Template
        $this->add_control('skin_dis_customtemplate', ['type' => Controls_Manager::RAW_HTML, 'show_label' => \false, 'raw' => '<img src="' . DCE_URL . 'assets/img/skins/template.png" />', 'content_classes' => 'dce-skin-dis dce-ect-dis', 'condition' => ['_skin' => ['', 'grid', 'carousel', 'grid-filters', 'dualcarousel'], 'style_items' => 'template']]);
        // skin: Carousel
        $this->add_control('skin_dis_default', ['type' => Controls_Manager::RAW_HTML, 'show_label' => \false, 'raw' => '<img src="' . DCE_URL . 'assets/img/skins/default.png" />', 'content_classes' => 'dce-skin-dis', 'condition' => ['_skin' => 'row']]);
        // skin: Grid
        $this->add_control('skin_dis_grid', ['type' => Controls_Manager::RAW_HTML, 'show_label' => \false, 'raw' => '<img src="' . DCE_URL . 'assets/img/skins/grid.png" />', 'content_classes' => 'dce-skin-dis', 'condition' => ['_skin' => 'grid']]);
        // skin: Carousel
        $this->add_control('skin_dis_carousel', ['type' => Controls_Manager::RAW_HTML, 'show_label' => \false, 'raw' => '<img src="' . DCE_URL . 'assets/img/skins/carousel.png" />', 'content_classes' => 'dce-skin-dis', 'condition' => ['_skin' => 'carousel']]);
        // skin: Filters
        $this->add_control('skin_dis_filters', ['type' => Controls_Manager::RAW_HTML, 'show_label' => \false, 'raw' => '<img src="' . DCE_URL . 'assets/img/skins/filters.png" />', 'content_classes' => 'dce-skin-dis', 'condition' => ['_skin' => 'grid-filters']]);
        // skin: Dual Carousel
        $this->add_control('skin_dis_dualcarousel', ['type' => Controls_Manager::RAW_HTML, 'show_label' => \false, 'raw' => '<img src="' . DCE_URL . 'assets/img/skins/dualcarousel.png" />', 'content_classes' => 'dce-skin-dis', 'condition' => ['_skin' => 'dualcarousel']]);
        // skin: Timeline
        $this->add_control('skin_dis_timeline', ['type' => Controls_Manager::RAW_HTML, 'show_label' => \false, 'raw' => '<img src="' . DCE_URL . 'assets/img/skins/timeline.png" />', 'content_classes' => 'dce-skin-dis', 'condition' => ['_skin' => 'timeline']]);
        // skin: smoothscroll
        $this->add_control('skin_dis_smoothscroll', ['type' => Controls_Manager::RAW_HTML, 'show_label' => \false, 'raw' => '<img src="' . DCE_URL . 'assets/img/skins/smoothscroll.png" />', 'content_classes' => 'dce-skin-dis', 'condition' => ['_skin' => 'smoothscroll']]);
        // skin: gridtofullscreen3d
        $this->add_control('skin_dis_gridtofullscreen3d', ['type' => Controls_Manager::RAW_HTML, 'show_label' => \false, 'raw' => '<img src="' . DCE_URL . 'assets/img/skins/gridtofullscreen3d.png" />', 'content_classes' => 'dce-skin-dis', 'condition' => ['_skin' => 'gridtofullscreen3d']]);
        // skin: crossroadsslideshow
        $this->add_control('skin_dis_crossroadsslideshow', ['type' => Controls_Manager::RAW_HTML, 'show_label' => \false, 'raw' => '<img src="' . DCE_URL . 'assets/img/skins/crossroadsslideshow.png" />', 'content_classes' => 'dce-skin-dis', 'condition' => ['_skin' => 'crossroadsslideshow']]);
        // skin: nextpost
        $this->add_control('skin_dis_nextpost', ['type' => Controls_Manager::RAW_HTML, 'show_label' => \false, 'raw' => '<img src="' . DCE_URL . 'assets/img/skins/nextpost.png" />', 'content_classes' => 'dce-skin-dis', 'condition' => ['_skin' => 'nextpost']]);
        // skin: 3d
        $this->add_control('skin_dis_3d', ['type' => Controls_Manager::RAW_HTML, 'show_label' => \false, 'raw' => '<img src="' . DCE_URL . 'assets/img/skins/3d.png" />', 'content_classes' => 'dce-skin-dis', 'condition' => ['_skin' => '3d']]);
        // skin: pagination classic
        $this->add_control('skin_dis_pagination', ['type' => Controls_Manager::RAW_HTML, 'show_label' => \false, 'raw' => '<img src="' . DCE_URL . 'assets/img/skins/pagination.png" />', 'content_classes' => 'dce-skin-dis dce-pagination-dis', 'conditions' => ['relation' => 'or', 'terms' => [['terms' => [['name' => '_skin', 'operator' => 'in', 'value' => ['', 'grid', 'grid-filters', 'gridtofullscreen3d']], ['name' => 'post_offset', 'operator' => 'in', 'value' => [0, '']], ['name' => 'pagination_enable', 'operator' => '==', 'value' => 'yes'], ['name' => 'infiniteScroll_enable', 'operator' => '==', 'value' => '']]], ['terms' => [['name' => '_skin', 'operator' => 'in', 'value' => ['', 'grid', 'grid-filters', 'gridtofullscreen3d']], ['name' => 'query_type', 'operator' => '!in', 'value' => ['get_cpt', 'dynamic_mode', 'sticky_posts', 'favorites']], ['name' => 'pagination_enable', 'operator' => '==', 'value' => 'yes'], ['name' => 'infiniteScroll_enable', 'operator' => '==', 'value' => '']]]]]]);
        // skin: infinitescroll
        $this->add_control('skin_dis_infinitescroll', ['type' => Controls_Manager::RAW_HTML, 'show_label' => \false, 'raw' => '<img src="' . DCE_URL . 'assets/img/skins/infinitescroll.png" />', 'content_classes' => 'dce-skin-dis dce-pagination-dis', 'conditions' => ['relation' => 'or', 'terms' => [['terms' => [['name' => '_skin', 'operator' => 'in', 'value' => ['', 'grid', 'grid-filters', 'gridtofullscreen3d']], ['name' => 'post_offset', 'operator' => 'in', 'value' => [0, '']], ['name' => 'pagination_enable', 'operator' => '==', 'value' => 'yes'], ['name' => 'infiniteScroll_enable', 'operator' => '==', 'value' => 'yes']]], ['terms' => [['name' => '_skin', 'operator' => 'in', 'value' => ['', 'grid', 'grid-filters', 'gridtofullscreen3d']], ['name' => 'query_type', 'operator' => '!in', 'value' => ['get_cpt', 'dynamic_mode', 'sticky_posts', 'favorites']], ['name' => 'pagination_enable', 'operator' => '==', 'value' => 'yes'], ['name' => 'infiniteScroll_enable', 'operator' => '==', 'value' => 'yes']]]]]]);
        // +********************* Pagination
        $this->add_control('pagination_enable', ['label' => __('Pagination', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SWITCHER, 'separator' => 'before', 'conditions' => ['relation' => 'or', 'terms' => [['terms' => [['name' => '_skin', 'operator' => 'in', 'value' => ['', 'grid', 'grid-filters', 'gridtofullscreen3d']], ['name' => 'post_offset', 'operator' => 'in', 'value' => [0, '']]]], ['terms' => [['name' => '_skin', 'operator' => 'in', 'value' => ['', 'grid', 'grid-filters', 'gridtofullscreen3d']], ['name' => 'query_type', 'operator' => '!in', 'value' => ['get_cpt', 'dynamic_mode', 'sticky_posts', 'favorites']]]]]]]);
        $this->add_control('infiniteScroll_enable', ['label' => __('Infinite Scroll', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SWITCHER, 'separator' => 'before', 'frontend_available' => \true, 'conditions' => ['relation' => 'or', 'terms' => [['terms' => [['name' => '_skin', 'operator' => 'in', 'value' => ['', 'grid', 'grid-filters', 'gridtofullscreen3d']], ['name' => 'post_offset', 'operator' => 'in', 'value' => [0, '']], ['name' => 'pagination_enable', 'operator' => '!=', 'value' => '']]], ['terms' => [['name' => '_skin', 'operator' => 'in', 'value' => ['', 'grid', 'grid-filters', 'gridtofullscreen3d']], ['name' => 'query_type', 'operator' => '!in', 'value' => ['get_cpt', 'dynamic_mode', 'sticky_posts', 'favorites']], ['name' => 'pagination_enable', 'operator' => '!=', 'value' => '']]]]]]);
        $this->add_control('style_items', ['label' => __('Items Style', 'dynamic-content-for-elementor'), 'type' => 'images_selector', 'type_selector' => 'image', 'columns_grid' => 5, 'separator' => 'before', 'options' => ['default' => ['title' => __('Default', 'dynamic-content-for-elementor'), 'return_val' => 'val', 'image' => DCE_URL . 'assets/img/layout/top.png'], 'left' => ['title' => __('Left', 'dynamic-content-for-elementor'), 'return_val' => 'val', 'image' => DCE_URL . 'assets/img/layout/left.png'], 'right' => ['title' => __('Right', 'dynamic-content-for-elementor'), 'return_val' => 'val', 'image' => DCE_URL . 'assets/img/layout/right.png'], 'alternate' => ['title' => __('Alternate', 'dynamic-content-for-elementor'), 'return_val' => 'val', 'image' => DCE_URL . 'assets/img/layout/alternate.png'], 'textzone' => ['title' => __('Text Zone', 'dynamic-content-for-elementor'), 'return_val' => 'val', 'image' => DCE_URL . 'assets/img/layout/textzone.png'], 'overlay' => ['title' => __('Overlay', 'dynamic-content-for-elementor'), 'return_val' => 'val', 'image' => DCE_URL . 'assets/img/layout/overlay.png'], 'float' => ['title' => __('Float', 'dynamic-content-for-elementor'), 'return_val' => 'val', 'image' => DCE_URL . 'assets/img/layout/float.png'], 'html_tokens' => ['title' => __('HTML & Tokens', 'dynamic-content-for-elementor'), 'return_val' => 'val', 'image' => DCE_URL . 'assets/img/layout/html_tokens.png'], 'template' => ['title' => __('Elementor Template', 'dynamic-content-for-elementor'), 'return_val' => 'val', 'image' => DCE_URL . 'assets/img/layout/template.png']], 'toggle' => \false, 'render_type' => 'template', 'prefix_class' => 'dce-posts-layout-', 'default' => 'default', 'frontend_available' => \true, 'condition' => ['_skin' => ['', 'grid', 'grid-filters', 'carousel', 'filters', 'dualcarousel', 'smoothscroll', '3d']]]);
        // +********************* Style: Left, Right, Alternate
        $this->add_responsive_control('image_rate', ['label' => __('Distribution (%)', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SLIDER, 'default' => ['size' => '', 'unit' => '%'], 'size_units' => ['%'], 'range' => ['%' => ['min' => 1, 'max' => 100]], 'selectors' => ['{{WRAPPER}} .dce-image-area' => 'width: {{SIZE}}%;', '{{WRAPPER}} .dce-content-area' => 'width: calc( 100% - {{SIZE}}% );'], 'condition' => ['_skin' => ['', 'grid', 'carousel', 'grid-filters', 'dualcarousel', 'smoothscroll', '3d'], 'style_items' => ['left', 'right', 'alternate']]]);
        // +********************* Float Hover style descripton:
        $this->add_control('float_hoverstyle_description', ['type' => Controls_Manager::RAW_HTML, 'show_label' => \false, 'raw' => __('The Float style allows you to create animations between the content and the underlying image, from the Hover effect panel you can set the features.', 'dynamic-content-for-elementor'), 'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning', 'condition' => ['_skin' => ['', 'grid', 'carousel', 'grid-filters', 'dualcarousel', 'smoothscroll', '3d'], 'style_items' => ['float']]]);
        if (!\DynamicContentForElementor\Helper::can_register_unsafe_controls()) {
            $this->add_control('html_tokens_notice', ['type' => Controls_Manager::RAW_HTML, 'raw' => __('You will need administrator capabilities to edit these settings.', 'dynamic-content-for-elementor'), 'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning']);
        } else {
            $this->add_control('html_tokens_editor', ['label' => __('HTML & Tokens', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::CODE, 'default' => '<a href="[post:permalink]">[post:thumb]</a><h4><a href="[post:permalink]">[post:title]</a></h4><p>[post:excerpt]</p><a class="btn btn-primary" href="[post:permalink]">' . __('Read more', 'dynamic-content-for-elementor') . '</a>', 'description' => __('Type here your content, you can use HTML and Tokens.', 'dynamic-content-for-elementor'), 'condition' => ['_skin' => ['', 'grid', 'carousel', 'grid-filters', 'dualcarousel', 'smoothscroll', '3d'], 'style_items' => 'html_tokens']]);
        }
        // +********************* Image Zone Style:
        $this->add_control('heading_imagezone', ['label' => __('Image', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::HEADING, 'separator' => 'before', 'condition' => ['_skin' => ['', 'grid', 'carousel', 'grid-filters', 'dualcarousel', 'smoothscroll', '3d'], 'style_items!' => ['default', 'template', 'html_tokens']]]);
        // +********************* Image Zone: Mask
        $this->add_control('imagemask_popover', ['label' => __('Mask', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::POPOVER_TOGGLE, 'label_off' => __('Default', 'dynamic-content-for-elementor'), 'label_on' => __('Custom', 'dynamic-content-for-elementor'), 'return_value' => 'yes', 'condition' => ['_skin' => ['', 'grid', 'carousel', 'grid-filters', 'dualcarousel', 'smoothscroll', '3d'], 'style_items!' => ['default', 'template', 'html_tokens']]]);
        $this->start_popover();
        $this->add_control('mask_heading', ['label' => __('Mask', 'dynamic-content-for-elementor'), 'description' => __('Shape parameters', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::HEADING, 'separator' => 'before', 'condition' => ['_skin' => ['', 'grid', 'carousel', 'grid-filters', 'dualcarousel', 'smoothscroll', '3d'], 'style_items!' => ['default', 'template', 'html_tokens'], 'imagemask_popover' => 'yes']]);
        $this->add_control('mask_shape_type', ['label' => __('Type', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SELECT, 'options' => ['image' => __('PNG Image', 'dynamic-content-for-elementor'), 'clippath' => __('Clip Path', 'dynamic-content-for-elementor')], 'default' => 'image', 'render_type' => 'template', 'condition' => ['_skin' => ['', 'grid', 'carousel', 'grid-filters', 'dualcarousel', 'smoothscroll', '3d'], 'style_items!' => ['default', 'template', 'html_tokens'], 'imagemask_popover' => 'yes']]);
        $this->add_control('images_mask', ['label' => __('Select PNG mask', 'dynamic-content-for-elementor'), 'type' => 'images_selector', 'toggle' => \false, 'type_selector' => 'image', 'columns_grid' => 4, 'default' => DCE_URL . 'assets/img/mask/flower.png', 'options' => ['mask1' => ['title' => __('Flower', 'dynamic-content-for-elementor'), 'image' => DCE_URL . 'assets/img/mask/flower.png', 'image_preview' => DCE_URL . 'assets/img/mask/low/flower.jpg'], 'mask2' => ['title' => __('Blob', 'dynamic-content-for-elementor'), 'image' => DCE_URL . 'assets/img/mask/blob.png', 'image_preview' => DCE_URL . 'assets/img/mask/low/blob.jpg'], 'mask3' => ['title' => __('Diagonals', 'dynamic-content-for-elementor'), 'image' => DCE_URL . 'assets/img/mask/diagonal.png', 'image_preview' => DCE_URL . 'assets/img/mask/low/diagonal.jpg'], 'mask4' => ['title' => __('Rhombus', 'dynamic-content-for-elementor'), 'image' => DCE_URL . 'assets/img/mask/rombs.png', 'image_preview' => DCE_URL . 'assets/img/mask/low/rombs.jpg'], 'mask5' => ['title' => __('Waves', 'dynamic-content-for-elementor'), 'image' => DCE_URL . 'assets/img/mask/waves.png', 'image_preview' => DCE_URL . 'assets/img/mask/low/waves.jpg'], 'mask6' => ['title' => __('Drawing', 'dynamic-content-for-elementor'), 'image' => DCE_URL . 'assets/img/mask/draw.png', 'image_preview' => DCE_URL . 'assets/img/mask/low/draw.jpg'], 'mask7' => ['title' => __('Sketch', 'dynamic-content-for-elementor'), 'image' => DCE_URL . 'assets/img/mask/sketch.png', 'image_preview' => DCE_URL . 'assets/img/mask/low/sketch.jpg'], 'custom_mask' => ['title' => __('Custom Mask', 'dynamic-content-for-elementor'), 'return_val' => 'val', 'image' => DCE_URL . 'assets/displacement/custom.jpg', 'image_preview' => DCE_URL . 'assets/displacement/custom.jpg']], 'condition' => ['_skin' => ['', 'grid', 'carousel', 'grid-filters', 'dualcarousel', 'smoothscroll', '3d'], 'style_items!' => ['default', 'template', 'html_tokens'], 'imagemask_popover' => 'yes', 'mask_shape_type' => 'image'], 'selectors' => ['{{WRAPPER}} .dce-posts-container .dce-post-image img' => '-webkit-mask-image: url({{VALUE}}); mask-image: url({{VALUE}}); -webkit-mask-position: 50% 50%; mask-position: 50% 50%; -webkit-mask-repeat: no-repeat; mask-repeat: no-repeat; -webkit-mask-size: contain; mask-size: contain;']]);
        $this->add_control('custom_image_mask', ['label' => __('Select a PNG file', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::MEDIA, 'dynamic' => ['active' => \true], 'default' => ['url' => \Elementor\Utils::get_placeholder_image_src()], 'selectors' => ['{{WRAPPER}} .dce-posts-container .dce-post-image img' => '-webkit-mask-image: url({{URL}}); mask-image: url({{URL}}); -webkit-mask-position: 50% 50%; mask-position: 50% 50%; -webkit-mask-repeat: no-repeat; mask-repeat: no-repeat; -webkit-mask-size: contain; mask-size: contain;'], 'condition' => ['_skin' => ['', 'grid', 'carousel', 'grid-filters', 'dualcarousel', 'smoothscroll', '3d'], 'style_items!' => ['default', 'template', 'html_tokens'], 'imagemask_popover' => 'yes', 'images_mask' => 'custom_mask', 'mask_shape_type' => 'image']]);
        $this->add_control('clippath_mask', ['label' => __('Predefined Clip-Path', 'dynamic-content-for-elementor'), 'type' => 'images_selector', 'toggle' => \false, 'type_selector' => 'image', 'columns_grid' => 5, 'options' => ['polygon(50% 0%, 0% 100%, 100% 100%)' => ['title' => 'Triangle', 'return_val' => 'val', 'image_preview' => DCE_URL . 'assets/img/shapes/triangle.png'], 'polygon(20% 0%, 80% 0%, 100% 100%, 0% 100%)' => ['title' => 'Trapezoid', 'return_val' => 'val', 'image_preview' => DCE_URL . 'assets/img/shapes/trapezoid.png'], 'polygon(25% 0%, 100% 0%, 75% 100%, 0% 100%)' => ['title' => 'Parallelogram', 'return_val' => 'val', 'image_preview' => DCE_URL . 'assets/img/shapes/parallelogram.png'], 'polygon(50% 0%, 100% 50%, 50% 100%, 0% 50%)' => ['title' => 'Rombus', 'return_val' => 'val', 'image_preview' => DCE_URL . 'assets/img/shapes/rombus.png'], 'polygon(50% 0%, 100% 38%, 82% 100%, 18% 100%, 0% 38%)' => ['title' => 'Pentagon', 'return_val' => 'val', 'image_preview' => DCE_URL . 'assets/img/shapes/pentagon.png'], 'polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%)' => ['title' => 'Hexagon', 'return_val' => 'val', 'image_preview' => DCE_URL . 'assets/img/shapes/hexagon.png'], 'polygon(50% 0%, 90% 20%, 100% 60%, 75% 100%, 25% 100%, 0% 60%, 10% 20%)' => ['title' => 'Heptagon', 'return_val' => 'val', 'image_preview' => DCE_URL . 'assets/img/shapes/heptagon.png'], 'polygon(30% 0%, 70% 0%, 100% 30%, 100% 70%, 70% 100%, 30% 100%, 0% 70%, 0% 30%)' => ['title' => 'Octagon', 'return_val' => 'val', 'image_preview' => DCE_URL . 'assets/img/shapes/octagon.png'], 'polygon(50% 0%, 83% 12%, 100% 43%, 94% 78%, 68% 100%, 32% 100%, 6% 78%, 0% 43%, 17% 12%)' => ['title' => 'Nonagon', 'return_val' => 'val', 'image_preview' => DCE_URL . 'assets/img/shapes/nonagon.png'], 'polygon(50% 0%, 80% 10%, 100% 35%, 100% 70%, 80% 90%, 50% 100%, 20% 90%, 0% 70%, 0% 35%, 20% 10%)' => ['title' => 'Decagon', 'return_val' => 'val', 'image_preview' => DCE_URL . 'assets/img/shapes/decagon.png'], 'polygon(20% 0%, 80% 0%, 100% 20%, 100% 80%, 80% 100%, 20% 100%, 0% 80%, 0% 20%)' => ['title' => 'Bevel', 'return_val' => 'val', 'image_preview' => DCE_URL . 'assets/img/shapes/bevel.png'], 'polygon(0% 15%, 15% 15%, 15% 0%, 85% 0%, 85% 15%, 100% 15%, 100% 85%, 85% 85%, 85% 100%, 15% 100%, 15% 85%, 0% 85%)' => ['title' => 'Rabbet', 'return_val' => 'val', 'image_preview' => DCE_URL . 'assets/img/shapes/rabbet.png'], 'polygon(40% 0%, 40% 20%, 100% 20%, 100% 80%, 40% 80%, 40% 100%, 0% 50%)' => ['title' => 'Left arrow', 'return_val' => 'val', 'image_preview' => DCE_URL . 'assets/img/shapes/leftarrow.png'], 'polygon(0% 20%, 60% 20%, 60% 0%, 100% 50%, 60% 100%, 60% 80%, 0% 80%)' => ['title' => 'Right arrow', 'return_val' => 'val', 'image_preview' => DCE_URL . 'assets/img/shapes/rightarrow.png'], 'polygon(25% 0%, 100% 1%, 100% 100%, 25% 100%, 0% 50%)' => ['title' => 'Left point', 'return_val' => 'val', 'image_preview' => DCE_URL . 'assets/img/shapes/leftpoint.png'], 'polygon(0% 0%, 75% 0%, 100% 50%, 75% 100%, 0% 100%)' => ['title' => 'Right point', 'return_val' => 'val', 'image_preview' => DCE_URL . 'assets/img/shapes/rightpoint.png'], 'polygon(100% 0%, 75% 50%, 100% 100%, 25% 100%, 0% 50%, 25% 0%)' => ['title' => 'Left chevron', 'return_val' => 'val', 'image_preview' => DCE_URL . 'assets/img/shapes/leftchevron.png'], 'polygon(75% 0%, 100% 50%, 75% 100%, 0% 100%, 25% 50%, 0% 0%)' => ['title' => 'Right Chevron', 'return_val' => 'val', 'image_preview' => DCE_URL . 'assets/img/shapes/rightchevron.png'], 'polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%)' => ['title' => 'Star', 'return_val' => 'val', 'image_preview' => DCE_URL . 'assets/img/shapes/star.png'], 'polygon(10% 25%, 35% 25%, 35% 0%, 65% 0%, 65% 25%, 90% 25%, 90% 50%, 65% 50%, 65% 100%, 35% 100%, 35% 50%, 10% 50%)' => ['title' => 'Cross', 'return_val' => 'val', 'image_preview' => DCE_URL . 'assets/img/shapes/cross.png'], 'polygon(0% 0%, 100% 0%, 100% 75%, 75% 75%, 75% 100%, 50% 75%, 0% 75%)' => ['title' => 'Message', 'return_val' => 'val', 'image_preview' => DCE_URL . 'assets/img/shapes/message.png'], 'polygon(20% 0%, 0% 20%, 30% 50%, 0% 80%, 20% 100%, 50% 70%, 80% 100%, 100% 80%, 70% 50%, 100% 20%, 80% 0%, 50% 30%)' => ['title' => 'Close', 'return_val' => 'val', 'image_preview' => DCE_URL . 'assets/img/shapes/close.png'], 'polygon(0% 0%, 0% 100%, 25% 100%, 25% 25%, 75% 25%, 75% 75%, 25% 75%, 25% 100%, 100% 100%, 100% 0%)' => ['title' => 'Frame', 'return_val' => 'val', 'image_preview' => DCE_URL . 'assets/img/shapes/frame.png'], 'circle(50% at 50% 50%)' => ['title' => 'Circle', 'return_val' => 'val', 'image_preview' => DCE_URL . 'assets/img/shapes/circle.png'], 'ellipse(25% 40% at 50% 50%)' => ['title' => 'Ellipse', 'return_val' => 'val', 'image_preview' => DCE_URL . 'assets/img/shapes/ellipse.png']], 'condition' => ['_skin' => ['', 'grid', 'carousel', 'grid-filters', 'dualcarousel', 'smoothscroll', '3d'], 'style_items!' => ['default', 'template', 'html_tokens'], 'imagemask_popover' => 'yes', 'mask_shape_type' => 'clippath'], 'selectors' => ['{{WRAPPER}} .dce-posts-container .dce-post-image img' => '-webkit-clip-path: {{VALUE}}; clip-path: {{VALUE}};']]);
        $this->end_popover();
        // +********************* Image Zone: Transforms
        $this->add_control('imagetransforms_popover', ['label' => __('Transforms', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::POPOVER_TOGGLE, 'return_value' => 'yes', 'render_type' => 'ui', 'condition' => ['_skin' => ['', 'grid', 'carousel', 'grid-filters', 'dualcarousel', 'smoothscroll', '3d'], 'style_items!' => ['default', 'template', 'html_tokens']]]);
        $this->start_popover();
        $this->add_group_control(DCE_Group_Control_Transform_Element::get_type(), ['name' => 'transform_image', 'label' => __('Transform image', 'dynamic-content-for-elementor'), 'selector' => '{{WRAPPER}} .dce-post-item .dce-image-area', 'separator' => 'before', 'condition' => ['_skin' => ['', 'grid', 'carousel', 'grid-filters', 'dualcarousel', 'smoothscroll', '3d'], 'style_items!' => ['default', 'template', 'html_tokens'], 'imagetransforms_popover' => 'yes']]);
        $this->end_popover();
        // +********************* Image Zone: Filters
        $this->add_group_control(DCE_Group_Control_Filters_CSS::get_type(), ['name' => 'imagezone_filters', 'label' => __('Filters', 'dynamic-content-for-elementor'), 'render_type' => 'ui', 'selector' => '{{WRAPPER}} .dce-post-block .dce-post-image img', 'condition' => ['_skin' => ['', 'grid', 'carousel', 'grid-filters', 'dualcarousel', 'smoothscroll', '3d'], 'style_items!' => ['default', 'template', 'html_tokens']]]);
        // +********************* Content Zone Style:
        $this->add_control('heading_contentzone', ['label' => __('Content', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::HEADING, 'separator' => 'before', 'condition' => ['_skin' => ['', 'grid', 'carousel', 'grid-filters', 'dualcarousel', 'smoothscroll', '3d'], 'style_items!' => ['default', 'template', 'html_tokens']]]);
        // +********************* Content Zone: Style
        $this->add_control('contentstyle_popover', ['label' => __('Style', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::POPOVER_TOGGLE, 'label_off' => __('Default', 'dynamic-content-for-elementor'), 'label_on' => __('Custom', 'dynamic-content-for-elementor'), 'return_value' => 'yes', 'render_type' => 'ui', 'condition' => ['_skin' => ['', 'grid', 'carousel', 'grid-filters', 'dualcarousel', 'smoothscroll'], 'style_items!' => ['default', 'template', 'html_tokens']]]);
        $this->start_popover();
        $this->add_control('contentzone_bgcolor', ['label' => __('Background Color', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::COLOR, 'default' => '', 'separator' => 'before', 'selectors' => ['{{WRAPPER}} .dce-post-item .dce-content-area' => 'background-color: {{VALUE}};'], 'condition' => ['_skin' => ['', 'grid', 'carousel', 'grid-filters', 'dualcarousel', 'smoothscroll', '3d'], 'style_items!' => ['default', 'template', 'html_tokens'], 'contentstyle_popover' => 'yes']]);
        $this->add_group_control(Group_Control_Border::get_type(), ['name' => 'contentzone_border', 'selector' => '{{WRAPPER}} .dce-post-item .dce-content-area', 'condition' => ['_skin' => ['', 'grid', 'carousel', 'grid-filters', 'dualcarousel', 'smoothscroll', '3d'], 'style_items!' => ['default', 'template', 'html_tokens'], 'contentstyle_popover' => 'yes']]);
        $this->add_responsive_control('contentzone_padding', ['label' => __('Padding', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => ['px', '%', 'em'], 'selectors' => ['{{WRAPPER}} .dce-post-item .dce-content-area' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'], 'condition' => ['_skin' => ['', 'grid', 'carousel', 'grid-filters', 'dualcarousel', 'smoothscroll', '3d'], 'style_items!' => ['default', 'template', 'html_tokens'], 'contentstyle_popover' => 'yes']]);
        $this->add_responsive_control('contentzone_border_radius', ['label' => __('Border Radius', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => ['px', '%', 'em'], 'selectors' => ['{{WRAPPER}} .dce-post-item .dce-content-area' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'], 'condition' => ['_skin' => ['', 'grid', 'carousel', 'grid-filters', 'dualcarousel', 'smoothscroll', '3d'], 'style_items!' => ['default', 'template', 'html_tokens'], 'contentstyle_popover' => 'yes']]);
        $this->end_popover();
        // +********************* Content Zone Transform: Overlay, TextZone, Float
        $this->add_control('contenttransform_popover', ['label' => __('Transform', 'dynamic-content-for-elementor'), 'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE, 'label_off' => __('Default', 'dynamic-content-for-elementor'), 'label_on' => __('Custom', 'dynamic-content-for-elementor'), 'return_value' => 'yes', 'render_type' => 'ui', 'condition' => ['_skin' => ['', 'grid', 'carousel', 'grid-filters', 'dualcarousel', 'smoothscroll', '3d'], 'style_items' => ['overlay', 'textzone', 'float']]]);
        $this->start_popover();
        $this->add_responsive_control('contentzone_x', ['label' => __('X', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SLIDER, 'size_units' => ['%'], 'default' => ['size' => '', 'unit' => '%'], 'range' => ['%' => ['min' => -100, 'max' => 100, 'step' => 0.1]], 'selectors' => ['{{WRAPPER}} .dce-content-area' => 'margin-left: {{SIZE}}%;'], 'condition' => ['contenttransform_popover' => 'yes', '_skin' => ['', 'grid', 'carousel', 'grid-filters', 'dualcarousel', 'smoothscroll', '3d'], 'style_items' => ['overlay', 'textzone', 'float']]]);
        $this->add_responsive_control('contentzone_y', ['label' => __('Y', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SLIDER, 'default' => ['size' => '', 'unit' => '%'], 'size_units' => ['%'], 'range' => ['%' => ['min' => -100, 'max' => 100, 'step' => 0.1]], 'selectors' => ['{{WRAPPER}} .dce-content-area' => 'margin-top: {{SIZE}}%;'], 'condition' => ['contenttransform_popover' => 'yes', '_skin' => ['', 'grid', 'carousel', 'grid-filters', 'dualcarousel', 'smoothscroll', '3d'], 'style_items' => ['overlay', 'textzone', 'float']]]);
        $this->add_responsive_control('contentzone_width', ['label' => __('Width (%)', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SLIDER, 'default' => ['size' => '', 'unit' => '%'], 'size_units' => ['%'], 'range' => ['%' => ['min' => 1, 'max' => 100, 'step' => 0.1]], 'selectors' => ['{{WRAPPER}} .dce-content-area' => 'width: {{SIZE}}%;'], 'condition' => ['contenttransform_popover' => 'yes', '_skin' => ['', 'grid', 'carousel', 'grid-filters', 'dualcarousel', 'smoothscroll', '3d'], 'style_items' => ['overlay', 'textzone', 'float']]]);
        $this->add_responsive_control('contentzone_height', ['label' => __('Height (%)', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SLIDER, 'default' => ['size' => '', 'unit' => '%'], 'size_units' => ['%'], 'range' => ['%' => ['min' => 1, 'max' => 100, 'step' => 0.1]], 'selectors' => ['{{WRAPPER}} .dce-content-area' => 'height: {{SIZE}}%;'], 'condition' => ['contenttransform_popover' => 'yes', '_skin' => ['', 'grid', 'carousel', 'grid-filters', 'dualcarousel', 'smoothscroll', '3d'], 'style_items' => ['float']]]);
        $this->end_popover();
        // +********************* Content Zone: BoxShadow
        $this->add_group_control(Group_Control_Box_Shadow::get_type(), ['name' => 'contentzone_box_shadow', 'selector' => '{{WRAPPER}} .dce-post-item .dce-content-area', 'condition' => ['_skin' => ['', 'grid', 'carousel', 'grid-filters', 'dualcarousel', 'smoothscroll', '3d'], 'style_items!' => ['default', 'template', 'html_tokens']], 'popover' => \true]);
        /* Responsive --------------- */
        $this->add_control('force_layout_default', ['label' => __('Force default layout on mobile', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SWITCHER, 'separator' => 'before', 'prefix_class' => 'force-default-mobile-', 'condition' => ['_skin' => ['', 'grid', 'grid-filters', 'carousel', 'dualcarousel', 'smoothscroll', '3d'], 'style_items' => ['left', 'right', 'alternate']]]);
        // +********************* Style: Elementor TEMPLATE
        $this->add_control('template_id', ['label' => __('Template', 'dynamic-content-for-elementor'), 'type' => 'ooo_query', 'placeholder' => __('Template Name', 'dynamic-content-for-elementor'), 'label_block' => \true, 'query_type' => 'posts', 'render_type' => 'template', 'object_type' => 'elementor_library', 'condition' => ['_skin' => ['', 'grid', 'carousel', 'grid-filters', 'dualcarousel', 'smoothscroll', '3d'], 'style_items' => 'template', 'native_templatemode_enable' => '']]);
        $this->add_control('templatemode_enable_2', ['label' => __('Template for even posts', 'dynamic-content-for-elementor'), 'description' => __('Enable a template to manage the appearance of the even elements.', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SWITCHER, 'default' => '', 'render_type' => 'template', 'condition' => ['_skin' => ['', 'grid', 'carousel', 'grid-filters', 'dualcarousel', 'smoothscroll', '3d'], 'style_items' => 'template', 'native_templatemode_enable' => '']]);
        $this->add_control('template_2_id', ['label' => __('Template for even posts', 'dynamic-content-for-elementor'), 'type' => 'ooo_query', 'placeholder' => __('Select Template', 'dynamic-content-for-elementor'), 'label_block' => \true, 'show_label' => \false, 'query_type' => 'posts', 'object_type' => 'elementor_library', 'render_type' => 'template', 'condition' => ['_skin' => ['', 'grid', 'carousel', 'grid-filters', 'dualcarousel', 'smoothscroll', '3d'], 'style_items' => 'template', 'templatemode_enable_2!' => '', 'native_templatemode_enable' => '']]);
        if (\DynamicContentForElementor\Plugin::instance()->template_system->is_active()) {
            $this->add_control('native_templatemode_enable', ['label' => __('Template System Block', 'dynamic-content-for-elementor'), 'description' => __('Use the template associated with the type (Menu: Dynamic.ooo > Template System) to manage the appearance of the individual elements of the grid ', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SWITCHER, 'default' => '', 'render_type' => 'template', 'condition' => ['_skin' => ['', 'grid', 'carousel', 'grid-filters', 'dualcarousel', 'smoothscroll', '3d'], 'style_items' => 'template', 'templatemode_enable_2' => '']]);
        }
        $this->add_control('templatemode_linkable', ['label' => __('Linkable', 'dynamic-content-for-elementor'), 'description' => __('Apply the extended link on the template block.', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SWITCHER, 'separator' => 'before', 'frontend_available' => \true, 'render_type' => 'template', 'condition' => ['_skin' => ['', 'grid', 'carousel', 'grid-filters', 'dualcarousel', 'smoothscroll', '3d'], 'style_items' => 'template']]);
        $this->end_controls_section();
        // ------------------------------------------------------------------ [SECTION ITEMS]
        $this->start_controls_section('section_items', ['label' => __('Items', 'dynamic-content-for-elementor'), 'condition' => ['_skin!' => ['nextpost'], 'style_items!' => ['template', 'html_tokens']]]);
        $repeater = new Repeater();
        // Items for WooCommerce
        $woocommerce_items = [];
        if (Helper::is_woocommerce_active()) {
            $woocommerce_items = ['item_addtocart' => __('Add to Cart', 'dynamic-content-for-elementor'), 'item_productprice' => __('Product Price', 'dynamic-content-for-elementor'), 'item_sku' => __('Product SKU', 'dynamic-content-for-elementor')];
        }
        $repeater->add_control('item_id', ['label' => __('Type', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SELECT, 'label_block' => \false, 'options' => \array_merge(['item_title' => __('Title', 'dynamic-content-for-elementor'), 'item_image' => __('Featured Image', 'dynamic-content-for-elementor'), 'item_date' => __('Date', 'dynamic-content-for-elementor'), 'item_termstaxonomy' => __('Terms', 'dynamic-content-for-elementor'), 'item_content' => __('Content', 'dynamic-content-for-elementor'), 'item_author' => __('Author', 'dynamic-content-for-elementor'), 'item_custommeta' => __('Custom Meta Fields', 'dynamic-content-for-elementor'), 'item_readmore' => __('Read More', 'dynamic-content-for-elementor'), 'item_posttype' => __('Post Type', 'dynamic-content-for-elementor')], $woocommerce_items), 'default' => 'item_title']);
        // TABS ----------
        $repeater->start_controls_tabs('items_repeater_tab');
        $repeater->start_controls_tab('tab_content', ['label' => __('Content', 'dynamic-content-for-elementor')]);
        // CONTENT - TAB
        //
        // +********************* Image
        $repeater->add_group_control(Group_Control_Image_Size::get_type(), ['name' => 'thumbnail_size', 'label' => __('Image Format', 'dynamic-content-for-elementor'), 'default' => 'large', 'conditions' => ['terms' => [['name' => 'item_id', 'value' => 'item_image']]]]);
        $repeater->add_control('preview_image_ratio', ['text' => __('Preview Image Ratio', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::BUTTON, 'event' => 'dceDynamicPosts:previewImageRatio', 'conditions' => ['terms' => [['name' => 'item_id', 'value' => 'item_image'], ['name' => 'use_bgimage', 'value' => '']]]]);
        $repeater->add_responsive_control('ratio_image', ['label' => __('Image Ratio', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SLIDER, 'range' => ['px' => ['min' => 0.1, 'max' => 2, 'step' => 0.1]], 'selectors' => ['{{WRAPPER}} {{CURRENT_ITEM}} .dce-img' => 'padding-bottom: calc( {{SIZE}} * 100% );'], 'conditions' => ['terms' => [['name' => 'item_id', 'value' => 'item_image'], ['name' => 'use_bgimage', 'value' => '']]]]);
        $repeater->add_responsive_control('width_image', ['label' => __('Image Width', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SLIDER, 'size_units' => ['%', 'px', 'vw'], 'range' => ['%' => ['min' => 1, 'max' => 100, 'step' => 1], 'vw' => ['min' => 1, 'max' => 100, 'step' => 1], 'px' => ['min' => 1, 'max' => 800, 'step' => 1]], 'selectors' => ['{{WRAPPER}} {{CURRENT_ITEM}} .dce-post-image' => 'width: {{SIZE}}{{UNIT}};'], 'conditions' => ['terms' => [['name' => 'item_id', 'value' => 'item_image'], ['name' => 'use_bgimage', 'value' => '']]]]);
        $repeater->add_control('use_bgimage', ['label' => __('Background Image', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SWITCHER, 'separator' => 'before', 'render_type' => 'template', 'conditions' => ['terms' => [['name' => 'item_id', 'value' => 'item_image']]], 'selectors' => ['{{WRAPPER}} .dce-image-area, {{WRAPPER}}.dce-posts-layout-default .dce-post-bgimage' => 'position: relative;']]);
        $repeater->add_responsive_control('height_bgimage', ['label' => __('Height', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SLIDER, 'size_units' => ['px', '%'], 'range' => ['px' => ['min' => 1, 'max' => 800, 'step' => 1]], 'selectors' => ['{{WRAPPER}} {{CURRENT_ITEM}} .dce-post-image.dce-post-bgimage' => 'height: {{SIZE}}{{UNIT}};'], 'conditions' => ['terms' => [['name' => 'item_id', 'value' => 'item_image'], ['name' => 'use_bgimage', 'operator' => '!=', 'value' => '']]]]);
        $repeater->add_responsive_control('position_bgimage', ['label' => __('Background Position', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SELECT, 'label_block' => \true, 'default' => '', 'responsive' => \true, 'options' => ['' => __('Default (Center Center)', 'dynamic-content-for-elementor'), 'top center' => _x('Top Center', 'Background Control', 'dynamic-content-for-elementor'), 'bottom center' => _x('Bottom Center', 'Background Control', 'dynamic-content-for-elementor')], 'selectors' => ['{{WRAPPER}} {{CURRENT_ITEM}} .dce-post-image.dce-post-bgimage .dce-bgimage' => 'background-position: {{VALUE}};'], 'conditions' => ['terms' => [['name' => 'item_id', 'value' => 'item_image'], ['name' => 'use_bgimage', 'operator' => '!=', 'value' => '']]]]);
        $repeater->add_control('use_overlay', ['label' => __('Overlay', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SWITCHER, 'separator' => 'before', 'prefix_class' => 'overlayimage-', 'render_type' => 'template', 'conditions' => ['terms' => [['name' => 'item_id', 'value' => 'item_image']]]]);
        $repeater->add_group_control(Group_Control_Background::get_type(), ['name' => 'overlay_color', 'label' => __('Background', 'dynamic-content-for-elementor'), 'types' => ['classic', 'gradient'], 'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .dce-post-image.dce-post-overlayimage:after', 'conditions' => ['terms' => [['name' => 'item_id', 'value' => 'item_image'], ['name' => 'use_overlay', 'operator' => '!==', 'value' => '']]]]);
        $repeater->add_responsive_control('overlay_opacity', ['label' => __('Opacity (%)', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SLIDER, 'default' => ['size' => 0.7], 'range' => ['px' => ['max' => 1, 'min' => 0.1, 'step' => 0.01]], 'selectors' => ['{{WRAPPER}} {{CURRENT_ITEM}} .dce-post-image.dce-post-overlayimage:after' => 'opacity: {{SIZE}};'], 'conditions' => ['terms' => [['name' => 'item_id', 'value' => 'item_image'], ['name' => 'use_overlay', 'operator' => '!==', 'value' => '']]]]);
        // Custom Meta Fields
        $repeater->add_control('metafield_key', ['label' => __('Meta Field', 'dynamic-content-for-elementor'), 'type' => 'ooo_query', 'placeholder' => __('Meta key or Name', 'dynamic-content-for-elementor'), 'label_block' => \true, 'query_type' => 'metas', 'object_type' => 'post', 'default' => '', 'dynamic' => ['active' => \false], 'conditions' => ['terms' => [['name' => 'item_id', 'value' => 'item_custommeta']]]]);
        $repeater->add_control('metafield_type', ['label' => __('Field type', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SELECT, 'default' => 'text', 'options' => ['image' => __('Image', 'dynamic-content-for-elementor'), 'date' => __('Date', 'dynamic-content-for-elementor'), 'text' => __('Text', 'dynamic-content-for-elementor'), 'textarea' => __('Textarea', 'dynamic-content-for-elementor'), 'textfield' => __('Textfield', 'dynamic-content-for-elementor'), 'button' => __('Button (URL)', 'dynamic-content-for-elementor'), 'url' => __('URL', 'dynamic-content-for-elementor')], 'conditions' => ['terms' => [['name' => 'item_id', 'value' => 'item_custommeta']]]]);
        $repeater->add_group_control(Group_Control_Image_Size::get_type(), ['name' => 'image_size', 'label' => __('Image Format', 'dynamic-content-for-elementor'), 'default' => 'large', 'conditions' => ['terms' => [['name' => 'item_id', 'value' => 'item_custommeta'], ['name' => 'metafield_type', 'value' => 'image']]]]);
        $repeater->add_control('metafield_date_format_source', ['label' => __('Date Format - Source', 'dynamic-content-for-elementor'), 'description' => '<a target="_blank" href="https://www.php.net/manual/en/function.date.php">' . __('Use standard PHP format characters', 'dynamic-content-for-elementor') . '</a>', 'type' => Controls_Manager::TEXT, 'default' => 'F j, Y, g:i a', 'placeholder' => __('YmdHis, d/m/Y, m-d-y', 'dynamic-content-for-elementor'), 'label_block' => \true, 'conditions' => ['terms' => [['name' => 'item_id', 'value' => 'item_custommeta'], ['name' => 'metafield_type', 'value' => 'date']]]]);
        $repeater->add_control('metafield_date_format_display', ['label' => __('Date Format - Display', 'dynamic-content-for-elementor'), 'placeholder' => __('YmdHis, d/m/Y, m-d-y', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::TEXT, 'default' => 'F j, Y, g:i a', 'label_block' => \true, 'conditions' => ['terms' => [['name' => 'item_id', 'value' => 'item_custommeta'], ['name' => 'metafield_type', 'value' => 'date']]]]);
        $repeater->add_control('metafield_button_label', ['label' => __('Button Label', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::TEXT, 'default' => 'Click me', 'conditions' => ['terms' => [['name' => 'item_id', 'value' => 'item_custommeta'], ['name' => 'metafield_type', 'value' => 'button']]]]);
        $repeater->add_control('metafield_button_size', ['label' => __('Button Size', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SELECT, 'default' => 'sm', 'options' => Helper::get_button_sizes(), 'style_transfer' => \true, 'conditions' => ['terms' => [['name' => 'item_id', 'value' => 'item_custommeta'], ['name' => 'metafield_type', 'value' => 'button']]]]);
        $repeater->add_control('price_format', ['label' => __('Price Format', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SELECT, 'default' => 'both', 'options' => ['both' => __('Both', 'dynamic-content-for-elementor'), 'regular' => __('Regular Price', 'dynamic-content-for-elementor'), 'sale' => __('Sale Price', 'dynamic-content-for-elementor')], 'conditions' => ['terms' => [['name' => 'item_id', 'value' => 'item_productprice']]]]);
        $repeater->add_control('add_to_cart_text', ['label' => __('Text', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::TEXT, 'default' => __('Add to Cart', 'dynamic-content-for-elementor'), 'conditions' => ['terms' => [['name' => 'item_id', 'value' => 'item_addtocart']]]]);
        $repeater->add_control('html_tag_item', ['label' => __('HTML Tag', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SELECT, 'options' => ['' => __('None', 'dynamic-content-for-elementor'), 'h1' => __('H1', 'dynamic-content-for-elementor'), 'h2' => __('H2', 'dynamic-content-for-elementor'), 'h3' => __('H3', 'dynamic-content-for-elementor'), 'h4' => __('H4', 'dynamic-content-for-elementor'), 'h5' => __('H5', 'dynamic-content-for-elementor'), 'h6' => __('H6', 'dynamic-content-for-elementor'), 'p' => __('p', 'dynamic-content-for-elementor'), 'div' => __('div', 'dynamic-content-for-elementor'), 'span' => __('span', 'dynamic-content-for-elementor')], 'conditions' => ['terms' => [['name' => 'item_id', 'value' => 'item_custommeta'], ['name' => 'metafield_type', 'value' => 'text']]], 'default' => '']);
        $repeater->add_control('link_to', ['label' => __('Link to', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SELECT, 'default' => '', 'separator' => 'before', 'options' => ['' => __('None', 'dynamic-content-for-elementor'), 'home' => __('Home URL', 'dynamic-content-for-elementor'), 'post' => __('Post URL', 'dynamic-content-for-elementor'), 'custom' => __('Custom URL', 'dynamic-content-for-elementor')], 'conditions' => ['terms' => [['name' => 'item_id', 'value' => 'item_custommeta'], ['name' => 'metafield_type', 'operator' => '!==', 'value' => 'button']]]]);
        $repeater->add_control('link', ['label' => __('Link', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::URL, 'placeholder' => __('https://your-link.com', 'dynamic-content-for-elementor'), 'conditions' => ['terms' => [['name' => 'item_id', 'value' => 'item_custommeta'], ['name' => 'link_to', 'value' => 'custom']]], 'default' => ['url' => ''], 'show_label' => \false]);
        // +********************* Title
        $repeater->add_control('html_tag', ['label' => __('HTML Tag', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SELECT, 'options' => ['h1' => __('H1', 'dynamic-content-for-elementor'), 'h2' => __('H2', 'dynamic-content-for-elementor'), 'h3' => __('H3', 'dynamic-content-for-elementor'), 'h4' => __('H4', 'dynamic-content-for-elementor'), 'h5' => __('H5', 'dynamic-content-for-elementor'), 'h6' => __('H6', 'dynamic-content-for-elementor'), 'p' => __('p', 'dynamic-content-for-elementor'), 'div' => __('div', 'dynamic-content-for-elementor'), 'span' => __('span', 'dynamic-content-for-elementor')], 'default' => 'h3', 'conditions' => ['terms' => [['name' => 'item_id', 'value' => 'item_title']]]]);
        // +********************* Date
        $repeater->add_control('date_type', ['label' => __('Date Type', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SELECT, 'options' => ['publish' => __('Publish Date', 'dynamic-content-for-elementor'), 'modified' => __('Last Modified Date', 'dynamic-content-for-elementor')], 'default' => 'publish', 'conditions' => ['terms' => [['name' => 'item_id', 'value' => 'item_date']]]]);
        // added block_enable
        $repeater->add_control('date_format', ['label' => __('Date Format', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::TEXT, 'default' => 'd/<b>m</b>/y', 'conditions' => ['terms' => [['name' => 'item_id', 'value' => 'item_date']]]]);
        // +********************* Terms of Taxonomy [metadata] (Category, Tag, CustomTax)
        $repeater->add_control('separator_chart', ['label' => __('Separator', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::TEXT, 'default' => '/', 'conditions' => ['terms' => [['name' => 'item_id', 'value' => 'item_termstaxonomy']]]]);
        $repeater->add_control('only_parent_terms', ['label' => __('Show only', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::CHOOSE, 'options' => ['both' => ['title' => __('Both', 'dynamic-content-for-elementor'), 'icon' => 'fa fa-sitemap'], 'yes' => ['title' => __('Parents', 'dynamic-content-for-elementor'), 'icon' => 'fa fa-female'], 'children' => ['title' => __('Children', 'dynamic-content-for-elementor'), 'icon' => 'fa fa-child']], 'toggle' => \false, 'default' => 'both', 'conditions' => ['terms' => [['name' => 'item_id', 'value' => 'item_termstaxonomy']]]]);
        $repeater->add_control('block_enable', ['label' => __('Block', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SWITCHER, 'return_value' => 'block', 'render_type' => 'template', 'selectors' => ['{{WRAPPER}} {{CURRENT_ITEM}} .dce-term-item' => 'display: {{VALUE}}'], 'conditions' => ['terms' => [['name' => 'item_id', 'operator' => 'in', 'value' => ['item_termstaxonomy']]]]]);
        $repeater->add_control('icon_enable', ['label' => __('Icon', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SWITCHER, 'conditions' => ['terms' => [['name' => 'item_id', 'operator' => 'in', 'value' => ['item_termstaxonomy', 'item_date']]]]]);
        $repeater->add_control('taxonomy_filter', ['label' => __('Filter Taxonomy', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SELECT2, 'separator' => 'before', 'label_block' => \true, 'multiple' => \true, 'options' => $taxonomies, 'placeholder' => __('Auto', 'dynamic-content-for-elementor'), 'description' => __('Use only terms in selected taxonomies. If empty all terms will be used.', 'dynamic-content-for-elementor'), 'conditions' => ['terms' => [['name' => 'item_id', 'value' => 'item_termstaxonomy']]]]);
        // +********************* Content/Excerpt
        $repeater->add_control('content_type', ['label' => __('Content type', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SELECT, 'toggle' => \false, 'label_block' => \false, 'options' => ['0' => __('Manual Excerpt', 'dynamic-content-for-elementor'), 'auto-excerpt' => __('Automatic Excerpt', 'dynamic-content-for-elementor'), '1' => __('Content', 'dynamic-content-for-elementor')], 'default' => '0', 'conditions' => ['terms' => [['name' => 'item_id', 'value' => 'item_content']]]]);
        $repeater->add_control('textcontent_limit', ['label' => __('Content Character Limit', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::NUMBER, 'default' => '', 'conditions' => ['terms' => [['name' => 'item_id', 'value' => 'item_content'], ['name' => 'content_type', 'value' => '1']]]]);
        // +********************* ReadMore
        $repeater->add_control('readmore_text', ['label' => __('Text', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::TEXT, 'default' => __('Read More', 'dynamic-content-for-elementor'), 'conditions' => ['terms' => [['name' => 'item_id', 'value' => 'item_readmore']]]]);
        $repeater->add_control('readmore_size', ['label' => __('Size', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SELECT, 'default' => 'sm', 'options' => Helper::get_button_sizes(), 'style_transfer' => \true, 'conditions' => ['terms' => [['name' => 'item_id', 'operator' => 'in', 'value' => ['item_readmore', 'item_addtocart']]]]]);
        // +********************* Author user
        $repeater->add_control('author_user_key', ['label' => __('User Key', 'dynamic-content-for-elementor'), 'type' => 'ooo_query', 'placeholder' => __('Field key or Name', 'dynamic-content-for-elementor'), 'label_block' => \true, 'multiple' => \true, 'query_type' => 'fields', 'object_type' => 'user', 'default' => ['avatar', 'display_name'], 'conditions' => ['terms' => [['name' => 'item_id', 'value' => 'item_author']]]]);
        $repeater->add_control('author_image_size', ['label' => __('Avatar size', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::NUMBER, 'default' => '50', 'render_type' => 'template', 'conditions' => ['terms' => [['name' => 'item_id', 'value' => 'item_author'], ['name' => 'author_user_key', 'operator' => 'contains', 'value' => 'avatar'], ['name' => 'author_user_key', 'operator' => '!=', 'value' => ''], ['name' => 'author_user_key', 'operator' => '!=', 'value' => []]]]]);
        // +********************* Post Type
        $repeater->add_control('posttype_label', ['label' => __('Post Type Label', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SELECT, 'default' => 'plural', 'options' => ['plural' => __('Plural', 'dynamic-content-for-elementor'), 'singular' => __('Singular', 'dynamic-content-for-elementor')], 'conditions' => ['terms' => [['name' => 'item_id', 'value' => 'item_posttype']]]]);
        $repeater->end_controls_tab();
        $repeater->start_controls_tab('tab_style', ['label' => __('Style', 'dynamic-content-for-elementor')]);
        $repeater->add_responsive_control('item_align', ['label' => __('Alignment', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::CHOOSE, 'options' => ['left' => ['title' => __('Left', 'dynamic-content-for-elementor'), 'icon' => 'eicon-text-align-left'], 'center' => ['title' => __('Center', 'dynamic-content-for-elementor'), 'icon' => 'eicon-text-align-center'], 'right' => ['title' => __('Right', 'dynamic-content-for-elementor'), 'icon' => 'eicon-text-align-right']], 'default' => '', 'selectors' => ['{{WRAPPER}} {{CURRENT_ITEM}}' => 'text-align: {{VALUE}};'], 'conditions' => ['terms' => [['name' => 'item_id', 'operator' => '!in', 'value' => ['item_image']]]]]);
        $repeater->add_responsive_control('image_align', ['label' => __('Image Alignment', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::CHOOSE, 'options' => ['flex-start' => ['title' => __('Left', 'dynamic-content-for-elementor'), 'icon' => 'eicon-h-align-left'], 'center' => ['title' => __('Center', 'dynamic-content-for-elementor'), 'icon' => 'eicon-h-align-center'], 'flex-end' => ['title' => __('Right', 'dynamic-content-for-elementor'), 'icon' => 'eicon-h-align-right']], 'default' => 'top', 'selectors' => ['{{WRAPPER}} {{CURRENT_ITEM}}.dce-item_image' => 'justify-content: {{VALUE}};'], 'conditions' => ['terms' => [['name' => 'item_id', 'operator' => 'in', 'value' => ['item_image']]]]]);
        // -------- TYPOGRAPHY
        $repeater->add_group_control(Group_Control_Typography::get_type(), ['name' => 'typography_item', 'label' => __('Typography', 'dynamic-content-for-elementor'), 'render_type' => 'ui', 'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} > *', 'conditions' => ['terms' => [['name' => 'item_id', 'operator' => '!in', 'value' => ['item_image', 'item_readmore', 'item_addtocart']]]]]);
        // Read More
        $repeater->add_group_control(Group_Control_Typography::get_type(), ['name' => 'typography_item_readmore', 'label' => __('Typography', 'dynamic-content-for-elementor'), 'render_type' => 'ui', 'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .dce-post-button > *', 'conditions' => ['terms' => [['name' => 'item_id', 'operator' => 'in', 'value' => ['item_readmore', 'item_addtocart']]]]]);
        // -------- COLORS
        $repeater->add_control('color_item', ['label' => __('Text Color', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} {{CURRENT_ITEM}} > *' => 'color: {{VALUE}};', '{{WRAPPER}} {{CURRENT_ITEM}} a' => 'color: {{VALUE}};'], 'conditions' => ['terms' => [['name' => 'item_id', 'operator' => '!in', 'value' => ['item_image']]]]]);
        $repeater->add_control('color_item_separator', ['label' => __('Separator Color', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} {{CURRENT_ITEM}} .dce-term-item .dce-separator' => 'color: {{VALUE}};'], 'conditions' => ['terms' => [['name' => 'item_id', 'operator' => 'in', 'value' => ['item_termstaxonomy']], ['name' => 'block_enable', 'value' => '']]]]);
        $repeater->add_control('color_item_icon', ['label' => __('Icon Color', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} {{CURRENT_ITEM}} .dce-post-icon' => 'color: {{VALUE}};'], 'conditions' => ['terms' => [['name' => 'item_id', 'operator' => 'in', 'value' => ['item_termstaxonomy', 'item_date']], ['name' => 'icon_enable', 'value' => 'yes']]]]);
        $repeater->add_control('bgcolor_item', ['label' => __('Background Color', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} {{CURRENT_ITEM}} *:not(.dce-post-button) > *' => 'background-color: {{VALUE}};', '{{WRAPPER}} {{CURRENT_ITEM}} a.dce-button' => 'background-color: {{VALUE}};'], 'conditions' => ['terms' => [['name' => 'item_id', 'operator' => '!in', 'value' => ['item_image', 'item_author']]]]]);
        $repeater->add_control('hover_color_item', ['label' => __('Hover Color', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} {{CURRENT_ITEM}} a:hover' => 'color: {{VALUE}};'], 'conditions' => ['relation' => 'or', 'terms' => [['name' => 'metafield_type', 'operator' => '!=', 'value' => 'image'], ['name' => 'link_to', 'operator' => '!=', 'value' => '']]]]);
        $repeater->add_control('hover_bgcolor_item', ['label' => __('Hover Background Color', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} {{CURRENT_ITEM}} a:hover' => 'background-color: {{VALUE}};'], 'condition' => ['metafield_type' => 'button']]);
        $repeater->add_control('padding_item', ['label' => __('Padding', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => ['px', '%'], 'selectors' => ['{{WRAPPER}} {{CURRENT_ITEM}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'], 'conditions' => ['terms' => [['name' => 'item_id', 'operator' => '!in', 'value' => ['item_readmore', 'item_addtocart']]]]]);
        $repeater->add_control('heading_item_button', ['label' => __('Button', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::HEADING, 'separator' => 'before', 'condition' => ['metafield_type' => 'button']]);
        $repeater->add_control('heading_item_image', ['label' => __('Image', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::HEADING, 'separator' => 'before', 'condition' => ['metafield_type' => 'image']]);
        $repeater->add_responsive_control('border_radius_item', ['label' => __('Border Radius', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => ['px', '%', 'em'], 'selectors' => ['{{WRAPPER}} {{CURRENT_ITEM}} .dce-button, {{WRAPPER}} {{CURRENT_ITEM}} .dce-img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'], 'condition' => ['metafield_type' => ['button', 'image']]]);
        $repeater->add_group_control(Group_Control_Border::get_type(), ['name' => 'border_item', 'label' => __('Border', 'dynamic-content-for-elementor'), 'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .dce-button', 'condition' => ['metafield_type' => ['button', 'image']]]);
        // ------------ SPACES
        $repeater->add_responsive_control('item_padding', ['label' => __('Padding', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => ['px', '%', 'rem'], 'separator' => 'before', 'selectors' => ['{{WRAPPER}} {{CURRENT_ITEM}}:not(.dce-item_readmore) > *, {{WRAPPER}} {{CURRENT_ITEM}} a.dce-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']]);
        $repeater->add_responsive_control('item_margin', ['label' => __('Margin', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => ['px', '%', 'rem'], 'selectors' => ['{{WRAPPER}} {{CURRENT_ITEM}}' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']]);
        $repeater->add_responsive_control('item_border_radius', ['label' => __('Border Radius', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => ['px', '%', 'em'], 'conditions' => ['terms' => [['name' => 'item_id', 'operator' => '!in', 'value' => ['item_image', 'item_readmore', 'item_custommeta', 'item_addtocart']]]], 'selectors' => ['{{WRAPPER}} {{CURRENT_ITEM}} > *' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']]);
        $repeater->add_group_control(Group_Control_Border::get_type(), ['name' => 'item_border', 'label' => __('Border', 'dynamic-content-for-elementor'), 'conditions' => ['terms' => [['name' => 'item_id', 'operator' => '!in', 'value' => ['item_image', 'item_readmore', 'item_author', 'item_custommeta', 'item_addtocart']]]], 'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} > *']);
        $repeater->add_group_control(Group_Control_Box_Shadow::get_type(), ['name' => 'box_shadow', 'label' => __('Box Shadow', 'dynamic-content-for-elementor'), 'conditions' => ['terms' => [['name' => 'item_id', 'operator' => '!in', 'value' => ['item_image', 'item_readmore', 'item_author', 'item_custommeta', 'item_addtocart']]]], 'selector' => '{{WsRAPPER}} {{CURRENT_ITEM}} > *']);
        $repeater->add_responsive_control('item_in_border_radius', ['label' => __('Border Radius', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => ['px', '%', 'em'], 'conditions' => ['terms' => [['name' => 'item_id', 'operator' => 'in', 'value' => ['item_image', 'item_readmore', 'item_addtocart']]]], 'selectors' => ['{{WRAPPER}} {{CURRENT_ITEM}} .dce-button, {{WRAPPER}} {{CURRENT_ITEM}} .dce-img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']]);
        $repeater->add_group_control(Group_Control_Border::get_type(), ['name' => 'item_in_border', 'label' => __('Border', 'dynamic-content-for-elementor'), 'conditions' => ['terms' => [['name' => 'item_id', 'operator' => 'in', 'value' => ['item_image', 'item_readmore', 'item_author', 'item_addtocart']]]], 'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .dce-button, {{WRAPPER}} {{CURRENT_ITEM}} .dce-img']);
        $repeater->add_group_control(Group_Control_Box_Shadow::get_type(), ['name' => 'box_in_shadow', 'label' => __('Box Shadow', 'dynamic-content-for-elementor'), 'conditions' => ['terms' => [['name' => 'item_id', 'operator' => 'in', 'value' => ['item_image', 'item_readmore', 'item_author', 'item_addtocart']]]], 'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .dce-button, {{WRAPPER}} {{CURRENT_ITEM}} .dce-img']);
        $repeater->add_control('display_inline', ['label' => __('Display', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'label_on' => 'Inline', 'label_off' => 'Block', 'return_value' => 'inline-block', 'selectors' => ['{{WRAPPER}} {{CURRENT_ITEM}} > *' => 'display: {{VALUE}};'], 'conditions' => ['terms' => [['name' => 'item_id', 'operator' => 'in', 'value' => ['item_title', 'item_posttype', 'item_date', 'item_content', 'item_termstaxonomy']]]]]);
        $repeater->end_controls_tab();
        $repeater->start_controls_tab('tab_advanced', ['label' => __('Advanced', 'dynamic-content-for-elementor'), 'conditions' => ['terms' => [['name' => 'item_id', 'operator' => '!in', 'value' => ['item_custommeta', 'item_author', 'item_custommeta', 'item_addtocart']]]]]);
        // ADVANCED - TAB
        $repeater->add_control('use_link', ['label' => __('Use link', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'conditions' => ['terms' => [['name' => 'item_id', 'operator' => '!in', 'value' => ['item_custommeta', 'item_author', 'item_date', 'item_readmore', 'item_addtocart', 'item_content', 'item_posttype']]]]]);
        $repeater->add_control('open_target_blank', ['label' => __('Open link in a new window', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SWITCHER, 'default' => '', 'conditions' => ['terms' => [['name' => 'use_link', 'value' => 'yes'], ['name' => 'item_id', 'operator' => '!in', 'value' => ['item_custommeta', 'item_author', 'item_date', 'item_content', 'item_posttype', 'item_sku', 'item_addtocart', 'item_productprice']]]]]);
        $repeater->end_controls_tab();
        $repeater->end_controls_tabs();
        $this->add_control('list_items', ['type' => Controls_Manager::REPEATER, 'label' => __('Show these items', 'dynamic-content-for-elementor'), 'fields' => $repeater->get_controls(), 'item_actions' => ['add' => \true, 'duplicate' => \false, 'remove' => \true, 'sort' => \true], 'default' => [['item_id' => 'item_title'], ['item_id' => 'item_image']], 'title_field' => '{{{ posts_v2_item_id_to_label(item_id) }}}']);
        $this->end_controls_section();
        // ------------------------------------------------------------ [SECTION Hover Effects]
        $this->start_controls_section('section_hover_effect', ['label' => __('Hover Effects', 'dynamic-content-for-elementor'), 'tab' => Controls_Manager::TAB_CONTENT, 'condition' => ['_skin' => ['', 'grid', 'grid-filters', 'carousel', 'dualcarousel'], 'style_items!' => 'template']]);
        $this->start_controls_tabs('items_this_tab');
        $this->start_controls_tab('tab_hover_block', ['label' => __('Block', 'dynamic-content-for-elementor')]);
        $this->add_control('hover_animation', ['label' => __('Hover Animation', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::HOVER_ANIMATION]);
        $this->end_controls_tab();
        $this->start_controls_tab('tab_hover_image', ['label' => __('Image', 'dynamic-content-for-elementor')]);
        $this->add_responsive_control('hover_image_opacity', ['label' => __('Opacity', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SLIDER, 'range' => ['px' => ['max' => 1, 'min' => 0.1, 'step' => 0.01]], 'selectors' => ['{{WRAPPER}} .dce-post-block:not(.dce-hover-effects) a.dce-post-image:hover, {{WRAPPER}} .dce-post-block.dce-hover-effects:hover a.dce-post-image' => 'opacity: {{SIZE}};']]);
        $this->add_group_control(DCE_Group_Control_Filters_CSS::get_type(), ['name' => 'hover_filters_image', 'label' => 'Filters image', 'selector' => '{{WRAPPER}} .dce-post-block:not(.dce-hover-effects) a.dce-post-image:hover img, {{WRAPPER}} .dce-post-block.dce-hover-effects:hover a.dce-post-image img']);
        $this->add_control('use_overlay_hover', ['label' => __('Overlay', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::CHOOSE, 'toggle' => \false, 'label_block' => \false, 'separator' => 'before', 'options' => ['1' => ['title' => __('Yes', 'dynamic-content-for-elementor'), 'icon' => 'fa fa-check'], '0' => ['title' => __('No', 'dynamic-content-for-elementor'), 'icon' => 'fa fa-ban']], 'default' => '0']);
        $this->add_group_control(Group_Control_Background::get_type(), ['name' => 'overlay_color_hover', 'label' => __('Background', 'dynamic-content-for-elementor'), 'types' => ['classic', 'gradient'], 'selector' => '{{WRAPPER}} a.dce-post-image.dce-post-overlayhover:before', 'condition' => ['use_overlay_hover' => '1']]);
        $this->end_controls_tab();
        $this->start_controls_tab('tab_hover_content', ['label' => __('Content', 'dynamic-content-for-elementor'), 'condition' => ['style_items!' => 'default']]);
        $this->add_control('hover_content_animation', ['label' => __('Hover Animation', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::HOVER_ANIMATION, 'condition' => ['style_items!' => 'float']]);
        $this->add_control('hover_text_heading_float', ['label' => __('Float Style', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::HEADING, 'separator' => 'before', 'condition' => ['style_items' => 'float']]);
        $this->add_control('hover_text_effect', ['label' => __('TextZone Effect', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SELECT, 'default' => '', 'options' => ['' => __('None', 'dynamic-content-for-elementor'), 'fade' => 'Fade', 'slidebottom' => 'Slide bottom', 'slidetop' => 'Slide top', 'slideleft' => 'Slide left', 'slideright' => 'Slide right', 'cssanimations' => 'CSS Animations'], 'render_type' => 'template', 'prefix_class' => 'dce-hovertexteffect-', 'condition' => ['style_items' => 'float']]);
        $this->add_control('hover_text_effect_timingFunction', ['label' => __('Effect Timing function', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SELECT, 'groups' => Helper::get_anim_timing_functions(), 'default' => 'ease-in-out', 'selectors' => ['{{WRAPPER}} .dce-post-item .dce-hover-effect-content' => 'transition-timing-function: {{VALUE}}; -webkit-transition-timing-function: {{VALUE}};'], 'condition' => ['hover_text_effect!' => ['', 'cssanimations'], 'style_items' => 'float']]);
        $this->add_control('heading_hover_text_effect_in', ['label' => __('Animation IN', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::HEADING, 'separator' => 'before', 'condition' => ['hover_text_effect' => 'cssanimations', 'style_items' => 'float']]);
        $this->add_control('hover_text_effect_animation_in', ['label' => __('Animation effect', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SELECT, 'groups' => Helper::get_anim_in(), 'default' => 'fadeIn', 'frontend_available' => \true, 'render_type' => 'template', 'condition' => ['hover_text_effect' => 'cssanimations', 'style_items' => 'float'], 'selectors' => ['{{WRAPPER}} .dce-post-item .dce-hover-effect-content.dce-open' => 'animation-name: {{VALUE}}; -webkit-animation-name: {{VALUE}};']]);
        $this->add_control('hover_text_effect_timingFunction_in', ['label' => __('Effect Timing function', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SELECT, 'groups' => Helper::get_anim_timing_functions(), 'default' => 'ease-in-out', 'selectors' => ['{{WRAPPER}} .dce-post-item:hover .dce-hover-effect-content.dce-open' => 'animation-timing-function: {{VALUE}}; -webkit-animation-timing-function: {{VALUE}};'], 'condition' => ['hover_text_effect' => 'cssanimations', 'style_items' => 'float']]);
        $this->add_control('hover_text_effect_speed_in', ['label' => __('Speed (sec.)', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::NUMBER, 'default' => 0.5, 'min' => 0.1, 'max' => 2, 'step' => 0.1, 'dynamic' => ['active' => \false], 'selectors' => ['{{WRAPPER}} .dce-post-item:hover .dce-hover-effect-content.dce-open' => 'animation-duration: {{VALUE}}s; -webkit-animation-duration: {{VALUE}}s;'], 'condition' => ['hover_text_effect' => 'cssanimations', 'style_items' => 'float']]);
        $this->add_control('heading_hover_text_effect_out', ['label' => __('Animation OUT', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::HEADING, 'separator' => 'before', 'condition' => ['hover_text_effect' => 'cssanimations', 'style_items' => 'float']]);
        $this->add_control('hover_text_effect_animation_out', ['label' => __('Animation effect', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SELECT, 'groups' => Helper::get_anim_out(), 'default' => 'fadeOut', 'frontend_available' => \true, 'render_type' => 'template', 'condition' => ['hover_text_effect' => 'cssanimations', 'style_items' => 'float'], 'selectors' => ['{{WRAPPER}} .dce-post-item .dce-hover-effect-content.dce-close' => 'animation-name: {{VALUE}}; -webkit-animation-name: {{VALUE}};']]);
        $this->add_control('hover_text_effect_timingFunction_out', ['label' => __('Effect Timing function', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SELECT, 'groups' => Helper::get_anim_timing_functions(), 'default' => 'ease-in-out', 'selectors' => ['{{WRAPPER}} .dce-post-item .dce-hover-effect-content.dce-close' => 'animation-timing-function: {{VALUE}}; -webkit-animation-timing-function: {{VALUE}};'], 'condition' => ['hover_text_effect' => 'cssanimations', 'style_items' => 'float']]);
        $this->add_control('hover_text_effect_speed_out', ['label' => __('Speed (sec.)', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::NUMBER, 'default' => 0.5, 'min' => 0.1, 'max' => 2, 'step' => 0.1, 'dynamic' => ['active' => \false], 'selectors' => ['{{WRAPPER}} .dce-post-item .dce-hover-effect-content.dce-close' => 'animation-duration: {{VALUE}}s; -webkit-animation-duration: {{VALUE}}s;'], 'condition' => ['hover_text_effect' => 'cssanimations', 'style_items' => 'float']]);
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }
    protected function register_pagination_controls()
    {
        $this->start_controls_section('section_pagination', ['label' => __('Pagination', 'dynamic-content-for-elementor'), 'tab' => Controls_Manager::TAB_CONTENT, 'condition' => ['pagination_enable' => 'yes', 'infiniteScroll_enable' => '', 'post_offset' => [0, ''], '_skin' => ['', 'grid', 'grid-filters', 'gridtofullscreen3d']]]);
        $this->add_control('pagination_position', ['label' => __('Position', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SELECT, 'options' => ['top' => __('Top', 'dynamic-content-for-elementor'), 'bottom' => __('Bottom', 'dynamic-content-for-elementor'), 'both' => __('Both', 'dynamic-content-for-elementor')], 'default' => 'bottom']);
        $this->add_control('pagination_show_numbers', ['label' => __('Show Numbers', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes']);
        $this->add_control('pagination_range', ['label' => __('Range of numbers', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::NUMBER, 'default' => 4, 'condition' => ['pagination_show_numbers' => 'yes']]);
        $this->add_control('pagination_show_prevnext', ['label' => __('Show Prev/Next', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'separator' => 'before']);
        $this->add_control('pagination_icon_prevnext', ['label' => __('Icon Prev/Next', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::ICON, 'default' => 'fa fa-long-arrow-right', 'include' => ['fa fa-arrow-right', 'fa fa-angle-right', 'fa fa-chevron-circle-right', 'fa fa-caret-square-o-right', 'fa fa-chevron-right', 'fa fa-caret-right', 'fa fa-angle-double-right', 'fa fa-hand-o-right', 'fa fa-arrow-circle-right', 'fa fa-long-arrow-right', 'fa fa-arrow-circle-o-right'], 'condition' => ['pagination_show_prevnext' => 'yes']]);
        $this->add_control('pagination_prev_label', ['label' => __('Previous Label', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::TEXT, 'default' => __('Previous', 'dynamic-content-for-elementor'), 'condition' => ['pagination_show_prevnext' => 'yes']]);
        $this->add_control('pagination_next_label', ['label' => __('Next Label', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::TEXT, 'default' => __('Next', 'dynamic-content-for-elementor'), 'condition' => ['pagination_show_prevnext' => 'yes']]);
        $this->add_control('pagination_show_firstlast', ['label' => __('Show First/Last', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'separator' => 'before']);
        $this->add_control('pagination_icon_firstlast', ['label' => __('Icon First/Last', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::ICON, 'default' => 'fa fa-long-arrow-right', 'include' => ['fa fa-arrow-right', 'fa fa-angle-right', 'fa fa-chevron-circle-right', 'fa fa-caret-square-o-right', 'fa fa-chevron-right', 'fa fa-caret-right', 'fa fa-angle-double-right', 'fa fa-hand-o-right', 'fa fa-arrow-circle-right', 'fa fa-long-arrow-right', 'fa fa-arrow-circle-o-right'], 'condition' => ['pagination_show_firstlast' => 'yes']]);
        $this->add_control('pagination_first_label', ['label' => __('Previous Label', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::TEXT, 'default' => __('First', 'dynamic-content-for-elementor'), 'condition' => ['pagination_show_firstlast' => 'yes']]);
        $this->add_control('pagination_last_label', ['label' => __('Next Label', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::TEXT, 'default' => __('Last', 'dynamic-content-for-elementor'), 'condition' => ['pagination_show_firstlast' => 'yes']]);
        $this->add_control('pagination_show_progression', ['label' => __('Show Progression', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'separator' => 'before']);
        $this->end_controls_section();
    }
    protected function register_infinitescroll_controls()
    {
        $this->start_controls_section('section_infinitescroll', ['label' => __('Infinite Scroll', 'dynamic-content-for-elementor'), 'tab' => Controls_Manager::TAB_CONTENT, 'condition' => ['pagination_enable' => 'yes', 'infiniteScroll_enable' => 'yes']]);
        $this->add_control('infiniteScroll_trigger', ['label' => __('Trigger', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SELECT, 'default' => 'scroll', 'frontend_available' => \true, 'options' => ['scroll' => __('On Scroll Page', 'dynamic-content-for-elementor'), 'button' => __('On Click Button', 'dynamic-content-for-elementor')]]);
        $this->add_control('infiniteScroll_label_button', ['label' => __('Label Button', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::TEXT, 'default' => __('View more', 'dynamic-content-for-elementor'), 'condition' => ['infiniteScroll_trigger' => 'button']]);
        $this->add_control('infiniteScroll_enable_status', ['label' => __('Enable Status', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'separator' => 'before']);
        $this->add_control('infiniteScroll_loading_type', ['label' => __('Loading Type', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::CHOOSE, 'toggle' => \false, 'options' => ['ellips' => ['title' => __('Ellips', 'dynamic-content-for-elementor'), 'icon' => 'fa fa-ellipsis-h'], 'text' => ['title' => __('Label Text', 'dynamic-content-for-elementor'), 'icon' => 'fa fa-font']], 'default' => 'ellips', 'separator' => 'before', 'condition' => ['infiniteScroll_enable_status' => 'yes']]);
        $this->add_control('infiniteScroll_label_loading', ['label' => __('Label Loading', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::TEXT, 'default' => __('Loading...', 'dynamic-content-for-elementor'), 'condition' => ['infiniteScroll_enable_status' => 'yes', 'infiniteScroll_loading_type' => 'text']]);
        $this->add_control('infiniteScroll_label_last', ['label' => __('Label Last', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::TEXT, 'default' => __('End of content', 'dynamic-content-for-elementor'), 'condition' => ['infiniteScroll_enable_status' => 'yes']]);
        $this->add_control('infiniteScroll_label_error', ['label' => __('Label Error', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::TEXT, 'default' => __('No more articles to load', 'dynamic-content-for-elementor'), 'condition' => ['infiniteScroll_enable_status' => 'yes']]);
        $this->add_control('infiniteScroll_enable_history', ['label' => __('Enable History', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SWITCHER, 'separator' => 'before', 'frontend_available' => \true]);
        $this->end_controls_section();
    }
    protected function register_query_controls()
    {
        $taxonomies = Helper::get_taxonomies();
        $sql_operators = Helper::get_sql_operators();
        $this->start_controls_section('section_query', ['label' => __('Query', 'dynamic-content-for-elementor')]);
        $this->add_control('query_type', ['label' => __('Query Type', 'dynamic-content-for-elementor'), 'type' => 'images_selector', 'toggle' => \false, 'type_selector' => 'icon', 'columns_grid' => 5, 'separator' => 'before', 'options' => ['get_cpt' => ['title' => __('From Post Type', 'dynamic-content-for-elementor'), 'return_val' => 'val', 'icon' => 'eicon-post-content'], 'dynamic_mode' => ['title' => __('Dynamic - Current Query', 'dynamic-content-for-elementor'), 'return_val' => 'val', 'icon' => 'fa fa-cogs'], 'relationship' => ['title' => __('ACF Relationship', 'dynamic-content-for-elementor'), 'return_val' => 'val', 'icon' => 'fa fa-american-sign-language-interpreting'], 'pods_relationship' => ['title' => __('Pods Relationship', 'dynamic-content-for-elementor'), 'return_val' => 'val', 'icon' => 'icon-dyn-relation'], 'search_filter' => ['title' => 'Search & Filter Pro', 'return_val' => 'val', 'icon' => 'icon-dyn-search-filter'], 'post_parent' => ['title' => __('From Post Parent', 'dynamic-content-for-elementor'), 'return_val' => 'val', 'icon' => 'fa fa-sitemap'], 'search_page' => ['title' => __('Search Page', 'dynamic-content-for-elementor'), 'return_val' => 'val', 'icon' => 'fa fa-search'], 'specific_posts' => ['title' => __('From Specific Posts', 'dynamic-content-for-elementor'), 'return_val' => 'val', 'icon' => 'fa fa-list-ul'], 'id_list' => ['title' => __('ID List', 'dynamic-content-for-elementor'), 'return_val' => 'val', 'icon' => 'fa fa-clipboard-list'], 'sticky_posts' => ['title' => __('Sticky Posts', 'dynamic-content-for-elementor'), 'return_val' => 'val', 'icon' => 'eicon-star'], 'custom_query' => ['title' => __('Custom Query Code', 'dynamic-content-for-elementor'), 'return_val' => 'val', 'icon' => 'eicon-editor-code']], 'default' => 'get_cpt']);
        $this->add_control('specific_page_parent', ['label' => __('Show children from this parent-page', 'dynamic-content-for-elementor'), 'type' => 'ooo_query', 'placeholder' => __('Page Title', 'dynamic-content-for-elementor'), 'label_block' => \true, 'query_type' => 'posts', 'condition' => ['query_type' => 'post_parent', 'parent_source' => '', 'child_source' => '']]);
        $this->add_control('dynamic_parent_heading', ['label' => __('Dynamic', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::HEADING, 'separator' => 'before', 'condition' => ['query_type' => 'post_parent']]);
        $this->add_control('parent_source', ['label' => __('My Siblings', 'dynamic-content-for-elementor'), 'description' => __('I take the post parent and I get my siblings out of myself.', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SWITCHER, 'label_on' => __('Same', 'dynamic-content-for-elementor'), 'label_off' => __('other', 'dynamic-content-for-elementor'), 'condition' => ['query_type' => 'post_parent']]);
        $this->add_control('child_source', ['label' => __('My Children', 'dynamic-content-for-elementor'), 'description' => __('Compared to myself, I\'ll retrieve my children.', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SWITCHER, 'label_on' => __('Same', 'dynamic-content-for-elementor'), 'label_off' => __('other', 'dynamic-content-for-elementor'), 'condition' => ['query_type' => 'post_parent', 'parent_source' => '']]);
        // --------------------------------- [ Specific Posts-Pages ]
        $repeater = new Repeater();
        $repeater->add_control('repeater_specific_posts', ['label' => __('Select Post', 'dynamic-content-for-elementor'), 'type' => 'ooo_query', 'show_label' => \false, 'placeholder' => __('Select post', 'dynamic-content-for-elementor'), 'label_block' => \true, 'query_type' => 'posts']);
        $this->add_control('specific_posts', ['label' => __('Specific Posts', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::REPEATER, 'prevent_empty' => \false, 'default' => [], 'separator' => 'after', 'fields' => $repeater->get_controls(), 'title_field' => 'ID: {{{ repeater_specific_posts }}}', 'condition' => ['query_type' => 'specific_posts']]);
        if (Helper::is_searchandfilterpro_active()) {
            $this->add_control('search_filter_id', ['label' => __('Filter', 'dynamic-content-for-elementor'), 'type' => 'ooo_query', 'label_block' => \true, 'placeholder' => __('Select the filter', 'dynamic-content-for-elementor'), 'query_type' => 'posts', 'object_type' => 'search-filter-widget', 'condition' => ['query_type' => 'search_filter']]);
        } else {
            $this->add_control('search_filter_notice', ['type' => Controls_Manager::RAW_HTML, 'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning', 'raw' => __('Combine the power of Search & Filter Pro front end filters with Dynamic Posts v2! Create front end search forms and filter Dynamic Posts v2 layouts using the advanced query and filter builder of Search & Filter Pro. Note: In order to use this feature you need install Search & Filter Pro. Search & Filter Pro is a premium product - you can <a href="https://searchandfilter.com">get it here</a>.', 'dynamic-content-for-elementor'), 'condition' => ['query_type' => 'search_filter']]);
        }
        $this->add_control('id_list', ['label' => __('ID List', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::TEXT, 'label_block' => \true, 'description' => __('Type a comma-separated list of ids (e.g. 1, 100, 250)', 'dynamic-content-for-elementor'), 'dynamic' => ['active' => \true], 'condition' => ['query_type' => 'id_list']]);
        $this->add_control('favorites_scope', ['label' => __('Favorites from', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::CHOOSE, 'description' => __('Set here the scope you used in the widget "Add to Favorites"', 'dynamic-content-for-elementor'), 'options' => ['cookie' => ['title' => __('Cookie', 'dynamic-content-for-elementor'), 'icon' => 'icon-dyn-cookie'], 'user' => ['title' => __('User', 'dynamic-content-for-elementor'), 'icon' => 'fa fa-user'], 'global' => ['title' => __('Global', 'dynamic-content-for-elementor'), 'icon' => 'fa fa-globe']], 'toggle' => \false, 'default' => 'user', 'condition' => ['query_type' => 'favorites']]);
        $this->add_control('favorites_key', ['label' => __('Favorites Key', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::TEXT, 'label_block' => \true, 'description' => __('Set here the key you used in the widget "Add to Favorites"', 'dynamic-content-for-elementor'), 'dynamic' => ['active' => \true], 'condition' => ['query_type' => 'favorites']]);
        if (\DynamicContentForElementor\Helper::can_register_unsafe_controls()) {
            $this->add_control('custom_query_code', ['label' => __('Custom Query Code', 'dynamic-content-for-elementor'), 'description' => __('Here you should return a valid list of arguments for the WP_Query', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::CODE, 'language' => 'php', 'rows' => 10, 'default' => "return array ( 'post_type' => 'any' );", 'label_block' => \true, 'condition' => ['query_type' => 'custom_query']]);
        } else {
            $this->add_control('custom_query_notice', ['type' => Controls_Manager::RAW_HTML, 'raw' => __('You will need administrator capabilities to edit these settings.', 'dynamic-content-for-elementor'), 'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning', 'condition' => ['query_type' => 'custom_query']]);
        }
        if (!Helper::is_woocommerce_active()) {
            $this->add_control('products_notice', ['type' => Controls_Manager::RAW_HTML, 'raw' => __('In order to use this feature you need install WooCommerce.', 'dynamic-content-for-elementor'), 'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning', 'condition' => ['query_type' => ['products_cart', 'product_upsells', 'product_crosssells']]]);
        }
        // --------------------------------- [ META relationship ]
        $this->add_control('relationship_meta', ['label' => __('ACF Relationship field', 'dynamic-content-for-elementor'), 'type' => 'ooo_query', 'placeholder' => __('Select the field', 'dynamic-content-for-elementor'), 'label_block' => \true, 'query_type' => 'acf', 'dynamic' => ['active' => \false], 'object_type' => 'post_object,relationship', 'condition' => ['query_type' => 'relationship']]);
        $this->add_control('relationship_invert', ['label' => __('Invert direction', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SWITCHER, 'description' => __('For bidirectional relationships, retrieve all posts that are associated with the current post', 'dynamic-content-for-elementor'), 'condition' => ['query_type' => 'relationship']]);
        if (Helper::is_pods_active()) {
            $this->add_control('pods_relationship_field', ['label' => __('PODS Relationship field', 'dynamic-content-for-elementor'), 'type' => 'ooo_query', 'placeholder' => __('Select the field', 'dynamic-content-for-elementor'), 'label_block' => \true, 'query_type' => 'pods', 'object_type' => 'relationship', 'default' => '0', 'condition' => ['query_type' => 'pods_relationship']]);
        } else {
            $this->add_control('pods_notice', ['label' => __('Important Note', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::RAW_HTML, 'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning', 'raw' => __('In order to use this feature you need install PODS. You can <a href="https://pods.io">download it free here</a>.', 'dynamic-content-for-elementor'), 'condition' => ['query_type' => 'pods_relationship']]);
        }
        // --------------------------------- [ Custom Post Type ]
        $this->add_control('post_type', ['label' => __('Post Type', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SELECT2, 'options' => Helper::get_post_types(), 'multiple' => \true, 'label_block' => \true, 'default' => [], 'condition' => ['query_type' => ['get_cpt', 'search_page', 'sticky_posts']]]);
        $this->add_control('post_status', ['label' => __('Post Status', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SELECT2, 'options' => get_post_statuses(), 'multiple' => \true, 'label_block' => \true, 'default' => [], 'condition' => ['query_type' => ['get_cpt', 'dynamic_mode', 'favorites']]]);
        $this->add_control('ignore_sticky_posts', ['label' => __('Ignore Sticky Posts', 'dynamic-content-for-elementor'), 'description' => __('Ignores that a post is sticky and shows the posts in the normal order. Your sticky posts will appear in the loop, however they will not be placed on the top', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'condition' => ['query_type' => ['get_cpt', 'dynamic_mode', 'favorites'], 'remove_sticky_posts' => '']]);
        $this->add_control('remove_sticky_posts', ['label' => __('Remove Sticky Posts from the loop', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SWITCHER, 'default' => '', 'condition' => ['query_type' => ['get_cpt', 'dynamic_mode']]]);
        $this->add_control('num_posts', ['label' => __('Results per page', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::NUMBER, 'separator' => 'before', 'default' => '10', 'condition' => ['query_type' => ['get_cpt', 'relationship', 'dynamic_mode', 'pods_relationship', 'search_page', 'post_parent', 'sticky_posts', 'favorites', 'products_cart', 'product_upsells', 'product_crosssells']]]);
        $this->add_control('num_posts_notice', ['type' => Controls_Manager::RAW_HTML, 'raw' => __('If you use pagination in Query Type "Dynamic - Current Query" the number of results per page should match the value you set in "Settings > Reading > Blog pages show at most". You have set the value', 'dynamic-content-for-elementor') . ' ' . get_option('posts_per_page'), 'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning', 'separator' => 'before', 'condition' => ['query_type' => ['dynamic_mode']]]);
        $this->add_control('post_offset', ['label' => __('Posts Offset', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::NUMBER, 'description' => __('Warning: posts offset doesn\'t support pagination', 'dynamic-content-for-elementor'), 'default' => 0, 'condition' => ['query_type' => ['get_cpt', 'dynamic_mode', 'sticky_posts', 'favorites'], 'num_posts!' => '-1']]);
        $this->add_control('orderby', ['label' => __('Order By', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SELECT, 'options' => Helper::get_post_orderby_options(), 'default' => 'date', 'condition' => ['query_type!' => ['search_filter', 'custom_query']]]);
        $this->add_control('metakey', ['label' => __('Meta Field', 'dynamic-content-for-elementor'), 'type' => 'ooo_query', 'placeholder' => __('Meta key', 'dynamic-content-for-elementor'), 'label_block' => \true, 'query_type' => 'metas', 'object_type' => 'post', 'separator' => 'after', 'dynamic' => ['active' => \false], 'condition' => ['orderby' => ['meta_value_date', 'meta_value_num', 'meta_value']]]);
        $this->add_control('order', ['label' => __('Order', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SELECT, 'options' => ['ASC' => __('Ascending', 'dynamic-content-for-elementor'), 'DESC' => __('Descending', 'dynamic-content-for-elementor')], 'default' => 'DESC', 'condition' => ['query_type!' => ['search_filter', 'custom_query'], 'orderby!' => 'random']]);
        // --------------------------------- [ Posts Exclusion ]
        $this->add_control('heading_query_options', ['label' => __('Exclude', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::HEADING, 'separator' => 'before', 'condition' => ['query_type' => ['get_cpt', 'dynamic_mode', 'search_page', 'sticky_posts']]]);
        $this->add_control('exclude_io', ['label' => __('Current Post', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'condition' => ['query_type' => ['get_cpt', 'dynamic_mode', 'sticky_posts']]]);
        $this->add_control('exclude_page_parent', ['label' => __('Page parent', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SWITCHER, 'condition' => ['query_type' => ['get_cpt', 'dynamic_mode', 'sticky_posts']]]);
        $this->add_control('exclude_posts', ['label' => __('Specific Posts', 'dynamic-content-for-elementor'), 'type' => 'ooo_query', 'placeholder' => __('Post Title', 'dynamic-content-for-elementor'), 'label_block' => \true, 'query_type' => 'posts', 'multiple' => \true, 'condition' => ['query_type' => ['get_cpt', 'dynamic_mode', 'search_page', 'sticky_posts']]]);
        $this->end_controls_section();
        // ------------------------------------------------------------------ [SECTION QUERY-FILTER]
        $this->start_controls_section('section_query_filter', ['label' => __('Query Filter', 'dynamic-content-for-elementor'), 'condition' => ['query_type' => ['get_cpt', 'dynamic_mode', 'search_page', 'relationship', 'id_list', 'sticky_posts']]]);
        $this->add_control('query_filter', ['label' => __('By', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SELECT2, 'options' => ['date' => __('Date', 'dynamic-content-for-elementor'), 'term' => __('Terms & Taxonomy', 'dynamic-content-for-elementor'), 'author' => __('Author', 'dynamic-content-for-elementor'), 'metakey' => __('Metakey', 'dynamic-content-for-elementor')], 'multiple' => \true, 'label_block' => \true, 'default' => []]);
        // +********************* Date
        $this->add_control('heading_query_filter_date', ['type' => Controls_Manager::RAW_HTML, 'show_label' => \false, 'raw' => __('Date Filters', 'dynamic-content-for-elementor'), 'label_block' => \false, 'separator' => 'before', 'content_classes' => 'dce-icon-heading', 'condition' => ['query_filter' => 'date']]);
        $this->add_control('querydate_mode', ['label' => __('Date Filter', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SELECT, 'default' => '', 'label_block' => \true, 'options' => ['' => __('No Filter', 'dynamic-content-for-elementor'), 'past' => __('Past', 'dynamic-content-for-elementor'), 'future' => __('Future', 'dynamic-content-for-elementor'), 'today' => __('Today', 'dynamic-content-for-elementor'), 'yesterday' => __('Yesterday', 'dynamic-content-for-elementor'), 'days' => __('Past Days', 'dynamic-content-for-elementor'), 'weeks' => __('Past Weeks', 'dynamic-content-for-elementor'), 'months' => __('Past Months', 'dynamic-content-for-elementor'), 'years' => __('Past Years', 'dynamic-content-for-elementor'), 'period' => __('Period', 'dynamic-content-for-elementor')], 'condition' => ['query_filter' => 'date']]);
        $this->add_control('querydate_field', ['label' => __('Date Field', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::CHOOSE, 'label_block' => \false, 'options' => ['post_date' => ['title' => __('Publish Date', 'dynamic-content-for-elementor'), 'icon' => 'fa fa-calendar'], 'post_modified' => ['title' => __('Modified Date', 'dynamic-content-for-elementor'), 'icon' => 'fa fa-edit'], 'post_meta' => ['title' => __('Post Meta', 'dynamic-content-for-elementor'), 'icon' => 'fa fa-square']], 'default' => 'post_date', 'toggle' => \false, 'condition' => ['query_filter' => 'date', 'querydate_mode!' => ['', 'future']]]);
        $this->add_control('querydate_field_meta', ['label' => __('Meta Field', 'dynamic-content-for-elementor'), 'type' => 'ooo_query', 'placeholder' => __('Meta key or Name', 'dynamic-content-for-elementor'), 'label_block' => \true, 'query_type' => 'metas', 'object_type' => 'post', 'description' => __('Selected Post Meta value must be stored if format "Ymd", like ACF Date', 'dynamic-content-for-elementor'), 'separator' => 'before', 'dynamic' => ['active' => \false], 'condition' => ['query_filter' => 'date', 'querydate_mode!' => 'future', 'querydate_field' => 'post_meta']]);
        $this->add_control('querydate_field_meta_format', ['label' => __('Meta Date Format', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::TEXT, 'placeholder' => __('Ymd', 'dynamic-content-for-elementor'), 'label_block' => \true, 'default' => __('Ymd', 'dynamic-content-for-elementor'), 'condition' => ['query_filter' => 'date', 'querydate_mode!' => 'future', 'querydate_field' => 'post_meta']]);
        $this->add_control('querydate_field_meta_future', ['label' => __('Meta Field', 'dynamic-content-for-elementor'), 'type' => 'ooo_query', 'placeholder' => __('Meta key or Name', 'dynamic-content-for-elementor'), 'label_block' => \true, 'query_type' => 'metas', 'object_type' => 'post', 'description' => __('Selected Post Meta value must be stored if format "Ymd", like ACF Date', 'dynamic-content-for-elementor'), 'separator' => 'before', 'dynamic' => ['active' => \false], 'condition' => ['query_filter' => 'date', 'querydate_mode' => 'future']]);
        $this->add_control('querydate_field_meta_future_format', ['label' => __('Meta Date Format', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::TEXT, 'placeholder' => __('Y-m-d', 'dynamic-content-for-elementor'), 'label_block' => \false, 'default' => __('Ymd', 'dynamic-content-for-elementor'), 'condition' => ['query_filter' => 'date', 'querydate_mode' => 'future']]);
        $this->add_control('querydate_field_meta_future_contains_today', ['label' => __('Future dates contains today\'s date', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'condition' => ['query_filter' => 'date', 'querydate_mode' => 'future']]);
        $this->add_control('querydate_range', ['label' => __('Number of (days/months/years) elapsed', 'dynamic-content-for-elementor'), 'label_block' => \false, 'type' => Controls_Manager::NUMBER, 'default' => 1, 'condition' => ['query_filter' => 'date', 'querydate_mode' => ['days', 'weeks', 'months', 'years']]]);
        $this->add_control('querydate_date_type', ['label' => __('Date Input Mode', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::CHOOSE, 'label_block' => \true, 'options' => ['static' => ['title' => __('Static', 'dynamic-content-for-elementor'), 'icon' => 'eicon-pencil'], 'dynamicstring' => ['title' => __('Dynamic', 'dynamic-content-for-elementor'), 'icon' => 'fa fa-cogs']], 'default' => '_dynamic', 'toggle' => \false, 'separator' => 'before', 'condition' => ['query_filter' => 'date', 'querydate_mode' => 'period']]);
        $this->add_control('querydate_date_from', ['label' => __('Date from', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::DATE_TIME, 'label_block' => \false, 'condition' => ['query_filter' => 'date', 'querydate_mode' => 'period', 'querydate_date_type' => 'static']]);
        $this->add_control('querydate_date_to', ['label' => __('Date to', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::DATE_TIME, 'label_block' => \false, 'condition' => ['query_filter' => 'date', 'querydate_mode' => 'period', 'querydate_date_type' => 'static']]);
        $this->add_control('querydate_date_from_dynamic', ['label' => __('Date from', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::TEXT, 'condition' => ['query_filter' => 'date', 'querydate_mode' => 'period', 'querydate_date_type' => 'dynamicstring']]);
        $this->add_control('querydate_date_to_dynamic', ['label' => __('Date to', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::TEXT, 'condition' => ['query_filter' => 'date', 'querydate_mode' => 'period', 'querydate_date_type' => 'dynamicstring']]);
        // +********************* Term Taxonomy
        $this->add_control('heading_query_filter_term', ['type' => Controls_Manager::RAW_HTML, 'show_label' => \false, 'raw' => __('Terms & Taxonomy Filters', 'dynamic-content-for-elementor'), 'separator' => 'before', 'content_classes' => 'dce-icon-heading', 'condition' => ['query_filter' => 'term']]);
        // From Post or Meta
        $this->add_control('term_from', ['label' => __('Type', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::CHOOSE, 'label_block' => \false, 'options' => ['post_term' => ['title' => __('Select Term', 'dynamic-content-for-elementor'), 'icon' => 'fa fa-tag'], 'post_meta' => ['title' => __('Post Meta Term', 'dynamic-content-for-elementor'), 'icon' => 'fa fa-square'], 'dynamicstring' => ['title' => __('Dynamic String', 'dynamic-content-for-elementor'), 'icon' => 'fa fa-cogs']], 'default' => 'post_term', 'toggle' => \false, 'condition' => ['query_filter' => 'term']]);
        $this->add_control('taxonomy', ['label' => __('Select Taxonomy', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SELECT, 'options' => ['' => __('All', 'dynamic-content-for-elementor')] + $taxonomies, 'default' => '', 'label_block' => \true, 'condition' => ['query_filter' => 'term']]);
        // [Post Meta]
        $this->add_control('term_field_meta', ['label' => __('Post Term custom meta field', 'dynamic-content-for-elementor'), 'type' => 'ooo_query', 'placeholder' => __('Meta key or Name', 'dynamic-content-for-elementor'), 'label_block' => \true, 'query_type' => 'metas', 'object_type' => 'post', 'dynamic' => ['active' => \false], 'description' => __('Selected post meta value. The meta must return an element of type array or comma separated string that contains the term type IDs. (e.g.: array [5,27,88] or 5,27,88)', 'dynamic-content-for-elementor'), 'condition' => ['term_from' => 'post_meta', 'query_filter' => 'term']]);
        // [Post Meta String]
        $this->add_control('term_field_meta_string', ['label' => __('Post Term string field', 'dynamic-content-for-elementor'), 'description' => __('Type the Post Meta value. Type a sequence of comma-separated term IDs. (e.g.: "5,27,88")', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::TEXT, 'label_block' => \true, 'render_type' => 'template', 'default' => '', 'condition' => ['term_from' => 'dynamicstring', 'query_filter' => 'term']]);
        // [Post Term]
        foreach ($taxonomies as $tax_key => $a_tax) {
            if ($tax_key) {
                $this->add_control('include_term_' . $tax_key, ['label' => __('Include Terms for', 'dynamic-content-for-elementor') . ' ' . $a_tax, 'type' => 'ooo_query', 'placeholder' => __('All terms', 'dynamic-content-for-elementor'), 'label_block' => \true, 'query_type' => 'terms', 'object_type' => $tax_key, 'render_type' => 'template', 'multiple' => \true, 'condition' => ['taxonomy' => $tax_key, 'query_filter' => 'term', 'term_from' => 'post_term', 'terms_current_post' => '']]);
            }
        }
        $this->add_control('include_term_operator', ['label' => __('Include Operator', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SELECT, 'options' => ['AND' => 'AND', 'IN' => 'IN'], 'toggle' => \false, 'default' => 'IN', 'conditions' => ['terms' => [['name' => 'taxonomy', 'operator' => '!=', 'value' => ''], ['name' => 'term_from', 'operator' => '==', 'value' => 'post_term']]]]);
        foreach ($taxonomies as $tax_key => $a_tax) {
            if ($tax_key) {
                $this->add_control('exclude_term_' . $tax_key, ['label' => __('Exclude Term for', 'dynamic-content-for-elementor') . ' ' . $a_tax, 'type' => 'ooo_query', 'placeholder' => __('All terms', 'dynamic-content-for-elementor'), 'label_block' => \true, 'query_type' => 'terms', 'object_type' => $tax_key, 'render_type' => 'template', 'multiple' => \true, 'condition' => ['taxonomy' => $tax_key, 'query_filter' => 'term', 'term_from' => 'post_term', 'terms_current_post' => '']]);
            }
        }
        $this->add_control('terms_current_post', ['label' => __('Dynamic Current Post Terms', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SWITCHER, 'description' => __('Filter results by taxonomy terms associated to the current post', 'dynamic-content-for-elementor'), 'separator' => 'before', 'condition' => ['taxonomy!' => '', 'query_filter' => 'term', 'term_from' => 'post_term']]);
        // Author
        $this->add_control('heading_query_filter_author', ['type' => Controls_Manager::RAW_HTML, 'show_label' => \false, 'raw' => __(' Author Filters', 'dynamic-content-for-elementor'), 'separator' => 'before', 'content_classes' => 'dce-icon-heading', 'condition' => ['query_filter' => 'author']]);
        // From, Post, Meta or Current
        $this->add_control('author_from', ['label' => __('Type', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::CHOOSE, 'label_block' => \false, 'options' => ['post_author' => ['title' => __('Select Author', 'dynamic-content-for-elementor'), 'icon' => 'fa fa-users'], 'current_author' => ['title' => __('Current author', 'dynamic-content-for-elementor'), 'icon' => 'eicon-edit'], 'current_user' => ['title' => __('Current user', 'dynamic-content-for-elementor'), 'icon' => 'fa fa-user']], 'default' => 'post_author', 'toggle' => \false, 'condition' => ['query_filter' => 'author']]);
        // Post Meta
        $this->add_control('author_field_meta', ['label' => __('Post author custom meta field', 'dynamic-content-for-elementor'), 'type' => 'ooo_query', 'placeholder' => __('Meta key or Name', 'dynamic-content-for-elementor'), 'label_block' => \true, 'query_type' => 'metas', 'object_type' => 'post', 'default' => 'nickname', 'dynamic' => ['active' => \false], 'description' => __('Selected Post Meta value. The meta must return an element of type array or comma separated string containing author IDs. (eg: array [5,27,88] or 5,27,88)', 'dynamic-content-for-elementor'), 'condition' => ['author_from' => 'post_meta', 'query_filter' => 'author']]);
        // Post Meta String
        $this->add_control('author_field_meta_string', ['label' => __('Post Author string field', 'dynamic-content-for-elementor'), 'description' => __('Type the Post Meta value. Type a sequence of author IDs separated by commas. (eg: 5,27,88)', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::TEXT, 'label_block' => \true, 'render_type' => 'template', 'default' => '', 'condition' => ['author_from' => 'dynamicstring', 'query_filter' => 'author']]);
        // Select Authors
        $this->add_control('include_author', ['label' => __('Include Author', 'dynamic-content-for-elementor'), 'type' => 'ooo_query', 'placeholder' => __('Select author', 'dynamic-content-for-elementor'), 'label_block' => \true, 'multiple' => \true, 'query_type' => 'users', 'description' => __('Filter posts by selected Authors', 'dynamic-content-for-elementor'), 'condition' => ['query_filter' => 'author', 'author_from' => 'post_author']]);
        $this->add_control('exclude_author', ['label' => __('Exclude Author', 'dynamic-content-for-elementor'), 'type' => 'ooo_query', 'placeholder' => __('No', 'dynamic-content-for-elementor'), 'label_block' => \true, 'multiple' => \true, 'query_type' => 'users', 'description' => __('Filter posts by selected Authors', 'dynamic-content-for-elementor'), 'separator' => 'after', 'condition' => ['query_filter' => 'author', 'author_from' => 'post_author']]);
        // Meta key
        $this->add_control('heading_query_filter_metakey', ['type' => Controls_Manager::RAW_HTML, 'show_label' => \false, 'raw' => __('Metakey Filters', 'dynamic-content-for-elementor'), 'separator' => 'before', 'content_classes' => 'dce-icon-heading', 'condition' => ['query_filter' => 'metakey']]);
        // From Post or Meta
        $this->add_control('metakey_from', ['label' => __('Type', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::CHOOSE, 'label_block' => \false, 'options' => ['post_metakey' => ['title' => __('Select Metakey', 'dynamic-content-for-elementor'), 'icon' => 'fa fa-key'], 'post_meta' => ['title' => __('Post Meta Key', 'dynamic-content-for-elementor'), 'icon' => 'fa fa-square']], 'default' => 'post_metakey', 'toggle' => \false, 'condition' => ['query_filter' => 'metakey']]);
        // Post Meta
        $this->add_control('metakey_field_meta', ['label' => __('Post Metakey custom meta field', 'dynamic-content-for-elementor'), 'type' => 'ooo_query', 'placeholder' => __('Meta key or name', 'dynamic-content-for-elementor'), 'label_block' => \true, 'query_type' => 'metas', 'object_type' => 'post', 'dynamic' => ['active' => \false], 'condition' => ['metakey_from' => 'post_meta', 'query_filter' => 'metakey']]);
        $this->add_control('metakey_field_meta_operator', ['label' => __('Operator', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SELECT, 'options' => $sql_operators, 'default' => '=', 'condition' => ['metakey_from' => 'post_meta', 'query_filter' => 'metakey']]);
        $this->add_control('metakey_field_meta_value', ['label' => __('Value', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::TEXT, 'condition' => ['metakey_from' => 'post_meta', 'query_filter' => 'metakey']]);
        // [Post Meta String]
        $this->add_control('metakey_field_meta_string', ['label' => __('Post Metakey string field', 'dynamic-content-for-elementor'), 'description' => __('Type the Post Meta value. Type a sequence of metakey-type IDs separated by commas. (e.g.:"5,27,88")', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::TEXT, 'label_block' => \true, 'render_type' => 'template', 'default' => '', 'condition' => ['metakey_from' => 'dynamicstring', 'query_filter' => 'metakey']]);
        // [Post Term]
        $this->add_control('include_metakey', ['label' => __('Include Metakey', 'dynamic-content-for-elementor'), 'type' => 'ooo_query', 'placeholder' => __('All metakeys', 'dynamic-content-for-elementor'), 'label_block' => \true, 'query_type' => 'metakeys', 'description' => __('Filter results by selected metakey', 'dynamic-content-for-elementor'), 'render_type' => 'template', 'dynamic' => ['active' => \false], 'multiple' => \true, 'condition' => ['query_filter' => 'metakey', 'metakey_from' => 'post_metakey']]);
        $this->add_control('include_metakey_combination', ['label' => __('Include Combination', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SELECT, 'options' => ['AND' => 'AND', 'OR' => 'OR'], 'toggle' => \false, 'default' => 'OR', 'conditions' => ['terms' => [['name' => 'query_filter', 'operator' => 'contains', 'value' => 'metakey'], ['name' => 'query_filter', 'operator' => '!=', 'value' => []], ['name' => 'include_metakey', 'operator' => '!=', 'value' => ''], ['name' => 'include_metakey', 'operator' => '!=', 'value' => []], ['name' => 'metakey_from', 'value' => 'post_metakey']]]]);
        $this->add_control('exclude_metakey', ['label' => __('Exclude Metakey', 'dynamic-content-for-elementor'), 'type' => 'ooo_query', 'placeholder' => __('All metakeys', 'dynamic-content-for-elementor'), 'label_block' => \true, 'query_type' => 'metakeys', 'description' => __('Filter results by selected metakey', 'dynamic-content-for-elementor'), 'render_type' => 'template', 'dynamic' => ['active' => \false], 'multiple' => \true, 'condition' => ['query_filter' => 'metakey', 'metakey_from' => 'post_metakey']]);
        $this->add_control('exclude_metakey_combination', ['label' => __('Exclude Combination', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SELECT, 'options' => ['AND' => 'AND', 'OR' => 'OR'], 'toggle' => \false, 'default' => 'OR', 'conditions' => ['terms' => [['name' => 'query_filter', 'operator' => 'contains', 'value' => 'metakey'], ['name' => 'query_filter', 'operator' => '!=', 'value' => []], ['name' => 'exclude_metakey', 'operator' => '!=', 'value' => ''], ['name' => 'exclude_metakey', 'operator' => '!=', 'value' => []], ['name' => 'metakey_from', 'value' => 'post_metakey']]]]);
        $this->end_controls_section();
        // FALLBACK for NO RESULTS
        $this->start_controls_section('section_fallback', ['label' => __('No Results Behaviour', 'dynamic-content-for-elementor')]);
        $this->add_control('fallback', ['label' => __('Fallback Content', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SWITCHER, 'description' => __('If you want to show something when no elements are found.', 'dynamic-content-for-elementor')]);
        $this->add_control('fallback_type', ['label' => __('Content type', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::CHOOSE, 'options' => ['text' => ['title' => __('Text', 'dynamic-content-for-elementor'), 'icon' => 'fa fa-align-left'], 'template' => ['title' => __('Template', 'dynamic-content-for-elementor'), 'icon' => 'fa fa-th-large']], 'toggle' => \false, 'default' => 'text', 'condition' => ['fallback!' => '']]);
        $this->add_control('fallback_template', ['label' => __('Render Template', 'dynamic-content-for-elementor'), 'type' => 'ooo_query', 'placeholder' => __('Template Name', 'dynamic-content-for-elementor'), 'label_block' => \true, 'query_type' => 'posts', 'object_type' => 'elementor_library', 'condition' => ['fallback!' => '', 'fallback_type' => 'template']]);
        $this->add_control('fallback_text', ['label' => __('Text Fallback', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::WYSIWYG, 'default' => __('No results found.', 'dynamic-content-for-elementor'), 'description' => __('Type here your content, you can use HTML and Tokens.', 'dynamic-content-for-elementor'), 'condition' => ['fallback!' => '', 'fallback_type' => 'text']]);
        $this->end_controls_section();
    }
    public function render()
    {
        $this->add_render_attribute('_wrapper', 'class', 'dce-dynamic-posts-collection');
        $is_imagemask = $this->get_settings('imagemask_popover');
        if ($is_imagemask) {
            $mask_shape_type = $this->get_settings('mask_shape_type');
            $this->render_svg_mask($mask_shape_type);
        }
    }
    public function query_posts()
    {
        $settings = $this->get_settings_for_display();
        if (empty($settings)) {
            return;
        }
        $post_type = $settings['post_type'];
        $id_page = Helper::get_the_id();
        $type_page = get_post_type();
        $args = [];
        $wishlist = [];
        $taxquery = [];
        $exclude_current_post = [];
        $sticky_posts_to_remove = [];
        $posts_excluded = [];
        $use_parent_page = [];
        $terms_query = 'all';
        $terms_query_excluded = [];
        $post_status = '';
        if (empty($settings['post_status'])) {
            $post_status = 'publish';
        } else {
            $post_status = $settings['post_status'];
        }
        if (is_singular()) {
            if ($settings['exclude_io']) {
                $exclude_current_post = [$id_page];
            }
        } elseif (is_home() || is_archive()) {
            $exclude_current_post = [];
        }
        if ($settings['exclude_posts']) {
            $posts_excluded = $settings['exclude_posts'];
        }
        if ($settings['exclude_page_parent']) {
            $use_parent_page = [0];
        } else {
            $use_parent_page = [];
        }
        // Ignore Sticky Posts
        if ($settings['ignore_sticky_posts']) {
            $args['ignore_sticky_posts'] = '1';
        }
        // Remove Sticky Posts
        if ($settings['remove_sticky_posts']) {
            $sticky_posts_to_remove = get_option('sticky_posts');
        }
        // Query Type - Search Page
        if ($settings['query_type'] == 'search_page') {
            if (empty($post_type)) {
                $post_type[] = 'any';
            }
            if (is_search()) {
                $args = \array_merge($args, ['s' => sanitize_text_field($_GET['s']), 'post_type' => $post_type, 'order' => $settings['order'], 'orderby' => $settings['orderby'], 'meta_key' => $settings['metakey'], 'posts_per_page' => $settings['num_posts'] ?? get_option('posts_per_page'), 'post__not_in' => $posts_excluded, 'post_parent__not_in' => $use_parent_page]);
            }
        } elseif ($settings['query_type'] == 'dynamic_mode') {
            // Query Type - Dynamic
            $array_taxquery = [];
            $taxonomy_list = [];
            if (is_archive()) {
                $queried_object = get_queried_object();
                if (is_tax() || is_category() || is_tag()) {
                    $taxonomy_list[0] = $queried_object->taxonomy;
                }
            } elseif (is_single()) {
                $taxonomy_list = get_post_taxonomies($id_page);
            }
            if (!empty($taxonomy_list)) {
                foreach ($taxonomy_list as $tax) {
                    $terms_list = [];
                    $lista_dei_termini = [];
                    if (is_single()) {
                        if ($settings['taxonomy'] == $tax) {
                            $terms_list = wp_get_post_terms($id_page, $tax, ['orderby' => 'name', 'order' => 'ASC', 'fields' => 'all', 'hide_empty' => \true]);
                        }
                        foreach ($terms_list as $term) {
                            $lista_dei_termini[] = $term->term_id;
                        }
                    } elseif (is_archive()) {
                        $lista_dei_termini[0] = $queried_object->term_id;
                    }
                    if (\count($lista_dei_termini) > 0) {
                        $array_taxquery = [];
                        if (\count($lista_dei_termini) > 1) {
                            $array_taxquery['relation'] = $settings['combination_taxonomy'];
                        }
                        foreach ($lista_dei_termini as $termine) {
                            $array_taxquery[] = ['taxonomy' => $tax, 'field' => 'id', 'terms' => $termine];
                        }
                    }
                    /* EXCLUDED */
                    if (isset($settings['terms_' . $tax . '_excluse'])) {
                        $terms_query_excluded = $settings['terms_' . $tax . '_excluse'];
                    }
                    if (!empty($terms_query_excluded)) {
                        $array_taxquery_excluded = [];
                        if (\count($terms_query_excluded) > 1) {
                            $array_taxquery_excluded['relation'] = $settings['combination_taxonomy_excluse'];
                        }
                        foreach ($terms_query_excluded as $term_query) {
                            $array_taxquery_excluded[] = ['taxonomy' => $tax, 'field' => 'term_id', 'terms' => $term_query, 'operator' => 'NOT IN'];
                        }
                        if (empty($array_taxquery)) {
                            $array_taxquery = $array_taxquery_excluded;
                        } else {
                            $array_taxquery = ['relation' => 'AND', $array_taxquery, $array_taxquery_excluded];
                        }
                    }
                }
            }
            // Se la taxQuery dinamica non da risultati uso quella statica.
            if (!$array_taxquery) {
                $array_taxquery = $taxquery;
            }
            if ('elementor_library' == $type_page) {
                $type_page = 'post';
            }
            if (!is_search()) {
                $args = \array_merge($args, ['post_type' => $type_page, 'posts_per_page' => $settings['num_posts'], 'order' => $settings['order'], 'orderby' => $settings['orderby'], 'meta_key' => $settings['metakey'], 'post__not_in' => \array_merge($posts_excluded, $exclude_current_post, $sticky_posts_to_remove), 'post_parent__not_in' => $use_parent_page, 'tax_query' => $array_taxquery, 'post_status' => $post_status]);
            }
            if (is_date()) {
                global $wp_query;
                $args['year'] = $wp_query->query_vars['year'];
                $args['monthnum'] = $wp_query->query_vars['monthnum'];
                $args['day'] = $wp_query->query_vars['day'];
            }
            if (!empty($settings['post_offset'])) {
                $args['offset'] = $settings['post_offset'];
            }
        } elseif ('get_cpt' === $settings['query_type']) {
            // Query Type - From Post Type
            $args = \array_merge($args, ['post_type' => $settings['post_type'], 'posts_per_page' => $settings['num_posts'], 'order' => $settings['order'], 'orderby' => $settings['orderby'], 'post_status' => $post_status]);
            if ($settings['metakey']) {
                $args['meta_key'] = $settings['metakey'];
            }
            $post__not_in = \array_merge($posts_excluded, $exclude_current_post, $sticky_posts_to_remove);
            if (!empty($post__not_in)) {
                $args['post__not_in'] = $post__not_in;
            }
            if (!empty($use_parent_page)) {
                $args['post_parent__not_in'] = $use_parent_page;
            }
            if (!empty($settings['post_offset'])) {
                $args['offset'] = $settings['post_offset'];
            }
        } elseif ('post_parent' === $settings['query_type']) {
            // Query Type - From Post Parent
            if (!empty($settings['specific_page_parent'])) {
                $args = \array_merge($args, ['post_type' => get_post_type($settings['specific_page_parent']), 'post_parent' => $settings['specific_page_parent']]);
            }
            if ($settings['parent_source']) {
                $args = \array_merge($args, ['post_type' => $type_page, 'post_parent' => wp_get_post_parent_id($id_page)]);
            }
            if ($settings['child_source']) {
                $args = \array_merge($args, ['post_type' => $type_page, 'post_parent' => $id_page]);
            }
            $args = \array_merge($args, ['posts_per_page' => $settings['num_posts'], 'order' => $settings['order'], 'orderby' => $settings['orderby']]);
        } elseif ('relationship' === $settings['query_type']) {
            // Query Type - ACF Relationship
            if ($settings['relationship_invert']) {
                $relations_ids = Helper::get_acf_field_value_relationship_invert($settings['relationship_meta'], $id_page);
            } else {
                $relations_ids = get_post_meta($id_page, $settings['relationship_meta'], \false);
            }
            if (!empty($relations_ids) && !\is_array($relations_ids)) {
                $relations_ids = [$relations_ids];
            } elseif (!empty($relations_ids) && \is_array($relations_ids[0])) {
                $relations_ids = $relations_ids[0];
            }
            if (empty($relations_ids)) {
                $relations_ids = ['0'];
            }
            if ($settings['metakey']) {
                $args['meta_key'] = $settings['metakey'];
            }
            $args = \array_merge($args, ['post_type' => 'any', 'posts_per_page' => $settings['num_posts'], 'post_status' => 'publish', 'post__in' => $relations_ids, 'orderby' => $settings['orderby'], 'order' => $settings['order']]);
        } elseif ('pods_relationship' === $settings['query_type'] && Helper::is_pods_active()) {
            // Query Type - PODS Relationship
            if (pods(get_post_type(), get_the_ID())) {
                $related_posts = pods_field_raw($settings['pods_relationship_field']);
            }
            $related_posts_id = \false;
            if (\is_numeric($related_posts)) {
                $related_posts_id = array($related_posts);
            } elseif (isset($related_posts['ID'])) {
                $related_posts_id = array($related_posts['ID']);
            } elseif (\is_array($related_posts)) {
                $related_posts_id = wp_list_pluck($related_posts, 'ID');
            }
            // Don't make WP_Query if the Pods Relationship field is empty
            if (empty($related_posts_id)) {
                $this->query = '';
                return;
            }
            if ($settings['metakey']) {
                $args['meta_key'] = $settings['metakey'];
            }
            $args = \array_merge($args, ['post_type' => 'any', 'posts_per_page' => $settings['num_posts'], 'post_status' => 'publish', 'post__in' => $related_posts_id, 'orderby' => $settings['orderby'], 'order' => $settings['order']]);
        } elseif ($settings['query_type'] == 'specific_posts') {
            // Query Type - From Specific Posts
            $post__in = [];
            $specific_posts = $settings['specific_posts'];
            if (\is_array($specific_posts) && !empty($specific_posts)) {
                foreach ($specific_posts as $post) {
                    if (!empty($post['repeater_specific_posts'])) {
                        $post__in[] = $post['repeater_specific_posts'];
                    }
                }
            } else {
                $post__in = [0];
            }
            $args = \array_merge($args, ['post_type' => 'any', 'post__in' => $post__in, 'order' => $settings['order'], 'orderby' => $settings['orderby'], 'meta_key' => $settings['metakey'], 'post_status' => 'publish', 'posts_per_page' => -1]);
        } elseif ('id_list' === $settings['query_type']) {
            // Query Type - ID List
            $args = \array_merge($args, ['post_type' => 'any', 'post__in' => \explode(',', sanitize_text_field($settings['id_list'])), 'order' => $settings['order'], 'orderby' => $settings['orderby'], 'meta_key' => $settings['metakey'], 'post_status' => 'publish', 'posts_per_page' => -1]);
        } elseif ('woo_products_on_sale' === $settings['query_type']) {
            // Query Type - Dynamic Woo Products On Sale
            $products_on_sale = \wc_get_product_ids_on_sale();
            if (empty($products_on_sale)) {
                $this->query = '';
                return;
            }
            $args = \array_merge($args, ['post_type' => 'product', 'post__in' => $products_on_sale, 'order' => $settings['order'], 'orderby' => $settings['orderby'], 'meta_key' => $settings['metakey'], 'post_status' => $post_status, 'posts_per_page' => $settings['num_posts']]);
        } elseif ('favorites' === $settings['query_type']) {
            // Query Type - Favorites
            if (empty($settings['favorites_key']) && \Elementor\Plugin::$instance->editor->is_edit_mode()) {
                Helper::notice('', __('Select the Favorites key', 'dynamic-content-for-elementor'));
            }
            $favorites_post_in = [];
            if ('user' === $settings['favorites_scope']) {
                $favorites_post_in = get_user_meta(get_current_user_id(), sanitize_text_field($settings['favorites_key']), \true);
            } elseif ('cookie' === $settings['favorites_scope'] && isset($_COOKIE[sanitize_text_field($settings['favorites_key'])])) {
                $favorites_post_in = \explode(',', $_COOKIE[sanitize_text_field($settings['favorites_key'])]);
            } elseif ('global' === $settings['favorites_scope']) {
                $favorites_post_in = get_option(sanitize_text_field($settings['favorites_key']));
            }
            if (!empty($favorites_post_in)) {
                if ('dce_wishlist' !== $settings['favorites_key']) {
                    $args = \array_merge($args, ['post_type' => 'any', 'post__in' => $favorites_post_in, 'order' => $settings['order'], 'orderby' => $settings['orderby'], 'meta_key' => $settings['metakey'], 'post_status' => $post_status, 'posts_per_page' => $settings['num_posts']]);
                } else {
                    if (!is_user_logged_in()) {
                        $this->query = '';
                        return;
                    }
                    foreach ($favorites_post_in as $product) {
                        if ('product' === get_post_type($product) && !wc_customer_bought_product('', get_current_user_id(), get_the_ID($product))) {
                            $wishlist[] = $product;
                        }
                    }
                    $args = \array_merge($args, ['post_type' => 'product', 'post__in' => $wishlist, 'order' => $settings['order'], 'orderby' => $settings['orderby'], 'meta_key' => $settings['metakey'], 'post_status' => $post_status, 'posts_per_page' => $settings['num_posts']]);
                }
            } else {
                $this->query = '';
                return;
            }
        } elseif ('custom_query' === $settings['query_type']) {
            // Query Type - Custom Query
            $custom_query = $this->check_custom_query($settings);
            if ($custom_query) {
                $args = $custom_query;
            }
        } elseif ('products_cart' === $settings['query_type']) {
            // Query Type - Products in the Cart
            if (Helper::is_woocommerce_active()) {
                global $woocommerce;
                $items = $woocommerce->cart->get_cart();
                $products = [];
                foreach ($items as $item => $values) {
                    $products[] = $values['data']->get_id();
                }
                $args = \array_merge($args, ['post_type' => 'product', 'post__in' => $products]);
            }
        } elseif ('product_upsells' === $settings['query_type']) {
            // Query Type - Up-Sells Products
            if (Helper::is_woocommerce_active()) {
                $upsell_ids = get_post_meta(get_the_ID(), '_upsell_ids', \true);
                if ($upsell_ids) {
                    $args = \array_merge($args, ['post_type' => 'product', 'post__in' => $upsell_ids]);
                } else {
                    $this->query = '';
                    return;
                }
            }
        } elseif ('product_crosssells' === $settings['query_type']) {
            // Query Type - Cross-Sells Products
            if (Helper::is_woocommerce_active()) {
                $crosssell_ids = get_post_meta(get_the_ID(), '_crosssell_ids', \true);
                if ($crosssell_ids) {
                    $args = \array_merge($args, ['post_type' => 'product', 'post__in' => $crosssell_ids]);
                } else {
                    $this->query = '';
                    return;
                }
            }
        } elseif ('sticky_posts' === $settings['query_type']) {
            // Query Type - Sticky Posts
            $args = \array_merge($args, ['post__in' => get_option('sticky_posts'), 'post_type' => $settings['post_type'], 'posts_per_page' => $settings['num_posts'], 'order' => $settings['order'], 'orderby' => $settings['orderby'], 'meta_key' => $settings['metakey'], 'post__not_in' => \array_merge($posts_excluded, $exclude_current_post), 'post_parent__not_in' => $use_parent_page, 'post_status' => $post_status]);
        } elseif ('search_filter' === $settings['query_type']) {
            // Query Type - Search and Filter
            if (Helper::is_searchandfilterpro_active()) {
                $sfid = \intval($settings['search_filter_id']);
                $args = ['search_filter_id' => $sfid];
            }
        }
        // Pagination
        if ('search_filter' !== $settings['query_type']) {
            global $paged;
            $page = $this->get_current_page();
            $args['paged'] = $page;
        } else {
            if (isset($_GET['sf_paged'])) {
                $args['paged'] = \intval($_GET['sf_paged']);
            } else {
                $args['paged'] = 1;
            }
        }
        // Query Filter
        if (\is_array($settings['query_filter'])) {
            // Date query filter
            if (\in_array('date', $settings['query_filter'])) {
                $querydate_field_meta_format = 'Ymd';
                $date_field = $settings['querydate_field'];
                if ($settings['querydate_mode'] != 'future' && $settings['querydate_field'] == 'post_meta') {
                    $date_field_meta = sanitize_key($settings['querydate_field_meta']);
                    $querydate_field_meta_format = sanitize_text_field($settings['querydate_field_meta_format']);
                }
                if ($settings['querydate_mode'] == 'future') {
                    $date_field_meta = sanitize_key($settings['querydate_field_meta_future']);
                    $querydate_field_meta_format = sanitize_text_field($settings['querydate_field_meta_future_format']);
                    if ($settings['querydate_field_meta_future_contains_today']) {
                        $future_compare = '>=';
                    } else {
                        $future_compare = '>';
                    }
                    $args['meta_query'] = [['key' => $date_field_meta, 'value' => \date($querydate_field_meta_format, \time()), 'meta_type' => 'DATETIME', 'compare' => $future_compare]];
                }
                if ($date_field) {
                    $date_after = \false;
                    $date_before = \false;
                    switch ($settings['querydate_mode']) {
                        case 'past':
                            $date_before = \date('Y-m-d H:i:s');
                            break;
                        case 'today':
                            $date_after = \date('Y-m-d 00:00:00');
                            $date_before = \date('Y-m-d 23:59:59');
                            break;
                        case 'yesterday':
                            $date_after = \date('Y-m-d 00:00:00', \strtotime('-1 day'));
                            $date_before = \date('Y-m-d 23:59:59', \strtotime('-1 day'));
                            break;
                        case 'days':
                        case 'weeks':
                        case 'months':
                        case 'years':
                            $date_after = '-' . $settings['querydate_range'] . ' ' . $settings['querydate_mode'];
                            $date_before = 'now';
                            break;
                        case 'period':
                            $date_after = $settings['querydate_date_type'] === 'static' ? $settings['querydate_date_from'] : $settings['querydate_date_from_dynamic'];
                            $date_before = $settings['querydate_date_type'] === 'static' ? $settings['querydate_date_to'] : $settings['querydate_date_to_dynamic'];
                            break;
                    }
                    // compare by post publish date
                    if ($settings['querydate_field'] == 'post_date') {
                        $args['date_query'] = [['after' => $date_after, 'before' => $date_before, 'inclusive' => \true]];
                    } elseif ($settings['querydate_field'] == 'post_modified') {
                        // compare by post modified date
                        $args['date_query'] = [['column' => 'post_modified', 'after' => $date_after, 'before' => $date_before, 'inclusive' => \true]];
                    } elseif ($settings['querydate_field'] == 'post_meta') {
                        // compare by post meta
                        if ($date_after) {
                            $date_after = \date($querydate_field_meta_format, \strtotime($date_after));
                        }
                        if ($date_before) {
                            $date_before = \date($querydate_field_meta_format, \strtotime($date_before));
                        }
                        if ($date_before && $date_after) {
                            $args['meta_query'] = [['key' => $date_field_meta, 'value' => [$date_after, $date_before], 'meta_type' => 'DATETIME', 'compare' => 'BETWEEN']];
                        } elseif ($date_after) {
                            $args['meta_query'] = [['key' => $date_field_meta, 'value' => $date_after, 'meta_type' => 'DATETIME', 'compare' => '>=']];
                        } else {
                            $args['meta_query'] = [['key' => $date_field_meta, 'value' => $date_before, 'meta_type' => 'DATETIME', 'compare' => '<=']];
                        }
                    }
                }
            }
            // Term query filter
            if (\in_array('term', $settings['query_filter'])) {
                if ($settings['term_from'] == 'post_term') {
                    if ($settings['taxonomy'] && ($settings['include_term_' . $settings['taxonomy']] || $settings['exclude_term_' . $settings['taxonomy']])) {
                        if ($settings['include_term_' . $settings['taxonomy']]) {
                            $args['tax_query'][] = ['operator' => $settings['include_term_operator'], 'taxonomy' => $settings['taxonomy'], 'terms' => $settings['include_term_' . $settings['taxonomy']]];
                        }
                        if ($settings['exclude_term_' . $settings['taxonomy']]) {
                            $args['tax_query'][] = ['operator' => 'NOT IN', 'taxonomy' => $settings['taxonomy'], 'terms' => $settings['exclude_term_' . $settings['taxonomy']]];
                        }
                        if ($settings['include_term_' . $settings['taxonomy']] && $settings['exclude_term_' . $settings['taxonomy']]) {
                            $args['tax_query']['relation'] = 'AND';
                        }
                    }
                    if ($settings['terms_current_post']) {
                        $terms_query = $this->get_terms_query($settings, $id_page);
                        if (\is_array($terms_query) && !empty($terms_query)) {
                            foreach ($terms_query as $term_query) {
                                $args['tax_query'][] = ['taxonomy' => $settings['taxonomy'], 'terms' => $term_query];
                            }
                        }
                        if (\is_array($terms_query) && \count($terms_query) > 1) {
                            $args['tax_query']['relation'] = 'OR';
                        }
                    }
                } elseif ($settings['term_from'] == 'post_meta') {
                    if ($settings['term_field_meta']) {
                        $args['tax_query'][] = ['operator' => 'IN', 'taxonomy' => $settings['taxonomy'], 'terms' => 'all'];
                        $args['meta_query'][] = ['key' => $settings['term_field_meta']];
                    }
                } elseif ($settings['term_from'] == 'dynamicstring') {
                    if ($settings['term_field_meta_string']) {
                        $args['tax_query'][] = ['operator' => 'IN', 'taxonomy' => $settings['taxonomy'], 'field' => 'slug', 'terms' => sanitize_text_field($settings['term_field_meta_string'])];
                    }
                }
            }
            // Author query filter
            if (\in_array('author', $settings['query_filter'])) {
                $author_id = get_the_author_meta('ID');
                if (!is_singular()) {
                    $queried_object = get_queried_object();
                    if ($queried_object) {
                        if (\get_class($queried_object) == 'WP_User') {
                            $author_id = get_queried_object_id();
                            $args['author__in'] = $author_id;
                        }
                    }
                }
                if ($settings['author_from'] == 'post_author') {
                    if ($settings['include_author']) {
                        $args['author__in'] = $settings['include_author'];
                    }
                    if ($settings['exclude_author']) {
                        $args['author__not_in'] = $settings['exclude_author'];
                    }
                } elseif ($settings['author_from'] == 'current_author') {
                    $args['author__in'] = $author_id;
                } elseif ($settings['author_from'] == 'current_user') {
                    $args['author__in'] = get_current_user_id();
                }
            }
            // Meta Key query filter
            if (\in_array('metakey', $settings['query_filter'])) {
                if ($settings['metakey_from'] == 'post_metakey') {
                    if ($settings['include_metakey'] && $settings('include_metakey_combination')) {
                        $args['meta_query'][] = ['key' => $settings['include_metakey'], 'compare' => $settings['include_metakey_combination']];
                    }
                    if ($settings['exclude_metakey'] && $settings('exclude_metakey_combination')) {
                        $args['meta_query'][] = ['key' => $settings['exclude_metakey'], 'compare' => $settings['exclude_metakey_combination']];
                    }
                } elseif ($settings['metakey_from'] == 'post_meta') {
                    if ($settings['metakey_field_meta']) {
                        $args['meta_query'][] = ['key' => $settings['metakey_field_meta'], 'value' => sanitize_text_field($settings['metakey_field_meta_value']), 'compare' => $settings['metakey_field_meta_operator']];
                    }
                } elseif ($settings['metakey_from'] == 'dynamicstring') {
                    if ($settings['metakey_field_meta_string']) {
                        $args['meta_query'][] = ['value' => sanitize_text_field($settings['metakey_field_meta_string'])];
                    }
                }
            }
        }
        $query_p = new \WP_Query($args);
        $this->query = $query_p;
        $this->query_args = $args;
    }
    public function get_terms_query($settings = null, $id_page = null)
    {
        $terms_query = 'all';
        if (!$settings) {
            $settings = $this->get_settings_for_display();
        }
        if (!$id_page) {
            $id_page = get_the_ID();
        }
        if ($settings['taxonomy']) {
            if ($settings['terms_current_post']) {
                // Da implementare oR & AND tems ...
                if (is_singular()) {
                    $terms_list = wp_get_post_terms($id_page, $settings['taxonomy'], ['orderby' => 'name', 'order' => 'ASC', 'fields' => 'all', 'hide_empty' => \true]);
                    if (!empty($terms_list)) {
                        $terms_query = [];
                        foreach ($terms_list as $akey => $aterm) {
                            if (\is_object($aterm) && \get_class($aterm) == 'WP_Term') {
                                if (!\in_array($aterm->term_id, $terms_query)) {
                                    $terms_query[] = $aterm->term_id;
                                }
                            }
                        }
                    }
                }
                if (is_archive()) {
                    if (is_tax() || is_category() || is_tag()) {
                        $queried_object = get_queried_object();
                        $terms_query = [$queried_object->term_id];
                    }
                }
            }
            if (isset($settings['terms_' . $settings['taxonomy']]) && !empty($settings['terms_' . $settings['taxonomy']])) {
                $terms_query = $settings['terms_' . $settings['taxonomy']];
                // add current post terms id
                $dce_key = \array_search('dce_current_post_terms', $terms_query);
                if ($dce_key !== \false) {
                    unset($terms_query[$dce_key]);
                    $terms_list = wp_get_post_terms($id_page, $settings['taxonomy'], ['orderby' => 'name', 'order' => 'ASC', 'fields' => 'all', 'hide_empty' => \true]);
                    if (!empty($terms_list)) {
                        $terms_query = [];
                        foreach ($terms_list as $akey => $aterm) {
                            if (!\in_array($aterm->term_id, $terms_query)) {
                                $terms_query[] = $aterm->term_id;
                            }
                        }
                    }
                }
            }
        }
        return $terms_query;
    }
    protected function check_custom_query($settings)
    {
        //+exclude_start
        $custom_query = $settings['custom_query_code'];
        if (\is_string($custom_query)) {
            try {
                return @eval($custom_query);
            } catch (\ParseError $e) {
                $evalError = \true;
            } catch (\Throwable $e) {
                $evalError = \true;
            }
            if ($evalError && \Elementor\Plugin::$instance->editor->is_edit_mode()) {
                echo '<strong>';
                echo __('Please check your Dynamic Posts - Custom Query Code', 'dynamic-content-for-elementor');
                echo '</strong><br />';
                echo __('ERROR', 'dynamic-content-for-elementor') . ': ' . $e->getMessage(), "\n";
            }
        }
        return \false;
        //+exclude_end
    }
    public function get_query()
    {
        return $this->query;
    }
    public function get_query_args()
    {
        return $this->query_args;
    }
    public function get_current_page()
    {
        if ('' === $this->get_settings('pagination_enable') && '' === $this->get_settings('infiniteScroll_enable')) {
            return 1;
        }
        return \max(1, get_query_var('paged'), get_query_var('page'));
    }
    protected function render_svg_mask($mask_shape_type)
    {
        $widgetId = $this->get_id();
        $shape_numbers = $this->get_settings('shape_numbers');
        $image_masking_url = $this->get_settings('image_masking')['url'];
    }
    protected function limit_content($limit)
    {
    }
    protected function limit_excerpt($limit)
    {
    }
}
