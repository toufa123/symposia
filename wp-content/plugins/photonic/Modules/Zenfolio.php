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
use WP_Error;

require_once('Core.php');
require_once('Level_One_Module.php');
require_once('Level_Two_Module.php');
require_once(PHOTONIC_PATH.'/Components/Collection.php');

/**
 * Processor for Zenfolio photos. This extends the Photonic_Plugin\Modules\Core class and defines methods local to Zenfolio.
 *
 */

class Zenfolio extends Core implements Level_One_Module, Level_Two_Module {
	var $user_name, $user_agent, $token, $service_url, $secure_url, $unlocked_realms, $temp_keyring, $standard_sizes;
	private static $instance = null;

	protected function __construct() {
		parent::__construct();
		global $photonic_zenfolio_disable_title_link;
		$this->provider = 'zenfolio';
		$this->user_agent = "Photonic for ".get_home_url();
		$this->link_lightbox_title = empty($photonic_zenfolio_disable_title_link);
		$this->service_url = 'https://api.zenfolio.com/api/1.8/zfapi.asmx';
		$this->secure_url = 'https://api.zenfolio.com/api/1.8/zfapi.asmx';
		$this->unlocked_realms = [];
		$this->temp_keyring = '';

		$this->doc_links = [
			'general' => 'https://aquoid.com/plugins/photonic/zenfolio/',
			'photos' => 'https://aquoid.com/plugins/photonic/zenfolio/photos/',
			'photosets' => 'https://aquoid.com/plugins/photonic/zenfolio/photosets/',
			'groups' => 'https://aquoid.com/plugins/photonic/zenfolio/group/',
			'hierarchies' => 'https://aquoid.com/plugins/photonic/zenfolio/group-hierarchy/',
		];
		$this->perform_back_end_authentication();
		$this->standard_sizes = [
			0 => ['w' => 80, 'h' => 80, 'c' => 0],
			1 => ['w' => 60, 'h' => 60, 'c' => 1],
			2 => ['w' => 400, 'h' => 400, 'c' => 0],
			3 => ['w' => 580, 'h' => 450, 'c' => 0],
			4 => ['w' => 800, 'h' => 630, 'c' => 0],
			5 => ['w' => 1100, 'h' => 850, 'c' => 0],
			6 => ['w' => 1550, 'h' => 960, 'c' => 0],
			10 => ['w' => 120, 'h' => 120, 'c' => 0],
			11 => ['w' => 120, 'h' => 120, 'c' => 0],
		];
	}

	public static function get_instance() {
		if (self::$instance == null) {
			self::$instance = new Zenfolio();
		}
		return self::$instance;
	}

