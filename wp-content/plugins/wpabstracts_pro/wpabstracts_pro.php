<?php
/*
Plugin Name: WP Abstracts Pro
Plugin URI: http://www.wpabstracts.com
Description: Manage abstracts submissions on your site. Everything from events, abstracts, authors, reviews, attachments, notifications and more.
Version: 2.3.1
Author: Kevon Adonis
Author URI: http://www.kevonadonis.com
*/
defined('ABSPATH') or die("ERROR: You do not have permission to access this page");
define('WPABSTRACTS_ACCESS_LEVEL', 'manage_options');
define('WPABSTRACTS_PLUGIN_DIR', dirname(__FILE__).'/');
define('WPABSTRACTS_VERSION', '2.3.1');
define('WPABSTRACTS_SECRET_KEY', '5a22d6e80bf870.68089106');
define('WPABSTRACTS_SERVER_URL', 'https://www.wpabstracts.com');
define('WPABSTRACTS_REFERENCE', 'WP Abstracts Pro');
register_activation_hook(__FILE__, 'wpabstracts_install_init');

if (isset($_GET['page']) && ($_GET['page'] == 'wpabstracts')){
	add_action('admin_head', 'wpabstracts_loadFormBuildJS');
	add_action('admin_head', 'wpabstracts_loadJS');
	add_action('admin_init', 'wpabstracts_loadCSS');
	add_action('admin_init', 'wpabstracts_editor_admin_init');
	add_action('tiny_mce_before_init', 'wpabstracts_editor_init');
}

if(isset($_REQUEST['action']) && ($_REQUEST['action']=='loadtopics' || $_REQUEST['action']=='wpa_login')){
	do_action( 'wp_ajax_' . $_REQUEST['action'] );
}

add_action('init', 'wpabstracts_init');
function wpabstracts_init() {
	load_plugin_textdomain('wpabstracts', false, dirname(plugin_basename(__FILE__)) . '/languages/');
	include_once( WPABSTRACTS_PLUGIN_DIR . 'inc/wpabstracts_functions.php' );
	include_once(apply_filters('wpabstracts_page_include', WPABSTRACTS_PLUGIN_DIR . 'inc/wpabstracts_downloads.php'));
}

add_action('admin_menu', 'wpabstracts_admin_menu');
function wpabstracts_admin_menu(){
	$page_title = __('WP Abstracts Pro', 'wpabstracts');
	add_menu_page( $page_title, $page_title, 'manage_options', 'wpabstracts', 'wpabstracts_admin_dashboard', plugins_url( 'images/icon.png', __FILE__), 99 );
	$submenus = array(
		array('page_title' => $page_title, 'menu_name' => apply_filters('wpabstracts_title_filter', __('Events', 'wpabstracts'), 'events'), 'capability' => 'manage_options', 'url' => 'admin.php?page=wpabstracts&tab=events'),
		array('page_title' => $page_title, 'menu_name' => apply_filters('wpabstracts_title_filter', __('Abstracts', 'wpabstracts'), 'abstracts'), 'capability' => 'manage_options', 'url' => 'admin.php?page=wpabstracts&tab=abstracts'),
		array('page_title' => $page_title, 'menu_name' => apply_filters('wpabstracts_title_filter', __('Reviews', 'wpabstracts'), 'reviews'), 'capability' => 'manage_options', 'url' => 'admin.php?page=wpabstracts&tab=reviews'),
		array('page_title' => $page_title, 'menu_name' => apply_filters('wpabstracts_title_filter', __('Attachments', 'wpabstracts'), 'attachments'), 'capability' => 'manage_options', 'url' => 'admin.php?page=wpabstracts&tab=attachments'),
		array('page_title' => $page_title, 'menu_name' => apply_filters('wpabstracts_title_filter', __('Users', 'wpabstracts'), 'users'), 'capability' => 'manage_options', 'url' => 'admin.php?page=wpabstracts&tab=users'),
		array('page_title' => $page_title, 'menu_name' => apply_filters('wpabstracts_title_filter', __('Emails', 'wpabstracts'), 'emails'), 'capability' => 'manage_options', 'url' => 'admin.php?page=wpabstracts&tab=emails'),
		array('page_title' => $page_title, 'menu_name' => apply_filters('wpabstracts_title_filter', __('Reports', 'wpabstracts'), 'reports'), 'capability' => 'manage_options', 'url' => 'admin.php?page=wpabstracts&tab=reports'),
		array('page_title' => $page_title, 'menu_name' => apply_filters('wpabstracts_title_filter', __('Settings', 'wpabstracts'), 'settings'), 'capability' => 'manage_options', 'url' => 'admin.php?page=wpabstracts&tab=settings'),
		array('page_title' => $page_title, 'menu_name' => apply_filters('wpabstracts_title_filter', __('License', 'wpabstracts'), 'license'), 'capability' => 'manage_options', 'url' => 'admin.php?page=wpabstracts&tab=license'),
		array('page_title' => $page_title, 'menu_name' => apply_filters('wpabstracts_title_filter', __('Help', 'wpabstracts'), 'help'), 'capability' => 'manage_options', 'url' => 'admin.php?page=wpabstracts&tab=help'),
	);

	$filter_menus = apply_filters('wpabstracts_menu_filter', $submenus);

	foreach($filter_menus as $submenu){
		add_submenu_page( 'wpabstracts',  $submenu['page_title'], $submenu['menu_name'], $submenu['capability'], $submenu['url'] );
	}
	remove_submenu_page('wpabstracts', 'wpabstracts');
}

add_shortcode('wpabstracts', 'wpabstracts_dashboard_shortcode');
function wpabstracts_dashboard_shortcode($atts) {
	wpabstracts_loadCSS(); //load css only on dashboard pages
	wpabstracts_loadJS($frontend=true);  //load js only on dashboard pages
	add_action('tiny_mce_before_init', 'wpabstracts_editor_init'); //  only load wpabstracts_updateWordCount on wpabstract pages
	add_filter('edit_post_link', '__return_false' ); // remove edit page link from dashboard
	do_action('wpabstracts_shortcode_action');
	$args = array('event_id' => 0); // shortcode args with defaults
	$a = shortcode_atts($args, $atts);
	$event_id = intval($a['event_id']); // if an event id was enter, make it available to dashboard
	ob_start();
	$dashboard = apply_filters('wpabstracts_page_include', 'html/wpabstracts_dashboard.php');
	include_once($dashboard);
	$html = ob_get_contents();
	ob_end_clean();
	return $html;
}

add_shortcode('wpabstracts_register', 'wpabstracts_register_shortcode');
function wpabstracts_register_shortcode($atts) {
	wpabstracts_loadCSS(); //load css only on dashboard pages
	wpabstracts_loadJS($frontend=true);  //load js only on dashboard pages
	wpabstracts_loadFormBuildJS();
	do_action('wpabstracts_shortcode_action');
	$html = wpabstracts_user_getview("register", false);
	return $html;
}

add_shortcode('wpabstracts_login', 'wpabstracts_login_shortcode');
function wpabstracts_login_shortcode($atts) {
	wpabstracts_loadCSS(); //load css only on dashboard pages
	wpabstracts_loadJS($frontend=true);  //load js only on dashboard pages
	do_action('wpabstracts_shortcode_action');
	wpabstracts_get_login();
}

add_action('admin_init', 'wpabstracts_disable_dashboard');
function wpabstracts_disable_dashboard() {
	if(!get_option('wpabstracts_frontend_dashboard')) {
		if (is_admin() && !current_user_can('administrator') && !(defined('DOING_AJAX') && DOING_AJAX)) {
			wp_redirect( home_url() );
			exit;
		}
	}
}

