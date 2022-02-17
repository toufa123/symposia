<?php
defined('ABSPATH') or die("ERROR: You do not have permission to access this page");

function wpabstracts_get_abstracts($field, $value){
	global $wpdb;
	$wpdb->show_errors();
	$abs_tbl = $wpdb->prefix."wpabstracts_abstracts";
	$evt_tbl = $wpdb->prefix."wpabstracts_events";
	$query = "SELECT abs.*, evt.status as evt_status FROM {$abs_tbl} as abs";
	$query .=" LEFT JOIN {$evt_tbl} AS evt";
	$query .=" ON evt.event_id = abs.event WHERE evt.status = 1 AND abs.{$field} = %d";
	$sql = $wpdb->prepare($query, $value);
	return $wpdb->get_results($sql);
}

function wpabstracts_get_abstract($absId){
	global $wpdb;
	return $wpdb->get_row("SELECT * FROM {$wpdb->prefix}wpabstracts_abstracts WHERE `abstract_id`=" . $absId);
}

function wpabstracts_get_assigned_abstracts($userId){
	global $wpdb;
	$abstracts = array();
	$query = "SELECT abs_id FROM {$wpdb->prefix}wpabstracts_reviewers as revs ";
	$query .="LEFT JOIN {$wpdb->prefix}wpabstracts_abstracts AS abs ON revs.abs_id = abs.abstract_id ";
	$query .="LEFT JOIN {$wpdb->prefix}wpabstracts_events AS evt ON evt.event_id = abs.event WHERE evt.status = 1 AND revs.user_id = %d";
	$abs_ids = $wpdb->get_col($wpdb->prepare($query, $userId));
	$abs_list = implode(",", $abs_ids);
	if($abs_list){
		$sql = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}wpabstracts_abstracts AS abs WHERE abstract_id NOT IN
			(SELECT abstract_id FROM {$wpdb->prefix}wpabstracts_reviews AS reviews WHERE reviews.user_id = %d)
			AND abs.abstract_id IN ($abs_list)", $userId);
		$abstracts = $wpdb->get_results($sql);
	}
	return $abstracts;
}

function wpabstracts_get_reviews($field, $value){
	global $wpdb;
	$wpdb->show_errors();
	$reviews_tbl = $wpdb->prefix."wpabstracts_reviews";
	$abstracts_tbl = $wpdb->prefix."wpabstracts_abstracts";
	$events_tbl = $wpdb->prefix."wpabstracts_events";
	$query = "SELECT review.*, abs.event FROM {$reviews_tbl} as review";
	$query .=" JOIN {$abstracts_tbl} AS abs";
	$query .=" ON review.abstract_id = abs.abstract_id";
	$query .=" LEFT JOIN {$events_tbl} AS evt";
	$query .=" ON evt.event_id = abs.event WHERE evt.status = 1 AND review.{$field} = %d";
	$sql = $wpdb->prepare($query, $value);
	return $wpdb->get_results($sql);
}

function wpabstracts_get_review($id) {
	global $wpdb;
	$sql = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}wpabstracts_reviews WHERE `review_id` = %d", $id);
	return $wpdb->get_row($sql);
}

function wpabstracts_get_reviewers($abs_id){
	global $wpdb;
	$sql = $wpdb->prepare("SELECT user_id FROM {$wpdb->prefix}wpabstracts_reviewers WHERE `abs_id`=%d", $abs_id);
	$rev_ids = $wpdb->get_col($sql);
	$reviewers = sizeof($rev_ids) ? get_users(array('include' => $rev_ids)) : array();
	return $reviewers;
}

function wpabstracts_get_reviewer($abs_id, $user_id){
	global $wpdb;
	$sql = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}wpabstracts_reviewers WHERE `abs_id`=%d AND `user_id`=%d", $abs_id, $user_id);
	return $wpdb->get_results($sql);
}

function wpabstracts_get_attachments($abs_id){
	global $wpdb;
	$sql = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}wpabstracts_attachments WHERE `abstracts_id` = %d", $abs_id);
	return $wpdb->get_results($sql);
}

function wpabstracts_delete_attachment($id, $message){
	global $wpdb;
	$wpdb->query("DELETE FROM {$wpdb->prefix}wpabstracts_attachments WHERE `attachment_id` = " . $id);
	if($message){
		wpabstracts_show_message("Attachment ID ". $id . " was successfully deleted", 'alert-success');
	}
}

function wpabstracts_get_review_attachment($review_id) {
	global $wpdb;
	return $wpdb->get_row("SELECT * FROM {$wpdb->prefix}wpabstracts_review_attachments WHERE `review_id` = " . intval($review_id));
}

function wpabstracts_get_review_attachment_by_id($id) {
	global $wpdb;
	return $wpdb->get_row("SELECT * FROM {$wpdb->prefix}wpabstracts_review_attachments WHERE `att_id` = " . intval($id));
}

function wpabstracts_get_events(){
	global $wpdb;
	$events = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wpabstracts_events WHERE `status` = 1");
	return $events;
}

function wpabstracts_get_event($id){
	global $wpdb;
	$sql = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}wpabstracts_events WHERE `event_id` = %d", $id);
	$event = $wpdb->get_row($sql);
	return $event;
}

function wpabstracts_get_email_templates() {
	global $wpdb;
	$templates = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wpabstracts_emailtemplates");
	return $templates;
}

function wpabstracts_get_email_template($id) {
	global $wpdb;
	$sql = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}wpabstracts_emailtemplates WHERE `id`=%d", $id);
	return $wpdb->get_row($sql);
}

function wpabstracts_delete_email_template($id, $message) {
	global $wpdb;
	$templ_tbl = $wpdb->prefix . "wpabstracts_emailtemplates";
	$wpdb->delete($templ_tbl, array('id'=>intval($id)));
	if($message){
		wpabstracts_show_message("Template ID ". $id . " was successfully deleted", 'alert-success');
	}
}

function wpabstracts_get_statuses(){
	global $wpdb;
	$statuses = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wpabstracts_statuses order by `id` asc");
	return $statuses;
}

function wpabstracts_get_status($id){
	global $wpdb;
	$sql = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}wpabstracts_statuses WHERE `id` = %d", $id);
	$status = $wpdb->get_row($sql);
	return $status;
}

function wpabstracts_map_status_name($statuses, $status){
	$status_name = '---';
	foreach($statuses as $_status){
		if ($_status->id == $status){
			$status_name = $_status->name; break;
		}
	}
	return $status_name;
}

function wpabstracts_upsert_status($id, $statusData){
	global $wpdb;
	$status_tbl = $wpdb->prefix . "wpabstracts_statuses";
	if($id > 0){ // negative ids are new entries
		$wpdb->update($status_tbl, $statusData, array('id' => $id));
	}else{
		$wpdb->insert($status_tbl, $statusData);
	}
}

function wpabstracts_delete_statuses($ids) {
	global $wpdb;
	$status_tbl = $wpdb->prefix . "wpabstracts_statuses";
	foreach($ids as $id){
		$wpdb->delete($status_tbl , array('id'=>intval($id)));
	}
}