	/**
	 * Main function that fetches the images associated with the shortcode.
	 *
	 * @param array $attr
	 * @return string|array
	 */
	public function get_gallery_images($attr = []) {
		global $photonic_zenfolio_thumb_size, $photonic_zenfolio_main_size, $photonic_zenfolio_tile_size, $photonic_zenfolio_title_caption, $photonic_zenfolio_video_size, $photonic_zenfolio_media, $photonic_zenfolio_default_user;
		$this->push_to_stack('Get Gallery Images');

		$attr = array_merge(
			$this->common_parameters,
			[
				'caption' => $photonic_zenfolio_title_caption,
				'thumb_size' => $photonic_zenfolio_thumb_size,
				'main_size' => $photonic_zenfolio_main_size,
				'video_size' => $photonic_zenfolio_video_size,
				'tile_size' => $photonic_zenfolio_tile_size,

				'count' => 500,
				'offset' => 0,
				'media' => $photonic_zenfolio_media,
				'structure' => 'nested',
				'login_name' => $photonic_zenfolio_default_user,
			], $attr);
		$attr = array_map('trim', $attr);

		$attr['limit'] = empty($attr['limit']) ? $attr['count'] : $attr['limit'];
		$attr['photo_count'] = empty($attr['photo_count']) ? $attr['limit'] : $attr['photo_count'];

		$attr['overlay_size'] = empty($attr['overlay_size']) ? $attr['thumb_size'] : $attr['overlay_size'];
		$attr['overlay_video_size'] = empty($attr['overlay_video_size']) ? $attr['video_size'] : $attr['overlay_video_size'];

		extract($attr);

		if (isset($_COOKIE['photonic-zf-keyring'])) {
			$realms = $this->make_wp_call('KeyringGetUnlockedRealms', ['keyring' => $_COOKIE['photonic-zf-keyring']]);
			if (!empty($realms) && !empty($realms->result)) {
				$this->unlocked_realms = $realms->result;
			}
		}

		$chained_methods = [];
		$zenfolio_params = [];
		$attr['headers_already_called'] = true;
		if (!empty($attr['view'])) {
			switch ($attr['view']) {
				case 'photos':
					if (!empty($object_id)) {
						$chained_methods[] = 'LoadPhoto';
						if(($h = stripos($object_id, 'h')) !== false) {
							$object_id = substr($object_id, $h + 1);
							$object_id = hexdec($object_id);
						}
						else if (($p = stripos($object_id, 'p')) !== false) {
							$object_id = substr($object_id, $p + 1);
						}
						else if (strlen($object_id) == 7) {
							$object_id = hexdec($object_id);
						}

						$zenfolio_params['photoId'] = $object_id;
						$zenfolio_params['level'] = 'Full';
					}
					else if (!empty($text) || !empty($category_code)) {
						$zenfolio_params['searchId'] = '';
						$zenfolio_params['sortOrder'] = !empty($sort_order) ? $sort_order : 'Date'; // Popularity | Date | Rank
						if (!empty($text)) {
							$zenfolio_params['query'] = $text;
							$chained_methods[] = 'SearchPhotoByText';
						}
						else if (!empty($category_code)) {
							$zenfolio_params['categoryCode'] = $category_code;
							$chained_methods[] = 'SearchPhotoByCategory';
						}
						$zenfolio_params['offset'] = $attr['offset'];
						$zenfolio_params['limit'] = $attr['limit'];
					}
					else if (!empty($kind)) {
						$zenfolio_params['offset'] = $attr['offset'];
						$zenfolio_params['limit'] = $attr['limit'];
						switch ($kind) {
							case 'popular':
								$chained_methods[] = 'GetPopularPhotos';
								break;

							case 'recent':
								$chained_methods[] = 'GetRecentPhotos';
								break;

							default:
								$this->pop_from_stack();
								return [new Error(sprintf(esc_html__('Invalid %s parameter.', 'photonic'), '<code>kind</code>').
									Photonic::doc_link($this->doc_links['photos']))];
						}
					}
					else {
						$this->pop_from_stack();
						return [new Error(sprintf(esc_html__('The %1$s parameter is required if %2$s is not specified.', 'photonic'), '<code>kind</code>', '<code>object_id</code>').
							Photonic::doc_link($this->doc_links['photos']))];
					}
					break;

				case 'photosets':
					if (!empty($object_id)) {
						if(($p = stripos($object_id, 'p')) !== false) {
							$object_id = substr($object_id, $p + 1);
						}

						$zenfolio_params['photosetId'] = $object_id;
						$zenfolio_params['level'] = 'Level2';
						$zenfolio_params['includePhotos'] = false;

						if (!empty($password) && empty($realm_id)) {
							$first_call = $this->make_wp_call('LoadPhotoSet', $zenfolio_params);
							if (isset($first_call->result) && !empty($first_call->result)) {
								$photoset = $first_call->result;
								if (isset($photoset->AccessDescriptor)) {
									$realm_id = $photoset->AccessDescriptor->Id;
								}
							}
						}

						if (!empty($password) && !empty($realm_id)) {
							if (!in_array($realm_id, $this->unlocked_realms)) {
								$attr['headers_already_called'] = empty($attr['panel']); //false;
								$chained_methods[] = 'KeyringAddKeyPlain';
								$zenfolio_params['keyring'] = empty($_COOKIE['photonic-zf-keyring']) ? '' : $_COOKIE['photonic-zf-keyring'];
								$zenfolio_params['realmId'] = $realm_id;
								$zenfolio_params['password'] = $password;
							}
						}

						$chained_methods[] = 'LoadPhotoSet';
						$zenfolio_params['startingIndex'] = $attr['offset'];
						$zenfolio_params['numberOfPhotos'] = $attr['limit'];
						$chained_methods[] = 'LoadPhotoSetPhotos';
					}
					else if (!empty($login_name)) {
						$chained_methods[] = 'LoadGroupHierarchy';
						$attr['structure'] = 'flat';
						$zenfolio_params['loginName'] =  $login_name;
					}
					else if (!empty($photoset_type)) {
						if (!empty($text) || !empty($category_code)) {
							$zenfolio_params['searchId'] = '';
							$chained_methods[] = !empty($text) ? 'SearchSetByText' : 'SearchSetByCategory';
						}
						else if (!empty($kind)) {
							switch ($kind) {
								case 'popular':
									$chained_methods[] = 'GetPopularSets';
									break;

								case 'recent':
									$chained_methods[] = 'GetRecentSets';
									break;

								default:
									$this->pop_from_stack();
									return [new Error(sprintf(esc_html__('Invalid %s parameter.', 'photonic'), '<code>kind</code>').
										Photonic::doc_link($this->doc_links['photosets']))];
							}
						}

						if (strtolower($photoset_type) == 'gallery' || strtolower($photoset_type) == 'galleries') {
							$zenfolio_params['type'] = 'Gallery';
						}
						else if (strtolower($photoset_type) == 'collection' || strtolower($photoset_type) == 'collections') {
							$zenfolio_params['type'] = 'Collection';
						}
						else {
							$this->pop_from_stack();
							return [new Error(sprintf(esc_html__('Invalid %1$s parameter. Permissible values are %2$s or %3$s.', 'photonic'), '<code>photoset_type</code>', '<code>Gallery</code>', '<code>Collection</code>').
								Photonic::doc_link($this->doc_links['photosets']))];
						}

						if (!empty($text) || !empty($category_code)) {
							$zenfolio_params['sortOrder'] = !empty($sort_order) ? $sort_order : (!empty($text) ? 'Rank' : 'Date');
							if (!empty($text)) {
								$zenfolio_params['query'] = $text;
							}
							else if (!empty($category_code)) {
								$zenfolio_params['categoryCode'] = $category_code;
							}
						}
						$zenfolio_params['offset'] = $attr['offset'];
						$zenfolio_params['limit'] = $attr['limit'];
					}
					else if (!empty($filter) && empty($login_name)) {
						$this->pop_from_stack();
						return [new Error(sprintf(esc_html__('The %1$s parameter is required if %2$s is specified.', 'photonic'), '<code>login_name</code>', '<code>filter</code>').
							Photonic::doc_link($this->doc_links['photosets']))];
					}
					else if (empty($kind)) {
						$this->pop_from_stack();
						return [new Error(sprintf(esc_html__('The %1$s parameter is required if %2$s or %3$s is not specified.', 'photonic'), '<code>kind</code>', '<code>object_id</code>', '<code>login_name</code>').
							Photonic::doc_link($this->doc_links['photosets']))];
					}
					else if (empty($photoset_type)) {
						$this->pop_from_stack();
						return [new Error(sprintf(esc_html__('The %1$s parameter is required if %2$s or %3$s is not specified.', 'photonic'), '<code>photoset_type</code>', '<code>object_id</code>', '<code>login_name</code>').
							Photonic::doc_link($this->doc_links['photosets']))];
					}

					if (!empty($login_name) && !empty($category_code)) {
						$attr['user_specific_category'] = true;
					}

					if (!empty($login_name) && !empty($text)) {
						$attr['user_specific_text'] = true;
					}
					break;

				case 'hierarchy':
					if (empty($login_name)) {
						$this->pop_from_stack();
						return [new Error(sprintf(esc_html__('The %s parameter is required.', 'photonic'), '<code>login_name</code>').
							Photonic::doc_link($this->doc_links['hierarchies']))];
					}
					$chained_methods[] = 'LoadGroupHierarchy';
					$zenfolio_params['loginName'] =  $login_name;
					break;

				case 'group':
					if (empty($object_id)) {
						$this->pop_from_stack();
						return [new Error(sprintf(esc_html__('The %s parameter is required.', 'photonic'), '<code>object_id</code>').
							Photonic::doc_link($this->doc_links['groups']))];
					}
					$chained_methods[] = 'LoadGroup';
					if(($f = stripos($object_id, 'f')) !== false) {
						$object_id = substr($object_id, $f + 1);
					}
					$zenfolio_params['groupId'] =  $object_id;
					$zenfolio_params['level'] = 'Full';
					$zenfolio_params['includeChildren'] = true;
					break;
			}
		}

		if (!empty($attr['panel'])) {
			$attr['display'] = 'popup';
		}
		else {
			$attr['display'] = 'in-page';
		}

		$header_display = $this->get_header_display($attr);
		$attr['header_display'] = $header_display;

		$call_return = $this->make_chained_calls($chained_methods, $zenfolio_params, $attr);
		$this->pop_from_stack();

		if (!empty($this->stack_trace[$this->gallery_index])) {
			$call_return[] = $this->stack_trace[$this->gallery_index];
		}

		return $call_return;
	}

