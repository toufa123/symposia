<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.4
 */

namespace radiustheme\eventalk;

use \Redux;

if ( ! class_exists( 'Redux' ) ) {
    return;
}

$opt_name = THEME_PREFIX_VAR;

$theme = wp_get_theme();
$args = array(
    // TYPICAL -> Change these values as you need/desire
    'opt_name'             => $opt_name,
    // This is where your data is stored in the database and also becomes your global variable name.
    'disable_tracking'     => true,
    'display_name'         => $theme->get( 'Name' ),
    // Name that appears at the top of your panel
    'display_version'      => $theme->get( 'Version' ),
    // Version that appears at the top of your panel
    'menu_type'            => 'submenu',
    //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
    'allow_sub_menu'       => true,
    // Show the sections below the admin menu item or not
    'menu_title'           =>esc_html__( 'Theme Options', 'eventalk' ),
    'page_title'           =>esc_html__( 'Theme Options', 'eventalk' ),
    // You will need to generate a Google API key to use this feature.
    // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
    //'google_api_key'       => 'AIzaSyC2GwbfJvi-WnYpScCPBGIUyFZF97LI0xs',
    // Set it you want google fonts to update weekly. A google_api_key value is required.
    'google_update_weekly' => false,
    // Must be defined to add google fonts to the typography module
    'async_typography'     => true,
    // Use a asynchronous font on the front end or font string
    //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
    'admin_bar'            => true,
    // Show the panel pages on the admin bar
    'admin_bar_icon'       => 'dashicons-menu',
    // Choose an icon for the admin bar menu
    'admin_bar_priority'   => 50,
    // Choose an priority for the admin bar menu
    'global_variable'      => '',
    // Set a different name for your global variable other than the opt_name
    'dev_mode'             => false,
    'forced_dev_mode_off'  => false,
    // Show the time the page took to load, etc
    'update_notice'        => false,
    // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
    'customizer'           => true,
    // Enable basic customizer support
    //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
    //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

    // OPTIONAL -> Give you extra features
    'page_priority'        => null,
    // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
    'page_parent'          => 'themes.php',
    // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
    'page_permissions'     => 'manage_options',
    // Permissions needed to access the options panel.
    'menu_icon'            => '',
    // Specify a custom URL to an icon
    'last_tab'             => '',
    // Force your panel to always open to a specific tab (by id)
    'page_icon'            => 'icon-themes',
    // Icon displayed in the admin panel next to your menu_title
    'page_slug'            => THEME_PREFIX . '-options',
    // Page slug used to denote the panel, will be based off page title then menu title then opt_name if not provided
    'save_defaults'        => true,
    // On load save the defaults to DB before user clicks save or not
    'default_show'         => true,
    // If true, shows the default value next to each field that is not the default value.
    'default_mark'         => '',
    // What to print by the field's title if the value shown is default. Suggested: *
    'show_import_export'   => true,
    // Shows the Import/Export panel when not used as a field.

    // CAREFUL -> These options are for advanced use only
    'transient_time'       => 60 * MINUTE_IN_SECONDS,
    'output'               => true,
    // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
    'output_tag'           => true,
    // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
    // 'footer_credit'     => '',
    // Disable the footer credit of Redux. Please leave if you can help it.

    // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
    'database'             => '',
    // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
    'use_cdn'              => true,
    // If you prefer not to use the CDN for Select2, Ace Editor, and others, you may download the Redux Vendor Support plugin yourself and run locally or embed it in your code.
);

Redux::setArgs( $opt_name, $args );


// ------ Custom Functions -----------

// Generate Common post type fields
function rdtheme_redux_post_type_fields( $prefix ){
    return array(
        array(
            'id'       => $prefix. '_layout',
            'type'     => 'button_set',
            'title'    =>esc_html__( 'Layout', 'eventalk' ),
            'options'  => array(
                'left-sidebar'  =>esc_html__( 'Left Sidebar', 'eventalk' ),
                'full-width'    =>esc_html__( 'Full Width', 'eventalk' ),
                'right-sidebar' =>esc_html__( 'Right Sidebar', 'eventalk' ),
            ),
            'default' => 'right-sidebar'
        ),
        array(
            'id'       => $prefix. '_sidebar',
            'type'     => 'select',
            'title'    =>esc_html__( 'Custom Sidebar', 'eventalk' ),
            'options'  => Helper::custom_sidebar_fields(),
            'default'  => 'sidebar',
            'required' => array( $prefix. '_layout', '!=', 'full-width' ),
        ),
        array(
            'id'       => $prefix. '_padding_top',
            'type'     => 'text',
            'title'    =>esc_html__( 'Content Padding Top', 'eventalk' ),
            'validate' => 'numeric',
            'default'  => '120',
        ),
        array(
            'id'       => $prefix. '_padding_bottom',
            'type'     => 'text',
            'title'    =>esc_html__( 'Content Padding Bottom', 'eventalk' ),
            'validate' => 'numeric',
            'default'  => '120'
        ),
        array(
            'id'       => $prefix. '_banner',
            'type'     => 'switch',
            'title'    =>esc_html__( 'Banner', 'eventalk' ),
            'on'       =>esc_html__( 'Enabled', 'eventalk' ),
            'off'      =>esc_html__( 'Disabled', 'eventalk' ),
            'default'  => true,
        ),
        array(
            'id'       => $prefix. '_breadcrumb',
            'type'     => 'switch',
            'title'    =>esc_html__( 'Breadcrumb', 'eventalk' ),
            'on'       =>esc_html__( 'Enabled', 'eventalk' ),
            'off'      =>esc_html__( 'Disabled', 'eventalk' ),
            'default'  => true,
            'required' => array( $prefix. '_banner', 'equals', true )
        ),
        array(
            'id'       => $prefix. '_bgtype',
            'type'     => 'button_set',
            'title'    =>esc_html__( 'Banner Background Type', 'eventalk' ),
            'options'  => array(
                'bgimg'    =>esc_html__( 'Background Image', 'eventalk' ),
                'bgcolor'  =>esc_html__( 'Background Color', 'eventalk' ),
            ),
            'default' => 'bgimg',
            'required' => array( $prefix. '_banner', 'equals', true )
        ),
        array(
            'id'       => $prefix. '_bgimg',
            'type'     => 'media',
            'title'    =>esc_html__( 'Banner Background Image', 'eventalk' ),
            'default'  => array(
                'url'=> Helper::get_img( 'banner.jpg' )
            ),
            'required' => array( $prefix. '_bgtype', 'equals', 'bgimg' )
        ), 
        array(
            'id'       => $prefix. '_bgcolor',
            'type'     => 'color',
            'title'    =>esc_html__('Banner Background Color', 'eventalk'), 
            'validate' => 'color',
            'transparent' => false,
            'default' => '#606060',
            'required' => array( $prefix. '_bgtype', 'equals', 'bgcolor' ),
        ),
    );
}

// ------ Fields -----------