add_filter('show_admin_bar', 'wpabstracts_disable_adminbar');
function wpabstracts_disable_adminbar() {
	if(is_user_logged_in() && get_option('wpabstracts_show_adminbar')){
		return true;
	}
	return false;
}

add_filter('plugin_row_meta', 'wpabstracts_plugin_links', 10, 2);
function wpabstracts_plugin_links($links, $file) {
	if ($file == plugin_basename(__FILE__)) {
		$links[] = '<a href="http://www.wpabstracts.com/customize" target="_blank">' . __('Customization', 'wpabstracts') . '</a>';
		$links[] = '<a href="http://www.wpabstracts.com/support" target="_blank">' . __('Support', 'wpabstracts') . '</a>';
	}
	return $links;
}

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'wpabstracts_plugin_actionlinks');
function wpabstracts_plugin_actionlinks($links) {
	$links[] = '<a href="' . esc_url(get_admin_url(null, 'admin.php?page=wpabstracts&tab=settings')) . '">Settings</a>';
	return $links;
}

function wpabstracts_admin_header(){
	$header = '<div class="wpabstracts container-fluid">' .
	'<div class="wpabstracts row">' .
	'<div class="wpabstracts logo col-xs-12">' .
	'<a href="?page=wpabstracts&tab=abstracts"><img src="'. plugins_url("images/admin_logo.png", __FILE__) . '"></a>' .
	'<span style="vertical-align: middle; font-size: 12px; color: #44648A;"> Pro v' . WPABSTRACTS_VERSION . '</span>' .
	'</div></div></div>';
	echo apply_filters('wpabstracts_admin_header', $header);
}

function wpabstracts_admin_dashboard() {
	global $pagenow;

	if ( $pagenow == 'admin.php' && $_GET['page'] == 'wpabstracts' ){

		$tab = isset($_GET['tab']) ? $_GET['tab'] : 'abstracts';

		wpabstracts_admin_header();
		wpabstracts_admin_tabs($tab);

		switch ($tab){
			case 'abstracts' :
			$page = 'wpabstracts_abstracts.php';
			break;
			case 'events' :
			$page =  'wpabstracts_events.php';
			break;
			case 'reviews' :
			$page = 'wpabstracts_reviews.php';
			break;
			case 'attachments' :
			$page = 'wpabstracts_attachments.php';
			break;
			case 'users' :
			$page = 'wpabstracts_users.php';
			break;
			case 'reports' :
			$page = 'wpabstracts_reports.php';
			break;
			case 'settings' :
			$page = 'wpabstracts_settings.php';
			break;
			case 'emails' :
			$page = 'wpabstracts_emails.php';
			break;
			case 'help' :
			$page = 'wpabstracts_help.php';
			break;
			case 'license' :
			$page = 'wpabstracts_license.php';
			break;
			default:
			$page = 'wpabstracts_abstracts.php';
		}

		$page_url = apply_filters('wpabstracts_page_include', $page);

		ob_start();
		include_once($page_url);
		$html = ob_get_contents();
		ob_end_clean();
		echo apply_filters('wpabstracts_admin_pages', $html, $tab);

	}

}

function wpabstracts_admin_tabs( $current = 'abstracts' ) {
	$basic_tabs = array(
		'events' => apply_filters('wpabstracts_title_filter', '<i class="wpabstracts glyphicon glyphicon-calendar"></i> ' . __('Events', 'wpabstracts'), 'events'),
		'abstracts' => apply_filters('wpabstracts_title_filter', '<i class="wpabstracts glyphicon glyphicon-th-list"></i> ' . __('Abstracts', 'wpabstracts'), 'abstracts'),
		'reviews' => apply_filters('wpabstracts_title_filter', '<i class="wpabstracts glyphicon glyphicon-star"></i> ' . __('Reviews', 'wpabstracts'), 'reviews'),
		'attachments' => apply_filters('wpabstracts_title_filter', '<i class="wpabstracts glyphicon glyphicon-paperclip"></i> ' . __('Attachments', 'wpabstracts'), 'attachments'),
		'users' => apply_filters('wpabstracts_title_filter', '<i class="wpabstracts glyphicon glyphicon-user"></i> ' . __('Users', 'wpabstracts'), 'users'),
		'emails' => apply_filters('wpabstracts_title_filter', '<i class="wpabstracts glyphicon glyphicon-envelope"></i> ' . __('Emails', 'wpabstracts'), 'emails'),
		'reports' => apply_filters('wpabstracts_title_filter', '<i class="wpabstracts glyphicon glyphicon-stats"></i> ' . __('Reports', 'wpabstracts'), 'reports')
	);

	$tabs = apply_filters('wpabstracts_admin_tabs', $basic_tabs);
	$tabs['settings'] = apply_filters('wpabstracts_title_filter', '<i class="wpabstracts glyphicon glyphicon-cog"></i> ' . __('Settings', 'wpabstracts'), 'settings');
	$tabs['license'] = apply_filters('wpabstracts_title_filter', '<i class="wpabstracts glyphicon glyphicon-leaf"></i> ' . __('License', 'wpabstracts'), 'license');
	$tabs['help'] = apply_filters('wpabstracts_title_filter', '<i class="wpabstracts glyphicon glyphicon-question-sign"></i> ' . __('Help', 'wpabstracts'), 'help');

	$top_menu = '<div class="wpabstracts container-fluid">';
	$top_menu .= '<ul class="wpabstracts nav nav-tabs">';
	foreach( $tabs as $tab => $name ){
		$class = ( $tab == $current ) ? "wpabstracts active" : "";
		$top_menu .= "<li role='presentation' class='".$class."'><a href='?page=wpabstracts&tab=$tab'><strong>$name</strong></a></li>";
	}
	$top_menu .= '</ul>';
	$top_menu .= '</div>';
	echo $top_menu;
}

function wpabstracts_install_init($network_wide) {
	global $wpdb;
	
	if ($network_wide) {
	  if (function_exists('get_sites') && function_exists('get_current_network_id')){
		$site_ids = get_sites( array( 'fields' => 'ids', 'network_id' => get_current_network_id() ) );
	  } else {
		$site_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs WHERE site_id = $wpdb->siteid;" );
	  }
	  
	  foreach ($site_ids as $site_id) {
		switch_to_blog($site_id);
		wpabstracts_install();
		restore_current_blog();
	  }

	} else {
		wpabstracts_install();
	}
}

function wpabstracts_install() {

	// init tables
	wpabstracts_init_db_tables();

	// init wpa options
	wpabstracts_init_options();
	
	// init custom titles
	wpabstracts_init_custom_titles();

	// install email templates
	wpabstracts_init_email_templates();

	// setup status if not inserted
	global $wpdb;
	$wpdb->show_errors();
	$statusesExist = $wpdb->get_var("SELECT COUNT(*) FROM " . $wpdb->prefix."wpabstracts_statuses");
	if(!$statusesExist){
		$statuses = array('Pending', 'Under Review', 'Accepted', 'Rejected');
		foreach($statuses as $key => $status) {
			$data = array('name' => $status, 'template_id' => $key + 1);
			$wpdb->insert($wpdb->prefix."wpabstracts_statuses", $data);
		}
	}

	// enable admin notification for admins that was never managed
	$admins = get_users(array('role'=>'administrator', 'fields' => array('ID')));
	foreach($admins as $admin){
		if (!metadata_exists('user', $admin->ID, 'wpabstracts_enable_notification')) {
			update_user_meta($admin->ID, 'wpabstracts_enable_notification', 1);
		}
	}

	// run users module installation
	wpabstracts_users_install();

	// if wpabstracts was previously installed, check version and run upgrade if needed
	if(get_option("wpabstracts_version")){
		wpabstracts_version_check();
	} else{
		add_option("wpabstracts_version", WPABSTRACTS_VERSION);
	}

}

