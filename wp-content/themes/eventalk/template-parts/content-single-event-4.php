<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.4
 */
    namespace radiustheme\Eventalk;
    use radiustheme\Eventalk\Helper;
    global $post;

    $prefix             = THEME_PREFIX;
    $cpt                = THEME_CPT_PREFIX;
    $thumb_size         = "{$prefix}-size1";
    $_event_start_date  = get_post_meta( $post->ID, "{$cpt}_event_start_date", true );
    $_event_start_time  = get_post_meta( $post->ID, "{$cpt}_event_start_time", true );
    $_event_end_date    = get_post_meta( $post->ID, "{$cpt}_event_end_date", true );
    $_event_end_time    = get_post_meta( $post->ID, "{$cpt}_event_end_time", true );
    $_event_location    = get_post_meta( $post->ID, "{$cpt}_event_location", true );
    $_event_lat         = get_post_meta( $post->ID, "{$cpt}_event_lat", true );
    $_event_lan         = get_post_meta( $post->ID, "{$cpt}_event_lan", true );
    $_event_ext_link    = get_post_meta( $post->ID, "{$cpt}_event_ext_link", true );
    $schedules                         = get_post_meta($post->ID, "{$cpt}_event_schedule", true);
    $event_additional_images           = get_post_meta($post->ID, "{$cpt}_event_additional_image", true);
    $socials                 = get_post_meta($post->ID, "{$cpt}_event_social", true );
    $socials                 = array_filter($socials);
    $social_fields          = Helper::event_socials();
    $responsive = array(
        '0'    => array( 'items' => 1 ),
        '480'  => array( 'items' => 1 ),
        '768'  => array( 'items' => 1 ),
        '992'  => array( 'items' => 1 ),
    );

    $owl_data = array( 
        'nav'                => false,
        'dots'               => true,
        'autoplay'           => true,
        'autoplayTimeout'    => '5000',
        'autoplaySpeed'      => '200',
        'autoplayHoverPause' => true,
        'loop'               => true,
        'margin'             => 20,
        'responsive'         => $responsive
    );

    $owl_data = json_encode( $owl_data );
    wp_enqueue_style( 'owl-carousel' );
    wp_enqueue_style( 'owl-theme-default' );
    wp_enqueue_script( 'owl-carousel' );
    ?>

