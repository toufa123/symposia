<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Eventalk;

// Layout class
if ( RDTheme::$layout == 'full-width' ) {
	$rdtheme_layout_class = 'col-sm-12 col-xs-12';
}
else{
	$rdtheme_layout_class = 'col-md-9 col-xs-12';
}
// Template
switch ( RDTheme::$options['single_arc_style'] ) {
	case 'style2':
	$template = 'single-event-2';
	break;
	case 'style3':
	$template = 'single-event-3';
	break;	
	case 'style4':
	$template = 'single-event-4';
	break;	
	case 'style5':
	$template = 'single-event-5';
	break;	
	case 'style6':
	$template = 'single-event-6';
	break;	
	case 'style7':
	$template = 'single-event-7';
	break;	
	default:
	$template = 'single-event-1';
	break;
}
?>
<?php get_header(); ?>
<div id="primary" class="content-area">
	<div class="container">
		<div class="row">
			<?php
			if ( RDTheme::$layout == 'left-sidebar' ) {
				get_sidebar();
			}
			?>
			<div class="<?php echo esc_attr( $rdtheme_layout_class );?>">
				<main id="main" class="site-main">
					<?php while ( have_posts() ) : the_post(); ?>
						<?php
						 get_template_part( 'template-parts/content', $template );
						if ( comments_open() || get_comments_number() ){
							comments_template();
						}
						?>
					<?php endwhile; ?>
				</main>					
			</div>
			<?php
			if ( RDTheme::$layout == 'right-sidebar' ) {
				get_sidebar();
			}
			?>
		</div>
	</div>
</div>
<?php get_footer(); ?>