function wpabstracts_init_db_tables() {
	global $wpdb;
	$wpdb->show_errors();
	require_once(ABSPATH.'wp-admin/includes/upgrade.php');
	$charset_collate = $wpdb->get_charset_collate();

	// setup abstracts table
	$abs_tbl = $wpdb->prefix."wpabstracts_abstracts";
	$abs_sql = "CREATE TABLE " . $abs_tbl . " (
		`abstract_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
		`title` text,
		`text` longtext,
		`event` int(11),
		`topic` text,
		`status` int(11),
		`author` text,
		`author_email` text,
		`author_affiliation` text,
		`presenter` varchar(255),
		`presenter_email` varchar(255),
		`presenter_preference` varchar(255),
		`keywords` text,
		`submit_by` int(11),
		`submit_date` datetime,
		`modified_date` datetime,
		PRIMARY KEY (abstract_id)
	) $charset_collate;";
	dbDelta($abs_sql);

	// setup reviews table
	$reviews_tbl = $wpdb->prefix."wpabstracts_reviews";
	$reviews_sql = "CREATE TABLE " . $reviews_tbl . " (
		`review_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
		`abstract_id` int(11),
		`user_id` int(11),
		`status` int(11),
		`relevance` varchar(255),
		`quality` varchar(255),
		`comments` text,
		`recommendation` varchar(255),
		`review_date` datetime,
		`modified_date` datetime,
		`visibility` tinyint(1),
		PRIMARY KEY (review_id)
	) $charset_collate;";
	dbDelta($reviews_sql);

	// setup reviewers table
	$reviewers_tbl = $wpdb->prefix."wpabstracts_reviewers";
	$reviewers_sql = "CREATE TABLE " . $reviewers_tbl . " (
		`reviewer_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
		`abs_id` int(11),
		`user_id` int(11),
		PRIMARY KEY (reviewer_id)
	) $charset_collate;";
	dbDelta($reviewers_sql);

	// setup events Table
	$events_tbl = $wpdb->prefix."wpabstracts_events";
	$events_sql = "CREATE TABLE " . $events_tbl . " (
		`event_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
		`name` varchar(255),
		`description` longtext,
		`address` longtext,
		`host` varchar(255),
		`topics` text,
		`start_date` date,
		`end_date` date,
		`deadline` date,
		`status` tinyint(1) DEFAULT 1,
		PRIMARY KEY  (event_id)
	) $charset_collate;";
	dbDelta($events_sql);

	// setup attachment table
	$atts_tbl = $wpdb->prefix."wpabstracts_attachments";
	$atts_sql = "CREATE TABLE " . $atts_tbl . " (
		`attachment_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
		`abstracts_id` int(11),
		`filecontent` longblob,
		`filename` varchar(255),
		`filetype` varchar(255),
		`filesize` varchar(255),
		`format` tinyint(1),
		`status` tinyint(1),
		PRIMARY KEY  (attachment_id)
	) $charset_collate;";
	dbDelta($atts_sql);

	// setup email templates table
	$email_tbl = $wpdb->prefix."wpabstracts_emailtemplates";
	$email_sql = "CREATE TABLE " . $email_tbl . " (
		`ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
		`name` varchar(255),
		`subject` varchar(255),
		`message` text,
		`from_name` varchar(255),
		`from_email` varchar(255),
		`receiver` varchar(255),
		`include_submission` tinyint(1) DEFAULT 0,
		`status` tinyint(1),
		PRIMARY KEY (ID)
	) $charset_collate;";
	dbDelta($email_sql);

	// setup status table - Since 1.8.0
	$status_tbl = $wpdb->prefix."wpabstracts_statuses";
	$status_sql = "CREATE TABLE " . $status_tbl . " (
		`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
		`name` varchar(255) NOT NULL,
		`order` int(11) DEFAULT NULL,
		`template_id` int(11),
		`status` TINYINT(1) NOT NULL DEFAULT 1,
		PRIMARY KEY (id)
	) $charset_collate;";
	dbDelta($status_sql);

	// setup review attachment table
	$atts_tbl = $wpdb->prefix."wpabstracts_review_attachments";
	$atts_sql = "CREATE TABLE " . $atts_tbl . " (
		`att_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
		`review_id` int(11),
		`abs_id` int(11),
		`filecontent` longblob,
		`filename` varchar(255),
		`filetype` varchar(255),
		`filesize` varchar(255),
		`format` tinyint(1),
		`status` tinyint(1),
		PRIMARY KEY  (att_id)
	) $charset_collate;";
	dbDelta($atts_sql);

}

function wpabstracts_init_options() {
	// set default settings
	add_option("wpabstracts_chars_count", 250);
	add_option("wpabstracts_upload_limit", 3);
	add_option("wpabstracts_max_attach_size", 2048000);
	add_option('wpabstracts_author_instructions', "Enter description here.");
	add_option("wpabstracts_presenter_preference", "Poster,Panel,Roundtable,Projector");
	add_option("wpabstracts_email_admin", 1);
	add_option("wpabstracts_email_author", 1);
	add_option("wpabstracts_frontend_dashboard", 1);
	add_option("wpabstracts_reviewer_submit", 0);
	add_option("wpabstracts_reviewer_edit", 0);
	add_option("wpabstracts_blind_review", 0);
	add_option("wpabstracts_show_adminbar", 0);
	add_option("wpabstracts_permitted_attachments", 'pdf,doc,xls,docx,xlsx,txt,rtf');
	add_option("wpabstracts_change_ownership", 1);
	add_option("wpabstracts_submission_notification", 1);
	add_option("wpabstracts_edit_notification", 0);
	add_option("wpabstracts_status_notification", 1);
	add_option("wpabstracts_review_notification", 1);
	add_option("wpabstracts_show_reviews", 1);
	add_option("wpabstracts_show_description", 1);
	add_option("wpabstracts_show_author", 1);
	add_option("wpabstracts_show_presenter", 1);
	add_option("wpabstracts_show_attachments", 1);
	add_option("wpabstracts_show_keywords", 0);
	add_option("wpabstracts_show_conditions", 0);
	add_option("wpabstracts_terms_conditions", "Enter your terms and conditions here.");
	add_option("wpabstracts_sync_status", 0);
	add_option("wpabstracts_captcha", 1);
	add_option("wpabstracts_captcha_secret", "JSuqfZDXakXbxrW3CzZgY");
	add_option("wpabstracts_editor_media", 1);
	add_option("wpabstracts_enable_register", 1);
	add_option("wpabstracts_login_redirect", 1);
	add_option("wpabstracts_edit_status", 1);
	add_option("wpabstracts_default_status", 1);
	add_option("wpabstracts_submit_limit", 2);
	add_option("wpabstracts_review_attachments", 1);
	add_option("wpabstracts_review_visibility", 1);
	add_option("wpabstracts_attachment_pref", 'optional');


	// default abstracts admin columns
	$columns['event'] = array('label' => __('Event', 'wpabstracts'), 'enabled' => true);
	$columns['topic'] = array('label' => __('Topic', 'wpabstracts'), 'enabled' => true);
	$columns['author'] = array('label' => __('Author', 'wpabstracts'), 'enabled' => true);
	$columns['preference'] = array('label' => __('Preference', 'wpabstracts'), 'enabled' => true);
	$columns['status'] = array('label' => __('Status', 'wpabstracts'), 'enabled' => true);
	$columns['reviewers'] = array('label' => __('Reviewers', 'wpabstracts'), 'enabled' => true);
	$columns['date_submitted'] = array('label' => __('Date Submitted', 'wpabstracts'), 'enabled' => true);
	$columns['attachments'] = array('label' => __('Attachments', 'wpabstracts'), 'enabled' => true);
	$columns['submit_by'] = array('label' => __('Submit By', 'wpabstracts'), 'enabled' => false);
	$columns['date_modified'] = array('label' => __('Date Modified', 'wpabstracts'), 'enabled' => false);
	$columns['keywords'] = array('label' => __('Keywords', 'wpabstracts'), 'enabled' => false);

	add_option("wpabstracts_abstracts_columns", $columns);

}

