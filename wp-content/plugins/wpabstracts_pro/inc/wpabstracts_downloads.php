<?php
defined('ABSPATH') or die("ERROR: You do not have permission to access this page");

if(is_user_logged_in() && is_super_admin()){
	do_action('wpabstracts_before_download');
	if (isset($_GET['task']) && ($_GET['task']) == 'download') {
		if(isset($_GET['type']) && ($_GET['type'])){
			$id = isset($_GET['id']) ? intval($_GET['id']) : 1;
			$statusId = isset($_GET['status']) ? intval($_GET['status']) : 1;
			$type = isset($_GET['type']) ? $_GET['type'] : 'abstracts';
			$event = isset($_GET['event']) ? intval($_GET['event']) : 0;
			switch($type){
				case 'attachment':
				if(has_action('wpabstracts_download_attachment')){
					do_action('wpabstracts_download_attachment', $id);
				}else{
					wpabstracts_download_attachment($id);
				}
				break;
				case 'review_attachment':
				if(has_action('wpabstracts_download_review_attachment')){
					do_action('wpabstracts_download_review_attachment', $id);
				}else{
					wpabstracts_download_review_attachment($id);
				}
				break;
				case 'pdf':
				if(has_action('wpabstracts_download_pdf')){
					do_action('wpabstracts_download_pdf', $id);
				}else{
					wpabstracts_download_pdf($id);
				}
				break;
				case 'abstracts':
				if(has_action('wpabstracts_download_report')){
					do_action('wpabstracts_download_report', 'abstracts', $statusId, $event);
				}else{
					wpabstracts_downloadReport('abstracts', $statusId, $event);
				}
				break;
				case 'reviews':
				if(has_action('wpabstracts_download_report')){
					do_action('wpabstracts_download_report', 'reviews', $statusId, $event);
				}else{
					wpabstracts_downloadReport('reviews', $statusId, $event);
				}
				break;
				case 'users':
				if(has_action('wpabstracts_download_report')){
					do_action('wpabstracts_download_report', 'users');
				}else{
					wpabstracts_downloadReport('users', $id);
				}
				break;
				case 'zip':
				wpabstracts_downloadZip(); 
				break;
			}
		}
	}
	if( (isset($_GET['action']) &&  $_GET['action'] == 'download') || isset($_GET['action2']) && $_GET['action2'] == 'download'){
		wpabstracts_download_attachments($_GET['attachment']);
	}
}else if(is_user_logged_in()){
	do_action('wpabstracts_before_download');
	if (isset($_GET['task']) && ($_GET['task']) == 'download') {
		if(isset($_GET['type']) && ($_GET['type'])){
			$type = $_GET['type'];
			$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
			switch($type){
				case 'attachment':
				if(has_action('wpabstracts_download_attachment')){
					do_action('wpabstracts_download_attachment', $id);
				}else{
					wpabstracts_download_attachment($id);
				}
				break;
				case 'review_attachment':
				if(has_action('wpabstracts_download_review_attachment')){
					do_action('wpabstracts_download_review_attachment', $id);
				}else{
					wpabstracts_download_review_attachment($id);
				}
				break;
			}
		}
	}
}else{
	defined('ABSPATH') or die("ERROR: You do not have permission to access this resource.");
}

function wpabstracts_download_attachment($id) {
	global $wpdb;
	$file = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "wpabstracts_attachments WHERE attachment_id=" . $id);
	$content = $file->format ? $file->filecontent : rawurldecode($file->filecontent);
	header("Cache-Control: no-cache, must-revalidate");
	header("Content-length: " . $file->filesize);
	header("Content-type: " . $file->filetype);
	header("Content-Disposition: attachment; filename=\"$file->filename\"");
	echo $content;
	exit(0);
}

function wpabstracts_download_review_attachment($id) {
	global $wpdb;
	$file = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "wpabstracts_review_attachments WHERE att_id=" . $id);
	$content = $file->format ? $file->filecontent : rawurldecode($file->filecontent);
	header("Cache-Control: no-cache, must-revalidate");
	header("Content-length: " . $file->filesize);
	header("Content-type: " . $file->filetype);
	header("Content-Disposition: attachment; filename=\"$file->filename\"");
	echo $content;
	exit(0);
}

