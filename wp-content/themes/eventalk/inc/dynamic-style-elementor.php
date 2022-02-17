<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Eventalk;

/*-------------------------------------
INDEX
=======================================
#. Section Title
#. EL: Owl Nav
#. EL: Owl Dots
#. EL: Post Slider
#. EL: Speaker Area
#. EL: Gallrey Tab
#. EL: Gallrey 1
#. EL: Gallrey 2
#. EL: Gallrey 3
#. EL: Call To Action
#. EL: Counter
#. EL: Info Box
#. EL: Text With Title
#. EL: Navigation Menu
#. EL: Contact
#. EL: Schedule
#. EL: Slider
#. EL: Price table

-------------------------------------*/

$prefix = THEME_PREFIX_VAR;
$primary_color    = apply_filters( "{$prefix}_primary_color", RDTheme::$options['primary_color'] ); // #ffbe00
$secondery_color  = apply_filters( "{$prefix}_secondery_color", RDTheme::$options['secondery_color'] ); // #d49f05
$primary_rgb      = Helper::hex2rgb( $primary_color ); // 255,190,0
$secondery_rgb      = Helper::hex2rgb( $secondery_color ); // 255,190,0
?>

<?php
/*-------------------------------------
#. Section Title
---------------------------------------*/
?>

.rt-el-title.style2 .rtin-title:after,
.rt-el-twt-3.rtin-dark .rtin-title:after{
	background-color: <?php echo esc_html( $primary_color ); ?>;
}
.header-icon-area .search-box .search-button i{
	color: <?php echo esc_html( $primary_color ); ?>;
}
#tophead .tophead-social li a:hover{
		color: <?php echo esc_html( $primary_color ); ?>;
}
<?php
/*-------------------------------------
#. EL: Owl Nav
---------------------------------------*/
?>
.rt-owl-nav .owl-theme .owl-nav > div {
	color: <?php echo esc_html( $primary_color ); ?>;
}
.rt-owl-nav .owl-theme .owl-nav > div {
	border-color: <?php echo esc_html( $primary_color ); ?>;
	
}
.rt-owl-nav .owl-theme .owl-nav > div:hover {	
	background-color: <?php echo esc_html( $primary_color ); ?>;
}

<?php
/*-------------------------------------
#. EL: Owl Dots
---------------------------------------*/
?>
.rt-owl-dot .owl-theme .owl-dots .owl-dot.active span,
.rt-owl-dot .owl-theme .owl-dots .owl-dot:hover span {
	background-color: <?php echo esc_html( $primary_color ); ?>;
}

<?php
/*-------------------------------------
#. EL: Post Slider
---------------------------------------*/
?>
.rt-el-post-slider .rtin-item .rtin-content-area .date-time {
		color: <?php echo esc_html( $primary_color ); ?>;
}
.rt-el-post-slider .rtin-item .rtin-content-area .rtin-header .rtin-title a:hover,
.rt-el-post-slider .rtin-item .rtin-content-area .read-more-btn i,
.rt-el-post-slider .rtin-item .rtin-content-area .read-more-btn:hover {
	color: <?php echo esc_html( $primary_color ); ?>;
}

<?php
/*-------------------------------------
#. EL: Speaker Area
---------------------------------------*/
?>
.about-layout2.rtin-layout3 .video-area .video-icon .popup-video {
		color: <?php echo esc_html( $primary_color ); ?>;
}
.about-layout2.rtin-layout3 .video-area .video-icon .play-btn:hover {
	background-color: <?php echo esc_html( $secondery_color ); ?>;
	color: <?php echo esc_html( $secondery_color ); ?>;
}
.speaker-layout4 .item-title .title a:hover {
	color: <?php echo esc_html( $primary_color ); ?>;
}
.title-bar:before {	
	background-color: <?php echo esc_html( $primary_color ); ?>;
}
.speaker-layout3:before {
	background: rgba(<?php echo esc_html( $primary_rgb ); ?>, 0.8);	
}
.speaker-layout3 .item-social ul li a{
	color: <?php echo esc_html( $secondery_color ); ?>;
}
.speaker-layout1 .item-img:before {	
	background: rgba(<?php echo esc_html( $secondery_rgb ); ?>, 0.9);
}
.speaker-layout2 .item-social ul li a:hover {
	color: <?php echo esc_html( $secondery_color ); ?>;
}
.title-light.hover-yellow a:hover, 
.title-regular.hover-yellow a:hover,
.title-medium.hover-yellow a:hover, 
.title-semibold.hover-yellow a:hover,
.title-bold.hover-yellow a:hover, 
.title-black.hover-yellow a:hover{
	color: <?php echo esc_html( $secondery_color ); ?>;
}
.speaker-layout2:before {
	background: rgba(<?php echo esc_html( $primary_rgb ); ?>, 0.8);
}

