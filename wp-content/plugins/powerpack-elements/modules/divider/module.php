<?php
namespace PowerpackElements\Modules\Divider;

use PowerpackElements\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function get_name() {
		return 'pp-divider';
	}

	public function get_widgets() {
		return [
			'Divider',
		];
	}
}
