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
#. Defaults
#. Top Bar
#. Header
#. Banner
#. Footer
#. Widgets
#. Typography
#. Pagination
#. Buttons
#. Error 404
#. Comments
#. Inner Contents
#. Comments
#. Error 404
#. CPT
#. Blog
#. WooCommerce

-------------------------------------*/

$prefix = THEME_PREFIX_VAR;
$primary_color    = apply_filters( "{$prefix}_primary_color", RDTheme::$options['primary_color'] ); 
$secondery_color  = apply_filters( "{$prefix}_secondery_color", RDTheme::$options['secondery_color'] ); 
$primary_rgb      = Helper::hex2rgb( $primary_color ); // 255,190,0
$secondery_rgb      = Helper::hex2rgb( $secondery_color ); // 255,190,0

$menu_typo                = RDTheme::$options['menu_typo'];
$submenu_typo             = RDTheme::$options['submenu_typo'];
$resmenu_typo             = RDTheme::$options['resmenu_typo'];

$typo_body                = RDTheme::$options['typo_body'];
$typo_h1                  = RDTheme::$options['typo_h1'];
$typo_h2                  = RDTheme::$options['typo_h2'];
$typo_h3                  = RDTheme::$options['typo_h3'];
$typo_h4                  = RDTheme::$options['typo_h4'];
$typo_h5                  = RDTheme::$options['typo_h5'];
$typo_h6                  = RDTheme::$options['typo_h6'];

$menu_color               = RDTheme::$options['menu_color'];
$menu_color_tr            = RDTheme::$options['menu_color_tr'];
$menu_hover_color         = RDTheme::$options['sitewide_color'] == 'custom' ? RDTheme::$options['menu_hover_color'] : $secondery_color;
$menu_hover_color_tr      = RDTheme::$options['sitewide_color'] == 'custom' ? RDTheme::$options['menu_hover_color_tr'] : $secondery_color;
$submenu_color            = RDTheme::$options['submenu_color'];
$submenu_bgcolor          = RDTheme::$options['sitewide_color'] == 'custom' ? RDTheme::$options['submenu_bgcolor'] : $secondery_color;
$submenu_hover_color      = RDTheme::$options['submenu_hover_color'];
$submenu_hover_bgcolor    = RDTheme::$options['sitewide_color'] == 'custom' ? RDTheme::$options['submenu_hover_bgcolor'] : $secondery_color;

$top_bar_icon_color       = RDTheme::$options['sitewide_color'] == 'custom' ? RDTheme::$options['top_bar_icon_color'] : $secondery_color;
$breadcrumb_link_color    = RDTheme::$options['sitewide_color'] == 'custom' ? RDTheme::$options['breadcrumb_link_color'] : $secondery_color;
$footer_link_hover_color  = RDTheme::$options['sitewide_color'] == 'custom' ? RDTheme::$options['footer_link_hover_color'] : $secondery_color;
$error_bodybg             = RDTheme::$options['sitewide_color'] == 'custom' ? RDTheme::$options['error_bodybg'] : $primary_color;
?>

<?php
/*-------------------------------------
#. Defaults
---------------------------------------*/
?>
.mobile-stick .mean-container .mean-bar{
	 background-color: <?php echo esc_html( RDTheme::$options['mobile_menu_bgcolor'] ); ?>;
}
.primary-color {
	color: <?php echo esc_html( $primary_color ); ?>;
}
.secondery-color {
	color: <?php echo esc_html( $secondery_color ); ?>;
}
.primary-bgcolor,
.bg-primary {
	background-color: <?php echo esc_html( $primary_color ); ?>!important;
}
.secondery-bgcolor,
.bg-secondary {
	background-color: <?php echo esc_html( $secondery_color ); ?>!important;
}
.site-wrp .color-primary{
	color: <?php echo esc_html( $primary_color ); ?>;
}
.overlay-primary80:before {	
	background-color: rgba(<?php echo esc_html( $primary_rgb ); ?>, 0.8);
}
.overlay-primary90:before {	
	background-color: rgba(<?php echo esc_html( $primary_rgb ); ?>, 0.9);
}

body #preloader {
 background-color: <?php echo esc_html( $primary_color ); ?>;
}
<?php
/*-------------------------------------
#. Top Bar
---------------------------------------*/
?>
#tophead {
    background-color: <?php echo esc_html( RDTheme::$options['top_bar_bgcolor'] ); ?>;
}
#tophead .tophead-contact .fa,
#tophead .tophead-address .fa{
 color: <?php echo esc_html( $primary_color ); ?>;
}
#tophead,
#tophead a,
#tophead .tophead-social li a,
#tophead .tophead-social li a:hover {
    color: <?php echo esc_html( RDTheme::$options['top_bar_color'] ); ?>;
}

