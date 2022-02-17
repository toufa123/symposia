<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Eventalk;

$rdtheme_error_img = empty( RDTheme::$options['error_bodybanner']['url'] ) ? Helper::get_img( '404.png' ) : RDTheme::$options['error_bodybanner']['url'];
$rdtheme_error_link = empty( RDTheme::$options['error_buttonlink']) ?  home_url( '/' ) : RDTheme::$options['error_buttonlink'];
?>
<?php get_header();?>
<div id="primary" class="content-area error-page-area">
	<div class="container">
		<div class="error-page">
			<img src="<?php echo esc_url( $rdtheme_error_img );?>" alt="<?php esc_attr_e( '404', 'eventalk' );?>">
			<h3><?php echo esc_html( RDTheme::$options['error_text1'] );?></h3>
			<p><?php echo esc_html( RDTheme::$options['error_text2'] );?></p>

			<a class="btn-fill size-lg color-yellow border-radius-5" href="<?php echo esc_url($rdtheme_error_link);?>"><?php echo esc_html( RDTheme::$options['error_buttontext'] );?></a>

		</div>
	</div>
</div>
<?php get_footer(); ?>