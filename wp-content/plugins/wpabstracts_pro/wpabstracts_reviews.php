<?php
defined('ABSPATH') or die("ERROR: You do not have permission to access this page");

if(!class_exists('WPAbstract_Abstracts_Table')){
	require_once(apply_filters('wpabstracts_page_include', WPABSTRACTS_PLUGIN_DIR . 'inc/wpabstracts_classes.php'));
}
if(!class_exists('WPAbstracts_Emailer')){
	require_once(apply_filters('wpabstracts_page_include', WPABSTRACTS_PLUGIN_DIR . 'inc/wpabstracts_emailer.php'));
}

if(is_admin() && isset($_GET['tab']) && ($_GET["tab"]=="reviews")){
	if(isset($_GET['task'])){
		$task = sanitize_text_field($_GET['task']);
		$id = intval($_GET['id']);

		switch($task){
			case 'new':
			wpabstracts_add_review($id);
			break;
			case 'edit':
			wpabstracts_edit_review($id);
			break;
			case 'view':
			wpabstracts_view_reviews($id);
			break;
			case 'show':
			wpabstracts_toggle_visibility($id, $visibility = 1, $message = true);
			wpabstracts_show_reviews();
			break;
			case 'hide':
			wpabstracts_toggle_visibility($id, $visibility = 0, $message = true);
			wpabstracts_show_reviews();
			break;
			case 'delete':
			wpabstracts_delete_review($id, true);
			default :
			if(has_action('wpabstracts_page_render')){
				do_action('wpabstracts_page_render');
			}else{
				wpabstracts_show_reviews();
			}
			break;
		}
	}else{
		wpabstracts_show_reviews();
	}
}

function wpabstracts_add_review($abstract_id) {
	global $wpdb;
	if($_POST) {

		$redirect = (is_super_admin()) ? '?page=wpabstracts&tab=reviews' : '?dashboard';

		$aid = intval($abstract_id);
		$user_id = get_current_user_id();
		$abs_status = (isset($_POST['abs_status'])) ? sanitize_text_field($_POST['abs_status']) : '';
		$abs_relevance = (isset($_POST['abs_relevance'])) ? sanitize_text_field($_POST['abs_relevance']) : '';
		$abs_quality = (isset($_POST['abs_quality'])) ? sanitize_text_field($_POST['abs_quality']) : '';
		$abs_comments = (isset($_POST['abs_comments'])) ? wp_kses_post($_POST['abs_comments']) : '';
		$abs_recommendation = (isset($_POST['abs_recommendation'])) ? sanitize_text_field($_POST['abs_recommendation']) : '';
		$review_time = current_time( 'mysql' );

		$data = array(
			'abstract_id' => $aid,
			'user_id' => $user_id,
			'quality' => $abs_quality,
			'relevance' => $abs_relevance,
			'recommendation' => $abs_recommendation,
			'status' => $abs_status,
			'comments' => $abs_comments,
			'review_date' => $review_time,
			'visibility' => intval(get_option('wpabstracts_review_visibility'))
		);

		$filter_data = apply_filters('wpabstracts_save_review', $data, $_POST);

		$wpdb->show_errors();
		$wpdb->insert($wpdb->prefix."wpabstracts_reviews", $filter_data);
		$review_id = $wpdb->insert_id;

		// update abstracts with status and presenter preference from review (if sync is enabled)
		if(get_option('wpabstracts_sync_status')){
			$data = array('status' => $abs_status, 'presenter_preference' => $abs_recommendation);
			$where = array('abstract_id' => $aid);
			$wpdb->update($wpdb->prefix."wpabstracts_abstracts", $data, $where);
			// send status change email if enabled
			if(get_option('wpabstracts_status_notification')){
				$abstract = wpabstracts_get_abstract($aid);
				$status = wpabstracts_get_status($abs_status);
				if($status && $status->template_id > 0){
					$emailer = new WPAbstracts_Emailer($aid, $abstract->submit_by, $status->template_id);
					$success = $emailer->send();
				}
			}
		}

		// handle review attachment
		wpabstracts_upload_review_attachment($review_id, $aid);

		// send notifications
		wpabstracts_review_notifications($aid);

		wpabstracts_redirect($redirect);
	}
	else{
		wpabstracts_get_add_view('Review', $abstract_id);
	}
}