function wpabstracts_init_custom_titles() {
	include_once(WPABSTRACTS_PLUGIN_DIR . 'inc/wpabstracts_functions.php');
	$customTitles = wpabstracts_get_custom_titles();
	print_r($customTitles);
	add_option('wpabstracts_custom_titles', $customTitles);
}

function wpabstracts_users_install(){
	global $wpdb;
	require_once(ABSPATH.'wp-admin/includes/upgrade.php');
	$charset_collate = $wpdb->get_charset_collate();
	$user_tbl = $wpdb->prefix."wpabstracts_users";
	$wp_user = $wpdb->prefix."wp_users";
	$create_users = "CREATE TABLE ".$user_tbl." (
		id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
		user_id BIGINT(20) UNIQUE NOT NULL,
		data longtext,
		activation_key varchar(255),
		status TINYINT(1) NOT NULL DEFAULT 0,
		PRIMARY KEY (id)
	) $charset_collate;";
	dbDelta($create_users);

	// sync users to wpabstracts profile
	$users = get_users();
	foreach($users as $user){
		$userExist = $wpdb->get_var("SELECT COUNT(*) FROM " . $user_tbl . " WHERE user_id = " . $user->ID);
		if(!$userExist){
			$data = array('user_id' => $user->ID, 'status' => 1);
			$wpdb->insert($wpdb->prefix.'wpabstracts_users', $data);
		}
	}

	$default_form = '[{"type":"paragraph","label":"Please take a moment and tell us about yourself."},{"type":"select","required":true,"label":"Title","name":"title","className":"form-control","layout":"form-group col-sm-4","values":[{"label":"Mr","value":"Mr"},{"label":"Mrs","value":"Mrs"},{"label":"Dr","value":"Dr"},{"label":"Prof","value":"Prof"}]},{"type":"text","required":true,"label":"First Name","description":"Please enter first name","name":"firstname","className":"form-control","layout":"form-group col-sm-4","wpSync":"first_name"},{"type":"text","required":true,"label":"Last Name","description":"Enter last name","name":"lastname","className":"form-control","layout":"form-group col-sm-4","wpSync":"last_name"},{"type":"select","required":true,"label":"Gender","description":"Select a gender","name":"gender","className":"form-control","layout":"form-group col-sm-4","values":[{"label":"Male","value":"Male"},{"label":"Female","value":"Female"}]},{"type":"text","label":"Phone","description":"How can you reach you?","name":"phone","className":"form-control","layout":"form-group col-sm-4"},{"type":"text","label":"Designation","description":"Tell us your role","name":"designation","className":"form-control","layout":"form-group col-sm-4"},{"type":"text","label":"Personal URL","description":"Enter your personal website  Twitter or LinkedIn URL","name":"personalurl","className":"form-control","layout":"form-group col-sm-4","wpSync":"user_url"},{"type":"text","label":"Organization","description":"What organization are you affiliated with.","name":"organization","className":"form-control","layout":"form-group col-sm-4"},{"type":"select","label":"Contact Preference","description":"What is your preferred method of contact?","name":"contact-preference","className":"form-control","layout":"form-group col-sm-4","values":[{"label":"Email","value":"Email","selected":true},{"label":"Phone","value":"Phone"}]},{"type":"text","label":"Address","description":"Enter full physical address","name":"address","className":"form-control","layout":"form-group col-sm-12","wpSync":"address"},{"type":"textarea","label":"Bio","description":"Tell us about yourself","name":"bio","rows":"3","className":"form-control","layout":"form-group col-sm-12","wpSync":"description"}]';

	add_option('wpabstracts_registration_form', $default_form);

	$formOptions = new stdClass();
	$formOptions->admin_name = get_option('blogname');
	$formOptions->admin_email = get_option('admin_email');
	$formOptions->email_subject = "Your Account Registration";
	$formOptions->auto_activate_on = 0;
	$formOptions->ignore_activation = 0;
	$formOptions->sync_fields = 1;
	$formOptions->display_name = 'firstname';
	$formOptions->reg_message_on = 1;
	$formOptions->reg_message = "Thank you for registering. You will receive a confirmation email shortly.";
	$formOptions->reg_email_on = 1;
	$formOptions->reg_email = 'Hello {DISPLAY_NAME},
	You have successfully registered for this Event.
	Please click the link below to activate your account.

	Activate Account: {ACTIVATE_LINK}

	Please visit your dashboard at: {SITE_URL} to submit or manage your abstracts.


	Regards,
	WP Abstracts Team
	{SITE_NAME}
	{SITE_URL}';

	// password Rules
	$pw_rules = new stdClass();
	$pw_rules->min_pwd = 7;
	$pw_rules->max_pwd = 14;
	$pw_rules->number = 1;
	$pw_rules->uppercase = 1;
	$pw_rules->lowercase = 1;
	$pw_rules->special = 1;
	$formOptions->password_rules = $pw_rules;

	// add admin column_status
	$columns['firstname'] = 'First Name';
	$columns['lastname'] = 'Last Name';
	$columns['phone'] = 'Phone';
	$columns['designation'] = 'Designation';
	$columns['personalurl'] = 'Personal URL';
	$formOptions->admin_columns = $columns;

	add_option('wpabstracts_user_settings', $formOptions);
}

