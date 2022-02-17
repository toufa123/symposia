<?php
defined('ABSPATH') or die("ERROR: You do not have permission to access this page");
if(!class_exists('WP_List_Table')){
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class WPAbstract_Reviews_Table extends WP_List_Table {

    function __construct(){
        global $status, $page;

        //Set parent defaults
        parent::__construct( array(
            'singular' => 'review', //singular name of the listed records
            'plural'    => 'reviews',    //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
        ) );

    }

	function column_cb($item) {
		return sprintf('<input type="checkbox" name="%1$s[]" value="%2$s" />', $this->_args['singular'], $item->review_id);
	}

	function column_abstract_id($item){
		global $wpdb;
        $abstract = wpabstracts_get_abstract($item->abstract_id);
		$paged = ($_POST && isset($_POST["paged"]) && intval($_POST["paged"]) > 0) ? intval($_POST["paged"]) : 1; //send paged to ajax to maintain current paged
		$actions = array(
			'edit' => '<a href="?page=wpabstracts&tab=reviews&task=edit&id=' . $item->review_id . '">' . __('Edit', 'wpabstracts') . '</a>',
			'delete' => '<a href="javascript:wpabstracts_delete_review(' . $item->review_id . ', ' . $paged .')">' . __('Delete', 'wpabstracts') . '</a>',
		);
        $filtered_abstract = sprintf('%1$s<span style="color:silver"> [%2$s]</span>%3$s', stripslashes($abstract->title), $item->abstract_id, $this->row_actions($actions));
        return apply_filters('wpabstracts_review_abstract', $filtered_abstract, $item);
    }
    
    function column_event_title($item) {
        echo stripslashes($item->event_title);
    }
    
    function column_topic($item) {
        echo $item->topic;
	}

	function column_comments($item){
		$user_info = get_userdata($item->user_id);
		if($user_info){
			$reviewer = $user_info->display_name;
		}else{
			$reviewer = __("User deleted", 'wpabstracts');
		}
        $lastUpdated = date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($item->review_date));
        $filtered_comments = sprintf('%1$s<br><span style="color:silver">' . __('Reviewed by', 'wpabstracts') . ': %2$s | %3$s</span>', stripslashes($item->comments), $reviewer, $lastUpdated);
        return apply_filters('wpabstracts_review_comments', $filtered_comments, $item);
    }
    
    function column_visibility($item) {
        if($item->visibility) {
            $visible = "Yes";
            $actions = array(
                'hide' => '<a href="?page=wpabstracts&tab=reviews&task=hide&id=' . $item->review_id . '">' . __('Hide', 'wpabstracts') . '</a>'
            );
        } else {
            $visible = "No";
            $actions = array(
                'show' => '<a href="?page=wpabstracts&tab=reviews&task=show&id=' . $item->review_id . '">' . __('Show', 'wpabstracts') . '</a>'
            );
        }       
        $filtered_visibility = sprintf('%1$s%2$s', $visible, $this->row_actions($actions));
        return apply_filters('wpabstracts_review_abstract', $filtered_visibility, $item);
    }

	function column_criteria($item){
        $criteria = array(
            __('Relevance', 'wpabstracts') => $item->relevance,
            __('Quality', 'wpabstracts') => $item->quality
        );
        $filtered_criteria = apply_filters('wpabstracts_review_criteria', $criteria, $item);
        foreach ($filtered_criteria as $name => $value) {
            echo $name . ': ' . $value . '<br>';
        }
	}

	function column_recommendation($item) {
        return apply_filters('wpabstracts_review_recommendation', $item->recommendation, $item);
	}

	function column_status($item) {
		$statuses = wpabstracts_get_statuses();
        $status = wpabstracts_map_status_name($statuses, $item->status);
        return apply_filters('wpabstracts_review_status', $status, $item);
    }
    
    function column_review_date($item) {
        $date = date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($item->review_date));
        return apply_filters('wpabstracts_review_date', $date, $item);
    }
    
    function column_attachments($item) {
        $atts = wpabstracts_get_review_attachment($item->review_id);
        if($atts){
            return sprintf('<a href="?page=wpabstracts&tab=reviews&task=download&type=review_attachment&id=' . $atts->att_id . '" "><span class="dashicons dashicons-download"></span></a>');
        }
	}

	function extra_tablenav( $which ) {
        if ($which == "top") { ?>
        <div class="wpabstracts alert alert-info">
            <i class='wpabstracts text-info glyphicon glyphicon-info-sign'></i></span>
            <span class="text-muted"><?php _e('If Sync Review Status is not enabled then the suggested status here is only a recommendation, administrators have the final decision to accept or reject submissions.', 'wpabstracts'); ?></span>
        </div>
		<?php }
	}

	function get_columns() {
		$columns = array(
			'cb' => '<input type="checkbox" />',
            'abstract_id' => __('Abstract Title', 'wpabstracts'),
            'event_title' => __('Event', 'wpabstracts'),
            'topic' => __('Topic', 'wpabstracts'),
            'comments' => __('Comments', 'wpabstracts'),
            'visibility' => __('Visible', 'wpabstracts'),
			'criteria' => __('Criteria', 'wpabstracts'),
			'recommendation' => __('Suggested Type', 'wpabstracts'),
            'status' => __('Suggested Status', 'wpabstracts'),
            'review_date' => __('Date Reviewed', 'wpabstracts')
        );
        if(get_option('wpabstracts_review_attachments')) {
            $columns['attachments'] = '';
        }
		return apply_filters('wpabstacts_review_columns', $columns);
	}

	function get_sortable_columns() {
		$sortable_columns = array();
		return $sortable_columns;
	}

	function get_bulk_actions() {
		$actions = array(
            'show' => __('Show', 'wpabstracts'),
            'hide' => __('Hide', 'wpabstracts'),
            'delete' => __('Delete', 'wpabstracts')
        );
        return apply_filters('wpabstracts_bulk_actions', $actions, 'reviews'); // actions and tab
	}

	function process_bulk_action() {
        if(isset($_GET['review']) && $_GET['review']) {
            do_action('wpabstracts_bulk_actions', $this->current_action(), 'reviews'); // action and tab 
            if ( 'delete' === $this->current_action() ) {
                foreach($_GET['review'] as $review) {
                    wpabstracts_delete_review($review, false);
                }
            }
            if ( 'show' === $this->current_action() ) {
                foreach($_GET['review'] as $review) {
                    wpabstracts_toggle_visibility($review, $visibility = 1, $message = false);
                }
            }
            if ( 'hide' === $this->current_action() ) {
                foreach($_GET['review'] as $review) {
                    wpabstracts_toggle_visibility($review, $visibility = 0, $message = false);
                }
            }
        }
	}

	function prepare_items() {
		global $wpdb, $_wp_column_headers;
		$screen = get_current_screen();
        $reviews_tbl = $wpdb->prefix."wpabstracts_reviews";
        $abstracts_tbl = $wpdb->prefix."wpabstracts_abstracts";
        $events_tbl = $wpdb->prefix."wpabstracts_events";

        $query = "SELECT review.*, abs.abstract_id, abs.event, abs.topic, evt.name as event_title FROM " . $reviews_tbl . " as review ";
        $query .="LEFT JOIN " . $abstracts_tbl . " AS abs ";
        $query .="ON review.abstract_id = abs.abstract_id ";
        $query .="LEFT JOIN " . $events_tbl . " AS evt ";
        $query .="ON evt.event_id = abs.event WHERE evt.status = 1";

		$orderby = !empty($_GET["orderby"]) ? sanitize_text_field($_GET["orderby"]) : 'review_id';
		$order = !empty($_GET["order"]) ? sanitize_text_field($_GET["order"]) : 'desc';
		if(!empty($orderby) & !empty($order)){ $query .= ' ORDER BY ' . $orderby . ' ' . $order; }
		$this->process_bulk_action();
		$this->items = $wpdb->get_results($query);
		$columns = $this->get_columns();
		$_wp_column_headers[$screen->id]=$columns;
		$hidden = array();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array($columns, $hidden, $sortable);
	}

} // end REVIEW Table class

