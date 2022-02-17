<?php

namespace DynamicContentForElementor\Widgets;

use Elementor\Controls_Manager;
use DynamicContentForElementor\Helper;
if (!\defined('ABSPATH')) {
    exit;
}
// Exit if accessed directly
class GoogleDocumentPreview extends \DynamicContentForElementor\Widgets\RemoteContent
{
    protected function register_controls_content()
    {
        parent::register_controls_content();
        $this->update_control('incorporate', ['type' => Controls_Manager::HIDDEN, 'default' => '']);
        $this->update_control('iframe_doc', ['type' => Controls_Manager::HIDDEN, 'default' => 'yes']);
    }
}
