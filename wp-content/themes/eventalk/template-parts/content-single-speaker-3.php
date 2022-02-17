<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.4
 */
namespace radiustheme\Eventalk;
use radiustheme\Eventalk\Helper;
global $post;

$prefix         = THEME_PREFIX;
$cpt            = THEME_CPT_PREFIX;
$thumb_size     = "{$prefix}-size4";
$designation    = get_post_meta( $post->ID, "{$cpt}_speaker_designation", true );
$degree    = get_post_meta( $post->ID, "{$cpt}_degree", true );
$education    = get_post_meta( $post->ID, "{$cpt}_education", true );
$contact_number    = get_post_meta( $post->ID, "{$cpt}_contact_number", true );
$email    = get_post_meta( $post->ID, "{$cpt}_email", true );
$times_chedules    = get_post_meta( $post->ID, "{$cpt}_times_chedules", true );
$appointment_link    = get_post_meta( $post->ID, "{$cpt}_appointment_link", true );
$skills         = get_post_meta( $post->ID, "{$cpt}_speaker_skill", true );
$socials        = get_post_meta( $post->ID, "{$cpt}_speaker_social", true );
$socials        = array_filter( $socials );
$socials_fields = Helper::speakers_socials();
?>
<div id="post-<?php the_ID(); ?>" <?php post_class( 'team-single' ); ?>>
    <div class="row">
        <div class="col-lg-5 col-md-12 col-sm-12">
            <div class="single-speaker-img">
               <?php the_post_thumbnail( $thumb_size );?>
            </div>
        </div>
        <div class="col-lg-7 col-md-12 col-sm-12">
            <div class="speaker-profile">
                <h2 class="title title-black color-dark"><?php the_title();?></h2>
                <div class="sub-title"><?php echo esc_html( $designation );?></div>
                   <ul class="profile-social">
                        <?php foreach ( $socials as $key => $value ): ?>
                                <li><a target="_blank" href="<?php echo esc_url( $value ); ?>"><i class="fa <?php echo esc_attr( $socials_fields[$key]['icon'] );?>"></i></a></li>
                        <?php endforeach; ?>
                   </ul>		
                <?php the_excerpt();?>	
            </div>
        </div>
    </div>
    <?php if (RDTheme::$options['speakers_elementor_content']) { ?>
        <div class="row">
            <div class="col-lg-12">
                <div class="rtin-content d-content">
                        <?php the_content();?>
                </div>
            </div>
        </div>
    <?php }  ?> 
</div>