class WPAbstract_Attachments_Table extends WP_List_Table {

    function __construct(){

        global $status, $page;

        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'attachment',     //singular name of the listed records
            'plural'    => 'attachments',    //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
        ) );

    }

    function column_filename($item){
		$paged = ($_POST && isset($_POST["paged"]) && intval($_POST["paged"]) > 0) ? intval($_POST["paged"]) : 1; //send paged to ajax to maintain current paged
        $actions = array(
            'download' => '<a href="?page=wpabstracts&tab=attachments&task=download&type=attachment&id=' . $item->attachment_id . '" ">' . __('Download', 'wpabstracts') . '</a>',
            'delete' => '<a href="javascript:wpabstracts_delete_attachment(' . $item->attachment_id . ', ' . $paged . ');">' . __('Delete', 'wpabstracts') . '</a>'
        );
        return sprintf('%1$s <span style="color:silver"></span>%2$s',$item->filename. " [" . $item->attachment_id . "]", $this->row_actions($actions));
    }

    function column_cb($item) {
        return sprintf('<input type="checkbox" name="%1$s[]" value="%2$s" />', $this->_args['singular'], $item->attachment_id);
    }

    function column_author( $item ) {
        $user = get_user_by( 'id', $item->submit_by );
        echo $user->display_name . " (" . $user->user_login . ")";
    }

    function column_default( $item, $column_name ) {
        switch( $column_name ) {
            case 'abstracts_id': echo $item->title . " [" . $item->abstract_id . "]"; break;
            case 'event': echo $item->event_name; break;
            case 'topic': echo $item->topic; break;
            case 'filetype': $filetype = wp_check_filetype($item->filename); echo $filetype['ext'];break;
            case 'filesize': echo number_format(($item->filesize/1048576), 2) . " MB"; break;
        }
    }

    function column_download($item) {
        return sprintf('<a href="?page=wpabstracts&tab=attachments&task=download&type=attachment&id=' . $item->attachment_id . '" "><span class="dashicons dashicons-download"></span></a>');
    }

    function get_columns(){
        $columns = array(
            'cb'        => '<input type="checkbox" />',
            'filename' => __('Attachment Name', 'wpabstracts'),
            'abstracts_id' => __('Abstract Title', 'wpabstracts'),
            'event' => __('Event', 'wpabstracts'),
            'topic' => __('Topic', 'wpabstracts'),
            'author' => __('Author', 'wpabstracts'),
            'filetype' => __('File Type', 'wpabstracts'),
            'filesize' => __('File Size', 'wpabstracts'),
            'download' => __('Download', 'wpabstracts')
        );
        return $columns;
    }

    /**
     *
     * @return array
     */
    function get_sortable_columns() {
        $sortable_columns = array();
        return $sortable_columns;
    }

    function get_bulk_actions() {
        $actions = array(
            'download' => __('Download', 'wpabstracts'),
            'delete' => __('Delete', 'wpabstracts')
        );
        return apply_filters('wpabstracts_bulk_actions', $actions, 'attachments'); // actions and tab
    }

    function process_bulk_action() {
        if(isset($_GET['attachment']) && $_GET['attachment']) {
            do_action('wpabstracts_bulk_actions', $this->current_action(), 'attachments'); // action and tab 
            if ('download'=== $this->current_action() ) {
                // bulk downloads are handled in the downloads.php file due to 'headers already sent' limitations 
            }
            else if ('delete'=== $this->current_action() ) {
                foreach($_GET['attachment'] as $attachment) {
                    wpabstracts_delete_attachment($attachment, false);
                }
            }
        }
    }

    function get_search_box() { ?>
        <label>Search Attachments</label>
        <input type="search" class="wpabstracts form-control" id="abstract_search" placeholder="title or file name" name="s" value="<?php _admin_search_query(); ?>" />
        <?php submit_button( __('Search', 'wpabstracts'), 'secondary', false, false ); ?>
    <?php
    }

    function get_export_btn() { ?>
		<span class="wpabstracts wpabstracts-admin-container">
			<a href="?page=wpabstracts&tab=attachments&task=download&type=zip" class="wpabstracts btn btn-primary">
				<?php _e('Download All', 'wpabstracts');?>
				<i class="wpabstracts glyphicon glyphicon-cloud-download"></i> 
			</a>
		</span>
		<?php
	}

    function extra_tablenav( $which ) {
        if ( $which == "top" ){
            $this->get_export_btn();
            //$this->get_search_box();
        }
    }

    function prepare_items() {
        global $wpdb, $_wp_column_headers;
	    $screen = get_current_screen();
        $attachments_tbl = $wpdb->prefix."wpabstracts_attachments";
        $abstracts_tbl = $wpdb->prefix."wpabstracts_abstracts";
        $events_tbl = $wpdb->prefix."wpabstracts_events";
        $query = "SELECT atts.attachment_id, atts.abstracts_id, atts.filename, atts.filesize, abs.abstract_id, abs.title, abs.event, abs.topic, abs.submit_by, event.name as event_name FROM " . $attachments_tbl . " AS atts ";
        $query .="LEFT JOIN " . $abstracts_tbl . " AS abs ";
        $query .="ON atts.abstracts_id = abs.abstract_id ";
        $query .="LEFT JOIN " . $events_tbl . " AS event ";
        $query .="ON event.event_id = abs.event WHERE event.status = 1";
        $orderby = !empty($_GET["orderby"]) ? sanitize_text_field($_GET["orderby"]) : 'attachment_id';
        $order = !empty($_GET["order"]) ? sanitize_text_field($_GET["order"]) : 'desc';
        if(!empty($orderby) & !empty($order)){ $query.=' ORDER BY '.$orderby.' '.$order; }
        $this->process_bulk_action();
        $this->items = $wpdb->get_results($query);
        $columns = $this->get_columns();
        $_wp_column_headers[$screen->id]=$columns;
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
    } // end prepare items

} // end Event Table class