function wpabstracts_downloadReport($reportType, $statusId, $eventId = 0) {

	global $wpdb;
	$reportData = null;
	$statuses = wpabstracts_get_statuses();

	if ($reportType == "abstracts") {

		$report_header = array(
			__("Abstract ID", 'wpabstracts'),
			__("Title", 'wpabstracts'),
			__("Description", 'wpabstracts'),
			__("Event Name", 'wpabstracts'),
			__("Topic", 'wpabstracts'),
			__("Keywords", 'wpabstracts'),
			__("Status", 'wpabstracts'),
			__("Author Name", 'wpabstracts'),
			__("Author Email", 'wpabstracts'),
			__("Author Affiliation", 'wpabstracts'),
			__("Presenter Name", 'wpabstracts'),
			__("Presenter Email", 'wpabstracts'),
			__("Presenter Preference", 'wpabstracts'),
			__("User ID", 'wpabstracts'),
			__("User", 'wpabstracts'),
			__("User Email", 'wpabstracts'),
			__("Reviewer ID", 'wpabstracts'),
			__("Reviewers", 'wpabstracts'),
			__("Date Submitted", 'wpabstracts')
		);

		$status_case = "CASE";
		foreach ($statuses as $status) {
			$status_case .= " WHEN abs.status = $status->id THEN '" . $status->name . "'";
		}
		$status_case .= " END as status";

		$selectSQL = "SELECT abs.abstract_id, abs.title, abs.text, event.name, abs.topic, abs.keywords, $status_case, abs.author, abs.author_email, 
		abs.author_affiliation, abs.presenter, abs.presenter_email, abs.presenter_preference, user.ID, user.display_name, 
		user.user_email, GROUP_CONCAT(reviewers.user_id), GROUP_CONCAT(reviewer.display_name), abs.submit_date
		FROM " . $wpdb->prefix . "wpabstracts_abstracts as abs
		LEFT JOIN ". $wpdb->prefix . "wpabstracts_events as event ON abs.event = event.event_id
		LEFT JOIN ". $wpdb->base_prefix . "users as user ON user.ID = abs.submit_by
		LEFT JOIN ". $wpdb->prefix . "wpabstracts_reviewers as reviewers ON reviewers.abs_id = abs.abstract_id
		LEFT JOIN ". $wpdb->base_prefix . "users as reviewer ON reviewer.ID = reviewers.user_id 
		WHERE event.status = 1 AND event.event_id = " . $eventId;

		if($statusId > 0){
			$reportName = __("Abstracts", 'wpabstracts') . "-" . wpabstracts_map_status_name($statuses, $statusId);
			$reportData = $wpdb->get_results($selectSQL . " AND abs.status = " . intval($statusId) . " group by abs.abstract_id", ARRAY_N);
		} else{
			$reportName = __("All-Abstracts", 'wpabstracts');
			$reportData = $wpdb->get_results($selectSQL . " group by abs.abstract_id", ARRAY_N);
		}

	} else if ($reportType == "reviews") {

		$report_header = array(
			__("Review ID", 'wpabstracts'),
			__("Abstract ID", 'wpabstracts'),
			__("Abstract Title", 'wpabstracts'),
			__("Event Name", 'wpabstracts'),
			__("Topic", 'wpabstracts'),
			__("User ID", 'wpabstracts'),
			__("User Name", 'wpabstracts'),
			__("User Email", 'wpabstracts'),
			__("Relevance", 'wpabstracts'),
			__("Quality", 'wpabstracts'),
			__("Comments", 'wpabstracts'),
			__("Recommendation", 'wpabstracts'),
			__("Status", 'wpabstracts'),
			__("Reviewer ID", 'wpabstracts'),
			__("Reviewer Name", 'wpabstracts'),
			__("Reviewer Email", 'wpabstracts'),
			__("Review Date", 'wpabstracts')
		);

		$status_case = "CASE";
		foreach ($statuses as $status) {
			$status_case .= " WHEN review.status = $status->id THEN '" . $status->name . "'";
		}
		$status_case .= " END as status";

		$selectSQL = "SELECT review.review_id, review.abstract_id, abs.title, event.name, abs.topic, user.ID as u_id, 
		user.display_name as u_name, user.user_email as u_email, review.relevance, review.quality, review.comments, review.recommendation, 
		$status_case, reviewer.ID, reviewer.display_name, reviewer.user_email, review.review_date 
		FROM " . $wpdb->prefix . "wpabstracts_reviews as review 
		LEFT JOIN ". $wpdb->prefix . "wpabstracts_abstracts as abs ON abs.abstract_id = review.abstract_id 
		LEFT JOIN ". $wpdb->prefix . "wpabstracts_events as event ON abs.event = event.event_id
		LEFT JOIN ". $wpdb->base_prefix . "users as user ON abs.submit_by = user.ID
		LEFT JOIN ". $wpdb->base_prefix . "users as reviewer ON review.user_id = reviewer.ID
		WHERE abs.event = " . $eventId;

		if($statusId > 0){
			$reportName = __("Reviews", 'wpabstracts') . "-" . wpabstracts_map_status_name($statuses, $statusId);
			$reportData = $wpdb->get_results($selectSQL . " AND review.status = " . intval($statusId) . " group by review.review_id", ARRAY_N);
		} else{
			$reportName = __("All-Reviews", 'wpabstracts');
			$reportData = $wpdb->get_results($selectSQL . " group by review.review_id", ARRAY_N);
		}

	} else if($reportType == "users") {

		function _getUserRow($userId, $userEmail, $form_fields, $user_data) {
			$user_row = array($userId, $userEmail); // mandatory
			foreach($form_fields as $form_field){
				if(isset($form_field->name)) {
					if(is_array($user_data) && array_key_exists($form_field->name, $user_data)){
						array_push($user_row, $user_data[$form_field->name]);
					}else{
						array_push($user_row, '');
					}
				}
			}
			return $user_row;
		}

		$reportName = __("All Users", 'wpabstracts');
		// build report header.
		$form_fields = json_decode(get_option('wpabstracts_registration_form'));
		$report_header = array('User Id', 'User Email');  // mandatory
		foreach($form_fields as $form_field){
			if(isset($form_field->name)){
				array_push($report_header, $form_field->label);
			}
		}

		// build user data
		$reportData = array();
		$wpa_users = $wpdb->prefix."wpabstracts_users";
		$wp_users = $wpdb->prefix."users";
		$query = "SELECT wpusers.ID, wpusers.user_email, wpausers.user_id, wpausers.data ";
		$query .= "FROM $wpa_users AS wpausers ";
		$query .= "JOIN $wp_users AS wpusers ON wpausers.user_id = wpusers.ID";
		$users = $wpdb->get_results($query);

		foreach ($users as $user) {
			$user_data = unserialize($user->data);
			array_push($reportData, _getUserRow($user->ID, $user->user_email, $form_fields, $user_data));
		}
	}

	$reportName = $reportName . ".csv";
	header("Cache-Control: no-cache, must-revalidate");
	header("Content-Type: text/csv");
	header("Content-Disposition: attachment; filename=\"$reportName\"");
	ob_start();
	$file_report = fopen('php://output', 'w');
	fputcsv($file_report, array_values($report_header), ",");
	foreach ($reportData AS $data) {
		fputcsv($file_report, array_values(stripslashes_deep($data)), ",");
	}
	fclose($file_report);
	$report = ob_get_contents();
	ob_end_clean();
	echo $report;
	exit(0);
}