function wpabstracts_init_email_templates(){
	global $wpdb;
	$from_name = get_option('blogname');
	$from_email = get_option('admin_email');

	// user submission confirmation template
	if(!get_option('wpabstracts_submit_templateId')) {
		$submitConfirmationMsg = 'Hi {DISPLAY_NAME},
		You have successfully submitted your abstract.
		Abstracts Title: {ABSTRACT_TITLE}
		Abstracts ID: {ABSTRACT_ID}
		Event: {EVENT_NAME}
		To make changes to your submission or view the status visit {SITE_URL} and sign in to your dashboard.
		Regards,
		WP Abstracts Team
		{SITE_NAME}
		{SITE_URL}';

		$submitConfirmationTemplate = array(
			'name' => "Abstracts Submitted - Author Notification",
			'subject' => "Your abstract was submitted successfully",
			'message'=> $submitConfirmationMsg,
			'from_name' => $from_name,
			'from_email' => $from_email,
			'receiver' => "Authors"
		);
		$success = $wpdb->insert($wpdb->prefix.'wpabstracts_emailtemplates', $submitConfirmationTemplate);
		if($success){
			update_option("wpabstracts_submit_templateId", $wpdb->insert_id);
		}
	}

	// user edit confirmation template
	if(!get_option('wpabstracts_author_edit_templateId')) {
		$editConfirmationMsg = 'Hi {DISPLAY_NAME},
		You have successfully edited your abstract.
		Abstracts Title: {ABSTRACT_TITLE}
		Abstracts ID: {ABSTRACT_ID}
		Event: {EVENT_NAME}
		To make changes to your submission or view the status visit {SITE_URL} and sign in to your dashboard.
		Regards,
		WP Abstracts Team
		{SITE_NAME}
		{SITE_URL}';

		$editConfirmationTemplate = array(
			'name' => "Abstracts Edited - Author Notification",
			'subject' => "Your abstract was edited successfully",
			'message'=> $editConfirmationMsg,
			'from_name' => $from_name,
			'from_email' => $from_email,
			'receiver' => "Authors"
		);
		$success = $wpdb->insert($wpdb->prefix.'wpabstracts_emailtemplates', $editConfirmationTemplate);
		if($success) {
			update_option("wpabstracts_author_edit_templateId", $wpdb->insert_id);
		}
	}

	// admin submission notification template
	if(!get_option('wpabstracts_admin_templateId')) {
		$adminSubmitEmailMsg = 'Hello {DISPLAY_NAME},
		You have a new abstract for {SITE_NAME}
		Abstract Title: {ABSTRACT_TITLE}
		Abstract ID: {ABSTRACT_ID}
		Regards,
		WP Abstracts Team
		{SITE_NAME}
		{SITE_URL}';
		$adminSubmitEmailTemplate = array(
			'name' => "Abstract Submitted - Admin Notification",
			'subject' => "A new abstract was submitted",
			'message'=> $adminSubmitEmailMsg,
			'from_name' => $from_name,
			'from_email' => $from_email,
			'receiver' => "Administrators"
		);
		$success = $wpdb->insert($wpdb->prefix.'wpabstracts_emailtemplates', $adminSubmitEmailTemplate);
		if($success) {
			update_option("wpabstracts_admin_templateId", $wpdb->insert_id);
		}
	}

	// admin abstract edit notification template
	if(!get_option('wpabstracts_admin_edit_templateId')) {
		$adminEditEmailMsg = 'Hello {DISPLAY_NAME},
		Abstract Title: {ABSTRACT_TITLE} was edited by {SUBMITTER_NAME}.
		Abstract ID: {ABSTRACT_ID}
		Regards,
		WP Abstracts Team
		{SITE_NAME}
		{SITE_URL}';
		$adminEditEmailTemplate = array(
			'name' => "Abstract Edited - Admin Notification",
			'subject' => "An abstract was edited",
			'message'=> $adminEditEmailMsg,
			'from_name' => $from_name,
			'from_email' => $from_email,
			'receiver' => "Administrators"
		);
		$success = $wpdb->insert($wpdb->prefix.'wpabstracts_emailtemplates', $adminEditEmailTemplate);
		if($success) {
			update_option("wpabstracts_admin_edit_templateId", $wpdb->insert_id);
		}
	}

	// author acceptance notification template
	if(!get_option('wpabstracts_approval_templateId')) {
		$authorApprovalMsg = 'Hello {DISPLAY_NAME},
		We are happy to announce that your abstract entitled {ABSTRACT_TITLE} was accepted.
		Regards,
		WP Abstracts Team
		{SITE_NAME}
		{SITE_URL}';
		$authorApprovalTemplate = array(
			'name' => "Abstract Accepted - Author Notification",
			'subject' => "Your abstract was accepted",
			'message'=> $authorApprovalMsg,
			'from_name' => $from_name,
			'from_email' => $from_email,
			'receiver' => "Authors"
		);
		$success = $wpdb->insert($wpdb->prefix.'wpabstracts_emailtemplates', $authorApprovalTemplate);
		if($success) {
			update_option("wpabstracts_approval_templateId", $wpdb->insert_id);
		}
	}

	// author rejection notification template
	if(!get_option('wpabstracts_rejected_templateId')) {
		$authorRejectedMsg = 'Hello {DISPLAY_NAME},
		We are sorry to inform you that your abstract entitled {ABSTRACT_TITLE} was rejected.
		Regards,
		WP Abstracts Team
		{SITE_NAME}
		{SITE_URL}';
		$authorRejectedTemplate = array(
			'name' => "Abstract Rejected - Author Notification",
			'subject' => "Your abstract was rejected",
			'message'=> $authorRejectedMsg,
			'from_name' => $from_name,
			'from_email' => $from_email,
			'receiver' => "Authors"
		);
		$success = $wpdb->insert($wpdb->prefix.'wpabstracts_emailtemplates', $authorRejectedTemplate);
		if($success) {
			update_option("wpabstracts_rejected_templateId", $wpdb->insert_id);
		}
	}

	// author under review notification template
	if(!get_option('wpabstracts_underreview_templateId')) {
		$abstractsUnderReviewMsg = 'Hello {DISPLAY_NAME},
		We are happy to inform you that your abstract entitled {ABSTRACT_TITLE} is now under review.
		Regards,
		WP Abstracts Team
		{SITE_NAME}
		{SITE_URL}';
		$abstractsUnderReviewTemplate = array(
			'name' => "Abstract Under Review - Author Notification",
			'subject' => "Your abstract is under review",
			'message'=> $abstractsUnderReviewMsg,
			'from_name' => $from_name,
			'from_email' => $from_email,
			'receiver' => "Authors"
		);
		$success = $wpdb->insert($wpdb->prefix.'wpabstracts_emailtemplates', $abstractsUnderReviewTemplate);
		if($success) {
			update_option("wpabstracts_underreview_templateId", $wpdb->insert_id);
		}
	}

	// reviewer assignment template
	if(!get_option('wpabstracts_assignment_templateId')) {
		$reviewerAssignmentMsg = 'Hello {DISPLAY_NAME},
		You have been assigned a new abstract for review.
		To review this or other abstracts please sign in at: {SITE_URL}
		Regards,
		WP Abstracts Team
		{SITE_NAME}
		{SITE_URL}';
		$reviewerAssignmentTemplate = array(
			'name' => "Reviewer Assignment - Reviewer Notification",
			'subject' => "An abstract was assigned to you",
			'message'=> $reviewerAssignmentMsg,
			'from_name' => $from_name,
			'from_email' => $from_email,
			'receiver' => "Reviewers"
		);
		$success = $wpdb->insert($wpdb->prefix.'wpabstracts_emailtemplates', $reviewerAssignmentTemplate);
		if($success) {
			update_option("wpabstracts_assignment_templateId", $wpdb->insert_id);
		}
	}

	// reviewer edit / revision template
	if(!get_option('wpabstracts_submit_revised_templateId')) {
		$submissionRevisedMsg = 'Hello {DISPLAY_NAME},
		Abstract ID {ABSTRACT_ID} assigned to you has been revised.
		Abstract Title: {ABSTRACT_TITLE}
		Abstract ID: {ABSTRACT_ID}
		To review this or other abstracts please sign in at: {SITE_URL}
		Regards,
		WP Abstracts Team
		{SITE_NAME}
		{SITE_URL}';
		$submissionRevisedTemplate = array(
			'name' => "Submission Revised - Reviewer Notification",
			'subject' => "An abstract assigned to you was revised",
			'message'=> $submissionRevisedMsg,
			'from_name' => $from_name,
			'from_email' => $from_email,
			'receiver' => "Reviewers"
		);
		$success = $wpdb->insert($wpdb->prefix.'wpabstracts_emailtemplates', $submissionRevisedTemplate);
		if($success) {
			update_option("wpabstracts_submit_revised_templateId", $wpdb->insert_id);
		}
	}

	// review submitted user notification template
	if(!get_option('wpabstracts_reviewed_templateId')) {
		$authorReviewsMsg = 'Hello {DISPLAY_NAME},
		We are happy to inform you that a reviewer submitted comments for your submission.
		To review these comments please login to your dashboard at {SITE_URL}
		Regards,
		WP Abstracts Team
		{SITE_NAME}
		{SITE_URL}';
		$authorReviewedTemplate = array(
			'name' => "Review Submitted - Author Notification",
			'subject' => "Your abstract was reviewed",
			'message'=> $authorReviewsMsg,
			'from_name' => $from_name,
			'from_email' => $from_email,
			'receiver' => "Authors"
		);
		$success = $wpdb->insert($wpdb->prefix.'wpabstracts_emailtemplates', $authorReviewedTemplate);
		if($success) {
			update_option("wpabstracts_reviewed_templateId", $wpdb->insert_id);
		}
	}

	// review submitted reviewer notification template
	if(!get_option('wpabstracts_reviewedreviewer_templateId')) {
		$reviewerReviewsMsg = 'Hello {DISPLAY_NAME},
		Thank you for submitting your review.
		To edit and change the status of your review please login in to your dashboard at {SITE_URL}
		Regards,
		WP Abstracts Team
		{SITE_NAME}
		{SITE_URL}';
		$reviewReviewedTemplate = array(
			'name' => "Review Submitted - Reviewer Notification",
			'subject' => "Your review has been submitted",
			'message'=> $reviewerReviewsMsg,
			'from_name' => $from_name,
			'from_email' => $from_email,
			'receiver' => "Reviewers"
		);
		$success = $wpdb->insert($wpdb->prefix.'wpabstracts_emailtemplates', $reviewReviewedTemplate);
		if($success) {
			update_option("wpabstracts_reviewedreviewer_templateId", $wpdb->insert_id);
		}
	}

	// review submitted admin notification template
	if(!get_option('wpabstracts_reviewedadmin_templateId')) {
		$reviewAdminMsg = 'Hi {DISPLAY_NAME},
		A review was successfully submitted to Abstract # {ABSTRACT_ID}.

		More Info:
		Abstracts Title: {ABSTRACT_TITLE}
		Abstracts Id: {ABSTRACT_ID}
		Event: {EVENT_NAME}
		To view the comments and manage the review visit your admin area.
		Regards,
		WP Abstracts Team
		{SITE_NAME}
		{SITE_URL}';
		$reviewAdminTemplate = array(
			'name' => "Review Submitted - Admin Notification",
			'subject' => "A new review was submitted",
			'message'=> $reviewAdminMsg,
			'from_name' => get_option('blogname'),
			'from_email' =>get_option('admin_email'),
			'receiver' => "Administrators"
		);
		$success = $wpdb->insert($wpdb->prefix.'wpabstracts_emailtemplates', $reviewAdminTemplate);
		if($success) {
			update_option("wpabstracts_reviewedadmin_templateId", $wpdb->insert_id);
		}
	}
}