function wpabstracts_template_shortcodes() {
	$shortcodes = array(
		array('name'=>'Display Name', 'code'=>'{DISPLAY_NAME}', 'help'=>'The wordpress display name of the person receiving the email.'),
		array('name'=>'Username', 'code'=>'{USERNAME}', 'help'=>'The wordpress username of the person receiving the email.'),
		array('name'=>'Email', 'code'=>'{USER_EMAIL}', 'help'=>'The wordpress email of the person receiving the email.'),
		array('name'=>'Abstract ID', 'code'=>'{ABSTRACT_ID}', 'help'=>'The ID of the submission in context.'),
		array('name'=>'Abstract Title', 'code'=>'{ABSTRACT_TITLE}', 'help'=>'The title of the submission in context.'),
		array('name'=>'Abstract Keywords', 'code'=>'{ABSTRACT_KEYWORDS}', 'help'=>'The keywords supplied for the submission in context.'),
		array('name'=>'Abstract Topic', 'code'=>'{ABSTRACT_TOPIC}', 'help'=>'The topic selected for the submission in context.'),
		array('name'=>'Submitter Name ', 'code'=>'{SUBMITTER_NAME}', 'help'=>'The wordpress display name of the person who submitted the abstract.'),
		array('name'=>'Submitter Email ', 'code'=>'{SUBMITTER_EMAIL}', 'help'=>'The wordpress username of the person who submitted the abstract.'),
		array('name'=>'Event Name', 'code'=>'{EVENT_NAME}', 'help'=>'The event name of the submission.'),
		array('name'=>'Event Start Date', 'code'=>'{EVENT_START}', 'help'=>'The event start time of the submission.'),
		array('name'=>'Event End Date', 'code'=>'{EVENT_END}', 'help'=>'The event end time of the submission.'),
		array('name'=>'Presenter Pref', 'code'=>'{PRESENTER_PREF}', 'help'=>'The presenter preference selected for the submission.'),
		array('name'=>'Reviews Comments', 'code'=>'{REVIEW_COMMENTS}', 'help'=>'A list of all the review comments for this submission.'),
		array('name'=>'Site Name', 'code'=>'{SITE_NAME}', 'help'=>'Your wordpress site name.'),
		array('name'=>'Site URL', 'code'=>'{SITE_URL}', 'help'=>'Your wordpress site url.'),
		array('name'=>'One Week Later', 'code'=>'{ONE_WEEK_LATER}', 'help'=>'One week later from current time.'),
		array('name'=>'Two Weeks Later', 'code'=>'{TWO_WEEKS_LATER}', 'help'=>'Two weeks later from current time.'),
	);
	$shortcodes = apply_filters('wpabstracts_email_shortcodes', $shortcodes);
	return $shortcodes;
}

function wpabstracts_reg_email_shortcodes() {
	$shortcodes = array(
		array('name'=>'Display Name', 'code'=>'{DISPLAY_NAME}', 'help'=>'The wordpress display name of the person receiving the email.'),
		array('name'=>'Username', 'code'=>'{USERNAME}', 'help'=>'The wordpress username of the person receiving the email.'),
		array('name'=>'Dashboard URL', 'code'=>'{DASHBOARD_URL}', 'help'=>'A link to the frontend dashboard.'),
		array('name'=>'Activation Link', 'code'=>'{ACTIVATE_LINK}', 'help'=>'A link for the user to activate their account.'),
		array('name'=>'Site Name', 'code'=>'{SITE_NAME}', 'help'=>'Your wordpress site name.'),
		array('name'=>'Site URL', 'code'=>'{SITE_URL}', 'help'=>'Your wordpress site url.')
	);
	$shortcodes = apply_filters('wpabstracts_reg_email_shortcodes', $shortcodes);
	return $shortcodes;
}

function wpabstracts_manage_abstracts($id, $action){
	global $wpdb;
	if($_POST){
		$abs_title = sanitize_text_field($_POST["abs_title"]);
		$abs_text = isset($_POST["abstext"]) ? wp_kses_post($_POST["abstext"]) : '';
		$abs_event = isset($_POST["abs_event"]) ? intval($_POST["abs_event"]) : '';
		$abs_keywords = isset($_POST["abs_keywords"]) ? sanitize_text_field($_POST["abs_keywords"]) : '';
		$abs_topic = isset($_POST["abs_topic"]) ? sanitize_text_field($_POST["abs_topic"]) : '';

		if(isset($_POST["abs_author"]) && sizeof($_POST["abs_author"]) > 1) {
			foreach($_POST["abs_author"] as $key=>$author) {
				$author = sanitize_text_field($_POST["abs_author"][$key]);
				if(strlen($author) > 0){
					$abs_authors[] = $author;
				}
			}
			foreach($_POST["abs_author_email"] as $key=>$author_email) {
				$author_email = sanitize_email($_POST["abs_author_email"][$key]);
				if(strlen($author_email) > 0){
					$abs_authors_email[] = $author_email;
				}
			}
			foreach($_POST["abs_author_affiliation"] as $key=>$author_affiliation) {
				$author_affiliation = sanitize_text_field($_POST["abs_author_affiliation"][$key]);
				if(strlen($author_affiliation) > 0){
					$abs_authors_affiliation[] = $author_affiliation;
				}
			}
			$abs_authors = implode(' | ', $abs_authors);
			$abs_authors_email = implode(' | ', $abs_authors_email);
			$abs_authors_affiliation = implode(' | ', $abs_authors_affiliation);
		} else {
			$abs_authors= isset($_POST["abs_author"]) ? sanitize_text_field($_POST["abs_author"][0]) : '';
			$abs_authors_email  = isset($_POST["abs_author_email"]) ? sanitize_email($_POST["abs_author_email"][0]) : '';
			$abs_authors_affiliation = isset($_POST["abs_author_affiliation"]) ? ($_POST["abs_author_affiliation"][0]) : '';
		}

		if(isset($_POST["presenter"]) && sizeof($_POST["presenter"]) > 1) {
			foreach($_POST["presenter"] as $key=>$presenter) {
				$presenter = sanitize_text_field($_POST["presenter"][$key]);
				if(strlen($presenter) > 0){
					$presenters[] = $presenter;
				}
			}
			foreach($_POST["presenter_email"] as $key=>$presenter_email) {
				$presenter_email = sanitize_email($_POST["presenter_email"][$key]);
				if(strlen($presenter_email) > 0){
					$presenter_emails[] = $presenter_email;
				}
			}
			foreach($_POST["presenter_preference"] as $key=>$presenter_preference) {
				$presenter_preference = sanitize_text_field($_POST["presenter_preference"][$key]);
				if(strlen($presenter_preference) > 0){
					$presenter_preferences[] = $presenter_preference;
				}
			}
			$presenters = implode(' | ', $presenters);
			$presenter_emails = implode(' | ', $presenter_emails);
			$presenter_preferences = implode(' | ', $presenter_preferences);
		} else {
			$presenters = isset($_POST["presenter"]) ? sanitize_text_field($_POST["presenter"][0]) : '';
			$presenter_emails = isset($_POST["presenter_email"]) ? sanitize_email($_POST["presenter_email"][0]) : '';
			$presenter_preferences = isset($_POST["presenter_preference"]) ? ($_POST["presenter_preference"][0]) : '';
		}

		$default_status = get_option("wpabstracts_default_status");

		$prefilter_data = array(
			'title' => $abs_title,
			'text' => $abs_text,
			'event' => $abs_event,
			'keywords' => $abs_keywords,
			'topic' => $abs_topic,
			'status' => $default_status ? $default_status : 1
		);


		// these may be empty show author fields are off which would remove existing entries
		// so only update these when they are present.
		if(!empty($abs_authors)){
			$prefilter_data['author'] = $abs_authors;
			$prefilter_data['author_email'] = $abs_authors_email;
			$prefilter_data['author_affiliation'] = $abs_authors_affiliation;
		}

		// these may be empty show author fields are off which would remove existing entries
		// so only update these when they are present.
		if(!empty($presenters)){
			$prefilter_data['presenter'] = $presenters;
			$prefilter_data['presenter_email'] = $presenter_emails;
			$prefilter_data['presenter_preference'] = $presenter_preferences;
		}
		$data = apply_filters('wpabstracts_save_abstracts', $prefilter_data, $_POST);
	}

	switch($action){
		case 'insert':
		$data['submit_by'] = get_current_user_id();
		$data['submit_date'] = current_time('mysql');
		$wpdb->show_errors();
		$wpdb->insert($wpdb->prefix.'wpabstracts_abstracts', $data);
		$abstract_id = $wpdb->insert_id;
		return $abstract_id;
		case 'update':
		$data['modified_date'] = current_time('mysql');
		$where = array('abstract_id' => $id);
		$wpdb->show_errors();
		$wpdb->update($wpdb->prefix.'wpabstracts_abstracts', $data, $where);

		// user was changed
		if(isset($_POST['abs_user']) && $_POST['abs_user']){
			$abs_user = intval($_POST['abs_user']);
			$wpdb->query("UPDATE {$wpdb->prefix}wpabstracts_abstracts SET submit_by = " . $abs_user . " WHERE abstract_id = " . $id);
		}

		if(isset($_POST['abs_remove_attachments'])){
			$attachmentsIDs = (array) $_POST["abs_remove_attachments"];
			foreach($attachmentsIDs AS $attachID){
				$wpdb->query("DELETE FROM {$wpdb->prefix}wpabstracts_attachments WHERE attachment_id=".intval($attachID));
			}
		}
		break;
	}

}

