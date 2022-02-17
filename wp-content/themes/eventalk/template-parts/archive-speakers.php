<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Eventalk;

// Layout class
if ( RDTheme::$layout == 'full-width' ) {
	$layout_class = 'col-sm-12 col-xs-12';
	$col_class    = 'col-lg-4 col-md-6 col-sm-6 col-xs-12';
}
else{
	$layout_class = 'col-sm-8 col-md-8 col-xs-12';
	$col_class    = 'col-lg-6 col-md-6 col-sm-12 col-xs-12';
}

// Template
$template_bg_sty='';
$gutters='';
$container_class='container';
switch ( RDTheme::$options['speakers_arc_style'] ) {
	case 'style2':
		$template = 'speakers-2';
		$gutters = 'no-gutters';
		$container_class='container-fluid';
		$col_class    = 'col-xl-2 col-lg-3 col-md-6 col-sm-6 col-xs-6 col-6';
		break;
	case 'style3':
		$template = 'speakers-3';
		break;	
	default:
		$template = 'speakers-1';
		$template_bg_sty = 'overlay-icon-layout3 bg-common bg-primary';	

		break;
}
?>
<?php get_header(); ?>
<div id="primary" class="content-area <?php echo esc_attr( $template_bg_sty );?>">
	<div class="<?php echo esc_attr( $container_class );?> <?php echo esc_attr( $gutters );?>">
		<div class="row <?php echo esc_attr( $gutters );?>">
			<?php
			if ( RDTheme::$layout == 'left-sidebar' ) {
				get_sidebar();
			}
			?>
			<div class="<?php echo esc_attr( $layout_class );?>">
				<main id="main" class="site-main rt-speakers-archive">
					<?php if ( have_posts() ) :?>
						<div class="row auto-clear <?php echo esc_attr( $gutters );?>">
							<?php while ( have_posts() ) : the_post(); ?>
								<div class="<?php echo esc_attr( $col_class );?>">
									<?php get_template_part( 'template-parts/content', $template ); ?>
								</div>
							<?php endwhile; ?>
						</div>
						<?php Helper::pagination();?>
					<?php else:?>
						<?php get_template_part( 'template-parts/content', 'none' );?>
					<?php endif;?>
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