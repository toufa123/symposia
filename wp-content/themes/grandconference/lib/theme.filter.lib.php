<?php
	
// NOTE: THE CODE TO COPY/PASTE STARTS *BELOW* THIS LINE

// Setting a custom timeout value for cURL. Using a high value for priority to ensure the function runs after any other added to the same action hook.
add_action('http_api_curl', 'grandconference_custom_curl_timeout', 9999, 1);
function grandconference_custom_curl_timeout( $handle ){
  curl_setopt( $handle, CURLOPT_CONNECTTIMEOUT, 30 ); // 30 seconds. Too much for production, only for testing.
  curl_setopt( $handle, CURLOPT_TIMEOUT, 30 ); // 30 seconds. Too much for production, only for testing.
}

// Setting custom timeout for the HTTP request
add_filter( 'http_request_timeout', 'grandconference_custom_http_request_timeout', 9999 );
function grandconference_custom_http_request_timeout( $timeout_value ) {
  return 30; // 30 seconds. Too much for production, only for testing.
}

// Setting custom timeout in HTTP request args
add_filter('http_request_args', 'grandconference_custom_http_request_args', 9999, 1);
function grandconference_custom_http_request_args( $r ){
  $r['timeout'] = 30; // 30 seconds. Too much for production, only for testing.
  return $r;
}

add_filter( 'https_local_ssl_verify', '__return_true' );

/**
 * This function runs when WordPress completes its upgrade process
 * It iterates through each plugin updated to see if ours is included
 * @param $upgrader_object Array
 * @param $options Array
 */
function grandconference_upgrade_completed( $upgrader_object, $options ) {
  if( $options['action'] == 'update' && $options['type'] == 'theme') {
    //Get verified purchase code data
    $is_verified_envato_purchase_code = grandconference_is_registered();
    
    //Check if registered purchase code valid
    if(!empty($is_verified_envato_purchase_code)) {
      $site_domain = grandconference_get_site_domain();
      
      if($site_domain != 'localhost') {
        $url = THEMEGOODS_API.'/check-purchase-domain';
        //var_dump($url);
        $data = array(
          'purchase_code' => $is_verified_envato_purchase_code, 
          'domain' => $site_domain,
        );
        $data = wp_json_encode( $data );
        $args = array( 
          'method'   	=> 'POST',
          'body'		=> $data,
        );
        //print '<pre>'; var_dump($args); print '</pre>';
        
        $response = wp_remote_post( $url, $args );
        $response_body = wp_remote_retrieve_body( $response );
        $response_obj = json_decode($response_body);
        
        $response_json = urlencode($response_body);
        /*print '<pre>'; var_dump($response_body); print '</pre>';
        die;*/
        
        //If purchase already in use with other domain then unregister
        if(!$response_obj->response_code) {
          grandconference_unregister_theme();
        }
      }
    }
  }
}
add_action( 'upgrader_process_complete', 'grandconference_upgrade_completed', 10, 2 );
  
//Remove one click demo import plugin from admin menus
function grandconference_plugin_page_setup( $default_settings ) {
	$default_settings['parent_slug'] = 'themes.php';
	$default_settings['page_title']  = esc_html__( 'Demo Import' , 'grandconference' );
	$default_settings['menu_title']  = esc_html__( 'Import Demo Content' , 'grandconference' );
	$default_settings['capability']  = 'import';
	$default_settings['menu_slug']   = 'tg-one-click-demo-import';

	return $default_settings;
}
add_filter( 'pt-ocdi/plugin_page_setup', 'grandconference_plugin_page_setup' );

function grandconference_menu_page_removing() {
    remove_submenu_page( 'themes.php', 'tg-one-click-demo-import' );
}
add_action( 'admin_menu', 'grandconference_menu_page_removing', 99 );
	
$is_verified_envato_purchase_code = false;

//Get verified purchase code data
$is_verified_envato_purchase_code = grandconference_is_registered();

