<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Eventalk;
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<?php
		// Preloader
			if ( RDTheme::$options['preloader'] ) {
				if ( !empty( RDTheme::$options['preloader_image']['url'] ) ) {
					$preloader_img = RDTheme::$options['preloader_image']['url'];
					echo '<div id="preloader" style="background-image:url(' . esc_url( $preloader_img ) . ');"></div>';
				} else {
				echo '<!-- Preloader Start Here -->
				<div id="preloader" class="preloader">
				   <div class="items">
				       <div class="item"></div>
				       <div class="item"></div>
				       <div class="item"></div>
				       <div class="item"></div>
				       <div class="item"></div>
				       <div class="item"></div>
				       <div class="item"></div>
				       <div class="item"></div>
				       <div class="item"></div>
				       <div class="item"></div>
				       <div class="item"></div>
				       <div class="item"></div>
				       <div class="item"></div>
				       <div class="item"></div>
				       <div class="item"></div>
				       <div class="item"></div>
				   </div>
				</div>
				<!-- Preloader End Here -->';
				}
			}
        if ( RDTheme::$options['site_layout']  ){
             $site_layout = 'full-layout';
        }
        else {
            $site_layout = 'box-layout';
        }
	?>
<div id="page" class="site site-wrp <?php echo esc_attr( $site_layout );?>">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'eventalk' ); ?></a>
	<header id="masthead" class="site-header">
		<?php get_template_part( 'template-parts/header/header', RDTheme::$header_style );?>
	</header>
		<?php if ( RDTheme::$options['header_btn'] ): ?>			
				<ul class="header-action-items mobile-button-area">
				    <li>
				    	<?php
				    	if (RDTheme::$options['header_new_window']) { ?>
				    	 	<a href="<?php echo esc_url( RDTheme::$options['header_buttonUrl'] );?>" title="<?php echo esc_html( RDTheme::$options['header_buttontext'] );?>" target="_blank" class="btn-fill size-xs color-yellow border-radius-5 gust"><?php echo esc_html( RDTheme::$options['header_buttontext'] );?></a>
				    	<?php } else { ?>
				    	 	<a href="<?php echo esc_url( RDTheme::$options['header_buttonUrl'] );?>" title="<?php echo esc_html( RDTheme::$options['header_buttontext'] );?>" class="btn-fill size-xs color-yellow border-radius-5 gust"><?php echo esc_html( RDTheme::$options['header_buttontext'] );?></a>
				    	<?php } ?>
				    </li>
				  </ul>	
			  <?php endif; ?>
	<?php get_template_part( 'template-parts/header/header', 'offscreen' );?>
	<div id="content" class="site-content">
		<?php get_template_part('template-parts/content', 'banner');?>