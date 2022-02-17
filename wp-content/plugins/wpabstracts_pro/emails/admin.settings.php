<?php
defined('ABSPATH') or die("ERROR: You do not have permission to access this page");

function wpabstracts_save_option($option) {
	update_option($option, $_POST['options'][$option]);
}

if ($_POST) {
	foreach ($_POST['options'] as $option => $value) {
		wpabstracts_save_option($option);
    }
    // save / update statuses
    if(isset($_POST['status_template'])){
        $statuses = $_POST['status_template'];
        foreach($statuses as $id => $templateId){
            wpabstracts_upsert_status($id, array('template_id' => $templateId));
        }
    }
    // save / update admin notification recipients
    if(isset($_POST['wpabstracts_admin_enabled'])){
        $admins_enabled = $_POST['wpabstracts_admin_enabled'];
        foreach($admins_enabled as $id => $enabled){
            update_user_meta($id, 'wpabstracts_enable_notification', $enabled);
        }
    }
    wpabstracts_show_message('Awesome, your email settings are locked away.', 'alert-success');
}

?>
                 

<div class="wpabstracts container-fluid wpabstracts-admin-container">
	<form method="post" id="wpabstracts_settings" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
        <h3><?php _e('Settings', 'wpabstracts'); ?> <input type="submit" name="Submit" class="wpabstracts btn btn-primary" value="<?php _e('Save Changes', 'wpabstracts'); ?>" /></h3>
        <?php $templates = wpabstracts_get_email_templates();?>
		<div class="wpabstracts row">
            <div class="wpabstracts col-xs-12 col-sm-8">
                <div class="wpabstracts panel panel-primary">
                    <div class="wpabstracts panel-heading">
                        <h6 class="wpabstracts panel-title">
                            <?php _e('Enable Submission Triggers', 'wpabstracts'); ?>
                            <select name="options[wpabstracts_submission_notification]">
                                <option value="1" <?php selected(get_option('wpabstracts_submission_notification'), 1); ?>><?php _e('Yes', 'wpabstracts'); ?></option>
                                <option value="0" <?php selected(get_option('wpabstracts_submission_notification'), 0); ?>><?php _e('No', 'wpabstracts'); ?></option>
                            </select>
                            <span class="settings_tip pull-right" data-tip="<?php _e('Enable or disable all submission email trigger.', 'wpabstracts'); ?>">
                                <i class="wpabstracts text-info glyphicon glyphicon-question-sign"></i>
                            </span>
                        </h6>
                    </div>

                    <div class="wpabstracts panel-body">

                        <div class="wpabstracts form-group col-xs-12">
                            <?php _e('Admin Notification', 'wpabstracts'); ?>
                            <?php $admin_submit_templateId = get_option("wpabstracts_admin_templateId");?>
                            <span class="settings_tip" data-tip="<?php _e('Select a template to email the site admin on submissions of new abstracts.', 'wpabstracts'); ?>">
                                <i class="wpabstracts text-info glyphicon glyphicon-question-sign"></i>
                            </span>
                            <select name="options[wpabstracts_admin_templateId]" class="wpabstracts pull-right">
                                <?php foreach($templates as $template){?>
                                    <option value="<?php echo $template->ID;?>" <?php selected($admin_submit_templateId, $template->ID); ?>><?php echo $template->name; ?></option>
                                <?php } ?>
                                    <option value="-1" <?php selected($admin_submit_templateId, "-1"); ?>><?php _e('-Do not send email-', 'wpabstracts'); ?></option>
                            </select>
                        </div>

                        <div class="wpabstracts form-group col-xs-12">
                            <?php _e('Author Notification', 'wpabstracts'); ?>
                            <?php $author_submit_templateId = get_option("wpabstracts_submit_templateId");?>
                            <span class="settings_tip" data-tip="<?php _e('Enable this to send an email to authors when they submit an abstract (email is sent to Author\'s email).', 'wpabstracts'); ?>">
                                    <i class="wpabstracts text-info glyphicon glyphicon-question-sign"></i>
                            </span>
                            <select name="options[wpabstracts_submit_templateId]" class="wpabstracts pull-right">
                                <?php foreach($templates as $template){?>
                                    <option value="<?php echo $template->ID;?>" <?php selected($author_submit_templateId, $template->ID); ?>><?php echo $template->name; ?></option>
                                <?php } ?>
                                    <option value="-1" <?php selected($author_submit_templateId, "-1"); ?>><?php _e('-Do not send email-', 'wpabstracts'); ?></option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="wpabstracts col-xs-12 col-sm-4">
				<div class="wpabstracts panel panel-primary">
					<div class="wpabstracts panel-heading">
						<h6 class="wpabstracts panel-title">
                            <?php _e('Help', 'wpabstracts'); ?>
                        </h6>
                    </div>
                    <div class="wpabstracts panel-body">                    
                        <p><?php _e('Select the email template for notifications when an abstract is submitted.', 'wpabstracts'); ?></p>
                        <p><?php _e('Use the master enable / disable option to turn off all submitted related notification. Select - Do not send email - to disable a notification on a particular destination email.', 'wpabstracts'); ?></p>
                    </div>
				</div>
            </div>
        </div>
        <div class="wpabstracts row">
            <div class="wpabstracts col-xs-12 col-sm-8">
                <div class="wpabstracts panel panel-primary">
                    <div class="wpabstracts panel-heading">
                        <h6 class="wpabstracts panel-title">
                            <?php _e('Enable Revision Triggers', 'wpabstracts'); ?>
                            <select name="options[wpabstracts_edit_notification]">
                                <option value="1" <?php selected(get_option('wpabstracts_edit_notification'), 1); ?>><?php _e('Yes', 'wpabstracts'); ?></option>
                                <option value="0" <?php selected(get_option('wpabstracts_edit_notification'), 0); ?>><?php _e('No', 'wpabstracts'); ?></option>
                            </select>
                            <span class="settings_tip pull-right" data-tip="<?php _e('Enable or disable all edit email triggers.', 'wpabstracts'); ?>">
                                <i class="wpabstracts text-info glyphicon glyphicon-question-sign"></i>
                            </span>
                        </h6>
                    </div>

                    <div class="wpabstracts panel-body">

                        <div class="wpabstracts form-group col-xs-12">
                            <?php _e('Admin Notification', 'wpabstracts'); ?>
                            <?php $admin_submit_templateId = get_option("wpabstracts_admin_edit_templateId");?>
                            <span class="settings_tip" data-tip="<?php _e('Select a template to email the site admin owhen an abstract is edited.', 'wpabstracts'); ?>">
                                <i class="wpabstracts text-info glyphicon glyphicon-question-sign"></i>
                            </span>
                            <select name="options[wpabstracts_admin_edit_templateId]" class="wpabstracts pull-right">
                                <?php foreach($templates as $template){?>
                                    <option value="<?php echo $template->ID;?>" <?php selected($admin_submit_templateId, $template->ID); ?>><?php echo $template->name; ?></option>
                                <?php } ?>
                                    <option value="-1" <?php selected($admin_submit_templateId, "-1"); ?>><?php _e('-Do not send email-', 'wpabstracts'); ?></option>
                            </select>
                        </div>

                        <div class="wpabstracts form-group col-xs-12">
                            <?php _e('Author Notification', 'wpabstracts'); ?>
                            <?php $author_submit_templateId = get_option("wpabstracts_author_edit_templateId");?>
                            <span class="settings_tip" data-tip="<?php _e('Enable this to send an email to authors when they edit an abstract (email is sent to Author\'s email).', 'wpabstracts'); ?>">
                                    <i class="wpabstracts text-info glyphicon glyphicon-question-sign"></i>
                            </span>
                            <select name="options[wpabstracts_author_edit_templateId]" class="wpabstracts pull-right">
                                <?php foreach($templates as $template){?>
                                    <option value="<?php echo $template->ID;?>" <?php selected($author_submit_templateId, $template->ID); ?>><?php echo $template->name; ?></option>
                                <?php } ?>
                                    <option value="-1" <?php selected($author_submit_templateId, "-1"); ?>><?php _e('-Do not send email-', 'wpabstracts'); ?></option>
                            </select>
                        </div>

                         <div class="wpabstracts form-group col-xs-12">
                            <?php _e('Reviewer Notification', 'wpabstracts'); ?>
                            <?php $reviewer_revised_templateId = get_option("wpabstracts_submit_revised_templateId");?>
                            <span class="settings_tip" data-tip="<?php _e('Enable this to send an email to assigned reviewers when an abstract is edited (email is sent to all reviewers on submission).', 'wpabstracts'); ?>">
                                    <i class="wpabstracts text-info glyphicon glyphicon-question-sign"></i>
                            </span>
                            <select name="options[wpabstracts_submit_revised_templateId]" class="wpabstracts pull-right">
                                <?php foreach($templates as $template){?>
                                    <option value="<?php echo $template->ID;?>" <?php selected($reviewer_revised_templateId, $template->ID); ?>><?php echo $template->name; ?></option>
                                <?php } ?>
                                    <option value="-1" <?php selected($reviewer_revised_templateId, "-1"); ?>><?php _e('-Do not send email-', 'wpabstracts'); ?></option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="wpabstracts col-xs-12 col-sm-4">
				<div class="wpabstracts panel panel-primary">
					<div class="wpabstracts panel-heading">
						<h6 class="wpabstracts panel-title">
                            <?php _e('Help', 'wpabstracts'); ?>
                        </h6>
                    </div>
                    <div class="wpabstracts panel-body">                    
                        <p><?php _e('Select the email template for notifications when an abstract is edited.', 'wpabstracts'); ?></p>
                        <p><?php _e('Use the master enable / disable option to turn off all edit related notification. Select - Do not send email - to disable a notification on a particular destination email.', 'wpabstracts'); ?></p>
                    </div>
				</div>
            </div>
        </div>
        <div class="wpabstracts row">
            <div class="wpabstracts col-xs-12 col-sm-8">
                <div class="wpabstracts panel panel-primary">
                    <div class="wpabstracts panel-heading">
                        <h6 class="wpabstracts panel-title">
                            <?php _e('Enable Reviewer Triggers', 'wpabstracts'); ?>
                            <select name="options[wpabstracts_review_notification]">
                                <option value="1" <?php selected(get_option('wpabstracts_review_notification'), 1); ?>><?php _e('Yes', 'wpabstracts'); ?></option>
                                <option value="0" <?php selected(get_option('wpabstracts_review_notification'), 0); ?>><?php _e('No', 'wpabstracts'); ?></option>
                            </select>
                            <span class="settings_tip pull-right" data-tip="<?php _e('Enable or disable all reviewer email trigger.', 'wpabstracts'); ?>">
                                <i class="wpabstracts text-info glyphicon glyphicon-question-sign"></i>
                            </span>
                        </h6>
                    </div>

                    <div class="wpabstracts panel-body">

                        <div class="wpabstracts form-group col-xs-12">
                            <?php _e('Admin Notification', 'wpabstracts'); ?>
                            <?php $admin_review_templateId = get_option("wpabstracts_reviewedadmin_templateId");?>
                            <span class="settings_tip" data-tip="<?php _e('Enable this to send an email to administrators when a review was submitted.', 'wpabstracts'); ?>">
                                <i class="wpabstracts text-info glyphicon glyphicon-question-sign"></i>
                                </span>
                            <select name="options[wpabstracts_reviewedadmin_templateId]" class="wpabstracts pull-right">
                                <?php foreach($templates as $template){?>
                                    <option value="<?php echo $template->ID;?>" <?php selected($admin_review_templateId, $template->ID); ?>><?php echo $template->name; ?></option>
                                <?php } ?>
                                    <option value="-1" <?php selected($admin_review_templateId, "-1"); ?>><?php _e('-Do not send email-', 'wpabstracts'); ?></option>
                            </select>
                        </div>

                        <div class="wpabstracts form-group col-xs-12">
                            <?php _e('Reviewer Notification', 'wpabstracts'); ?>
                            <?php $reviewer_review_templateId = get_option("wpabstracts_reviewedreviewer_templateId");?>
                            <span class="settings_tip" data-tip="<?php _e('Enable this to send an email to the reviewer when he/she submits a review.', 'wpabstracts'); ?>">
                                <i class="wpabstracts text-info glyphicon glyphicon-question-sign"></i>
                                </span>
                            <select name="options[wpabstracts_reviewedreviewer_templateId]" class="wpabstracts pull-right">
                                <?php foreach($templates as $template){?>
                                    <option value="<?php echo $template->ID;?>" <?php selected($reviewer_review_templateId, $template->ID); ?>><?php echo $template->name; ?></option>
                                <?php } ?>
                                    <option value="-1" <?php selected($reviewer_review_templateId, "-1"); ?>><?php _e('-Do not send email-', 'wpabstracts'); ?></option>
                            </select>
                        </div>

                        <div class="wpabstracts form-group col-xs-12">
                            <?php _e('Author Notification', 'wpabstracts'); ?>
                            <?php $author_review_templateId = get_option("wpabstracts_reviewed_templateId");?>
                            <span class="settings_tip" data-tip="<?php _e('Enable this to send an email to authors when their abstracts has been reviewed (email is sent to submitter\'s email only).', 'wpabstracts'); ?>">
                                <i class="wpabstracts text-info glyphicon glyphicon-question-sign"></i>
                                </span>
                            <select name="options[wpabstracts_reviewed_templateId]" class="wpabstracts pull-right">
                                <?php foreach($templates as $template){?>
                                    <option value="<?php echo $template->ID;?>" <?php selected($author_review_templateId, $template->ID); ?>><?php echo $template->name; ?></option>
                                <?php } ?>
                                    <option value="-1" <?php selected($author_review_templateId, "-1"); ?>><?php _e('-Do not send email-', 'wpabstracts'); ?></option>
                            </select>
                        </div>

                        <div class="wpabstracts form-group col-xs-12">
                            <?php _e('Reviewer Assignment Notification', 'wpabstracts'); ?>
                            <?php $reviewer_assignment_templateId = get_option("wpabstracts_assignment_templateId");?>
                            <span class="settings_tip" data-tip="<?php _e('Select a template to send to reviewers when they have been assigned a submission.', 'wpabstracts'); ?>">
                                <i class="wpabstracts text-info glyphicon glyphicon-question-sign"></i>
                                </span>
                            <select name="options[wpabstracts_assignment_templateId]" class="wpabstracts pull-right">
                                <?php foreach($templates as $template){?>
                                    <option value="<?php echo $template->ID;?>" <?php selected($reviewer_assignment_templateId, $template->ID); ?>><?php echo $template->name; ?></option>
                                <?php } ?>
                                    <option value="-1" <?php selected($reviewer_assignment_templateId, "-1"); ?>><?php _e('-Do not send email-', 'wpabstracts'); ?></option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="wpabstracts col-xs-12 col-sm-4">
				<div class="wpabstracts panel panel-primary">
					<div class="wpabstracts panel-heading">
						<h6 class="wpabstracts panel-title">
                            <?php _e('Help', 'wpabstracts'); ?>
                        </h6>
                    </div>
                    <div class="wpabstracts panel-body">
                        <p><?php _e('Select reviewer related templates for admin and author notification when reviews are submitted and when an abstract is assigned to a reviewer.', 'wpabstracts'); ?></p>
                        <p><?php _e('Use the master enable / disable option to turn off all reviewer related notification. Select - Do not send email - to disable a notification on a particular status change.', 'wpabstracts'); ?></p>
                    </div>
				</div>
            </div>
        </div>

        <div class="wpabstracts row">
			<div class="wpabstracts col-xs-12 col-sm-8">
				<div class="wpabstracts panel panel-primary">
					<div class="wpabstracts panel-heading">
						<h6 class="wpabstracts panel-title">
                            <?php _e('Enable Status Change Triggers', 'wpabstracts'); ?>
                            <select name="options[wpabstracts_status_notification]">
                                <option value="1" <?php selected(get_option('wpabstracts_status_notification'), 1); ?>><?php _e('Yes', 'wpabstracts'); ?></option>
                                <option value="0" <?php selected(get_option('wpabstracts_status_notification'), 0); ?>><?php _e('No', 'wpabstracts'); ?></option>
                            </select>
                            <span class="settings_tip pull-right" data-tip="<?php _e('Enable or disable all status change email trigger.', 'wpabstracts'); ?>">
                                <i class="wpabstracts text-info glyphicon glyphicon-question-sign"></i>
                            </span>
                        </h6>
                    </div>
                    <div class="wpabstracts panel-body">
                        <?php $statuses = wpabstracts_get_statuses();
                        foreach($statuses as $status){ ?>                        
                            <div class="wpabstracts form-group col-xs-12">
                                <?php echo $status->name; ?>
                                <span class="settings_tip" data-tip="<?php _e('Select the email template for this status change notification', 'wpabstracts'); ?>">
                                    <i class="wpabstracts text-info glyphicon glyphicon-question-sign"></i>
                                </span>
                                <select name="status_template[<?php echo $status->id;?>]" class="wpabstracts pull-right">
                                <?php foreach($templates as $template){?>
                                    <option value="<?php echo $template->ID;?>" <?php selected($status->template_id, $template->ID); ?>><?php echo $template->name; ?></option>
                                <?php } ?>
                                    <option value="-1" <?php selected($status->template_id, "-1"); ?>><?php _e('-Do not send email-', 'wpabstracts'); ?></option>
                                </select>
                            </div>
                        <?php } ?>
                    </div>
				</div>
            </div>
            
            <div class="wpabstracts col-xs-12 col-sm-4">
				<div class="wpabstracts panel panel-primary">
					<div class="wpabstracts panel-heading">
						<h6 class="wpabstracts panel-title">
                            <?php _e('Help', 'wpabstracts'); ?>
                        </h6>
                    </div>
                    <div class="wpabstracts panel-body">
                        <p><?php _e('Select the email template for when the status is changed on Abstracts.', 'wpabstracts'); ?></p>
                        <p><?php _e('Use the master enable / disable option to turn off all reviewer related notification. Select - Do not send email - to disable a notification on a particular status change.', 'wpabstracts'); ?></p>
                    </div>
				</div>
            </div>
        </div>
        
        <div class="wpabstracts row">
			<div class="wpabstracts col-xs-12 col-sm-8">
				<div class="wpabstracts panel panel-primary">
					<div class="wpabstracts panel-heading">
						<h6 class="wpabstracts panel-title">
                            <?php _e('Admin Notification Recipients', 'wpabstracts'); ?>
                            <span class="settings_tip" data-tip="<?php _e('Enable or disable admin notications for admin users.', 'wpabstracts'); ?>">
                                <i class="wpabstracts text-info glyphicon glyphicon-question-sign"></i>
                            </span>
                        </h6>
                    </div>
                    <div class="wpabstracts panel-body">
                        <div class="wpabstracts form-group col-xs-12" id="wpa_email_container">
                            <?php $super_admins = get_users( array('role'=>'administrator', 'fields' => array('ID', 'user_email', 'display_name')));?>
                            <?php foreach($super_admins as $super_admin){ ?>
                            <?php $enabled = get_user_meta($super_admin->ID, 'wpabstracts_enable_notification', true);?>
                            <div class="wpabstracts form-group col-xs-12">
                                <?php echo $super_admin->display_name . ' ['.$super_admin->user_email.']';?>
                                <select name="wpabstracts_admin_enabled[<?php echo $super_admin->ID;?>]" class="wpabstracts pull-right">>
                                    <option value="1" <?php selected(intval($enabled), 1); ?>><?php _e('Yes', 'wpabstracts'); ?></option>
                                    <option value="0" <?php selected(intval($enabled), 0); ?>><?php _e('No', 'wpabstracts'); ?></option>
                                </select>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
				</div>
            </div>
		</div>
	</form>
</div>