	/**
	 * Calls a Zenfolio method with the passed parameters.
	 *
	 * @param $method
	 * @param null $params
	 * @param null $keyring
	 * @return array|mixed|null|object|string|string[]|WP_Error
	 */
	function make_wp_call($method, $params = null, $keyring = null) {
		$request = $this->prepare_request($method, $params, $keyring);
		$response = $this->make_wp_request($method, $request);
		return $response;
	}

	/**
	 * Makes a sequence of calls to different Zenfolio methods. This is particularly useful in case of authenticated calls, where
	 * first the authentication happens, then the content is displayed, all in the same call.
	 *
	 * @param array $methods
	 * @param array $zenfolio_args
	 * @param array $short_code
	 * @return array
	 */
	function make_chained_calls($methods, $zenfolio_args, &$short_code = []) {
		$this->push_to_stack('Make chained calls');
		$components = [];

		$keyring = null;
		$original_params = [];
		foreach ($zenfolio_args as $param => $value) {
			$original_params[$param] = $value;
		}

		$pagination = new Pagination();
		$pagination->start = $short_code['offset'];
		$pagination->per_page = $short_code['limit'];

		foreach ($methods as $method) {
			$this->push_to_stack("Making call for $method");
			$keyring_params = [];
			if ($method == 'KeyringGetUnlockedRealms') {
				$keyring_params['keyring'] = $zenfolio_args['keyring'];
				$response = $this->make_wp_call($method, $keyring_params);
				if (isset($response->result)) {
					$this->unlocked_realms = $response->result;
				}
			}
			else if ($method == 'KeyringAddKeyPlain') {
				if (in_array($zenfolio_args['realmId'], $this->unlocked_realms)) {
					continue;
				}
				$keyring_params['keyring'] = $zenfolio_args['keyring'];
				$keyring_params['realmId'] = $zenfolio_args['realmId'];
				$keyring_params['password'] = $zenfolio_args['password'];
				$response = $this->make_wp_call($method, $keyring_params);

				if (!empty($response->result)) {
					// Sometimes the cookie isn't set by the setcookie command (happens when the password is passed as a shortcode parameter
					// instead of the password prompt)
					$keyring = $response->result;
					if (!in_array($keyring_params['realmId'], $this->unlocked_realms)) {
						$this->unlocked_realms[] = $keyring_params['realmId'];
						$this->temp_keyring = $keyring;
					}

					if (!$short_code['headers_already_called']) {
						setcookie('photonic-zf-keyring', $keyring, time() + 60 * 60 * 24, COOKIEPATH);
					}
				}
				else {
					$components[] = $this->password_protected;
					break;
				}
			}
			else {
				foreach ($original_params as $param => $value) {
					$zenfolio_args[$param] = $value;
				}

				$keyring_fields = ['keyring', 'realmId', 'password'];
				foreach ($zenfolio_args as $param => $value) {
					if (in_array($param, $keyring_fields)) {
						unset($zenfolio_args[$param]);
					}
				}

				if ($method === 'LoadPhotoSetPhotos') {
					unset($zenfolio_args['level']);
					unset($zenfolio_args['includePhotos']);
				}
				else if ($method === 'LoadPhotoSet') {
					unset($zenfolio_args['startingIndex']);
					unset($zenfolio_args['numberOfPhotos']);
				}

				$response = $this->make_wp_call($method, $zenfolio_args, $keyring);
				$this->pop_from_stack();
				$this->push_to_stack('Processing response');

				$components = array_merge($components, $this->process_response($method, $response, $short_code, $pagination));
				$this->pop_from_stack();
			}
		}

		$this->pop_from_stack();
		return $components;
	}

