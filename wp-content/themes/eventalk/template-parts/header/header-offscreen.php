<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */ 

namespace radiustheme\Eventalk;
$nav_menu_args   = Helper::nav_menu_args();
$rdtheme_logo_url = empty( RDTheme::$options['logo']['url'] ) ? Helper::get_img( 'logo-dark.png' ) : RDTheme::$options['logo']['url'];
$rdtheme_logo  =  empty( RDTheme::$options['logo']['id'] ) ? '<img width="489" height="121" class="logo-small" alt="'.get_bloginfo( 'name' ).'" src="'.$rdtheme_logo_url.'">' :  wp_get_attachment_image(RDTheme::$options['logo']['id'],'full',"", array( "class" => "logo-small" ));

?>  
 
<div class="rt-header-menu mean-container" id="meanmenu">
    <div class="mean-bar">
    	<a href="<?php echo esc_url(home_url('/'));?>" alt="<?php echo esc_attr( get_bloginfo( 'title' ) );?>"><?php echo $rdtheme_logo;?></a>
        <span class="sidebarBtn ">
            <span class="fa fa-bars">
            </span>
        </span>
    </div>

    <div class="rt-slide-nav">
        <div class="offscreen-navigation">
            <?php wp_nav_menu( $nav_menu_args );?>
        </div>
    </div>

</div>
