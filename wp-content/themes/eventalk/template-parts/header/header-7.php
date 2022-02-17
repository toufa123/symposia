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


$rdtheme_logo_width = (int) RDTheme::$options['logo_width'];
if(RDTheme::$options['header_btn']){
	$rdtheme_menu_width = 10 - $rdtheme_logo_width;
}else{
	$rdtheme_menu_width = 12 - $rdtheme_logo_width;
}

$rdtheme_logo_class = "col-sm-{$rdtheme_logo_width} col-xs-12";
$rdtheme_menu_class = "col-sm-{$rdtheme_menu_width} col-xs-12";

?>


<div class="masthead-container full-width-left-compress new-header-2">
	<div class="container-fluid">
		<div class="row no-gutters d-flex align-items-center">
			
			<div class="<?php echo esc_attr( $rdtheme_logo_class );?>">
				<div class="site-branding site-logo">
					<a class="dark-logo" href="<?php echo esc_url( home_url( '/' ) );?>"><?php echo $rdtheme_dark_logo; ?></a>
					<a class="light-logo" href="<?php echo esc_url( home_url( '/' ) );?>"><?php echo $rdtheme_light_logo; ?></a>
				</div>
			</div>

			<div class="<?php echo esc_attr( $rdtheme_menu_class );?>">		
				<div id="site-navigation" class="main-navigation">
					<?php wp_nav_menu( $nav_menu_args );?>
				</div>
			</div>	

			<?php if ( RDTheme::$options['header_btn'] ): ?>			
				<div class="col-lg-2 col-md-2 d-none d-lg-block header-new-icon7-wrp">				
				<div class="header-icon-area header-new-icon7">
					<?php 
						if ( RDTheme::$options['search_icon'] ) {
							get_template_part( 'template-parts/header/icon', 'search' );
						}
					?>
				</div>	


					<ul class="header-action-items header-new-float-right">
				      <li>
				    	<?php
				    	if (RDTheme::$options['header_new_window']) { ?>
				    	 	<a href="<?php echo esc_url( RDTheme::$options['header_buttonUrl'] );?>" title="<?php echo esc_html( RDTheme::$options['header_buttontext'] );?>" target="_blank" class="btn-no-fill size-xs border-radius-5 gust"><?php echo esc_html( RDTheme::$options['header_buttontext'] );?></a>
				    	<?php } else { ?>
				    	 	<a href="<?php echo esc_url( RDTheme::$options['header_buttonUrl'] );?>" title="<?php echo esc_html( RDTheme::$options['header_buttontext'] );?>" class="btn-no-fill size-xs border-radius-5 gust"><?php echo esc_html( RDTheme::$options['header_buttontext'] );?></a>
				    	<?php } ?>
				    </li>
				</ul>

				

			</div>
	  	<?php endif; ?>
		</div>		
	</div>
</div>