function wpabstracts_upload_attachments($id){
	global $wpdb;
	if($_FILES) {
		do_action('wpabstracts_before_upload', $id);
		foreach($_FILES['attachments']['error'] as $key => $error) {
			if($error == 0) {
				$fileName = $_FILES['attachments']['name'][$key];
				$tmpName  = $_FILES['attachments']['tmp_name'][$key];
				$fileSize = $_FILES['attachments']['size'][$key];
				$fileType = $_FILES['attachments']['type'][$key];

				$fileExtension = explode('.', $fileName);
				$fileExt = strtolower($fileExtension[count($fileExtension)-1]);

				$approvedExtensions = explode(',', get_option('wpabstracts_permitted_attachments'));
				$maxFileSize = get_option('wpabstracts_max_attach_size');

				// checks file type is approved and size is within limit
				if(in_array($fileExt, $approvedExtensions) && $fileSize <= $maxFileSize) {

					$fileContent = file_get_contents($tmpName);

					$data = array(
						'abstracts_id' => $id,
						'filecontent' => $fileContent,
						'filename' => $fileName,
						'filetype' => $fileType,
						'filesize' => $fileSize,
						'format' => 1 // file_get_contents (1) vs rawurlencode (null) @since 1.6.0
					);
					$filtered_data = apply_filters('wpabstracts_add_attachment', $data, $id);
					$wpdb->show_errors();
					$wpdb->insert($wpdb->prefix."wpabstracts_attachments", $filtered_data);
				}
			}
		}
	}
}

function wpabstracts_upload_review_attachment($review_id, $aid) {
	global $wpdb;
	// upload review attachment
	if($_FILES && $_FILES['review_attachment']['error'] == 0) {
		$fileName = $_FILES['review_attachment']['name'];
		$tmpName  = $_FILES['review_attachment']['tmp_name'];
		$fileSize = $_FILES['review_attachment']['size'];
		$fileType = $_FILES['review_attachment']['type'];

		$fileExtension = explode('.', $fileName);
		$fileExt = strtolower($fileExtension[count($fileExtension)-1]);

		$approvedExtensions = explode(',', get_option('wpabstracts_permitted_attachments'));
		$maxFileSize = get_option('wpabstracts_max_attach_size');

		// checks file type is approved and size is within limit
		if(in_array($fileExt, $approvedExtensions) && $fileSize <= $maxFileSize) {

			$fileContent = file_get_contents($tmpName);

			$data = array(
				'review_id' => $review_id,
				'abs_id' => $aid,
				'filecontent' => $fileContent,
				'filename' => $fileName,
				'filetype' => $fileType,
				'filesize' => $fileSize,
				'format' => 1
			);
			$filtered_data = apply_filters('wpabstracts_add_review_attachment', $data);
			$wpdb->show_errors();
			$wpdb->insert($wpdb->prefix."wpabstracts_review_attachments", $filtered_data);
		}
	}
}

function wpabstracts_get_add_view($type, $id) {
	global $wpdb;
	$path = WPABSTRACTS_PLUGIN_DIR . 'html/wpabstracts_add' . $type . '.php';
	$templatePath = apply_filters('wpabstracts_template_path_addview', $path, $type);
	$html = null;
	switch($type){
		case 'Abstract':
		$events = wpabstracts_get_events();
		$event_id = $id ? $id : false;
		if(count($events) < 1){
			wpabstracts_show_message(__('There are no active events. Please ensure an event exists with a deadline later than yesterday.', 'wpabstracts'), 'alert-danger');
			return;
		}
		ob_start();
		include ( $templatePath);
		$html = ob_get_contents();
		ob_end_clean();
		break;
		case 'Review':
		$abs_id = intval($id);
		$abstract = wpabstracts_get_abstract($abs_id);
		$attachments = wpabstracts_get_attachments($abs_id);
		$event = wpabstracts_get_event($abstract->event);
		ob_start();
		include ($templatePath);
		$html = ob_get_contents();
		ob_end_clean();
		break;
		case 'Event' :
		ob_start();
		include ( $templatePath);
		$html = ob_get_contents();
		ob_end_clean();
		break;
		case 'EmailTemplate' :
		ob_start();
		include ( $templatePath);
		$html = ob_get_contents();
		ob_end_clean();
		break;
		case 'User' :
		ob_start();
		include ( $templatePath);
		$html = ob_get_contents();
		ob_end_clean();
		break;
		default :
		ob_start();
		include ( $templatePath);
		$html = ob_get_contents();
		ob_end_clean();
	}
	echo $html;
}

