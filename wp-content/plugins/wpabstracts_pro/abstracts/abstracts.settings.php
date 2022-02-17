<?php
defined('ABSPATH') or die("ERROR: You do not have permission to access this page");

if ($_POST) {
	// save / update options
	foreach ($_POST['options'] as $option => $value) {
		wpabstracts_save_option($option);
    }
	wpabstracts_admin_columns();

	// save / update statuses
    if(isset($_POST['wpabstracts_status'])){
        $statuses = $_POST['wpabstracts_status'];
        foreach($statuses as $id => $status){
            if($status){
                $statusData = array('name' => $status);
                wpabstracts_upsert_status($id, $statusData);
            }
        }
    }
    // remove statuses is necessary
    if(isset($_POST['wpabstracts_delete_status'])){
        $ids = (array) $_POST["wpabstracts_delete_status"];
        wpabstracts_delete_statuses($ids);
    }
	do_action('wpabstracts_save_settings');
	
	wpabstracts_show_message('Awesome! Your user settings are locked away.', 'alert-success');
}

function wpabstracts_save_option($option) {
	switch($option){
		case 'wpabstracts_permitted_attachments':
		$_POST['options'][$option] = str_replace(" ", "", $_POST['options'][$option]);
		break;
    }
	update_option($option, $_POST['options'][$option]);
}

function wpabstracts_admin_columns() {
	$columns = get_option('wpabstracts_abstracts_columns');
	$admin_columns = $_POST['admin_columns'];

	foreach($columns as $key => $column) {
		if(in_array($key, $admin_columns)) {
			$columns[$key]['enabled'] = true;
		} else {
			$columns[$key]['enabled'] = false;
		}
	}
	update_option('wpabstracts_abstracts_columns', $columns);
}

function wpabstracts_is_column_selected($column_key, $columns){
	if(is_array($columns) && array_key_exists($column_key, $columns) && $columns[$column_key]['enabled']){
		return 'checked="checked"';
	}
}

$statuses = wpabstracts_get_statuses();

$columns = get_option('wpabstracts_abstracts_columns');

?>