Redux::setSection( $opt_name,
    array(
        'title'   =>esc_html__( 'General', 'eventalk' ),
        'id'      => 'general_section',
        'heading' => '',
        'icon'    => 'el el-network',
        'fields'  => array(
             array(
                'id'       => 'site_layout',
                'type'     => 'switch',
                'title'    =>esc_html__( 'Site Layout', 'eventalk' ),
                'on'       =>esc_html__( 'Full Layout', 'eventalk' ),
                'off'      =>esc_html__( 'Box Layout', 'eventalk' ),
                'default'  => true,
            ),
            array(
                'id'       => 'logo',
                'type'     => 'media',
                'title'    =>esc_html__( 'Main Logo', 'eventalk' ),
                'default'  => array(
                    'url'=> Helper::get_img( 'logo-dark.png' )
                ),
            ),
            array(
                'id'       => 'logo_light',
                'type'     => 'media',
                'title'    =>esc_html__( 'Light Logo', 'eventalk' ),
                'default'  => array(
                    'url'=> Helper::get_img( 'logo-light.png' )
                ),
                'subtitle' =>esc_html__( 'Used when Transparent Header is enabled', 'eventalk' ),
            ),
            array(
                'id'       => 'logo_width',
                'type'     => 'select',
                'title'    =>esc_html__( 'Logo Area Width', 'eventalk'), 
                'subtitle' =>esc_html__( 'Width is defined by the number of bootstrap columns. Please note, navigation menu width will be decreased with the increase of logo width', 'eventalk' ),
                'options'  => array(
                    '1' =>esc_html__( '1 Column', 'eventalk' ),
                    '2' =>esc_html__( '2 Column', 'eventalk' ),
                    '3' =>esc_html__( '3 Column', 'eventalk' ),
                    '4' =>esc_html__( '4 Column', 'eventalk' ),
                ),
                'default'  => '2',
            ),       
            array(
                'id'       => 'preloader',
                'type'     => 'switch',
                'title'    =>esc_html__( 'Preloader', 'eventalk' ),
                'on'       =>esc_html__( 'Enabled', 'eventalk' ),
                'off'      =>esc_html__( 'Disabled', 'eventalk' ),
                'default'  => true,
            ),
            array(
                'id'       => 'preloader_image',
                'type'     => 'media',
                'title'    =>esc_html__( 'Preloader Image', 'eventalk' ),
                'subtitle' =>esc_html__( 'Please upload your choice of preloader image. Transparent GIF format is recommended', 'eventalk' ),
                'default'  => '',
                'required' => array( 'preloader', 'equals', true )
            ),
            array(
                'id'       => 'back_to_top',
                'type'     => 'switch',
                'title'    =>esc_html__( 'Back to Top Arrow', 'eventalk' ),
                'on'       =>esc_html__( 'Enabled', 'eventalk' ),
                'off'      =>esc_html__( 'Disabled', 'eventalk' ),
                'default'  => true,
            ),
            array(
                'id'       => 'inner_fix_banner',
                'type'     => 'switch',
                'title'    =>esc_html__( 'Inner Banner Parallax', 'eventalk' ),
                'on'       =>esc_html__( 'Enabled', 'eventalk' ),
                'off'      =>esc_html__( 'Disabled', 'eventalk' ),
                'default'  => true,
            ),
            array(
                'id'       => 'no_preview_image',
                'type'     => 'media',
                'title'    =>esc_html__( 'Alternative Preview Image', 'eventalk' ),
                'subtitle' =>esc_html__( 'This image will be used as preview image in some archive pages if no featured image exists', 'eventalk' ),
                'default'  => array(
                    'url'=> Helper::get_img( 'noimage.jpg' )
                ),
            ),
            array(
                'id'       => 'speakers_slug',
                'type'     => 'text',
                'title'    => esc_html__( 'Speakers Slug', 'eventalk' ),
                'subtitle' => esc_html__( 'Will be used in URL', 'eventalk' ),
                'default'  => 'speakers',
            ),
            array(
                'id'       => 'speakers_cat_slug',
                'type'     => 'text',
                'title'    => esc_html__( 'Speakers Category Slug', 'eventalk' ),
                'subtitle' => esc_html__( 'Will be used in URL', 'eventalk' ),
                'default'  => 'speakers-cat',
            ),
            array(
                'id'       => 'gallrey_slug',
                'type'     => 'text',
                'title'    => esc_html__( 'Gallrey Slug', 'eventalk' ),
                'subtitle' => esc_html__( 'Will be used in URL', 'eventalk' ),
                'default'  => 'gallrey',
            ),
            array(
                'id'       => 'gallrey_cat_slug',
                'type'     => 'text',
                'title'    => esc_html__( 'Gallrey Category Slug', 'eventalk' ),
                'subtitle' => esc_html__( 'Will be used in URL', 'eventalk' ),
                'default'  => 'gallrey-cat',
            ),
             array(
                'id'       => 'event_slug',
                'type'     => 'text',
                'title'    => esc_html__( 'Event Slug', 'eventalk' ),
                'subtitle' => esc_html__( 'Will be used in URL', 'eventalk' ),
                'default'  => 'event',
            ),
            array(
                'id'       => 'event_cat_slug',
                'type'     => 'text',
                'title'    => esc_html__( 'Event Category Slug', 'eventalk' ),
                'subtitle' => esc_html__( 'Will be used in URL', 'eventalk' ),
                'default'  => 'event-cat',
            ),
        )            
    ) 
);