	/**
	 * Routing function that takes the response and redirects it to the appropriate processing function.
	 *
	 * @param $method
	 * @param $response
	 * @param array $short_code
	 * @param Pagination $pagination
	 * @return string|array
	 */
	function process_response($method, $response, &$short_code, Pagination &$pagination) {
		$header_display = $short_code['header_display'];

		if (!empty($response->result)) {
			$result = $response->result;
			$components = [];

			switch ($method) {
				case 'GetPopularPhotos':
				case 'GetRecentPhotos':
				case 'SearchPhotoByText':
				case 'SearchPhotoByCategory':
					$pagination->total = $result->TotalCount;
					$components[] = $this->process_photos($result, 'stream', $short_code, $pagination);
					break;

				case 'LoadPhoto':
					$component = $this->process_photo($result, $short_code);
					if (!is_null($component)) {
						$components[] = $component;
					}
					break;

				case 'GetPopularSets':
				case 'GetRecentSets':
				case 'SearchSetByText':
				case 'SearchSetByCategory':
					$components[] = $this->process_sets($result, $short_code);
					break;

				case 'LoadPhotoSet':
				case 'LoadPhotoSetPhotos':
					if (isset($result->ImageCount)) {
						$pagination->total = $result->ImageCount;
					}
					$components = array_merge($components, $this->process_set($result, ['header_display' => $header_display], $short_code, $pagination));
					break;

				case 'LoadGroupHierarchy':
					$component = $this->process_group_hierarchy($result, ['header_display' => $header_display], $short_code);
					if (!is_null($component)) {
						$components[] = $component;
					}
					break;

				case 'LoadGroup':
					$component = $this->process_group($result, ['header_display' => $header_display], $short_code, 0);
					if (!is_null($component)) {
						$components[] = $component;
					}
					break;
			}
			return $components;
		}
		else if ($response == $this->password_protected) {
			return [$response];
		}
		else if (!empty($response->error)) {
			if (!empty($response->error->message)) {
				return [new Error(esc_html__('Zenfolio returned an error:', 'photonic')."<br/>\n".$response->error->message)];
			}
			else {
				return [new Error(esc_html__('Unknown error', 'photonic'))];
			}
		}
		else {
			return [new Error(esc_html__('Unknown error', 'photonic'))];
		}
	}

	/**
	 * Takes an array of photos and displays each as a thumbnail. Each thumbnail, upon clicking launches a lightbox.
	 *
	 * @param $response
	 * @param string $parent
	 * @param array $short_code
	 * @param Pagination|null $pagination
	 * @return Photo_List|Error
	 */
	function process_photos($response, $parent, $short_code, Pagination $pagination) {
		if (!is_array($response)) {
			if (empty($response->Photos) || !is_array($response->Photos)) {
				return new Error(esc_html__('Response is not an array', 'photonic'));
			}
			$response = $response->Photos;
		}

		global $photonic_zenfolio_photos_per_row_constraint, $photonic_zenfolio_photo_title_display, $photonic_zenfolio_photos_constrain_by_padding, $photonic_zenfolio_photos_constrain_by_count;
		$row_constraints = ['constraint-type' => $photonic_zenfolio_photos_per_row_constraint, 'padding' => $photonic_zenfolio_photos_constrain_by_padding, 'count' => $photonic_zenfolio_photos_constrain_by_count];
		$photo_objects = $this->build_level_1_objects($response, $short_code);

		$pagination->end = $pagination->start + count($response);

		$photo_list = new Photo_List($short_code);
		$photo_list->photos = $photo_objects;
		$photo_list->title_position = $photonic_zenfolio_photo_title_display;
		$photo_list->row_constraints = $row_constraints;
		$photo_list->parent = $parent;
		$photo_list->pagination = $pagination;

		return $photo_list;
	}

	function build_level_1_objects($response, array $shortcode = [], $module_parameters = [], $options = []) {
		if (!is_array($response)) {
			if (empty($response->Photos) || !is_array($response->Photos)) {
				return [];
			}
			$response = $response->Photos;
		}

		$tile_size = (empty($shortcode['tile_size']) || $shortcode['tile_size'] == 'same') ? $shortcode['main_size'] : $shortcode['tile_size'];

		$type = '$type';
		$photo_objects = [];

		$media = explode(',', $shortcode['media']);
		$videos_ok = in_array('videos', $media) || in_array('all', $media);
		$photos_ok = in_array('photos', $media) || in_array('all', $media);

		$sizes = [
			'thumb_size' => $shortcode['thumb_size'],
			'tile_size' => $tile_size,
			'main_size' => $shortcode['main_size'],
		];
		foreach ($response as $photo) {
			if (empty($photo->$type) || $photo->$type != 'Photo' || ($photo->IsVideo && !$videos_ok) || (!$photo->IsVideo && !$photos_ok)) {
				continue;
			}

			$appendage = [];
			if (isset($photo->Sequence)) {
				$appendage[] = 'sn='.$photo->Sequence;
			}
			if (isset($photo->UrlToken)) {
				$appendage[] = 'tk='.$photo->UrlToken;
			}

			$photonic_photo = new Photo();
			$photonic_photo->thumbnail = 'https://'.$photo->UrlHost.$photo->UrlCore.'-'.$shortcode['thumb_size'].'.jpg';
			$photonic_photo->main_image = 'https://'.$photo->UrlHost.$photo->UrlCore.'-'.$shortcode['main_size'].'.jpg';
			$photonic_photo->tile_image = 'https://'.$photo->UrlHost.$photo->UrlCore.'-'.$tile_size.'.jpg';

			$this->calculate_sizes($photo, $photonic_photo, $sizes);

			if ($photo->IsVideo) {
				$photonic_photo->video = substr($photo->OriginalUrl, 0, strlen($photo->OriginalUrl) - 7).$shortcode['video_size'].'.mp4';
			}

			$photonic_photo->download = $photonic_photo->main_image.'?'.implode('&', $appendage);
			$photonic_photo->title = $photo->Title;
			$photonic_photo->alt_title = $photo->Title;
			$photonic_photo->description = $photo->Caption;
			$photonic_photo->main_page = $photo->PageUrl;
			$photonic_photo->id = $photo->Id;

			$photo_objects[] = $photonic_photo;
		}

		return $photo_objects;
	}

