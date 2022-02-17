<?php
namespace Photonic_Plugin\Modules;

use Photonic_Plugin\Components\Album_List;
use Photonic_Plugin\Components\Collection;
use Photonic_Plugin\Components\Error;
use Photonic_Plugin\Components\Header;
use Photonic_Plugin\Components\Pagination;
use Photonic_Plugin\Components\Photo_List;
use Photonic_Plugin\Components\Single_Photo;
use Photonic_Plugin\Core\Photonic;
use Photonic_Plugin\Components\Album;
use Photonic_Plugin\Components\Photo;
use Requests;
use Requests_Hooks;
use WP_Error;

require_once('OAuth1.php');
require_once('Level_One_Module.php');
require_once('Level_Two_Module.php');
require_once(PHOTONIC_PATH.'/Components/Collection.php');

/**
 * ## Main Module for Flickr Galleries
 *
 * This module inherits from the Photonic_Plugin\Modules\Core module. Flickr supports **OAuth1 authentication**.
 *
 * Flickr supports 4 levels of galleries:
 *	- Individual photos (Level 0)
 * 	- Photosets / Individual Albums / Individual Galleries (Level 1)
 *	- All or selected Albums and Galleries (Level 2)
 * 	- Collections (Level 3)
 *
 * All galleries can be laid out using any of the layout options.
 */
class Flickr extends OAuth1 implements Level_One_Module, Level_Two_Module {
	private static $instance = null;

	protected function __construct() {
		parent::__construct();
		global $photonic_flickr_api_key, $photonic_flickr_api_secret, $photonic_flickr_disable_title_link, $photonic_flickr_access_token, $photonic_flickr_token_secret;
		$this->api_key = trim($photonic_flickr_api_key);
		$this->api_secret = trim($photonic_flickr_api_secret);
		$this->token = trim($photonic_flickr_access_token);
		$this->token_secret = trim($photonic_flickr_token_secret);

		$this->provider = 'flickr';
		$this->link_lightbox_title = empty($photonic_flickr_disable_title_link);
		$this->base_url = 'https://api.flickr.com/services/rest/';

		$this->doc_links = [
			'general' => 'https://aquoid.com/plugins/photonic/flickr/',
			'photo' => 'https://aquoid.com/plugins/photonic/flickr/flickr-photo',
			'photos' => 'https://aquoid.com/plugins/photonic/flickr/flickr-photos/',
			'photosets' => 'https://aquoid.com/plugins/photonic/flickr/flickr-photosets/',
			'galleries' => 'https://aquoid.com/plugins/photonic/flickr/flickr-galleries/',
			'collections' => 'https://aquoid.com/plugins/photonic/flickr/flickr-collections/',
			'auth' => 'https://aquoid.com/plugins/photonic/flickr/flickr-authentication',
		];

		$this->set_oauth_done();
	}

	public static function get_instance() {
		if (self::$instance == null) {
			self::$instance = new Flickr();
		}
		return self::$instance;
	}