function wpabstracts_get_edit_view($type, $edit_id){
	global $wpdb;
	$id = intval($edit_id); // can never be too safe
	$html = $id ? null : false;
	$path = WPABSTRACTS_PLUGIN_DIR  . 'html/wpabstracts_edit' . $type . '.php';
	$templatePath = apply_filters('wpabstracts_template_path_editview', $path, $type);

	switch($type){
		case 'Abstract':
		$abstract = wpabstracts_get_abstract($id);
		if($abstract){
			$event = wpabstracts_get_event($abstract->event);
			$events = wpabstracts_get_events();
			$topics = array();
			if($event){
				$topics = explode('|', $event->topics);
			}
			$attachments = wpabstracts_get_attachments($abstract->abstract_id);
			ob_start();
			include ($templatePath);
			$html = ob_get_contents();
			ob_end_clean();
		}
		break;
		case 'Review':
		$review = wpabstracts_get_review($id);
		if($review){
			$abstract = wpabstracts_get_abstract($review->abstract_id);
			$attachments = wpabstracts_get_attachments($review->abstract_id);
			$event = wpabstracts_get_event($abstract->event); 
			ob_start();
			include ( $templatePath);
			$html = ob_get_contents();
			ob_end_clean();
		}
		break;
		case 'Event':
		$abs_event = wpabstracts_get_event($id);
		if($abs_event){
			$topics = explode("|", $abs_event->topics);
			ob_start();
			include ( $templatePath);
			$html = ob_get_contents();
			ob_end_clean();
		}
		break;
		case 'EmailTemplate':
		$template = wpabstracts_get_email_template($id);
		if($template){
			ob_start();
			include ( $templatePath);
			$html = ob_get_contents();
			ob_end_clean();
		}
		break;
		default :
		ob_start();
		include ($templatePath);
		$html = ob_get_contents();
		ob_end_clean();
	}
	return $html;
}

function wpabstracts_get_readonly_view($type, $id){
	global $wpdb;
	$id = intval($id); // can never be too safe
	$html = $id ? null : false;
	$path = WPABSTRACTS_PLUGIN_DIR  . 'html/wpabstracts_view' . $type . '.php';
	$templatePath = apply_filters('wpabstracts_template_path_readonly_view', $path, $type);
	switch($type){
		case 'Abstract':
		$abstract = wpabstracts_get_abstract($id);
		if($abstract){
			$event = wpabstracts_get_event($abstract->event);
			$attachments = wpabstracts_get_attachments($abstract->abstract_id);
			ob_start();
			include ($templatePath);
			$html = ob_get_contents();
			ob_end_clean();
		}
		break;
	}
	return $html;
}

function wpabstracts_redirect($tab){ ?>
	<script type="text/javascript">
	window.location = '<?php echo $tab; ?>';
	</script>
	<?php
}

function wpabstracts_display_admin_notice($message, $class) {
	add_action('admin_notices', function() use($message, $class){
		printf('<div class="notice notice-%1$s"><p>%2$s</p></div>', esc_attr($class), $message);
	});
}

function wpabstracts_show_message($message, $alert_class){
	echo "<br><div class='wpabstracts containter-fluid'>"
	. "<div class='wpabstracts alert ".$alert_class."' role='alert'>"
	. "<strong>$message</strong>"
	. "</div>"
	. "</div>";
}

function wpabstracts_show_alert($message, $alert_type){ ?>
	<script type="text/javascript">
	var type = '<?php echo $alert_type;?>';
	var message = '<?php echo $message;?>';
	switch(type){
		'success':
		alertify.success(message);
		break;
		'error':
		alertify.error(message);
		break;
	}
	</script>
	<?php
}

function wpabstracts_is_event_active($event_id){
	$event = wpabstracts_get_event($event_id);
	$current_date = current_time('Y-m-d');
	$date = date_create_from_format('Y-m-d', $event->deadline);
	$deadline = date_format($date, 'Y-m-d');
	return strtotime($deadline) >= strtotime($current_date);
}

function wpabstracts_user_submission_count() {
	$abstracts = wpabstracts_get_abstracts('submit_by', get_current_user_id());
	return count($abstracts);
}

function wpabstracts_get_dashboard(){
	$dashboard_id = get_option('wpabstracts_dashboard_id');
	$dashboard_url = $dashboard_id ? get_permalink($dashboard_id) : home_url();
	return $dashboard_url;
}

function wpabstracts_get_abstract_user($aid){
	global $wpdb;
	$wpdb->show_errors();
	$sql = $wpdb->prepare("SELECT submit_by FROM {$wpdb->prefix}wpabstracts_abstracts WHERE abstract_id=%d", $aid);
	$abstract = $wpdb->get_row($sql);
	$user = get_user_by( 'id', $abstract->submit_by );
	return $user;
}

function wpabstracts_get_login(){
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	ob_start();
	include_once ( WPABSTRACTS_PLUGIN_DIR . 'users/login.php');
	$login_form = ob_get_clean();
	echo apply_filters('wpabstracts_login_form', $login_form);
}

function wpabstracts_register_url(){
	$use_wpa_register = get_option('wpabstracts_enable_register');

	$args = array('post_type' => 'page', 'post_status' => 'publish');
	$pages = get_pages($args);
	$register_page_id = false;
	foreach($pages as $page){
		if(has_shortcode( $page->post_content, 'wpabstracts_register') ) {
			$register_page_id = $page->ID;
		}
	}
	$register_url = $register_page_id ? get_permalink($register_page_id) : wpabstracts_get_dashboard() ."?task=register";
	$link_html = "";
	if(get_option('users_can_register')) {
		$link_html = stripslashes(apply_filters('wpabstracts_title_filter', __("Don't have an account? ", 'wpabstracts'), 'no_account'));
		if(!$use_wpa_register){
			$link_html .= " <a href=" . wp_registration_url() .">" . apply_filters('wpabstracts_title_filter', __('Sign Up','wpabstracts'), 'create_account') . "</a>";
		}else{
			$link_html .= " <a href=" . $register_url . ">" . apply_filters('wpabstracts_title_filter', __('Create an Account','wpabstracts'), 'create_account') . "</a>";
		}
	}
	return apply_filters('wpabstracts_register_link', $link_html);
}

function wpabstracts_lostpassword_url(){
	$use_wpa_register = get_option('wpabstracts_enable_register');
	$lostPWlink = apply_filters('wpabstracts_title_filter', __('Forgot Password? ','wpabstracts'), 'forgot_password');
	if(!$use_wpa_register){
		$lostPWlink .= " <a href=". wp_lostpassword_url() . ">" . apply_filters('wpabstracts_title_filter',  __('Reset','wpabstracts'), 'reset_password') . "</a>";
	}else{
		$lostPWlink .= " <a href=" . wpabstracts_get_dashboard() . "?task=lostpassword" . ">" . apply_filters('wpabstracts_title_filter',  __('Reset','wpabstracts'), 'reset_password') . "</a>";
	}
	return apply_filters('wpabstracts_lostpassword_link', $lostPWlink);
}