<div class="wpabstracts container-fluid wpabstracts-admin-container">
	<form method="post" id="abstracts_settings" action="?page=wpabstracts&tab=abstracts&subtab=settings">
		<h3>
			<?php _e('Settings', 'wpabstracts'); ?> <input type="submit" name="Submit" class="wpabstracts btn btn-primary" value="<?php _e('Save Changes', 'wpabstracts'); ?>" />		
		</h3>
		<div class="wpabstracts row">
			<div class="wpabstracts col-xs-12 col-md-6">
				<div class="wpabstracts panel panel-primary">
					<div class="wpabstracts panel-heading">
						<h6 class="wpabstracts panel-title"><?php _e('Abstracts Configuration', 'wpabstracts'); ?></h6>
					</div>

					<div class="wpabstracts panel-body">
						<div class="wpabstracts form-group col-xs-12">
							<?php _e('Show Abstract Description', 'wpabstracts'); ?>
							<span class="settings_tip" data-tip="<?php _e('Use this setting to hide the decription area completely from the submission page.', 'wpabstracts'); ?>">
										<i class="wpabstracts text-info glyphicon glyphicon-question-sign"></i>                        </span>
							<select name="options[wpabstracts_show_description]" class="wpabstracts pull-right">
								<option value="1" <?php selected(get_option('wpabstracts_show_description'), 1); ?>><?php _e('Yes', 'wpabstracts'); ?></option>
								<option value="0" <?php selected(get_option('wpabstracts_show_description'), 0); ?>><?php _e('No', 'wpabstracts'); ?></option>
							</select>
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<?php _e('Allow Editor Media', 'wpabstracts'); ?>
							<span class="settings_tip" data-tip="<?php _e('Use this setting to hide or show the Add Media button on the submission text editor.', 'wpabstracts'); ?>">
								<i class="wpabstracts text-info glyphicon glyphicon-question-sign"></i>
							</span>
							<select name="options[wpabstracts_editor_media]" class="wpabstracts pull-right">
									<option value="1" <?php selected(get_option('wpabstracts_editor_media'), 1); ?>><?php _e('Yes', 'wpabstracts'); ?></option>
									<option value="0" <?php selected(get_option('wpabstracts_editor_media'), 0); ?>><?php _e('No', 'wpabstracts'); ?></option>
							</select>
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<?php _e('Show Author Fields', 'wpabstracts'); ?>
							<span class="settings_tip" data-tip="<?php _e('Use this setting to hide the author area completely from the submission page.', 'wpabstracts'); ?>">
								<i class="wpabstracts text-info glyphicon glyphicon-question-sign"></i>
							</span>
							<select name="options[wpabstracts_show_author]" class="wpabstracts pull-right">
								<option value="1" <?php selected(get_option('wpabstracts_show_author'), 1); ?>><?php _e('Yes', 'wpabstracts'); ?></option>
								<option value="0" <?php selected(get_option('wpabstracts_show_author'), 0); ?>><?php _e('No', 'wpabstracts'); ?></option>
							</select>
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<?php _e('Show Attachment Uploads', 'wpabstracts'); ?>
							<span class="settings_tip" data-tip="<?php _e('Use this setting to hide the attachment area completely from the submission page.', 'wpabstracts'); ?>">
								<i class="wpabstracts text-info glyphicon glyphicon-question-sign"></i>
							</span>
							<select name="options[wpabstracts_show_attachments]" class="wpabstracts pull-right">
								<option value="1" <?php selected(get_option('wpabstracts_show_attachments'), 1); ?>><?php _e('Yes', 'wpabstracts'); ?></option>
								<option value="0" <?php selected(get_option('wpabstracts_show_attachments'), 0); ?>><?php _e('No', 'wpabstracts'); ?></option>
							</select>
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<?php _e('Make Attachment Required', 'wpabstracts'); ?>
							<span class="settings_tip" data-tip="<?php _e('Use this setting to make attachments required or optional.', 'wpabstracts'); ?>">
								<i class="wpabstracts text-info glyphicon glyphicon-question-sign"></i>
							</span>
							<select name="options[wpabstracts_attachment_pref]" class="wpabstracts pull-right">
								<option value="required" <?php selected(get_option('wpabstracts_attachment_pref'), 'required'); ?>><?php _e('Yes', 'wpabstracts'); ?></option>
								<option value="optional" <?php selected(get_option('wpabstracts_attachment_pref'), 'optional'); ?>><?php _e('No', 'wpabstracts'); ?></option>
							</select>
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<?php _e('Show Presenter Fields', 'wpabstracts'); ?>
							<span class="settings_tip" data-tip="<?php _e('Use this setting to hide the presenter area completely from the submission and edit pages.', 'wpabstracts'); ?>">
								<i class="wpabstracts text-info glyphicon glyphicon-question-sign"></i>
							</span>
							<select name="options[wpabstracts_show_presenter]" class="wpabstracts pull-right">
								<option value='1' <?php selected(get_option('wpabstracts_show_presenter'), 1); ?>><?php _e('Yes', 'wpabstracts'); ?></option>
								<option value='0' <?php selected(get_option('wpabstracts_show_presenter'), 0); ?>><?php _e('No', 'wpabstracts'); ?></option>
							</select>
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<?php _e('Show Abstract Keywords', 'wpabstracts'); ?>
							<span class="settings_tip" data-tip="<?php _e('Enable this to display the keywords input on the submission page. Enabling this makes keywords required.', 'wpabstracts'); ?>">
								<i class="wpabstracts text-info glyphicon glyphicon-question-sign"></i>
							</span>
							<select name="options[wpabstracts_show_keywords]" class="wpabstracts pull-right">
								<option value="1" <?php selected(get_option('wpabstracts_show_keywords'), 1); ?>><?php _e('Yes', 'wpabstracts'); ?></option>
								<option value="0" <?php selected(get_option('wpabstracts_show_keywords'), 0); ?>><?php _e('No', 'wpabstracts'); ?></option>
							</select>
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<?php _e('Show Terms & Conditions', 'wpabstracts'); ?>
							<span class="settings_tip" data-tip="<?php _e('Enable this to display your terms and conditions on the submission page. Enabling this makes the checkbox required.', 'wpabstracts'); ?>">
								<i class="wpabstracts text-info glyphicon glyphicon-question-sign"></i>
							</span>
							<select name="options[wpabstracts_show_conditions]" class="wpabstracts pull-right">
								<option value="1" <?php selected(get_option('wpabstracts_show_conditions'), 1); ?>><?php _e('Yes', 'wpabstracts'); ?></option>
								<option value="0" <?php selected(get_option('wpabstracts_show_conditions'), 0); ?>><?php _e('No', 'wpabstracts'); ?></option>
							</select>
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<?php _e('Maximum User Submissions', 'wpabstracts'); ?>
							<span class="settings_tip" data-tip="<?php _e('Set the maximum submissions allowed per user.', 'wpabstracts'); ?>">
									<i class="wpabstracts text-info glyphicon glyphicon-question-sign"></i>
								</span>
							<input name="options[wpabstracts_submit_limit]" type="text" value="<?php echo get_option('wpabstracts_submit_limit'); ?>" class="wpabstracts form-control" />
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<?php _e('Maximum Word Count', 'wpabstracts'); ?>
							<span class="settings_tip" data-tip="<?php _e('Maximum character count allowed in a submission.', 'wpabstracts'); ?>">
								<i class="wpabstracts text-info glyphicon glyphicon-question-sign"></i>
							</span>
							<input name="options[wpabstracts_chars_count]" type="text" id="charscount" value="<?php echo get_option('wpabstracts_chars_count'); ?>" class="wpabstracts form-control" />
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<?php _e('Maximum Attachments', 'wpabstracts'); ?>
							<span class="settings_tip" data-tip="<?php _e('Set the maximum attachment upload allowed per submission.', 'wpabstracts'); ?>">
									<i class="wpabstracts text-info glyphicon glyphicon-question-sign"></i>
								</span>
							<input name="options[wpabstracts_upload_limit]" type="text" value="<?php echo get_option('wpabstracts_upload_limit'); ?>" class="wpabstracts form-control" />
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<?php _e('Maximum Attachment Size', 'wpabstracts'); ?>
							<span class="settings_tip" data-tip="<?php _e('Maxmium size allowed for attachments (in bytes).', 'wpabstracts'); ?>">
								<i class="wpabstracts text-info glyphicon glyphicon-question-sign"></i>
							</span>
							<input name="options[wpabstracts_max_attach_size]" type="text" value="<?php echo get_option('wpabstracts_max_attach_size'); ?>" class="wpabstracts form-control"/>
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<?php _e('Permitted Attachments', 'wpabstracts'); ?>
							<span class="settings_tip" data-tip="<?php _e('File extentions allowed for uploading (separate extentions with a comma).', 'wpabstracts'); ?>">
								<i class="wpabstracts text-info glyphicon glyphicon-question-sign"></i>
							</span>
							<input name="options[wpabstracts_permitted_attachments]" type="text" id="attachments_permitted" value="<?php echo get_option('wpabstracts_permitted_attachments'); ?>" class="wpabstracts form-control" />
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<?php _e('Set Presenter Preferences', 'wpabstracts'); ?>
							<span class="settings_tip" data-tip="<?php _e('Set the types of presentation allowed (separated by commas), Eg. Poster, Panel, Round Table', 'wpabstracts'); ?>">
								<i class="wpabstracts text-info glyphicon glyphicon-question-sign"></i>
							</span>
							<input name="options[wpabstracts_presenter_preference]" type="text" value="<?php echo get_option('wpabstracts_presenter_preference'); ?>" class="wpabstracts form-control"/>
						</div>

					</div>

				</div>
			</div>

			<div class="wpabstracts col-xs-12 col-md-6">
				<div class="wpabstracts panel panel-primary">
					<div class="wpabstracts panel-heading">
						<h6 class="wpabstracts panel-title"><?php _e('Abstract Status', 'wpabstracts'); ?></h6>
					</div>

					<div class="wpabstracts panel-body">

						<div class="wpabstracts form-group col-xs-12" id="wpa_status_container">
							<?php _e('Abstracts Status Description', 'wpabstracts'); ?>
							<span class="settings_tip" data-tip="<?php _e('Describe the four statuses Abstracts may be in.', 'wpabstracts'); ?>">
								<i class="wpabstracts text-info glyphicon glyphicon-question-sign"></i>
								<i class="wpabstracts glyphicon glyphicon-plus-sign add_status" onclick="wpabstracts_add_status();"></i>
							</span>
							<?php foreach($statuses as $status){ ?>
								<?php $status_id = 'wpa_status_' . $status->id;?>
								<div class="input-group wpa_status" id="<?php echo $status_id;?>" style="margin-bottom: 5px;">
									<input type="text" name="wpabstracts_status[<?php echo $status->id;?>]" value="<?php echo $status->name; ?>" class="form-control" autocomplete="off">
									<i class="wpabstracts input-group-addon glyphicon glyphicon-minus-sign delete_status" onclick="wpabstracts_delete_status(this, <?php echo $status->id;?>);"></i>
								</div>
							<?php } ?>
							<div class="input-group wpa_status_default hidden">
								<input type="text" name="wpabstracts_status[]" class="form-control" autocomplete="off">
								<i class="wpabstracts input-group-addon glyphicon glyphicon-minus-sign delete_status" onclick="wpabstracts_delete_status(this, null);"></i>
							</div>
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<?php _e('Status that allow Revision', 'wpabstracts'); ?> 
							<span class="settings_tip" data-tip="<?php _e('Select the status when authors are allowed to edit their submission.', 'wpabstracts'); ?>">
								<i class="wpabstracts text-info glyphicon glyphicon-question-sign"></i>
							</span>
							<?php $edit_status = get_option('wpabstracts_edit_status'); ?>
							<select name="options[wpabstracts_edit_status]" class="wpabstracts form-control">
								<?php foreach ($statuses as $key => $status) { ?>
									<option value="<?php echo $status->id; ?>" <?php selected($edit_status, $status->id); ?>><?php _e($status->name, 'wpabstracts'); ?></option>
								<?php } ?>
							</select>
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<?php _e('Default Submission Status', 'wpabstracts'); ?> 
							<span class="settings_tip" data-tip="<?php _e('Select the default status of newly submitted abstracts.', 'wpabstracts'); ?>">
								<i class="wpabstracts text-info glyphicon glyphicon-question-sign"></i>
							</span>
							<?php $edit_status = get_option('wpabstracts_default_status'); ?>
							<select name="options[wpabstracts_default_status]" class="wpabstracts form-control">
								<?php foreach ($statuses as $key => $status) { ?>
									<option value="<?php echo $status->id; ?>" <?php selected($edit_status, $status->id); ?>><?php _e($status->name, 'wpabstracts'); ?></option>
								<?php } ?>
							</select>
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<?php _e('Sync Review Status to Abstracts', 'wpabstracts'); ?>
							<span class="settings_tip" data-tip="<?php _e('Enable this to allow reviewer status selection to update the abstract status. This works best when one reviewer is assigned to the abstract. If more than one reviews are submitted the last one wins.', 'wpabstracts'); ?>">
									<i class="wpabstracts text-info glyphicon glyphicon-question-sign"></i>
								</span>
							<select name="options[wpabstracts_sync_status]" class="wpabstracts pull-right">
								<option value="1" <?php selected(get_option('wpabstracts_sync_status'), 1); ?>><?php _e('Yes', 'wpabstracts'); ?></option>
								<option value="0" <?php selected(get_option('wpabstracts_sync_status'), 0); ?>><?php _e('No', 'wpabstracts'); ?></option>
							</select>
						</div>
						
					</div>
				</div>

				<div class="wpabstracts panel panel-primary">
					<div class="wpabstracts panel-heading">
						<h6 class="wpabstracts panel-title"><?php _e('Admin Columns', 'wpabstracts'); ?>
							<span class="settings_tip" data-tip="<?php _e('Select columns to display on manage abstracts tab.', 'wpabstracts'); ?>">
								<i class="wpabstracts text-info glyphicon glyphicon-question-sign"></i>
							</span>
						</h6>
					</div>

					<div class="wpabstracts panel-body">
						<div class="wpabstracts form-group col-xs-12">
							<?php 
							foreach($columns as $key => $column) { ?>
								<div class="checkbox">
									<label>
										<input type="checkbox" name="admin_columns[]" value="<?php echo $key;?>" <?php echo wpabstracts_is_column_selected($key, $columns);?>>
										<?php echo $columns[$key]['label'];?>
									</label>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>

		</div>
		<?php apply_filters('wpabstracts_html_filter', $html = null, 'abstracts_settings_after'); ?>

	</form>
</div>
