<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Eventalk;
$rdtheme_socials = Helper::socials();

$rdtheme_footer_logo_url = empty( RDTheme::$options['footer_logo']['url'] ) ? Helper::get_img( 'footer-logo.png' ) : RDTheme::$options['footer_logo']['url'];

$rdtheme_footer_logo = !isset(RDTheme::$options['footer_logo']['id']) || empty( RDTheme::$options['footer_logo']['id'] ) 
	? '<img width="360" height="112" src="' . esc_url($rdtheme_footer_logo_url) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" loading="lazy" />' 
	: wp_get_attachment_image( RDTheme::$options['footer_logo']['id'], array(211, 75 ) );

?>
</div><!-- #content -->
<footer>
<div class="footer-layout1 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
            	<a class="footer-logo" href="<?php echo esc_url( home_url( '/' ) );?>"><?php echo $rdtheme_footer_logo; ?></a>
			<?php if ( RDTheme::$options['copyright_area'] ): ?>
				<p><?php echo wp_kses_post( RDTheme::$options['copyright_text'] );?></p>			
			<?php endif; ?>
               <?php if ( $rdtheme_socials ): ?>
					<div class="footer-social">
						<ul>
							<?php foreach ( $rdtheme_socials as $rdtheme_social ): ?>
							<li><a target="_blank" href="<?php echo esc_url( $rdtheme_social['url'] );?>"><i class="fa <?php echo esc_attr( $rdtheme_social['icon'] );?>"></i></a></li>
							<?php endforeach; ?>					
						</ul>	
					</div>					
				<?php endif; ?>
            </div>
        </div>
    </div>
</div>
</footer>

