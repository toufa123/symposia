<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

use radiustheme\Eventalk\RDTheme;

/*-------------------------------------
#. Theme supports for WooCommerce
---------------------------------------*/
function rdtheme_wc_support() {
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-lightbox' );
}

/*-------------------------------------
#. Replace WooCommerce Default functions
---------------------------------------*/

// Short description - Use excerpt when description doesn't exist
if ( ! function_exists( 'woocommerce_template_single_excerpt' ) ) {
	function woocommerce_template_single_excerpt() {
		global $post;
		if ( ! $post->post_excerpt && !RDTheme::$options['wc_show_excerpt'] ) {
			return false;
		}

		echo '<div class="short-description">';
		if ( ! $post->post_excerpt ) {
			the_excerpt();
		}
		else {
			wc_get_template( 'single-product/short-description.php' );
		}
		echo '</div>';
	}
}

/*-------------------------------------
#. Custom functions used directly
---------------------------------------*/
function rdtheme_wc_get_template_parts( $template ){
	get_template_part( 'woocommerce/custom/template-parts/content', $template );
}

function rdtheme_wc_product_slider( $products, $title, $type='' ) {
	$filename = '/woocommerce/custom/template-parts/content-product-slider.php';

	$child_file  = get_stylesheet_directory() . $filename;
	$parent_file = get_template_directory() . $filename;

	if ( file_exists( $child_file ) ) {
		$file = $child_file;
	}
	else {
		$file = $parent_file;
	}

	include $file;
}

function rdtheme_wc_add_to_cart_icon(){
	global $product;
	$quantity = 1;
	$class = implode( ' ', array_filter( array(
		'fa fa-shopping-cart button',
		'product_type_' . $product->get_type(),
		$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
		$product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : '',
		) ) );

	echo sprintf( '<a rel="nofollow" href="%s" data-quantity="%s" data-product_id="%s" data-product_sku="%s" class="%s"></a>',
		esc_url( $product->add_to_cart_url() ),
		esc_attr( isset( $quantity ) ? $quantity : 1 ),
		esc_attr( $product->get_id() ),
		esc_attr( $product->get_sku() ),
		esc_attr( isset( $class ) ? $class : 'fa fa-shopping-cart' )
	);
}

/*-------------------------------------
#. Custom functions used in hooks
---------------------------------------*/
function rdtheme_header_cart_count( $fragments ) {
	global $woocommerce;
	ob_start();?>
	<span class="cart-icon-num"><?php echo WC()->cart->get_cart_contents_count();?></span>
	<?php
	$fragments['span.cart-icon-num'] = ob_get_clean();
	return $fragments;
}

function rdtheme_smallscreen_breakpoint(){
	return '767px';
}

function rdtheme_wc_hide_page_title(){
	return false;
}

function rdtheme_wc_loop_shop_per_page(){
	return RDTheme::$options['wc_num_product'];
}

function rdtheme_wc_wrapper_start() {
	rdtheme_wc_get_template_parts( 'shop-header' );
}

function rdtheme_wc_wrapper_end() {
	rdtheme_wc_get_template_parts( 'shop-footer' );
}

function rdtheme_wc_shop_topbar() {
	rdtheme_wc_get_template_parts( 'shop-top' );
}

function rdtheme_wc_loop_product_title(){
	echo '<h3><a href="' . get_the_permalink() . '" class="woocommerce-LoopProduct-link">' . get_the_title() . '</a></h3>';
}

function rdtheme_wc_loop_shop_columns(){
	if ( RDTheme::$layout == 'full-width' ) {
		return 4;
	}
	return 3;
}

function rdtheme_wc_shop_thumb_area(){
	rdtheme_wc_get_template_parts( 'shop-thumb' );
}

function rdtheme_wc_shop_info_wrap_start(){
	echo '<div class="product-info-area">';
}

function rdtheme_wc_shop_add_description(){
	if ( is_shop() || is_product_category() || is_product_tag() ) {
		global $post;
		echo '<div class="shop-excerpt grid-hide"><div class="short-description">';
		the_excerpt();
		echo '</div></div>';	
	}
}

function rdtheme_wc_shop_info_wrap_end(){
	echo '</div><div class="clear"></div>';
}

function rdtheme_wc_render_sku(){
	rdtheme_wc_get_template_parts( 'product-sku' );
}

function rdtheme_wc_render_meta(){
	rdtheme_wc_get_template_parts( 'product-meta' );
}

function rdtheme_wc_show_or_hide_related_products(){
	// Show or hide related products
	if ( empty( RDTheme::$options['wc_related'] ) ) {
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
	}
}

function rdtheme_wc_hide_product_data_tab( $tabs ){
	if ( empty( RDTheme::$options['wc_description'] ) ) {
		unset( $tabs['description'] );
	}
	if ( empty( RDTheme::$options['wc_reviews'] ) ) {
		unset( $tabs['reviews'] );
	}
	if ( empty( RDTheme::$options['wc_additional_info'] ) ) {
		unset( $tabs['additional_information'] );
	}
	return $tabs;
}

function rdtheme_wc_product_review_form( $comment_form ){
	$commenter = wp_get_current_commenter();

	$comment_form['fields'] = array(
		'author' => '<div class="row"><div class="col-sm-6"><div class="comment-form-author form-group"><input id="author" name="author" type="text" class="form-control" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" placeholder="' . esc_attr__( 'Name *', 'eventalk' ) . '" required /></div></div>',
		'email'  => '<div class="comment-form-email col-sm-6"><div class="form-group"><input id="email" class="form-control" name="email" type="email" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" placeholder="' . esc_attr__( 'Email *', 'eventalk' ) . '" required /></div></div></div>',
	);

	$comment_form['comment_field'] = '';

	if ( get_option( 'woocommerce_enable_review_rating' ) === 'yes' ) {
		$comment_form['comment_field'] = '<p class="comment-form-rating"><label for="rating">' . esc_html__( 'Your Rating', 'eventalk' ) .'</label>
		<select name="rating" id="rating" required>
			<option value="">' . esc_html__( 'Rate&hellip;', 'eventalk' ) . '</option>
			<option value="5">' . esc_html__( 'Perfect', 'eventalk' ) . '</option>
			<option value="4">' . esc_html__( 'Good', 'eventalk' ) . '</option>
			<option value="3">' . esc_html__( 'Average', 'eventalk' ) . '</option>
			<option value="2">' . esc_html__( 'Not that bad', 'eventalk' ) . '</option>
			<option value="1">' . esc_html__( 'Very Poor', 'eventalk' ) . '</option>
			</select></p>';
		}

		$comment_form['comment_field'] .= '<div class="col-sm-12 p0"><div class="form-group comment-form-comment"><textarea id="comment" name="comment" class="form-control" placeholder="' . esc_attr__( 'Your Review *', 'eventalk' ) . '" cols="45" rows="8" required></textarea></div></div>';

		return $comment_form;
	}

	function rdtheme_wc_show_or_hide_cross_sells(){
	// Show or hide related cross sells
		if ( !empty( RDTheme::$options['wc_cross_sell'] ) ) {
			add_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display', 10 );
		}
	}