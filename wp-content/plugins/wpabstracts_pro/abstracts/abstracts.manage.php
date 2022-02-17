<?php
defined('ABSPATH') or die("ERROR: You do not have permission to access this page");

if(!class_exists('WPAbstract_Abstracts_Table')){
	require_once(apply_filters('wpabstracts_page_include', WPABSTRACTS_PLUGIN_DIR . 'abstracts/abstracts.classes.php'));
}
if(!class_exists('WPAbstracts_Emailer')){
	require_once(apply_filters('wpabstracts_page_include', WPABSTRACTS_PLUGIN_DIR . 'inc/wpabstracts_emailer.php'));
}

if(is_admin() && isset($_GET['tab']) && $_GET["tab"]=="abstracts"){
	if(isset($_GET["task"])){
		$task = sanitize_text_field($_GET["task"]);
		$status = isset($_GET["status"]) ? sanitize_text_field($_GET["status"]) : 0;
		$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

		switch($task){
			case 'new':
			wpabstracts_add_abstract();
			break;
			case 'edit':
			wpabstracts_edit_abstract($id);
			break;
			case 'view':
			wpabstracts_view_abstract($id);
			break;
			case 'status':
			wpabstracts_change_status($status);
			wpabstracts_display_abstracts();
			break;
			case 'assign':
			wpabstracts_assign_reviewers();
			wpabstracts_display_abstracts();
			break;
			case 'delete':
			wpabstracts_delete_abstracts();
			wpabstracts_display_abstracts();
			break;
			default :
			if(has_action('wpabstracts_page_render')){
				do_action('wpabstracts_page_render');
			}else{
				wpabstracts_display_abstracts();
			}
			break;
		}

	}else{
		wpabstracts_display_abstracts();
	}
}

function wpabstracts_add_abstract($event_id = null) {
	if($_POST){
		// inserts submission to DB
		$id = wpabstracts_manage_abstracts(0, 'insert');

		// sends email notifications if enabled
		wpabstracts_send_submit_notifications($id);

		if($_FILES) {
			wpabstracts_upload_attachments($id);
		}
		$redirect = (is_super_admin()) ? '?page=wpabstracts&tab=abstracts' : '?dashboard';
		wpabstracts_redirect($redirect);
	}
	else {
		wpabstracts_get_add_view('Abstract', $event_id);
	}
}

function wpabstracts_edit_abstract($id) {
	if ($_POST) {
		wpabstracts_manage_abstracts($id, 'update');

		// sends email notifications if enabled
		wpabstracts_send_edit_notifications($id);

		if($_FILES){
			wpabstracts_upload_attachments($id);
		}
		$redirect = (is_super_admin()) ? '?page=wpabstracts&tab=abstracts' : '?dashboard';
		wpabstracts_redirect($redirect);
	}else{
		$abstract = wpabstracts_get_edit_view('Abstract', $id);
        if($abstract){
            echo $abstract;
        }else{
            wpabstracts_show_message(__('Could not locate this resource. Please try again.', 'wpabstracts'), 'alert-danger');
		}
	}
}

function wpabstracts_view_abstract($id) {
	$abstract = wpabstracts_get_readonly_view('Abstract', $id);
	if($abstract){
		echo $abstract;
	}else{
		wpabstracts_show_message(__('Could not locate this resource. Please try again.', 'wpabstracts'), 'alert-danger');
	}
}

function wpabstracts_send_submit_notifications($aid){
	// sends author or admin email if submission notifications are enabled
	if(get_option('wpabstracts_submission_notification')) {
		// sends author email if option is enabled
		$author_submit_templateId = get_option('wpabstracts_submit_templateId');
		if($author_submit_templateId > 0){
			$user_id =  get_current_user_id();
			$emailer = new WPAbstracts_Emailer($aid, $user_id, $author_submit_templateId);
			$emailer->send();
		}
		// sends system admin an email if enabled
		$admin_submit_templateId = get_option('wpabstracts_admin_templateId');
		if($admin_submit_templateId > 0) {
			$super_admins = get_users( array('role'=>'administrator', 'fields'=>'ID') );
			foreach ($super_admins as $super_admin_id) {
				$enabled = get_user_meta($super_admin_id, 'wpabstracts_enable_notification', true); 
				if($enabled){
					$emailer = new WPAbstracts_Emailer($aid, $super_admin_id, $admin_submit_templateId );
					$emailer->send();
				}
			}
		}
	}
}

