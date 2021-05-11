<?php

/*
* Template Name: Register complain
* Template Post Type: post, page
*/

get_header();

$order_id = $_GET['order'];
$order = wc_get_order( $order_id );
ep_register_complain1($order);

get_footer();

function ep_register_complain1($order) {
    //previous code to update meta data
    //$result = update_post_meta( $order->get_id(), 'insurance_complain', 'true', 'false');

    if (!empty($order)) {
        $order->update_status( 'reclamo' );
        ep_complain_message($order);
    }else{
        echo "Error al procesar";
    }
 }

function ep_complain_message($order){

    $name = ucwords(get_post_meta( $order->get_id() , '_billing_first_name', true));

    echo "<b> Estimad@ " . $name . ", el reclamo de su orden #" . $order->get_id() . " se ha registrado exitosamente.";
    echo "</b> </br> Nuestro personal se contactará con usted en la brevedad posible.";

}


?>