#tophead .tophead-contact .fa,
#tophead .tophead-address .fa {
	color: <?php echo esc_html( $top_bar_icon_color ); ?>;
}

.trheader #tophead{
	background-color: rgba(<?php echo esc_html( Helper::hex2rgb( RDTheme::$options['top_bar_bgcolor'] ) ); ?>, 0.8);
}

.trheader #tophead,
.trheader #tophead a,
.trheader #tophead .tophead-social li a,
.trheader #tophead .tophead-social li a:hover {
	color: <?php echo esc_html( RDTheme::$options['top_bar_color_tr'] ); ?>;
}

<?php // Topbar Layout 5 ?>
.topbar-style-5 #tophead .tophead-social li a:hover,
.trheader.topbar-style-5 #tophead .tophead-social li a:hover {
	color: <?php echo esc_html( $top_bar_icon_color ); ?>;
}

<?php
/*-------------------------------------
#. Header
---------------------------------------*/
?>
<?php // slider ?>
.site-wrp .ls-v6 .ls-nav-prev:after, 
.site-wrp .ls-v6 .ls-nav-next:after {       
color: <?php echo esc_html( $secondery_color ); ?> !important;
}  

.site-wrp .ls-v6 .ls-nav-prev, 
.site-wrp .ls-v6 .ls-nav-next {
	border: 2px solid <?php echo esc_html( $secondery_color ); ?> !important;
}

.site-wrp .ls-v6 .ls-nav-next:hover,
.site-wrp .ls-v6 .ls-nav-prev:hover{        
border: 2px solid <?php echo esc_html( $secondery_color ); ?> !important;
background-color: <?php echo esc_html( $secondery_color ); ?> !important;
}
.site-wrp  .ls-v6 .ls-nav-next:after { 
border-right: 3px solid <?php echo esc_html( $secondery_color ); ?> !important;
}


.site-wrp .ls-v6 .ls-nav-prev:after, 
.site-wrp  .ls-v6 .ls-nav-next:after {    
border-top: 3px solid  <?php echo esc_html( $secondery_color ); ?> !important;
}

.site-wrp  .ls-v6 .ls-nav-prev:after {   
border-left: 3px solid <?php echo esc_html( $secondery_color ); ?> !important;
}