add_filter('login_redirect', 'wpabstracts_login_redirect', 10, 3);
function wpabstracts_login_redirect($redirect_to, $request, $user){
	$enable_redirect = get_option('wpabstracts_login_redirect');
	if($enable_redirect){
		if (isset($user->roles) && is_array($user->roles)) {
			if (in_array('subscriber', $user->roles) || in_array('editor', $user->roles)) {
				$redirect_to =  wpabstracts_get_dashboard();
			}
		}
	}
    return $redirect_to;
}

add_action('wp_ajax_nopriv_wpa_login', 'wpabstracts_login_ajax');
function wpabstracts_login_ajax(){
	$user = wp_signon();
	$results = new stdClass();
    $message = null;
	if ( is_wp_error($user) ){
		foreach ( $user->get_error_codes() as $code ) {
			if($code == 'invalid_username') {
				$message = __('Invalid username or password. Please try again.', 'wpabstracts');
			}
			if($code == 'incorrect_password') {
				$message = __('Invalid username or password. Please try again.', 'wpabstracts');
			}
			if($code == 'invalid_captcha') {
				$message = __('Invalid security code. Please try again.', 'wpabstracts');
			}
		}
		if(!$message) {
			$message = $user->get_error_message();
		}
        $results->message = $message;
		$results->success = 0;
	} else {
		$settings = get_option('wpabstracts_user_settings');
		if(!$settings->ignore_activation) {
			$profile = wpabstracts_get_user($user->ID);
			if($profile->status == 0 && !is_super_admin($user->ID)){
				$results->success = 0;
				$results->message = __('Your account has not been activated. Please check your Inbox (including spam folder) for your activation link.', 'wpabstracts');
				wp_logout();
			} else {
				$results->success = 1;
				$results->message = __('Sign in successful.', 'wpabstracts');
				$results->redirect = wpabstracts_get_dashboard() . '?dashboard';
			}
		}
		else {
			$results->success = 1;
			$results->message = __('Sign in successful.', 'wpabstracts');
			$results->redirect = wpabstracts_get_dashboard() . '?dashboard';
		}
	}
	echo json_encode($results);
	die();
}

add_action('wp_ajax_nopriv_wpa_lostpassword', 'wpabstracts_lostpassword_ajax');
function wpabstracts_lostpassword_ajax(){

	$results = new stdClass();

	if (!isset($_POST['nonce']) || !wp_verify_nonce( $_POST['nonce'], 'wpabstracts_lost_password_action')){
		$results->success = 0;
		$results->message = __('Hmm, your nonce is not valid.', 'wpabstracts');
		echo json_encode($results);
		die();
	}

	$user_login = $_POST['user_login'];

	if(is_email($user_login)){
		$user_data = get_user_by('email', $user_login);
	}else{
		$user_data = get_user_by('login', $user_login);
	}

	if (empty($user_data)){
		$results->success = 0;
		$results->message .= __('There is no user registered with that username or email address.', 'wpabstracts');
	}else{
		$key = get_password_reset_key( $user_data );
		if (is_wp_error($key)){
			return $key;
		}
		$message = __('Hi,', 'wpabstracts') . "<br><br>";
		$message .= __('Someone requested a password reset for ', 'wpabstracts') . '<strong>' .$user_data->user_login . '</strong>' . ' at ' . get_option('blogname') . "<br><br>";
		$message .= __('If this was a mistake, simply ignore this email and nothing will happen.', 'wpabstracts') . "<br><br>";
		$message .= __('To reset your password, visit the following link:', 'wpabstracts') . "<br><br>";
		$message .= wpabstracts_get_dashboard() . "?task=resetpassword&key=$key&login=" . $user_data->user_login . "<br><br>";

		$title = __('Password Reset for ', 'wpabstracts') . get_option('blogname');
		$message = apply_filters( 'retrieve_password_message', $message, $key, $user_data->user_login, $user_data );

		add_filter('wp_mail_content_type', 'wpabstracts_set_html_content_type');
		if ( wp_mail( $user_data->user_email, wp_specialchars_decode( $title ), $message ) ){
			$results->success = 1;
			$results->message = __('Your request was successful. Please check your e-mail for more instructions.', 'wpabstracts');
		}else{
			$results->success = 0;
			$results->message = __('The e-mail could not be sent. Please contact support.', 'wpabstracts');
		}
		remove_filter( 'wp_mail_content_type', 'wpabstracts_set_html_content_type' );

	}
	echo json_encode($results);
	die();
}

add_action('wp_ajax_nopriv_wpa_resetpassword', 'wpabstracts_resetpassword_ajax');
function wpabstracts_resetpassword_ajax(){

	$results = new stdClass();

	if (!isset($_POST['nonce']) || !wp_verify_nonce( $_POST['nonce'], 'wpabstracts_reset_password_action')){
		$results->success = 0;
		$results->message = __('Hmm, your nonce is not valid.', 'wpabstracts');
		echo json_encode($results);
		die();
	}

	$password = $_POST['password'];
	$password_repeat = $_POST['password_repeat'];
	$user_key = $_POST['user_key'];
	$user_login = $_POST['user_login'];

	if( empty( $password ) || empty( $password_repeat ) ){
		$results->success = 0;
		$results->message = __('Password is required field.', 'wpabstracts');
		echo json_encode($results);
		die();
	}

	if($password != $password_repeat){
		$results->success = 0;
		$results->message = __('The passwords do not match.', 'wpabstracts');
		echo json_encode($results);
		die();
	}

	$user = check_password_reset_key($user_key, $user_login);

	if(is_wp_error($user)){
		$results->success = 0;
		$results->message = __('Invalid password reset key. Please request a new password reset.', 'wpabstracts');
	}else{
		reset_password($user, $password);
		$results->success = 1;
		$results->message = __('Your password has been reset.', 'wpabstracts');
	}
	echo json_encode($results);
	die();
}

add_action('wp_ajax_wpabs_save_regform', 'wpabstracts_user_save_regform');
function wpabstracts_user_save_regform(){
	$form_data = stripslashes($_POST['form_data']);
	update_option('wpabstracts_registration_form', $form_data);
	echo "Form Saved!";
	die();
}

add_action('wp_ajax_nopriv_wpabs_get_regform', 'wpabstracts_user_get_regform');
function wpabstracts_user_get_regform(){
	wpabstracts_pro_js();
	wpabstracts_pro_css();
	ob_start();
	include WPABSTRACTS_PLUGIN_DIR . 'users/register.php';
	echo ob_get_clean();
	die();
}

add_action('delete_user', 'wpabstracts_user_delete_action');
function wpabstracts_user_delete_action($user_id){
	global $wpdb;
	$wpdb->delete($wpdb->prefix."wpabstracts_users", array('id' => intval($user_id)));
}