<div id="post-<?php the_ID(); ?>" <?php post_class( 'team-single' ); ?>>
    <div class="row">
       <div class="col-xl-12">
         <?php 
        if( !empty($event_additional_images ) ) { 
            ?>            
        <div class="event-owl-wrap event-description">
            <div class="rt-owl-carousel owl-carousel owl-theme" data-carousel-options="<?php echo esc_attr( $owl_data );?>">
            <?php foreach ($event_additional_images  as $event_additional_image) {
                            $event_additional_image_id = $event_additional_image['additional_img']; 
                        ?>                     
                            <div class="item-event-img"><?php echo wp_get_attachment_image($event_additional_image_id, 'full' );?></div>
                       <?php
                        } ?>
                </div>
            </div>            
        <?php  }  ?>  
        </div>
    </div> 
    <div class="row">
         <div class="col-xl-12">
            <div class="event-description">
                <h2 class="title title-bold color-dark"><?php the_title();?></h2>
               <?php the_excerpt();?>            
            </div>
        </div>  
    </div>       
    <div class="row">
       <div class="col-xl-12">
          <div class="event-description">
              <ul>
                   <li>
                      <span><?php esc_html_e( 'Start Date :', 'eventalk' );?></span> 
                   <?php echo date_i18n( 'j F, Y', strtotime($_event_start_date)); ?>
                                             
                  </li>
                  <li>
                      <span><?php esc_html_e( 'Start Time :', 'eventalk' );?></span>
                      <?php echo esc_html($_event_start_time); ?>
                  </li>
                 <li>
                      <span><?php esc_html_e( 'End Date :', 'eventalk' );?></span>                       
                  <?php echo date_i18n( 'j F, Y', strtotime($_event_end_date)); ?>
                      
                  </li>
                   <li>
                        <span><?php esc_html_e( 'End Time :', 'eventalk' );?></span>
                       <?php echo esc_html($_event_end_time); ?>
                   </li>
                  <li><span><?php esc_html_e( 'Address :', 'eventalk' );?></span> <?php echo esc_html($_event_location); ?></li>
              </ul>   
            </div>
        </div>  
    </div>
    <?php 
     if(!empty($schedules)){ ?>
       <div class="row"> 
            <?php
            foreach ($schedules as $schedule) { 
                $image_attributes = wp_get_attachment_image_src($schedule['session_img'], $thumb_size );  
                ?>
               <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 single-event-img">
               
                    <div class="schedule-layout4 bg-common" style="background-image: url('<?php echo esc_attr($image_attributes[0]); ?>');">
                    <div class="item-content zindex-up"> 
                        <ul>
                            <li>
                                <h3 class="title title-bold hover-yellow color-light size-xl">
                                    <a href="<?php the_permalink();?>"><?php echo esc_html($schedule['session_start_date']); ?></a>
                                </h3>
                            </li>
                            <li>
                                <h3 class="title title-regular color-light size-md"><?php echo esc_html($schedule['session_title']); ?></h3>
                                <p><?php echo esc_html($schedule['session_start_time']); ?> - <?php echo esc_html($schedule['session_end_time']); ?></p>
                            </li>
                            <li>
                             <?php if(!empty($schedule['speaker'])){ ?>
                                <h3 class="title title-medium color-light"><a href="<?php echo get_the_permalink($schedule['speaker']); ?>"><?php echo get_the_title($schedule['speaker'] ) ; ?></a></h3>
                            <?php } ?>
                           <?php if(!empty($schedule['speaker2'])){ ?>
                                <h3 class="title title-medium color-light"><a href="<?php echo get_the_permalink($schedule['speaker2']); ?>"><?php echo get_the_title($schedule['speaker2'] ) ; ?></a></h3>
                            <?php } ?>
                                <p><?php esc_html_e( 'Speaker', 'eventalk' );?></p>
                            </li>
                            <li>
                                <h3 class="title title-regular color-light size-lg"><?php esc_html_e( 'Hall', 'eventalk' );?></h3>
                                <p><?php echo esc_html($schedule['schedule_Hall']); ?></p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        <?php
        } 
        ?>
     </div>
    <?php } ?>           
<div class="row">
 <div class="col-xl-6">                
        <?php if( RDTheme::$options['single_event_btn']): ?>  
    <a href="<?php echo esc_url($_event_ext_link );?>" title="More Speakers" class="loadmore-four-item btn-fill border-radius-5 size-lg color-yellow"><?php echo esc_attr( RDTheme::$options['single_event_btn_text'] );?></a>
    <?php endif; ?>  
   
 </div>   
    <div class="col-xl-6">                   
            <?php if( RDTheme::$options['single_event_socials']): ?>             
            <div class="item-social text-right">
                <ul class="profile-social ">
                <?php foreach ( $socials as $key => $social ): ?>
                    <?php if ( !empty( $social ) ): ?>
                        <li><a target="_blank" href="<?php echo esc_url( $social );?>"><i class="fa <?php echo esc_attr( $social_fields[$key]['icon'] );?>" aria-hidden="true"></i></a></li>
                    <?php endif; ?>
                <?php endforeach; ?>
                </ul>
            </div>  
    <?php endif; ?>    
    </div>  
</div>      
<?php if(the_content()){ ?>   
   <div class="row">
         <div class="col-xl-12">
            <div class="rtin-content d-content">
                <?php the_content();?>
            </div>
        </div>
    </div>
 <?php } ?>
</div>