<?php // Main Menu ?>
.header-icon-area .search-box .search-button i{
	color: <?php echo esc_html( $primary_color); ?>;
}
.search-form .custom-search-input button.btn {	
	background-color: <?php echo esc_html( $secondery_color ); ?>;
}
.site-header .main-navigation ul li a {
	font-family: <?php echo esc_html( $menu_typo['font-family'] ); ?>, sans-serif;
	font-size : <?php echo esc_html( $menu_typo['font-size'] ); ?>;
	font-weight : <?php echo esc_html( $menu_typo['font-weight'] ); ?>;
	line-height : <?php echo esc_html( $menu_typo['line-height'] ); ?>;
	color: <?php echo esc_html( $menu_color ); ?>;
	text-transform : <?php echo esc_html( $menu_typo['text-transform'] ); ?>;
	font-style: <?php echo empty( $menu_typo['font-style'] ) ? 'normal' : $menu_typo['font-style']; ?>;
}
.site-header .main-navigation ul.menu > li > a:hover,
.site-header .main-navigation ul.menu > li.current-menu-item > a,
.site-header .main-navigation ul.menu > li.current > a {
	color: <?php echo esc_html( $menu_hover_color ); ?>;
}
.trheader.non-stick .site-header .main-navigation ul.menu > li > a,
.trheader.non-stick .site-header .search-box .search-button i,
.trheader.non-stick .header-icon-seperator,
.trheader.non-stick .header-icon-area .cart-icon-area > a, 
.trheader.non-stick .additional-menu-area a.side-menu-trigger {
	color: <?php echo esc_html( $menu_color_tr ); ?>;
}
.trheader.non-stick .site-header .main-navigation ul.menu > li > a:hover,
.trheader.non-stick .site-header .main-navigation ul.menu > li.current-menu-item > a,
.trheader.non-stick .site-header .main-navigation ul.menu > li.current > a {
	color: <?php echo esc_html( $menu_hover_color_tr ); ?>;
}
<?php // Submenu ?>
.site-header .main-navigation ul li ul{
	 border-top: 1px solid <?php echo esc_html( $secondery_color ); ?>;
}
.site-header .main-navigation ul li ul li:hover > a {
	background-color: <?php echo esc_html( $secondery_color ); ?>;
	
}
.site-header .main-navigation ul li ul li a {
	font-family: <?php echo esc_html( $submenu_typo['font-family'] ); ?>, sans-serif;
	font-size : <?php echo esc_html( $submenu_typo['font-size'] ); ?>;
	font-weight : <?php echo esc_html( $submenu_typo['font-weight'] ); ?>;
	line-height : <?php echo esc_html( $submenu_typo['line-height'] ); ?>;
	color: <?php echo esc_html( $submenu_color ); ?>;
	text-transform : <?php echo esc_html( $submenu_typo['text-transform'] ); ?>;
	font-style: <?php echo empty( $submenu_typo['font-style'] ) ? 'normal' : $submenu_typo['font-style']; ?>;
}
<?php // Sticky Menu ?>
.stick .site-header {
	border-color: <?php echo esc_html( $primary_color ); ?>
}
<?php // Multi Column Menu ?>
.site-header .main-navigation ul li.mega-menu > ul.sub-menu {
	background-color: <?php echo esc_html( $submenu_bgcolor ); ?>
}
.site-header .main-navigation ul li.mega-menu ul.sub-menu li a {
	color: <?php echo esc_html( $submenu_color ); ?>
}
.site-header .main-navigation ul li.mega-menu ul.sub-menu li a:hover {
	background-color: <?php echo esc_html( $submenu_hover_bgcolor ); ?>;
	color: <?php echo esc_html( $submenu_hover_color ); ?>;
}
<?php // Mean Menu ?>
.mean-container a.meanmenu-reveal,
.mean-container .mean-nav ul li a.mean-expand {
	color: <?php echo esc_html( $menu_hover_color ); ?>;
}
.mean-container a.meanmenu-reveal span {
	background-color: <?php echo esc_html( $menu_hover_color ); ?>;
}
.mean-container .mean-bar {
	border-color: <?php echo esc_html( $menu_hover_color ); ?>;
}
.mean-container .mean-nav ul li a {
	font-family: <?php echo esc_html( $resmenu_typo['font-family'] ); ?>, sans-serif;
	font-size : <?php echo esc_html( $resmenu_typo['font-size'] ); ?>;
	font-weight : <?php echo esc_html( $resmenu_typo['font-weight'] ); ?>;
	line-height : <?php echo esc_html( $resmenu_typo['line-height'] ); ?>;
	color: <?php echo esc_html( $menu_color ); ?>;
	text-transform : <?php echo esc_html( $resmenu_typo['text-transform'] ); ?>;
	font-style: <?php echo empty( $resmenu_typo['font-style'] ) ? 'normal' : $resmenu_typo['font-style']; ?>;
}
.mean-container .mean-nav ul li a:hover,
.mean-container .mean-nav > ul > li.current-menu-item > a {
	color: <?php echo esc_html( $menu_hover_color ); ?>;
}

<?php // Header icons ?>
.header-icon-area .cart-icon-area .cart-icon-num {
	background-color: <?php echo esc_html( $menu_hover_color );?>;
}
.site-header .search-box .search-text {
	border-color: <?php echo esc_html( $menu_hover_color );?>;
}

<?php // Header Layout 3 ?>
.header-style-3 .header-social li a:hover {
	color: <?php echo esc_html( $menu_hover_color );?>;
}
.header-style-3.trheader .header-contact li a,
.header-style-3.trheader .header-social li a {
	color: <?php echo esc_html( $menu_color_tr ); ?>;
}
.header-style-3.trheader .header-social li a:hover {
	color: <?php echo esc_html( $menu_hover_color_tr ); ?>;
}

