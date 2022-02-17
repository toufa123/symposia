<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.4
 */
namespace radiustheme\Eventalk;
use radiustheme\Eventalk\Helper;
global $post;

$prefix                 = THEME_PREFIX;
$cpt                    = THEME_CPT_PREFIX;
$thumb_size             = "{$prefix}-size4";
$designation            = get_post_meta( $post->ID, "{$cpt}_speaker_designation", true );
$degree                 = get_post_meta( $post->ID, "{$cpt}_degree", true );
$education              = get_post_meta( $post->ID, "{$cpt}_education", true );
$contact_number         = get_post_meta( $post->ID, "{$cpt}_contact_number", true );
$email                  = get_post_meta( $post->ID, "{$cpt}_email", true );
$times_chedules         = get_post_meta( $post->ID, "{$cpt}_times_chedules", true );
$appointment_link       = get_post_meta( $post->ID, "{$cpt}_appointment_link", true );
$skills                 = get_post_meta( $post->ID, "{$cpt}_speaker_skill", true );
$socials                = get_post_meta( $post->ID, "{$cpt}_speaker_social", true );
$socials                = array_filter( $socials );
$socials_fields         = Helper::speakers_socials();
$date_format            = 'j F, Y';
$selected_events        = array();
$events                 = get_posts(array(
    'post_type' => 'eventalk_event',
    'posts_per_page' => -1
));
$has_events = is_array($events) && count($events);
$speaker_labels  = array('speaker', 'speaker2', 'speaker3', 'speaker4', 'speaker5','speaker6','speaker7','speaker8','speaker9'); 
if ( $has_events ) {
  foreach($events as $event){    
     $schedules =  get_post_meta($event->ID, 'eventalk_event_schedule', true);
        if($schedules && is_array($schedules) && !empty($schedules)){
         foreach ($schedules as $schedule) {
             foreach ($speaker_labels as $speaker_label) {
                 if(isset($schedule[$speaker_label]) && absint($schedule[$speaker_label]) === $post->ID){
                  $selected_events[$event->ID][] = $schedule;
                 }
             }
         }
     }
  }
}

$has_selected_events = is_array( $selected_events ) && count( $selected_events );
?>
<div id="post-<?php the_ID(); ?>" <?php post_class( 'team-single-2' ); ?>>
    <div class="row">
        <div class="col-lg-4 col-md-12 col-sm-12">
            <div class="single-speaker-holder">
                <div class="single-speaker-img">
                   <?php the_post_thumbnail( $thumb_size );?>
                </div>
                <div class="single-speaker-designation">
                   <h3><?php the_title();?></h3>
                   <p><?php echo esc_html( $designation );?></p>                  
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-md-12 col-sm-12">
            <div class="speaker-profile">  
                <h3 class="content-title"><?php  echo esc_html__( 'Biography', 'eventalk' ); ?> </h3>
                 <?php the_content();?>                
            </div>
            <?php if ( $has_selected_events ): ?>
              <div class="speaker-profile">  
                <h3 class="content-title"><?php  echo esc_html__( 'All sessions', 'eventalk' ); ?> </h3> 
                <div class="speaker-profile row"> 
                  <?php  foreach ($selected_events as $selected_event) : ?>
                     <?php foreach ($selected_event as $item){?>

                        <div class="col-lg-6 col-md-12 col-sm-12">
                          <div class="speaker-info "> 
                              <h4 class="speaker-session-item-title"><?php echo esc_html($item['session_title']); ?></h4>
                              <ul>                         
                                  <li><i class="fa fa-calendar"></i> <?php echo esc_html( date_i18n( $date_format, strtotime($item['session_start_date']))); ?>
                              </li>
                                  <li><i class="fa fa-clock-o"></i><?php echo esc_html($item['session_start_time']); ?> - <?php echo esc_html($item['session_end_time']); ?></li>
                                  <li><i class="fa fa-location-arrow"></i><?php echo esc_html($item['schedule_Hall']); ?></li>
                              </ul>
                          </div>
                      </div>

                     <?php } ?>   

                  <?Php  endforeach; ?>
                      </div>
              </div>
            <?php endif ?>

        </div>
    </div>   
</div>