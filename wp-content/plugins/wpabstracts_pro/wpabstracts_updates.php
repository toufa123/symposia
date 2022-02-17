<?php
defined('ABSPATH') or die("ERROR: You do not have permission to access this page");

$api_url = 'http://updates.wpabstracts.com/';
$plugin_slug = basename(dirname(__FILE__));
$plugin_file = $plugin_slug .'/'. $plugin_slug .'.php';
$plugin_hash = "fa770f1c6dbbd8f36127e0aa8e94f559";
$license_key = get_option('wpabstracts_license_key');

add_filter('pre_set_site_transient_update_plugins', 'wpabstracts_check_updates');
function wpabstracts_check_updates($checked_data) {
	global $api_url, $plugin_slug, $plugin_file, $wp_version, $plugin_hash;

	if (empty($checked_data->checked)){
		return $checked_data;
	}

	$args = array(
		'slug' => $plugin_slug,
		'version' => $checked_data->checked[$plugin_file]
	);

	$request_string = array(
		'body' => array(
			'action' => 'basic_check',
			'request' => serialize($args),
			'api-key' => md5(get_bloginfo('url'))
		),
		'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
	);

	// Start checking for an update
	$raw_response = wp_remote_post($api_url, $request_string);

	$success = !is_wp_error($raw_response) && $raw_response['response']['code'] == 200;

	$response = null;

	if($success){
		$response = unserialize($raw_response['body']);
	}

	if (is_object($response) && !empty($response)){
		$license = wpabstracts_check_license();
		if(isset($license) && $license->status !== 'active') {
			unset($response->package);
			$response->slug = $plugin_hash;
		}
		$checked_data->response[$plugin_slug .'/'.$plugin_slug.'.php'] = $response;
	}
	return $checked_data;
}

add_filter('plugins_api', 'wpabstracts_api_call', 10, 3);
function wpabstracts_api_call($def, $action, $args) {
	global $plugin_slug, $plugin_hash, $api_url, $wp_version;

	if(isset($args->slug) && $args->slug == $plugin_hash){
		$args->slug = $plugin_slug;
	}

	if (isset($args->slug) && ($args->slug != $plugin_slug)){
		return false;
	}

	// Get the current version
	$plugin_info = get_site_transient('update_plugins');
	if(isset($plugin_info->checked)){
		$current_version = $plugin_info->checked[$plugin_slug .'/'.$plugin_slug.'.php'];
		$args->version = $current_version;
	}

	$request_string = array(
		'body' => array(
			'action' => $action,
			'request' => serialize($args),
			'api-key' => md5(get_bloginfo('url'))
		),
		'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
	);

	$request = wp_remote_post($api_url, $request_string);

	if (is_wp_error($request)) {
		$res = new WP_Error('plugins_api_failed', __('An Unexpected HTTP Error occurred during the API request.</p> <p><a href="?" onclick="document.location.reload(); return false;">Try again</a>'), $request->get_error_message());
	} else {
		$res = unserialize($request['body']);
	}
	return $res;
}

add_action('in_plugin_update_message-'. $plugin_file, 'wpabstracts_get_update_message');
function wpabstracts_get_update_message($args){
	global $api_url, $plugin_file;
	$license = wpabstracts_check_license();
	if(isset($license) && $license->status == 'active'){
		$response = wp_safe_remote_get($api_url . 'upgrade_message.php?v=' . $args['version']);
		if (!is_wp_error($response) && !empty($response['body'])) {
			echo '<br><span style="color: #FC6B32; margin-left: 28px;">Important: </span>' . $response['body'];
		}
	}
}

add_action("after_plugin_row_{$plugin_file}", 'wpabstracts_verify_license', 10, 3);
function wpabstracts_verify_license( $path, $plugin_data, $status ) {
	global $api_url, $plugin_slug, $wp_version;
	$license = wpabstracts_check_license();
	$license_message = false;
	$version_message = false;
	$update_message = false;

	if(isset($license) && $license->status !== 'active') { // license key invalid, do custom messaging

		$enter_licence_link = sprintf('<a href="%s" class="js-action-link enter-licence">%s</a>', admin_url('admin.php?page=wpabstracts&tab=license'), __( 'enter your license key', 'wpabstracts'));
		$update_license_link = sprintf('<a href="%s" class="js-action-link enter-licence">%s</a>', admin_url('admin.php?page=wpabstracts&tab=license'), __( 'update license key', 'wpabstracts'));
		$buy_license_link = sprintf('<a href="%s" target="_blank" class="js-action-link enter-licence">%s</a>', 'https://www.wpabstracts.com/pricing', __( 'purchase one', 'wpabstracts'));
		$renew_license_link = sprintf('<a href="%s" target="_blank" class="js-action-link enter-licence">%s</a>', 'https://www.wpabstracts.com/account/licenses/', __( 'renew license', 'wpabstracts'));

		switch($license->status){
			case 'no_key': // none entered
			$license_message = sprintf( __( 'To complete activation %1$s. If you don\'t have a license key, you may %2$s.', 'wpabstracts' ), $enter_licence_link, $buy_license_link );
			break;
			case 'expired': // expired
			$license_message = sprintf( __( 'Your license has expired. For continued support and updates please %1$s.', 'wpabstracts' ), $renew_license_link);
			break;
			case 'invalid': // invalid
			$license_message = sprintf( __( 'Your license key is invalid. For continued support and updates please %1$s.', 'wpabstracts' ), $update_license_link);
			break;
			case 'http_failed': // http_failed
			$license_message = __('We could not perform your license validation at this time.', 'wpabstracts');
			break;
		}

		if($license_message){
			$formated_message = '<tr class="plugin-update-tr active">';
			$formated_message .= '<td class="plugin-update colspanchange" colspan="3">';
			$formated_message .= '<div class="update-message notice inline notice-warning notice-alt"><p>'.$license_message.'</p></div>';
			$formated_message .= '</td>';
			$formated_message .= '</tr>';
			echo $formated_message;
		}

	}

}
