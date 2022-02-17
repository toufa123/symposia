<?php
namespace Photonic_Plugin\Core;

class AJAX {
	private function __construct() {
		add_action('wp_ajax_photonic_display_level_2_contents', [&$this, 'display_level_2_contents']);
		add_action('wp_ajax_nopriv_photonic_display_level_2_contents', [&$this, 'display_level_2_contents']);

		add_action('wp_ajax_photonic_display_level_3_contents', [&$this, 'display_level_3_contents']);
		add_action('wp_ajax_nopriv_photonic_display_level_3_contents', [&$this, 'display_level_3_contents']);

		add_action('wp_ajax_photonic_load_more', [&$this, 'load_more']);
		add_action('wp_ajax_nopriv_photonic_load_more', [&$this, 'load_more']);

		add_action('wp_ajax_photonic_lazy_load', [&$this, 'lazy_load']);
		add_action('wp_ajax_nopriv_photonic_lazy_load', [&$this, 'lazy_load']);

		add_action('wp_ajax_photonic_helper_shortcode_more', [&$this, 'helper_shortcode_more']);
		add_action('wp_ajax_nopriv_photonic_helper_shortcode_more', [&$this, 'helper_shortcode_more']);

		add_action('wp_ajax_photonic_invoke_helper', [&$this, 'invoke_helper']);
		add_action('wp_ajax_photonic_obtain_token', [&$this, 'obtain_token']);
		add_action('wp_ajax_photonic_save_token', [&$this, 'save_token_in_options']);
		add_action('wp_ajax_photonic_delete_token', [&$this, 'delete_token_from_options']);
	}

	/**
	 * Clicking on a level 2 object (i.e. an Album / Set / Gallery) triggers this. This will fetch the contents of the level 2 object and generate the markup for it.
	 * This is the hook for an AJAX-invoked call
	 *
	 * @return void
	 */
	function display_level_2_contents() {
		$panel = esc_attr($_POST['panel_id']);
		$components = explode('-', $panel);

		if (count($components) <= 5) {
			die();
		}
		$panel = implode('-', array_slice($components, 4, 10, true));
		$query = sanitize_text_field($_POST['query']);
		$query = wp_parse_args($query);

		$args = [
			'display' => 'popup',
			'layout' => 'square',
			'panel' => $panel,
			'password' => !empty($_POST['password']) ? sanitize_text_field($_POST['password']) : '',
			'count' => sanitize_text_field($_POST['photo_count']),
			'photo_more' => sanitize_text_field($_POST['photo_more']),
			'main_size' => $query['main_size'],
			'type' => $components[1]
		];

		$provider = $components[1];
		$type = $components[2];
		if (in_array($provider, ['smug', 'smugmug', 'zenfolio', 'google', 'flickr'])) {
			if ($provider == 'smug') {
				$args['view'] = 'album';
				$args['album_key'] = $components[4];
			}
			else if ($provider == 'zenfolio') {
				$args['view'] = 'photosets';
				$args['object_id'] = $components[4];
				$args['thumb_size'] = sanitize_text_field($_POST['overlay_size']);
				$args['video_size'] = sanitize_text_field($_POST['overlay_video_size']);
				if (isset($_POST['realm_id'])) {
					$args['realm_id'] = sanitize_text_field($_POST['realm_id']);
				}
			}
			else if ($provider == 'google') {
				$args['view'] = 'photos';
				$args['album_id'] = implode('-', array_slice($components, 4, (count($components) - 1) - 4));
				$args['thumb_size'] = sanitize_text_field($_POST['overlay_size']);
				$args['video_size'] = sanitize_text_field($_POST['overlay_video_size']);
				$args['crop_thumb'] = sanitize_text_field($_POST['overlay_crop']);
			}
			else if ($provider == 'flickr') {
				if ($type == 'gallery') {
					$args['gallery_id'] = $components[4].'-'.$components[5];
					$args['gallery_id_computed'] = true;
				}
				else if ($type = 'set') {
					$args['photoset_id'] = $components[4];
				}
				$args['thumb_size'] = sanitize_text_field($_POST['overlay_size']);
				$args['video_size'] = sanitize_text_field($_POST['overlay_video_size']);
			}

			$gallery = new Gallery($args);
			echo $gallery->get_contents();
		}
		die();
	}

	/**
	 * Clicking on the expander for a level 3 object (e.g. a Flickr Collection etc.) triggers this. This will fetch the nested level 2 objects and generate the corresponding markup.
	 * This is the hook for an AJAX-invoked call.
	 */
	function display_level_3_contents() {
		$node = esc_attr($_POST['node']);
		$components = explode('-', $node);

		if (count($components) <= 3) {
			die();
		}

		$args = ['display' => 'in-page', 'headers' => '', 'layout' => esc_attr($_POST['layout']), 'stream' => esc_attr($_POST['stream'])];

		$provider = $components[0];
		if ($provider == 'flickr') {
			$args['collection_id'] = implode('-', array_slice($components, 2, 2, true));
			$args['user_id'] = $components[4];
			$args['type'] = 'flickr';
			$args['strip_top_level'] = 'remove';
			$gallery = new Gallery($args);
			echo $gallery->get_contents();
		}
		die();
	}

	function load_more() {
		$provider = esc_attr($_POST['provider']);
		$query = sanitize_text_field($_POST['query']);
		$attr = wp_parse_args($query);

		$attr['type'] = $provider;
		if ($provider == 'flickr') {
			$attr['page'] = isset($attr['page']) ? $attr['page'] + 1 : 0;
		}
		else if ($provider == 'smug') {
			$attr['start'] = $attr['start'] + $attr['count'];
		}
		else if ($provider == 'zenfolio') {
			$attr['offset'] = $attr['offset'] + $attr['limit'];
		}
		else if ($provider == 'wp') {
			$attr['page'] = $attr['page'] + 1;
		}
		else {
			unset($attr['type']);
		}

		if (!empty($attr['type'])) {
			$gallery = new Gallery($attr);
			echo $gallery->get_contents();
		}
		die();
	}

	function lazy_load() {
		$shortcode = $_POST['shortcode'];
		parse_str($shortcode, $attr);
		$images = $this->get_gallery_images($attr);
		echo $images;
		die();
	}

	function helper_shortcode_more() {
		if (!empty($_POST['provider'])) {
			$provider = sanitize_text_field($_POST['provider']);
			if (in_array($provider, ['google'])) {
				$attr = ['type' => $provider];
				if ($provider == 'google') {
					$attr['nextPageToken'] = sanitize_text_field($_POST['nextPageToken']);
					$attr['album_type'] = sanitize_text_field($_POST['access']);
					$gallery = new Gallery($attr);
					echo $gallery->get_helper_contents();
				}
			}
		}
		die();
	}
}