<?php // Header Layout 4 ?>
.header-style-4 .header-social li a:hover {
	color: <?php echo esc_html( $menu_hover_color );?>;
}
.header-style-4.trheader .header-contact li a,
.header-style-4.trheader .header-social li a {
	color: <?php echo esc_html( $menu_color_tr ); ?>;
}
.header-style-4.trheader .header-social li a:hover {
	color: <?php echo esc_html( $menu_hover_color_tr ); ?>;
}
<?php
/*-------------------------------------
#. Banner
---------------------------------------*/
?>
.entry-banner .entry-banner-content h1 {
	color: <?php echo esc_html( RDTheme::$options['banner_heading_color'] );?>;
}
.breadcrumb-area .entry-breadcrumb span a,
.breadcrumb-area .entry-breadcrumb span a span {
	color: <?php echo esc_html( $breadcrumb_link_color );?>;
}
.breadcrumb-area .entry-breadcrumb span a:hover,
.breadcrumb-area .entry-breadcrumb span a:hover span {
	color: <?php echo esc_html( RDTheme::$options['breadcrumb_link_hover_color'] );?>;
}
.breadcrumb-area .entry-breadcrumb {
	color: <?php echo esc_html( RDTheme::$options['breadcrumb_seperator_color'] );?>;
}
.breadcrumb-area .entry-breadcrumb > span {
	color: <?php echo esc_html( RDTheme::$options['breadcrumb_active_color'] );?>;
}

<?php
/*-------------------------------------
#. Footer
---------------------------------------*/
?>

<!-- 
.footer-layout1.bg-light {
	background-color: <?php echo esc_html( RDTheme::$options['footer_bgcolor'] ); ?> !important;
}
.footer-layout1.bg-light .widget > h3 {
	color: <?php echo esc_html( RDTheme::$options['footer_title_color'] ); ?>;
}
.footer-layout1.bg-light .widget {
	color: <?php echo esc_html( RDTheme::$options['footer_color'] ); ?>;
}
.footer-layout1.bg-light a:link,
.footer-layout1.bg-light a:visited,
.footer-layout1.bg-light widget_nav_menu ul.menu li:before {
	color: <?php echo esc_html( RDTheme::$options['footer_link_color'] ); ?>;
}
.footer-layout1.bg-light .widget a:hover,
.footer-layout1.bg-light .widget a:active {
	color: <?php echo esc_html( $footer_link_hover_color ); ?>;
}

 -->
.footer-layout2 .footer-top-area .widget .btn-fill.size-md,
.footer-layout2 .footer-top-area .widget .btn-ghost.size-md{
	background-color: <?php echo esc_html( $secondery_color ); ?>;
}
.footer-layout2 .footer-top-area .widget .footer-widget-contact a:hover {
	color: <?php echo esc_html( $secondery_color ); ?>;
}
.widget_eventalk_info.widget ul li i,
.footer-layout2 .footer-top-area .widget .footer-widget-contact:before,
.footer-layout2 .footer-bottom-area a:hover{
	color: <?php echo esc_html( $secondery_color ); ?>;
}
.widget.widget_eventalk_about ul li a:hover {
		color: <?php echo esc_html( $primary_color ); ?>;
}
.kebo-tweets .ktweet .kmeta a {
	color: <?php echo esc_html( $primary_color ); ?>;
}
.footer-top-area .widget ul li:before {
	color: <?php echo esc_html( $secondery_color ); ?>;
}
.footer-top-area .widget a:hover,
.footer-top-area .widget a:active {
	color: <?php echo esc_html( $primary_color ); ?>;
}
.footer-top-area {
	background-color: <?php echo esc_html( RDTheme::$options['footer_bgcolor'] ); ?> !important;
}
.footer-top-area .widget > h3 {
	color: <?php echo esc_html( RDTheme::$options['footer_title_color'] ); ?>;
}
.footer-top-area .widget {
	color: <?php echo esc_html( RDTheme::$options['footer_color'] ); ?>;
}
.footer-top-area a:link,
.footer-top-area a:visited,
.footer-top-area widget_nav_menu ul.menu li:before {
	color: <?php echo esc_html( RDTheme::$options['footer_link_color'] ); ?>;
}
.footer-top-area .widget a:hover,
.footer-top-area .widget a:active {
	color: <?php echo esc_html( $footer_link_hover_color ); ?>;
}
.footer-bottom-area {
	background-color: <?php echo esc_html( RDTheme::$options['copyright_bgcolor'] ); ?>;
	color: <?php echo esc_html( RDTheme::$options['copyright_color'] ); ?>;
}
a.scrollToTop {
	background-color: rgba(<?php echo esc_html( $primary_rgb ); ?>, 0.3);
	color: <?php echo esc_html( $primary_color ); ?>;
	border-color: <?php echo esc_html( $primary_color ); ?>;
}
a.scrollToTop:hover,
a.scrollToTop:focus ,
.sidebar-widget-area .widget_eventalk_info{
	background-color: <?php echo esc_html( $primary_color ); ?>;
}
.footer-top-area .widget.widget_eventalk_info ul li i{
	color: <?php echo esc_html( $secondery_color ); ?>;
}

