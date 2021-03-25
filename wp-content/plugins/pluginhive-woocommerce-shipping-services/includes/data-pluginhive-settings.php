<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;	// exit if directly accessed
}
if (is_admin() && !empty($_GET['section']) && $_GET['section'] == 'pluginhive_woocommerce_shipping') {
	?>
	<script>


		window.addEventListener('load', function() {

			// Hide 'Active' option
			jQuery('#woocommerce_pluginhive_woocommerce_shipping_enabled').closest('tr').hide();

			function hideSaveButton() {
				if (jQuery('p.submit button.woocommerce-save-button').prop('type') == 'submit') {
					jQuery('p.submit button.woocommerce-save-button').hide();
				}
			}

			// To create button to get the account details
			var ajaxUrl = "<?php echo admin_url('admin-ajax.php'); ?>";
			console.log('ajaxUrl: ', ajaxUrl);
			var storeData = JSON.parse('<?php echo json_encode(get_option(SP_PLUGIN_ID, true)); ?>');
			console.log('storeData: ', storeData);

			if (!storeData || (!storeData.asp_account_id || !storeData.integration_id || !storeData.secret_key)) {
				console.log('if: ');
				// Hide Setup
				jQuery('#woocommerce_pluginhive_woocommerce_shipping_other_settings').closest('h3').hide();
				jQuery('#woocommerce_pluginhive_woocommerce_shipping_enabled_rates').closest('tr').hide();
				jQuery('#woocommerce_pluginhive_woocommerce_shipping_debug').closest('tr').hide();
				jQuery('#woocommerce_pluginhive_woocommerce_shipping_fallback_rate').closest('tr').hide();
				jQuery('#woocommerce_pluginhive_woocommerce_shipping_tax_calculation_mode').closest('tr').hide();

				jQuery('#woocommerce_pluginhive_woocommerce_shipping_setup_button').removeClass().addClass('button-primary woocommerce-save-button').val('Register');
				hideSaveButton();
			} else if (storeData && !storeData.store_uuid) {
				console.log('elseif: ');

				jQuery('#woocommerce_pluginhive_woocommerce_shipping_setup_button').attr('id', 'woocommerce_pluginhive_woocommerce_shipping_sync_store_button')
				jQuery('#woocommerce_pluginhive_woocommerce_shipping_sync_store_button').removeClass().addClass('button-primary woocommerce-save-button').val('Sync Store');
				jQuery('#woocommerce_pluginhive_woocommerce_shipping_sync_store_button').show();
				hideSaveButton();
			} else {
				console.log('else: ');

				jQuery('#woocommerce_pluginhive_woocommerce_shipping_setup_button').hide();
			}


			if (storeData && storeData.synced_store && storeData.synced_store !== "<?php echo get_site_url(); ?>") {
				if (!jQuery("#mc_shipping_labels_store_sync_message").length) {
					var message = "<tr><td colspan=2 id='mc_shipping_labels_store_sync_message'><span style='color: red;'> We see that you have changed your store after setting up the app. Please contact <a href='https://www.pluginhive.com'>PluginHive.com</a> to continue the services.<br/><br/> Last Synced store was <b>" + storeData.synced_store + "</b></span></td></tr>";
					jQuery('#woocommerce_pluginhive_woocommerce_shipping_fallback_rate').closest('tr').after(message);
					hideSaveButton();
				}
			}

			if (storeData && storeData.asp_account_id && storeData.integration_id && storeData.secret_key && storeData.store_uuid) {
				console.log("inside everything completed if")
				console.log('storeData.integration_id: ', storeData.integration_id);

				var spNavLink = "<?php echo admin_url('admin.php?page=woocommerce_pluginhive_settings'); ?>"
				console.log('spNavLink: ', spNavLink);
				if (!jQuery("#mc_shipping_labels_account_complete_message").length) {
					// Hide consumer credentials and display message
					console.log('Hide consumer: ');
					// var message = "<div style='padding-bottom:10px' id='mc_shipping_labels_account_complete_message'><span style='color: green;'>Congratulations! You have successfully integrated your store with WooCommerce Shipping Services. <a class='button-primary woocommerce-save-button' href='" + spNavLink + "'>Let's start Fulfilling</a></span></div>";
					// jQuery('#woocommerce_pluginhive_woocommerce_shipping_store_settings').closest('h3').after(message);
					var message = "<div style='padding-bottom:10px' id='mc_shipping_labels_account_complete_message'><span style='color: green;'>Congratulations! You have successfully integrated your store with WooCommerce Shipping Services. <br/><br/><a class='button-primary woocommerce-save-button' href='" + spNavLink + "'>Let's start Fulfilling</a></span> &nbsp; <input type='button' id='woocommerce_pluginhive_woocommerce_shipping_resync_button' class='button button-primary' value='Resync'></div>";
					jQuery('#woocommerce_pluginhive_woocommerce_shipping_store_settings').closest('h3').after(message);
					//jQuery('#woocommerce_pluginhive_woocommerce_shipping_consumer_key').closest('tr').hide();
					//jQuery('#woocommerce_pluginhive_woocommerce_shipping_consumer_secret').closest('tr').hide();
				}
			}

			jQuery('#woocommerce_pluginhive_woocommerce_shipping_setup_button').unbind('click');
			jQuery('#woocommerce_pluginhive_woocommerce_shipping_setup_button').on('click', function() {
				var data = {};
				data.enabled = jQuery("#woocommerce_pluginhive_woocommerce_shipping_enabled").val();
				data.enabled_rates = jQuery("#woocommerce_pluginhive_woocommerce_shipping_enabled_rates").val();
				//data.consumer_key = jQuery("#woocommerce_pluginhive_woocommerce_shipping_consumer_key").val();
				//data.consumer_secret = jQuery("#woocommerce_pluginhive_woocommerce_shipping_consumer_secret").val();

				// if (!data.consumer_key) {
				// 	return alert('consumer key needed.');
				// }
				// if (!data.consumer_secret) {
				// 	return alert('consumer secret needed.');
				// }
				console.log('data: ', data);

				var actions = {
					action: 'sp_configure_account',
					data: data,
				};

				jQuery(this).prop("disabled", true);
				jQuery.post(ajaxUrl, actions)
					.done(function(response) {
						console.log('response: ', response);
						var parsedResponse = JSON.parse(response);
						if (!parsedResponse.success) {
							alert(parsedResponse.message);
						} else if (parsedResponse.success) {
							window.location.reload();
						}
						jQuery('#woocommerce_pluginhive_woocommerce_shipping_setup_button').prop("disabled", false);
					})
					.fail(function(result) {
						console.log('result:configure ', result);
						alert('No response from Server. Something Went wrong');
						jQuery('#woocommerce_pluginhive_woocommerce_shipping_setup_button').prop("disabled", false);
					});
			});

			jQuery('#woocommerce_pluginhive_woocommerce_shipping_resync_button').unbind('click');
			jQuery('#woocommerce_pluginhive_woocommerce_shipping_resync_button').on('click', function() {
				var data = {};
				// data.enabled = jQuery("#woocommerce_pluginhive_woocommerce_shipping_enabled").val();
				// data.enabled_rates = jQuery("#woocommerce_pluginhive_woocommerce_shipping_enabled_rates").val();
				//data.consumer_key = jQuery("#woocommerce_pluginhive_woocommerce_shipping_consumer_key").val();
				//data.consumer_secret = jQuery("#woocommerce_pluginhive_woocommerce_shipping_consumer_secret").val();

				// if (!data.consumer_key) {
				// 	return alert('consumer key needed.');
				// }
				// if (!data.consumer_secret) {
				// 	return alert('consumer secret needed.');
				// }
				// console.log('data: ', data);
				var confirmValue = confirm("This will resync the Store URL and the Email ID. would you like to continue?");
				if(confirmValue == true){

				var actions = {
					action: 'sp_resync_account',
					data: data,
				};

				jQuery(this).prop("disabled", true);
				jQuery.post(ajaxUrl, actions)
					.done(function(response) {
						console.log('response: ', response);
						var parsedResponse = JSON.parse(response);
						if (!parsedResponse.success) {
							alert(parsedResponse.message);
						} else if (parsedResponse.success) {
							// window.location.reload();
							alert(parsedResponse.message);
						}
						jQuery('#woocommerce_pluginhive_woocommerce_shipping_resync_button').prop("disabled", false);
					})
					.fail(function(result) {
						console.log('result:configure ', result);
						alert('No response from Server. Something Went wrong');
						jQuery('#woocommerce_pluginhive_woocommerce_shipping_setup_button').prop("disabled", false);
					});
				}
			});

			jQuery('#woocommerce_pluginhive_woocommerce_shipping_sync_store_button').unbind('click');
			jQuery('#woocommerce_pluginhive_woocommerce_shipping_sync_store_button').on('click', function() {
				var data = {};
				//data.consumer_key = jQuery("#woocommerce_pluginhive_woocommerce_shipping_consumer_key").val();
				//data.consumer_secret = jQuery("#woocommerce_pluginhive_woocommerce_shipping_consumer_secret").val();

				// if (!data.consumer_key) {
				// 	return alert('consumer key needed.');
				// }
				// if (!data.consumer_secret) {
				// 	return alert('consumer secret needed.');
				// }

				var actions = {
					action: 'sp_sync_store',
					data: data,
				};

				jQuery(this).prop("disabled", true);
				jQuery.post(ajaxUrl, actions)
					.done(function(response) {
						var parsedResponse = JSON.parse(response);
						alert(parsedResponse.message);
						jQuery('#woocommerce_pluginhive_woocommerce_shipping_sync_store_button').prop("disabled", false);
					})
					.fail(function(result) {
						console.log('result:sync ', result);
						alert('No response from Server. Something Went wrong');
						jQuery('#woocommerce_pluginhive_woocommerce_shipping_sync_store_button').prop("disabled", false);
					});
			});

		});
	</script>

	<style>
		/* Style for Signup AddonStation.com & Get API Keys button */
	</style>

<?php
}