	function build_level_2_objects($response, $short_code = [], $filters_do_not_use = [], &$options_do_not_use = []) {
		global $photonic_zenfolio_hide_password_protected_thumbnail, $photonic_gallery_template_page;
		$tile_size = (empty($short_code['tile_size']) || $short_code['tile_size'] == 'same') ? $short_code['main_size'] : $short_code['tile_size'];

		$sizes = [
			'thumb_size' => $short_code['thumb_size'],
			'tile_size' => $tile_size,
		];

		$filter_list = [];
		if (!empty($short_code['filter'])) {
			$filter_list = explode(',', $short_code['filter']);
		}

		$objects = [];
		foreach ($response as $photoset) {
			if (empty($photoset->TitlePhoto)) {
				continue;
			}
			if (!empty($photoset->AccessDescriptor) && !empty($photoset->AccessDescriptor->AccessType) && $photoset->AccessDescriptor->AccessType == 'Password' && !empty($photonic_zenfolio_hide_password_protected_thumbnail)) {
				continue;
			}

			$photonic_album = new Album();

			$photo = $photoset->TitlePhoto;

			$photonic_album->id = $photoset->Id;
			$photonic_album->thumbnail = 'https://'.$photo->UrlHost.$photo->UrlCore.'-'.$short_code['thumb_size'].'.jpg';
			$photonic_album->tile_image = 'https://'.$photo->UrlHost.$photo->UrlCore.'-'.$tile_size.'.jpg';
			$this->calculate_sizes($photo, $photonic_album, $sizes);
			$photonic_album->main_page = $photoset->PageUrl;
			$photonic_album->title = esc_attr($photoset->Title);
			$photonic_album->counter = $photoset->PhotoCount;
			$photonic_album->data_attributes = [
				'thumb-size' => $short_code['thumb_size'],
				'photo-count' => $short_code['photo_count'],
				'photo-more' => empty($short_code['photo_more']) ? '' : $short_code['photo_more'],
				'overlay-size' => $short_code['overlay_size'],
				'overlay-video-size' => $short_code['overlay_video_size'],
			];


			if (!empty($photoset->AccessDescriptor) && !empty($photoset->AccessDescriptor->AccessType) && $photoset->AccessDescriptor->AccessType == 'Password') {
				if (!in_array($photoset->AccessDescriptor->Id, $this->unlocked_realms)) {
					$photonic_album->classes = ['photonic-zenfolio-passworded'];
					$photonic_album->passworded = 1;
					$photonic_album->realm_id = $photoset->AccessDescriptor->Id;
					$photonic_album->data_attributes['realm'] = $photoset->AccessDescriptor->Id;
				}
			}

			$internal_short_code = $short_code;
			$internal_short_code['view'] = 'photosets';
			$internal_short_code['object_id'] = $photoset->Id;

			$internal_short_code['layout'] = empty($short_code['photo_layout']) ? $short_code['layout'] : $short_code['photo_layout'];
			$internal_short_code['limit'] = empty($short_code['photo_count']) ? $short_code['limit'] : $short_code['photo_count'];
			$internal_short_code['more'] = empty($short_code['photo_more']) ? $short_code['more'] : $short_code['photo_more'];

			if ($short_code['popup'] == 'page' && !empty($photonic_gallery_template_page) && is_string(get_post_status($photonic_gallery_template_page))) {
				$photonic_album->gallery_url = $this->get_gallery_url($internal_short_code, [
					'title' => $photonic_album->title,
				]);
			}

			$page_url = parse_url($photoset->PageUrl);
			$page_url = $page_url['path'];
			$page_url = explode('/', $page_url);
			if (count($page_url) > 1) {
				$page_url = $page_url[1];
			}

			if (!is_array($page_url) && (count($filter_list) === 0 || (count($filter_list) > 0 && in_array($page_url, $filter_list) && strtolower($short_code['filter_type']) !== 'exclude') ||
				(count($filter_list) > 0 && !in_array($page_url, $filter_list) && strtolower($short_code['filter_type']) === 'exclude'))) {
				$objects[] = $photonic_album;
			}
			else if (is_array($page_url)) { // Something went wrong. Let's be safe and add the object.
				$objects[] = $photonic_album;
			}
		}
		return $objects;
	}

	private function calculate_sizes($photo, &$object, $sizes) {
		$width = $photo->Width;
		$height = $photo->Height;
		$aspect_ratio = 0;
		if ($width && $height) {
			$aspect_ratio = $width / $height;
		}

		if ($aspect_ratio > 0) {
			foreach ($sizes as $size => $size_value) {
				if ($width >= $this->standard_sizes[$size_value]['w'] || $height >= $this->standard_sizes[$size_value]['h']) {
					$object->{$size} = [
						'w' => $size_value == 1 ? 60 : ($aspect_ratio > 1 ? $this->standard_sizes[$size_value]['w'] : ($this->standard_sizes[$size_value]['h'] * $aspect_ratio)),
						'h' => $size_value == 1 ? 60 : ($aspect_ratio < 1 ? $this->standard_sizes[$size_value]['h'] : ($this->standard_sizes[$size_value]['w'] / $aspect_ratio)),
					];
				}
				else {
					$object->{$size} = [
						'w' => $size_value == 1 ? 60 : $width,
						'h' => $size_value == 1 ? 60 : $height,
					];
				}
			}
		}
	}

	/**
	 * Prints a single photo with the title as an <h3> and the caption as the image caption.
	 *
	 * @param $photo
	 * @param $short_code
	 * @return Single_Photo
	 */
	function process_photo($photo, $short_code) {
		$type = '$type';
		if (empty($photo->$type) || $photo->$type != 'Photo') {
			return null;
		}

		return new Single_Photo('https://'.$photo->UrlHost.$photo->UrlCore.'-'.$short_code['main_size'].'.jpg', $photo->PageUrl, $photo->Title, $photo->Caption);
	}