<?php
/*-------------------------------------
#. Widgets
---------------------------------------*/
?>
.widget .category-type ul li:before {
	color: <?php echo esc_html( $primary_color ); ?>;
}
.widget h3:after {
	background-color: <?php echo esc_html( $secondery_color ); ?>;
}
footer .widget h3:after {
	background-color: <?php echo esc_html( $secondery_color ); ?>;
}
.widget ul li:before {
	color: <?php echo esc_html( $secondery_color ); ?>;
}
.widget ul li a:hover {
	color: <?php echo esc_html( $secondery_color ); ?>;
}
.widget_tag_cloud a:hover {
	border-color: <?php echo esc_html( $secondery_color ); ?>;
	background-color: <?php echo esc_html( $secondery_color ); ?>;
}
.footer-top-area .widget.widget_tag_cloud a:hover {
	border-color: <?php echo esc_html( $primary_color ); ?>;
}
.widget.widget_eventalk_about ul li a {
	border-color: <?php echo esc_html( $primary_color ); ?>;
}
.widget.widget_eventalk_info ul li i {
	color: <?php echo esc_html( $primary_color ); ?>;
}
.widget.widget_eventalk_menu ul li a i {
	color: <?php echo esc_html( $primary_color ); ?>;
}
<?php
/*-------------------------------------
#. Typography
---------------------------------------*/
?>
body, ul li {
	font-family: '<?php echo esc_html( $typo_body['font-family'] ); ?>', sans-serif;
	font-size: <?php echo esc_html( $typo_body['font-size'] ); ?>;
	line-height: <?php echo esc_html( $typo_body['line-height'] ); ?>;
	font-weight : <?php echo esc_html( $typo_body['font-weight'] ); ?>;
	font-style: <?php echo empty( $typo_body['font-style'] ) ? 'normal' : $typo_body['font-style']; ?>;
	
}
h1 {
	font-family: '<?php echo esc_html( $typo_h1['font-family'] ); ?>', sans-serif;
	font-size: <?php echo esc_html( $typo_h1['font-size'] ); ?>;
	line-height: <?php echo esc_html( $typo_h1['line-height'] ); ?>;
	font-weight : <?php echo esc_html( $typo_h1['font-weight'] ); ?>;
	font-style: <?php echo empty( $typo_h1['font-style'] ) ? 'normal' : $typo_h1['font-style']; ?>;
}

h2 {
	font-family: '<?php echo esc_html( $typo_h2['font-family'] ); ?>', sans-serif;
	font-size: <?php echo esc_html( $typo_h2['font-size'] ); ?>;
	line-height: <?php echo esc_html( $typo_h2['line-height'] ); ?>;
	font-weight : <?php echo esc_html( $typo_h2['font-weight'] ); ?>;
	font-style: <?php echo empty( $typo_h2['font-style'] ) ? 'normal' : $typo_h2['font-style']; ?>;
}

h3 {
	font-family: '<?php echo esc_html( $typo_h3['font-family'] ); ?>', sans-serif;
	font-size: <?php echo esc_html( $typo_h3['font-size'] ); ?>;
	line-height: <?php echo esc_html( $typo_h3['line-height'] ); ?>;
	font-weight : <?php echo esc_html( $typo_h3['font-weight'] ); ?>;
	font-style: <?php echo empty( $typo_h3['font-style'] ) ? 'normal' : $typo_h3['font-style']; ?>;
}

