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
						get_template_part( 'template-parts/content-single', get_post_format() );
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