<?php

namespace DynamicContentForElementor\Includes\Skins;

if (!\defined('ABSPATH')) {
    exit;
    // Exit if accessed directly
}
class Show_Woo_Wishlist_Skin_3D extends \DynamicContentForElementor\Includes\Skins\Skin_3D
{
    protected function _register_controls_actions()
    {
        add_action('elementor/element/dce-woo-wishlist/section_query/after_section_end', [$this, 'register_controls_layout']);
        add_action('elementor/element/dce-woo-wishlist/section_dynamicposts/after_section_end', [$this, 'register_additional_3d_controls']);
    }
}
