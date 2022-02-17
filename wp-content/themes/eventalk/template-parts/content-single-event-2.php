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
        $starDate           = Helper::custom_date_format($_event_start_date);            
        $endDate            = Helper::custom_date_format($_event_end_date); 

        $dStart = new \DateTime($starDate);
        $dEnd  = new \DateTime($endDate);
        $dDiff = $dStart->diff($dEnd);
        $difday =  $dDiff->days;
        $tabs = null;
        $tabcontent = null;    
      // if($difday){
            for($i = 0; $i <= $difday; $i++) {
                $datetime = new \DateTime($starDate);
                $datetime->modify('+'.$i.' day');
                $dayNumber = $i + 1;
                $tabActive =  $i==0 ? 'active': null;
                $tabNall =  $difday==0 ? 'tab-btn-nall': null;
                 $tabs .='<li class="nav-item '.$tabNall.'">
                            <a class="'.$tabActive.'" href="#schedule-'.$datetime->format('Ymd').'" data-toggle="tab" aria-expanded="true">
                                <div class="day-number"> '. esc_html__( 'Day', 'eventalk' ) .'- 0'.$dayNumber.'</div>
                                
                                <div class="schedule-date">'. date_i18n( 'F j, Y', strtotime($datetime->format('Ymd'))) .'</div>
                            </a>
                        </li>';
                $slt  = array();
                if(!empty($schedules)){
                    foreach ($schedules as $schedule) {                        
                             if(Helper::custom_date_format( $schedule['session_start_date'], 'm/d/Y' ) == $datetime->format('m/d/Y')){  
                            $slt[] = $schedule;
                        }
                    }
                }
                $inner_content = '<p>No Schedule</p>';
                if(!empty($slt)){
                    $inner_content = '<table class="table table-bordered table-responsive">
                                        <thead>
                                            <tr>
                                            <th>'.esc_html__( '#', 'eventalk' ).'</th>
                                            <th>'.esc_html__( 'Topic', 'eventalk' ).'</th>                                          
                                            <th>'.esc_html__( 'Speaker', 'eventalk' ).'</th>                                          
                                            <th>'.esc_html__( 'Time', 'eventalk' ).'</th>
                                            <th>'.esc_html__( 'Hall', 'eventalk' ).'</th>
                                            </tr>
                                        </thead>
                                        <tbody>';
                                        $count_table = 1;
                                    foreach ($slt as $item) {
                                            $inner_content .='<tr>
                                                <th scope="row">'.$count_table.'</th>
                                                <td>'. $item['session_title'] .'
                                                </td>';
                                                $inner_content .='<td>';
                                                    if(!empty($item['speaker'])){
                                                            $inner_content .= get_the_title($item['speaker'] );
                                                        } 
                                                        if(!empty($item['speaker2']) AND !empty($item['speaker'])){
                                                              $inner_content .= ', ';                                                   
                                                        }
                                                        if(!empty($item['speaker2'])){
                                                              $inner_content .= get_the_title($item['speaker2'] );
                                                        }   
                                                        if(!empty($item['speaker3']) AND !empty($item['speaker2'])){
                                                              $inner_content .= ', ';                                                   
                                                        }
                                                        if(!empty($item['speaker3'])){
                                                              $inner_content .= get_the_title($item['speaker3'] );
                                                        }  
                                                        if(!empty($item['speaker4']) AND !empty($item['speaker3'])){
                                                              $inner_content .= ', ';                                                   
                                                        }
                                                        if(!empty($item['speaker4'])){
                                                              $inner_content .= get_the_title($item['speaker4'] );
                                                        }   
                                                        if(!empty($item['speaker5']) AND !empty($item['speaker4'])){
                                                              $inner_content .= ', ';                                                   
                                                        }
                                                        if(!empty($item['speaker5'])){
                                                              $inner_content .= get_the_title($item['speaker5'] );
                                                        } 
                                                        
                                                $inner_content .='</td>';
                                                $inner_content .='<td>'. $item['session_start_time'] .' - '. $item['session_end_time'] .'</td>
                                                <td>'. $item['schedule_Hall'] .'</td>
                                                </tr>';
                                                $count_table++;
                                        }

                        $inner_content .= '</tbody>
                                    </table>';
                }
                $tabcontent .= '<div role="tabpanel" class="tab-pane fade show '.$tabActive.'" id="schedule-'.$datetime->format('Ymd').'" aria-expanded="true">'.$inner_content.'</div>';   
            }
        //}
        printf('<div class="schedule-layout2">
                <ul class="schedule-nav nav nav-tabs">%s</ul>
                <div class="schedule-content">
                    <div class="tab-content">%s</div>
                </div>
            </div>',
            $tabs,
            $tabcontent
        )
?>
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