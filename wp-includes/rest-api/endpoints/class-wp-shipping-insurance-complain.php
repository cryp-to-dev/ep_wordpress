<?php


function shipping_insurance_complain_listener($data){
    $orders = get_posts( array(
        'order' => $data['order'],    
    ) );
    
    if ( empty($orders)) {
        return null;
    }
    
    return $orders;
}


?>