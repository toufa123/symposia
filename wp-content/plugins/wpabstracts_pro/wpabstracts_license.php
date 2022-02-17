<?php
defined('ABSPATH') or die("ERROR: You do not have permission to access this page");

/*** Activate ***/
if (isset($_REQUEST['activate_license'])) {
	$license_key = $_REQUEST['wpabstracts_license_key'];

	// API query parameters
	$api_params = array(
		'slm_action' => 'slm_activate',
		'secret_key' => WPABSTRACTS_SECRET_KEY,
		'license_key' => $license_key,
		'registered_domain' => $_SERVER['SERVER_NAME'],
		'item_reference' => urlencode(WPABSTRACTS_REFERENCE),
	);

	// Send query to the license manager server
	$query = esc_url_raw(add_query_arg($api_params, WPABSTRACTS_SERVER_URL));
	$response = wp_remote_get($query, array('timeout' => 20, 'sslverify' => false));

	// Check for error in the response
	if (is_wp_error($response)){
		wpabstracts_show_message(__("We could not perform the activation at this time. Please try again later.", "wpabstracts"), 'alert-danger');
		return;
	}

	$license_data = json_decode(wp_remote_retrieve_body($response));

	if($license_data->result == 'success'){
		wpabstracts_show_message("Activation was successful.", 'alert-success');
		update_option('wpabstracts_license_key', $license_key);
		set_site_transient('update_plugins', null); // important: flush update transient so the new status gets updated
	}
	else{
		wpabstracts_show_message(__("Activation failed: ", "wpabstracts") . $license_data->message, 'alert-danger');
	}

}

/*** Deactivate ***/
if (isset($_REQUEST['deactivate_license'])) {
	$license_key = $_REQUEST['wpabstracts_license_key'];

	$api_params = array(
		'slm_action' => 'slm_deactivate',
		'secret_key' => WPABSTRACTS_SECRET_KEY,
		'license_key' => $license_key,
		'registered_domain' => $_SERVER['SERVER_NAME'],
		'item_reference' => urlencode(WPABSTRACTS_REFERENCE),
	);

	$query = esc_url_raw(add_query_arg($api_params, WPABSTRACTS_SERVER_URL));
	$response = wp_remote_get($query, array('timeout' => 20, 'sslverify' => false));

	if (is_wp_error($response)){
		wpabstracts_show_message(__("We could not perform the deactivation at this time. Please try again later.", "wpabstracts"), 'alert-danger');
		return;
	}

	$license_data = json_decode(wp_remote_retrieve_body($response));

	if($license_data->result == 'success'){
		wpabstracts_show_message("Deactivation was successful.", 'alert-success');
		update_option('wpabstracts_license_key', '');
		set_site_transient('update_plugins', null); // important: flush update transient so the new status gets updated
	}
	else{
		wpabstracts_show_message(__("Deactivation failed: ", "wpabstracts") . $license_data->message, 'alert-danger');
	}

}

/*** Retrieve License ***/
if (isset($_REQUEST['retrieve_license'])) {
	$order_email = $_REQUEST['wpabstracts_order_email'];

	if($order_email){
		$api_params = array(
			'slm_action' => 'retrieve_license',
			'secret_key' => WPABSTRACTS_SECRET_KEY,
			'order_email' => $order_email
		);

		$query = esc_url_raw(add_query_arg($api_params, WPABSTRACTS_SERVER_URL));
		$response = wp_remote_get($query, array('timeout' => 20, 'sslverify' => false));

		if (is_wp_error($response)){
			wpabstracts_show_message(__("We could not perform the lookup at this time. Please try again later.", "wpabstracts"), 'alert-danger');
			return;
		}

		$license_data = json_decode(wp_remote_retrieve_body($response));

		if($license_data->result == 'success'){
			wpabstracts_show_message("Please check your email, grab your key and activate.", 'alert-success');
		}else{
			wpabstracts_show_message("We could not locate a license key with that email.", 'alert-danger');
		}
	}else{
		wpabstracts_show_message("Please enter your order email address.", 'alert-danger');
	}

}