	/**
	 * A very flexible function to display a user's photos from Flickr. This makes use of the Flickr API, hence it requires the user's API key.
	 * The API key is defined in the options. The function makes use of three different APIs:
	 *  1. <a href='https://www.flickr.com/services/api/flickr.photos.search.html'>flickr.photos.search</a> - for retrieving photos based on search critiera
	 *  2. <a href='https://www.flickr.com/services/api/flickr.photosets.getPhotos.html'>flickr.photosets.getPhotos</a> - for retrieving photo sets
	 *  3. <a href='https://www.flickr.com/services/api/flickr.galleries.getPhotos.html'>flickr.galleries.getPhotos</a> - for retrieving galleries
	 *
	 * The following short-code parameters are supported:
	 * All
	 * - per_page: number of photos to display
	 * - view: photos | collections | galleries | photosets, displays hierarchically if user_id is passed
	 * Photosets
	 * - photoset_id
	 * Galleries
	 * - gallery_id
	 * Photos
	 * - user_id: can be obtained from the Helpers page
	 * - tags: comma-separated list of tags
	 * - tag_mode: any | all, tells whether any tag should be used or all
	 * - text: string for text search
	 * - sort: date-posted-desc | date-posted-asc | date-taken-asc | date-taken-desc | interestingness-desc | interestingness-asc | relevance
	 * - group_id: group id for which photos will be displayed
	 *
	 * @param array $attr
	 * @return string|array
	 * @since 1.02
	 */
	public function get_gallery_images($attr = []) {
		global $photonic_flickr_title_caption, $photonic_flickr_media, $photonic_flickr_default_user,
			   $photonic_flickr_thumb_size, $photonic_flickr_main_size, $photonic_flickr_tile_size, $photonic_flickr_video_size;

		$this->push_to_stack('Get gallery images');
		$attr = array_merge(
			$this->common_parameters,
			[
				// Common overrides ...
				'caption' => $photonic_flickr_title_caption,
				'thumb_size' => $photonic_flickr_thumb_size,
				'main_size' => $photonic_flickr_main_size,
				'tile_size' => $photonic_flickr_tile_size,
				'video_size' => $photonic_flickr_video_size,

				// Flickr-Specific ...
				//		'view' => 'photos'  // photos | collections | galleries | photosets: if only a user id is passed, what should be displayed?
				'privacy_filter' => '',
				'count' => 500,
				'page' => 1,
				'paginate' => false,
				'collections_display' => 'expanded',
				'user_id' => $photonic_flickr_default_user,
				'collection_id' => '',
				'photoset_id' => '',
				'gallery_id' => '',
				'photo_id' => '',
				'media' => $photonic_flickr_media,
			],
			$attr);
		$attr = array_map('trim', $attr);

		extract($attr);

		if (empty($this->api_key)) {
			$this->pop_from_stack();
			return [new Error(esc_html__('Flickr API Key not defined.', 'photonic').Photonic::doc_link($this->doc_links['general']))];
		}

		$query_urls = [];
		$flickr_params = [];

		$flickr_params['extras'] = apply_filters('photonic_flickr_query_extras', 'description,owner_name,url_c,c_dims,url_h,h_dims,url_k,k_dims,url_o,o_dims,url_b,b_dims,url_z,z_dims,url_n,n_dims,url_m,m_dims,url_q,q_dims,url_t,t_dims,url_s,s_dims,media');
		$flickr_params['primary_photo_extras'] = apply_filters('photonic_flickr_query_primary_photo_extras', 'url_c,c_dims,url_h,h_dims,url_k,k_dims,url_o,o_dims,url_b,b_dims,url_z,z_dims,url_n,n_dims,url_m,m_dims,url_q,q_dims,url_t,t_dims,url_s,s_dims,media');

		$attr['iterate_level_3'] = $attr['collections_display'] === 'expanded';
		$attr['per_page'] = empty($attr['per_page']) ? $attr['count'] : $attr['per_page'];
		$attr['photo_count'] = empty($attr['photo_count']) ? $attr['per_page'] : $attr['photo_count'];

		$attr['overlay_size'] = empty($attr['overlay_size']) ? $attr['thumb_size'] : $attr['overlay_size'];
		$attr['overlay_video_size'] = empty($attr['overlay_video_size']) ? $attr['video_size'] : $attr['overlay_video_size'];

		if (empty($attr['group_id'])) {
			$user = empty($attr['user_id']) ? $photonic_flickr_default_user : $attr['user_id'];
		}

		if (isset($view) && $view == 'photos' && !empty($attr['group_id']) && empty($attr['photoset_id'])) {
			$query_urls[] = $this->base_url.'?method=flickr.photos.search';
		}
		else if (isset($view) && $view == 'photo' && !empty($attr['photo_id'])) {
			$query_urls[] = $this->base_url.'?method=flickr.photos.getInfo';
		}
		else if (isset($view) && (!empty($user))) {
			switch ($view) {
				case 'collections':
					if (empty($attr['collection_id'])) {
						$collections = $this->get_collection_list($user, '', $attr['filter']);
						foreach ($collections as $collection) {
							$query_urls[] = $this->base_url.'?method=flickr.collections.getTree&collection_id='.$collection['id'];
						}
					}
					break;

				case 'galleries':
					if (empty($attr['gallery_id'])) {
						$query_urls[] = $this->base_url.'?method=flickr.galleries.getList';
					}
					break;

				case 'photosets':
					if (empty($attr['photoset_id'])) {
						$query_urls[] = $this->base_url.'?method=flickr.photosets.getList';
					}
					break;

				case 'photo':
					if (!empty($attr['photo_id'])) {
						$query_urls[] = $this->base_url.'?method=flickr.photos.getInfo';
					}
					break;

				case 'photos':
				default:
					if (empty($attr['photoset_id']) && empty($attr['gallery_id']) && empty($attr['collection_id']) && empty($attr['photo_id']))
					$query_urls[] = $this->base_url.'?method=flickr.photos.search';
					break;
			}
		}

		// Collection > galleries > photosets
		if (!empty($attr['collection_id'])) {
			$collections = $this->get_collection_list($user, $attr['collection_id']);
			$attr['iterate_level_3'] = true;
			foreach ($collections as $collection) {
				$query_urls[] = $this->base_url.'?method=flickr.collections.getTree&collection_id='.$collection['id'];
			}
		}
		else if (!empty($attr['gallery_id'])) {
			if (empty($gallery_id_computed)) {
				if (empty($user)) {
					$this->pop_from_stack();
					return [new Error(esc_html__('User id or default user is required for displaying a single gallery', 'photonic'))];
				}

				$this->push_to_stack("Gallery list (user '$user')");
				$feed = $this->make_call($this->base_url.'?method=flickr.galleries.getList', $flickr_params);

				if (!is_wp_error($feed)) {
					if ($feed['response']['code'] == 200) {
						$feed = $feed['body'];
						$feed = json_decode($feed);
						if (isset($feed->galleries)) {
							$galleries = $feed->galleries;
							$galleries = $galleries->gallery;
							if (is_array($galleries) && count($galleries) > 0) {
								$gallery = $galleries[0];
								$global_dbid = $gallery->id;
								$global_dbid = substr($global_dbid, 0, stripos($global_dbid, '-'));
							}
						}
					}
				}

				if (isset($global_dbid)) {
					$attr['gallery_id'] = $global_dbid.'-'.$attr['gallery_id'];
				}
				$this->pop_from_stack();
			}
			$query_urls[] = $this->base_url.'?method=flickr.galleries.getInfo';
			$query_urls[] = $this->base_url.'?method=flickr.galleries.getPhotos';
		}
		else if (!empty($attr['photoset_id'])) {
			$query_urls[] = $this->base_url.'?method=flickr.photosets.getInfo';
			$query_urls[] = $this->base_url.'?method=flickr.photosets.getPhotos';
		}
		else if (empty($attr['photo_id']) && empty($user) && empty($attr['group_id'])) {
			$query_urls[] = $this->base_url.'?method=flickr.photos.search';
		}

		if (!empty($user) && empty($photoset_id) && empty($photo_id)) {
			$flickr_params['user_id'] = $user;
		}

		if (!empty($attr['collection_id'])) {
			$flickr_params['collection_id'] = $attr['collection_id'];
		}
		else if (!empty($attr['gallery_id'])) {
			$flickr_params['gallery_id'] = $attr['gallery_id'];
		}
		else if (!empty($attr['photoset_id'])) {
			$flickr_params['photoset_id'] = $attr['photoset_id'];
		}
		else if (!empty($attr['photo_id'])) {
			$flickr_params['photo_id'] = $attr['photo_id'];
		}

		if (!empty($attr['tags'])) {
			$flickr_params['tags'] = $attr['tags'];
		}

		if (!empty($attr['tag_mode'])) {
			$flickr_params['tag_mode'] = $attr['tag_mode'];
		}

		if (!empty($attr['text'])) {
			$flickr_params['text'] = $attr['text'];
		}

		if (!empty($attr['sort'])) {
			$flickr_params['sort'] = $attr['sort'];
		}

		if (!empty($attr['group_id'])) {
			$flickr_params['group_id'] = $attr['group_id'];
		}

		if (!empty($attr['per_page'])) {
			$flickr_params['per_page'] = $attr['per_page'];
		}

		if (!empty($attr['page'])) {
			$flickr_params['page'] = $attr['page'];
		}

		if (!empty($attr['privacy_filter'])) {
			$flickr_params['privacy_filter'] = $attr['privacy_filter'];
		}

		if (!empty($attr['media'])) {
			$flickr_params['media'] = $attr['media'];
		}

		// Allow users to define additional query parameters
		$query_urls = apply_filters('photonic_flickr_query_urls', $query_urls, $attr);
		$header_display = $this->get_header_display($attr);
		$attr['header_display'] = $header_display;

		$components = [];
		foreach ($query_urls as $query_url) {
			$method = 'flickr.photos.getInfo';
			$iterator = [];
			if (is_array($query_url)) {
				$iterator = $query_url;
			}
			else {
				$iterator[] = $query_url;
			}

			foreach ($iterator as $nested_query_url) {
				$this->push_to_stack("Nested call $method");
				$method = wp_parse_args(substr($nested_query_url, stripos($nested_query_url, '?') + 1));
				$method = $method['method'];
				$response = $this->make_call($nested_query_url, $flickr_params);
				$flickr_params['method'] = $method;

				$processed_response = $this->process_query($response, $flickr_params, $attr);
				$components = array_merge($components, $processed_response);
				$this->pop_from_stack();
			}
		}
		$this->pop_from_stack();

		if (!empty($this->stack_trace[$this->gallery_index])) {
			$components[] = $this->stack_trace[$this->gallery_index];
		}
		return $components;
	}

