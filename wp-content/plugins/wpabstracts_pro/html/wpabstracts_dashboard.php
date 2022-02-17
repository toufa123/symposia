<?php
defined('ABSPATH') or die("ERROR: You do not have permission to access this page");
include_once(apply_filters('wpabstracts_page_include', WPABSTRACTS_PLUGIN_DIR . 'abstracts/abstracts.manage.php'));
include_once(apply_filters('wpabstracts_page_include', WPABSTRACTS_PLUGIN_DIR . 'wpabstracts_reviews.php'));
global $post;
update_option('wpabstracts_event_id', $event_id); // set event_id for use in functions (redirection etc)
update_option('wpabstracts_dashboard_id', $post->ID); // set dashboard_id for use in functions (redirection etc)
$task = isset($_GET["task"]) ? sanitize_text_field($_GET["task"]) : '';
$userTasks = array('register', 'activate', 'lostpassword', 'resetpassword');

if(in_array($task, $userTasks)){
	do_action('wpabstracts_dashboard_init');
	$html = wpabstracts_user_getview($task, false);
	echo $html;
	return;
}

if(is_user_logged_in()){
	$user = wp_get_current_user();
	if(in_array('administrator', $user->roles)){
		wpabstracts_show_message(__("You're logged in as an administrator, please use WordPress admin area to manage abstracts or use another browser to view your dashboard as author or reviewer.", "wpabstracts"), 'alert-danger');
		return;
	}else if(in_array('subscriber', $user->roles) || in_array('editor', $user->roles)){
		$task = isset($_GET["task"]) ? sanitize_text_field($_GET["task"]) : '';
        $id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
        if($task && $id && !wpabstracts_user_can_manage($task, $id)) {
            wpabstracts_show_message(__("You do not have permissions to manage this resource.", "wpabstracts"), 'alert-danger');
            return;
        }

		// when on add or edit abstracts - hide new abstracts menu
		$show_nav_btns = true;
		if($task == 'add_abstract' || $task == 'edit_abstract'){
			$show_nav_btns = false;
		}
		wpabstracts_dashboard_header($user, $show_nav_btns);

		if($task == 'profile'){
			do_action('wpabstracts_dashboard_init');
			$html = wpabstracts_user_getview('profile', false);
			echo $html;
			return;
		}

		if(in_array('subscriber', $user->roles)){
			if( $task == "add_abstract" ){
				wpabstracts_add_abstract($event_id);
			}
			else if ($task == "edit_abstract" ){
				wpabstracts_edit_abstract($id);
            }
            else if ($task == "view_abstract" ){
				wpabstracts_view_abstract($id);
            }
			else if ($task == "delete_abstract"){
				wpabstracts_delete_abstract($id, true);
				wpabstracts_show_author_dashboard($user);
			}else{
				wpabstracts_show_author_dashboard($user);
			}
		}else if(in_array('editor', $user->roles)){
			if( $task == "add_abstract" ){
				wpabstracts_add_abstract($event_id);
			}
			else if ($task == "edit_abstract" ){
				wpabstracts_edit_abstract($id);
            }
            else if ($task == "view_abstract" ){
				wpabstracts_view_abstract($id);
            }
			else if ($task == "delete_abstract"){
				wpabstracts_delete_abstract($id, true);
				wpabstracts_show_reviewer_dashboard($user);
			}
			else if ($task == "new_review"){
				wpabstracts_add_review($id);
			}
			else if ($task == "edit_review"){
				wpabstracts_edit_review($id);
			}
			else if ($task == "delete_review"){
				wpabstracts_delete_review($id, true);
				wpabstracts_show_reviewer_dashboard($user);
			}else{
				wpabstracts_show_reviewer_dashboard($user);
			}
		}
	} else{
		wpabstracts_show_message(__('Your account is not setup to view this dashboard. Please contact your site admin.', 'wpabstracts'), 'alert-danger');
	}
} else{
	wpabstracts_get_login();
}