function wpabstracts_send_edit_notifications($aid){
	// sends author or admin email if submission notifications are enabled
	if(get_option('wpabstracts_edit_notification')) {
		// sends author email if option is enabled
		$author_edit_templateId = get_option('wpabstracts_author_edit_templateId');
		if($author_edit_templateId > 0){
			$user_id =  get_current_user_id();
			$emailer = new WPAbstracts_Emailer($aid, $user_id, $author_edit_templateId);
			$emailer->send();
		}
		// sends system admin an email if enabled
		$admin_edit_templateId = get_option('wpabstracts_admin_edit_templateId');
		if($admin_edit_templateId > 0) {
			$super_admins = get_users( array('role'=>'administrator', 'fields'=>'ID') );
			foreach ($super_admins as $super_admin_id) {
				$enabled = get_user_meta($super_admin_id, 'wpabstracts_enable_notification', true); 
				if($enabled){
					$emailer = new WPAbstracts_Emailer($aid, $super_admin_id, $admin_edit_templateId );
					$emailer->send();
				}
			}
		}
		// sends reviewers a revision email if enabled
		$reviewer_revised_templateId = get_option('wpabstracts_submit_revised_templateId');
		if($reviewer_revised_templateId > 0) {
			$reviewers = wpabstracts_get_reviewers($aid);
			foreach ($reviewers as $reviewer) {
				$emailer = new WPAbstracts_Emailer($aid, $reviewer->ID, $reviewer_revised_templateId );
				$emailer->send();
			}
		}
	}

}

function wpabstracts_load_reviewers(){ // ajax
	try {
		ob_start();
		$abs_ids = isset($_POST['abs_ids']) ? $_POST['abs_ids'] : array();
		$isBulk = isset($_POST['isBulk']) ? intval($_POST['isBulk']) : 0;
		$paged = isset($_POST['page']) ? intval($_POST['page']) : 1;
		if($isBulk){
			$all_reviewers = get_users(array( 
				'role__in' => array( 'administrator', 'editor' ), 
				'orderby' => 'display_name'
			));
			$current_reviewers = array();
		}else{
			$abstract = wpabstracts_get_abstract($abs_ids[0]);
			// get all admins and editors and exclude the user who submitted this abstract
			$all_reviewers = get_users(array( 
				'role__in' => array( 'administrator', 'editor' ), 
				'exclude' => array($abstract->submit_by),
				'orderby' => 'display_name'
			));
			$current_reviewers = wpabstracts_get_reviewers($abs_ids[0]);
			$noShow = sizeof($current_reviewers) > 0 ? 'hidden' : '';
			$show = sizeof($current_reviewers) > 0 ? '' : 'hidden';
		}
		?>
		<div class="wpabstracts container-fluid">
			<form method="post" id="assign_form" action="?page=wpabstracts&tab=abstracts&task=assign&paged=<?php echo $paged;?>">
				<div class="wpabstracts modal-header">
					<div class="header"><?php echo apply_filters('wpabstracts_title_filter', __("Assign Reviewers", 'wpabstracts'), 'assign_reviewers');?> <span class="wpabstracts glyphicon glyphicon-plus-sign add_reviewer" onclick="wpabstracts_add_reviewer();"></span></div>
				</div>
				<div class="wpabstracts modal-body" id="reviewers_table">
					<div class="wpabstracts row row_titles <?php echo $show;?>">
						<div class="wpabstracts col-sm-10" style="text-align: right"><?php _e('Notify', 'wpabstracts');?></div>
						<div class="wpabstracts col-sm-2"><?php _e('Delete', 'wpabstracts');?></div>
					</div>
					<div class="wpabstracts row no_reviewers <?php echo $noShow;?>">
						<div class="wpabstracts col-sm-12" style="text-align: center"><?php _e('No Reviewers Assigned', 'wpabstracts');?></div>
					</div>
					<br>
					<?php if(!$isBulk) { ?>
						<?php foreach($current_reviewers as $current_reviewer) { ?>
							<div class="wpabstracts row reviewer_selection">
								<div class="wpabstracts col-sm-8">
									<select class="wpabstracts form-control" name="reviewers[]">
										<?php foreach($all_reviewers as $reviewer){ ?>
											<option value="<?php echo $reviewer->ID;?>" <?php selected($reviewer->ID, $current_reviewer->ID);?>><?php echo $reviewer->display_name;?></option>
										<?php } ?>
									</select>
								</div>
								<div class="wpabstracts col-sm-2">
									<input type="checkbox" class="wpabs_email" name="reviewers_notify[]" value="true">
								</div>
								<div class="wpabstracts col-sm-2">
									<span class="wpabstracts glyphicon glyphicon-minus-sign delete_reviewer" onclick="wpabstracts_delete_reviewer(this);"></span>
								</div>
							</div>
						<?php } ?>
					<?php } else { ?>
						<!-- add next selection -->
						<div class="wpabstracts row reviewer_selection">
							<div class="wpabstracts col-sm-8">
								<select class="wpabstracts form-control" name="reviewers[]">
									<option selected value="" style="display:none"><?php _e('Select Reviewer', 'wpabstracts');?></option>
										<?php foreach($all_reviewers as $reviewer){ ?>
											<option value="<?php echo $reviewer->ID;?>"><?php echo $reviewer->display_name;?></option>
										<?php } ?>
								</select>
							</div>
							<div class="wpabstracts col-sm-2">
								<input type="checkbox" class="wpabs_email" name="reviewers_notify[]" value="true">
							</div>
							<div class="wpabstracts col-sm-2">
								<span class="wpabstracts glyphicon glyphicon-minus-sign delete_reviewer" onclick="wpabstracts_delete_reviewer(this);"></span>
							</div>
						</div>
					<?php } ?>
					<!-- default reviewer_selection -->
					<div class="wpabstracts row reviewer_selection empty_reviewer hidden">
						<div class="wpabstracts col-sm-8">
							<select class="wpabstracts form-control" name="reviewers[]">
								<option selected value="" disabled style="display:none"><?php _e('Select Reviewer', 'wpabstracts');?></option>
								<?php foreach($all_reviewers as $reviewer){ ?>
									<option value="<?php echo $reviewer->ID;?>"><?php echo $reviewer->display_name;?></option>
								<?php } ?>
							</select>
						</div>
						<div class="wpabstracts col-sm-2">
							<input type="checkbox" class="wpabs_email" name="reviewers_notify[]" value="true">
						</div>
						<div class="wpabstracts col-sm-2">
							<span class="wpabstracts glyphicon glyphicon-minus-sign delete_reviewer" onclick="wpabstracts_delete_reviewer(this);"></span>
						</div>
					</div>
				</div>
				<div class="wpabstracts col-xs-12">
				<?php foreach($abs_ids as $id) { ?>
					<input type="hidden" name="abs_ids[]" value="<?php echo $id; ?>">
				<?php } ?>
				</div>
			</form>
		</div>
	<?php 
		
	} catch (Exception $e) {
		echo 'Caught exception: ',  $e->getMessage(); 	
	}
	$html = ob_get_contents();
	ob_end_clean();
	echo apply_filters('wpabstracts_get_reviewers', $html);
	die();
}

