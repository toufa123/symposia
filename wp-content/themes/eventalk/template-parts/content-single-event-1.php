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

    <?php if( RDTheme::$options['single_img_slider']):   ?> 
    <div class="row">
       <div class="col-xl-12">
         <?php 
        if ( RDTheme::$options['event_img_option'] ) {  
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
                <?php  } 
              } else{  ?>
                <div class="event-description">
                    <?php  the_post_thumbnail( 'full' ); ?>
                </div>
                <?php 
             }  ?>
        </div>
    </div> 
    <?php endif; ?>

    <div class="row">
         <div class="col-xl-12">
            <div class="event-description">
                <h2 class="title title-bold color-dark"><?php the_title();?></h2>
                <?php the_excerpt();?>              
            </div>
        </div>  
    </div>     
        <?php if( RDTheme::$options['single_schedule_info']):   ?>  
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
<?php endif; ?>
<?php if( RDTheme::$options['single_event_table']):   ?>
      <div class="row">
         <div class="col-xl-12">
            <div class="event-description"> 
   <?php 
     if(!empty($schedules)){
                ?>
        <table class="table table-striped schedule-layout1">
            <tbody class="menu-list">
                <?php
                    foreach ($schedules as $schedule) { 
                        $schedule_speaker_title = null;
                        $schedule_speaker_img = null;

                        if(!empty($schedule['speaker']) && $spiker_id = $schedule['speaker']){
                            $schedule_speaker_title = get_the_title($spiker_id) ;  
                            $schedule_speaker_img = get_the_post_thumbnail_url($schedule['speaker'], 'thumbnail' ) ;              
                        }  
                        if(!empty($schedule['speaker2']) && $spiker_id2 = $schedule['speaker2']){
                            $schedule_speaker_title2 = get_the_title($spiker_id2) ;  
                            $schedule_speaker_img2 = get_the_post_thumbnail_url($schedule['speaker2'], 'thumbnail' ) ;              
                        }   
                        if(!empty($schedule['speaker3']) && $spiker_id3 = $schedule['speaker3']){
                            $schedule_speaker_title3 = get_the_title($spiker_id3) ;  
                            $schedule_speaker_img3 = get_the_post_thumbnail_url($schedule['speaker3'], 'thumbnail' ) ;              
                        }   
                        if(!empty($schedule['speaker4']) && $spiker_id4 = $schedule['speaker4']){
                            $schedule_speaker_title4 = get_the_title($spiker_id4) ;  
                            $schedule_speaker_img4 = get_the_post_thumbnail_url($schedule['speaker4'], 'thumbnail' ) ;              
                        }  
                        if(!empty($schedule['speaker5']) && $spiker_id4 = $schedule['speaker5']){
                            $schedule_speaker_title5 = get_the_title($spiker_id4) ;  
                            $schedule_speaker_img5 = get_the_post_thumbnail_url($schedule['speaker5'], 'thumbnail' ) ;              
                        }   
                      
                ?>
                    <tr class="menu-item">
                    <th class="event-color" style="background-color: <?php echo esc_attr($schedule['color']); ?>">
                        <div class="day-number"><?php echo esc_html($schedule['schedule_label']); ?></div>
                        <div class="schedule-date">
                             <?php echo date_i18n( 'j F, Y', strtotime($schedule['session_start_date'])); ?>
                        </div>

                    </th>
                    <td>
                        <div class="schedule-title">
                           <?php echo esc_html($schedule['session_title']); ?>
                        </div>
                    </td>
                    <td>
                        <div class="schedule-time">
                            <i class="fa fa-clock-o" aria-hidden="true"></i><?php echo wp_kses_post($schedule['session_start_time']); ?> - <?php echo esc_html($schedule['session_end_time']); ?></div>
                    </td>
                    <td>
                    <?php if (!empty($schedule['speaker'])) {?>
                    <?php if($schedule_speaker_img){ ?>
                    <ul class="schedule-speaker">                           
                            <li>
                                <div class="speaker-img-tooltip" data-tips="<?php  echo esc_attr($schedule_speaker_title);?>">
                                    <a href="<?php echo get_permalink( $schedule['speaker']); ?>">
                                        <img src="<?php echo esc_url($schedule_speaker_img); ?>" alt="schedule" class="rounded-circle"></a>
                                </div>   
                            </li>    
                        </ul>
                        <?php } else {  ?>
                        <div class="title_sp text-right"> 
                            <?php  echo esc_attr($schedule_speaker_title) ; ?>
                        </div>
                    <?php  } ?>   
                    <?php  } ?>   

                     <?php if (!empty($schedule['speaker2'])) {?>
                         <?php if($schedule_speaker_img2){ ?>
                        <ul class="schedule-speaker">                           
                                <li>
                                    <div class="speaker-img-tooltip" data-tips="<?php  echo esc_attr($schedule_speaker_title2);?>">
                                        <a href="<?php echo get_permalink( $schedule['speaker2']); ?>">
                                            <img src="<?php echo esc_url($schedule_speaker_img2); ?>" alt="schedule" class="rounded-circle"></a>
                                    </div>   
                                </li>    
                            </ul>
                            <?php } else {  ?>
                            <div class="title_sp text-right"> 
                                <?php  echo esc_attr($schedule_speaker_title2) ; ?>
                            </div>
                        <?php  } ?>
                    <?php  } ?>               
                    <?php if (!empty($schedule['speaker3'])) {?>
                         <?php if($schedule_speaker_img3){ ?>
                        <ul class="schedule-speaker">                           
                                <li>
                                    <div class="speaker-img-tooltip" data-tips="<?php  echo esc_attr($schedule_speaker_title3);?>">
                                        <a href="<?php echo get_permalink( $schedule['speaker3']); ?>">
                                            <img src="<?php echo esc_url($schedule_speaker_img3); ?>" alt="schedule" class="rounded-circle"></a>
                                    </div>   
                                </li>    
                            </ul>
                            <?php } else {  ?>
                            <div class="title_sp text-right"> 
                                <?php  echo esc_attr($schedule_speaker_title3) ; ?>
                            </div>
                        <?php  } ?>
                    <?php  } ?>     

                    <?php if (!empty($schedule['speaker4'])) {?>
                         <?php if($schedule_speaker_img4){ ?>
                        <ul class="schedule-speaker">                           
                                <li>
                                    <div class="speaker-img-tooltip" data-tips="<?php  echo esc_attr($schedule_speaker_title4);?>">
                                        <a href="<?php echo get_permalink( $schedule['speaker4']); ?>">
                                            <img src="<?php echo esc_url($schedule_speaker_img4); ?>" alt="schedule" class="rounded-circle"></a>
                                    </div>   
                                </li>    
                            </ul>
                            <?php } else {  ?>
                            <div class="title_sp text-right"> 
                                <?php  echo esc_attr($schedule_speaker_title4) ; ?>
                            </div>
                        <?php  } ?>
                    <?php  } ?>                      
                    <?php if (!empty($schedule['speaker5'])) {?>
                         <?php if($schedule_speaker_img5){ ?>
                        <ul class="schedule-speaker">                           
                                <li>
                                    <div class="speaker-img-tooltip" data-tips="<?php  echo esc_attr($schedule_speaker_title5);?>">
                                        <a href="<?php echo get_permalink( $schedule['speaker5']); ?>">
                                            <img src="<?php echo esc_url($schedule_speaker_img5); ?>" alt="schedule" class="rounded-circle"></a>
                                    </div>   
                                </li>    
                            </ul>
                            <?php } else {  ?>
                            <div class="title_sp text-right"> 
                                <?php  echo esc_attr($schedule_speaker_title5) ; ?>
                            </div>
                        <?php  } ?>
                    <?php  } ?>                            
                           
                    </td>
                    </tr>
                <?php
            }?>
       </tbody>
    </table>
    <?php }  ?>
     </div>
    </div>
</div>       
  <?php endif; ?>

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
   
    <div class="row">
         <div class="col-xl-12">
            <div class="rtin-content d-content">
                <?php the_content();?>
            </div>
        </div>
    </div>
 
</div>