function wpabstracts_dashboard_header($user, $show_nav_btns) { ?> 
    <p class="wpabstracts text-right"><strong><?php echo apply_filters('wpabstracts_title_filter', __('Welcome back','wpabstracts'), 'welcome_back');?> <?php echo $user->display_name; ?></strong></p>
    <div class="wpabstracts">
        <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#wpabstracts-dash-menu">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" id="dashboard-btn" href="?dashboard"><span class="wpabstracts glyphicon glyphicon-home" aria-hidden="true"></span> <?php echo apply_filters('wpabstracts_title_filter', __('Dashboard','wpabstracts'), 'dashboard');?></a>
            </div>
            <div class="collapse navbar-collapse" id="wpabstracts-dash-menu">
					<?php if($show_nav_btns){ ?>
                <ul class="nav navbar-nav">
                <?php if (in_array('subscriber', $user->roles)) { ?>
                      <li>
                          <a href="<?php echo get_permalink(); ?>?task=add_abstract"><span class="wpabstracts glyphicon glyphicon-plus" aria-hidden="true"></span> <?php echo apply_filters('wpabstracts_title_filter', __('New Abstract','wpabstracts'), 'new_abstract');?></a>
                      </li>
                <?php } else if (in_array('editor', $user->roles) && get_option('wpabstracts_reviewer_submit')){ ?>
                      <li>
                          <a href="<?php echo get_permalink(); ?>?task=add_abstract"><span class="wpabstracts glyphicon glyphicon-plus" aria-hidden="true"></span> <?php echo apply_filters('wpabstracts_title_filter', __('New Abstract','wpabstracts'), 'new_abstract');?></a>
                      </li>
                <?php } ?>
                </ul>
					 <?php } ?>
                <ul class="nav navbar-nav navbar-right">
                    <?php ob_start();?>
                        <li>
                            <a href="<?php echo get_permalink(); ?>?task=profile">
                                    <span class="wpabstracts glyphicon glyphicon-user" aria-hidden="true"></span> <?php echo apply_filters('wpabstracts_title_filter', __('My Profile','wpabstracts'), 'my_profile');?>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo wp_logout_url(home_url()); ?>">
                                <span class="wpabstracts glyphicon glyphicon-off" aria-hidden="true"></span> <?php echo apply_filters('wpabstracts_title_filter', __('Logout','wpabstracts'), 'logout_btn');?>
                            </a>
                        </li>
                    <?php $right_links = ob_get_clean();?>
                    <?php echo apply_filters('wpabstracts_dashboard_rightmenu', $right_links); ?>
                </ul>
            </div>
        </div>
    </nav>
    </div>
    <?php
}

