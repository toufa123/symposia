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
$id                  = get_the_id();	
$_event_start_date  = get_post_meta( $id, "{$cpt}_event_start_date", true );
$_event_start_time  = get_post_meta( $id, "{$cpt}_event_start_time", true );
$_event_end_date    = get_post_meta( $id, "{$cpt}_event_end_date", true );
$_event_end_time    = get_post_meta( $id, "{$cpt}_event_end_time", true );
$_event_location    = get_post_meta( $id, "{$cpt}_event_location", true );
$_event_lat         = get_post_meta( $id, "{$cpt}_event_lat", true );
$_event_lan         = get_post_meta( $id, "{$cpt}_event_lan", true );
$_event_ext_link    = get_post_meta( $id, "{$cpt}_event_ext_link", true );
$schedules          = get_post_meta( $id, 'eventalk_event_schedule', true);   


$socials                    = get_post_meta($id, "{$cpt}_event_social", true );
$socials                    = array_filter($socials);
$social_fields              = Helper::event_socials();               
$starDate                   = Helper::custom_date_format($_event_start_date);            
$endDate                    = Helper::custom_date_format($_event_end_date);                      
$dStart             = new \DateTime($starDate);                
$dEnd               = new \DateTime($endDate);
$dDiff              = $dStart->diff($dEnd);
$difday             =  $dDiff->days;     
?>
             <h2 class="multiple-title text-center margin-b-30"><?Php the_title(); ?></h2>  
                <div class="schedule-wrapper schedule-slider">
                    <?php for($i = 0; $i <= $difday; $i++) {
                        $datetime = new \DateTime($starDate);
                        $datetime->modify('+'.$i.' day');
                        $dayNumber = $i + 1;
                        $sdl  = array();
                        foreach ($schedules as $schedule) {                    
                            if(Helper::custom_date_format( $schedule['session_start_date'], 'm/d/Y' ) == $datetime->format('m/d/Y')){                         
                                $schedule['day_number'] = $dayNumber;                               
                                $schedule['date'] = $datetime->format('m d y' );
                                $schedule['date'] = date_i18n( 'm d y', strtotime($schedule['session_start_date']));
                                $sdl[] = $schedule;
                            }
                        }
                        if(!empty($sdl)){
                            ?>
                            <div class="schedule-slider-item table-responsive">
                                  <div class="table-responsive">
                                <table class="table table-striped schedule-layout1">
                                    <tbody class="menu-list">
                                        <?php foreach ($sdl as $item) { 
                                            $sDate           = \DateTime::createFromFormat('m/d/Y', $item['session_start_date']);
                                                $schedule_speaker_title = esc_attr(get_the_title($item['speaker'])) ;                 
                                                $schedule_speaker_img = get_the_post_thumbnail_url($item['speaker'], 'thumbnail' ) ;
                                                if (!empty($item['speaker2'])) {
                                                    $schedule_speaker_title2 = esc_attr(get_the_title($item['speaker2'])) ;                 
                                                    $schedule_speaker_img2 = get_the_post_thumbnail_url($item['speaker2'], 'thumbnail' ) ;   
                                                }
                                            ?>
                                            <tr class="menu-item">
                                                <th class="event-color" style="background-color: <?php echo esc_attr($item['color']); ?>">
                                                   
                                                           <div class="day-number"><?php echo esc_html__( 'Day', 'eventalk' );?> - 0<?php echo esc_html($item['day_number']); ?></div>
                                                 
                                                                                                                                                     
                                                    <div class="schedule-date">
                                                        <?php echo esc_html( date_i18n( 'm d y', strtotime($item['session_start_date']))); ?>
                                                    </div>                                                
                                                </th>
                                                <td>
                                                    <h3 class="schedule-title">                                                       
                                                            <a href="<?php the_permalink();?>"><?php echo esc_attr($item['session_title']); ?></a>
                                                            
                                                    </h3>
                                                </td>
                                                <td>
                                                    <div class="schedule-time">
                                                        <i class="fa fa-clock-o" aria-hidden="true"></i><?php echo wp_kses_post($item['session_start_time']); ?> - <?php echo esc_html($item['session_end_time']); ?></div>
                                                </td>
                                                 <td>                               
                                                <?php if($item['speaker']){ ?>
                                                    <?php if($schedule_speaker_img){ ?>
                                                        <ul class="schedule-speaker">                           
                                                            <li>
                                                                <div class="speaker-img-tooltip" data-tips="<?php  echo  esc_attr(get_the_title($item['speaker'])) ;?>">
                                                                <a href="<?php echo get_permalink( $item['speaker']); ?>">
                                                                    <img src="<?php echo get_the_post_thumbnail_url($item['speaker'], 'thumbnail' ) ; ?>" alt="schedule" class="rounded-circle"></a>
                                                            </div>   
                                                            </li>    
                                                        </ul>
                                                        <?php }else {  ?>
                                                            <div class="title_sp text-right"> 
                                                             <?php   echo esc_attr(get_the_title($schedule['speaker'])) ; ?>
                                                        </div>
                                                    <?php  } ?> 
                                                <?php  } ?> 
                                    <?php if (!empty($item['speaker2'])) { ?>
                                        <?php if($schedule_speaker_img2){ ?>
                                            <ul class="schedule-speaker">                           
                                                <li>
                                                    <div class="speaker-img-tooltip" data-tips="<?php  echo  esc_attr(get_the_title($item['speaker2'])) ;?>">
                                                        <a href="<?php echo get_permalink( $item['speaker2']); ?>">
                                                            <img src="<?php echo get_the_post_thumbnail_url($item['speaker2'], 'thumbnail' ) ; ?>" alt="schedule" class="rounded-circle">
                                                        </a>
                                                    </div>   
                                                </li>    
                                            </ul>
                                            <?php }else {  ?>
                                            <div class="title_sp text-right"> 
                                                <?php   echo esc_attr(get_the_title($schedule['speaker2'])) ; ?>
                                            </div>
                                        <?php  } ?>                                   
                                    <?php  } ?>   
                                    <?php if (!empty($item['speaker3'])) { ?>
                                        <?php if($schedule_speaker_img2){ ?>
                                            <ul class="schedule-speaker">                           
                                                <li>
                                                    <div class="speaker-img-tooltip" data-tips="<?php  echo  esc_attr(get_the_title($item['speaker3'])) ;?>">
                                                        <a href="<?php echo get_permalink( $item['speaker3']); ?>">
                                                            <img src="<?php echo get_the_post_thumbnail_url($item['speaker3'], 'thumbnail' ) ; ?>" alt="schedule" class="rounded-circle">
                                                        </a>
                                                    </div>   
                                                </li>    
                                            </ul>
                                            <?php }else {  ?>
                                            <div class="title_sp text-right"> 
                                                <?php   echo esc_attr(get_the_title($schedule['speaker3'])) ; ?>
                                            </div>
                                        <?php  } ?>                                   
                                    <?php  } ?>   
                                    <?php if (!empty($item['speaker4'])) { ?>
                                        <?php if($schedule_speaker_img2){ ?>
                                            <ul class="schedule-speaker">                           
                                                <li>
                                                    <div class="speaker-img-tooltip" data-tips="<?php  echo  esc_attr(get_the_title($item['speaker4'])) ;?>">
                                                        <a href="<?php echo get_permalink( $item['speaker4']); ?>">
                                                            <img src="<?php echo get_the_post_thumbnail_url($item['speaker4'], 'thumbnail' ) ; ?>" alt="schedule" class="rounded-circle">
                                                        </a>
                                                    </div>   
                                                </li>    
                                            </ul>
                                            <?php }else {  ?>
                                            <div class="title_sp text-right"> 
                                                <?php   echo esc_attr(get_the_title($schedule['speaker4'])) ; ?>
                                            </div>
                                        <?php  } ?>                                   
                                    <?php  } ?>   
                                    <?php if (!empty($item['speaker5'])) { ?>
                                        <?php if($schedule_speaker_img2){ ?>
                                            <ul class="schedule-speaker">                           
                                                <li>
                                                    <div class="speaker-img-tooltip" data-tips="<?php  echo  esc_attr(get_the_title($item['speaker5'])) ;?>">
                                                        <a href="<?php echo get_permalink( $item['speaker5']); ?>">
                                                            <img src="<?php echo get_the_post_thumbnail_url($item['speaker5'], 'thumbnail' ) ; ?>" alt="schedule" class="rounded-circle">
                                                        </a>
                                                    </div>   
                                                </li>    
                                            </ul>
                                            <?php }else {  ?>
                                            <div class="title_sp text-right"> 
                                                <?php   echo esc_attr(get_the_title($schedule['speaker5'])) ; ?>
                                            </div>
                                        <?php  } ?>                                   
                                    <?php  } ?>                                      
                                   
                                            </td>
                                            </tr>
                            			<?php } // End foreach?>
                                    </tbody>
                                </table>
                            </div>
                            </div>
                        <?php 
                        } // End sdl if
                  } //End for loop?>
                </div>
            