function wpabstracts_assign_reviewers(){
	do_action('wpabstracts_assign_reviewers', $_POST);
	global $wpdb;
	$wpdb->show_errors();
	if($_POST){
		$abs_ids = isset($_POST['abs_ids']) ? $_POST['abs_ids'] : array();
		$notify_reviewer = isset($_POST['reviewers_notify']) ? $_POST['reviewers_notify'] : array();
		// insert reviewers (remove all entries first)
		foreach($abs_ids as $abstract_id){
			if(isset($_POST['reviewers'])){
				$reviewers = $_POST['reviewers'];
				$wpdb->delete($wpdb->prefix."wpabstracts_reviewers", array('abs_id' => $abstract_id));
				foreach($reviewers as $key => $reviewer_id) {
					$data = array('abs_id' => $abstract_id, 'user_id' => $reviewer_id);
					$wpdb->insert($wpdb->prefix."wpabstracts_reviewers", $data);
		
					// send email if review notifications are enabled and if email check box is checked.
					if(get_option('wpabstracts_review_notification')) {
						if(!empty($notify_reviewer) && $key < sizeof($notify_reviewer) && $notify_reviewer[$key]){
							$review_assignment_templateId = get_option('wpabstracts_assignment_templateId');
							if($review_assignment_templateId > 0) {
								$emailer = new WPAbstracts_Emailer($abstract_id, $reviewer_id, $review_assignment_templateId);
								$emailer->send();
							}
						}
					}
				}
			}
			// process removals
			if(isset($_POST['remove_reviewers'])){
				$remove_reviewers = $_POST['remove_reviewers'];
				foreach($remove_reviewers as $id) {
					$wpdb->delete($wpdb->prefix."wpabstracts_reviewers", array('abs_id' => $abstract_id, 'user_id'=>$id));
				}
			}
		}
	}
}