// auto sign in users when auto-activation is on
//add_action('user_register', 'wpabstacts_user_after_registration', 10, 1);
function wpabstacts_user_after_registration($user_id) {
	$user_settings = get_option('wpabstracts_user_settings');
	$user = get_user_by('id', $user_id );
	if($user && $user_settings && $user_settings->auto_activate_on) {
		// auto sign in
		wp_set_current_user($user_id);
		wp_set_auth_cookie($user_id);
		do_action('wp_login', $user->user_login);
		$url = wpabstracts_get_dashboard();
		wp_redirect($url);
		exit();
	}
}

function wpabstracts_truncate_text($longText, $maxLength){
	$length = $maxLength ? $maxLength : 100;
	if (strlen($longText) <= $length) {
		$text = $longText; //do nothing
	} else {
		$text = substr($longText, 0, strpos(wordwrap($longText, $length), "\n")) . " ...";
	}
	return $text;
}

add_action('login_form', 'wpabstracts_show_captcha');
add_action('register_form', 'wpabstracts_show_captcha');
function wpabstracts_show_captcha() {
	if(get_option('wpabstracts_captcha')){
		if (extension_loaded('gd') && function_exists('gd_info')) {
			$captcha_word = wpabstracts_generate_random_word();
			$captcha_image = wpabstracts_generate_image($captcha_word);
			$captcha_hash = wpabstracts_generate_hash($captcha_word);
			?>
			<input type="hidden" id="captcha_hash" name="captcha_hash" value="<?php echo $captcha_hash; ?>" />
			<label for="captcha_input" class="wpabstracts control-label"><?php echo apply_filters('wpabstracts_title_filter',  __('Enter security code (required)','wpabstracts'), 'security_code');?></label>
			<div class="row">
				<div class="col-xs-8">
					<div class="wpabstracts input-group">
						<span class="input-group-addon"><i class="fas fa-shield-alt"></i></span>
						<input class="wpabstracts form-control" type="text" id="captcha_input" name="captcha_input" required>
					</div>
				</div>
				<div class="col-xs-4">
					<img src="data:image/png;base64,<?php echo base64_encode($captcha_image); ?>" alt="captcha" />
				</div>
			</div>
		<?php
		} else {
			wpabstracts_show_message(__('PHP GD library is NOT installed on your web server. Please install GD on your server or disable captcha from your settings.', 'wpabstracts'), 'alert-danger');
		}
	}
}

add_filter('wp_authenticate_user', 'wpabstracts_check_login_captcha', 10, 3);
function wpabstracts_check_login_captcha($user, $password) {
	if(get_option('wpabstracts_captcha')){
		$captcha_input = (isset($_POST['captcha_input'])) ? strtolower(sanitize_text_field($_POST['captcha_input'])) : false;
		$captcha_hash = (isset($_POST['captcha_input'])) ? $_POST['captcha_hash'] : 'zero';
		if (!$captcha_input || 0 != strcmp($captcha_hash, wpabstracts_generate_hash(str_replace(' ', '', $captcha_input)))){
			$user = new WP_Error( 'invalid_captcha', __('Invalid security code. Please try again.', "wpabstracts" ));
		}
	}
	return $user;
}

add_filter('registration_errors', 'wpabstracts_check_register_captcha', 10, 3);
function wpabstracts_check_register_captcha($errors, $user_login, $user_email) {
	if(get_option('wpabstracts_captcha')){
		$captcha_input = (isset($_POST['captcha_input'])) ? strtolower(sanitize_text_field($_POST['captcha_input'])) : false;
		$captcha_hash = (isset($_POST['captcha_input'])) ? $_POST['captcha_hash'] : 'zero';
		if (!$captcha_input || 0 != strcmp($captcha_hash, wpabstracts_generate_hash(str_replace(' ', '', $captcha_input)))){
			$errors->add('invalid_captcha', __('<strong>ERROR</strong>: Please enter a valid security code.', "wpabstracts" ));
		}
	}
	return $errors;
}

function wpabstracts_generate_random_word() {
	$chars = '0abc1de2fgh3ijk4lmn5opq6rst7uvw8xyz9';
	$word = '';
	$maxIndex = strlen( $chars ) - 1;
	for ( $i = 0; $i < 4; $i++ ) {
		$word .= $chars[mt_rand(0, $maxIndex)];
	}
	return $word;
}

function wpabstracts_generate_hash($word) {
	$hash = md5(get_option('wpabstracts_captcha_secret') . $word);
	return $hash;
}

function wpabstracts_generate_image($word) {
	if (!($im = imagecreatetruecolor(80, 30))) {
		return '';
	}
	$fonts = array('css/fonts/GenAI102.TTF', 'css/fonts/GenAR102.TTF', 'css/fonts/GenI102.TTF', 'css/fonts/GenR102.TTF');
	$bg = imagecolorallocate( $im, 255, 255, 255);
	$fg = imagecolorallocate( $im, 50, 50, 50);
	imagefill( $im, 0, 0, $bg );
	$x = 6 + mt_rand( -2, 2 );
	for ( $i = 0; $i < strlen( $word ); $i++ ) {
		$font = WPABSTRACTS_PLUGIN_DIR . $fonts[array_rand($fonts)];
		imagettftext( $im, $font_size = 16, mt_rand( -2, 2 ), $x, 18 + mt_rand( -2, 2 ), $fg, $font, $word[$i] );
		$x += 15;
	}
	ob_start();
	imagepng( $im );
	$theImage = ob_get_contents();
	ob_end_clean();
	imagedestroy( $im );
	return $theImage;
}

function wpabstracts_check_license(){

	$license = new stdClass();
	$license->result = '';
	$license->license_key = '';
	$license->status = '';
	$license->message = '';

	$license_key = get_option('wpabstracts_license_key');

	if(!$license_key){
		$license->result = 'error';
		$license->error_code = 0;
		$license->status = 'no_key';
		$license->message = 'No license key found';
	}else{
		// API query parameters
		$api_params = array(
			'slm_action' => 'slm_check',
			'secret_key' => WPABSTRACTS_SECRET_KEY,
			'license_key' => $license_key,
			'registered_domain' => $_SERVER['SERVER_NAME'],
			'item_reference' => urlencode(WPABSTRACTS_REFERENCE),
		);

		// Send query to the license manager server
		$query = esc_url_raw(add_query_arg($api_params, WPABSTRACTS_SERVER_URL));
		$response = wp_remote_get($query, array('timeout' => 20, 'sslverify' => true));
		// Check for error in the response
		if (is_wp_error($response)){
			$license->result = 'error';
			$license->error_code = 0;
			$license->status = 'http_failed';
			$license->message = 'Could you reach server.';
			wpabstracts_show_message(__('We could not perform your WP Abstracts license validation at this time.', 'wpabstracts'), 'alert-danger');
		}else {
			$license_data = json_decode(wp_remote_retrieve_body($response));
			$license = $license_data;
			$license->license_key = $license_key;
		}
	}
	return $license;
}

