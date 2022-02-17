<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Eventalk;
?>
<div class="col-md-3 col-xs-12">
	<aside class="sidebar-widget-area">
		<?php
		if ( RDTheme::$sidebar ) {
			if ( is_active_sidebar( RDTheme::$sidebar ) ){
				dynamic_sidebar( RDTheme::$sidebar );
			}
		}
		else {
			if ( is_active_sidebar( 'sidebar' ) ){
				dynamic_sidebar( 'sidebar' );
			}
		}
		?>
	</aside>
</div>