	function make_call($query, $flickr_params) {
		$params = substr($query, strlen($this->base_url));
		if (strlen($params) > 1) {
			$params = substr($params, 1);
		}
		$params = Core::parse_parameters($params);
		$params['format'] = 'json';
		$params['nojsoncallback'] = 1;
		$params['api_key'] = $this->api_key;

		$params = array_merge($flickr_params, $params);

		// We only worry about signing the call if the authentication is done. Otherwise we just show what is available.
		if ($this->oauth_done) {
			$signed_args = $this->sign_call($this->base_url, 'GET', $params);
			$params = $signed_args;
		}

		$this->push_to_stack("Make call ({$params['method']})");
		$response = Photonic::http($this->base_url, 'GET', $params);
		$this->pop_from_stack();
		return $response;
	}

	/**
	 * Retrieves a list of collection objects for a given user. This first invokes the web-service, then iterates through the collections returned.
	 * For each collection returned it recursively looks for nested collections and sets.
	 *
	 * @param $user_id
	 * @param string $collection_id
	 * @param string $filters
	 * @return array
	 */
	function get_collection_list($user_id, $collection_id = '', $filters = '') {
		$this->push_to_stack("Collection list (collection id '$collection_id')");
		$query = $this->base_url.'?method=flickr.collections.getTree';
		$flickr_params = [];
		if (!empty($collection_id)) {
			$flickr_params['collection_id'] = $collection_id;
		}
		if (!empty($user_id)) {
			$flickr_params['user_id'] = $user_id;
		}

		$collection_list = [];
		if (!empty($filters)) {
			$collection_list = explode(',', $filters);
		}

		$feed = $this->make_call($query, $flickr_params);
		if (!is_wp_error($feed) && 200 == $feed['response']['code']) {
			$feed = $feed['body'];
			$feed = json_decode($feed);
			if ($feed->stat == 'ok') {
				$collections = $feed->collections;
				$collections = $collections->collection;
				$ret = [];
				$processed = [];
				foreach ($collections as $collection) {
					if (isset($collection->id)) {
						if (!in_array($collection->id, $processed)) {
							$iterative = $this->get_nested_collections($collection, $processed);
							$ret = array_merge($ret, $iterative);
						}
					}
				}

				$filtered_ret = [];
				if (!empty($collection_list)) {
					foreach ($ret as $collection) {
						if (in_array($collection['id'], $collection_list)) {
							$filtered_ret[] = $collection;
						}
					}
					$this->pop_from_stack();
					return $filtered_ret;
				}

				$this->pop_from_stack();
				return $ret;
			}
		}
		$this->pop_from_stack();
		return [];
	}

	/**
	 * Goes through a Flickr collection and recursively fetches all sets and other collections within it. This is returned as
	 * a flattened array.
	 *
	 * @param $collection
	 * @param $processed
	 * @return array
	 */
	function get_nested_collections($collection, &$processed) {
		$id = isset($collection->id) ? (string)$collection->id : '';
		if (in_array($id, $processed)) {
			return [];
		}

		$processed[] = $id;
		$id = substr($id, strpos($id, '-') + 1);
		$title = isset($collection->title) ? (string)$collection->title : '';
		$description = isset($collection->description) ? (string)$collection->description : '';
		$thumb = isset($collection->iconsmall) ? (string)$collection->iconsmall : (isset($collection->iconlarge) ? (string)$collection->iconlarge : '');
		$thumb = ($thumb == '/images/collection_default_l.gif' || $thumb = '/images/collection_default_s.gif') ? 'https://www.flickr.com'.$thumb : $thumb;

		$ret = [];

		if (isset($collection->set)) {
			$inner_sets = $collection->set;
			$sets = [];
			if (count($inner_sets) > 0) {
				foreach ($inner_sets as $inner_set) {
					$sets[] = [
						'id' => (string)$inner_set->id,
						'title' => (string)$inner_set->title,
						'description' => (string)$inner_set->description,
					];
				}
			}

			$ret[] = [
				'id' => $id,
				'title' => $title,
				'description' => $description,
				'thumb' => $thumb,
				'sets' => $sets,
			];
		}

		if (isset($collection->collection)) {
			$inner_collections = $collection->collection;
			if (count($inner_collections) > 0) {
				foreach ($inner_collections as $inner_collection) {
					$inner_ret = $this->get_nested_collections($inner_collection, $processed);
					$ret = array_merge($ret, $inner_ret);
				}
			}
		}
		return $ret;
	}