class WPAbstract_singleItem_Reviews_Table extends WP_List_Table {


    function __construct($id){

        global $status, $page;


        //Set parent defaults
        parent::__construct( array(
            'singular' => __('review', 'wpabstracts'), //singular name of the listed records
            'plural' => __('reviews', 'wpabstracts'), //plural name of the listed records
            'ajax'      => false,        //does this table support ajax?
            'id' => $id
        ) );

    }

    function column_abstract_id($item){
		global $wpdb;
		$paged = ($_POST && isset($_POST["paged"]) && intval($_POST["paged"]) > 0) ? intval($_POST["paged"]) : 1; //send paged to ajax to maintain current paged
		$actions = array(
			'edit' => '<a href="?page=wpabstracts&tab=reviews&task=edit&id=' . $item->review_id . '">' . __('Edit', 'wpabstracts') . '</a>',
			'delete' => '<a href="javascript:wpabstracts_delete_review(' . $item->review_id . ', ' . $paged .')">' . __('Delete', 'wpabstracts') . '</a>',
		);
        $filtered_abstract = sprintf('%1$s<span style="color:silver"> [%2$s]</span>%3$s', $item->title, $item->abstract_id, $this->row_actions($actions));
        return apply_filters('wpabstracts_review_abstract', $filtered_abstract, $item);
    }