<?php
/*-------------------------------------
#. EL: Gallrey Tab
---------------------------------------*/
?>
.rt-el-gallrey-tab a {
	border-color: <?php echo esc_html( $secondery_color ); ?>;
}
.rt-el-gallrey-tab a:hover,
.rt-el-gallrey-tab .current {
	background-color: <?php echo esc_html( $secondery_color ); ?>;
	color: #111 !important;
}

<?php
/*-------------------------------------
#. EL: Gallrey 1
---------------------------------------*/
?>
.rt-el-gallrey-1 .rtin-item .rtin-content {
	background-color: <?php echo esc_html( $primary_color ); ?>;
}
.rt-el-gallrey-1 .rtin-item .rtin-icon:hover {
	color: <?php echo esc_html( $primary_color ); ?>;
}
.rt-el-gallrey-box .rtin-content .rtin-title a:hover{
	color: <?php echo esc_html( $primary_color ); ?>;
}
.rt-el-gallrey-1 .rtin-item:before {	
	background-color: rgba(<?php echo esc_html( $primary_rgb ); ?>, 0.8);
}
.rt-el-post-slider .rtin-item .rtin-thumbnail-area .rtin-meta-1 {	
	background-color: rgba(<?php echo esc_html( $primary_rgb ); ?>, 0.8);
}
.rt-el-gallrey-1 .rtin-item .rtin-icon:hover {
	color: <?php echo esc_html( $primary_color ); ?>;
}
.sidebar-widget-area .contact-us-form {
	background-color: <?php echo esc_html( $primary_color ); ?>;
}
.sidebar-widget-area .contact-us-form .form-group .form-control {
	background: <?php echo esc_html( $secondery_color ); ?>;
}
.services-single .rtin-heading:after{
	background-color: <?php echo esc_html( $primary_color ); ?>;
}
.rt-el-title.style3 .rtin-title:after{
	background-color: <?php echo esc_html( $primary_color ); ?>;
}
<?php
/*-------------------------------------
#. EL: Gallrey 2
---------------------------------------*/
?>
.rt-el-gallrey-2 .rtin-item:before {
	background-image: linear-gradient(transparent, <?php echo esc_html( $primary_color ); ?>), linear-gradient(transparent, <?php echo esc_html( $primary_color ); ?>);
}
.rt-el-gallrey-2 .rtin-item .rtin-icon:hover {
	color: <?php echo esc_html( $primary_color ); ?>;
}

<?php
/*-------------------------------------
#. EL: Gallrey 3
---------------------------------------*/
?>
.rt-el-gallrey-3 .rtin-item:before {
	background-color: rgba(<?php echo esc_html( $primary_rgb ); ?>, 0.8);
}
.rt-el-gallrey-3 .rtin-item .rtin-content .rtin-icon {
	color: <?php echo esc_html( $primary_color ); ?>;
}
.rt-el-services-box.rtin-style3 .rtin-content .rtin-title:after {
	background-color: <?php echo esc_html( $primary_color ); ?>;
}
.services-single .rtin-heading:after,
.rt-el-title.style3 .rtin-title:after {
	background-color: <?php echo esc_html( $primary_color ); ?>;
}
.widget .category-type ul li a:hover {
	background-color: <?php echo esc_html( $primary_color ); ?>;	
}



<?php
/*-------------------------------------
#. EL: Call To Action
---------------------------------------*/
?>
.rt-el-cta-1 {
	background-color: <?php echo esc_html( $primary_color ); ?>;
}

<?php
/*-------------------------------------
#. EL: Counter
---------------------------------------*/
?>
.rt-el-counter .rtin-left .fa,
.rt-el-counter .rtin-item .rtin-right.text-center .rtin-title {
	color: <?php echo esc_html( $primary_color ); ?>;
}

<?php
/*-------------------------------------
#. EL: Info Box
---------------------------------------*/
?>
.rt-el-info-box .rtin-content .rtin-title a:hover {
	color: <?php echo esc_html( $primary_color ); ?>;
}
.rt-el-info-box.rtin-style1.rtin-light .rtin-content .rtin-title a,
.rt-el-info-box.rtin-style1.rtin-light .rtin-icon i,
.rt-el-info-box.rtin-style1.rtin-light .rtin-content .rtin-title {
	color: <?php echo esc_html( $secondery_color );?>!important;;

}
.rt-el-info-box.rtin-style3 .rtin-icon .rtin-button {	
	background-color: rgba(<?php echo esc_html( $primary_rgb ); ?>, 0.9);	
}
.rt-el-twt-2 .rtin-title span {
	color: <?php echo esc_html( $primary_color ); ?>;
}
.rt-el-twt-2 .rtin-content ul li:after {
	color: <?php echo esc_html( $primary_color ); ?>;
}