h4 {
	font-family: '<?php echo esc_html( $typo_h4['font-family'] ); ?>', sans-serif;
	font-size: <?php echo esc_html( $typo_h4['font-size'] ); ?>;
	line-height: <?php echo esc_html( $typo_h4['line-height'] ); ?>;
	font-weight : <?php echo esc_html( $typo_h4['font-weight'] ); ?>;
	font-style: <?php echo empty( $typo_h4['font-style'] ) ? 'normal' : $typo_h4['font-style']; ?>;
}
h5 {
	font-family: '<?php echo esc_html( $typo_h5['font-family'] ); ?>', sans-serif;
	font-size: <?php echo esc_html( $typo_h5['font-size'] ); ?>;
	line-height: <?php echo esc_html( $typo_h5['line-height'] ); ?>;
	font-weight : <?php echo esc_html( $typo_h5['font-weight'] ); ?>;
	font-style: <?php echo empty( $typo_h5['font-style'] ) ? 'normal' : $typo_h5['font-style']; ?>;
}
h6 {
	font-family: '<?php echo esc_html( $typo_h6['font-family'] ); ?>', sans-serif;
	font-size: <?php echo esc_html( $typo_h6['font-size'] ); ?>;
	line-height: <?php echo esc_html( $typo_h6['line-height'] ); ?>;
	font-weight : <?php echo esc_html( $typo_h6['font-weight'] ); ?>;
	font-style: <?php echo empty( $typo_h6['font-style'] ) ? 'normal' : $typo_h6['font-style']; ?>;
}
<?php
/*-------------------------------------
#. Pagination
---------------------------------------*/
?>
.pagination-area ul li.active a,
.pagination-area ul li a:hover,
.pagination-area ul li span.current {
	background-color: <?php echo esc_html( $secondery_color );?>;
}
<?php
/*------------------------------------
# Buttons
------------------------------------*/
?>
.price-table-layout2:hover .tpt-footer .tpt-footer-btn:hover {
	background-color: <?php echo esc_html( $secondery_color );?>;
}
.btn-ghost.color-light.hover-yellow:hover {
	border-color:<?php echo esc_html( $secondery_color );?>;	
	color: <?php echo esc_html( $secondery_color );?>;	
}
.profile-social li a:hover {
	border: 1px solid <?php echo esc_html( $primary_color );?>;
	background: <?php echo esc_html( $primary_color );?>;		
}
.profile-social li a:hover {
	border: 1px solid <?php echo esc_html( $primary_color );?>;
	background-color: <?php echo esc_html( $primary_color );?>;	
}
.rt-el-slider .nivo-directionNav a.nivo-prevNav,
.rt-el-slider .nivo-directionNav a.nivo-nextNav {	
	border: 1px solid <?php echo esc_html( $secondery_color );?>;	
}
.site-wrp .btn-fill.color-primary{
	background-color: <?php echo esc_html( $primary_color );?>;
	border-color: <?php echo esc_html( $primary_color );?>;
	color: #ffffff;
}
.site-wrp .btn-fill.color-primary:hover{
	color: <?php echo esc_html( $primary_color );?>;
}
.site-wrp .btn-fill.color-yellow{
	background-color: <?php echo esc_html( $secondery_color );?>;
	border-color: <?php echo esc_html( $secondery_color );?>;
}
.btn-ghost.color-yellow{
	border-color: <?php echo esc_html( $secondery_color );?>;
	color: <?php echo esc_html( $secondery_color );?>;
}
.btn-ghost.color-yellow:hover{
	background-color: <?php echo esc_html( $secondery_color );?>;	
}
.schedule-layout1 .schedule-time i{
	color: <?php echo esc_html( $secondery_color );?>;
}

.site-wrp .btn-fill.color-yellow:hover{
	 border-color:<?php echo esc_html( $primary_color );?>;
     background-color: <?php echo esc_html( $primary_color );?>;
     color: #ffffff;
}

.site-wrp .btn-fill.color-yellow.gust:hover{
	 border-color:<?php echo esc_html( $secondery_color );?>;
     background-color: transparent;
     color: <?php echo esc_html( $secondery_color );?>;
}

.site-wrp .rtin-light .btn-fill.color-yellow.gust:hover{
	 border-color:<?php echo esc_html( $primary_color );?>;
     background-color: <?php echo esc_html( $primary_color );?>;
     color: #ffffff;
}


