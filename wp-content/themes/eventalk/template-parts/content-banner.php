<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Eventalk;

$prefix = THEME_PREFIX_VAR;

if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
	$rdtheme_title = woocommerce_page_title( false );
}
elseif ( is_404() ) {
	$rdtheme_title = RDTheme::$options['error_title'];
}
elseif ( is_search() ) {
	$rdtheme_title = esc_html__( 'Search Results for : ', 'eventalk' ) . get_search_query();
}
elseif ( is_home() ) {
	if ( get_option( 'page_for_posts' ) ) {
		$rdtheme_title = get_the_title( get_option( 'page_for_posts' ) );
	}
	else {
		$rdtheme_title = apply_filters( "{$prefix}_blog_title", esc_html__( 'All Posts', 'eventalk' ) );
	}
}
elseif ( is_archive() ) {
	$cpt = THEME_CPT_PREFIX;
	if ( is_post_type_archive( "{$cpt}_speaker" ) ) {
		$rdtheme_title = esc_html__( 'All Speakers', 'eventalk' );
	}
	elseif ( is_post_type_archive( "{$cpt}_event" ) ) {
		$rdtheme_title = esc_html__( 'All Events', 'eventalk' );
	}
	else {
		$rdtheme_title = get_the_archive_title();
	}
}elseif (is_single()) {
	$rdtheme_title  = get_the_title();

}else{
	$rdtheme_title = get_the_title();
}
?>
<?php if ( RDTheme::$has_banner == '1' || RDTheme::$has_banner == 'on' ): ?>
	<div class="entry-banner">
		<?php if( function_exists( 'bcn_display') ){ 
				 echo '<div class="inner-page-banner">';
			 } else{
			 	echo '<div class="inner-page-banner breadcrumbs-off">';
			 } ?>	
		<div class="container">
			<div class="entry-banner-content breadcrumbs-area">
				<h1 class="entry-title"><?php echo wp_kses_post( $rdtheme_title );?></h1>
				<?php if ( RDTheme::$has_breadcrumb == '1' || RDTheme::$has_breadcrumb == 'on' ): ?>
					<?php get_template_part( 'template-parts/content', 'breadcrumb' );?>
				<?php endif; ?>
			</div>
		</div>
	</div>
	</div>
<?php endif; ?>

