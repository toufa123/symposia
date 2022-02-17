<?php
defined('ABSPATH') or die("ERROR: You do not have permission to access this page");

if(isset($_GET['task']) && $_GET['task'] == 'reset') {
	if(check_admin_referer("subtab=titles&task=reset")) {
		$defaultTitles = wpabstracts_get_custom_titles();
		update_option('wpabstracts_custom_titles', $defaultTitles);
		wpabstracts_show_message('Your titles and messages were restored.', 'alert-success');
	}else {
		wpabstracts_show_message("Action terminated due to failed security checks.", 'alert-danger');
	}
}

if ($_POST) {
	wpabstracts_save_titles();
	wpabstracts_show_message('Awesome, your custom titles are locked away.', 'alert-success');
}

$titles = get_option('wpabstracts_custom_titles');

function wpabstracts_save_titles() {
	$customTitles = new stdClass();
	// submission
	$customTitles->new_abstract = sanitize_text_field($_POST['new_abstract']);
	$customTitles->edit_abstract = sanitize_text_field($_POST['edit_abstract']);
	$customTitles->abstract_information = sanitize_text_field($_POST['abstract_information']);
	$customTitles->enter_title = sanitize_text_field($_POST['enter_title']);
	$customTitles->keywords = sanitize_text_field($_POST['keywords']);
	$customTitles->no_keywords = sanitize_text_field($_POST['no_keywords']);
	$customTitles->enter_keywords = sanitize_text_field($_POST['enter_keywords']);
	$customTitles->attachments = sanitize_text_field($_POST['attachments']);
	$customTitles->no_attachments = sanitize_text_field($_POST['no_attachments']);
	$customTitles->attachment_help = sanitize_text_field($_POST['attachment_help']);
	$customTitles->supported_formats = sanitize_text_field($_POST['supported_formats']);
	$customTitles->max_attachment_size = sanitize_text_field($_POST['max_attachment_size']);
	$customTitles->terms_conditions = sanitize_text_field($_POST['terms_conditions']);
	$customTitles->event_information = sanitize_text_field($_POST['event_information']);
	$customTitles->event = sanitize_text_field($_POST['event']);
	$customTitles->select_event = sanitize_text_field($_POST['select_event']);
	$customTitles->topic = sanitize_text_field($_POST['topic']);
	$customTitles->select_topic = sanitize_text_field($_POST['select_topic']);
	$customTitles->author_information = sanitize_text_field($_POST['author_information']);
	$customTitles->author_name = sanitize_text_field($_POST['author_name']);
	$customTitles->author_email = sanitize_text_field($_POST['author_email']);
	$customTitles->author_affiliation = sanitize_text_field($_POST['author_affiliation']);
	$customTitles->presenter_information = sanitize_text_field($_POST['presenter_information']);
	$customTitles->presenter_name = sanitize_text_field($_POST['presenter_name']);
	$customTitles->presenter_email = sanitize_text_field($_POST['presenter_email']);
	$customTitles->presenter_preference = sanitize_text_field($_POST['presenter_preference']);

	// reviews
	$customTitles->new_review = sanitize_text_field($_POST['new_review']);
	$customTitles->edit_review = sanitize_text_field($_POST['edit_review']);
	$customTitles->add_comments = sanitize_text_field($_POST['add_comments']);
	$customTitles->reviewer_attachment_help = sanitize_text_field($_POST['reviewer_attachment_help']);
	$customTitles->review_attachments = sanitize_text_field($_POST['review_attachments']);
	$customTitles->additional_information = sanitize_text_field($_POST['additional_information']);
	$customTitles->suggest_type = sanitize_text_field($_POST['suggest_type']);
	$customTitles->suggest_status = sanitize_text_field($_POST['suggest_status']);
	$customTitles->submitted = sanitize_text_field($_POST['submitted']);

	// login 
	$customTitles->sign_in_help = sanitize_text_field($_POST['sign_in_help']);
	$customTitles->sign_in = sanitize_text_field($_POST['sign_in']);
	$customTitles->login_btn = sanitize_text_field($_POST['login_btn']);
	$customTitles->remember_me = sanitize_text_field($_POST['remember_me']);
	$customTitles->forgot_password = sanitize_text_field($_POST['forgot_password']);
	$customTitles->get_password = sanitize_text_field($_POST['get_password']);
	$customTitles->reset_password = sanitize_text_field($_POST['reset_password']);
	$customTitles->create_account = sanitize_text_field($_POST['create_account']);
	$customTitles->no_account = sanitize_text_field($_POST['no_account']);
	$customTitles->security_code = sanitize_text_field($_POST['security_code']);

	// register
	$customTitles->login_information = sanitize_text_field($_POST['login_information']);
	$customTitles->account_information = sanitize_text_field($_POST['account_information']);

	// dashboard
	$customTitles->dashboard = sanitize_text_field($_POST['dashboard']);
	$customTitles->welcome_back = sanitize_text_field($_POST['welcome_back']);
	$customTitles->logout_btn = sanitize_text_field($_POST['logout_btn']);
	$customTitles->my_profile = sanitize_text_field($_POST['my_profile']);
	$customTitles->my_abstracts = sanitize_text_field($_POST['my_abstracts']);
	$customTitles->assigned_abstracts = sanitize_text_field($_POST['assigned_abstracts']);
	$customTitles->my_reviews = sanitize_text_field($_POST['my_reviews']);
	$customTitles->edit_profile = sanitize_text_field($_POST['edit_profile']);
	$customTitles->profile_information = sanitize_text_field($_POST['profile_information']);
	$customTitles->review_document = sanitize_text_field($_POST['review_document']);
	update_option('wpabstracts_custom_titles', $customTitles);
}