    function column_event_name($item) {
        echo $item->event_name . " / " . $item->topic;
	}

    function column_comments($item){
		$user_info = get_userdata($item->user_id);
		if($user_info){
			$reviewer = $user_info->display_name;
		}else{
			$reviewer = __("User deleted", 'wpabstracts');
		}
        $lastUpdated = date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($item->review_date));
        $filtered_comments = sprintf('%1$s<br><span style="color:silver">' . __('Reviewed by', 'wpabstracts') . ': %2$s | %3$s</span>', stripslashes($item->comments), $reviewer, $lastUpdated);
        return apply_filters('wpabstracts_review_comments', $filtered_comments, $item);
	}

    function column_cb($item) {
        return sprintf('<input type="checkbox" name="%1$s[]" value="%2$s" />', $this->_args['singular'], $item->review_id);
    }

    function column_recommendation($item) {
        return apply_filters('wpabstracts_review_recommendation', $item->recommendation, $item);
	}

	function column_status($item) {
		$statuses = wpabstracts_get_statuses();
        $status = wpabstracts_map_status_name($statuses, $item->status);
        return apply_filters('wpabstracts_review_status', $status, $item);
    }
    
    function column_review_date($item) {
        $date = date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($item->review_date));
        return apply_filters('wpabstracts_review_date', $date, $item);
	}
     
    function column_criteria($item){
        $criteria = array(
            __('Relevance', 'wpabstracts') => $item->relevance,
            __('Quality', 'wpabstracts') => $item->quality
        );
        $filtered_criteria = apply_filters('wpabstracts_review_criteria', $criteria, $item);
        foreach ($filtered_criteria as $name => $value) {
            echo $name . ': ' . $value . '<br>';
        }
    }
    
	function get_columns() {
		$columns = array(
			'cb' => '<input type="checkbox" />',
            'abstract_id' => __('Abstract Title', 'wpabstracts'),
            'event_name' => __('Event / Topic', 'wpabstracts'),
			'comments' => __('Comments', 'wpabstracts'),
			'criteria' => __('Criteria', 'wpabstracts'),
			'recommendation' => __('Suggested Type', 'wpabstracts'),
            'status' => __('Suggested Status', 'wpabstracts'),
            'review_date' => __('Date Reviewed', 'wpabstracts')
		);
		return apply_filters('wpabstacts_review_columns', $columns);
	}

    function get_sortable_columns() {
        $sortable_columns = array(
            'title'     => array('title',false),
            'status'    => array('status',false),
            'recommendaton'    => array('recommendaton',false)
        );
        return $sortable_columns;
    }

    function get_bulk_actions() {
        $actions = array(
            'delete' => __('Delete', 'wpabstracts')
        );
        return apply_filters('wpabstracts_bulk_actions', $actions, 'review'); // actions and tab
    }

    function process_bulk_action() {
        if(isset($_GET['review']) && $_GET['review']) {
            do_action('wpabstracts_bulk_actions', $this->current_action(), 'review'); // action and tab 
            if ( 'delete'=== $this->current_action() ) {
                foreach($_GET['review'] as $review) {
                    wpabstracts_delete_review($review, false);
                }
            }
        }
    }

    function prepare_items() {
        global $wpdb, $aID, $_wp_column_headers;
        $abstract_id = intval($this->_args['id']);
		$screen = get_current_screen();
        $reviews_tbl = $wpdb->prefix."wpabstracts_reviews";
        $abstracts_tbl = $wpdb->prefix."wpabstracts_abstracts";
        $events_tbl = $wpdb->prefix."wpabstracts_events";
        $query = "SELECT review.*, abs.abstract_id, abs.title, abs.event, abs.topic, evt.name as event_name FROM " . $reviews_tbl  . " as review";
        $query .=" JOIN " . $abstracts_tbl . " AS abs";
        $query .=" ON review.abstract_id = abs.abstract_id AND review.abstract_id = " . $abstract_id;
        $query .=" LEFT JOIN " . $events_tbl . " AS evt";
        $query .=" ON evt.event_id = abs.event AND evt.status = 1";
        $orderby = !empty($_GET["orderby"]) ? sanitize_text_field($_GET["orderby"]) : 'review.review_id';
        $order = !empty($_GET["order"]) ? sanitize_text_field($_GET["order"]) : 'desc';
        if(!empty($orderby) & !empty($order)){ $query.=' ORDER BY '.$orderby.' '.$order; }
        $totalitems = $wpdb->get_var("SELECT COUNT(*) FROM " . $reviews_tbl . " WHERE `abstract_id` = " . $abstract_id); // get  the total number of rows
        $perpage = 35;
        $paged = !empty($_GET["paged"]) ? intval($_GET["paged"]) : '';
        if(empty($paged) || !is_numeric($paged) || $paged<=0 ){ $paged=1; }
        $totalpages = ceil($totalitems/$perpage);
        if(!empty($paged) && !empty($perpage)){
            $offset=($paged-1)*$perpage;
            $query.=' LIMIT '.(int)$offset.','.(int)$perpage;
        }
        $this->set_pagination_args( array(
            "total_items" => $totalitems,
            "total_pages" => $totalpages,
            "per_page" => $perpage,
        ) );

        $this->process_bulk_action();
        $this->items = $wpdb->get_results($query);

        $columns = $this->get_columns();
        $_wp_column_headers[$screen->id]=$columns;
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);

    } // end prepare items

} // end REVIEW Table class