//if verified envato purchase code
if($is_verified_envato_purchase_code)
{
	function grandconference_import_files() {
	  return array(
	    array(
	      'import_file_name'             => 'Classic Conference',
	      'local_import_file'            => trailingslashit( get_template_directory() ) . 'cache/demos/xml/demo1/1.xml',
	      'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'cache/demos/xml/demo1/1.wie',
	      'local_import_customizer_file' => trailingslashit( get_template_directory() ) . 'cache/demos/xml/demo1/1.dat',
	      'import_preview_image_url'     => trailingslashit( get_template_directory_uri() ) . 'cache/demos/xml/demo1/demo1.jpg',
	      'preview_url'                  => 'http://themes.themegoods.com/grandconference/demo/',
	    ),
	    array(
	      'import_file_name'             => 'Music Event',
	      'local_import_file'            => trailingslashit( get_template_directory() ) . 'cache/demos/xml/demo2/2.xml',
	      'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'cache/demos/xml/demo2/2.wie',
	      'local_import_customizer_file' => trailingslashit( get_template_directory() ) . 'cache/demos/xml/demo2/2.dat',
	      'import_preview_image_url'     => trailingslashit( get_template_directory_uri() ) . 'cache/demos/xml/demo2/demo2.jpg',
	      'preview_url'                  => 'http://themes.themegoods.com/grandconference/demo2/',
	    ),
	    array(
	      'import_file_name'             => 'One Page Event',
	      'local_import_file'            => trailingslashit( get_template_directory() ) . 'cache/demos/xml/demo3/3.xml',
	      'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'cache/demos/xml/demo3/3.wie',
	      'local_import_customizer_file' => trailingslashit( get_template_directory() ) . 'cache/demos/xml/demo3/3.dat',
	      'import_preview_image_url'     => trailingslashit( get_template_directory_uri() ) . 'cache/demos/xml/demo3/demo3.jpg',
	      'preview_url'                  => 'http://themes.themegoods.com/grandconference/demo3/',
	    ),
	  );
	}
	add_filter( 'pt-ocdi/import_files', 'grandconference_import_files' );
	
	function grandconference_confirmation_dialog_options ( $options ) {
		return array_merge( $options, array(
			'width'       => 300,
			'dialogClass' => 'wp-dialog',
			'resizable'   => false,
			'height'      => 'auto',
			'modal'       => true,
		) );
	}
	add_filter( 'pt-ocdi/confirmation_dialog_options', 'grandconference_confirmation_dialog_options', 10, 1 );
	
	function grandconference_before_widgets_import( $selected_import ) {
		//Add demo custom sidebar
		if ( function_exists('register_sidebar') )
		{
		    register_sidebar(array('id' => 'faq-sidebar', 'name' => 'FAQ Sidebar'));
		    register_sidebar(array('id' => 'schedule-sidebar', 'name' => 'Schedule Sidebar'));
		}
	}
	add_action( 'pt-ocdi/before_widgets_import', 'grandconference_before_widgets_import' );
	
	function grandconference_after_import( $selected_import ) {
		switch($selected_import['import_file_name'])
		{
			default:
			case 'Classic Conference':
				// Assign menus to their locations.
				$main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
				$top_menu = get_term_by( 'name', 'Top Bar Menu', 'nav_menu' );
			
				set_theme_mod( 'nav_menu_locations', array(
						'primary-menu' => $main_menu->term_id,
						'side-menu' => $main_menu->term_id,
						'top-menu' => $top_menu->term_id,
					)
				);
				
			break;
			
			case 'Music Event':
			case 'One Page Event':
				// Assign menus to their locations.
				$main_menu = get_term_by( 'name', 'Main Left Menu', 'nav_menu' );
		
				set_theme_mod( 'nav_menu_locations', array(
						'primary-menu' => $main_menu->term_id,
						'side-menu' => $main_menu->term_id,
						'footer-menu' => $main_menu->term_id,
					)
				);
				
			break;
		}
		
		//Import Revolution Slider if activate
		if(class_exists('RevSlider'))
		{
			$slider_array = array();
			
			switch($selected_import['import_file_name'])
	    	{
		    	case 'Classic Conference':
		    	default:
		    		$slider_array = array(
		    			get_template_directory() ."/cache/demos/xml/demo1/home-1-slider.zip",
		    			get_template_directory() ."/cache/demos/xml/demo1/home-2-slider.zip",
		    			get_template_directory() ."/cache/demos/xml/demo1/home-3-slider.zip",
		    			get_template_directory() ."/cache/demos/xml/demo1/home-4-slider.zip",
		    		);
		    	break;
		    	
		    	case 'Music Event':
		    		$slider_array = array(
		    			get_template_directory() ."/cache/demos/xml/demo2/demo2-slider.zip",
		    		);
		    	break;
		    	
		    	case 'One Page Event':
		    		$slider_array = array(
		    			get_template_directory() ."/cache/demos/xml/demo3/demo3-slider.zip",
		    		);
		    	break;
	    	}
	    	
	    	if(!empty($slider_array))
	    	{
		    	require_once ABSPATH . 'wp-admin/includes/file.php';
				$obj_revslider = new RevSlider();
				
				foreach($slider_array as $revslider_filepath)
				{
					$obj_revslider->importSliderFromPost(true,true,$revslider_filepath);
				}
			}
		}
		
		// Assign front page
		switch($selected_import['import_file_name'])
		{
			default:
			case 'Classic Conference':
				$front_page_id = get_page_by_title( 'Home 1 - Classic Conference' );
			break;
			
			case 'Music Event':
			case 'One Page Event':
				$front_page_id = get_page_by_title( 'Homepage' );
			break;
		}
		
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $front_page_id->ID );
	}
	add_action( 'pt-ocdi/after_import', 'grandconference_after_import' );
	add_filter( 'pt-ocdi/disable_pt_branding', '__return_true' );
}
	