	/**
	 * Takes an array of photosets and displays a thumbnail for each of them. Password-protected thumbnails might be excluded via the options.
	 *
	 * @param $response
	 * @param array $short_code
	 * @return Album_List|Error
	 */
	function process_sets($response, $short_code = []) {
		if (!is_array($response)) {
			if (empty($response->PhotoSets) || !is_array($response->PhotoSets)) {
				return new Error(esc_html__('Response is not an array', 'photonic'));
			}
			$response = $response->PhotoSets;
		}

		global $photonic_zenfolio_sets_per_row_constraint, $photonic_zenfolio_sets_constrain_by_count, $photonic_zenfolio_sets_constrain_by_padding,
			$photonic_zenfolio_set_title_display, $photonic_zenfolio_hide_set_photos_count_display;
		$row_constraints = ['constraint-type' => $photonic_zenfolio_sets_per_row_constraint, 'padding' => $photonic_zenfolio_sets_constrain_by_padding, 'count' => $photonic_zenfolio_sets_constrain_by_count];
		$objects = $this->build_level_2_objects($response, $short_code);

		$album_list = new Album_List($short_code);
		$album_list->albums = $objects;
		$album_list->row_constraints = $row_constraints;
		$album_list->type = 'photosets';
		$album_list->singular_type = 'set';
		$album_list->title_position = $photonic_zenfolio_set_title_display;
		$album_list->level_1_count_display = $photonic_zenfolio_hide_set_photos_count_display;

		return $album_list;
	}

	/**
	 * Displays a header with a basic summary for a photoset, along with thumbnails for all associated photos.
	 *
	 * @param $response
	 * @param array $options
	 * @param array $short_code
	 * @param Pagination $pagination
	 * @return array
	 */
	function process_set($response, $options, &$short_code, Pagination &$pagination) {
		$components = [];

		$media = explode(',', $short_code['media']);
		$videos_ok = in_array('videos', $media) || in_array('all', $media);
		$photos_ok = in_array('photos', $media) || in_array('all', $media);

		if (!is_array($response)) {
			global $photonic_zenfolio_link_set_page, $photonic_zenfolio_hide_set_thumbnail, $photonic_zenfolio_hide_set_title, $photonic_zenfolio_hide_set_photo_count;

			$header = $this->get_header_object($response, $short_code);
			$hidden = ['thumbnail' => !empty($photonic_zenfolio_hide_set_thumbnail), 'title' => !empty($photonic_zenfolio_hide_set_title), 'counter' => !empty($photonic_zenfolio_hide_set_photo_count)];
			$counters = [];
			if ($photos_ok) $counters['photos'] = $response->ImageCount;
			if ($videos_ok) $counters['videos'] = $response->VideoCount;

			$pagination->total = ($photos_ok ? $response->ImageCount : 0) + ($videos_ok ? $response->VideoCount : 0);

			$header->hidden_elements = $this->get_hidden_headers($options['header_display'], $hidden);
			$header->counters = $counters;
			$header->enable_link = empty($photonic_zenfolio_link_set_page);

			$components[] = $header;
		}
		else {
			$components[] = $this->process_photos($response, 'set', $short_code, $pagination);
		}
		return $components;
	}

	/**
	 * Takes a Zenfolio response object and converts it into an associative array with a title, a thumbnail URL and a link URL.
	 *
	 * @param $object
	 * @param $short_code
	 * @return Header
	 */
	public function get_header_object($object, $short_code) {
		$thumb_size = $short_code['thumb_size'];

		$header = new Header();
		$header->title = $object->Title ?: '';
		$header->description = $object->Caption ?: '' ;
		if (!empty($object->Title)) {
			if (!empty($object->TitlePhoto)) {
				$photo = $object->TitlePhoto;
				$header->thumb_url = 'https://' . $photo->UrlHost . $photo->UrlCore . '-' . $thumb_size . '.jpg';
			}
			$header->page_url = $object->PageUrl;
		}
		$header->header_for = 'set';
		$header->display_location = $short_code['display'];

		return $header;
	}

	/**
	 * For a given user this prints out the group hierarchy. This starts with the root level and first prints all immediate
	 * children photosets. It then goes into each child group and recursively displays the photosets for each of them in separate sections.
	 *
	 * @param $response
	 * @param array $options
	 * @param array $short_code
	 * @return Collection|Error
	 */
	function process_group_hierarchy($response, $options = [], $short_code = []) {
		if (empty($response->Elements)) {
			return new Error(esc_html__('No galleries, collections or groups defined for this user', 'photonic'));
		}

		$filters = [];
		if (!empty($short_code['kind']) && in_array(strtolower($short_code['kind']), ['popular', 'recent']) && !empty($short_code['login_name']) && empty($short_code['filter'])) {
			$pr_response = $this->make_wp_call('LoadPublicProfile', ['login_name' => $short_code['login_name']]);
			if (!empty($pr_response->result)) {
				$pr_response = $pr_response->result;
				if (strtolower($short_code['kind']) == 'popular' && !empty($pr_response->FeaturedPhotoSets)) {
					foreach ($pr_response->FeaturedPhotoSets as $photoset) {
						$filters[] = $photoset->Id;
					}
				}
				else if (strtolower($short_code['kind']) == 'recent' && !empty($pr_response->RecentPhotoSets)) {
					foreach ($pr_response->RecentPhotoSets as $photoset) {
						$filters[] = $photoset->Id;
					}
				}
				if (empty($filters)) {
					return null;
				}
			}
		}

		$all_photosets = [];
		return $this->process_group($response, $options, $short_code, 0, $all_photosets, $filters);
	}