	function process_query($response, $flickr_params, $short_code = []) {
		$this->push_to_stack('Process response');

		$filter_list = [];
		if (!empty($short_code['filter'])) {
			$filter_list = explode(',', $short_code['filter']);
		}

		$components = [];
		if (!is_wp_error($response)) {
			if ($response['response']['code'] == 200) {
				$body = $response['body'];
				$body = json_decode($body);
				switch ($flickr_params['method']) {
					case 'flickr.photos.getInfo':
						if (isset($body->photo)) {
							$photo = $body->photo;
							$components[] = $this->get_single_photo($photo, $short_code, $flickr_params);
						}
						break;

					case 'flickr.photos.search':
						if (isset($body->photos) && isset($body->photos->photo)) {
							$photos = $body->photos->photo;
							$components[] = $this->get_photo_list($photos, 'stream', $flickr_params, $short_code, $this->get_pagination($body->photos));
						}
						break;

					case 'flickr.photosets.getInfo':
						if (isset($body->photoset)) {
							$photoset = $body->photoset;
							$components[] = $this->get_photoset_header($photoset, $short_code);
						}
						break;

					case 'flickr.photosets.getPhotos':
						if (isset($body->photoset)) {
							$photoset = $body->photoset;
							if (isset($photoset->photo) && isset($photoset->owner)) {
								$photos = $photoset->photo;
								$options = [];
								if (isset($photoset->owner)) {
									$options['owner'] = $photoset->owner;
								}
								$components[] = $this->get_photo_list($photos, 'set', $flickr_params, $short_code, $this->get_pagination($photoset), $options);
							}
						}
						break;

					case 'flickr.photosets.getList':
						if (isset($body->photosets)) {
							$photosets = $body->photosets;
							$components[] = $this->get_photoset_list($photosets, $filter_list, $short_code);
						}
						break;

					case 'flickr.galleries.getInfo':
						if (isset($body->gallery)) {
							$gallery = $body->gallery;
							$components[] = $this->get_gallery_header($gallery, $short_code);
						}
						break;

					case 'flickr.galleries.getPhotos':
						if (isset($body->photos)) {
							$photos = $body->photos;
							if (isset($photos->photo)) {
								$photos = $photos->photo;
								$components[] = $this->get_photo_list($photos, 'gallery', $flickr_params, $short_code, $this->get_pagination($body->photos));
							}
						}
						break;

					case 'flickr.galleries.getList':
						if (isset($body->galleries)) {
							$galleries = $body->galleries;
							$components[] = $this->get_gallery_list($galleries, $filter_list, $short_code);
						}
						break;

					case 'flickr.collections.getTree':
						if (isset($body->collections)) {
							$collections = $body->collections;
							$components[] = $this->get_collections($collections, $short_code);
						}
						break;
				}
			}
		}
		else {
			$this->pop_from_stack();
			return [new Error($this->wp_error_message($response))];
		}

		$this->pop_from_stack();
		return $components;
	}

	/**
	 * Prints a single photo with the title as an <h3> and the caption as the image caption.
	 *
	 * @param $photo
	 * @param $short_code
	 * @param $flickr_params
	 * @return Single_Photo
	 */
	function get_single_photo($photo, $short_code, $flickr_params) {
		$main_size = $short_code['main_size'] == 'none' ? '' : $short_code['main_size'];
		$main_image = 'https://farm'.$photo->farm.'.staticflickr.com/'.$photo->server.'/'.(!empty($photo->primary) ? $photo->primary : $photo->id).'_'.$photo->secret.'_z'.'.jpg';

		$size_response = $this->make_call($this->base_url.'?method=flickr.photos.getSizes&photo_id='.$photo->id, $flickr_params);
		if (!is_wp_error($size_response) && 200 == $size_response['response']['code']) {
			$size_response = $size_response['body'];
			$size_response = json_decode($size_response);
			if ($size_response->stat == 'ok') {
				$size_response = $size_response->sizes;
				$size_response = $size_response->size;
				if (is_array($size_response)) {
					$sizes = [
						'o' => 'Original',
						'k' => 'Large 2048',
						'h' => 'Large 1600',
						'b' => 'Large',
						'c' => 'Medium 800',
						'z' => 'Medium 640',
						'' => 'Medium',
						'n' => 'Small 320',
						'm' => 'Small',
						'q' => 'Large Square',
						't' => 'Thumbnail',
						's' => 'Square',
					];

					$max_to_min = array_keys($sizes);

					$pos = array_search($main_size, $max_to_min);
					for ($idx = $pos; $idx < count($max_to_min); $idx++) {
						foreach ($size_response as $flickr_size) {
							if ($flickr_size->label == $sizes[$max_to_min[$idx]]) {
								$main_image = $flickr_size->source;
								$match_found = true;
								break;
							}
						}
						if ($match_found) {
							break;
						}
					}
				}
			}
		}

		return new Single_Photo(
			$main_image,
			(isset($photo->urls) && isset($photo->urls->url) && count($photo->urls->url) > 0) ? $photo->urls->url[0]->_content : '',
			isset($photo->title) ? $photo->title->_content : '',
			isset($photo->description) ? $photo->description->_content : ''
		);
	}

	/**
	 * Prints thumbnails for all photos returned in a query. This is used for printing the results of a search, tag, photoset or gallery.
	 * The photos are printed in-page.
	 *
	 * @param $photos
	 * @param string $parent
	 * @param $flickr_params
	 * @param $short_code
	 * @param Pagination $pagination
	 * @param array $options
	 * @return Photo_List
	 */
	function get_photo_list($photos, $parent, $flickr_params, $short_code, $pagination, $options = []) {
		global $photonic_flickr_photo_title_display, $photonic_flickr_photo_pop_title_display;
		global $photonic_flickr_photos_per_row_constraint, $photonic_flickr_photos_constrain_by_padding, $photonic_flickr_photos_constrain_by_count;

		if ($short_code['display'] == 'in-page') {
			$title_position = $photonic_flickr_photo_title_display;
			$row_constraints = ['constraint-type' => $photonic_flickr_photos_per_row_constraint, 'padding' => $photonic_flickr_photos_constrain_by_padding, 'count' => $photonic_flickr_photos_constrain_by_count];
		}
		else {
			$title_position = $photonic_flickr_photo_pop_title_display;
			$row_constraints = ['constraint-type' => 'padding'];
		}
		$photo_objects = $this->build_level_1_objects($photos, $short_code, $flickr_params, array_merge(['parent' => $parent], $options));

		$photo_list = new Photo_List($short_code);
		$photo_list->photos = $photo_objects;
		$photo_list->title_position = $title_position;
		$photo_list->row_constraints = $row_constraints;
		$photo_list->parent = $parent;
		$photo_list->pagination = $pagination;

		return $photo_list;
	}

