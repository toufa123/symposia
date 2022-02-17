<?php
namespace Photonic_Plugin\Components;

use Photonic_Plugin\Layouts\Core_Layout;
use Photonic_Plugin\Modules\Core;

class Collection implements Printable {
	/**
	 * @var Header $header
	 */
	public $header;

	/**
	 * @var Album_List $album_list
	 */
	public $album_list;

	/**
	 * @var array
	 */
	public $collections = [];

	public
		$indent,
		$strip_top_level // Specifically for Flickr, when you do lazy loading
	;

	/**
	 * {@inheritDoc} - a Collection
	 */
	function html(Core $module, Core_Layout $layout, $print = false) {
		$start = empty($this->strip_top_level) ? $this->indent."<div class='photonic-tree'>\n" : '';
		$out = $start;

		if (!empty($this->header)) {
			$out .= $this->header->html($module, $layout);
		}

		if (!empty($this->album_list)) {
			$out .= $this->album_list->html($module, $layout);
		}

		if (!empty($this->collections)) {
			/** @var Printable|string $collection */
			foreach ($this->collections as $collection) {
				if (is_a($collection, 'Photonic_Plugin\Components\Printable')) {
					$out .= $collection->html($module, $layout);
				}
				else {
					$out .= $collection;
				}
			}
		}

		if ($out != $start && !$this->strip_top_level) {
			$out .= $this->indent."</div>\n";
		}

		if ($print) {
			echo $out;
		}
		return $out;
	}
}