class WPAbstract_Events_Table extends WP_List_Table {

    function __construct(){

        global $status, $page;

        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'event',     //singular name of the listed records
            'plural'    => 'events',    //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
        ) );

    }

    function column_name($item){
        $actions = array(
            'edit' => '<a href="?page=wpabstracts&tab=events&task=edit&id=' . $item->event_id . '">' . __('Edit', 'wpabstracts') . '</a>',
            'delete' => '<a href="javascript:wpabstracts_delete_event(' . $item->event_id . ',`' . wp_create_nonce("delete-event-".$item->event_id) . '`);">' . __('Delete', 'wpabstracts') . '</a>'
        );
        if($item->status == null || $item->status == '1') { 
            $actions['archive'] = '<a href="?page=wpabstracts&tab=events&task=archive&id=' . $item->event_id . '">' . __('Archive', 'wpabstracts') . '</a>';
        }
        if($item->status == '-1') {
            $actions['unarchive'] = '<a href="?page=wpabstracts&tab=events&task=unarchive&id=' . $item->event_id . '">' . __('Unarchive', 'wpabstracts') . '</a>';
        }
        return sprintf('%1$s <span style="color:silver">[ID: %2$s]</span>%3$s',$item->name, $item->event_id, $this->row_actions($actions));
    }

    function column_cb($item) {
        return sprintf('<input type="checkbox" name="%1$s[]" value="%2$s" />', $this->_args['singular'], $item->event_id);
    }

    function column_shortcode($item){
        $shortcode = "[wpabstracts event_id=" . $item->event_id . "]";
        $actions = array(
            'copy' => '<a href="javascript:wpabstracts_copy_to_clipboard(\'' . $shortcode . '\')">' . __('Copy', 'wpabstracts') . '</a>'
        );
        return sprintf($shortcode . ' %1$s', $this->row_actions($actions));
    }

    function column_count($item){
        global $wpdb;
        $count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}wpabstracts_abstracts WHERE `event` = " . $item->event_id);
        echo apply_filters('wpabstracts_event_submissions', $count, $item); 
    }

     function column_default( $item, $column_name ) {
        switch( $column_name ) {
          case 'host': echo stripslashes($item->$column_name); break;
          case 'topics': echo stripslashes($item->$column_name); break;
          case 'start_date': echo stripslashes($item->$column_name); break;
          case 'end_date': echo stripslashes($item->$column_name); break;
          case 'deadline': echo stripslashes($item->$column_name); break;
        }
    }

    function column_status($item){
        switch($item->status){
            case '1':
            $status = 'Active'; break;
            case '-1':
            $status = 'Archived'; break;
            default:
            $status = 'Active'; 
        }
        echo apply_filters('wpabstracts_event_status', $status, $item); 
    }

    function get_columns(){
        $columns = array(
            'cb'        => '<input type="checkbox" />',
            'name' => __('Event Name', 'wpabstracts'),
            'shortcode' => __('Shortcode', 'wpabstracts'),
            'status' => __('Status', 'wpabstracts'),
            'host' => __('Host', 'wpabstracts'),
            'topics' => __('Topics', 'wpabstracts'),
            'start_date' => __('Start Date', 'wpabstracts'),
            'end_date' => __('End Date', 'wpabstracts'),
            'deadline' => __('Deadline', 'wpabstracts'),
            'count' => __('Submissions', 'wpabstracts'),
        );
        return $columns;
    }

    function get_sortable_columns() {
        $sortable_columns = array();
        return $sortable_columns;
    }

    function get_bulk_actions() {
        $actions = array(
            'archive' => __('Archive', 'wpabstracts'),
            'unarchive' => __('Unarchive', 'wpabstracts')
        );
        return apply_filters('wpabstracts_bulk_actions', $actions, 'events'); // actions and tab
    }

    function process_bulk_action() {
        if(isset($_GET['event']) && $_GET['event']){
            do_action('wpabstracts_bulk_actions', $this->current_action(), 'events'); // action and tab 
            if ('archive' === $this->current_action() ) {
                foreach($_GET['event'] as $event_id) {
                    wpabstracts_event_status($event_id, '-1', false);
                }
                $event = count($_GET['event']) > 1 ? 'events were' : 'event was';
                wpabstracts_show_message("Your $event was successfully archived.", 'alert-success');
            }
            if ('unarchive' === $this->current_action() ) {
                foreach($_GET['event'] as $event_id) {
                    wpabstracts_event_status($event_id, '1', false);
                }
                $event = count($_GET['event']) > 1 ? 'events were' : 'event was';
                wpabstracts_show_message("Your $event was successfully unarchived.", 'alert-success');
            }
        }
    }

    function prepare_items() {
        global $wpdb, $_wp_column_headers;
		$screen = get_current_screen();
        $events_tbl = $wpdb->prefix."wpabstracts_events";
        $query = "SELECT * FROM " . $events_tbl;
        $orderby = !empty($_GET["orderby"]) ? sanitize_text_field($_GET["orderby"]) : 'event_id';
        $order = !empty($_GET["order"]) ? sanitize_text_field($_GET["order"]) : 'desc';
        if(!empty($orderby) & !empty($order)){ $query.=' ORDER BY '.$orderby.' '.$order; }
        $this->process_bulk_action();
        $this->items = $wpdb->get_results($query);
        $columns = $this->get_columns();
        $_wp_column_headers[$screen->id]=$columns;
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
    } // end prepare items

} // end Event Table class

