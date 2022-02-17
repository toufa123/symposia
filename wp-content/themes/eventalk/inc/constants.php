<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Eventalk;

define( __NAMESPACE__ . '\NS',    __NAMESPACE__ . '\\' );

$theme_data = wp_get_theme( get_template() );
define( NS . 'THEME_VERSION',     ( WP_DEBUG ) ? time() : $theme_data->get( 'Version' ) );
define( NS . 'THEME_AUTHOR_URI',  $theme_data->get( 'AuthorURI' ) );
define( NS . 'THEME_PREFIX',      'eventalk' );
define( NS . 'THEME_PREFIX_VAR',  'eventalk' );
define( NS . 'WIDGET_PREFIX',     'eventalk' );
define( NS . 'THEME_CPT_PREFIX',  'eventalk' );

// DIR
define( NS . 'THEME_BASE_DIR',    get_template_directory(). '/' );
define( NS . 'THEME_INC_DIR',     THEME_BASE_DIR . 'inc/' );
define( NS . 'THEME_VIEW_DIR',    THEME_INC_DIR . 'views/' );
define( NS . 'THEME_PLUGINS_DIR', THEME_BASE_DIR . 'inc/plugin-bundle/' );
