<?php
$url = get_site_url();
$token = get_option('woocommerce_pluginhive_settings', true);
$vendor_id = get_current_user_id();
// error_log($vendor_id);
// foreach($token as $val){
// error_log($val);
// }
// error_log($url);
if (!empty($token) && isset($token['enabled']) && ($token['enabled'] === 'yes') && isset($token['integration_id']) && !empty($token['integration_id'])) {
    $integration_id = $token['integration_id'];
    // error_log($integration_id);
    $request_body = array(
        'integrationId' => $integration_id,
        'storeVendorId' => $vendor_id,
        'storeUrl' => $url,
        'type' => 'WCFM'
    );

    $response = wp_remote_post(
        PLUGINHIVE_WC_VENDOR_TOKEN_URL,
        array(
            'headers'    =>    array(
                'authorization'    =>    "Bearer " . $integration_id,
                'Content-Type'    =>    "application/json",
            ),
            'timeout'    =>    20,
            'body'        =>    json_encode($request_body),
        )
    );
    // error_log(print_r($response['response'], true));
    if (is_wp_error($response)) {
        $error_string = $response->get_error_message();
        // self::debug('StorePep Response: <a href="#" class="debug_reveal">Reveal</a><pre class="debug_info" style="background:#EEE;border:1px solid #DDD;padding:5px;">' . __('WP Error : ') . print_r($error_string, true) . '</pre>');
        // error_log($error_string);
        return false;
    }
    // error_log(PLUGINHIVE_WC_MV_UI_URL);
    // Successful response
    // error_log($response['body']);
    if ($response['response']['code'] == '200') {
        $body = $response['body'];
        $body = json_decode($body);
        if (isset($body->token)) {
            echo "<div id='app'></div>
            <script src=" . PLUGINHIVE_WC_MV_UI_URL . " token=" . $body->token . " mv_mode='wcfm'> 
            </script>";
        }
    } else {
        // error_log('Oops');
        // self::debug('StorePep Response: <a href="#" class="debug_reveal">Reveal</a><pre class="debug_info" style="background:#EEE;border:1px solid #DDD;padding:5px;">' . __('Error Code : ') . print_r($response['response']['code'], true) . '<br/>' . __('Error Message : ') . print_r($response['response']['message'], true) . '</pre>');
        echo "<div><h3>!!Oops Unable to fetch orders... please retry after some time.</h3></div>";
    }
} else {
    echo "<div><h3>!!Oops PluginHive Settings Not Active... please contact your Admin.</h3></div>";
}