function wpabstracts_edit_review($id) {
	global $wpdb;
	if($_POST){

		$redirect = (is_super_admin()) ? '?page=wpabstracts&tab=reviews' : '?dashboard';

		$aid = (isset($_POST['abs_id'])) ? intval($_POST['abs_id']) : '';
		$abs_status = (isset($_POST['abs_status'])) ? sanitize_text_field($_POST['abs_status']) : '';
		$abs_relevance = (isset($_POST['abs_relevance'])) ? sanitize_text_field($_POST['abs_relevance']) : '';
		$abs_quality = (isset($_POST['abs_quality'])) ? sanitize_text_field($_POST['abs_quality']) : '';
		$abs_comments = (isset($_POST['abs_comments'])) ? wp_kses_post($_POST['abs_comments']) : '';
		$abs_recommendation = (isset($_POST['abs_recommendation'])) ? sanitize_text_field($_POST['abs_recommendation']) : '';
		$modified_date = current_time('mysql');

		$data = array(
			'quality' => $abs_quality,
			'relevance' => $abs_relevance,
			'recommendation' => $abs_recommendation,
			'status' => $abs_status,
			'comments' => $abs_comments,
			'modified_date' => $modified_date,
			'visibility' => intval(get_option('wpabstracts_review_visibility'))
		);

		$filter_data = apply_filters('wpabstracts_save_review', $data, $_POST);
		$wpdb->show_errors();
		$wpdb->update($wpdb->prefix."wpabstracts_reviews", $filter_data, array('review_id' => intval($id)));

		// update abstracts with status - if enabled
		if(get_option('wpabstracts_sync_status')){
			$data = array('status' => $abs_status, 'presenter_preference' => $abs_recommendation);
			$where = array('abstract_id' => $aid);
			$wpdb->update($wpdb->prefix."wpabstracts_abstracts", $data, $where);
			// send status change email if enabled
			if(get_option('wpabstracts_status_notification')){
				$abstract = wpabstracts_get_abstract($aid);
				$status = wpabstracts_get_status($abs_status);
				if($status && $status->template_id > 0){
					$emailer = new WPAbstracts_Emailer($aid, $abstract->submit_by, $status->template_id);
					$success = $emailer->send();
				}
			}
		}

		// remove review attachments
		if(isset($_POST['remove_review_attachment'])) { 
			$att_id = intval($_POST['remove_review_attachment']);
			$wpdb->delete($wpdb->prefix."wpabstracts_review_attachments",  array('att_id' => $att_id) );
		}

		// handle review attachment
		wpabstracts_upload_review_attachment($id, $aid);

		// send notifications
		wpabstracts_review_notifications($aid);

		wpabstracts_redirect($redirect);
	}
	else{
		$review = wpabstracts_get_edit_view("Review", $id);
        if($review){
            echo $review;
        }else{
            wpabstracts_show_message(__('Could not locate this resource. Please try again.', 'wpabstracts'), 'alert-danger');
		}
	}
}

function wpabstracts_toggle_visibility($id, $visibility, $show_message) {
	global $wpdb;
	$data = array(
		'visibility' => intval($visibility)
	);
	$where = array('review_id' => intval($id));
	$wpdb->show_errors();
	$wpdb->update($wpdb->prefix."wpabstracts_reviews", $data, $where);
	$_visibility = $visibility ? 'visible' : 'invisible';
	if($show_message){
		wpabstracts_show_message(__('Review Id ', 'wpabstracts') . $id . __(' is now ', 'wpabstracts') . $_visibility . __(' to the author.', 'wpabstracts'), 'alert-success');
	}
}

function wpabstracts_review_notifications($aid){
	// sends author or admin email if reviewer notifications are enabled
	if(get_option('wpabstracts_review_notification')) {
		// sends author email if option is enabled
		$author_review_templateId = get_option('wpabstracts_reviewed_templateId');
		if($author_review_templateId > 0){
			$abstract = wpabstracts_get_abstract($aid);
			$user_id =  $abstract->submit_by;
			$emailer = new WPAbstracts_Emailer($aid, $user_id, $author_review_templateId);
			$emailer->send();
		}
		// sends system admin an email if enabled
		$admin_review_templateId = get_option('wpabstracts_reviewedadmin_templateId');
		if($admin_review_templateId > 0) {
			$super_admins = get_users( array('role'=>'administrator', 'fields'=>'ID') );
			foreach ($super_admins as $super_admin_id) {
				$enabled = get_user_meta($super_admin_id, 'wpabstracts_enable_notification', true); 
				if($enabled){
					$emailer = new WPAbstracts_Emailer($aid, $super_admin_id, $admin_review_templateId );
					$emailer->send();
				}
			}
		}
		// sends review a confirmation email if enabled
		$reviewer_review_templateId = get_option('wpabstracts_reviewedreviewer_templateId');
		if($reviewer_review_templateId > 0) {
			$user_id = get_current_user_id();
			$emailer = new WPAbstracts_Emailer($aid, $user_id, $reviewer_review_templateId);
			$emailer->send();
		}
	}

}