add_action( 'admin_init', 'grandconference_gutenberg_init', 10 );

function grandconference_gutenberg_init()
{
	if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
    }
    
    global $pagenow;
    if($pagenow == 'post.php' && isset($_GET['post']))
    {
		if(current_user_can('edit_post', $_GET['post']));
		{
			if (!isset( $_GET['gutenberg-editor'] ) && (isset($_GET['action']) && $_GET['action'] == 'edit') && (function_exists( 'is_gutenberg_page' ) && !is_gutenberg_page())) {
			    // Disable Gutenberg
				add_filter( 'gutenberg_can_edit_post_type', '__return_false' );
				add_filter( 'use_block_editor_for_post_type', '__return_false' );
			}
			
			if (isset( $_GET['gutenberg-editor'] ))
			{
				if(isset($_GET['post']) && !empty($_GET['post']))
				{
					delete_post_meta($_GET['post'], 'ppb_enable');
					$ppb_enable = get_post_meta($_GET['post'], 'ppb_enable', true);
				}
			}
		
			if (isset( $_GET['classic-editor'] ))
			{
				// Disable Gutenberg
				add_filter( 'gutenberg_can_edit_post_type', '__return_false' );
				add_filter( 'use_block_editor_for_post_type', '__return_false' );
			}
			
			if (isset( $_GET['action'] ) && $_GET['action'] == 'edit' && !isset( $_GET['gutenberg-editor'] ))
			{
				$ppb_enable = get_post_meta($_GET['post'], 'ppb_enable', true);
				if(!empty($ppb_enable))
				{
					// Disable Gutenberg
					add_filter( 'gutenberg_can_edit_post_type', '__return_false' );
					add_filter( 'use_block_editor_for_post_type', '__return_false' );
				}
			}
		}
	}
}

function grandconference_tag_cloud_filter($args = array()) {
   $args['smallest'] = 15;
   $args['largest'] = 15;
   $args['unit'] = 'px';
   return $args;
}

add_filter('widget_tag_cloud_args', 'grandconference_tag_cloud_filter', 90);

//Control post excerpt length
function grandconference_custom_excerpt_length( $length ) {
	return 40;
}
add_filter( 'excerpt_length', 'grandconference_custom_excerpt_length', 200 );

// remove version query string from scripts and stylesheets
function grandconference_remove_script_styles_version( $src ){
    return remove_query_arg( 'ver', $src );
}
add_filter( 'script_loader_src', 'grandconference_remove_script_styles_version' );
add_filter( 'style_loader_src', 'grandconference_remove_script_styles_version' );