	/**
	 * For a given group this displays the immediate children photosets and then recursively displays all children groups.
	 *
	 * @param $group
	 * @param array $options
	 * @param array $short_code
	 * @param $level
	 * @param array $all_photosets
	 * @param array $recent_popular
	 * @return Collection|Error
	 */
	function process_group($group, $options, $short_code, $level, &$all_photosets = [], $recent_popular = []) {
		$collection = new Collection();

		$type = '$type';
		if (!isset($group->Elements)) {
			$object_id = $group->Id;
			$method = 'LoadGroup';
			if(($f = stripos($object_id, 'f')) !== false) {
				$object_id = substr($object_id, $f + 1);
			}
			$params = [];
			$params['groupId'] =  $object_id;
			$params['level'] = 'Full';
			$params['includeChildren'] = true;
			$response = $this->make_wp_call($method, $params);
			if (!empty($response->result)) {
				$group = $response->result;
			}
		}

		if (empty($group->Elements)) {
			return null;
		}

		$elements = $group->Elements;
		$photosets = [];
		$groups = [];
		global $photonic_zenfolio_hide_password_protected_thumbnail;
		$image_count = 0;
		$requests = [];

		foreach ($elements as $element) {
			if ($element->$type == 'PhotoSet') {
				if (!empty($short_code['photoset_type']) && in_array(strtolower($short_code['photoset_type']), ['gallery', 'collection']) && $short_code['photoset_type'] !== $element->Type) {
					continue;
				}

				if (!empty($element->AccessDescriptor) && !empty($element->AccessDescriptor->AccessType) && $element->AccessDescriptor->AccessType == 'Password' && !empty($photonic_zenfolio_hide_password_protected_thumbnail)) {
					continue;
				}

				if (!empty($short_code['user_specific_category']) || !empty($short_code['user_specific_text'])) {
					// Need an extra call here, since the LoadGroup doesn't return Level2 attributes for the children
					// Calls are slow, so we make all of them together at the end of this instead of calling the API individually
					$params = [];
					$params['photosetId'] = $element->Id;
					$params['level'] = 'Level2';
					$params['includePhotos'] = false;
					$method = 'LoadPhotoSet';

					$request = $this->prepare_request($method, $params);
					$requests[] = [
						'url' => $this->secure_url,
						'type' => 'POST',
						'headers' => $request['headers'],
						'data' => $request['body'],
					];
				}
				$photosets[$element->Id] = $element;
				$image_count += $element->ImageCount;
			}
			else if ($element->$type == 'Group') {
				$groups[] = $element;
			}
		}

		if (!empty($requests)) {
			$responses = Requests::request_multiple($requests);
			foreach ($responses as $ps_response) {
				if (is_a($ps_response, 'Requests_Response')) {
					$found = false;
					$ps_response = json_decode($ps_response->body);
					if (!empty($ps_response->result)) {
						$ps_response = $ps_response->result;
						if (empty($ps_response->Categories) && !empty($short_code['user_specific_category'])) {
							$found = false;
						}
						else if (!empty($short_code['user_specific_category'])) {
							$categories = $ps_response->Categories;
							foreach ($categories as $category) {
								if ($category == $short_code['category_code']) {
									$found = true;
								}
							}
						}

						if (!$found) {
							if (!empty($short_code['user_specific_text'])) {
								// Check Title, Caption, Keywords
								$text = $short_code['text'];
								$text = explode(',', $text);
								$text = array_map('trim', $text);
								$to_match = [];
								foreach ($text as $item) {
									$to_match[] = '\b'.$item.'\b';
								}
								$to_match = '/('.implode('|', $to_match).')/i';
								$keywords = implode(',', $ps_response->Keywords);
								$found = preg_match($to_match, $ps_response->Title) || preg_match($to_match, $ps_response->Caption) || preg_match($to_match, $keywords);
							}
						}

						if (!$found) {
							unset($photosets[$ps_response->Id]);
						}
					}
				}
			}
		}

		if (!empty($recent_popular)) {
			foreach ($photosets as $id => $set) {
				if (!in_array($id, $recent_popular)) {
					unset($photosets[$id]);
				}
			}
		}

		$all_photosets = array_merge($all_photosets, array_values($photosets));

		global $photonic_zenfolio_hide_empty_groups;
		global $photonic_zenfolio_link_group_page, $photonic_zenfolio_hide_group_title, $photonic_zenfolio_hide_group_photo_count, $photonic_zenfolio_hide_group_group_count, $photonic_zenfolio_hide_group_set_count;

		$hidden = [
			'thumbnail' => true,
			'title' => !empty($photonic_zenfolio_hide_group_title),
			'counter' => !(empty($photonic_zenfolio_hide_group_photo_count) || empty($photonic_zenfolio_hide_group_group_count) || empty($photonic_zenfolio_hide_group_set_count)),
		];

		if (!empty($group->Title) && ($image_count > 0 || empty($photonic_zenfolio_hide_empty_groups))) {
			$header = $this->get_header_object($group, $short_code);

			$counters = [
				'sets' => empty($photonic_zenfolio_hide_group_set_count) ? count($photosets) : 0,
				'groups' => empty($photonic_zenfolio_hide_group_group_count) ? count($groups) : 0,
				'photos' => empty($photonic_zenfolio_hide_group_photo_count)? $image_count : 0,
			];

			$header->hidden_elements = $this->get_hidden_headers($options['header_display'], $hidden);
			if ($short_code['structure'] !== 'flat') {
				$header->counters = $counters;
				$header->enable_link = empty($photonic_zenfolio_link_group_page);

				$collection->header = $header;
			}
		}

		if ($short_code['structure'] !== 'flat') {
			$sets = $this->process_sets($photosets, $short_code);
			if (is_a($sets, 'Photonic_Plugin\Components\Album_List')) {
				$collection->album_list = $sets;
			}
			else if (is_a($sets, 'Photonic_Plugin\Components\Error')) {
				return $sets;
			}
		}

		foreach ($groups as $group) {
			$out = $this->process_group($group, $options, $short_code, $level + 1, $all_photosets, $recent_popular);
			if ($short_code['structure'] !== 'flat') {
				$collection->collections[] = $out;
			}
		}

		if ($short_code['structure'] === 'flat' && $level === 0) {
			if (!empty($header)) {
				$counters = [
					'sets' => empty($photonic_zenfolio_hide_group_set_count) ? count($all_photosets) : 0,
				];
				$header->counters = $counters;

				$collection->header = $header;
			}

			$sets = $this->process_sets($all_photosets, $short_code);
			if (is_a($sets, 'Photonic_Plugin\Components\Album_List')) {
				$collection->album_list = $sets;
			}
			else if (is_a($sets, 'Photonic_Plugin\Components\Error')) {
				return $sets;
			}
		}
		return $collection;
	}

