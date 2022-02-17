<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */
namespace radiustheme\Eventalk;
$prefix      = THEME_PREFIX;
$cpt         = THEME_CPT_PREFIX;
$thumb_size  = "{$prefix}-size4";
$id            = get_the_id();
$designation   = get_post_meta( $id, "{$cpt}_speaker_designation", true );
$socials       = get_post_meta( $id, "{$cpt}_speaker_social", true );
$social_fields = Helper::speakers_socials();
$content = Helper::get_current_post_content();
$content = wp_trim_words( $content, RDTheme::$options['speakers_content_number'] );
$content = "<p>$content</p>";
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'rt-el-speakers-grid-3' ); ?>>
	<div class="rtin-item">
		<div class="speaker-layout3">                        
		    <?php
			if ( has_post_thumbnail() ){
				the_post_thumbnail( $thumb_size );
			}
			else {
				if ( !empty( RDTheme::$options['no_preview_image']['id'] ) ) {
					echo wp_get_attachment_image( RDTheme::$options['no_preview_image']['id'], $thumb_size );
				}
				else {
					echo '<img class="wp-post-image img-fluid rounded-circle" src="' . Helper::get_img( 'noimage_430x430.jpg' ) . '" alt="'.get_the_title().'">';
					}
				}
				?>
	            <div class="item-title">
	                <h3 class="title title-medium color-light hover-yellow size-md">
	                   <a href="<?php the_permalink();?>"><?php the_title();?></a>
	                </h3>
	                <div class="text-left title-light size-md color-light"><?php echo esc_attr( $designation );?></div>
	            </div>
	            <div class="item-social">
	                <ul>
						<?php foreach ( $socials as $key => $social ): ?>
							<?php if ( !empty( $social ) ): ?>
								<li><a target="_blank" href="<?php echo esc_url( $social );?>"><i class="fa <?php echo esc_attr( $social_fields[$key]['icon'] );?>" aria-hidden="true"></i></a></li>
							<?php endif; ?>
						<?php endforeach; ?>
                </ul>
            </div>
        </div>
	</div>		
</article>