function wpabstracts_downloadZip(){
	global $wpdb;
	$attachments = $wpdb->get_col("SELECT `attachment_id` FROM {$wpdb->prefix}wpabstracts_attachments");
	$zip = new ZipArchive();
	$tempFiles = array();
	$zip_name = 'wpabstracts_attachments_all.zip';

	// ensure temp directory email_exists
	$tempPath = WPABSTRACTS_PLUGIN_DIR . 'temp';
	if(!is_dir($tempPath)){
		mkdir($tempPath, 0755);
	}

	if ($zip->open($zip_name, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
		wp_die("An error occurred creating your ZIP file. Maybe your server lacks support for this feature or it has no space for temp files.");
	}

	foreach($attachments as $attachments_id){
		$sql = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}wpabstracts_attachments WHERE `attachment_id`=%d", $attachments_id);
        $attachment = $wpdb->get_row($sql);
		$content = $attachment->format ? $attachment->filecontent : rawurldecode($attachment->filecontent);
		$filename = "ID_". $attachment->abstracts_id . "_" . $attachment->attachment_id . "_" . $attachment->filename;
		$file = $tempPath ."/". $filename;

		if(file_put_contents($file, $content)){
			$tempFiles[] = $file;
			$zip->addFile($file, $filename);
		}
	}

	$zip->close();

	foreach ($tempFiles as $file){
		unlink($file);
	}

	header('Content-Type: application/zip');
	header("Content-Disposition: attachment; filename=\"$zip_name\"");
	header('Content-Length: ' . filesize($zip_name));
	header("Location: " . $zip_name);
	exit(0);
}