function wpabstracts_show_author_dashboard($user) {
	$statuses = wpabstracts_get_statuses();
    $abstracts = wpabstracts_get_abstracts('submit_by', $user->ID); ?>
        <div class="wpabstracts container-fluid">
            <div class="wpabstracts panel panel-default">
                <div class="wpabstracts panel-heading">
                    <h6 class="wpabstracts panel-title"><?php echo apply_filters('wpabstracts_title_filter', __('My Abstracts','wpabstracts'), 'my_abstracts');?></h6>
                </div>

                <div class="wpabstracts panel-body">
                    <div class="wpabstracts table-responsive">
                        <table class="wpabstracts table table-hover table-striped" id="wpa_my_abstracts">
                            <thead>
                                <tr>
                                    <th><?php _e('ID','wpabstracts');?></th>
                                    <th><?php _e('Title','wpabstracts');?></th>
                                    <?php if(get_option('wpabstracts_show_reviews')){ ?>
                                    <th><?php _e('Review','wpabstracts');?></th>
                                    <?php } ?>
                                    <th><?php _e('Status','wpabstracts');?></th>
                                    <th><?php _e('Preference','wpabstracts');?></th>
                                    <th><?php _e('Submit Date','wpabstracts');?></th>
                                    <th><i class="wpabstracts glyphicon glyphicon-paperclip" title="<?php _e("Attachments", 'wpabstracts'); ?>"></th>
                                    <th><?php _e('Action','wpabstracts');?></th>
                                </tr>
                            </thead>
                            <?php
                                if (!count($abstracts)) { ?>
                                    <tbody>
                                        <tr>
                                            <td colspan="7"><?php _e("You have NOT submitted any abstracts.", 'wpabstracts'); ?><td>
                                        </tr>
                                    </tbody>
                                    <?php } else { ?>
                                    <tbody>
                                    <?php
                                    foreach($abstracts as $abstract){ ?>
                                        <?php $reviews = wpabstracts_get_reviews('abstract_id', $abstract->abstract_id); ?>
                                        <?php $attachments = wpabstracts_get_attachments($abstract->abstract_id); ?>
                                        <?php 
                                            $visible_reviews = array();
                                            foreach($reviews as $review){
                                                if($review->visibility) {
                                                    array_push($visible_reviews, $review);
                                                }
                                            }
                                        ?>
                                        <tr>
                                            <td><?php echo $abstract->abstract_id; ?></td>
                                            <td style="width:20%;"><?php echo stripslashes($abstract->title); ?></td>
                                            <?php if(get_option('wpabstracts_show_reviews')){ ?>
                                            <td style="width:35%;"><ol><?php
                                                if(!count($reviews) || !count($visible_reviews)){
                                                    _e("No reviews as yet.", 'wpabstracts');
                                                }else{
                                                    foreach($visible_reviews as $review){
                                                        echo '<li>' . stripslashes($review->comments);
                                                        $review_atts = wpabstracts_get_review_attachment($review->review_id);
                                                        if($review_atts) {
                                                            echo ' (<a href="?task=download&type=review_attachment&id=' . $review_atts->att_id . '">' . apply_filters('wpabstracts_title_filter', __('Document','wpabstracts'), 'review_document') . '</a>)';
                                                        }
                                                        echo '</li>';
                                                    }
                                                }?>
                                            </ol></td>
                                            <?php } ?>
                                            <td><?php echo wpabstracts_map_status_name($statuses, $abstract->status); ?></td>
                                            <td><?php echo stripslashes($abstract->presenter_preference); ?></td>
                                            <td><?php echo date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($abstract->submit_date)); ?></td>
                                            <td style="text-align: center;"><?php echo count($attachments); ?></td>
                                            <?php $edit_status = get_option('wpabstracts_edit_status');?>
                                            <?php if(!$abstract->status || $edit_status == $abstract->status) { ?>
                                            <td>
                                                <a href="?task=edit_abstract&id=<?php echo $abstract->abstract_id; ?>"><?php _e('Edit','wpabstracts');?></a>
                                                <a href='javascript:wpabstracts_delete_abstracts(<?php echo $abstract->abstract_id; ?>, 1, 0)'><?php _e('Delete','wpabstracts');?></a>
                                            </td>
                                            <?php } else { ?>
                                                <td>
                                                <a href="?task=view_abstract&id=<?php echo $abstract->abstract_id; ?>"><?php _e('View','wpabstracts');?></a>
                                                </td>
                                            <?php } ?>
                                        </tr>
                                    <?php
                                    } ?>
                                    </tbody>
                            <?php } ?>
                        </table>
                        <script>
                            jQuery(document).ready( function () {
                                var abs_count = '<?php echo count($abstracts);?>';
                                if(abs_count > 0){
                                    jQuery('#wpa_my_abstracts').DataTable({
                                        responsive: false,
                                        dom: 'Bfrltip',
                                        buttons: []
                                    });
                                }                                
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    <?php
}

function wpabstracts_show_reviewer_dashboard($user) {
    global $wpdb;
    $statuses = wpabstracts_get_statuses();
    ob_start();
    if(get_option('wpabstracts_reviewer_submit')){
        wpabstracts_show_author_dashboard($user);
    }
    ?>
    <div class="wpabstracts container-fluid">
        <div class="wpabstracts panel panel-default">
            <div class="wpabstracts panel-heading">
                <h6 class="wpabstracts panel-title"><?php echo apply_filters('wpabstracts_title_filter', __('Assigned Abstracts','wpabstracts'), 'assigned_abstracts');?></h6>
            </div>
            <div class="wpabstracts panel-body">
                <div class="wpabstracts table-responsive">
                    <table class="wpabstracts table table-hover" id="wpa_assigned_abs">
                        <thead>
                            <tr role="row">
                                <th><?php _e('ID','wpabstracts');?></th>
                                <th><?php _e('Title','wpabstracts');?></th>
                                <?php if(!get_option('wpabstracts_blind_review')){ ?>
                                    <th><?php _e('Author','wpabstracts');?></th>
                                <?php } ?>
                                <th><?php _e('Topic','wpabstracts');?></th>
                                <th><?php _e('Preference','wpabstracts');?></th>
                                <th><?php _e('Submit Date','wpabstracts');?></th>
                                <th><i class="wpabstracts glyphicon glyphicon-paperclip" title="<?php _e("Attachments", 'wpabstracts'); ?>"></th>
                                <th><?php _e('Action', 'wpabstracts'); ?></th>
                            </tr>
                        </thead>
                        <?php
                            $abstracts = wpabstracts_get_assigned_abstracts($user->ID);
                            if(!count($abstracts)) { ?>
                                <tbody>
                                    <tr>
                                        <td colspan="7"><?php _e('You have NO newly assigned abstracts','wpabstracts');?><td>
                                    </tr>
                                </tbody>
                            <?php
                            } else { ?>
                                <tbody>
                                <?php 
                                foreach($abstracts as $abstract) {
                                    $attachments_count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}wpabstracts_attachments WHERE abstracts_id = " . $abstract->abstract_id); ?>
                                    <tr>
                                        <td><?php echo $abstract->abstract_id; ?></td>
                                        <td><?php echo stripslashes($abstract->title); ?></td>
                                        <?php if(!get_option('wpabstracts_blind_review')){ ?>
                                            <td><?php echo $abstract->author; ?></td>
                                        <?php } ?>
                                        <td><?php echo stripslashes($abstract->topic); ?></td>
                                        <td><?php echo stripslashes($abstract->presenter_preference); ?></td>
                                        <td><?php echo date('m-d-y g:i a', strtotime($abstract->submit_date)); ?></td>
                                        <td style="text-align: center;"><?php echo $attachments_count; ?></td>
                                        <td>
                                            <?php if (get_option('wpabstracts_reviewer_edit')) { ?>
                                            <a href="?task=edit_abstract&id=<?php echo $abstract->abstract_id; ?>"><?php _e('Edit','wpabstracts');?></a> |
                                            <?php } ?>
                                            <a href="?task=new_review&id=<?php echo $abstract->abstract_id; ?>"><?php _e('Add Review','wpabstracts');?></a>
                                        </td>
                                    </tr>
                            <?php }
                            }
                            ?>
                            </tbody>
                        </table>
                        <script>
                            jQuery(document).ready( function () {
                                var abs_count = '<?php echo count($abstracts);?>';
                                if(abs_count > 0){
                                    jQuery('#wpa_assigned_abs').DataTable({
                                        responsive: false,
                                        dom: 'Bfrltip',
                                        buttons: []
                                    });
                                }   
                            });
                        </script>
                    </div>
                </div>
        </div>

        <div class="wpabstracts panel panel-default">
            <div class="wpabstracts panel-heading">
                <h6 class="wpabstracts panel-title"><?php echo apply_filters('wpabstracts_title_filter', __('My Reviews','wpabstracts'), 'my_reviews');?></h6>
            </div>
            <div class="wpabstracts panel-body">
                <div class="wpabstracts table-responsive">
                    <table class="wpabstracts table table-hover" id="wpa_my_reviews">
                        <thead>
                            <tr>
                                <th><?php _e('ID','wpabstracts');?></th>
                                <th><?php _e('Title','wpabstracts');?></th>
                                <th><?php _e('Comments','wpabstracts');?></th>
                                <th><?php _e('Status','wpabstracts');?></th>
                                <th><?php _e('Relevance','wpabstracts');?></th>
                                <th><?php _e('Quality','wpabstracts');?></th>
                                <th><?php _e('Preference','wpabstracts');?></th>
                                <th><?php _e('Reviewed','wpabstracts');?></th>
                                <th><?php _e('Action','wpabstracts');?></th>
                            </tr>
                        </thead>
                        <?php
                        $reviews = wpabstracts_get_reviews('user_id', $user->ID);
                        if(!count($reviews)) { ?>
                            <tbody>
                                <tr>
                                    <td colspan="9"><?php _e('You have no COMPLETED reviews','wpabstracts');?></td>
                                </tr>
                            </tbody>
                        <?php
                        }
                        else{ ?>
                            <tbody>
                            <?php
                            foreach($reviews as $review){
                                $abs = wpabstracts_get_abstract($review->abstract_id); ?>
                                <tr>
                                    <td><?php echo $review->review_id; ?></td>
                                    <td><?php echo stripslashes($abs->title); ?></td>
                                    <td><?php echo stripslashes($review->comments); ?></td>
                                    <td><?php echo wpabstracts_map_status_name($statuses, $review->status); ?></td>
                                    <td><?php _e($review->relevance, 'wpabstracts'); ?></td>
                                    <td><?php _e( $review->quality, 'wpabstracts'); ?></td>
                                    <td><?php echo $review->recommendation; ?></td>
                                    <td><?php echo date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($review->review_date)); ?></td>
                                    <td>
                                        <a href="?task=edit_review&id=<?php echo $review->review_id; ?>"><?php _e('Edit','wpabstracts');?></a> |
                                        <a href="javascript:wpabstracts_delete_review(<?php echo $review->review_id; ?>)"><?php _e('Delete','wpabstracts');?></a>
                                    </td>
                                </tr>
                            <?php }
                            } ?>
                        </tbody>
                    </table>
                    <script>
                        jQuery(document).ready( function () {
                            var reviews_count = '<?php echo count($reviews);?>';
                                if(reviews_count > 0){
                                    jQuery('#wpa_my_reviews').DataTable({
                                        responsive: false,
                                        dom: 'Bfrltip',
                                        buttons: []
                                    });
                                }  
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
  <?php
  $html = ob_get_clean();
  echo apply_filters('wpabstracts_reviewer_dashboard', $html, $user);
}