Redux::setSection( $opt_name,
    array(
        'title'   =>esc_html__( 'Colors', 'eventalk' ),
        'id'      => 'color_section',
        'heading' => '',
        'icon'    => 'el el-eye-open',
        'fields'  => array(
            array(
                'id'       => 'section-color-sitewide',
                'type'     => 'section',
                'title'    =>esc_html__( 'Sitewide Colors', 'eventalk' ),
                'indent'   => true,
            ),
            array(
                'id'       => 'primary_color',
                'type'     => 'color',
                'transparent' => false,
                'title'    =>esc_html__( 'Primary Color', 'eventalk' ),
                'default'  => '#4c1864',
            ),
            array(
                'id'       => 'secondery_color',
                'type'     => 'color',
                'transparent' => false,
                'title'    =>esc_html__( 'Secondery Color', 'eventalk' ),
                'default'  => '#fad03b',
            ),
            array(
                'id'       => 'sitewide_color',
                'type'     => 'button_set',
                'title'    =>esc_html__( 'Other Colors', 'eventalk' ),
                'options'  => array(
                    'primary' =>esc_html__( 'Primary Color', 'eventalk' ),
                    'custom'  =>esc_html__( 'Custom', 'eventalk' ),
                ),
                'default' => 'primary',
                'subtitle' =>esc_html__( 'Selecting Primary Color will hide some color options from the below settings and replace them with Primary/Secondery Color', 'eventalk' ),
            ),
            array(
                'id'       => 'section-color-topbar',
                'type'     => 'section',
                'title'    =>esc_html__( 'Top Bar', 'eventalk' ),
                'indent'   => true,
            ),
            array(
                'id'       => 'top_bar_bgcolor',
                'type'     => 'color',
                'transparent' => false,
                'title'    =>esc_html__( 'Top Bar Background Color', 'eventalk' ),
                'default'  => '#ffffff',
            ),
            array(
                'id'       => 'top_bar_color',
                'type'     => 'color',
                'transparent' => false,
                'title'    =>esc_html__( 'Top Bar Text Color', 'eventalk' ),
                'default'  => '#444444',
            ),
            array(
                'id'       => 'top_bar_icon_color',
                'type'     => 'color',
                'transparent' => false,
                'title'    =>esc_html__( 'Top Bar Icon Color', 'eventalk' ),
                'default'  => '#fad03b',
                'required' => array( 'sitewide_color', '=', 'custom' )
            ),
            array(
                'id'       => 'top_bar_color_tr',
                'type'     => 'color',
                'transparent' => false,
                'title'    =>esc_html__( 'Transparent Top Bar Text Color', 'eventalk' ),
                'subtitle' =>esc_html__( 'Applied when Transparent Header is enabled', 'eventalk' ),
                'default'  => '#444444',
            ),
            array(
                'id'       => 'section-color-menu',
                'type'     => 'section',
                'title'    =>esc_html__( 'Main Menu', 'eventalk' ),
                'indent'   => true,
            ),
            array(
                'id'       => 'menu_color',
                'type'     => 'color',
                'transparent' => false,
                'title'    =>esc_html__( 'Menu Color', 'eventalk' ),
                'default'  => '#111111',
            ),
            array(
                'id'       => 'menu_hover_color',
                'type'     => 'color',
                'transparent' => false,
                'title'    =>esc_html__( 'Menu Hover Color', 'eventalk' ),
                'default'  => '#fad03b',
                'required' => array( 'sitewide_color', '=', 'custom' )
            ),
            array(
                'id'       => 'menu_color_tr',
                'type'     => 'color',
                'transparent' => false,
                'title'    =>esc_html__( 'Transparent Menu Color', 'eventalk' ),
                'subtitle' =>esc_html__( 'Applied when Transparent Header is enabled', 'eventalk' ),
                'default'  => '#ffffff',
            ),
            array(
                'id'       => 'menu_hover_color_tr',
                'type'     => 'color',
                'transparent' => false,
                'title'    =>esc_html__( 'Transparent Menu Hover Color', 'eventalk' ),
                'subtitle' =>esc_html__( 'Applied when Transparent Header is enabled', 'eventalk' ),
                'default'  => '#fad03b',
                'required' => array( 'sitewide_color', '=', 'custom' )
            ),
            array(
                'id'       => 'section-color-submenu',
                'type'     => 'section',
                'title'    =>esc_html__( 'Sub Menu', 'eventalk' ),
                'indent'   => true,
            ),
            array(
                'id'       => 'submenu_color',
                'type'     => 'color',
                'transparent' => false,
                'title'    =>esc_html__( 'Submenu Color', 'eventalk' ),
                'default'  => '#111111',
            ), 
            array(
                'id'       => 'submenu_bgcolor',
                'type'     => 'color',
                'transparent' => false,
                'title'    =>esc_html__( 'Submenu Background Color', 'eventalk' ),
                'default'  => '#ffffff',
                'required' => array( 'sitewide_color', '=', 'custom' )
            ),  
            array(
                'id'       => 'submenu_hover_color',
                'type'     => 'color',
                'transparent' => false,
                'title'    =>esc_html__( 'Submenu Hover Color', 'eventalk' ),
                'default'  => '#111111',
            ), 
            array(
                'id'       => 'submenu_hover_bgcolor',
                'type'     => 'color',
                'transparent' => false,
                'title'    =>esc_html__( 'Submenu Hover Background Color', 'eventalk' ),
                'default'  => '#f0f3f8',
                'required' => array( 'sitewide_color', '=', 'custom' )
            ),
              array(
                'id'       => 'section-bg-mobile-menu',
                'type'     => 'section',
                'title'    =>esc_html__( 'Mobile Menu', 'eventalk' ),
                'indent'   => true,
            ),
            array(
                'id'       => 'mobile_menu_bgcolor',
                'type'     => 'color',
                'transparent' => false,
                'title'    =>esc_html__( 'Mobile Menu Background Color', 'eventalk' ),
                'default'  => '#ffffff',
            ),
            array(
                'id'       => 'section-color-banner',
                'type'     => 'section',
                'title'    =>esc_html__( 'Banner and Breadcrumb', 'eventalk' ),
                'indent'   => true,
            ),
            array(
                'id'       => 'banner_heading_color',
                'type'     => 'color',
                'transparent' => false,
                'title'    =>esc_html__( 'Banner Heading Color', 'eventalk' ),
                'default'  => '#ffffff',
            ), 
            array(
                'id'       => 'breadcrumb_link_color',
                'type'     => 'color',
                'transparent' => false,
                'title'    =>esc_html__( 'Breadcrumb Link Color', 'eventalk' ),
                'default'  => '#fad03b',
                'required' => array( 'sitewide_color', '=', 'custom' )
            ),
            array(
                'id'       => 'breadcrumb_link_hover_color',
                'type'     => 'color',
                'transparent' => false,
                'title'    =>esc_html__( 'Breadcrumb Link Hover Color', 'eventalk' ),
                'default'  => '#ffffff',
            ),
            array(
                'id'       => 'breadcrumb_active_color',
                'type'     => 'color',
                'transparent' => false,
                'title'    =>esc_html__( 'Active Breadcrumb Color', 'eventalk' ),
                'default'  => '#ffffff',
            ),
            array(
                'id'       => 'breadcrumb_seperator_color',
                'type'     => 'color',
                'transparent' => false,
                'title'    =>esc_html__( 'Breadcrumb Seperator Color', 'eventalk' ),
                'default'  => '#ffffff',
            ),
            array(
                'id'       => 'section-color-footer',
                'type'     => 'section',
                'title'    =>esc_html__( 'Footer Area', 'eventalk' ),
                'indent'   => true,
            ),
            array(
                'id'       => 'footer_bgcolor',
                'type'     => 'color',
                'transparent' => false,
                'title'    =>esc_html__( 'Footer Background Color', 'eventalk' ),
                'default'  => '#111111',
            ), 
            array(
                'id'       => 'footer_title_color',
                'type'     => 'color',
                'transparent' => false,
                'title'    =>esc_html__( 'Footer Title Text Color', 'eventalk' ),
                'default'  => '#ffffff',
            ), 
            array(
                'id'       => 'footer_color',
                'type'     => 'color',
                'transparent' => false,
                'title'    =>esc_html__( 'Footer Body Text Color', 'eventalk' ),
                'default'  => '#b3b3b3',
            ), 
            array(
                'id'       => 'footer_link_color',
                'type'     => 'color',
                'transparent' => false,
                'title'    =>esc_html__( 'Footer Body Link Color', 'eventalk' ),
                'default'  => '#b3b3b3',
            ), 
            array(
                'id'       => 'footer_link_hover_color',
                'type'     => 'color',
                'transparent' => false,
                'title'    =>esc_html__( 'Footer Body Link Hover Color', 'eventalk' ),
                'default'  => '#fad03b',
                'required' => array( 'sitewide_color', '=', 'custom' )
            ),
            array(
                'id'       => 'section-color-copyright',
                'type'     => 'section',
                'title'    =>esc_html__( 'Copyright Area', 'eventalk' ),
                'indent'   => true,
            ),
            array(
                'id'       => 'copyright_bgcolor',
                'type'     => 'color',
                'transparent' => false,
                'title'    =>esc_html__( 'Copyright Background Color', 'eventalk' ),
                'default'  => '#111111',
            ),
            array(
                'id'       => 'copyright_color',
                'type'     => 'color',
                'transparent' => false,
                'title'    =>esc_html__( 'Copyright Text Color', 'eventalk' ),
                'default'  => '#8f8f8f',
            ),
            array(
                'id'       => 'section-color-error',
                'type'     => 'section',
                'title'    =>esc_html__( 'Error Page', 'eventalk' ),
                'indent'   => true,
            ),
            array(
                'id'       => 'error_bodybg',
                'type'     => 'color',
                'transparent' => false,
                'title'    =>esc_html__( 'Body Background Color', 'eventalk' ),
                'default'  => '#fad03b',
                'required' => array( 'sitewide_color', '=', 'custom' )
            ),
            array(
                'id'       => 'error_text1_color',
                'type'     => 'color',
                'transparent' => false,
                'title'    =>esc_html__( 'Body Text 1 Color', 'eventalk' ),
                'default'  => '#ffffff',
            ),
            array(
                'id'       => 'error_text2_color',
                'type'     => 'color',
                'transparent' => false,
                'title'    =>esc_html__( 'Body Text 2 Color', 'eventalk' ),
                'default'  => '#ffffff',
            ),          
        )
    )
);

Redux::setSection( $opt_name,
    array(
        'title'   =>esc_html__( 'Contact & Socials', 'eventalk' ),
        'id'      => 'socials_section',
        'heading' => '',
        'desc'    =>esc_html__( 'In case you want to hide any field, just keep that field empty', 'eventalk' ),
        'icon'    => 'el el-twitter',
        'fields'  => array(
            array(
                'id'       => 'phone',
                'type'     => 'text',
                'title'    =>esc_html__( 'Phone', 'eventalk' ),
                'default'  => '',
            ),
            array(
                'id'       => 'email',
                'type'     => 'text',
                'title'    =>esc_html__( 'Email', 'eventalk' ),
                'validate' => 'email',
                'default'  => '',
            ),  
               array(
                'id'       => 'opening',
                'type'     => 'text',
                'title'    =>esc_html__( 'Opening Hours', 'eventalk' ),              
                'default'  => '',
            ),
            array(
                'id'       => 'address',
                'type'     => 'textarea',
                'title'    =>esc_html__( 'Address', 'eventalk' ),
                'default'  => '',
            ),
            array(
                'id'       => 'social_facebook',
                'type'     => 'text',
                'title'    =>esc_html__( 'Facebook', 'eventalk' ),
                'default'  => '',
            ),
            array(
                'id'       => 'social_twitter',
                'type'     => 'text',
                'title'    =>esc_html__( 'Twitter', 'eventalk' ),
                'default'  => '',
            ),
            array(
                'id'       => 'social_gplus',
                'type'     => 'text',
                'title'    =>esc_html__( 'Google Plus', 'eventalk' ),
                'default'  => '',
            ),
            array(
                'id'       => 'social_linkedin',
                'type'     => 'text',
                'title'    =>esc_html__( 'Linkedin', 'eventalk' ),
                'default'  => '',
            ),
            array(
                'id'       => 'social_youtube',
                'type'     => 'text',
                'title'    =>esc_html__( 'Youtube', 'eventalk' ),
                'default'  => '',
            ),
            array(
                'id'       => 'social_pinterest',
                'type'     => 'text',
                'title'    =>esc_html__( 'Pinterest', 'eventalk' ),
                'default'  => '',
            ),
            array(
                'id'       => 'social_instagram',
                'type'     => 'text',
                'title'    =>esc_html__( 'Instagram', 'eventalk' ),
                'default'  => '',
            ),
            array(
                'id'       => 'social_rss',
                'type'     => 'text',
                'title'    =>esc_html__( 'RSS', 'eventalk' ),
                'default'  => '',
            ),
        )            
    ) 
);