// if( is_admin() && ! empty($_GET['section']) && $_GET['section'] == 'pluginhive_woocommerce_shipping' ) {
// 	wp_enqueue_script( 'sp-admin-script', plugins_url( '/resources/js/sp-admin-settings.js', __FILE__ ), array( 'jquery' ) );
// }




$logged_in_user_email_id = null;
if( is_admin() && ! empty($_GET['section']) && $_GET['section'] == 'pluginhive_woocommerce_shipping' ) {
	$logged_in_user_email_id = Pluginhive_Shipping_Rates_Common::get_current_user_email_id();
}

$urlToCreateKeys = admin_url('admin.php?page=wc-settings&tab=advanced&section=keys&create-key=1');

// Settings
return array(
	'store_settings'			=> array(
		'title'		   	=> __('Store Setup', 'pluginhive-woocommerce-shipping-services'),
		'type'			=> 'title',
		//'description'	=> __('Click on register to create your account')
	),
	'enabled'			=> array(
		'title'		   	=> __('Active', 'pluginhive-woocommerce-shipping-services'),
		'type'			=> 'checkbox',
		'label'			=> __('Enable', 'pluginhive-woocommerce-shipping-services'),
		'default'		=> 'yes',
		'class'			=> 'disable',
	),
	// 'consumer_key'	=> array(
	// 	'title'			=> __('WooCommerce Consumer Key', 'woocommerce-shipping-rates-labels-and-tracking'),
	// 	'type'			=> 'text',
	// 	'description'	=> __("Required for AddonStation Account Authentication. Get it from ", 'woocommerce-shipping-rates-labels-and-tracking') . '<a href="' . $urlToCreateKeys . '" target="_blank">' . __('Here', 'woocommerce-shipping-rates-labels-and-tracking') . '</a>',
	// 	// 'desc_tip'		=> true,
	// ),
	// 'consumer_secret'		=> array(
	// 	'title'			=> __('WooCommerce Consumer Secret', 'woocommerce-shipping-rates-labels-and-tracking'),
	// 	'type'			=> 'text',
	// 	'description'	=> __("Required for AddonStation Account Authentication. Get it from ", 'woocommerce-shipping-rates-labels-and-tracking') . '<a href="' . $urlToCreateKeys . '" target="_blank">' . __('Here', 'woocommerce-shipping-rates-labels-and-tracking') . '</a>',
	// 	// 'desc_tip'		=> true,
	// ),
	'other_settings'			=> array(
		'title'		   	=> __('Settings', 'pluginhive-woocommerce-shipping-services'),
		'type'			=> 'title',
	),
	'enabled_rates'			=> array(
		'title'		   	=> __('Realtime Rates', 'pluginhive-woocommerce-shipping-services'),
		'type'			=> 'checkbox',
		'label'			=> __('Enable', 'pluginhive-woocommerce-shipping-services'),
		'default'		=> 'yes',
	),
	'debug'		=> array(
		'title'		   	=> __('Debug Mode', 'pluginhive-woocommerce-shipping-services'),
		'type'			=> 'checkbox',
		'label'			=> __('Enable', 'pluginhive-woocommerce-shipping-services'),
		'description'	=> __('Enable debug mode to show debugging information on your cart/checkout.', 'pluginhive-woocommerce-shipping-services'),
		'desc_tip'		=>	true,
		'default'		=> 'no',
	),
	'tax_calculation_mode'		=> array(
		'title'		   	=> __('Tax Calculation', 'pluginhive-woocommerce-shipping-services'),
		'type'			=> 'select',
		'description'	=> __('Select Tax Calculation for shipping rates as your requirement.', 'pluginhive-woocommerce-shipping-services'),
		'desc_tip'		=>	true,
		'default'		=> null,
		'options'	 => array(
			'per_order' 	=> __('Taxable', 'pluginhive-woocommerce-shipping-services'),
			null			=> __('None', 'pluginhive-woocommerce-shipping-services'),
		),
	),
	'fallback_rate'		=> array(
		'title'			=> __('Fallback Rate', 'pluginhive-woocommerce-shipping-services'),
		'type'			=> 'text',
		'default'		=> '10',
		'description'	=> __("If no rate returned by PluginHive account then this fallback rate will be displayed. Shipping Method Title will be used as Service name.", 'woocommerce-shipping-rates-labels-and-tracking'),
		'desc_tip'		=> true,
	),
	'setup_button'		=> array(
		// 'title'			=> __('Start Setup', 'woocommerce-shipping-rates-labels-and-tracking'),
		'type'			=> 'button',
	),
);