function wpabstracts_load_status(){ // ajax
	$statuses = wpabstracts_get_statuses();
	ob_start();
	$abs_ids = isset($_POST['abs_ids']) ? $_POST['abs_ids'] : array();
	$isBulk = isset($_POST['isBulk']) ? intval($_POST['isBulk']) : 0;
	$paged = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$currentStatus = ""; 
	if($isBulk){
		$abstract = wpabstracts_get_abstract($abs_ids[0]);
		$currentStatus = $abstract ? $abstract->status : "";
	}
	?>
	<div class="wpabstracts container-fluid">
		<form method="post" id="change_status_form" action="?page=wpabstracts&tab=abstracts&task=status&paged=<?php echo $paged;?>">
			<div class="wpabstracts modal-header">
				<div class="header"><?php echo apply_filters('wpabstracts_title_filter', __("Change Status", 'wpabstracts'), 'change_status');?></div>
			</div>
			<div class="wpabstracts modal-body" id="status_table">
				<select class="wpabstracts form-control" name="abs_status">
					<option value="" <?php selected("", $currentStatus);?>><?php _e('Select a status', 'wpabstracts'); ?></option>
					<?php foreach($statuses as $status) { ?>
						<option value="<?php echo $status->id;?>" <?php selected($status->id, $currentStatus);?>><?php echo $status->name;?></option>
					<?php } ?>
				</select>
				<?php foreach($abs_ids as $id) { ?>
					<input type="hidden" id="aid" name="abs_ids[]" value="<?php echo $id; ?>">
				<?php } ?>
			</div>
		</form>
	</div>
<?php
$html = ob_get_contents();
ob_end_clean();
echo apply_filters('wpabstracts_load_status', $html);
die();
}

function wpabstracts_change_status($status){
	global $wpdb;
	$wpdb->show_errors();
	if($_POST){
		$abs_ids = isset($_POST['abs_ids']) ? $_POST['abs_ids'] : array();
		$statusId = isset($_POST['abs_status']) ? intval($_POST['abs_status']) : null;
		$data = array('status' => $statusId);
		$updated = false;
		foreach($abs_ids as $abs_id) {
			$where = array('abstract_id' => $abs_id);
			$updated = $wpdb->update($wpdb->prefix."wpabstracts_abstracts", $data, $where);
	
			if(get_option('wpabstracts_status_notification')){
				$abstract = wpabstracts_get_abstract($abs_id);
				$status = wpabstracts_get_status($statusId);
				if($status && $status->template_id > 0){
					$emailer = new WPAbstracts_Emailer($abs_id, $abstract->submit_by, $status->template_id);
					$success = $emailer->send();
				}
			}
		}
		if($updated){
			wpabstracts_show_message(__('Status was updated successfully.', 'wpabstracts'), 'alert-success');
		}
	}

}

function wpabstracts_delete_abstract($abs_id, $showMsg){
	global $wpdb;
	$wpdb->show_errors();
	$wpdb->delete("{$wpdb->prefix}wpabstracts_abstracts", array( 'abstract_id' => $abs_id));
	$wpdb->delete("{$wpdb->prefix}wpabstracts_reviews", array( 'abstract_id' => $abs_id));
	$wpdb->delete("{$wpdb->prefix}wpabstracts_reviewers", array( 'abs_id' => $abs_id));
	$wpdb->delete("{$wpdb->prefix}wpabstracts_attachments", array( 'abstracts_id' => $abs_id));	
	if($showMsg){
		wpabstracts_show_message(__('Abstract Id ', 'wpabstracts') . $abs_id . __(' was successfully deleted.', 'wpabstracts'), 'alert-success');
	}
}

function wpabstracts_delete_abstracts(){
	global $wpdb;
	$wpdb->show_errors();
	if($_POST){
		$abs_ids = isset($_POST['abs_ids']) ? $_POST['abs_ids'] : array();
		$isBulk = isset($_POST['isBulk']) ? intval($_POST['isBulk']) : 0;
		$paged = isset($_POST['page']) ? intval($_POST['page']) : 1;
		foreach($abs_ids as $abs_id) {
			$wpdb->delete("{$wpdb->prefix}wpabstracts_abstracts", array( 'abstract_id' => $abs_id));
			$wpdb->delete("{$wpdb->prefix}wpabstracts_reviews", array( 'abstract_id' => $abs_id));
			$wpdb->delete("{$wpdb->prefix}wpabstracts_reviewers", array( 'abs_id' => $abs_id));
			$wpdb->delete("{$wpdb->prefix}wpabstracts_attachments", array( 'abstracts_id' => $abs_id));		
		}
		if(!$isBulk){
			wpabstracts_show_message(__('Abstract Id ', 'wpabstracts') . $abs_ids[0] . __(' was successfully deleted.', 'wpabstracts'), 'alert-success');
		}else{
			wpabstracts_show_message(__('The selected Abstracts were successfully deleted.', 'wpabstracts'), 'alert-success');
		}
	}
}