	function authenticate($password) {
		global $photonic_zenfolio_default_user;
		$photonic_authentication = get_option('photonic_authentication');
		if (!isset($photonic_authentication['zenfolio'])) {
			$photonic_authentication['zenfolio'] = [];
		}
		$ret = [];
		if (!empty($photonic_zenfolio_default_user)) {
			$challenge_response = $this->make_wp_call('GetChallenge', ['loginName' => $photonic_zenfolio_default_user]);

			if (!empty($challenge_response)) {
				$salt = $challenge_response->result->PasswordSalt;
				$salt = call_user_func_array('pack', array_merge(['C*'], $salt));
				$pass_hash = hash('sha256', $salt.utf8_encode($password), true);

				$challenge = $challenge_response->result->Challenge;

				$this->perform_back_end_authentication($pass_hash, $challenge);
				if (empty($this->token)) {
					$ret['error'] = esc_html__('Authentication failed.', 'photonic');
					unset($photonic_authentication['zenfolio']['pass_hash']);
				}
				else {
					$photonic_authentication['zenfolio']['pass_hash'] = unpack('C*', $pass_hash);
					$ret['success'] = $this->token;
				}
			}
			else {
				$ret['error'] = esc_html__('Failed to get challenge.', 'photonic');
			}
		}
		else {
			unset($photonic_authentication['zenfolio']['pass_hash']);
			$ret['error'] = sprintf(esc_html__('Default user not defined. Please define one under %s', 'photonic'), '<em>Photonic &rarr; Settings &rarr; Zenfolio &rarr; Zenfolio Photo Settings &rarr; Default User</em>');
		}
		update_option('photonic_authentication', $photonic_authentication);
		return $ret;
	}

	function perform_back_end_authentication($pass_hash = '', $challenge = false) {
		global $photonic_zenfolio_default_user;
		if (!empty($photonic_zenfolio_default_user)) {
			$photonic_authentication = get_option('photonic_authentication');
			$auth_done = !empty($photonic_authentication) && !empty($photonic_authentication['zenfolio']) && !empty($photonic_authentication['zenfolio']['pass_hash']);
			if ($auth_done || !empty($pass_hash)) {
				if (empty($pass_hash)) {
					$pass_hash = $photonic_authentication['zenfolio']['pass_hash'];
					$pass_hash = call_user_func_array('pack', array_merge(['C*'], $pass_hash));
				}

				if (empty($challenge)) {
					$challenge_response = $this->make_wp_call('GetChallenge', ['loginName' => $photonic_zenfolio_default_user]);
					$challenge = $challenge_response->result->Challenge;
				}

				$challenge_pack = call_user_func_array('pack', array_merge(['C*'], $challenge));
				$challenge_hash = hash('sha256', $challenge_pack.$pass_hash, true);
				$proof = array_values(unpack('C*', $challenge_hash));

				$auth_response = $this->make_wp_call('Authenticate', ['challenge' => $challenge, 'proof' => $proof]);
				if (!empty($auth_response->result)) {
					$this->token = $auth_response->result;
					return;
				}
			}
		}
		$this->token = false;
	}

	/**
	 * @param $method
	 * @param $params
	 * @param $keyring
	 * @return array
	 */
	function prepare_request($method, $params, $keyring = null) {
		$request = [];
		$request['method'] = $method;
		$request['params'] = array_values($params);
		$request['id'] = 1;
		$bodyString = json_encode($request);
		$bodyLength = strlen($bodyString);

		$headers = [];
		$headers['Host'] = 'api.zenfolio.com';
		$headers['User-Agent'] = $this->user_agent;
		if ($this->token && $method !== 'GetChallenge' && $method !== 'Authenticate') {
			$headers['X-Zenfolio-Token'] = $this->token;
		}
		if (isset($_COOKIE['photonic-zf-keyring'])) {
			$headers['X-Zenfolio-Keyring'] = $_COOKIE['photonic-zf-keyring'];
		}
		else if (!empty($keyring)) {
			$headers['X-Zenfolio-Keyring'] = $keyring;
		}
		else if (!empty($this->temp_keyring)) {
			$headers['X-Zenfolio-Keyring'] = $this->temp_keyring;
		}
		$headers['Content-Type'] = 'application/json';
		$headers['Content-Length'] = $bodyLength;
		return ['headers' => $headers, 'body' => $bodyString];
	}

	/**
	 * @param $method
	 * @param $request
	 * @return array|mixed|null|object|string|string[]|WP_Error
	 */
	public function make_wp_request($method, $request) {
		$args = [
			'user-agent' => $this->user_agent,
			'sslverify' => PHOTONIC_SSL_VERIFY,
			'timeout' => 30,
			'headers' => $request['headers'],
			'method' => 'POST',
			'body' => $request['body'],
		];

		$response = wp_remote_request($this->secure_url, $args);
		$response = wp_remote_retrieve_body($response);

		//PHP/WP's json_decode() function really messes up the "long" ids returned by Zenfolio. The following takes care of this.
		// Can't pass the 4th argument as outlined here: https://php.net/manual/en/function.json-decode.php, since it only came into existence in PHP 5.4
		$response = preg_replace('/"Id":(\d+)/', '"Id":"$1"', $response);
		$response = preg_replace('/"RealmId":(\d+)/', '"Id":"$1"', $response);
		if ($method == 'KeyringGetUnlockedRealms') {
			$realm_ids = [];
			preg_match('/([\[^,\d]+])/', $response, $realm_ids);
			if (!empty($realm_ids)) {
				$realm_ids = $realm_ids[0];
				$replace = $realm_ids;
				$replace = str_replace('[', '["', $replace);
				$replace = str_replace(']', '"]', $replace);
				$replace = str_replace(',', '","', $replace);
				$response = str_replace($realm_ids, $replace, $response);
			}
		}

		$response = json_decode($response);
		return $response;
	}
}
