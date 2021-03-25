<?php
/**
 * Cross-sells
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cross-sells.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     4.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$classes = array('products', 'col_wrap_two');
if (rehub_option('woo_design') == 'grid') {
	$classes[] = 'rh-flex-eq-height grid_woo';
}
elseif (rehub_option('woo_design') == 'gridtwo') {
	$classes[] = 'eq_grid post_eq_grid rh-flex-eq-height';
}
elseif (rehub_option('woo_design') == 'gridrev') {
	$classes[] = 'rh-flex-eq-height woogridrev';
}
else {
	$classes[] = 'rh-flex-eq-height column_woo';
}
if (rehub_option('woo_design') == 'deallist') {
	$classes[] = 'woo_offer_list';
}
if ( $cross_sells ) : ?>
	<div class="cross-sells">
		<?php $heading = apply_filters( 'woocommerce_product_cross_sells_products_heading', esc_html__( 'You may be interested in&hellip;', 'rehub-theme' ) );
		if ( $heading ) :
			?>
			<h2><?php echo ''.$heading; ?></h2>
		<?php endif; ?>
		<div class="<?php echo implode(' ',$classes);?>">
			<?php foreach ( $cross_sells as $cross_sell ) : ?>
				<?php
				 	$post_object = get_post( $cross_sell->get_id() );
					setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found
					if (rehub_option('woo_design') == 'list' || rehub_option('woo_design') == 'deallist'){
					    include(rh_locate_template('inc/parts/woocolumnpart.php'));
					}else{
						wc_get_template_part( 'content', 'product' );
					} 
				?>
			<?php endforeach; ?>
		</div>
	</div>
<?php endif;
wp_reset_postdata();