function wpabstracts_download_attachments($atts){
	global $wpdb;
	if(is_array($atts)){
		$sql = esc_sql("SELECT * FROM {$wpdb->prefix}wpabstracts_attachments WHERE attachment_id IN (" . implode(',', $atts) . ")");
		$attachments = $wpdb->get_results($sql);
	}
	if(!$attachments){
		return false;
	}
	$zip = new ZipArchive();
	$tempFiles = array();
	$zip_name = 'wpabstracts_attachments_bulk_' . time() . '.zip';

	// ensure temp directory email_exists
	$tempPath = WPABSTRACTS_PLUGIN_DIR . 'temp';
	if(!is_dir($tempPath)){
		mkdir($tempPath, 0755);
	}

	error_log($tempPath); 

	if ($zip->open($zip_name, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
		wp_die("An error occurred creating your ZIP file. Maybe your server lacks support for this feature or it has no space for temp files.");
	}

	foreach($attachments as $attachment){
		$content = $attachment->format ? $attachment->filecontent : rawurldecode($attachment->filecontent);
		$filename = "ID_". $attachment->abstracts_id . "_" . $attachment->attachment_id . "_" . $attachment->filename;
		$file = $tempPath ."/". $filename;

		if(file_put_contents($file, $content)){
			$tempFiles[] = $file;
			$zip->addFile($file, $filename);
		}
	}

	$zip->close();

	foreach ($tempFiles as $file){
		unlink($file);
	}

	header('Content-Type: application/zip');
	header("Content-Disposition: attachment; filename=\"$zip_name\"");
	header('Content-Length: ' . filesize($zip_name));
	header("Location: " . $zip_name);
	exit(0);
}

function wpabstracts_download_pdf($abs_id,  $is_email = false) {
	global $wpdb;
	$abstract = wpabstracts_get_abstract($abs_id);
	$event = wpabstracts_get_event($abstract->event);
	require_once(WPABSTRACTS_PLUGIN_DIR . "inc/mpdf/autoload.php");
	$styleUrl = WPABSTRACTS_PLUGIN_DIR . 'css/pdf.css';
	$mpdf = new \Mpdf\Mpdf();
	$stylesheet = file_get_contents($styleUrl);
	$mpdf->WriteHTML($stylesheet, 1);
	$header = __("Abstract ID", 'wpabstracts') . ": " . $abstract->abstract_id . " for " . get_option("blogname") . " (" . __("Auto-Generated", 'wpabstracts') . " " . date_i18n(get_option('date_format') . ' ' . get_option('time_format')) . ")";
	$mpdf->SetHeader($header);
	$filename = stripslashes($abstract->title) . " (ID_" . $abstract->abstract_id . ").pdf";
	$mpdf->SetTitle($filename);
	$mpdf->SetAuthor($abstract->author);
	$footer = "Copyright " . date("Y") . " " . get_option("blogname") . " powered by WPAbstracts Pro";
	$mpdf->SetFooter($footer);
	$html = wpabstracts_generate_html($abstract);
	$mpdf->WriteHTML($html, 2);
	if($is_email) {
		// ensure temp directory email_exists
		$tempPath = WPABSTRACTS_PLUGIN_DIR . 'temp';
		if(!is_dir($tempPath)){
			mkdir($tempPath, 0755);
		}
		$filePath = $tempPath . '/' . $filename;
		$mpdf->Output($filePath, 'F');
		return $filePath;
	} else {
		$mpdf->Output($filename, "D");
		exit(0);
	}
}