	function find_largest_image($photo, $size = 'o', &$dimensions = []) {
/*		if (in_array($size, ['z','','n','m','q','t','s'])) {
			$ret_size = $size == '' ? '' : '_'.$size;
			$photo_id = !empty($photo->primary) ? $photo->primary : $photo->id;
			return 'https://farm'.$photo->farm.'.staticflickr.com/'.$photo->server.'/'.$photo_id.'_'.$photo->secret.$ret_size.'.jpg';
		}*/

//		$max_to_min = ['o','k','h','b','c'];
		$max_to_min = ['o','k','h','b','c','z','m','n','s','q','t'];
		if ($size == '') {
			$size = 'm';
		}

		$pos = array_search($size, $max_to_min);
		if ($pos !== FALSE) {
			for ($idx = $pos; $idx < count($max_to_min); $idx++) {
				$value = $max_to_min[$idx];
				if (isset($photo->{'url_'.$value})) {
					$dimensions['w'] = $photo->{'width_'.$value};
					$dimensions['h'] = $photo->{'height_'.$value};
					return $photo->{'url_'.$value};
				}
				else if (isset($photo->primary_photo_extras) && isset($photo->primary_photo_extras->{'url_'.$value})) {
					$dimensions['w'] = $photo->primary_photo_extras->{'width_'.$value};
					$dimensions['h'] = $photo->primary_photo_extras->{'height_'.$value};
					return $photo->primary_photo_extras->{'url_'.$value};
				}
			}
		}

		if (isset($photo->width_z) && isset($photo->height_z)) {
			$dimensions['w'] = $photo->width_z;
			$dimensions['h'] = $photo->height_z;
		}
		return 'https://farm'.$photo->farm.'.staticflickr.com/'.$photo->server.'/'.(!empty($photo->primary) ? $photo->primary : $photo->id).'_'.$photo->secret.'_z'.'.jpg';
	}

	function find_largest_video_thumb(&$photo_struct, $current_sizes, $shortcode_sizes) {
		$video_sizes = [
			'o' => 'Original',
			'k' => 'Large 2048',
			'h' => 'Large 1600',
			'b' => 'Large',
			'c' => 'Medium 800',
			'z' => 'Medium 640',
			'' => 'Medium',
			'n' => 'Small 320',
			'm' => 'Small',
			'q' => 'Large Square',
			't' => 'Thumbnail',
			's' => 'Square',
		];

		$max_to_min = array_keys($video_sizes);
		foreach ($shortcode_sizes as $type => $size) {
			$match_found = false;
			$pos = array_search($size, $max_to_min);
			for ($idx = $pos; $idx < count($max_to_min); $idx++) {
				foreach ($current_sizes as $flickr_size) {
					if ($flickr_size->label == $video_sizes[$max_to_min[$idx]]) {
						$photo_struct->{$type} = $flickr_size->source;
						$match_found = true;
						break;
					}
				}
				if ($match_found) {
					break;
				}
			}
		}
	}

	function build_level_1_objects($response, array $shortcode, $module_parameters = [], $options = []) {
		$photo_objects = [];
		$video_size = in_array($this->library, ['baguettebox', 'colorbox', 'fancybox', 'fancybox2', 'fancybox3', 'featherlight', 'glightbox', 'lightgallery', 'magnific', 'photoswipe', 'spotlight', 'swipebox']) ? $shortcode['video_size'] : 'Video Player';
//		$video_size = $short_code['video_size'];

		$main_size = $shortcode['main_size'] == 'none' ? '' : $shortcode['main_size'];
		$tile_size = (empty($shortcode['tile_size']) || $shortcode['tile_size'] == 'same') ? $main_size : ($shortcode['tile_size'] == 'none' ? '' : $shortcode['tile_size']);

		foreach ($response as $photo) {
			$photonic_photo = new Photo();

			$photonic_photo->thumbnail = 'https://farm'.$photo->farm.'.staticflickr.com/'.$photo->server.'/'.$photo->id.'_'.$photo->secret.'_'.$shortcode['thumb_size'].'.jpg';
			$photonic_photo->thumb_size = [
				'w' => $shortcode['thumb_size'] == 's' ? 75 : ($shortcode['thumb_size'] == 'q' ? 150 : $photo->{'width_'.$shortcode['thumb_size']}),
				'h' => $shortcode['thumb_size'] == 's' ? 75 : ($shortcode['thumb_size'] == 'q' ? 150 : $photo->{'height_'.$shortcode['thumb_size']}),
			];

			$main_dim = [];
			$photonic_photo->main_image = $this->find_largest_image($photo, $main_size, $main_dim);
			$photonic_photo->main_size = $main_dim;

			$download = $this->find_largest_image($photo);
			$photonic_photo->download = substr($download, 0, strlen($download)-4).'_d'.substr($download, -4);

			$tile_dim = $main_dim;
			$photonic_photo->tile_image = $tile_size == $main_size ? $photonic_photo->main_image : $this->find_largest_image($photo, $tile_size, $tile_dim);
			$photonic_photo->tile_size = $tile_dim;
			$photonic_photo->alt_title = esc_attr($photo->title);

			$owner = '';
			if (!empty($options['owner'])) {
				$owner = $options['owner'];
			}
			else if (isset($photo->owner)) {
				$owner = $photo->owner;
			}
			else if (isset($photo->ownername)) {
				$owner = $photo->ownername;
			}

			$specific = '';
			if ($options['parent'] === 'set' && !empty($module_parameters['photoset_id'])) {
				$specific = '/in/set-'.$module_parameters['photoset_id'];
			}
			$url = "https://www.flickr.com/photos/".$owner."/".$photo->id.$specific;
			$photonic_photo->main_page = $url;

			$title = $photo->title;
			$photonic_photo->title = $title;

			if (isset($photo->description)) {
				$photonic_photo->description = $photo->description->_content;
			}
			else {
				$photonic_photo->description = '';
			}

			if (!empty($photo->media) && $photo->media == 'video') {
				$video_response = $this->make_call($this->base_url.'?method=flickr.photos.getSizes&photo_id='.$photo->id, $module_parameters);
				if (!is_wp_error($video_response) && 200 == $video_response['response']['code']) {
					$video_response = $video_response['body'];
					$video_response = json_decode($video_response);
					if ($video_response->stat == 'ok') {
						$video_response = $video_response->sizes;
						$video_response = $video_response->size;
						if (is_array($video_response)) {
							$this->find_largest_video_thumb($photonic_photo, $video_response, [
								'main_image' => $main_size,
								'tile_image' => $tile_size,
								'thumbnail' => $shortcode['thumb_size'],
							]);

							foreach ($video_response as $size) {
								if ($size->label != $video_size) {
									continue;
								}
								else {
									$photonic_photo->video = $size->source;
									$photonic_photo->mime = 'video/mp4';
									break;
								}
							}
						}
					}
				}
			}

			$photonic_photo->id = $photo->id;

			$photo_objects[] = $photonic_photo;
		}

		return $photo_objects;
	}

