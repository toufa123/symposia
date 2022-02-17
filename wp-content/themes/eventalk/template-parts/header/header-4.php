<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Eventalk;

$rdtheme_socials = Helper::socials();
$nav_menu_args   = Helper::nav_menu_args();

// Logo
$rdtheme_dark_logo_url = empty( RDTheme::$options['logo']['url'] ) ? Helper::get_img( 'logo-dark.png' ) : RDTheme::$options['logo']['url'];
$rdtheme_light_logo_url = empty( RDTheme::$options['logo_light']['url'] ) ? Helper::get_img( 'logo-light.png' ) : RDTheme::$options['logo_light']['url'];

$rdtheme_dark_logo = !isset(RDTheme::$options['logo']['id']) || empty( RDTheme::$options['logo']['id'] ) 
	? '<img width="360" height="112" src="' . esc_url($rdtheme_dark_logo_url) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" loading="lazy" />' 
	: wp_get_attachment_image( RDTheme::$options['logo']['id'], array(211, 75 ) );

$rdtheme_light_logo = !isset(RDTheme::$options['logo_light']['id']) || empty( RDTheme::$options['logo_light']['id'] ) 
	? '<img width="360" height="112" src="' . esc_url($rdtheme_light_logo_url) . '" loading="lazy" />' 
	: wp_get_attachment_image( RDTheme::$options['logo_light']['id'], array(211, 75 ) );
	
// Icon count
$rdtheme_icon_count = 0;
if ( RDTheme::$options['search_icon'] ) {
	$rdtheme_icon_count++;
}
if ( RDTheme::$options['cart_icon'] && class_exists( 'WC_Widget_Cart' ) ) {
	$rdtheme_icon_count++;
}
if ( RDTheme::$options['vertical_menu_icon'] && has_nav_menu( 'topright' ) ) {
	$rdtheme_icon_count++;
}
?>
<div class="masthead-container">
	<div class="container">
		<div class="row header-firstrow-wrap">
			<div class="col-sm-4 col-xs-12">
				<div class="header-firstrow">
					<div class="header-firstrow-contents">
						<ul class="header-contact">
							<?php if ( RDTheme::$options['phone'] ): ?>
								<li>
									<i class="fa fa-phone" aria-hidden="true"></i><a href="tel:<?php echo esc_attr( RDTheme::$options['phone'] );?>"><?php echo esc_html( RDTheme::$options['phone'] );?></a>
								</li>
							<?php endif; ?>
							<?php if ( RDTheme::$options['email'] ): ?>
								<li>
									<i class="fa fa-envelope-o" aria-hidden="true"></i><a href="mailto:<?php echo esc_attr( RDTheme::$options['email'] );?>"><?php echo esc_html( RDTheme::$options['email'] );?></a>
								</li>
							<?php endif; ?>
						</ul>
					</div>
				</div>
			</div>
			<div class="col-sm-4 col-xs-12">
				<div class="site-branding">
					<a class="dark-logo" href="<?php echo esc_url( home_url( '/' ) );?>"><?php echo $rdtheme_dark_logo; ?></a>
					<a class="light-logo" href="<?php echo esc_url( home_url( '/' ) );?>"><?php echo $rdtheme_light_logo; ?></a>
				</div>
			</div>
			<div class="col-sm-4 col-xs-12">
				<div class="header-firstrow">
					<div class="header-firstrow-contents header-firstrow-contents-right">
							<?php if ( $rdtheme_socials ): ?>
								<ul class="header-social">
									<?php foreach ( $rdtheme_socials as $rdtheme_social ): ?>
										<li><a target="_blank" href="<?php echo esc_url( $rdtheme_social['url'] );?>"><i class="fa <?php echo esc_attr( $rdtheme_social['icon'] );?>"></i></a></li>
									<?php endforeach; ?>					
								</ul>						
							<?php endif; ?>
						<?php if ( $rdtheme_icon_count ): ?>
							<?php get_template_part( 'template-parts/header/icon', 'area' );?>
						<?php endif; ?>		
					</div>
				</div>
			</div>
		</div>
		<hr class="menu-sep" />
		<div id="site-navigation" class="main-navigation">
			<?php wp_nav_menu( $nav_menu_args );?>
		</div>
	</div>
</div>