?>

<div class="wpabstracts container-fluid wpabstracts-admin-container">
	<form method="post" id="wpabstracts_custom_titles" action="?page=wpabstracts&tab=settings&subtab=titles">
		<h3>
			<?php _e('Custom Titles', 'wpabstracts'); ?> <input type="submit" name="Submit" class="wpabstracts btn btn-primary" value="<?php _e('Save Changes', 'wpabstracts'); ?>" />
			<a href="javascript:wpabstracts_restore_titles(`<?php echo wp_create_nonce("subtab=titles&task=reset");?>`);" class="wpabstracts btn btn-primary"><?php _e('Reset Defaults', 'wpabstracts'); ?></a>

		</h3>
		<div class="wpabstracts row">
			<div class="wpabstracts col-xs-12 col-sm-4">
				<div class="wpabstracts panel panel-primary">
					<div class="wpabstracts panel-heading">
						<h6 class="wpabstracts panel-title"><?php _e('Login', 'wpabstracts'); ?></h6>
					</div>

					<div class="wpabstracts panel-body">

						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('Please sign in for your conference participation', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="sign_in_help" type="text" value="<?php echo stripslashes($titles->sign_in_help); ?>" />
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('Sign In', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="sign_in" type="text" value="<?php echo stripslashes($titles->sign_in); ?>" />
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('Login', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="login_btn" type="text" value="<?php echo stripslashes($titles->login_btn); ?>" />
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('Remember Me', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="remember_me" type="text" value="<?php echo stripslashes($titles->remember_me); ?>" />
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('Forgot Password', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="forgot_password" type="text" value="<?php echo stripslashes($titles->forgot_password); ?>" />
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('Get New Password', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="get_password" type="text" value="<?php echo stripslashes($titles->get_password); ?>" />
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('Reset', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="reset_password" type="text" value="<?php echo stripslashes($titles->reset_password); ?>" />
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('Create an Account', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="create_account" type="text" value="<?php echo stripslashes($titles->create_account); ?>" />
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('Don\'t have an account? ', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="no_account" type="text" value="<?php echo stripslashes($titles->no_account); ?>" />
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('Enter security code (required)', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="security_code" type="text" value="<?php echo stripslashes($titles->security_code); ?>" />
						</div>

					</div>
				</div>

				<div class="wpabstracts panel panel-primary">
					<div class="wpabstracts panel-heading">
						<h6 class="wpabstracts panel-title"><?php _e('Register', 'wpabstracts'); ?></h6>
					</div>

					<div class="wpabstracts panel-body">

						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('Login Information', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="login_information" type="text" value="<?php echo stripslashes($titles->login_information); ?>" />
						</div>
						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('Account Information', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="account_information" type="text" value="<?php echo stripslashes($titles->account_information); ?>" />
						</div>
					</div>
				</div>

				<div class="wpabstracts panel panel-primary">
					<div class="wpabstracts panel-heading">
						<h6 class="wpabstracts panel-title"><?php _e('Dashboard', 'wpabstracts'); ?></h6>
					</div>

					<div class="wpabstracts panel-body">

					<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('Dashboard', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="dashboard" type="text" value="<?php echo stripslashes($titles->dashboard); ?>" />
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('Welcome back', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="welcome_back" type="text" value="<?php echo stripslashes($titles->welcome_back); ?>" />
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('Logout', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="logout_btn" type="text" value="<?php echo stripslashes($titles->logout_btn); ?>" />
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('My Profile', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="my_profile" type="text" value="<?php echo stripslashes($titles->my_profile); ?>" />
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('My Abstracts', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="my_abstracts" type="text" value="<?php echo stripslashes($titles->my_abstracts); ?>" />
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('Assigned Abstracts', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="assigned_abstracts" type="text" value="<?php echo stripslashes($titles->assigned_abstracts); ?>" />
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('My Reviews', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="my_reviews" type="text" value="<?php echo stripslashes($titles->my_reviews); ?>" />
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('Edit Profile', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="edit_profile" type="text" value="<?php echo stripslashes($titles->edit_profile); ?>" />
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('Profile Information', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="profile_information" type="text" value="<?php echo stripslashes($titles->profile_information); ?>" />
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('Document', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="review_document" type="text" value="<?php echo stripslashes($titles->review_document); ?>" />
						</div>

					</div>
				</div>
			</div>

			<div class="wpabstracts col-xs-12 col-sm-4">
				<div class="wpabstracts panel panel-primary">
					<div class="wpabstracts panel-heading">
						<h6 class="wpabstracts panel-title"><?php _e('Submissions', 'wpabstracts'); ?></h6>
					</div>

					<div class="wpabstracts panel-body">

						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('New Abstract', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="new_abstract" type="text" value="<?php echo stripslashes($titles->new_abstract); ?>" />
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('Edit Abstract', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="edit_abstract" type="text" value="<?php echo stripslashes($titles->edit_abstract); ?>" />
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('Abstract Information', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="abstract_information" type="text" value="<?php echo stripslashes($titles->abstract_information); ?>" />
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('Enter Title', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="enter_title" type="text" value="<?php echo stripslashes($titles->enter_title); ?>" />
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('Keywords', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="keywords" type="text" value="<?php echo stripslashes($titles->keywords); ?>" />
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('No keywords found', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="no_keywords" type="text" value="<?php echo stripslashes($titles->no_keywords); ?>" />
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('Enter comma separated keywords', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="enter_keywords" type="text" value="<?php echo stripslashes($titles->enter_keywords); ?>" />
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('Attachments', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="attachments" type="text" value="<?php echo stripslashes($titles->attachments); ?>" />
						</div>
						
						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('No Attachments submitted', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="no_attachments" type="text" value="<?php echo stripslashes($titles->no_attachments); ?>" />
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('Use this form to upload your images, photos or tables', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="attachment_help" type="text" value="<?php echo stripslashes($titles->attachment_help); ?>" />
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('Supported formats', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="supported_formats" type="text" value="<?php echo stripslashes($titles->supported_formats); ?>" />
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('Maximum attachment size', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="max_attachment_size" type="text" value="<?php echo stripslashes($titles->max_attachment_size); ?>" />
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('Terms and Conditions', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="terms_conditions" type="text" value="<?php echo stripslashes($titles->terms_conditions); ?>" />
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('Event Information', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="event_information" type="text" value="<?php echo stripslashes($titles->event_information); ?>" />
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('Event', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="event" type="text" value="<?php echo stripslashes($titles->event); ?>" />
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('Select an Event', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="select_event" type="text" value="<?php echo stripslashes($titles->select_event); ?>" />
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('Topic', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="topic" type="text" value="<?php echo stripslashes($titles->topic); ?>" />
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('Select a Topic', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="select_topic" type="text" value="<?php echo stripslashes($titles->select_topic); ?>" />
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('Author Information', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="author_information" type="text" value="<?php echo stripslashes($titles->author_information); ?>" />
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('Author Name', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="author_name" type="text" value="<?php echo stripslashes($titles->author_name); ?>" />
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('Author Email', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="author_email" type="text" value="<?php echo stripslashes($titles->author_email); ?>" />
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('Author Affiliation', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="author_affiliation" type="text" value="<?php echo stripslashes($titles->author_affiliation); ?>" />
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('Presenter Information', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="presenter_information" type="text" value="<?php echo stripslashes($titles->presenter_information); ?>" />
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('Presenter Name', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="presenter_name" type="text" value="<?php echo stripslashes($titles->presenter_name); ?>" />
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('Presenter Email', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="presenter_email" type="text" value="<?php echo stripslashes($titles->presenter_email); ?>" />
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('Presenter Preference', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="presenter_preference" type="text" value="<?php echo stripslashes($titles->presenter_preference); ?>" />
						</div>

					</div>
				</div>
			</div>
			<div class="wpabstracts col-xs-12 col-sm-4">
			<div class="wpabstracts panel panel-primary">
					<div class="wpabstracts panel-heading">
						<h6 class="wpabstracts panel-title"><?php _e('Reviews', 'wpabstracts'); ?></h6>
					</div>

					<div class="wpabstracts panel-body">

						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('New Review', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="new_review" type="text" value="<?php echo stripslashes($titles->new_review); ?>" />
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('Edit Review', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="edit_review" type="text" value="<?php echo stripslashes($titles->edit_review); ?>" />
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('Add Comments', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="add_comments" type="text" value="<?php echo stripslashes($titles->add_comments); ?>" />
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('Review Attachments', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="review_attachments" type="text" value="<?php echo stripslashes($titles->review_attachments); ?>" />
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('Use this option to upload a document to this review', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="reviewer_attachment_help" type="text" value="<?php echo stripslashes($titles->reviewer_attachment_help); ?>" />
						</div>

						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('Suggest Type', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="suggest_type" type="text" value="<?php echo stripslashes($titles->suggest_type); ?>" />
						</div>
						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('Suggest Status', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="suggest_status" type="text" value="<?php echo stripslashes($titles->suggest_status); ?>" />
						</div>
						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('Additional Information', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="additional_information" type="text" value="<?php echo stripslashes($titles->additional_information); ?>" />
						</div>
						<div class="wpabstracts form-group col-xs-12">
							<label class="wpabstracts control-label"><?php _e('Submitted', 'wpabstracts'); ?></label>
							<input class="wpabstracts form-control" name="submitted" type="text" value="<?php echo stripslashes($titles->submitted); ?>" />
						</div>

					</div>
				</div>
			</div>
		</div>
	</form>
</div>