function grandconference_theme_queue_js(){
  if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) { 
    // enqueue the javascript that performs in-link comment reply fanciness
    wp_enqueue_script( 'comment-reply' ); 
  }
}
add_action('get_header', 'grandconference_theme_queue_js');


function grandconference_add_meta_tags() {
    $post = grandconference_get_wp_post();
    
    echo '<meta charset="'.get_bloginfo( 'charset' ).'" />';
    
    //Check if responsive layout is enabled
    $tg_mobile_responsive = kirki_get_option('tg_mobile_responsive');
	
	if(!empty($tg_mobile_responsive))
	{
		echo '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />';
	}
	
	//meta for phone number link on mobile
	echo '<meta name="format-detection" content="telephone=no">';
    
    //check if single post then add meta description and keywords
    if(is_single()) 
    {
        //Prepare data for Facebook opengraph sharing
        if(has_post_thumbnail(get_the_ID(), 'medium'))
		{
		    $image_id = get_post_thumbnail_id(get_the_ID());
		    $fb_thumb = wp_get_attachment_image_src($image_id, 'medium', true);
		}
	
		if(isset($fb_thumb[0]) && !empty($fb_thumb[0]))
		{
			echo '<meta property="og:image" content="'.esc_url($fb_thumb[0]).'"/>';
		}
			$post_content = get_post_field('post_excerpt', $post->ID);
			
		echo '<meta property="og:type" content="article" />';
		echo '<meta property="og:title" content="'.esc_attr(get_the_title()).'"/>';
		echo '<meta property="og:url" content="'.esc_url(get_permalink($post->ID)).'"/>';
		echo '<meta property="og:description" content="'.esc_attr(strip_tags($post_content)).'"/>';
    }
}
add_action( 'wp_head', 'grandconference_add_meta_tags' , 2 );

add_filter('redirect_canonical','custom_disable_redirect_canonical');
function custom_disable_redirect_canonical($redirect_url) {if (is_paged() && is_singular()) $redirect_url = false; return $redirect_url; }

if(GRANDCONFERENCE_THEMEDEMO)
{
	add_filter('gettext', 'grandconference_translate_reply');
	add_filter('ngettext', 'grandconference_translate_reply');
	
	function grandconference_translate_reply($translated) 
	{
		$translated = str_ireplace('Quantity', 'No. of People', $translated);

		return $translated;
	}
}

function grandconference_body_class_names($classes) {

	if(is_page())
	{
		$tg_post = grandconference_get_wp_post();
		$ppb_enable = get_post_meta($tg_post->ID, 'ppb_enable', true);
		if(!empty($ppb_enable))
		{
			$classes[] = 'ppb_enable';
		}
	}
	
	//Check if boxed layout is enable
	$tg_boxed = kirki_get_option('tg_boxed');
	if((GRANDCONFERENCE_THEMEDEMO && isset($_GET['boxed']) && !empty($_GET['boxed'])) OR !empty($tg_boxed))
	{
		$classes[] = esc_attr('tg_boxed');
	}

	return $classes;
}

//Now add test class to the filter
add_filter('body_class','grandconference_body_class_names');

add_action( 'admin_enqueue_scripts', 'grandconference_admin_pointers_header' );

function grandconference_admin_pointers_header() {
   if ( grandconference_admin_pointers_check() ) {
      add_action( 'admin_print_footer_scripts', 'grandconference_admin_pointers_footer' );

      wp_enqueue_script( 'wp-pointer' );
      wp_enqueue_style( 'wp-pointer' );
   }
}

function grandconference_admin_pointers_check() {
   $admin_pointers = grandconference_admin_pointers();
   foreach ( $admin_pointers as $pointer => $array ) {
      if ( $array['active'] )
         return true;
   }
}