add_action('admin_init', 'wpabstracts_version_check');
function wpabstracts_version_check(){
	if (version_compare(WPABSTRACTS_VERSION, get_option("wpabstracts_version"), '>') ) {
		wpabstracts_upgrade_db();
	}
}

function wpabstracts_upgrade_db(){
	global $wpdb;
	require_once(ABSPATH.'wp-admin/includes/upgrade.php');
	$charset_collate = $wpdb->get_charset_collate();

	/**** UPDATING TO 1.5.0 ******/
	if(version_compare(get_option("wpabstracts_version"), '1.5.0', '<')) {
		// Update Abstract Status from Varchar to Int
		$abs_tbl = $wpdb->prefix."wpabstracts_abstracts";
		$updatePending = "UPDATE " . $abs_tbl . " SET status = '1' WHERE status = 'Pending'";
		$updateAccepted = "UPDATE " . $abs_tbl . " SET status = '3' WHERE status = 'Accepted'";
		$updateRejected = "UPDATE " . $abs_tbl . " SET status = '4' WHERE status = 'Rejected'";
		$updateAbsTable = "ALTER TABLE " . $abs_tbl . " CHANGE status status int(11);";
		$wpdb->query($updatePending);
		$wpdb->query($updateAccepted);
		$wpdb->query($updateRejected);
		$wpdb->query($updateAbsTable);

		// Update Review Status from Varchar to Int
		$review_tbl = $wpdb->prefix."wpabstracts_reviews";
		$updatePending = "UPDATE " . $review_tbl . " SET status = '1' WHERE status = 'Pending'";
		$updateAccepted = "UPDATE " . $review_tbl . " SET status = '3' WHERE status = 'Accepted'";
		$updateRejected = "UPDATE " . $review_tbl . " SET status = '4' WHERE status = 'Rejected'";
		$updateReviewTable = "ALTER TABLE " . $review_tbl . " CHANGE status status int(11);";
		$wpdb->query($updatePending);
		$wpdb->query($updateAccepted);
		$wpdb->query($updateRejected);
		$wpdb->query($updateReviewTable);

		// author under review notification template
		if(!get_option('wpabstracts_underreview_templateId')){
			$abstractsUnderReviewMsg = 'Hello {DISPLAY_NAME},
			We are happy to inform you that your abstract entitled {ABSTRACT_TITLE} is now under review.
			Regards,
			WP Abstracts Team
			{SITE_NAME}
			{SITE_URL}';
			$abstractsUnderReviewTemplate = array(
				'name' => "Abstract Under Review Notification",
				'subject' => "Your Abstract is Under Review",
				'message'=> $abstractsUnderReviewMsg,
				'from_name' => get_option('blogname'),
				'from_email' => get_option('admin_email'),
				'receiver' => "Authors"
			);
			$wpdb->insert($wpdb->prefix.'wpabstracts_emailtemplates', $abstractsUnderReviewTemplate);
			update_option("wpabstracts_underreview_templateId", $wpdb->insert_id);
		}

		// review submitted admin notification template
		if(!get_option('wpabstracts_reviewedadmin_templateId')){
			$reviewAdminMsg = 'Hi {DISPLAY_NAME},
			A review was successfully submitted to Abstract # {ABSTRACT_ID}.

			More Info:
			Abstracts Title: {ABSTRACT_TITLE}
			Abstracts Id: {ABSTRACT_ID}
			Event: {EVENT_NAME}
			To view the comments and manage the review visit your admin area.
			Regards,
			WP Abstracts Team
			{SITE_NAME}
			{SITE_URL}';

			$reviewAdminTemplate = array(
				'name' => "Review Submitted - Admin Notification",
				'subject' => "A New Review was Submitted",
				'message'=> $reviewAdminMsg,
				'from_name' => get_option('blogname'),
				'from_email' =>get_option('admin_email'),
				'receiver' => "Administrators"
			);
			$wpdb->insert($wpdb->prefix.'wpabstracts_emailtemplates', $reviewAdminTemplate);
			update_option("wpabstracts_reviewedadmin_templateId", $wpdb->insert_id);
		}

		// fix typo on receiver column for Approval Notification
		$email_tbl = $wpdb->prefix."wpabstracts_emailtemplates";
		$updateReceiverColumn = "UPDATE " . $email_tbl . " SET receiver = 'Authors' WHERE ID = " . get_option("wpabstracts_approval_templateId");
		$wpdb->query($updateReceiverColumn);
	}

	/**** UPDATING TO 1.6.0 ******/
	if(version_compare(get_option("wpabstracts_version"), '1.6.0', '<')) {
		// Insert all existing reviewers into reviewers table
		$reviewers_tbl = $wpdb->prefix."wpabstracts_reviewers";
		$reviewers_sql = "CREATE TABLE " . $reviewers_tbl . " (
			reviewer_id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			abs_id int(11),
			user_id int(11),
			PRIMARY KEY (reviewer_id)
		) $charset_collate;";
		dbDelta($reviewers_sql);

		$abs_tbl = $wpdb->prefix."wpabstracts_abstracts";
		$updateRev1 = "INSERT INTO {$reviewers_tbl} (`abs_id`, `user_id`) SELECT `abstract_id`, `reviewer_id1` FROM {$abs_tbl}";
		$updateRev2 = "INSERT INTO {$reviewers_tbl} (`abs_id`, `user_id`) SELECT `abstract_id`, `reviewer_id2` FROM {$abs_tbl}";
		$updateRev3 = "INSERT INTO {$reviewers_tbl} (`abs_id`, `user_id`) SELECT `abstract_id`, `reviewer_id3` FROM {$abs_tbl}";
		$wpdb->query($updateRev1);
		$wpdb->query($updateRev2);
		$wpdb->query($updateRev3);

		// add attachment format to diffentiate file_get_contents (1) vs rawurlencode (null) @since 1.6.0
		$atts_tbl = $wpdb->prefix."wpabstracts_attachments";
		$updateAttsTbl = "ALTER TABLE " . $atts_tbl . " ADD format tinyint(1);";
		$wpdb->query($updateAttsTbl);
	}

	/**** UPDATING TO 1.7.0 ******/
	if(version_compare(get_option("wpabstracts_version"), '1.7.0', '<')) {
		// Update DB for user profiles and set defaults for user module
		wpabstracts_users_install();
		add_option("wpabstracts_enable_register", 1);
		add_option("wpabstracts_login_redirect", 1);
	}

	/**** UPDATING TO 1.8.0 ******/
	if(version_compare(get_option("wpabstracts_version"), '1.8.0', '<')) {
		// Migrate wpabstracts_status_desc to DB table to support unlimited statuses 
		$status_tbl = $wpdb->prefix."wpabstracts_statuses";
		$status_sql = "CREATE TABLE " . $status_tbl . " (
			`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			`name` varchar(255) NOT NULL,
			`order` int(11) DEFAULT NULL,
			`template_id` int(11),
			`status` TINYINT(1) NOT NULL DEFAULT 1,
			PRIMARY KEY (id)
		) $charset_collate;";
		dbDelta($status_sql);

		if(get_option('wpabstracts_status_desc')) {
			$statuses = unserialize(get_option('wpabstracts_status_desc'));
			foreach($statuses as $key => $status) {
				$data = array(
					'name' => $status,
					'template_id' => -1, 
					'status' => 1
				);
				$id = $wpdb->insert($status_tbl, $data);
			}

			// set under review template_id on status
			$underReviewTemplData = array('template_id' => get_option('wpabstracts_underreview_templateId'));
			$wpdb->update($status_tbl, $underReviewTemplData, array('id' => 2));

			// set under review template_id on status
			$approvalTemplData = array('template_id' => get_option('wpabstracts_approval_templateId'));
			$wpdb->update($status_tbl, $approvalTemplData, array('id' => 3));
			// set under review template_id on status
			$rejectedTemplData = array('template_id' => get_option('wpabstracts_rejected_templateId'));
			$wpdb->update($status_tbl, $rejectedTemplData, array('id' => 4));
		}

		// delete option to avoid replication on acitivation 
		delete_option('wpabstracts_status_desc');

		// add option to enable / disable all submission notifications
		add_option("wpabstracts_submission_notification", 1);

		// add admin notice for email trigger setting comfirmation 
		function wpabstracts_admin_notice_v180() {
			$link = admin_url("admin.php?page=wpabstracts&tab=emails&subtab=settings");
			$message = sprintf(__("There was an update to the WP Abstracts email notification workflow. Please visit the <a href=".$link.">Email Settings</a> and verify your notifications templates are correctly mapped.", 'wpabstracts'));
			printf('<div class="update-nag notice"><p>%1$s</p></div>', $message); 
		}
		add_action('admin_notices', 'wpabstracts_admin_notice_v180');
	}

	/**** UPDATING TO 1.9.0 ******/
	if(version_compare(get_option("wpabstracts_version"), '1.9.0', '<')) {
		// enable admin notification for admins that was never managed
		$admins = get_users(array('role'=>'administrator', 'fields' => array('ID')));
		foreach($admins as $admin){
			if (!metadata_exists('user', $admin->ID, 'wpabstracts_enable_notification')) {
				update_user_meta($admin->ID, 'wpabstracts_enable_notification', 1);
			}
		}
	}

	/**** UPDATING TO 2.0.0 ******/
	if(version_compare(get_option("wpabstracts_version"), '2.0.0', '<')) {
		// update tables 
		wpabstracts_init_db_tables();
		// init custom titles
		wpabstracts_init_custom_titles();
		// update templates
		wpabstracts_init_email_templates();
		// add options and update user settings
		add_option("wpabstracts_edit_status", 1);
		add_option("wpabstracts_edit_notification", 0);
		$user_settings = get_option('wpabstracts_user_settings');
		$user_settings->email_subject = "Your Account Registration";
		$user_settings->auto_activate_on = 0;
		$user_settings->sync_fields = 1;
		$user_settings->display_name = 'firstname';
		update_option('wpabstracts_user_settings', $user_settings);
	}

	/**** UPDATING TO 2.2.0 ******/
	if(version_compare(get_option("wpabstracts_version"), '2.2.0', '<')) {
		// update tables 
		wpabstracts_init_db_tables();
		
		// add new options
		wpabstracts_init_options();

		// insert new custom titles
		$titles = get_option('wpabstracts_custom_titles');
		$titles->review_attachments = __('Review Attachments', 'wpabstracts');
		$titles->reviewer_attachment_help = __('Use this option to upload a document to this review.', 'wpabstracts');
		$titles->login_information = __('Login Information', 'wpabstracts');
		$titles->account_information = __('Account Information', 'wpabstracts');
		$titles->add_comments = __('Add Comments', 'wpabstracts');
		$titles->additional_information = __('Additional Information', 'wpabstracts');
		$titles->new_review = __('New Review', 'wpabstracts');
		$titles->edit_review = __('Edit Review', 'wpabstracts');
		$titles->suggest_type = __('Suggest Type', 'wpabstracts');
		$titles->suggest_status = __('Suggest Status', 'wpabstracts');

		update_option('wpabstracts_custom_titles', $titles);
	}

	/**** UPDATING TO 2.2.2 ******/
	if(version_compare(get_option("wpabstracts_version"), '2.2.2', '<')) {
		// insert new custom titles
		$titles = get_option('wpabstracts_custom_titles');
		$titles->review_document = __('Document', 'wpabstracts');
		update_option('wpabstracts_custom_titles', $titles);
	}

	/**** UPDATING TO 2.3.0 ******/
	if(version_compare(get_option("wpabstracts_version"), '2.3.0', '<')) {
		// update tables 
		wpabstracts_init_db_tables();
		// add new options
		wpabstracts_init_options();
		// update templates
		wpabstracts_init_email_templates();
		// update user settings
		$user_settings = get_option('wpabstracts_user_settings');
		$user_settings->auto_acignore_activationtivate_on = 0;
		update_option('wpabstracts_user_settings', $user_settings);
	}

	// update version number
	update_option("wpabstracts_version", WPABSTRACTS_VERSION);

}

function wpabstracts_force_jquery_inhead(){
	wp_enqueue_script('jquery', false, array(), false, false);
}
add_filter('wp_enqueue_scripts','wpabstracts_force_jquery_inhead', 1);

function wpabstracts_loadJS($frontend=false) {
	if ($frontend && isset($_GET['task']) && ($_GET['task'] == 'profile' || $_GET['task'] == 'register' )){
		wpabstracts_loadFormBuildJS();
	}
	wp_enqueue_script('wpabstracts-multiselect', plugins_url('js/multiselect.js', __FILE__), array('jquery'), WPABSTRACTS_VERSION, true);
	wp_enqueue_script('wpabstracts-jquery-ui', plugins_url('js/jquery-ui.min.js', __FILE__), array('jquery'), WPABSTRACTS_VERSION, true);
	wp_enqueue_script('wpabstracts-alertify', plugins_url('js/alertify.min.js', __FILE__), array('jquery'), WPABSTRACTS_VERSION, true);
	wp_enqueue_script('wpabstracts-bootstrap', plugins_url('js/bootstrap.min.js', __FILE__), array('jquery'), WPABSTRACTS_VERSION, true);
	wp_enqueue_script('wpabstracts-datatables', plugins_url('js/datatables.min.js', __FILE__), array('jquery'), WPABSTRACTS_VERSION, true);
	wp_enqueue_script('wpabstracts-dt-natural', plugins_url('js/datatables.natural.js', __FILE__), array('jquery'), WPABSTRACTS_VERSION, true);
	wp_enqueue_script('wpabstracts-scripts', plugins_url('js/wpabstracts.js', __FILE__), array('jquery'), WPABSTRACTS_VERSION, true);
	wpabstracts_localize();
}

function wpabstracts_loadFormBuildJS() {
	wp_enqueue_script('wpabstracts-form-polyfill', plugins_url('js/polyfill.js', __FILE__), array('jquery'), WPABSTRACTS_VERSION, true);
	wp_enqueue_script('wpabstracts-form-builder', plugins_url('js/form-builder.min.js', __FILE__), array('jquery'), WPABSTRACTS_VERSION, true);
	wp_enqueue_script('wpabstracts-form-render', plugins_url('js/form-render.min.js', __FILE__), array('jquery'), WPABSTRACTS_VERSION, true);
}

function wpabstracts_loadCSS(){
	wp_enqueue_style('wpabstracts-style', plugins_url('css/wpabstracts.css', __FILE__), null, WPABSTRACTS_VERSION);
	wp_enqueue_style('wpabstracts-fonts', plugins_url('css/fontawesome.css', __FILE__), null, WPABSTRACTS_VERSION);
	wp_enqueue_style('wpabstracts-alertify', plugins_url('css/alertify.css', __FILE__), null, WPABSTRACTS_VERSION);
	wp_enqueue_style('wpabstracts-jquery-ui', plugins_url('css/jquery-ui.css', __FILE__), null, WPABSTRACTS_VERSION);
	wp_enqueue_style('wpabstracts-jquery-ui-ie', plugins_url('css/jquery-ui-ie.css', __FILE__), null, WPABSTRACTS_VERSION);
	wp_enqueue_style('wpabstracts-datatables', plugins_url('css/datatables.min.css', __FILE__), null, WPABSTRACTS_VERSION);
}

function wpabstracts_localize(){
	$schema = is_ssl() ? 'https' : 'http';
	$data = array(
		'ajaxurl' => admin_url('admin-ajax.php', $schema),
		'security' => wp_create_nonce(WPABSTRACTS_SECRET_KEY),
		'confirm_abstract_delete' => __('Do you really want to delete this abstract, its reviews and all its attachments?', 'wpabstracts'),
		'confirm_abstracts_delete' => __('Do you really want to delete the selected abstracts, their reviews and all their attachments?', 'wpabstracts'),
		'confirm_event_delete' => __('Are you sure you want to delete this event? Deleting this event will delete all submissions, reviews and attachments related to this event. This cannot be undone. Type DELETE to confirm.', 'wpabstracts'),
		'confirm_template_delete' => __('Do you really want to delete this email template?', 'wpabstracts'),
		'confirm_review_delete' => __('Do you really want to delete this review?', 'wpabstracts'),
		'confirm_atts_delete' => __('Do you really want to delete this attachment?', 'wpabstracts'),
		'confirm_user_delete' => __('Are you sure you want to delete this user?', 'wpabstracts'),
		'sign_in_msg' => __('Please enter a username and password to sign in.', 'wpabstracts'),
		'captcha_required' => __('Please enter a the security code.', 'wpabstracts'),
		'change_status' => apply_filters('wpabstracts_title_filter', __("Change Status", 'wpabstracts'), 'change_status'),
		'select_status' => apply_filters('wpabstracts_title_filter', __("Please select a status", 'wpabstracts'), 'select_status'),
		'required_fields' => __('Please fill in all required fields.', 'wpabstracts'),
		'desc_required' => __('Please add your abstract description', 'wpabstracts'),
		'word_count_err' => __("You have exceeded the maximum words allowed for this submission.", 'wpabstracts'),
		'file_ext_err' => __('One or more of your file extension is not supported.', 'wpabstracts'),
		'file_size_err' => __('One or more of your files exceeds the maximum file size allowed.', 'wpabstracts'),
		'max_atts_size' => get_option('wpabstracts_max_attach_size'),
		'approved_exts' => explode(',', get_option('wpabstracts_permitted_attachments')),
		'no_attachments' => apply_filters('wpabstracts_title_filter', __("No Attachments Uploaded", 'wpabstracts'), 'no_attachments'),
		'no_order_email' => apply_filters('wpabstracts_title_filter', __("Please enter the email address used for the order.", 'wpabstracts'), 'no_order_email'),
		'reg_fields_success' => __("Successfully updated user registration form.", 'wpabstracts'),
		'reg_fields_failure' => __("Failed to save register form fields.", 'wpabstracts'),
		'copy_success' => __("Successfully copied to clipboard.", 'wpabstracts'),
		'copy_failure' => __("Failed to copy text to clipboard.", 'wpabstracts'),
		'confirm_status_delete' => __('Submissions with this status will no longer have a status. Do you really want to delete this status?', 'wpabstracts'),
		'status_required' => __('At least one status is required.', 'wpabstracts'),
		'topic_required' => __('At least one topic is required.', 'wpabstracts'),
		'confirm_form_restore' => __('Are you sure you want to restore this form to the default inputs? This cannot be undone.', 'wpabstracts'),
		'confirm_title_restore' => __('Are you sure you want to restore your default titles?', 'wpabstracts'),
		'confirm_usermeta_sync' => __('Are you sure you want to SYNC profile data from WP Abstracts to Wordpress user profiles? This cannot be undone. Type SYNC to confirm.', 'wpabstracts'),
		'invalid_email' => __('One or more of your email address seems invalid.', 'wpabstracts'),
		'confirm_title' => __('Confirmation', 'wpabstracts'),
		'button_ok' => __('OK', 'wpabstracts'),
		'button_cancel' => __('Cancel', 'wpabstracts'),
		'button_delete' => __('Delete', 'wpabstracts'),
	);
	wp_localize_script('wpabstracts-scripts', 'wpabstracts', $data);
}

add_action('wp_ajax_loadreviewers', 'wpabstracts_load_reviewers_ajax');
function wpabstracts_load_reviewers_ajax(){
	require_once(apply_filters('wpabstracts_page_include', WPABSTRACTS_PLUGIN_DIR . 'abstracts/abstracts.manage.php'));
	wpabstracts_load_reviewers();
}

add_action('wp_ajax_loadstatus', 'wpabstracts_load_status_ajax');
function wpabstracts_load_status_ajax(){
	require_once(apply_filters('wpabstracts_page_include', WPABSTRACTS_PLUGIN_DIR . 'abstracts/abstracts.manage.php'));
	wpabstracts_load_status();
}

add_action('wp_ajax_loadtopics', 'wpabstracts_load_topics_ajax');
function wpabstracts_load_topics_ajax(){
	require_once(apply_filters('wpabstracts_page_include', WPABSTRACTS_PLUGIN_DIR . 'wpabstracts_events.php'));
	wpabstracts_load_topics();
}

function wpabstracts_editor_admin_init() {
	wp_enqueue_script('post');
	wp_enqueue_script('editor');
	wp_enqueue_script('media-upload');
}

function wpabstracts_set_html_content_type() {
	return 'text/html';
}

function wpabstracts_editor_init($initArray){
	$initArray['setup'] = 'function(ed){ed.on("keyup", function(ed, e){ wpabstracts_updateWordCount()})}';
	return $initArray;
}

// add custom titles filters
add_filter('wpabstracts_title_filter', 'wpabstracts_apply_custom_titles', 10, 2);
function wpabstracts_apply_custom_titles($title, $title_tag) {
	$titles = get_option('wpabstracts_custom_titles');
	if($titles && property_exists($titles, $title_tag)) {
		$title =  stripslashes($titles->$title_tag);
	}
	return $title;
}

/****************** AUTO-UPDATER *******************/
include_once(WPABSTRACTS_PLUGIN_DIR . 'wpabstracts_updates.php');
/****************** END AUTO-UPDATER ***************/