<?php
/*-------------------------------------
#. EL: Text With Title
---------------------------------------*/
?>
.rt-el-twt-3 .rtin-title:after {
	background-color: <?php echo esc_html( $primary_color ); ?>;
}

<?php
/*-------------------------------------
#. EL: Navigation Menu
---------------------------------------*/
?>
.rt-el-nav-menu.widget ul li.current-menu-item a,
.rt-el-nav-menu.widget ul li.current-menu-item a:hover {
	background-color: <?php echo esc_html( $secondery_color ); ?>;
}
.site-header .main-navigation > nav > ul > li > a:after {
	background-color: <?php echo esc_html( $secondery_color ); ?>;
}
<?php
/*-------------------------------------
#. EL: Contact
---------------------------------------*/
?>
.rt-el-contact ul li i {
	color: <?php echo esc_html( $primary_color ); ?>;
}

<?php
/*-------------------------------------
#. EL: Schedule
---------------------------------------*/
?>
.schedule-wrapper-8 .schedule-title a:hover {
	color: <?php echo esc_html( $secondery_color ); ?>;
}
.schedule-wrapper-8 .schedule-list-info li i,
.schedule-wrapper-8 .details-down:hover,
.schedule-wrapper-8 .schedule-time i {
	color: <?php echo esc_html( $secondery_color ); ?>;
}
.schedule-layout3 .schedule-nav li a:hover {
color: <?php echo esc_html( $secondery_color ); ?>;
}
.schedule-layout3 .schedule-nav li a:hover:before {
color: <?php echo esc_html( $secondery_color ); ?>;
}
.schedule-layout3 .schedule-nav li .active:before {
	color: <?php echo esc_html( $secondery_color ); ?>;
}
.schedule-layout3 .schedule-nav li .active {
color: <?php echo esc_html( $secondery_color ); ?>;
}
.schedule-layout2 .schedule-nav li a:before {
	background:<?php echo esc_html( $secondery_color ); ?>;
}
.schedule-layout2 .schedule-nav li a:hover {
	background:<?php echo esc_html( $secondery_color ); ?>;
}
<?php
/*-------------------------------------
#. EL: Slider
---------------------------------------*/
?>

.slider-layout2 .comingsoon-inner .comingsoon-content .upcoming-event-info{
	color: <?php echo esc_html( $secondery_color ); ?>;
}
.rt-el-slider .nivo-directionNav a.nivo-prevNav:before, 
.rt-el-slider .nivo-directionNav a.nivo-nextNav:before {
	color: <?php echo esc_html( $secondery_color ); ?>;
}
.rt-el-slider .nivo-directionNav a.nivo-prevNav:hover,
.rt-el-slider .nivo-directionNav a.nivo-nextNav:hover {
		background-color: <?php echo esc_html( $secondery_color ); ?>;
}



.skew{
background: rgba(<?php echo esc_html( $primary_rgb ); ?>, 0.7);	 
}
.skew_2{
 background: rgba(<?php echo esc_html( $primary_rgb ); ?>, 0.6);	
 
}
.skew_3{
 background: rgba(<?php echo esc_html( $primary_rgb ); ?>, 0.5);	
 
}

<?php
/*-------------------------------------
#. EL: Price table
---------------------------------------*/
?>
.price-table-layout3 .tpt-col-inner:hover{
	background: <?php echo esc_html( $primary_color ); ?>;	
}
.price-table-layout2:after {
	background: <?php echo esc_html( $secondery_color ); ?>;	
}
.price-table-layout2 .tpt-header .tpt-header-top .tpt-title:before {
	background-color: <?php echo esc_html( $secondery_color ); ?>;
}
.price-table-layout2 .tpt-header .tpt-header-top .tpt-title:after {
	background-color: <?php echo esc_html( $secondery_color ); ?>;
}
.price-table-layout3 .tpt-footer .tpt-footer-btn {	
	background-color: <?php echo esc_html( $secondery_color ); ?>;
}
.price-table-layout3 .tpt-col-inner:hover .tpt-header .tpt-header-top {
	border-bottom: 1px solid <?php echo esc_html( $secondery_color ); ?>;
}