Redux::setSection( $opt_name,
    array(
        'title'   =>esc_html__( 'Header', 'eventalk' ),
        'id'      => 'header_section',
        'heading' => '',
        'icon'    => 'el el-flag',
        'fields'  => array(
            array(
                'id'       => 'sticky_menu',
                'type'     => 'switch',
                'title'    =>esc_html__( 'Sticky Header', 'eventalk' ),
                'on'       =>esc_html__( 'Enabled', 'eventalk' ),
                'off'      =>esc_html__( 'Disabled', 'eventalk' ),
                'default'  => true,
                'subtitle' =>esc_html__( 'Show header when scroll down', 'eventalk' ),
            ),   
            array(
                'id'       => 'mobile_sticky_menu',
                'type'     => 'switch',
                'title'    =>esc_html__( 'Mobile Sticky Header', 'eventalk' ),
                'on'       =>esc_html__( 'Enabled', 'eventalk' ),
                'off'      =>esc_html__( 'Disabled', 'eventalk' ),
                'default'  => false,
                'subtitle' =>esc_html__( 'Show Mobile header when scroll down', 'eventalk' ),
            ),
            array(
                'id'       => 'tr_header',
                'type'     => 'switch',
                'title'    =>esc_html__( 'Transparent Header', 'eventalk' ),
                'on'       =>esc_html__( 'Enabled', 'eventalk' ),
                'off'      =>esc_html__( 'Disabled', 'eventalk' ),
                'default'  => false,
                'subtitle' =>esc_html__( 'You have to enable Banner or Slider in page to make it work properly. You can override this settings in individual pages', 'eventalk' ),
            ),           
            array(
                'id'       => 'header_style',
                'type'     => 'image_select',
                'title'    =>esc_html__( 'Header Layout', 'eventalk' ),
                'default'  => '1',
                'options' => array(
                    '1' => array(
                        'title' => '<b>'.esc_html__( 'Layout 1', 'eventalk' ) . '</b>',
                        'img' => Helper::get_img( 'header-1.jpg' ),
                    ),
                    '2' => array(
                        'title' => '<b>'.esc_html__( 'Layout 2', 'eventalk' ) . '</b>',
                        'img' => Helper::get_img( 'header-2.jpg' ),
                    ),
                    '3' => array(
                        'title' => '<b>'.esc_html__( 'Layout 3', 'eventalk' ) . '</b>',
                        'img' => Helper::get_img( 'header-3.jpg' ),
                    ),
                    '4' => array(
                        'title' => '<b>'.esc_html__( 'Layout 4', 'eventalk' ) . '</b>',
                        'img' => Helper::get_img( 'header-4.jpg' ),
                    ),
                    '5' => array(
                        'title' => '<b>'.esc_html__( 'Layout 5', 'eventalk' ) . '</b>',
                        'img' => Helper::get_img( 'header-5.jpg' ),
                    ),
                    '6' => array(
                        'title' => '<b>'.esc_html__( 'Layout 6', 'eventalk' ) . '</b>',
                        'img' => Helper::get_img( 'header-6.jpg' ),
                    ), 
                    '7' => array(
                        'title' => '<b>'.esc_html__( 'Layout 7', 'eventalk' ) . '</b>',
                        'img' => Helper::get_img( 'header-7.jpg' ),
                    ),
                ),
                'subtitle' =>esc_html__( 'You can override this settings in individual pages', 'eventalk' ),
            ),
            array(
                'id'       => 'search_icon',
                'type'     => 'switch',
                'title'    =>esc_html__( 'Search Icon', 'eventalk' ),
                'on'       =>esc_html__( 'Enabled', 'eventalk' ),
                'off'      =>esc_html__( 'Disabled', 'eventalk' ),
                'default'  => true,
            ), 
            array(
                'id'       => 'cart_icon',
                'type'     => 'switch',
                'title'    =>esc_html__( 'Cart Icon', 'eventalk' ),
                'on'       =>esc_html__( 'Enabled', 'eventalk' ),
                'off'      =>esc_html__( 'Disabled', 'eventalk' ),
                'default'  => true,
            ), 
            array(
                'id'       => 'vertical_menu_icon',
                'type'     => 'switch',
                'title'    =>esc_html__( 'Vertical Menu Icon', 'eventalk' ),
                'on'       =>esc_html__( 'Enabled', 'eventalk' ),
                'off'      =>esc_html__( 'Disabled', 'eventalk' ),
                'default'  => true,
            ),
            array(
                'id'       => 'header_btn',
                'type'     => 'switch',
                'title'    =>esc_html__( 'Buy Tickets Button', 'eventalk' ),
                'on'       =>esc_html__( 'Enabled', 'eventalk' ),
                'off'      =>esc_html__( 'Disabled', 'eventalk' ),
                'default'  => false,
            ),
             array(
                'id'       => 'header_buttontext',
                'type'     => 'text',
                'title'    =>esc_html__( 'Button Text', 'eventalk' ),
                'default'  =>esc_html__( 'Buy Tickets', 'eventalk' ),
                'required' => array( 'header_btn', 'equals', true ),
            ),
             array(
                'id'       => 'header_buttonUrl',
                'type'     => 'text',                
                'default'  => '',
                'title'    =>esc_html__( 'Button Url', 'eventalk' ),               
                'required' => array( 'header_btn', 'equals', true ),
            ),
              array(
                'id'       => 'header_new_window',
                'type'     => 'switch',
                'title'    =>esc_html__( 'Open in new window', 'eventalk' ),
                'on'       =>esc_html__( 'Enabled', 'eventalk' ),
                'off'      =>esc_html__( 'Disabled', 'eventalk' ),
                'default'  => false,
                'required' => array( 'header_btn', 'equals', true ),
            ),             
        )
    ) 
);

Redux::setSection( $opt_name,
    array(
        'title'   =>esc_html__( 'Main Menu', 'eventalk' ),
        'id'      => 'menu_section',
        'heading' => '',
        'icon'    => 'el el-book',
        'fields'  => array(
            array(
                'id'       => 'section-mainmenu',
                'type'     => 'section',
                'title'    =>esc_html__( 'Main Menu Items', 'eventalk' ),
                'indent'   => true,
            ),
            array(
                'id'       => 'menu_typo',
                'type'     => 'typography',
                'title'    =>esc_html__( 'Menu Font', 'eventalk' ),
                'text-align' => false,
                'color'   => false,
                'text-transform' => true,
                'default'     => array(
                    'font-family' => 'Open Sans',
                    'google'      => true,
                    'font-size'   => '16px',
                    'font-weight' => '600',
                    'line-height' => '24px',
                    'text-transform' => 'uppercase',
                ),
            ),
            array(
                'id'       => 'section-submenu',
                'type'     => 'section',
                'title'    =>esc_html__( 'Sub Menu Items', 'eventalk' ),
                'indent'   => true,
            ), 
            array(
                'id'       => 'submenu_typo',
                'type'     => 'typography',
                'title'    =>esc_html__( 'Submenu Font', 'eventalk' ),
                'text-align'   => false,
                'color'   => false,
                'text-transform' => true,
                'default'     => array(
                    'font-family' => 'Open Sans',
                    'google'      => true,
                    'font-size'   => '14px',
                    'font-weight' => '400',
                    'line-height' => '22px',
                    'text-transform' => 'none',
                ),
            ),
            array(
                'id'       => 'section-resmenu',
                'type'     => 'section',
                'title'    =>esc_html__( 'Mobile Menu', 'eventalk' ),
                'indent'   => true,
            ), 
            array(
                'id'       => 'resmenu_width',
                'type'     => 'slider',
                'title'    =>esc_html__( 'Screen width in which mobile menu activated', 'eventalk' ),
                'subtitle' =>esc_html__( 'Recommended value is: 992', 'eventalk' ),
                'default'  => 992,
                'min'      => 0,
                'step'     => 1,
                'max'      => 2000,
            ),
            array(
                'id'       => 'resmenu_typo',
                'type'     => 'typography',
                'title'    =>esc_html__( 'Mobile Menu Font', 'eventalk' ),
                'text-align' => false,
                'color'   => false,
                'text-transform' => true,
                'default'     => array(
                    'font-family' => 'Open Sans',
                    'google'      => true,
                    'font-size'   => '14px',
                    'font-weight' => '400',
                    'line-height' => '21px',
                    'text-transform' => 'none',
                ),
            ),          
        )            
    ) 
);

