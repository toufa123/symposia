<?php

namespace DynamicContentForElementor;

trait Pagination
{
    public static function allow_posts_pagination($preempt, $wp_query)
    {
        if ($preempt || empty($wp_query->query_vars['page']) || empty($wp_query->post) || !is_singular()) {
            return $preempt;
        }
        $allow_pagination = \false;
        $document = '';
        $current_post_id = $wp_query->post->ID;
        $dce_posts_widgets = ['dyncontel-acfposts', 'dce-dynamicposts-v2', 'dyncontel-dynamicusers', 'dce-sticky-posts', 'dce-my-posts', 'dce-dynamic-woo-products', 'dce-search-results', 'dce-dynamic-show-favorites', 'dce-woo-products-cart', 'dce-woo-product-upsells', 'dce-woo-product-crosssells', 'dce-woo-wishlist'];
        // Check if current post/page is built with Elementor and check for DCE posts pagination
        if (\Elementor\Plugin::$instance->db->is_built_with_elementor($current_post_id)) {
            $allow_pagination = self::check_posts_pagination($current_post_id, $dce_posts_widgets);
        }
        $dce_template = get_option('dce_template');
        // Check if single DCE template is active and check for DCE posts pagination in template
        if ('active' === $dce_template && !$allow_pagination) {
            $options = get_option(DCE_TEMPLATE_SYSTEM_OPTION);
            $post_type = get_post_type($current_post_id);
            if (\is_array($options) && $options['dyncontel_field_single' . $post_type]) {
                $allow_pagination = self::check_posts_pagination($options['dyncontel_field_single' . $post_type], $dce_posts_widgets);
            }
        }
        // Check if single Elementor Pro template is active and check for DCE posts pagination in template
        if (\DynamicContentForElementor\Helper::is_elementorpro_active() && !$allow_pagination) {
            $locations = \ElementorPro\Modules\ThemeBuilder\Module::instance()->get_locations_manager()->get_locations();
            if (isset($locations['single'])) {
                $location_docs = \ElementorPro\Modules\ThemeBuilder\Module::instance()->get_conditions_manager()->get_documents_for_location('single');
                foreach ($location_docs as $location_doc_id => $settings) {
                    if ($wp_query->post->ID !== $location_doc_id) {
                        $allow_pagination = self::check_posts_pagination($location_doc_id, $dce_posts_widgets);
                        break;
                    }
                }
            }
        }
        if ($allow_pagination) {
            return $allow_pagination;
        }
        return $preempt;
    }
    protected static function check_posts_pagination($post_id, $dce_posts_widgets, $current_page = null)
    {
        if (!$post_id) {
            return \false;
        }
        $pagination = \false;
        $document = \Elementor\Plugin::$instance->documents->get($post_id);
        $document_elements = $document->get_elements_data();
        // Check if DCE posts widgets are present and if pagination or infinite scroll is active
        \Elementor\Plugin::$instance->db->iterate_data($document_elements, function ($element) use(&$pagination, $dce_posts_widgets) {
            if (isset($element['widgetType']) && \in_array($element['widgetType'], $dce_posts_widgets, \true)) {
                if (isset($element['settings']['pagination_enable']) && $element['settings']['pagination_enable']) {
                    $pagination = \true;
                }
                if (isset($element['settings']['infiniteScroll_enable']) && $element['settings']['infiniteScroll_enable']) {
                    $pagination = \true;
                }
            }
        });
        return $pagination;
    }
}