function wpabstracts_display_abstracts(){ ?>  
	<div class="wpabstracts container-fluid wpabstracts-admin-container">
		<h3><?php echo apply_filters('wpabstracts_title_filter', __('Abstracts','wpabstracts'), 'abstracts');?>  <a href="?page=wpabstracts&tab=abstracts&task=new" role="button" class="wpabstracts btn btn-primary"><?php _e('Add New', 'wpabstracts');?></a></h3>
	</div>
	<div class="wpabstracts-assign-modal"></div>
	<form id="showsAbstracts" name="abstracts_list" method="get">
		<input type="hidden" name="page" value="wpabstracts" />
		<input type="hidden" name="tab" value="abstracts" />
		<?php
			$abstracts = new WPAbstract_Abstracts_Table();
			$abstracts->prepare_items();
			$abstracts->display();
			$events = wpabstracts_get_events();
			$statuses = wpabstracts_get_statuses();
			$preferences = wpabstracts_get_preferences();
		?>
	</form>
	<script>
		
		jQuery(document).ready( function () {

			var abs_count = '<?php echo count($abstracts->items);?>';

			if(abs_count > 0) {
				var statuses = JSON.parse('<?php echo json_encode($statuses);?>');
				var preferences = JSON.parse('<?php echo json_encode($preferences);?>');
				var table = jQuery('.wp-list-table').DataTable({
					responsive: false,
					dom: 'Bfrltip',
					buttons: [],
					colReorder: false,
					lengthMenu: [ 25, 50, 100, 250, 500, 1000 ],
					columnDefs: [ { 
						type: 'natural', 
						targets: 'column-abs_id'
					}]
				});

				table.column('.column-event').every( function () {
					var column = this;
					var select = jQuery('<select />').appendTo(jQuery('.dt-buttons')).on( 'change', function () {
						jQuery('#wpa_topics').val('');
						column.search(jQuery(this).val()).draw();
					}).append(jQuery('<option value="">Filter by Event</option>')).attr('id', 'wpa_events').attr('name', 'wpa_events');
					column.data('search').sort().unique().each(function (val) {
						select.append( jQuery('<option value="'+val+'">'+val+'</option>'));
					});
				});

				table.column('.column-topic').every( function () {
					var column = this;
					var select = jQuery('<select />').appendTo(jQuery('.dt-buttons')).on( 'change', function () {
						column.search( jQuery(this).val() ).draw();
					}).append(jQuery('<option value="">Filter by Topic</option>')).attr('id', 'wpa_topics').attr('name', 'wpa_topics');
					column.data('search').sort().unique().each(function (val) {
						select.append( jQuery('<option value="'+val+'">'+val+'</option>'));
					});
				});

				table.column('.column-status').every( function () {
					var column = this;
					var select = jQuery('<select />').appendTo(jQuery('.dt-buttons')).on( 'change', function () {
						column.search( jQuery(this).val() ).draw();
					}).append(jQuery('<option value="">Filter by Status</option>')).attr('id', 'wpa_status').attr('name', 'wpa_status');
					statuses.forEach(status => {
						select.append( jQuery('<option value="'+status.name+'">'+status.name+'</option>'));
					});
				});

				table.column('.column-presenter_preference').every( function () {
					var column = this;
					var select = jQuery('<select />').appendTo(jQuery('.dt-buttons')).on( 'change', function () {
						column.search( jQuery(this).val() ).draw();
					}).append(jQuery('<option value="">Filter by Preference</option>')).attr('id', 'wpa_preference').attr('name', 'wpa_preference');
					preferences.forEach(preference => {
						select.append( jQuery('<option value="'+preference+'">'+preference+'</option>'));
					});
				});
			}
			
			jQuery('#doaction, #doaction2').on('click', function(event){
				event.preventDefault(); 
				var selected = jQuery('input[name="abstract\\[\\]"]:checked').map(function() {
					return jQuery(this).val();
				}).toArray();
				var action = -1;
				if(event.target.id == 'doaction'){
					action = jQuery('#bulk-action-selector-top').val();
				}else{
					action = jQuery('#bulk-action-selector-bottom').val();
				}
				switch(action){
					case 'assign_reviewer':
					wpabstracts_load_reviewers(selected, 1, 1);
					break;
					case 'change_status':
					wpabstracts_load_status(selected, 1, 1);
					break;
					case 'delete':
					wpabstracts_delete_abstracts(selected, 1, 1);
					break;
					default:
					jQuery("#showsAbstracts").submit();

				}
			});

		});
	</script>
	<?php
}