Redux::setSection( $opt_name,
    array(
        'title'   =>esc_html__( 'Footer', 'eventalk' ),
        'id'      => 'footer_section',
        'heading' => '',
        'icon'    => 'el el-caret-down',
        'fields'  => array(
            array(
                'id'       => 'section-footer-area',
                'type'     => 'section',
                'title'    =>esc_html__( 'Footer Area', 'eventalk' ),
                'indent'   => true,
            ),
            array(
                'id'       => 'footer_area',
                'type'     => 'switch',
                'title'    =>esc_html__( 'Display Footer Area', 'eventalk' ),
                'on'       =>esc_html__( 'Enabled', 'eventalk' ),
                'off'      =>esc_html__( 'Disabled', 'eventalk' ),
                'default'  => true,
            ),
             array(
                'id'       => 'footer_logo',
                'type'     => 'media',
                'title'    =>esc_html__( 'Footer Logo', 'eventalk' ),
                'default'  => array(
                    'url'=> Helper::get_img( 'footer-logo.png' )
                ),
            ),
             array(
                'id'       => 'footer_style',
                'type'     => 'image_select',
                'title'    =>esc_html__( 'Footer Layout', 'eventalk' ),
                'default'  => '3',
                'options' => array(
                    '1' => array(
                        'title' => '<b>'.esc_html__( 'Layout 1', 'eventalk' ) . '</b>',
                        'img' => Helper::get_img( 'footer-1.jpg' ),
                    ),
                    '2' => array(
                        'title' => '<b>'.esc_html__( 'Layout 2', 'eventalk' ) . '</b>',
                        'img' => Helper::get_img( 'footer-2.jpg' ),
                    ), 
                    '3' => array(
                        'title' => '<b>'.esc_html__( 'Layout 3', 'eventalk' ) . '</b>',
                        'img' => Helper::get_img( 'footer-3.jpg' ),
                    ),  
                    '4' => array(
                        'title' => '<b>'.esc_html__( 'Layout 4', 'eventalk' ) . '</b>',
                        'img' => Helper::get_img( 'footer-4.jpg' ),
                    ),
                  
                ),
                'subtitle' =>esc_html__( 'You can override this settings in individual pages', 'eventalk' ),
            ),
            array(
                'id'       => 'footer_column',
                'type'     => 'select',
                'title'    =>esc_html__( 'Number of Columns', 'eventalk' ),
                'options'  => array(
                    '1' =>esc_html__( '1 Column', 'eventalk' ),
                    '2' =>esc_html__( '2 Columns', 'eventalk' ),
                    '3' =>esc_html__( '3 Columns', 'eventalk' ),
                    '4' =>esc_html__( '4 Columns', 'eventalk' ),
                ),
                'default'  => '4',
                'required' => array( 'footer_style', 'equals', '2' )
            ),
            array(
                'id'       => 'section-copyright-area',
                'type'     => 'section',
                'title'    =>esc_html__( 'Copyright Area', 'eventalk' ),
                'indent'   => true,
            ),
            array(
                'id'       => 'copyright_area',
                'type'     => 'switch',
                'title'    =>esc_html__( 'Display Copyright Area', 'eventalk' ),
                'on'       =>esc_html__( 'Enabled', 'eventalk' ),
                'off'      =>esc_html__( 'Disabled', 'eventalk' ),
                'default'  => true,
            ),
            array(
                'id'       => 'copyright_text',
                'type'     => 'textarea',
                'title'    =>esc_html__( 'Copyright Text', 'eventalk' ),
                'default'  => '&copy; Copyright eventalk 2020. All Right Reserved. Designed and Developed by <a target="_blank" href="' . THEME_AUTHOR_URI . '">RadiusTheme</a>',
                'required' => array( 'copyright_area', 'equals', true )
            ),
        )
    )
);
Redux::setSection( $opt_name,
    array(
        'title'  =>esc_html__( 'Typography', 'eventalk' ),
        'id'     => 'typo_section',
        'icon'   => 'el el-text-width',
        'fields' => array(
            array(
                'id'       => 'typo_body',
                'type'     => 'typography',
                'title'    =>esc_html__( 'Body', 'eventalk' ),
                'text-align'  => false,
                'font-weight' => false,
                'color'   => false,
                'default' => array(
                    'font-family' => 'Roboto',
                    'google'      => true,
                    'font-size'   => '16px',
                    'font-weight' => '400',
                    'line-height' => '28px',
                ),
            ),
            array(
                'id'       => 'typo_h1',
                'type'     => 'typography',
                'title'    =>esc_html__( 'Header h1', 'eventalk' ),
                'text-align'  => false,
                'font-weight' => false,
                'color'    => false,
                'default'  => array(
                    'font-family' => 'Poppins',
                    'google'      => true,
                    'font-size'   => '52px',
                    'font-weight' => '700',
                    'line-height' => '50px',
                ),
            ),
            array(
                'id'       => 'typo_h2',
                'type'     => 'typography',
                'title'    =>esc_html__( 'Header h2', 'eventalk' ),
                'text-align'  => false,
                'font-weight' => false,
                'color'   => false,
                'default' => array(
                    'font-family' => 'Poppins',
                    'google'      => true,
                    'font-size'   => '36px',
                    'font-weight' => '700',
                    'line-height' => '38px',
                ),
            ),
            array(
                'id'       => 'typo_h3',
                'type'     => 'typography',
                'title'    =>esc_html__( 'Header h3', 'eventalk' ),
                'text-align'  => false,
                'font-weight' => false,
                'color'   => false,
                'default' => array(
                    'font-family' => 'Poppins',
                    'google'      => true,
                    'font-size'   => '28px',
                    'font-weight' => '700',
                    'line-height' => '36px',
                ),
            ),
            array(
                'id'       => 'typo_h4',
                'type'     => 'typography',
                'title'    =>esc_html__( 'Header h4', 'eventalk' ),
                'text-align'  => false,
                'font-weight' => false,
                'color'   => false,
                'default' => array(
                    'font-family' => 'Poppins',
                    'google'      => true,
                    'font-size'   => '18px',
                    'font-weight' => '700',
                    'line-height' => '26px',
                ),
            ),
            array(
                'id'       => 'typo_h5',
                'type'     => 'typography',
                'title'    =>esc_html__( 'Header h5', 'eventalk' ),
                'text-align'  => false,
                'font-weight' => false,
                'color'   => false,
                'default' => array(
                    'font-family' => 'Poppins',
                    'google'      => true,
                    'font-size'   => '16px',
                    'font-weight' => '500',
                    'line-height' => '24px',
                ),
            ),
            array(
                'id'       => 'typo_h6',
                'type'     => 'typography',
                'title'    =>esc_html__( 'Header h6', 'eventalk' ),
                'text-align'  => false,
                'font-weight' => false,
                'color'   => false,
                'default' => array(
                    'font-family' => 'Poppins',
                    'google'      => true,
                    'font-size'   => '16px',
                    'font-weight' => '500',
                    'line-height' => '26px',
                ),
            )
        )
    )
);
Redux::setSection( $opt_name,
    array(
        'title' =>esc_html__( 'Layout Defaults', 'eventalk' ),
        'id'    => 'layout_defaults',
        'icon'  => 'el el-th',
    )
);