class WPAbstracts_EmailsTemplates extends WP_List_Table {

    function __construct(){
        global $status, $page;

        //Set parent defaults
        parent::__construct( array(
            'singular' => 'template', //singular name of the listed records
            'plural'    => 'templates',    //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
        ) );
    }

    function column_name($item){
        global $wpdb;
        $actions = array(
            'edit' => '<a href="?page=wpabstracts&tab=emails&task=edit&id=' . $item->ID . '">' . __('Edit', 'wpabstracts') . '</a>',
            'delete' => '<a href="javascript:wpabstracts_delete_template(' . $item->ID . ')">' . __('Delete', 'wpabstracts') . '</a>'
        );
        return sprintf('%1$s<span style="color:silver"> [%2$s]</span>%3$s', $item->name, $item->ID, $this->row_actions($actions));
    }

    function column_cb($item) {
        return sprintf('<input type="checkbox" name="%1$s[]" value="%2$s" />', $this->_args['singular'], $item->ID);
    }

    function column_default( $item, $column_name ) {
        switch( $column_name ) {
            case 'id': echo $item->$column_name; break;
            case 'name': _e($item->$column_name, 'wpabstracts');break;
            case 'subject': _e($item->$column_name, 'wpabstracts');break;
            case 'from_name': _e($item->$column_name, 'wpabstracts');break;
            case 'from_email': _e($item->$column_name, 'wpabstracts');break;
        }
    }

