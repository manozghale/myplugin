<?php
// Include Stripe PHP SDK
// require_once('path/to/stripe-php/init.php');

// Include Stripe PHP SDK via Composer autoloader
require_once plugin_dir_path(__FILE__) . '../vendor/autoload.php';


// Stripe payment process
function myplugin_process_stripe_payment($amount, $currency = 'usd', $customer_email = '') {
    // Retrieve Stripe API key from settings
    $stripe_secret_key = get_option('stripe_secret_key');

    if (!$stripe_secret_key) {
        error_log('Stripe Secret Key is missing.');
        return false;
    }

    \Stripe\Stripe::setApiKey($stripe_secret_key);  // Set Stripe API key

    try {
        $payment_intent = \Stripe\PaymentIntent::create([
            'amount' => $amount * 100, // Convert to cents
            'currency' => $currency,
            'receipt_email' => $customer_email,
        ]);
        return $payment_intent;
    } catch (Exception $e) {
        error_log('Stripe payment failed: ' . $e->getMessage());
        return false;
    }
}