// Page
$rdtheme_page_fields = rdtheme_redux_post_type_fields( 'page' );
$rdtheme_page_fields[0]['default'] = 'full-width';
Redux::setSection( $opt_name,
    array(
        'title'      =>esc_html__( 'Page', 'eventalk' ),
        'id'         => 'pages_section',
        'subsection' => true,
        'fields'     => $rdtheme_page_fields     
    )
);

//Post Archive
$rdtheme_post_archive_fields = rdtheme_redux_post_type_fields( 'blog' );
Redux::setSection( $opt_name,
    array(
        'title'      =>esc_html__( 'Blog / Archive', 'eventalk' ),
        'id'         => 'blog_section',
        'subsection' => true,
        'fields'     => $rdtheme_post_archive_fields
    )
);

// Single Post
$rdtheme_single_post_fields = rdtheme_redux_post_type_fields( 'single_post' );
Redux::setSection( $opt_name,
    array(
        'title'      =>esc_html__( 'Post Single', 'eventalk' ),
        'id'         => 'single_post_section',
        'subsection' => true,
        'fields'     => $rdtheme_single_post_fields           
    ) 
);


// Team Archive
$rdtheme_fields = rdtheme_redux_post_type_fields( 'speakers_archive' );
$rdtheme_fields[0]['default'] = 'full-width';

$rdtheme_fields2 = array(
    array(
        'id'       => 'speakers_arc_section',
        'type'     => 'section',
        'title'    =>esc_html__( 'More Options', 'eventalk' ),
        'indent'   => true,
    ),
    array(
        'id'       => 'speakers_arc_style',
        'type'     => 'button_set',
        'title'    =>esc_html__( 'Style', 'eventalk' ),
        'options'  => array(
            'style1' =>esc_html__( 'Style 1', 'eventalk' ),
            'style2' =>esc_html__( 'Style 2', 'eventalk' ),
            'style3' =>esc_html__( 'Style 3', 'eventalk' ),
        ),
        'default'  => 'style1'
    ),
    array(
        'id'       => 'spk_arv_bgcolor',
        'type'     => 'color',
        'title'    =>esc_html__('Archive Background Color', 'eventalk'), 
        'validate' => 'color',
        'transparent' => false,
        'default' => '#4c1864',
        'required' => array( 'speakers_arc_style', 'equals', 'style1' )
    ),
    array(
        'id'       => 'spk_arv_bgimg',
        'type'     => 'media',
        'title'    =>esc_html__( 'Archive Background Image', 'eventalk' ),
        'default'  => array(
            'url'=> Helper::get_img( 'banner.jpg' )
        ),
       'required' => array( 'speakers_arc_style', 'equals', 'style1' )
    ), 
     array(
        'id'       => 'spk_arv_ovr__bgimg',
        'type'     => 'media',
        'title'    =>esc_html__( 'Archive Overlayer Image', 'eventalk' ),
        'default'  => array(
            'url'=> Helper::get_img( 'figure-icon3.png' )
        ),
       'required' => array( 'speakers_arc_style', 'equals', 'style1' )
    ), 
    array(
        'id'       => 'speakers_arc_number',
        'type'     => 'text',
        'title'    =>esc_html__( 'Number of items per page', 'eventalk' ),
        'validate' => 'numeric',
        'default'  => '9'
    ),
    array(
        'id'       => 'speakers_arc_orderby',
        'type'     => 'select',
        'title'    =>esc_html__( 'Order By', 'eventalk' ),
        'options'  => array(
            'date'        =>esc_html__( 'Date (Recents comes first)', 'eventalk' ),
            'title'       =>esc_html__( 'Title', 'eventalk' ),
            'menu_order'  =>esc_html__( 'Custom Order (Available via Order field inside Page Attributes box)', 'eventalk' ),
        ),
        'default'  => 'date'
    ),
    array(
        'id'       => 'speakers_arc_designation_display',
        'type'     => 'switch',
        'title'    =>esc_html__( 'Designation Display', 'eventalk' ),
        'on'       =>esc_html__( 'Enabled', 'eventalk' ),
        'off'      =>esc_html__( 'Disabled', 'eventalk' ),
        'default'  => true,
    ),
    array(
        'id'       => 'speakers_arc_social_display',
        'type'     => 'switch',
        'title'    =>esc_html__( 'Social Media Display', 'eventalk' ),
        'on'       =>esc_html__( 'Enabled', 'eventalk' ),
        'off'      =>esc_html__( 'Disabled', 'eventalk' ),
        'default'  => true,
    ),     
    array(
        'id'       => 'speakers_content_number',
        'type'     => 'text',
        'title'    =>esc_html__( 'Number of Content', 'eventalk' ),
        'validate' => 'numeric',
        'default'  => '10',     
        'required' => array( 'speakers_arc_content_display', 'equals', true )
    ),
);

$rdtheme_fields = array_merge( $rdtheme_fields, $rdtheme_fields2 );
Redux::setSection( $opt_name,
    array(
    'title'         =>esc_html__( 'Speakers Archive', 'eventalk' ),
    'id'            => 'speakers_archive_section',
    'subsection'    => true,
    'fields'        => $rdtheme_fields
    )
);

// Single speakers

$rdtheme_fields = rdtheme_redux_post_type_fields( 'speaker' );
$rdtheme_fields2 = array(      
    
     array(
        'id'       => 'single_speaker_style',
        'type'     => 'button_set',
        'title'    =>esc_html__( 'Speaker Single Style', 'eventalk' ),
        'options'  => array(
            'style1' =>esc_html__( 'Style 1', 'eventalk' ),                 
            'style2' =>esc_html__( 'Style 2', 'eventalk' ),
          
        ),
        'default'  => 'style1'
    ), 

     array(
        'id'       => 'speakers_elementor_content',
        'type'     => 'switch',
        'title'    =>esc_html__( 'Full Content', 'eventalk' ),
        'on'       =>esc_html__( 'Enabled', 'eventalk' ),
        'off'      =>esc_html__( 'Disabled', 'eventalk' ),
        'required' => array('single_speaker_style', 'equals', 'style1' ),
        'default'  => false,
    ),

    
);
$rdtheme_fields = array_merge( $rdtheme_fields, $rdtheme_fields2 );
Redux::setSection( $opt_name,
    array(
    'title'         =>esc_html__( 'Single Speakers', 'eventalk' ),
    'id'            => 'single_speakers_section',
    'subsection'    => true,
    'fields'        => $rdtheme_fields
    )
);


// Event Archive
$rdtheme_fields = rdtheme_redux_post_type_fields( 'event_archive' );
// Class Single
$rdtheme_fields2        = array(
    array(
        'id' => 'class_slug',
        'type' => 'text',
        'title' =>esc_html__( 'Slug', 'eventalk' ),
        'default' => 'event' 
    ),
    array(
        'id' => 'class_time_format',
        'type' => 'radio',
        'title' =>esc_html__( 'Events Time Format', 'eventalk' ),
        'options' => array(
            '12' =>esc_html__( '12-hour', 'eventalk' ),
            '24' =>esc_html__( '24-hour', 'eventalk' ) 
        ),
        'default' => '12' 
    ) 
);

$rdtheme_fields = array_merge( $rdtheme_fields, $rdtheme_fields2 );

Redux::setSection( $opt_name,
    array(
    'title'         =>esc_html__( 'Events Archive', 'eventalk' ),
    'id'            => 'event_archive_section',
    'subsection'    => true,
    'fields'        => $rdtheme_fields
    )
);