	function build_level_2_objects($flickr_objects, $short_code = [], $filter_list = [], &$options = []) {
		global $photonic_gallery_template_page;

		$main_size = $short_code['main_size'] == 'none' ? '' : $short_code['main_size'];
		$tile_size = (empty($short_code['tile_size']) || $short_code['tile_size'] == 'same') ? $main_size : ($short_code['tile_size'] == 'none' ? '' : $short_code['tile_size']);

		$objects = [];

		$type = $options['type'];
		foreach ($flickr_objects as $flickr_object) {
			if (!empty($filter_list) &&
				(($type == 'photoset' && ((!in_array($flickr_object->id, $filter_list) && strtolower($short_code['filter_type']) !== 'exclude') ||
							(in_array($flickr_object->id, $filter_list) && strtolower($short_code['filter_type']) === 'exclude'))) ||
					($type == 'gallery' && ((!in_array(substr($flickr_object->id, stripos($flickr_object->id, '-') + 1), $filter_list) && strtolower($short_code['filter_type']) !== 'exclude') ||
							(in_array(substr($flickr_object->id, stripos($flickr_object->id, '-') + 1), $filter_list) && strtolower($short_code['filter_type']) === 'exclude'))))) {
				continue;
			}

			$photonic_album = new Album();

			$internal_short_code = $short_code;
			unset($internal_short_code['collection_id']);
			$internal_short_code['layout'] = empty($short_code['photo_layout']) ? $short_code['layout'] : $short_code['photo_layout'];

			$photonic_album->id = $flickr_object->id;
			$photonic_album->title = esc_attr($flickr_object->title->_content);
			$photonic_album->description = esc_attr($flickr_object->description->_content);

			if ($type == 'gallery') {
				$photonic_album->thumbnail = "https://farm".$flickr_object->primary_photo_farm.".staticflickr.com/".$flickr_object->primary_photo_server."/".$flickr_object->primary_photo_id."_".$flickr_object->primary_photo_secret."_".$short_code['thumb_size'].".jpg";
				$photonic_album->tile_image = "https://farm".$flickr_object->primary_photo_farm.".staticflickr.com/".$flickr_object->primary_photo_server."/".$flickr_object->primary_photo_id."_".$flickr_object->primary_photo_secret.'_'.$tile_size.".jpg";
				$photonic_album->tile_size = [
					'w' => $tile_size == 's' ? 75 : ($tile_size == 'q' ? 150 : $flickr_object->primary_photo_extras->{'width_'.$tile_size}),
					'h' => $tile_size == 's' ? 75 : ($tile_size == 'q' ? 150 : $flickr_object->primary_photo_extras->{'height_'.$tile_size}),
				];

				$photonic_album->main_page = $flickr_object->url;
				$photonic_album->counter = $flickr_object->count_photos;
				$photonic_album->classes = ["photonic-flickr-gallery-thumb-user-{$short_code['user_id']}"];

				$internal_short_code['view'] = 'gallery';
				$internal_short_code['gallery_id'] = $flickr_object->id;
			}
			else if ($type == 'photoset') {
				$photonic_album->thumbnail = "https://farm".$flickr_object->farm.".staticflickr.com/".$flickr_object->server."/".$flickr_object->primary."_".$flickr_object->secret."_".$short_code['thumb_size'].".jpg";
				$photonic_album->tile_image = $this->find_largest_image($flickr_object, $tile_size, $photonic_album->tile_size);

				$owner = isset($flickr_object->owner) ? $flickr_object->owner : $short_code['user_id'];
				$photonic_album->main_page = "https://www.flickr.com/photos/$owner/sets/{$flickr_object->id}";
				$photonic_album->counter = $flickr_object->photos;

				$internal_short_code['view'] = 'photoset';
				$internal_short_code['photoset_id'] = $flickr_object->id;
			}

			$photonic_album->thumb_size = [
				'w' => $short_code['thumb_size'] == 's' ? 75 : ($short_code['thumb_size'] == 'q' ? 150 : $flickr_object->primary_photo_extras->{'width_'.$short_code['thumb_size']}),
				'h' => $short_code['thumb_size'] == 's' ? 75 : ($short_code['thumb_size'] == 'q' ? 150 : $flickr_object->primary_photo_extras->{'height_'.$short_code['thumb_size']}),
			];

			$photonic_album->data_attributes = [
				'photo-count' => $short_code['photo_count'],
				'photo-more' => empty($short_code['photo_more']) ? '' : $short_code['photo_more'],
				'overlay-size' => $short_code['overlay_size'],
				'overlay-video-size' => $short_code['overlay_video_size'],
			];

			if ($short_code['popup'] == 'page' && !empty($photonic_gallery_template_page) && is_string(get_post_status($photonic_gallery_template_page))) {
				$photonic_album->gallery_url = $this->get_gallery_url($internal_short_code, [
					'title' => $photonic_album->title,
				]);
			}

			$objects[] = $photonic_album;
		}
		return $objects;
	}

	/**
	 * Prints the header for an in-page photoset.
	 *
	 * @param $photoset
	 * @param array $short_code
	 * @return Header
	 */
	function get_photoset_header($photoset, $short_code = []) {
		global $photonic_flickr_hide_set_thumbnail, $photonic_flickr_hide_set_title, $photonic_flickr_hide_set_photo_count;

		$owner = $photoset->owner;
		$hidden = ['thumbnail' => !empty($photonic_flickr_hide_set_thumbnail), 'title' => !empty($photonic_flickr_hide_set_title), 'counter' => !empty($photonic_flickr_hide_set_photo_count)];

		$header = new Header();
		$header->title = $photoset->title->_content;
		$header->description = $photoset->description->_content;
		$header->thumb_url = "https://farm{$photoset->farm}.staticflickr.com/{$photoset->server}/{$photoset->primary}_{$photoset->secret}_{$short_code['thumb_size']}.jpg";
		$header->page_url = 'https://www.flickr.com/photos/'.$owner.'/sets/'.$photoset->id;

		$header->header_for = 'set';
		$header->hidden_elements = $this->get_hidden_headers($short_code['header_display'], $hidden);
		$header->counters = ['photos' => $photoset->photos];
		$header->enable_link = true;
		$header->display_location = $short_code['display'];

		return $header;
	}

