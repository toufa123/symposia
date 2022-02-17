<?php

namespace DynamicContentForElementor\Widgets;

use Elementor\Controls_Manager;
use DynamicContentForElementor\Helper;
use DynamicContentForElementor\Includes\Skins;
if (!\defined('ABSPATH')) {
    exit;
    // Exit if accessed directly
}
class SearchResults extends \DynamicContentForElementor\Widgets\DynamicPostsBase
{
    public function get_name()
    {
        return 'dce-search-results';
    }
    protected function register_skins()
    {
        $this->add_skin(new Skins\Search_Page_Results_Skin_Grid($this));
        $this->add_skin(new Skins\Search_Page_Results_Skin_Grid_Filters($this));
        $this->add_skin(new Skins\Search_Page_Results_Skin_Carousel($this));
        $this->add_skin(new Skins\Search_Page_Results_Skin_DualCarousel($this));
        $this->add_skin(new Skins\Search_Page_Results_Skin_Timeline($this));
        $this->add_skin(new Skins\Search_Page_Results_Skin_3D($this));
        $this->add_skin(new Skins\Search_Page_Results_Skin_Gridtofullscreen3d($this));
        $this->add_skin(new Skins\Search_Page_Results_Skin_CrossroadsSlideshow($this));
    }
    protected function _register_controls()
    {
        parent::_register_controls();
        $this->update_control('query_type', ['type' => Controls_Manager::HIDDEN, 'default' => 'search_page']);
        $this->update_control('orderby', ['default' => 'relevance']);
    }
}