    function get_columns(){
        $columns = array(
            'cb'        => '<input type="checkbox" />',
            'name' => __('Email Template', 'wpconference'),
            'subject' => __('Subject', 'wpconference'),
            'from_name' => __('From Name', 'wpconference'),
            'from_email' => __('From Email', 'wpconference')
        );
        return $columns;
    }

    function get_sortable_columns() {
        $sortable_columns = array();
        return $sortable_columns;
    }

    function get_bulk_actions() {
        $actions = array(
            'delete' => __('Delete', 'wpabstracts')
        );
        return apply_filters('wpabstracts_bulk_actions', $actions, 'emailtemplates'); // actions and tab
    }

    function process_bulk_action() {
        if(isset($_GET['template']) && $_GET['template']) {
            do_action('wpabstracts_bulk_actions', $this->current_action(), 'emailtemplates'); // action and tab 
            if ( 'delete'=== $this->current_action() ) {
                foreach($_GET['template'] as $event) {
                    wpabstracts_delete_email_template($event, false);
                }
                wpabstracts_show_message("Successfully deleted selected templates.", 'alert-success');
            }
        }
    }

    function prepare_items() {
        global $wpdb, $_wp_column_headers;
		$screen = get_current_screen();
        $templates_tbl = $wpdb->prefix."wpabstracts_emailtemplates";
        $query = "SELECT * FROM " . $templates_tbl;
        $orderby = !empty($_GET["orderby"]) ? sanitize_text_field($_GET["orderby"]) : 'ID';
        $order = !empty($_GET["order"]) ? sanitize_text_field($_GET["order"]) : 'desc';
        if(!empty($orderby) & !empty($order)){ $query.=' ORDER BY '.$orderby.' '.$order; }
        $this->process_bulk_action();
        $this->items = $wpdb->get_results($query);
        /* -- Register the Columns -- */
        $columns = $this->get_columns();
        $_wp_column_headers[$screen->id]=$columns;
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
    } // end prepare items

} // end