.blog-layout1 .item-img .item-date{
	background-color: <?php echo esc_html( $secondery_color );?>;
}
.site-wrp .btn-text:hover:before{
	color: <?php echo esc_html( $secondery_color );?>;
}
.site-wrp .btn-text:hover{
	color: <?php echo esc_html( $secondery_color );?>;
}
.rt-el-info-box.rtin-style1.rtin-dark .rtin-title a{
	color: <?php echo esc_html( $secondery_color );?>;
}
.footer-layout1 .footer-social ul li a:before,
.footer-layout1 .footer-social ul li a:after {
	background-color: <?php echo esc_html( $secondery_color );?>;
}
.footer-layout1 .footer-social ul li a:hover{
	color: <?php echo esc_html( $secondery_color );?>;
}
.rt-el-cta-1.style2 .rtin-button a{
	color: <?php echo esc_html( $primary_color );?>;
}
.rdtheme-button-1,
.rdtheme-button-ghost-1 {
	background-color: <?php echo esc_html( $primary_color );?>;
}
.rdtheme-button-1:hover {
	background-color: <?php echo esc_html( $secondery_color );?>;
}
.rdtheme-button-ghost-1{
	border-color: <?php echo esc_html( $primary_color );?>;	
}
.rdtheme-button-2:hover {
	border-color: <?php echo esc_html( $primary_color );?>;
	background-color: <?php echo esc_html( $primary_color );?>;
}
.rdtheme-button-ghost-1:hover {
	color: <?php echo esc_html( $primary_color );?>;
}
.rdtheme-button-4 {
	border: 2px solid <?php echo esc_html( $primary_color );?>;	
}
.rdtheme-button-4:hover {	
	background-color: <?php echo esc_html( $primary_color );?>;
}
.rt-el-services-box.rtin-style3:hover {
	-webkit-box-shadow: inset 2px 0px 73px 0px rgba(<?php echo esc_html( $primary_rgb ); ?>, 0.5);
	-moz-box-shadow: inset 2px 0px 73px 0px rgba(<?php echo esc_html( $primary_rgb ); ?>, 0.5);
	box-shadow: inset 2px 0px 73px 0px rgba(<?php echo esc_html( $primary_rgb ); ?>, 0.5);
}
.rdtheme-button-2 {
	border: 2px solid <?php echo esc_html( $primary_color );?>;	
}
.schedule-layout1 .schedule-title a:hover{
	color: <?php echo esc_html( $secondery_color );?>;
}
<?php
/*-------------------------------------
#. Inner Contents
---------------------------------------*/
?>
a {
	color: <?php echo esc_html( $primary_color ); ?>;
}
a:hover,
a:focus,
a:active {
	color: <?php echo esc_html( $secondery_color );?>;
}
button,
input[type="button"],
input[type="reset"],
input[type="submit"],
.primary-list li:before {
	background-color: <?php echo esc_html( $primary_color ); ?>;
}
.post-each .entry-thumbnail-area .entry-meta-1 li i,
.post-each .entry-thumbnail-area .entry-meta-1 li a:hover,
.post-each .entry-content-area .entry-header a.entry-title:hover,
.post-each .entry-content-area .read-more-btn i,
.post-each .entry-content-area .read-more-btn:hover,
.post-each.post-each-single .entry-content-area .entry-tags a:hover {
	color: <?php echo esc_html($secondery_color); ?>;
}
.site-index .sticky {
	border-color: <?php echo esc_html( $primary_color ); ?>;
}
<?php
/*-------------------------------------
#. Comments
------------------------------------*/
?>
.comments-area h3.comment-title:after,
.comments-area .main-comments .comment-meta .reply-area a:hover,
#respond .comment-reply-title:after,
#respond form .btn-send {
	background-color: <?php echo esc_html( $secondery_color ); ?>;
}
#respond form .btn-send:hover {
	background-color: <?php echo esc_html( $primary_color ); ?>;
}

<?php
/*-------------------------------------
#. Error 404
---------------------------------------*/
?>
.error-page-area {
    background-color: <?php echo esc_html( $error_bodybg );?>;
}
.error-page-area .error-page h3 {
	color: <?php echo esc_html( RDTheme::$options['error_text1_color'] );?>;
}
.error-page-area .error-page p {
	color: <?php echo esc_html( RDTheme::$options['error_text2_color'] );?>;
}

<?php
/*-------------------------------------
#. CPT
---------------------------------------*/
?>
.team-single ul.rtin-social li a {
	border-color: <?php echo esc_html( $primary_color ); ?>;
	color: <?php echo esc_html( $primary_color ); ?>;
}
.team-single ul.rtin-social li a:hover,
.team-single .rtin-content .rtin-heading:after,
.team-single .rtin-skills .rtin-skill-each .progress .progress-bar,
.rt-el-cta-1.style2,
.rt-el-cta-1.style1{
	background-color: <?php echo esc_html( $primary_color ); ?>;
}

