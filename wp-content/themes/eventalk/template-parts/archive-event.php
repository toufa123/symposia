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
	$col_class    = 'col-12';
}
else{
	$layout_class = 'col-sm-8 col-md-8 col-xs-12';
	$col_class    = 'col-lg-12';
}

// Template
$template_bg_sty='';
$gutters='';
$container_class='container';
$template = 'event-1';

?>
<?php get_header(); ?>
<div id="primary" class="content-area <?php echo esc_attr( $template_bg_sty );?>">
	<div class="<?php echo esc_attr( $container_class );?>">
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