	/**
	 * Prints thumbnails for each photoset returned in a query.
	 *
	 * @param $photosets
	 * @param array $filter_list
	 * @param array $short_code
	 * @return Album_List
	 */
	function get_photoset_list($photosets, $filter_list = [], $short_code = []) {
		global $photonic_flickr_collection_set_per_row_constraint, $photonic_flickr_collection_set_constrain_by_count, $photonic_flickr_collection_set_constrain_by_padding,
			$photonic_flickr_collection_set_title_display, $photonic_flickr_hide_collection_set_photos_count_display;
		$options = ['type' => 'photoset'];
		$objects = $this->build_level_2_objects($photosets->photoset, $short_code, $filter_list, $options);

		$row_constraints = ['constraint-type' => $photonic_flickr_collection_set_per_row_constraint, 'padding' => $photonic_flickr_collection_set_constrain_by_padding, 'count' => $photonic_flickr_collection_set_constrain_by_count];

		$album_list = new Album_List($short_code);
		$album_list->albums = $objects;
		$album_list->row_constraints = $row_constraints;
		$album_list->type = 'photosets';
		$album_list->singular_type = 'set';
		$album_list->title_position = $photonic_flickr_collection_set_title_display;
		$album_list->level_1_count_display = $photonic_flickr_hide_collection_set_photos_count_display;
		$album_list->pagination = $this->get_pagination($photosets);

		return $album_list;
	}

	/**
	 * Shows the header for a gallery invoked in-page.
	 *
	 * @param $gallery
	 * @param $short_code
	 * @return Header
	 */
	function get_gallery_header($gallery, $short_code) {
		global $photonic_flickr_hide_gallery_thumbnail, $photonic_flickr_hide_gallery_title, $photonic_flickr_hide_gallery_photo_count;

		$hidden = ['thumbnail' => !empty($photonic_flickr_hide_gallery_thumbnail), 'title' => !empty($photonic_flickr_hide_gallery_title), 'counter' => !empty($photonic_flickr_hide_gallery_photo_count)];

		$header = new Header();
		$header->title = $gallery->title->_content;
		$header->description = $gallery->description->_content;
		$header->thumb_url = "https://farm{$gallery->primary_photo_farm}.staticflickr.com/{$gallery->primary_photo_server}/{$gallery->primary_photo_id}_{$gallery->primary_photo_secret}_{$short_code['thumb_size']}.jpg";
		$header->page_url = $gallery->url;

		$header->header_for = 'gallery';
		$header->hidden_elements = $this->get_hidden_headers($short_code['header_display'], $hidden);
		$header->counters = ['photos' => $gallery->count_photos];
		$header->enable_link = true;
		$header->display_location = $short_code['display'];

		return $header;
	}

	/**
	 * Prints out the thumbnails for all galleries belonging to a user.
	 *
	 * @param $galleries
	 * @param array $filter_list
	 * @param array $short_code
	 * @return Album_List
	 */
	function get_gallery_list($galleries, $filter_list = [], $short_code = []) {
		global $photonic_flickr_galleries_per_row_constraint, $photonic_flickr_galleries_constrain_by_padding,
			$photonic_flickr_galleries_constrain_by_count, $photonic_flickr_gallery_title_display, $photonic_flickr_hide_gallery_photos_count_display;

		$options = ['type' => 'gallery'];
		$objects = $this->build_level_2_objects($galleries->gallery, $short_code, $filter_list, $options);

		$row_constraints = ['constraint-type' => $photonic_flickr_galleries_per_row_constraint, 'padding' => $photonic_flickr_galleries_constrain_by_padding, 'count' => $photonic_flickr_galleries_constrain_by_count];

		$album_list = new Album_List($short_code);
		$album_list->albums = $objects;
		$album_list->row_constraints = $row_constraints;
		$album_list->type = 'galleries';
		$album_list->singular_type = 'gallery';
		$album_list->title_position = $photonic_flickr_gallery_title_display;
		$album_list->level_1_count_display = $photonic_flickr_hide_gallery_photos_count_display;
		$album_list->pagination = $this->get_pagination($galleries);

		return $album_list;
	}

	/**
	 * Prints a collection header, followed by thumbnails of all sets in that collection.
	 *
	 * @param $collections
	 * @param array $short_code
	 * @return Collection
	 */
	function get_collections($collections, $short_code = []) {
		global $photonic_flickr_hide_empty_collection_details, $photonic_flickr_collection_set_per_row_constraint, $photonic_flickr_collection_set_constrain_by_padding,
			   $photonic_flickr_collection_set_constrain_by_count, $photonic_flickr_hide_collection_thumbnail, $photonic_flickr_hide_collection_title, $photonic_flickr_hide_collection_set_count, $photonic_flickr_collection_set_title_display, $photonic_flickr_hide_collection_set_photos_count_display;
		$photonic_collections = new Collection();
		if (!empty($short_code['strip_top_level'])) {
			$photonic_collections->strip_top_level = true;
		}

		$row_constraints = ['constraint-type' => $photonic_flickr_collection_set_per_row_constraint, 'padding' => $photonic_flickr_collection_set_constrain_by_padding, 'count' => $photonic_flickr_collection_set_constrain_by_count];

		foreach ($collections->collection as $collection) {
			$photonic_collection = new Collection();
			if (!empty($short_code['strip_top_level'])) {
				$photonic_collection->strip_top_level = true;
			}

			$dont_show = false;
			if (empty($collection->set) && !empty($photonic_flickr_hide_empty_collection_details)) {
				$dont_show = true;
			}
			$id = $collection->id;
			if (!$dont_show) {
				$url_id = substr($id, stripos($id, '-') + 1);
				if ($collection->iconsmall == '/images/collection_default_s.gif') {
					$thumb = 'https://www.flickr.com'.$collection->iconsmall;
				}
				else {
					$thumb = $collection->iconsmall;
				}

				$hidden = ['thumbnail' => !empty($photonic_flickr_hide_collection_thumbnail), 'title' => !empty($photonic_flickr_hide_collection_title), 'counter' => !empty($photonic_flickr_hide_collection_set_count)];
				$counters = [];
				if (isset($collection->set)) {
					$photosets = $collection->set;
					$counters['sets'] = count($photosets);
				}

				$header = new Header();
				$header->id = $id.'-'.$short_code['user_id'];
				$header->title = $collection->title;
				$header->thumb_url = $thumb;
				$header->page_url = "https://www.flickr.com/photos/{$short_code['user_id']}/collections/$url_id";

				$header->header_for = 'collection';
				$header->hidden_elements = $this->get_hidden_headers($short_code['header_display'], $hidden);
				$header->counters = $counters;
				$header->enable_link = true;
				$header->iterate_level_3 = $short_code['iterate_level_3'];
				$header->layout = $short_code['layout'];

				$photonic_collection->header = $header;
			}

			if (isset($collection->set) && !empty($collection->set) && $short_code['iterate_level_3']) {
				$flickr_objects = [];
				$photosets = $collection->set;

				$parallel = [];
				$psets = [];
				$hooks = new Requests_Hooks();
				$hooks->register('curl.before_multi_add', [$this, 'ssl_verify_peer'], 100);
				foreach ($photosets as $set) {
					$parallel_params = [];
					$parallel_params['format'] = 'json';
					$parallel_params['nojsoncallback'] = 1;
					$parallel_params['api_key'] = $this->api_key;
					$parallel_params['method'] = 'flickr.photosets.getInfo';
					$parallel_params['photoset_id'] = $set->id;
					// We only worry about signing the call if the authentication is done. Otherwise we just show what is available.
					if ($this->oauth_done) {
						$signed_args = $this->sign_call($this->base_url, 'GET', $parallel_params);
						$parallel_params = $signed_args;
					}

					$parallel[] = [
						'url' => $this->base_url,
						'type' => 'GET',
						'data' => $parallel_params,
					];
					$psets[] = $set->id;
				}

				if (!empty($parallel)) {
					$parallel_responses = Requests::request_multiple($parallel, ['hooks' => $hooks]);
					foreach ($parallel_responses as $ps_response) {
						if (is_a($ps_response, 'Requests_Response')) {
							$ps_response = json_decode($ps_response->body);
							if (!empty($ps_response->photoset->id)) {
								$flickr_objects[array_search($ps_response->photoset->id, $psets)] = $ps_response->photoset;
							}
						}
					}
				}
				ksort($flickr_objects);

				$options = ['type' => 'photoset'];
				$objects = $this->build_level_2_objects($flickr_objects, $short_code, [], $options); // No filters passed for this

				$album_list = new Album_List($short_code);
				$album_list->albums = $objects;
				$album_list->row_constraints = $row_constraints;
				$album_list->type = 'photosets';
				$album_list->singular_type = 'set';
				$album_list->title_position = $photonic_flickr_collection_set_title_display;
				$album_list->level_1_count_display = $photonic_flickr_hide_collection_set_photos_count_display;

				$photonic_collection->album_list = $album_list;
			}
			$photonic_collections->collections[] = $photonic_collection;
		}

		return $photonic_collections;
	}