.rt-project-archive .rt-project-arc-1 .rtin-item .rtin-img:before {
	background-color: rgba(<?php echo esc_html( $primary_rgb ); ?>, 0.8);
}
.rt-project-archive .rt-project-arc-1 .rtin-item .rtin-img .rtin-icon-wrap .rtin-icon,
.rt-project-archive .rt-project-arc-1 .rtin-item .rtin-content .rtin-title a:hover {
	color: <?php echo esc_html( $primary_color ); ?>;
}
<?php
/*-------------------------------------
#. Blog
---------------------------------------*/
?>
.post-each .entry-content-area:hover .entry-meta-2 li.vcard-comments i{
	color: <?php echo esc_html( $secondery_color ); ?>;		
}
.post-each .entry-meta-1 li i,
.post-each .entry-content-area:hover .entry-meta-2 li.vcard-author i{
	color: <?php echo esc_html( $secondery_color ); ?>;		
}
.blog-layout3.thumb-img .item-date-wrap .item-date {
	background-color: <?php echo esc_html( $secondery_color ); ?>;		
}
.blog-layout3.thumb-img .item-date-wrap .item-date:before {
	background-color: <?php echo esc_html( $secondery_color ); ?>;	
}
.site-wrp .btn-text.hover-yellow:hover:before{
	color: <?php echo esc_html( $primary_color ); ?>;
}
.site-wrp .btn-text.hover-yellow:hover{
	color: <?php echo esc_html( $secondery_color ); ?>;
}
.blog-layout3 .item-date-wrap .item-date {	
	background-color: <?php echo esc_html( $secondery_color ); ?>;
}
.blog-layout3 .item-date-wrap .item-date:before {		
	background: rgba(<?php echo esc_html( $secondery_rgb ); ?>, 0.9);
}
.widget_eventalk_posts p.date,
.widget_eventalk_posts h4 a:hover {	
color: <?php echo esc_html( $secondery_color ); ?>;
}
.post-each .entry-content-area .entry-header .entry-meta-2 li a:hover{
	color: <?php echo esc_html( $primary_color ); ?>;
}
.blog-layout2 .item-img .item-date {
	background-color: <?php echo esc_html( $secondery_color ); ?>;
}
.title-light.hover-primary a:hover,
.title-regular.hover-primary a:hover,
.title-medium.hover-primary a:hover, 
.title-semibold.hover-primary a:hover,
.title-bold.hover-primary a:hover,
.title-black.hover-primary a:hover {
	color: <?php echo esc_html( $secondery_color ); ?>;
}
<?php
/*-------------------------------------
#. WooCommerce
---------------------------------------*/
?>
.woocommerce .widget_price_filter .ui-slider .ui-slider-range,
.woocommerce .widget_price_filter .ui-slider .ui-slider-handle,
.rt-woo-nav .owl-custom-nav-title:after,
.rt-woo-nav .owl-custom-nav .owl-prev:hover,
.rt-woo-nav .owl-custom-nav .owl-next:hover,
.woocommerce ul.products li.product .onsale,
.woocommerce span.onsale,
.woocommerce a.added_to_cart,
.woocommerce div.product form.cart .button,
.woocommerce #respond input#submit,
.woocommerce a.button,
.woocommerce button.button,
.woocommerce input.button,
p.demo_store,
.woocommerce #respond input#submit.disabled:hover, .woocommerce #respond input#submit:disabled:hover, .woocommerce #respond input#submit[disabled]:disabled:hover, .woocommerce a.button.disabled:hover, .woocommerce a.button:disabled:hover, .woocommerce a.button[disabled]:disabled:hover, .woocommerce button.button.disabled:hover, .woocommerce button.button:disabled:hover, .woocommerce button.button[disabled]:disabled:hover, .woocommerce input.button.disabled:hover, .woocommerce input.button:disabled:hover, .woocommerce input.button[disabled]:disabled:hover,
.woocommerce #respond input#submit.alt,
.woocommerce a.button.alt,
.woocommerce button.button.alt,
.woocommerce input.button.alt {
	background-color: <?php echo esc_html( $primary_color ); ?>;
}

.product-grid-view .view-mode ul li.grid-view-nav a,
.product-list-view .view-mode ul li.list-view-nav a,
.woocommerce ul.products li.product h3 a:hover,
.woocommerce div.product p.price,
.woocommerce div.product span.price,
.woocommerce div.product .product-meta a:hover,
.woocommerce div.product .product_meta a:hover,
.woocommerce div.product .woocommerce-tabs ul.tabs li.active a,
.woocommerce a.woocommerce-review-link:hover,
.woocommerce-message:before,
.woocommerce-info::before {
	color: <?php echo esc_html( $primary_color ); ?>;
}

.woocommerce-message,
.woocommerce-info {
	border-color: <?php echo esc_html( $primary_color ); ?>;
}

.woocommerce .product-thumb-area .product-info ul li a:hover {
	color: <?php echo esc_html( $primary_color ); ?>;
}