// Event Single
$rdtheme_fields2        = array(
    array(
        'id'       => 'event_img_option',
        'type'     => 'switch',
        'title'    =>esc_html__( 'Single Image', 'eventalk' ),
        'on'       =>esc_html__( 'Schedule Images', 'eventalk' ),
        'off'      =>esc_html__( 'Featured Image', 'eventalk' ),
        'default'  => false,
        'subtitle' =>esc_html__( 'You can Select Schedule Images slider or  Event Featured Image', 'eventalk' ),
    ),
     array(
        'id'       => 'single_arc_style',
        'type'     => 'button_set',
        'title'    =>esc_html__( 'Single Style', 'eventalk' ),
        'options'  => array(
            'style1' =>esc_html__( 'Style 1', 'eventalk' ),
            'style7' =>esc_html__( 'Style 1 - (Conditional)', 'eventalk' ),           
            'style2' =>esc_html__( 'Style 2', 'eventalk' ),
            'style5' =>esc_html__( 'Style 2 - (Conditional)', 'eventalk' ),           
            'style3' =>esc_html__( 'Style 3', 'eventalk' ),           
            'style6' =>esc_html__( 'Style 3 - (Conditional)', 'eventalk' ),           
            'style4' =>esc_html__( 'Style 4', 'eventalk' ),
        ),
        'default'  => 'style1'
    ), 

     array(
        'id'       => 'schedule_label_display',
        'type'     => 'button_set',
        'title'    =>esc_html__( 'Schedule Label Display', 'eventalk' ),
        'subtitle' =>esc_html__( 'Auto Display or Label Display (Meta Schedule Label). Default: Auto Display', 'eventalk' ),
        'options'  => array(
            'label_auto' =>esc_html__( 'Auto Label', 'eventalk' ),
            'label_meta' =>esc_html__( 'Meta Schedule Label', 'eventalk' ),
                      
        ),
        'default'  => 'label_auto'
    ),
      array(
        'id'       => 'single_schedule_info',
        'type'     => 'switch',
        'title'    =>esc_html__( 'Single Schedule Info', 'eventalk' ),
        'on'       =>esc_html__( 'Enabled', 'eventalk' ),
        'off'      =>esc_html__( 'Disabled', 'eventalk' ),
        'default'  => true,

    ),  
    array(
        'id'       => 'single_event_table',
        'type'     => 'switch',
        'title'    =>esc_html__( 'Single Event Table', 'eventalk' ),
        'on'       =>esc_html__( 'Enabled', 'eventalk' ),
        'off'      =>esc_html__( 'Disabled', 'eventalk' ),
        'default'  => true,

    ),   
   
    array(
        'id'       => 'single_img_slider',
        'type'     => 'switch',
        'title'    =>esc_html__( 'Single Image Slider', 'eventalk' ),
        'on'       =>esc_html__( 'Enabled', 'eventalk' ),
        'off'      =>esc_html__( 'Disabled', 'eventalk' ),
        'default'  => true,

    ),
    array(
        'id'       => 'single_event_socials',
        'type'     => 'switch',
        'title'    =>esc_html__( 'Single Event Socials Display', 'eventalk' ),
        'on'       =>esc_html__( 'Enabled', 'eventalk' ),
        'off'      =>esc_html__( 'Disabled', 'eventalk' ),
        'default'  => true,

    ), 
 
    array(
        'id'       => 'single_event_btn',
        'type'     => 'switch',
        'title'    =>esc_html__( 'Buy Now Ticket Button', 'eventalk' ),
        'on'       =>esc_html__( 'Enabled', 'eventalk' ),
        'off'      =>esc_html__( 'Disabled', 'eventalk' ),
        'default'  => true,
    ),     
    array(
        'id'       => 'single_event_btn_text',
        'type'     => 'text',
        'title'    =>esc_html__( 'Button Text', 'eventalk' ),
        'validate' => 'text',
        'default'  => 'Buy Now Ticket',     
        'required' => array( 'single_event_btn', 'equals', true )
    ),

);

$rdtheme_fields = rdtheme_redux_post_type_fields( 'event' );
$rdtheme_fields = array_merge( $rdtheme_fields, $rdtheme_fields2 );
$rdtheme_fields[0]['default'] = 'full-width';
// Single Event
//unset($rdtheme_fields[0]);
Redux::setSection( $opt_name,
    array(
    'title'        =>esc_html__( 'Single Event', 'eventalk' ),
    'id'           => 'single_event_section',
    'subsection'   => true,
    'fields'       => $rdtheme_fields           
    ) 
);

// Search
$rdtheme_search_fields = rdtheme_redux_post_type_fields( 'search' );
Redux::setSection( $opt_name,
    array(
        'title'      =>esc_html__( 'Search Layout', 'eventalk' ),
        'id'         => 'search_section',
        'subsection' => true,
        'fields'     => $rdtheme_search_fields            
    )
);

// Error 404 Layout
$rdtheme_error_fields = rdtheme_redux_post_type_fields( 'error' );
unset($rdtheme_error_fields[0]);
Redux::setSection( $opt_name,
    array(
        'title'      =>esc_html__( 'Error 404 Layout', 'eventalk' ),
        'id'         => 'error_section',
        'subsection' => true,
        'fields'     => $rdtheme_error_fields           
    )
);

if ( class_exists( 'WooCommerce' ) ) {
    // Woocommerce Shop Archive
    $rdtheme_shop_archive_fields = rdtheme_redux_post_type_fields( 'shop' );
    Redux::setSection( $opt_name,
        array(
            'title'      => esc_html__( 'Shop Archive', 'eventalk' ),
            'id'         => 'shop_section',
            'subsection' => true,
            'fields'     => $rdtheme_shop_archive_fields
        ) 
    );

    // Woocommerce Product
    $rdtheme_product_fields = rdtheme_redux_post_type_fields( 'product' );
    Redux::setSection( $opt_name,
        array(
            'title'      => esc_html__( 'Product Single', 'eventalk' ),
            'id'         => 'product_section',
            'subsection' => true,
            'fields'     => $rdtheme_product_fields
        ) 
    );
}

// Blog Settings
Redux::setSection( $opt_name,
    array(
        'title'   =>esc_html__( 'Blog Settings', 'eventalk' ),
        'id'      => 'blog_settings_section',
        'icon'    => 'el el-tags',
        'heading' => '',
        'fields'  => array(
            array(
                'id'       =>'blog_style',
                'type'     => 'image_select',
                'title'    =>esc_html__( 'Blog/Archive Layout', 'eventalk' ),
                'default'  => 'style1',
                'options'  => array(
                    'style1' => array(
                        'title' => '<b>'.esc_html__( 'Layout 1', 'eventalk' ) . '</b>',
                        'img'   => Helper::get_img( 'blog1.jpg' ),
                    ),
                    'style2' => array(
                        'title' => '<b>'.esc_html__( 'Layout 2', 'eventalk' ) . '</b>',
                        'img'   => Helper::get_img( 'blog2.jpg' ),
                    ),
                    'style3' => array(
                        'title' => '<b>'.esc_html__( 'Layout 3', 'eventalk' ) . '</b>',
                        'img'   => Helper::get_img( 'blog3.jpg' ),
                    ),
                ),
            ),
            array(
                'id'       => 'blog_date',
                'type'     => 'switch',
                'title'    =>esc_html__( 'Show Date', 'eventalk' ),
                'on'       =>esc_html__( 'On', 'eventalk' ),
                'off'      =>esc_html__( 'Off', 'eventalk' ),
                'default'  => true,
            ), 
            array(
                'id'       => 'blog_author_name',
                'type'     => 'switch',
                'title'    =>esc_html__( 'Show Author Name', 'eventalk' ),
                'on'       =>esc_html__( 'On', 'eventalk' ),
                'off'      =>esc_html__( 'Off', 'eventalk' ),
                'default'  => true,
            ),
            array(
                'id'       => 'blog_cats',
                'type'     => 'switch',
                'title'    =>esc_html__( 'Show Categories', 'eventalk' ),
                'on'       =>esc_html__( 'On', 'eventalk' ),
                'off'      =>esc_html__( 'Off', 'eventalk' ),
                'default'  => true,
            ),
            array(
                'id'       => 'blog_comment_num',
                'type'     => 'switch',
                'title'    =>esc_html__( 'Show Comment Number', 'eventalk' ),
                'on'       =>esc_html__( 'On', 'eventalk' ),
                'off'      =>esc_html__( 'Off', 'eventalk' ),
                'default'  => true,
            ),
             array(
                'id'       => 'blog_content_number',
                'type'     => 'text',
                'title'    =>esc_html__( 'Number of Content', 'eventalk' ),
                'validate' => 'numeric',
                'default'  => '20', 
            ),
        )
    ) 
);