?>
<br>
<div class="wpabstracts container-fluid wpabstracts-admin-container">
	<form method="post" id="wpabstracts_settings" action="<?php echo $_SERVER['REQUEST_URI']; ?>">

		<div class="wpabstracts row">

			<div class="wpabstracts col-xs-12 col-sm-4">
				<div class="wpabstracts panel panel-primary">
					<div class="wpabstracts panel-heading">
						<h6 class="wpabstracts panel-title"><?php _e('Activate License', 'wpabstracts'); ?></h6>
					</div>
					<div class="wpabstracts panel-body">
						<p><?php _e("Please enter your license key. You were given a license key in your order confirmation email.", "wpabstracts");?></p>
						<div class="wpabstracts form-group col-xs-12">
							<?php $renew_link = sprintf( '<a href="%s" target="_blank" class="js-action-link enter-licence">%s</a>', 'https://www.wpabstracts.com/account/licenses/', __( 'renew license', 'wpabstracts' ) ); ?>
							<?php $license = wpabstracts_check_license();?>
							<?php $btnDesc = $license->status == 'active' ? "Deactivate" : "Activate"; ?>
							<?php $btnAction = $license->status == 'active'  ? "deactivate_license" : "activate_license"; ?>
							<?php $msgIcon = $license->status == 'active' ? "text-success glyphicon glyphicon-ok-sign" : "text-danger glyphicon glyphicon-remove-sign"; ?>
							<?php if($license->status == 'invalid'){ ?>
							<div class='wpabstracts containter-fluid'>
								<div class='wpabstracts alert alert-danger' role='alert'><?php _e("Please enter a valid license key.", "wpabstracts");?></div>
							</div>
							<?php } ?>
							<?php if($license->status == 'expired'){ ?>
							<div class='wpabstracts containter-fluid'>
								<div class='wpabstracts alert alert-danger' role='alert'><?php echo sprintf( __( 'Your license has expired. For continued support and updates please %1$s.', 'wpabstracts' ), $renew_link);?></div>
							</div>
							<?php } ?>
							<label for="wpabstracts_license_key">License Key <span class="<?php echo $msgIcon;?>"></span></label>
							<input class="wpabstracts form-control" type="text" id="wpabstracts_license_key" name="wpabstracts_license_key" value="<?php echo $license->license_key;?>">
						</div>
						<div class="wpabstracts form-group col-xs-12">
							<input type="submit" name="<?php echo $btnAction;?>" value="<?php echo $btnDesc;?>" class="button-primary" />
						</div>
					</div>
				</div>
			</div>

			<div class="wpabstracts col-xs-12 col-sm-4">
				<div class="wpabstracts panel panel-primary">
					<div class="wpabstracts panel-heading">
						<h6 class="wpabstracts panel-title"><?php _e('Lost your key?', 'wpabstracts'); ?></h6>
					</div>

					<div class="wpabstracts panel-body">
						<p><?php _e("If you can't find your license key, we're are happy to resend it to you.", "wpabstracts");?></p>
						<div style="display:none" id="wpabstracts-alert" class="wpabstracts alert alert-danger col-sm-12"></div>
						<div class="wpabstracts form-group col-xs-12">
							<label for="wpabstracts_order_email">Order Email</label>
							<input class="wpabstracts form-control" type="text" id="wpabstracts_order_email" name="wpabstracts_order_email"  value="">
						</div>
						<div class="wpabstracts form-group col-xs-12">
							<input type="submit" name="retrieve_license" value="Retrieve License" class="button-primary"/>
						</div>
					</div>
				</div>
			</div>

			<div class="wpabstracts col-xs-12 col-sm-4">
				<div class="wpabstracts panel panel-primary">
					<div class="wpabstracts panel-heading">
						<h6 class="wpabstracts panel-title"><?php _e('Frequently Asked Questions?', 'wpabstracts'); ?></h6>
					</div>

					<div class="wpabstracts panel-body">
						<p><strong><?php _e("Q: ", "wpabstracts");?></strong><?php _e("Why do I need to activate WP Abstracts?", "wpabstracts");?></p>
						<p><strong><?php _e("A: ", "wpabstracts");?></strong><?php _e("Activating allows you to stay up to date with the latest releases and bug fixes.", "wpabstracts");?></p>
						<p><strong><?php _e("Q: ", "wpabstracts");?></strong><?php _e("Do I need to renew my license?", "wpabstracts");?></p>
						<p><strong><?php _e("A: ", "wpabstracts");?></strong><?php _e("Renewing your license allows continued access to support and important releases.", "wpabstracts");?></p>
					</div>
				</div>
			</div>

		</div>

	</form>
</div>
