<?php
namespace Photonic_Plugin\Lightboxes;

require_once('Lightbox.php');

class Spotlight extends Lightbox {
	protected function __construct() {
		$this->library = 'spotlight';
		parent::__construct();
		$this->class = ['photonic-lb', 'photonic-spotlight'];
	}

	function get_container_classes() {
		return "spotlight-group";
	}

	function get_photo_attributes($photo_data, $module) {
		$out = parent::get_photo_attributes($photo_data, $module);
		return $out.(!empty($photo_data['video']) ? ' data-src-mp4="'.$photo_data['video'].'" data-media="video" data-poster="'.$photo_data['poster'].'"': '');
	}

/*	function get_lightbox_title($photo, $module, $title, $alt_title, $target) {
		if (empty($title)) {
			$shown_title = $module->link_lightbox_title ? $this->default_lightbox_text : $alt_title;
		}
		else {
			$shown_title = $title;
		}

		return apply_filters('photonic_lightbox_title_markup', $shown_title);
	}*/
}