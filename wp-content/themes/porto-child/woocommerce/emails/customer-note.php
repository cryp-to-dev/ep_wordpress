<?php
/**
 * Add a custom message for (seguro de envio) to the emails
 */
add_action( 'woocommerce_email_after_order_table', 'custom_woocommerce_shipping_insurance', 10, 2 );

function custom_woocommerce_shipping_insurance( $order, $sent_to_admin) {
    if (get_post_meta( $order->id, 'seguro_envio', true ) == '1' ):
       echo 'Por favor revisar el estado de rastreo del pedido, las solicitudes del seguro de envío se atenderán antes de que el paquete llegue al destinatario. En caso de tener un reclamo seguir este enlace <a href="http://eplaza.ec/insurance/complain.php?order=' . $order->id . '">reclamo</a>';

    endif;
}
/**
 * Customer note email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-note.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 3.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<?php /* translators: %s: Customer first name */ ?>
<p><?php printf( esc_html__( 'Hi %s,', 'woocommerce' ), esc_html( $order->get_billing_first_name() ) ); ?></p>
<p><?php esc_html_e( 'The following note has been added to your order:', 'woocommerce' ); ?></p>

<blockquote><?php echo wpautop( wptexturize( make_clickable( $customer_note ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></blockquote>

<p><?php esc_html_e( 'As a reminder, here are your order details:', 'woocommerce' ); ?></p>

<?php

/*
 * @hooked WC_Emails::order_details() Shows the order details table.
 * @hooked WC_Structured_Data::generate_order_data() Generates structured data.
 * @hooked WC_Structured_Data::output_structured_data() Outputs structured data.
 * @since 2.5.0
 */
do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

do_action(  'custom_woocommerce_shipping_insurance', $order, $sent_to_admin );

/*
 * @hooked WC_Emails::order_meta() Shows order meta data.
 */
do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

/*
 * @hooked WC_Emails::customer_details() Shows customer details
 * @hooked WC_Emails::email_address() Shows email address
 */
do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

/*
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action( 'woocommerce_email_footer', $email );