function grandconference_admin_pointers_footer() {
   $admin_pointers = grandconference_admin_pointers();
   ?>
<script type="text/javascript">
/* <![CDATA[ */
( function($) {
   <?php
   foreach ( $admin_pointers as $pointer => $array ) {
      if ( $array['active'] ) {
         ?>
         $( '<?php echo esc_js($array['anchor_id']); ?>' ).pointer( {
            content: '<?php echo $array['content']; ?>',
            position: {
            edge: '<?php echo esc_js($array['edge']); ?>',
            align: '<?php echo esc_js($array['align']); ?>'
         },
            close: function() {
               $.post( ajaxurl, {
                  pointer: '<?php echo esc_js($pointer); ?>',
                  action: 'dismiss-wp-pointer'
               } );
            }
         } ).pointer( 'open' );
         <?php
      }
   }
   ?>
} )(jQuery);
/* ]]> */
</script>
   <?php
}

function grandconference_admin_pointers() {
   $dismissed = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
   $prefix = 'grandconference_admin_pointer';

   //Page help pointers
   $content_builder_content = '<h3>Content Builder</h3>';
   $content_builder_content .= '<p>Basically you can use WordPress visual editor to create page content but theme also has another way to create page content. By using Content Builder, you would be ale to drag&drop each content block without coding knowledge. Click here to enable Content Builder.</p>';
   
   $page_options_content = '<h3>Page Options</h3>';
   $page_options_content .= '<p>You can customise various options for this page including menu styling, page templates etc.</p>';
   
   $page_featured_image_content = '<h3>Page Featured Image</h3>';
   $page_featured_image_content .= '<p>Upload or select featured image for this page to displays it as background header.</p>';
   
   //Post help pointers
   $post_options_content = '<h3>Post Options</h3>';
   $post_options_content .= '<p>You can customise various options for this post including its layout and featured content type.</p>';
   
   $post_featured_image_content = '<h3>Post Featured Image (*Required)</h3>';
   $post_featured_image_content .= '<p>Upload or select featured image for this post to displays it as post image on blog, archive, category, tag and search pages.</p>';
   
   //Gallery help pointers
   $gallery_images_content = '<h3>Gallery Images</h3>';
   $gallery_images_content .= '<p>Upload or select for this gallery. You can select multiple images to upload using SHIFT or CTRL keys.</p>';
   
   $gallery_options_content = '<h3>Gallery Options</h3>';
   $gallery_options_content .= '<p>You can customise various options for this gallery including gallery template, password and gallery images file.</p>';
   
   $gallery_featured_image_content = '<h3>Gallery Featured Image (*Required)</h3>';
   $gallery_featured_image_content .= '<p>Upload or select featured image for this gallery to displays it as gallery image on gallery archive pages. If featured image is not selected, this gallery will not display on gallery archive page.</p>';
   
   //Session help pointers
   $session_options_content = '<h3>Session Options</h3>';
   $session_options_content .= '<p>You can customise various options for this session including speakers, start time, end time and location of this session.</p>';
   
   $session_day_content = '<h3>Session Days</h3>';
   $session_day_content .= '<p>You can assign this session to day you created. So it displays this session under selected day.</p>';
   
   $session_topic_content = '<h3>Session Topics</h3>';
   $session_topic_content .= '<p>You can assign this session to topic you created. So users can filter sessions to selected topic</p>';
   
   $session_excerpt_content = '<h3>Session Excerpt</h3>';
   $session_excerpt_content .= '<p>You can enter short information about this session.</p>';
   
   $schedule_day_name_content = '<h3>Schedule Day Name</h3>';
   $schedule_day_name_content .= '<p>You can enter name for day or example date like Day 1</p>';
   
   $schedule_day_desc_content = '<h3>Schedule Day Description</h3>';
   $schedule_day_desc_content .= '<p>You can enter description for day or example date like July 23, 2017</p>';
   
   //Speaker help pointers
   $speaker_options_content = '<h3>Speaker Options</h3>';
   $speaker_options_content .= '<p>You can customise various options for this speaker including description, wesites and many social profiles.</p>';
   
   $speaker_featured_image_content = '<h3>Speaker Featured Image (*Required)</h3>';
   $speaker_featured_image_content .= '<p>Upload or select featured image for this speaker. It should be speaker photo.</p>';
   
   //Testimonials help pointers
   $testimonials_options_content = '<h3>Testimonials Options</h3>';
   $testimonials_options_content .= '<p>You can customise various options for this testimonial including customer name, position, company etc.</p>';
   
   $testimonials_featured_image_content = '<h3>Testimonials Featured Image</h3>';
   $testimonials_featured_image_content .= '<p>Upload or select featured image for this testimonial to displays it as customer photo.</p>';
   
   //Team Member help pointers
   $team_options_content = '<h3>Team Member Options</h3>';
   $team_options_content .= '<p>You can customise various options for this team member including position and social profiles URL.</p>';
   
   $team_featured_image_content = '<h3>Team Member Featured Image</h3>';
   $team_featured_image_content .= '<p>Upload or select featured image for this team member to displays it as team member photo.</p>';
   
   //Ticket help pointers
   $ticket_options_content = '<h3>Ticket Options</h3>';
   $ticket_options_content .= '<p>You can customise various options for this ticket including currency, price, features etc. of this session.</p>';
   
   $ticket_excerpt_content = '<h3>Ticket Excerpt</h3>';
   $ticket_excerpt_content .= '<p>You can enter short information about this session.</p>';

   $tg_pointer_arr = array(
   
   	  //Page help pointers
      $prefix . '_content_builder' => array(
         'content' => $content_builder_content,
         'anchor_id' => '#enable_builder',
         'edge' => 'top',
         'align' => 'left',
         'active' => ( ! in_array( $prefix . '_content_builder', $dismissed ) )
      ),
      
      $prefix . '_page_options' => array(
         'content' => $page_options_content,
         'anchor_id' => 'body.post-type-page #page_option_page_menu_transparent',
         'edge' => 'top',
         'align' => 'left',
         'active' => ( ! in_array( $prefix . '_page_options', $dismissed ) )
      ),
      
      $prefix . '_page_featured_image' => array(
         'content' => $page_featured_image_content,
         'anchor_id' => 'body.post-type-page #set-post-thumbnail',
         'edge' => 'top',
         'align' => 'left',
         'active' => ( ! in_array( $prefix . '_page_featured_image', $dismissed ) )
      ),
      
      //Post help pointers
      $prefix . '_post_options' => array(
         'content' => $post_options_content,
         'anchor_id' => 'body.post-type-post #post_option_post_layout',
         'edge' => 'top',
         'align' => 'left',
         'active' => ( ! in_array( $prefix . '_post_options', $dismissed ) )
      ),
      
      $prefix . '_post_featured_image' => array(
         'content' => $post_featured_image_content,
         'anchor_id' => 'body.post-type-post #set-post-thumbnail',
         'edge' => 'top',
         'align' => 'left',
         'active' => ( ! in_array( $prefix . '_post_featured_image', $dismissed ) )
      ),
      
      //Gallery help pointers
      $prefix . '_gallery_images' => array(
         'content' => $gallery_images_content,
         'anchor_id' => 'body.post-type-galleries #wpsimplegallery_container',
         'edge' => 'top',
         'align' => 'left',
         'active' => ( ! in_array( $prefix . '_gallery_images', $dismissed ) )
      ),
      
      //Session help pointers
      $prefix . '_session_options' => array(
         'content' => $session_options_content,
         'anchor_id' => 'body.post-type-session #metabox #post_option_session_speakers',
         'edge' => 'bottom',
         'align' => 'left',
         'active' => ( ! in_array( $prefix . '_session_options', $dismissed ) )
      ),
      
      $prefix . '_session_day' => array(
         'content' => $session_day_content,
         'anchor_id' => 'body.post-type-session #scheduledaydiv',
         'edge' => 'right',
         'align' => 'left',
         'active' => ( ! in_array( $prefix . '_session_day', $dismissed ) )
      ),
      
      $prefix . '_session_topic' => array(
         'content' => $session_topic_content,
         'anchor_id' => 'body.post-type-session #sessiontopicdiv',
         'edge' => 'right',
         'align' => 'left',
         'active' => ( ! in_array( $prefix . '_session_topic', $dismissed ) )
      ),
      
      $prefix . '_session_excerpt' => array(
         'content' => $session_excerpt_content,
         'anchor_id' => 'body.post-type-session #postexcerpt',
         'edge' => 'top',
         'align' => 'left',
         'active' => ( ! in_array( $prefix . '_session_excerpt', $dismissed ) )
      ),
      
      $prefix . '_schedule_day_title' => array(
         'content' => $schedule_day_name_content,
         'anchor_id' => 'body.edit-tags-php.post-type-session.taxonomy-scheduleday #tag-name',
         'edge' => 'bottom',
         'align' => 'left',
         'active' => ( ! in_array( $prefix . '_schedule_day_title', $dismissed ) )
      ),
      
      $prefix . '_schedule_day_desc' => array(
         'content' => $schedule_day_desc_content,
         'anchor_id' => 'body.edit-tags-php.post-type-session.taxonomy-scheduleday #tag-description',
         'edge' => 'top',
         'align' => 'left',
         'active' => ( ! in_array( $prefix . '_schedule_day_desc', $dismissed ) )
      ),
      
      //Speaker help pointers
      $prefix . '_speaker_options' => array(
         'content' => $speaker_options_content,
         'anchor_id' => 'body.post-type-speaker #metabox #post_option_speaker_desciption',
         'edge' => 'top',
         'align' => 'left',
         'active' => ( ! in_array( $prefix . '_speaker_options', $dismissed ) )
      ),
      
      $prefix . '_speaker_featured_image' => array(
         'content' => $speaker_featured_image_content,
         'anchor_id' => 'body.post-type-speaker #set-post-thumbnail',
         'edge' => 'top',
         'align' => 'left',
         'active' => ( ! in_array( $prefix . '_speaker_featured_image', $dismissed ) )
      ),
      
      //Testimonials help pointers
      $prefix . '_testimonials_options' => array(
         'content' => $testimonials_options_content,
         'anchor_id' => 'body.post-type-testimonials #metabox #post_option_testimonial_name',
         'edge' => 'top',
         'align' => 'left',
         'active' => ( ! in_array( $prefix . '_testimonials_options', $dismissed ) )
      ),
      
      $prefix . '_testimonials_featured_image' => array(
         'content' => $testimonials_featured_image_content,
         'anchor_id' => 'body.post-type-testimonials #set-post-thumbnail',
         'edge' => 'top',
         'align' => 'left',
         'active' => ( ! in_array( $prefix . '_testimonials_featured_image', $dismissed ) )
      ),
      
      //Team Member help pointers
      $prefix . '_team_options' => array(
         'content' => $team_options_content,
         'anchor_id' => 'body.post-type-team #metabox #post_option_team_position',
         'edge' => 'top',
         'align' => 'left',
         'active' => ( ! in_array( $prefix . '_team_options', $dismissed ) )
      ),
      
      $prefix . '_team_featured_image' => array(
         'content' => $team_featured_image_content,
         'anchor_id' => 'body.post-type-team #set-post-thumbnail',
         'edge' => 'top',
         'align' => 'left',
         'active' => ( ! in_array( $prefix . '_team_featured_image', $dismissed ) )
      ),
      
      //Ticket help pointers
      $prefix . '_ticket_options' => array(
         'content' => $ticket_options_content,
         'anchor_id' => 'body.post-type-ticket #metabox #post_option_ticket_featured',
         'edge' => 'top',
         'align' => 'left',
         'active' => ( ! in_array( $prefix . '_ticket_options', $dismissed ) )
      ),
      
      $prefix . '_ticket_excerpt' => array(
         'content' => $ticket_excerpt_content,
         'anchor_id' => 'body.post-type-ticket #postexcerpt',
         'edge' => 'bottom',
         'align' => 'left',
         'active' => ( ! in_array( $prefix . '_ticket_excerpt', $dismissed ) )
      ),
   );

   return $tg_pointer_arr;
}

function grandconference_create_admin_menu() {
	global $wp_admin_bar;

	$menu_id = 'grandconference_admin';
	$wp_admin_bar->add_menu(array('id' => $menu_id, 'title' => esc_html__('Theme Setting'), 'href' => '/wp-admin/themes.php?page=functions.php'));
}
add_action('admin_bar_menu', 'grandconference_create_admin_menu', 2000);
?>