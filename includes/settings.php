<?php

// Register settings for Stripe, PayPal, and other settings
function myplugin_register_settings()
{
    register_setting('myplugin_settings_group', 'stripe_secret_key');
    register_setting('myplugin_settings_group', 'paypal_client_id');
    // register_setting('myplugin_settings_group', 'payment_currency');
    // register_setting('myplugin_settings_group', 'test_mode');

    // Add settings section
    add_settings_section(
        'myplugin_settings_section', // ID
        'Payment Gateway Settings',  // Title
        'myplugin_settings_section_callback', // Callback
        'myplugin_settings'          // Page
    );

    // Add settings fields
    add_settings_field(
        'stripe_secret_key', // ID
        'Stripe Secret Key', // Title
        'myplugin_stripe_secret_key_callback', // Callback
        'myplugin_settings', // Page
        'myplugin_settings_section' // Section
    );

    add_settings_field(
        'paypal_client_id', // ID
        'PayPal Client ID', // Title
        'myplugin_paypal_client_id_callback', // Callback
        'myplugin_settings', // Page
        'myplugin_settings_section' // Section
    );
}
add_action('admin_init', 'myplugin_register_settings');

// Settings section callback function
function myplugin_settings_section_callback() {
    echo '<p>Enter your payment gateway settings below:</p>';
}

// Stripe Secret Key field callback function
function myplugin_stripe_secret_key_callback() {
    $value = get_option('stripe_secret_key', '');
    echo '<input type="text" name="stripe_secret_key" value="' . esc_attr($value) . '" />';
}

// PayPal Client ID field callback function
function myplugin_paypal_client_id_callback() {
    $value = get_option('paypal_client_id', '');
    echo '<input type="text" name="paypal_client_id" value="' . esc_attr($value) . '" />';
}

// Callback function to display the Settings page content
function myplugin_settings_page_callback() {
    ?>
    <div class="wrap">
        <h1>Settings</h1>
        <form method="post" action="options.php">
            <?php
            // Output security fields for the registered setting "myplugin_settings_group"
            settings_fields('myplugin_settings_group');

            // Output setting sections and their fields
            do_settings_sections('myplugin_settings');

            // Output save settings button
            submit_button();
            ?>
        </form>
    </div>
    <?php
}


