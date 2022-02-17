<?php
defined('ABSPATH') or die("ERROR: You do not have permission to access this page");

if(is_admin() && isset($_GET['tab']) && ($_GET["tab"]=="emails")){
    if(isset($_GET['task'])){
        $task = sanitize_text_field($_GET['task']);
        $id = isset($_GET['id']) ? intval($_GET['id']) : '';
        switch($task){
            case 'new':
            wpabstracts_add_template(); break;
            case 'edit':
            wpabstracts_edit_template($id); break;
            case 'delete':
            wpabstracts_delete_email_template($id, true);
            default:
			if(has_action('wpabstracts_page_render')){
				do_action('wpabstracts_page_render');
			}else{
				wpabstracts_show_templates();
			}
        }
    } else {
        wpabstracts_show_templates();
    }
}

function wpabstracts_add_template() {
    global $wpdb;
    if($_POST){
        $template_name = sanitize_text_field($_POST["template_name"]);
        $from_name = sanitize_text_field($_POST["from_name"]);
        $from_email = sanitize_text_field($_POST["from_email"]);
        $email_subject = sanitize_text_field($_POST["email_subject"]);
        $email_body = wp_kses_post($_POST["email_body"]);
        $include_submission = isset($_POST["include_submission"]) ? 1 : 0;
        $wpdb->show_errors();
        $data = array(
            'name' => $template_name, 
            'subject' => $email_subject, 
            'message' => $email_body,
            'from_name' => $from_name, 
            'from_email' => $from_email,
            'include_submission' => $include_submission
        );
        $wpdb->insert($wpdb->prefix."wpabstracts_emailtemplates", $data);
        wpabstracts_redirect('?page=wpabstracts&tab=emails');
    }else{
        wpabstracts_get_add_view('EmailTemplate', null);
    }
}

function wpabstracts_edit_template($id) {
    global $wpdb;
    if($_POST){
        $template_name = sanitize_text_field($_POST["template_name"]);
        $from_name = sanitize_text_field($_POST["from_name"]);
        $from_email = sanitize_text_field($_POST["from_email"]);
        $email_subject = sanitize_text_field($_POST["email_subject"]);
        $email_body = wp_kses_post($_POST["email_body"]);
        $include_submission = isset($_POST["include_submission"]) ? 1 : 0;
        $wpdb->show_errors();
        $data = array(
            'name' => $template_name, 
            'subject' => $email_subject, 
            'message' => $email_body,
            'from_name' => $from_name, 
            'from_email' => $from_email,
            'include_submission' => $include_submission
        );
        $where = array( 'ID' => $id);
        $wpdb->update($wpdb->prefix."wpabstracts_emailtemplates", $data, $where);
        wpabstracts_redirect('?page=wpabstracts&tab=emails');
    }else{
        $template = wpabstracts_get_edit_view('EmailTemplate', $id);
        if($template){
            echo $template;
        }else{
            wpabstracts_show_message(__('Could not locate this resource. Please try again.', 'wpabstracts'), 'alert-danger');
        }
    }
}


function wpabstracts_show_templates(){ ?>
    <div class="wpabstracts container-fluid wpabstracts-admin-container">
		<h3><?php echo apply_filters('wpabstracts_title_filter', __('Email Templates', 'wpabstracts'), 'abstracts');?>  <a href="?page=wpabstracts&tab=emails&subtab=templates&task=new" role="button" class="wpabstracts btn btn-primary"><?php _e('Add New', 'wpabstracts');?></a></h3>
	</div>
        <form id="showTemplates" method="get">
            <input type="hidden" name="page" value="wpabstracts" />
            <input type="hidden" name="tab" value="emails" />
            <input type="hidden" name="subtab" value="templates" />
            <?php
                $templates = new WPAbstracts_EmailsTemplates();
                $templates->prepare_items();
                $templates->display(); 
            ?>
        </form>
        <script>
            jQuery(document).ready( function () {
                var templates_count = '<?php echo count($templates->items);?>';
                if(templates_count > 0) {
                    jQuery('.wp-list-table').DataTable({
                        responsive: false,
                        lengthMenu: [ 50, 100, 250, 500 ]
                    });
                }
            });
        </script>
    <?php
}
