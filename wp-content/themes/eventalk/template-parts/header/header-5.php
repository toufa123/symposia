<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */
namespace radiustheme\Eventalk;
$nav_menu_args = Helper::nav_menu_args();
// Logo
$rdtheme_dark_logo_url = empty( RDTheme::$options['logo']['url'] ) ? Helper::get_img( 'logo-dark.png' ) : RDTheme::$options['logo']['url'];
$rdtheme_light_logo_url = empty( RDTheme::$options['logo_light']['url'] ) ? Helper::get_img( 'logo-light.png' ) : RDTheme::$options['logo_light']['url'];

$rdtheme_dark_logo = !isset(RDTheme::$options['logo']['id']) || empty( RDTheme::$options['logo']['id'] ) 
	? '<img width="360" height="112" src="' . esc_url($rdtheme_dark_logo_url) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" loading="lazy" />' 
	: wp_get_attachment_image( RDTheme::$options['logo']['id'], array(211, 75 ) );

$rdtheme_light_logo = !isset(RDTheme::$options['logo_light']['id']) || empty( RDTheme::$options['logo_light']['id'] ) 
	? '<img width="360" height="112" src="' . esc_url($rdtheme_light_logo_url) . '" loading="lazy" />' 
	: wp_get_attachment_image( RDTheme::$options['logo_light']['id'], array(211, 75 ) );
?>
<div class="masthead-container">
	<div class="site-branding">
		<a class="dark-logo" href="<?php echo esc_url( home_url( '/' ) );?>"><?php echo $rdtheme_dark_logo; ?></a>
		<a class="light-logo" href="<?php echo esc_url( home_url( '/' ) );?>"><?php echo $rdtheme_light_logo; ?></a>
	</div>
	<div id="site-navigation" class="main-navigation">
		<?php wp_nav_menu( $nav_menu_args );?>
	</div>
	<div class="clear"></div>
</div>