	/**
	 * Access Token URL
	 *
	 * @return string
	 */
	public function access_token_URL() {
		return 'https://www.flickr.com/services/oauth/access_token';
	}

	/**
	 * Authenticate URL
	 *
	 * @return string
	 */
	public function authenticate_URL() {
		return 'https://www.flickr.com/services/oauth/authorize';
	}

	/**
	 * Authorize URL
	 *
	 * @return string
	 */
	public function authorize_URL() {
		return 'https://www.flickr.com/services/oauth/authorize';
	}

	/**
	 * Request Token URL
	 *
	 * @return string
	 */
	public function request_token_URL() {
		return 'https://www.flickr.com/services/oauth/request_token';
	}

	/**
	 * Method to validate that the stored token is indeed authenticated.
	 *
	 * @param $token
	 * @return array|WP_Error
	 */
	function check_access_token($token) {
		$parameters = ['method' => 'flickr.test.login', 'format' => 'json', 'nojsoncallback' => 1];
		$signed_parameters = $this->sign_call($this->base_url, 'GET', $parameters);
		$end_point = $this->base_url;
		$end_point .= '?'.Authenticator::build_query($signed_parameters);
		$parameters = null;

		$response = Photonic::http($end_point, 'GET', $parameters);
		return $response;
	}

	function execute_helper($args) {
		if (!empty($args['user'])) {
			$url = 'https://api.flickr.com/services/rest/?format=json&nojsoncallback=1&api_key='.$this->api_key.'&method=flickr.urls.lookupUser&url='.urlencode('https://www.flickr.com/photos/').$args['user'];
		}
		else if (!empty($args['group'])) {
			$url = 'https://api.flickr.com/services/rest/?format=json&nojsoncallback=1&api_key='.$this->api_key.'&method=flickr.urls.lookupGroup&url='.urlencode('https://www.flickr.com/groups/').$args['group'];
		}
		else {
			return '<div class="photonic-helper">'.sprintf(esc_html__('Please pass the %1$s or %2$s attribute.', 'photonic'), '<code>user</code>', '<code>group</code>').'</div>';
		}

		$response = wp_remote_request($url, ['sslverify' => PHOTONIC_SSL_VERIFY]);
		if (!is_wp_error($response)) {
			if (isset($response['response']) && isset($response['response']['code'])) {
				if ($response['response']['code'] == 200) {
					$body = json_decode(wp_remote_retrieve_body($response));
					if (isset($body->stat) && $body->stat == 'fail') {
						Photonic::log($response);
						return '<div class="photonic-helper">'.$body->message.'</div>';
					}

					if (isset($body->user)) {
						return '<div class="photonic-helper">'.sprintf(esc_html__('User id: %s', 'photonic'), $body->user->id).'</div>';
					}
					else if (isset($body->group)) {
						return '<div class="photonic-helper">'.sprintf(esc_html__('Group id: %s', 'photonic'), $body->group->id).'</div>';
					}
					else {
						return '<div class="photonic-helper">'.esc_html__('No data returned.', 'photonic').'</div>';
					}
				}
				else {
					Photonic::log($response['response']);
					return '<div class="photonic-helper">'.sprintf(esc_html__('No data returned. Error code %s', 'photonic'), $response['response']['code']).'</div>';
				}
			}
			else {
				Photonic::log($response);
				return '<div class="photonic-helper">'.esc_html__('No data returned. Empty response, or empty error code.', 'photonic').'</div>';
			}
		}
		else {
			return '<div class="photonic-helper">'.$response->get_error_message().'</div>';
		}
	}

	/**
	 * @param $entity
	 * @return Pagination
	 */
	private function get_pagination($entity) {
		$per_page = isset($entity->perpage) ? $entity->perpage : $entity->per_page;

		$pagination = new Pagination();
		$pagination->total = $entity->total;
		$pagination->start = ($entity->page - 1) * $per_page + 1;
		$pagination->end = $entity->page * $per_page > $entity->total ? $entity->total : $entity->page * $per_page;
		$pagination->per_page = $per_page;

		return $pagination;
	}
}
