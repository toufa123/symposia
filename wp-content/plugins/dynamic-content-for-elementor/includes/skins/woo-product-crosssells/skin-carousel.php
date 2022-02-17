<?php

namespace DynamicContentForElementor\Includes\Skins;

if (!\defined('ABSPATH')) {
    exit;
    // Exit if accessed directly
}
class Woo_Product_Crosssells_Skin_Carousel extends \DynamicContentForElementor\Includes\Skins\Skin_Carousel
{
    protected function _register_controls_actions()
    {
        add_action('elementor/element/dce-woo-product-crosssells/section_query/after_section_end', [$this, 'register_controls_layout']);
        add_action('elementor/element/dce-woo-product-crosssells/section_dynamicposts/after_section_end', [$this, 'register_additional_carousel_controls']);
    }
}