function wpabstracts_user_getview($view, $id){
	$page = WPABSTRACTS_PLUGIN_DIR . 'users/' . $view . '.php';
	if(is_file($page)){
		ob_start();
		$user_id = ($id) ? $id : get_current_user_id();
		include($page);
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}else{
		wpabstracts_show_message(__('Sorry, the tab you selected does not exist.', 'wpabstracts'), 'alert-danger');
	}
}

function wpabstracts_get_users(){
	global $wpdb;
	$users = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix."wpabstracts_users");
	return $users;
}

function wpabstracts_get_user($user_id){
	global $wpdb;
	$user = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix."wpabstracts_users WHERE user_id = " . $user_id);
	return $user;
}

function wpabstracts_user_sync(){
	global $wpdb;
	$users = get_users();
	foreach($users as $user){
		$userExist = $wpdb->get_var('SELECT COUNT(*) FROM ' . $wpdb->prefix.'wpabstracts_users WHERE user_id = ' . $user->ID);
		if(!$userExist){
			$data = array('user_id' => $user->ID, 'status' => 1);
			$wpdb->insert($wpdb->prefix.'wpabstracts_users', $data);
		}
	}
}

function wpabstracts_activate_user($userId, $message) {
	global $wpdb;
	$data = array('status' => 1);
	$where = array('user_id' => $userId);
	$wpdb->update($wpdb->prefix."wpabstracts_users", $data, $where);
	if($message){
		wpabstracts_show_message(__('User Id ' . $userId . ' was successfully activated.', 'wpabstracts'), 'alert-success');
	}
}

function wpabstracts_user_delete($id, $message){
	global $wpdb;
	if(wp_delete_user($id)){
		$wpdb->delete($wpdb->prefix."wpabstracts_users", array('user_id' => intval($id)));
		if($message){
			wpabstracts_show_message(__('Successfully deleted user ID ' . $id . '.', 'wpabstracts'), 'alert-success');
		}
	}
}

function wpabstracts_get_preferences(){
	$prefs = get_option('wpabstracts_presenter_preference');
	return explode(',', $prefs);
}

function wpabstracts_get_admin_emails(){
	$emails = get_option('wpabstracts_admin_emails');
	if($emails){
		return explode(',', $emails);
	}else {
		$users = get_users( array('role'=>'administrator', 'fields' => array('user_email')));
		foreach ($users as $user){
			$emails[] = $user->user_email;
		}    
		return $emails;
	}
}

function wpabstracts_get_custom_titles() {

	$customTitles = new stdClass();
	// submission
	$customTitles->new_abstract = __('New Abstract', 'wpabstracts');
	$customTitles->edit_abstract = __('Edit Abstract', 'wpabstracts');
	$customTitles->abstract_information = __('Abstract Information', 'wpabstracts');
	$customTitles->enter_title = __('Enter Title', 'wpabstracts');
	$customTitles->keywords = __('Keywords', 'wpabstracts');
	$customTitles->no_keywords = __('No keywords found', 'wpabstracts');
	$customTitles->enter_keywords = __('Enter comma separated keywords', 'wpabstracts');
	$customTitles->attachments = __('Attachments', 'wpabstracts');
	$customTitles->no_attachments = __('No Attachments submitted', 'wpabstracts');
	$customTitles->attachment_help = __('Use this form to upload your images, photos or tables.', 'wpabstracts');
	$customTitles->supported_formats = __('Supported formats', 'wpabstracts');
	$customTitles->max_attachment_size = __('Maximum attachment size', 'wpabstracts');
	$customTitles->terms_conditions = __('Terms and Conditions', 'wpabstracts');
	$customTitles->event_information = __('Event Information', 'wpabstracts');
	$customTitles->event = __('Event', 'wpabstracts');
	$customTitles->select_event = __('Select an Event', 'wpabstracts');
	$customTitles->topic = __('Topic', 'wpabstracts');
	$customTitles->select_topic = __('Select a Topic', 'wpabstracts');
	$customTitles->author_information = __('Author Information', 'wpabstracts');
	$customTitles->author_name = __('Author Name', 'wpabstracts');
	$customTitles->author_email = __('Author Email', 'wpabstracts');
	$customTitles->author_affiliation = __('Author Affiliation', 'wpabstracts');
	$customTitles->presenter_information = __('Presenter Information', 'wpabstracts');
	$customTitles->presenter_name = __('Presenter Name', 'wpabstracts');
	$customTitles->presenter_email = __('Presenter Email', 'wpabstracts');
	$customTitles->presenter_preference = __('Presenter Preference', 'wpabstracts');
	// review
	$customTitles->new_review = __('New Review', 'wpabstracts');
	$customTitles->edit_review = __('Edit Review', 'wpabstracts');
	$customTitles->add_comments = __('Add Comments', 'wpabstracts');
	$customTitles->review_attachments = __('Review Attachments', 'wpabstracts');
	$customTitles->reviewer_attachment_help = __('Use this option to upload a document to this review.', 'wpabstracts');
	$customTitles->suggest_type = __('Suggest Type', 'wpabstracts');
	$customTitles->suggest_status = __('Suggest Status', 'wpabstracts');
	$customTitles->additional_information = __('Additional Information', 'wpabstracts');
	$customTitles->submitted = __('Submitted', 'wpabstracts');
	// login
	$customTitles->sign_in_help = __('Please sign in for your conference participation', 'wpabstracts');
	$customTitles->sign_in = __('Sign In', 'wpabstracts');
	$customTitles->login_btn = __('Login', 'wpabstracts');
	$customTitles->remember_me = __('Remember Me', 'wpabstracts');
	$customTitles->forgot_password = __('Forgot Password', 'wpabstracts');
	$customTitles->get_password = __('Get New Password', 'wpabstracts');
	$customTitles->reset_password = __('Reset', 'wpabstracts');
	$customTitles->create_account = __('Create an Account', 'wpabstracts');
	$customTitles->no_account = __("Don't have an account? ", 'wpabstracts');
	$customTitles->security_code = __('Enter security code (required)', 'wpabstracts');
	// register 
	$customTitles->login_information = __('Login Information', 'wpabstracts');
	$customTitles->account_information = __('Account Information', 'wpabstracts');
	// dashboard
	$customTitles->dashboard = __('Dashboard', 'wpabstracts');
	$customTitles->welcome_back =  __('Welcome back', 'wpabstracts');
	$customTitles->logout_btn = __('Logout', 'wpabstracts');
	$customTitles->my_profile = __('My Profile', 'wpabstracts');
	$customTitles->my_abstracts = __('My Abstracts', 'wpabstracts');
	$customTitles->assigned_abstracts = __('Assigned Abstracts', 'wpabstracts');
	$customTitles->my_reviews = __('My Reviews', 'wpabstracts');
	$customTitles->edit_profile = __('Edit Profile', 'wpabstracts');
	$customTitles->profile_information = __('Profile Information', 'wpabstracts');
	$customTitles->review_document = __('Document', 'wpabstracts');
	return $customTitles;
}

function wpabstracts_form_field_record($form_fields, $field_name){
	foreach($form_fields as $k => $field) {
		if(array_key_exists('name', $field) && $field->name == $field_name){
			return $field;
		}
	}
}