// Post Settings
Redux::setSection( $opt_name,
    array(
        'title'   =>esc_html__( 'Post Settings', 'eventalk' ),
        'id'      => 'post_settings_section',
        'icon'    => 'el el-file-edit',
        'heading' => '',
        'fields'  => array(
            array(
                'id'      => 'post_date',
                'type'    => 'switch',
                'title'   =>esc_html__( 'Show Post Date', 'eventalk' ),
                'on'      =>esc_html__( 'On', 'eventalk' ),
                'off'     =>esc_html__( 'Off', 'eventalk' ),
                'default' => true,
            ),
            array(
                'id'      => 'post_author_name',
                'type'    => 'switch',
                'title'   =>esc_html__( 'Show Author Name', 'eventalk' ),
                'on'      =>esc_html__( 'On', 'eventalk' ),
                'off'     =>esc_html__( 'Off', 'eventalk' ),
                'default' => true,
            ),
            array(
                'id'      => 'post_cats',
                'type'    => 'switch',
                'title'   =>esc_html__( 'Show Categories', 'eventalk' ),
                'on'      =>esc_html__( 'On', 'eventalk' ),
                'off'     =>esc_html__( 'Off', 'eventalk' ),
                'default' => true,
            ),
            array(
                'id'      => 'post_comment_num',
                'type'    => 'switch',
                'title'   =>esc_html__( 'Show Comment Number', 'eventalk' ),
                'on'      =>esc_html__( 'On', 'eventalk' ),
                'off'     =>esc_html__( 'Off', 'eventalk' ),
                'default' => true,
            ), 
            array(
                'id'      => 'post_tags',
                'type'    => 'switch',
                'title'   =>esc_html__( 'Show Tags', 'eventalk' ),
                'on'      =>esc_html__( 'On', 'eventalk' ),
                'off'     =>esc_html__( 'Off', 'eventalk' ),
                'default' => true,
            ),
        )            
    ) 
);

// Error
Redux::setSection( $opt_name,
    array(
        'title'   =>esc_html__( 'Error Page Settings', 'eventalk' ),
        'id'      => 'error_srttings_section',
        'heading' => '',
        'icon'    => 'el el-error-alt',
        'fields'  => array( 
            array(
                'id'       => 'error_title',
                'type'     => 'text',
                'title'    =>esc_html__( 'Page Title', 'eventalk' ),
                'default'  =>esc_html__( 'Error 404', 'eventalk' ),
            ), 
            array(
                'id'       => 'error_bodybanner',
                'type'     => 'media',
                'title'    =>esc_html__( 'Body Banner', 'eventalk' ),
                'default'  => array(
                    'url'=> Helper::get_img( '404.png' )
                ),
            ), 
            array(
                'id'       => 'error_text1',
                'type'     => 'text',
                'title'    =>esc_html__( 'Body Text 1', 'eventalk' ),
                'default'  =>esc_html__( 'OOPS! THAT PAGE CAN’T BE FOUND.', 'eventalk' ),
            ),
            array(
                'id'       => 'error_text2',
                'type'     => 'text',
                'title'    =>esc_html__( 'Body Text 2', 'eventalk' ),
                'default'  =>esc_html__( 'The page you are looking is not available or has been removed. Try going to Home Page by using the button below.', 'eventalk' ),
            ),   
            array(
                'id'       => 'error_buttontext',
                'type'     => 'text',
                'title'    =>esc_html__( 'Button Text', 'eventalk' ),
                'default'  =>esc_html__( 'GO TO HOME PAGE', 'eventalk' ),
            ),
            array(
                'id'       => 'error_buttonlink',
                'type'     => 'text',
                'title'    =>esc_html__( 'Button Link', 'eventalk' ),                
            )
        )
    )
);

do_action( 'rt_after_redux_options_loaded', 'eventalk' );

if ( class_exists( 'WooCommerce' ) ) {
    // Woocommerce Settings
    Redux::setSection( $opt_name,
        array(
            'title'   => esc_html__( 'WooCommerce', 'eventalk' ),
            'id'      => 'woo_Settings_section',
            'heading' => '',
            'icon'    => 'el el-shopping-cart',
            'fields'  => array(
                array(
                    'id'       => 'wc_sec_general',
                    'type'     => 'section',
                    'title'    => esc_html__( 'General', 'eventalk' ),
                    'indent'   => true,
                ),
                array(
                    'id'       => 'wc_num_product',
                    'type'     => 'text',
                    'title'    => esc_html__( 'Number of Products Per Page', 'eventalk' ),
                    'default'  => '9',
                ),
                array(
                    'id'       => 'wc_wishlist_icon',
                    'type'     => 'switch',
                    'title'    => esc_html__( 'Product Add to Wishlist Icon', 'eventalk' ),
                    'on'       => esc_html__( 'Enabled', 'eventalk' ),
                    'off'      => esc_html__( 'Disabled', 'eventalk' ),
                    'default'  => true,
                ),
                array(
                    'id'       => 'wc_quickview_icon',
                    'type'     => 'switch',
                    'title'    => esc_html__( 'Product Quickview Icon', 'eventalk' ),
                    'on'       => esc_html__( 'Enabled', 'eventalk' ),
                    'off'      => esc_html__( 'Disabled', 'eventalk' ),
                    'default'  => true,
                ),
                array(
                    'id'       => 'wc_sec_product',
                    'type'     => 'section',
                    'title'    => esc_html__( 'Product Single Page', 'eventalk' ),
                    'indent'   => true,
                ),
                array(
                    'id'       => 'wc_show_excerpt',
                    'type'     => 'switch',
                    'title'    => esc_html__( "Show excerpt when short description doesn't exist", 'eventalk' ),
                    'on'       => esc_html__( 'Enabled', 'eventalk' ),
                    'off'      => esc_html__( 'Disabled', 'eventalk' ),
                    'default'  => true,
                ),
                array(
                    'id'       => 'wc_cats',
                    'type'     => 'switch',
                    'title'    => esc_html__( 'Categories', 'eventalk' ),
                    'on'       => esc_html__( 'Show', 'eventalk' ),
                    'off'      => esc_html__( 'Hide', 'eventalk' ),
                    'default'  => true,
                ),
                array(
                    'id'       => 'wc_tags',
                    'type'     => 'switch',
                    'title'    => esc_html__( 'Tags', 'eventalk' ),
                    'on'       => esc_html__( 'Show', 'eventalk' ),
                    'off'      => esc_html__( 'Hide', 'eventalk' ),
                    'default'  => true,
                ),
                array(
                    'id'       => 'wc_related',
                    'type'     => 'switch',
                    'title'    => esc_html__( 'Related Products', 'eventalk' ),
                    'on'       => esc_html__( 'Show', 'eventalk' ),
                    'off'      => esc_html__( 'Hide', 'eventalk' ),
                    'default'  => true,
                ),
                array(
                    'id'       => 'wc_description',
                    'type'     => 'switch',
                    'title'    => esc_html__( 'Description Tab', 'eventalk' ),
                    'on'       => esc_html__( 'Show', 'eventalk' ),
                    'off'      => esc_html__( 'Hide', 'eventalk' ),
                    'default'  => true,
                ),
                array(
                    'id'       => 'wc_reviews',
                    'type'     => 'switch',
                    'title'    => esc_html__( 'Reviews Tab', 'eventalk' ),
                    'on'       => esc_html__( 'Show', 'eventalk' ),
                    'off'      => esc_html__( 'Hide', 'eventalk' ),
                    'default'  => true,
                ),
                array(
                    'id'       => 'wc_additional_info',
                    'type'     => 'switch',
                    'title'    => esc_html__( 'Additional Information Tab', 'eventalk' ),
                    'on'       => esc_html__( 'Show', 'eventalk' ),
                    'off'      => esc_html__( 'Hide', 'eventalk' ),
                    'default'  => true,
                ),
                array(
                    'id'       => 'wc_sec_cart',
                    'type'     => 'section',
                    'title'    => esc_html__( 'Cart Page', 'eventalk' ),
                    'indent'   => true,
                ),
                array(
                    'id'       => 'wc_cross_sell',
                    'type'     => 'switch',
                    'title'    => esc_html__( 'Cross Sell Products', 'eventalk' ),
                    'on'       => esc_html__( 'Show', 'eventalk' ),
                    'off'      => esc_html__( 'Hide', 'eventalk' ),
                    'default'  => true,
                ),
            )
        ) 
    );
}