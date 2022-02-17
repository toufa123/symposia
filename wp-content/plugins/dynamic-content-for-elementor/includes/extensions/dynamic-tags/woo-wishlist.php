<?php

namespace DynamicContentForElementor\Extensions\DynamicTags;

use DynamicContentForElementor\Extensions\DCE_Extension_Prototype;
if (!\defined('ABSPATH')) {
    exit;
    // Exit if accessed directly
}
class WooWishlist extends DCE_Extension_Prototype
{
    public function init($param = null)
    {
        parent::init();
        $this->add_dynamic_tag('WooWishlist');
    }
}