function wpabstracts_delete_review($id, $show_message) {
	global $wpdb;
	$wpdb->show_errors();
	$where = array('review_id' => $id);
	$wpdb->delete($wpdb->prefix."wpabstracts_reviews", $where );
	$wpdb->delete($wpdb->prefix."wpabstracts_review_attachments", $where);

	if($show_message){
		wpabstracts_show_message(__('Review Id ', 'wpabstracts') . $id . __(' was successfully deleted.', 'wpabstracts'), 'alert-success');
	}
}

function wpabstracts_view_reviews($id){ ?>
	<?php ob_start(); ?>
	<div class="wpabstracts container-fluid wpabstracts-admin-container">
		<h4><?php echo apply_filters('wpabstracts_title_filter', __('Reviews for Abstract ID','wpabstracts'), 'events'); ?>: <?php echo $id; ?>
			<a href="?page=wpabstracts&tab=reviews&task=new&id=<?php echo $id; ?>" class="wpabstracts btn btn-primary"><?php _e('Add New', 'wpabstracts'); ?></a>
			<a href="?page=wpabstracts&tab=reviews" class="wpabstracts btn btn-primary"><?php _e('All Reviews', 'wpabstracts'); ?></a>
		</h4>
	</div>
	<form id="viewReviews" method="get">
		<input type="hidden" name="page" value="wpabstracts" />
		<input type="hidden" name="tab" value="reviews" />
		<?php
			$reviews = new WPAbstract_singleItem_Reviews_Table($id);
			$reviews->prepare_items();
			$reviews->display(); 
		?>
	</form>
	<?php
	$html = ob_get_contents();
	ob_end_clean();
	echo apply_filters('wpabstracts_view_single_review', $html, $id);
}

function wpabstracts_show_reviews(){ ?>
	<?php ob_start(); ?>
	<form id="showReviews" method="get">
		<input type="hidden" name="page" value="wpabstracts" />
		<input type="hidden" name="tab" value="reviews" />
		<?php
			$reviews = new WPAbstract_Reviews_Table();
			$reviews->prepare_items();
			$reviews->display(); 
			$statuses = wpabstracts_get_statuses();
			$preferences = wpabstracts_get_preferences();
		?>
	</form>

	<?php
		$html = ob_get_contents();
		ob_end_clean();
		echo apply_filters('wpabstracts_view_all_reviews', $html);
	?>

	<script>
		jQuery(document).ready( function () {
			var review_count = '<?php echo count($reviews->items);?>';
			if(review_count > 0) {
				var statuses = JSON.parse('<?php echo json_encode($statuses);?>');
				var preferences = JSON.parse('<?php echo json_encode($preferences);?>');
				var table = jQuery('.wp-list-table').DataTable({
					responsive: false,
					dom: 'Bfrltip',
					buttons: [],
					colReorder: false
				});

				// Filter by event
				table.column('.column-event_title').every( function () {
					var column = this;
					var select = jQuery('<select />').appendTo(jQuery('.dt-buttons')).on( 'change', function () {
						jQuery('#wpa_topics').val('');
						column.search(jQuery(this).val()).draw();
					}).append(jQuery('<option value="">Filter by Event</option>')).attr('id', 'wpa_events').attr('name', 'wpa_events');
					column.data('search').sort().unique().each(function (val) {
						select.append( jQuery('<option value="'+val+'">'+val+'</option>'));
					});
				});

				// Filter by topic
				table.column('.column-topic').every( function () {
					var column = this;
					var select = jQuery('<select />').appendTo(jQuery('.dt-buttons')).on( 'change', function () {
						column.search(jQuery(this).val()).draw();
					}).append(jQuery('<option value="">Filter by Topic</option>')).attr('id', 'wpa_topics').attr('name', 'wpa_topics');
					column.data('search').sort().unique().each(function (val) {
						select.append( jQuery('<option value="'+val+'">'+val+'</option>'));
					});
				});

				// Filter by Suggested Type
				table.column('.column-recommendation').every( function () {
					var column = this;
					var select = jQuery('<select />').appendTo(jQuery('.dt-buttons')).on( 'change', function () {
						column.search( jQuery(this).val() ).draw();
					}).append(jQuery('<option value="">Filter by Suggested Type</option>')).attr('id', 'wpa_preference');
					preferences.forEach(preference => {
						select.append( jQuery('<option value="'+preference+'">'+preference+'</option>'));
					});
				});

				// Filter by Suggested Status
				table.column('.column-status').every( function () {
					var column = this;
					var select = jQuery('<select />').appendTo(jQuery('.dt-buttons')).on( 'change', function () {
						column.search( jQuery(this).val() ).draw();
					}).append(jQuery('<option value="">Filter by Suggested Status</option>')).attr('id', 'wpa_status');
					statuses.forEach(status => {
						select.append( jQuery('<option value="'+status.name+'">'+status.name+'</option>'));
					});
				});
			}
		});
	</script>
	<?php
}