function wpabstracts_sync_wpfields($user_data, $user_id) {
	$form_fields = json_decode(get_option('wpabstracts_registration_form'));
	// sync user meta
	foreach($user_data as $field_name => $field_value) {
		$user_field = wpabstracts_form_field_record($form_fields, $field_name);
		if($user_field && array_key_exists('wpSync', $user_field)){
			update_user_meta($user_id, $user_field->wpSync, $field_value);
		}
	}
	// sync display name when its a single field
	$user_settings = get_option('wpabstracts_user_settings');
	if(array_key_exists($user_settings->display_name, $user_data)){
		wp_update_user( array( 'ID' => $user_id, 'display_name' => $user_data[$user_settings->display_name]));
	}
	// sync display name when its combined
	if($user_settings->display_name == "first_last") {
		wp_update_user( array( 'ID' => $user_id, 'display_name' => $user_data['firstname'] . ' ' . $user_data['lastname']));
	} 
	if($user_settings->display_name == "last_first") {
		wp_update_user( array( 'ID' => $user_id, 'display_name' => $user_data['lastname'] . ' ' . $user_data['firstname']));
	}
}

function wpabstracts_user_can_manage($task, $id) {
	$can_manage = false;
	$user = wp_get_current_user();
	switch($task) {
		case 'edit_abstract':
		case 'view_abstract':
		case 'delete_abstract':
		$abstract = wpabstracts_get_abstract($id);
		$is_reviewer = wpabstracts_get_reviewer($id, $user->ID);
		$is_owner = $abstract && $abstract->submit_by == $user->ID;
		$can_manage = $is_owner || (count($is_reviewer) && get_option('wpabstracts_reviewer_edit'));
		break;
		case 'new_review': // check if user is assigned to this abstract
		$is_reviewer = wpabstracts_get_reviewer($id, $user->ID);
		$can_manage = count($is_reviewer);
		break;
		case 'edit_review': // check if user is assigned to abstract and review is by this reviewer
		case 'delete_review':
		$review = wpabstracts_get_review($id);
		if($review){
			$is_reviewer = wpabstracts_get_reviewer($review->abstract_id, $user->ID);
			$can_manage = count($is_reviewer) && $review->user_id == $user->ID;
		}
		break;
		case 'download':
		$review_attachment = wpabstracts_get_review_attachment_by_id($id);
		if($review_attachment){
			$abstract = wpabstracts_get_abstract($review_attachment->abs_id);
			$is_reviewer = wpabstracts_get_reviewer($id, $user->ID);
			$is_owner = $abstract && $abstract->submit_by == $user->ID;
			$can_manage = $is_owner || (count($is_reviewer) && get_option('wpabstracts_reviewer_edit'));
		}
		break;
	}

	return $can_manage;
}

add_action('wp_ajax_export_event_stats', 'wpabstracts_export_event_ajax');
function wpabstracts_export_event_ajax() {
	global $wpdb;
	$event_id = $_POST["event_id"];
	$statuses = wpabstracts_get_statuses();
	
	// abstracts stats
	$abstract_stats = array();
	$abstractCountSql = "SELECT COUNT(*) FROM {$wpdb->prefix}wpabstracts_abstracts as abs WHERE abs.event = {$event_id}";
	foreach($statuses as $status) {
		$sql = $abstractCountSql . " AND abs.status = {$status->id}";
		$status_stat = array(
			'id' => $status->id,
			'name' => $status->name,
			'count' => $wpdb->get_var($sql)
		);
		array_push($abstract_stats, $status_stat);
	}

	// review stats
	$review_stats = array();
	$reviewCountSql = "SELECT COUNT(*) FROM {$wpdb->prefix}wpabstracts_reviews as review";
	$reviewCountSql .= " LEFT JOIN {$wpdb->prefix}wpabstracts_abstracts as abs ON abs.abstract_id = review.abstract_id";
	$reviewCountSql .= " WHERE abs.event = {$event_id}";

	foreach($statuses as $status) {
		$sql = $reviewCountSql . " AND review.status = {$status->id}";
		$status_stat = array(
			'id' => $status->id,
			'name' => $status->name,
			'count' => $wpdb->get_var($sql)
		);
		array_push($review_stats, $status_stat);
	}
	
	// build results
	$results = array(
		'abstracts' => array(
			'results' => $abstract_stats,
			'total' => $wpdb->get_var($abstractCountSql)
		),
		'reviews' => array(
			'results' => $review_stats,
			'total' => $wpdb->get_var($reviewCountSql)
		)
	);
	echo json_encode($results);
	die();
}

function wpabstracts_generate_html($abstract) {
	$event = wpabstracts_get_event($abstract->event);
	ob_start();
	?>
	<div class="wpabstracts pdf">
		<div class="wpabstracts pdf_header">
			<div style="font-size: 24px;"><?php echo stripslashes($abstract->title); ?></div>
		</div>
		<?php if(get_option('wpabstracts_show_author')){?>
			<div class="wpabstracts pdf_author">
				<?php $author = ($abstract->author) ? "by " . $abstract->author . ' | ' . $abstract->author_affiliation : " - No author found -"; ?>
				<div style="font-size: 14px;"><span ><?php echo stripslashes($author); ?></span></div>
			</div>
		<?php } ?>
		<div class="wpabstracts pdf_headerbar" style="text-align: right; font-style: italic;">
			<div class="pdf_header_row"><?php echo apply_filters('wpabstracts_title_filter', __('Abstract ID','wpabstracts'), 'abstract_id'); ?>: <?php echo $abstract->abstract_id; ?></div>
			<div class="pdf_header_row"><?php echo apply_filters('wpabstracts_title_filter', __('Submitted','wpabstracts'), 'submitted'); ?>: <?php echo date_i18n(get_option('date_format'), strtotime($abstract->submit_date)); ?></div>
			<div class="pdf_header_row"><?php echo apply_filters('wpabstracts_title_filter', __('Event','wpabstracts'), 'event'); ?>: <?php echo $event->name; ?></div>
			<div class="pdf_header_row"><?php echo apply_filters('wpabstracts_title_filter', __('Topic','wpabstracts'), 'topic'); ?>: <?php echo stripslashes($abstract->topic); ?></div>
			<?php if(get_option('wpabstracts_show_keywords')){ ?>
				<?php $keywords = array_map('trim', explode(',', stripslashes($abstract->keywords)));?>
				<?php sort($keywords);?>
				<div class="pdf_header_row"><?php echo apply_filters('wpabstracts_title_filter', __('Keywords','wpabstracts'), 'keywords'); ?>: <?php echo implode(", ", $keywords); ?></div>
			<?php }?>
		</div>
		<div class="wpabstracts pdf_body">
			<div class="pdf_text" style="text-align: justify;">
				<?php echo wpautop(stripslashes($abstract->text)); ?>
			</div>
		</div>
	</div>

	<?php
	$html = ob_get_contents();
	ob_end_clean();
	return $html;
}