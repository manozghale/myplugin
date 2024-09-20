<?php
/*
Plugin Name: My Digital Downloads Plugin
Description: A custom digital product management plugin similar to EDD.
Version: 1.0
Author: Manoj Ghale
*/

// Minimum PHP version check
if (version_compare(PHP_VERSION, '7.4.0', '<')) {
    wp_die('This plugin requires PHP 7.4 or higher.');
}

// Prevent direct access
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Include necessary files for functionality
include_once plugin_dir_path(__FILE__) . 'includes/license-manager.php';
include_once plugin_dir_path(__FILE__) . 'includes/payment-gateway.php';
include_once plugin_dir_path(__FILE__) . 'includes/reporting.php';
include_once plugin_dir_path(__FILE__) . 'includes/settings.php';
include_once plugin_dir_path(__FILE__) . 'includes/orders.php';
include_once plugin_dir_path(__FILE__) . 'includes/customers.php';
include_once plugin_dir_path(__FILE__) . 'includes/discounts.php';

// Prevent function redeclaration errors using function_exists
if (!function_exists('myplugin_register_settings')) {

    // Register settings for Stripe and PayPal API keys
    function myplugin_register_settings() {
        register_setting('myplugin_settings_group', 'stripe_secret_key');
        register_setting('myplugin_settings_group', 'paypal_client_id');
    }
}

// Prevent function redeclaration errors using function_exists
if (!function_exists('myplugin_register_settings')) {
    include_once plugin_dir_path(__FILE__) . 'includes/settings.php';
}




// Add All Submenus
function myplugin_add_all_sub_menus() {
    // Add Orders submenu
    add_submenu_page(
        'edit.php?post_type=myplugin_download',  // Parent slug (Downloads post type menu)
        'Orders',                               // Page title
        'Orders',                               // Menu title
        'manage_options',                       // Capability required
        'myplugin_orders',                      // Menu slug
        'myplugin_orders_page_callback'         // Function to display the page content
    );

    // Add Payment Gateway submenu
    // add_submenu_page(
    //     'edit.php?post_type=myplugin_download',  // Parent slug (Downloads post type menu)
    //     'Payment Gateway Settings',             // Page title
    //     'Gateway',                              // Menu title
    //     'manage_options',                       // Capability required
    //     'myplugin_gateway',                     // Menu slug
    //     'myplugin_gateway_page_callback'        // Function to display the page content
    // );

    // Add Customers submenu
    add_submenu_page(
        'edit.php?post_type=myplugin_download',  // Parent slug (Downloads post type menu)
        'Customers',                            // Page title
        'Customers',                            // Menu title
        'manage_options',                       // Capability required
        'myplugin_customers',                   // Menu slug
        'myplugin_customers_page_callback'      // Function to display the page content
    );

    // Add Discounts submenu
    add_submenu_page(
        'edit.php?post_type=myplugin_download',  // Parent slug (Downloads post type menu)
        'Discounts',                             // Page title
        'Discounts',                             // Menu title
        'manage_options',                        // Capability required
        'myplugin_discounts',                    // Menu slug
        'myplugin_discounts_page_callback'       // Function to display the page content
    );

     // Add Settings submenu
    add_submenu_page(
        'edit.php?post_type=myplugin_download',  // Parent slug (Downloads post type menu)
        'Settings',                             // Page title
        'Settings',                             // Menu title
        'manage_options',                       // Capability required
        'myplugin_settings',                    // Menu slug
        'myplugin_settings_page_callback'       // Function to display the page content
    );

    // Set a custom icon for the main menu page
    // add_menu_page(
    //     'My Digital Downloads',                 // Page title
    //     'Digital Downloads',                    // Menu title
    //     'manage_options',                       // Capability required
    //     'myplugin_downloads',                  // Menu slug
    //     'myplugin_main_page_callback',          // Function to display the page content
    //     plugin_dir_url(__FILE__) . 'images/download.png' // URL to the custom icon
    // );

    // Add Reports submenu
    add_submenu_page(
        'edit.php?post_type=myplugin_download',  // Parent slug (Downloads post type menu)
        'Reports',                              // Page title
        'Reports',                              // Menu title
        'manage_options',                       // Capability required
        'myplugin_reports',                     // Menu slug
        'myplugin_reports_overview_page',        // Function to display the page content
        6
    );

    // Overview Submenu
    add_submenu_page(
        'myplugin_reports',                     // Parent slug
        'Overview',                             // Page title
        'Overview',                             // Menu title
        'manage_options',                       // Capability
        'myplugin_reports',                     // Same slug as main page for default subpage
        'myplugin_reports_overview_page'        // Callback function for overview page
    );

    // Downloads Submenu
    add_submenu_page(
        'myplugin_reports',                     // Parent slug
        'Downloads Report',                     // Page title
        'Downloads',                            // Menu title
        'manage_options',                       // Capability
        'myplugin_reports_downloads',           // Menu slug
        'myplugin_reports_downloads_page'       // Callback function for downloads page
    );

    // Refunds Submenu
    add_submenu_page(
        'myplugin_reports',                     // Parent slug
        'Refunds Report',                       // Page title
        'Refunds',                              // Menu title
        'manage_options',                       // Capability
        'myplugin_reports_refunds',             // Menu slug
        'myplugin_reports_refunds_page'         // Callback function for refunds page
    );
}
add_action('admin_menu', 'myplugin_add_all_sub_menus');


// Register custom taxonomy for Categories
function myplugin_register_download_categories() {
    $labels = array(
        'name'              => 'Categories',
        'singular_name'     => 'Category',
        'search_items'      => 'Search Categories',
        'all_items'         => 'All Categories',
        'parent_item'       => 'Parent Category',
        'parent_item_colon' => 'Parent Category:',
        'edit_item'         => 'Edit Category',
        'update_item'       => 'Update Category',
        'add_new_item'      => 'Add New Category',
        'new_item_name'     => 'New Category Name',
        'menu_name'         => 'Categories',
    );

    $args = array(
        'hierarchical'      => true, // Makes it act like a category with checkboxes
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'download-category'),
    );

    register_taxonomy('download_category', array('myplugin_download'), $args);

    // Predefine the terms: Mp3 and Wav
    if (!term_exists('Mp3', 'download_category')) {
        wp_insert_term('Mp3', 'download_category');
    }
    if (!term_exists('Wav', 'download_category')) {
        wp_insert_term('Wav', 'download_category');
    }
}
add_action('init', 'myplugin_register_download_categories');


// Add the Download Settings meta box
function myplugin_add_download_settings_meta_box() {
    add_meta_box(
        'download_settings_meta',         // Unique ID
        'Download Settings',              // Box title
        'myplugin_download_settings_meta_box_callback',  // Callback function
        'myplugin_download',              // Post type
        'normal',                         // Context
        'high'                            // Priority
    );
}
add_action('add_meta_boxes', 'myplugin_add_download_settings_meta_box');


// Callback for the Download Settings meta box
function myplugin_download_settings_meta_box_callback($post) {
    // Retrieve existing values from the post meta
    $refund_status = get_post_meta($post->ID, '_refund_status', true);
    $refund_window = get_post_meta($post->ID, '_refund_window', true);
    $button_option = get_post_meta($post->ID, '_button_option', true);
    $purchase_shortcode = get_post_meta($post->ID, '_purchase_shortcode', true);

    // Default refund status if not set
    if (empty($refund_status)) {
        $refund_status = 'default';
    }

    ?>
    <h4>Refund Status</h4>
    <select name="refund_status" id="refund_status">
        <option value="default" <?php selected($refund_status, 'default'); ?>>Default (Non Refundable)</option>
        <option value="refundable" <?php selected($refund_status, 'refundable'); ?>>Refundable</option>
        <option value="non_refundable" <?php selected($refund_status, 'non_refundable'); ?>>Non Refundable</option>
    </select>

    <div id="refund_window_wrapper" style="display: <?php echo ($refund_status === 'refundable') ? 'block' : 'none'; ?>;">
        <h4>Refund Window (in days)</h4>
        <input type="number" name="refund_window" value="<?php echo esc_attr($refund_window); ?>" />
    </div>

    <h4>Button Options</h4>
    <select name="button_option" id="button_option">
        <option value="add_to_cart" <?php selected($button_option, 'add_to_cart'); ?>>Add to Cart</option>
        <option value="buy_now" <?php selected($button_option, 'buy_now'); ?>>Buy Now</option>
    </select>

    <h4>Purchase Shortcode</h4>
    <input type="text" name="purchase_shortcode" value="<?php echo esc_attr($purchase_shortcode); ?>" placeholder="[myplugin_purchase id=<?php echo $post->ID; ?>]" />

    <script>
        // Toggle the refund window field visibility based on refund status
        document.getElementById('refund_status').addEventListener('change', function() {
            var refundWindowWrapper = document.getElementById('refund_window_wrapper');
            if (this.value === 'refundable') {
                refundWindowWrapper.style.display = 'block';
            } else {
                refundWindowWrapper.style.display = 'none';
            }
        });
    </script>
    <?php
}

// Save download settings
function myplugin_save_download_settings_meta($post_id) {
    // Save refund status
    if (isset($_POST['refund_status'])) {
        update_post_meta($post_id, '_refund_status', sanitize_text_field($_POST['refund_status']));
    }

    // Save refund window only if 'refundable' is selected
    if (isset($_POST['refund_status']) && $_POST['refund_status'] === 'refundable') {
        if (isset($_POST['refund_window'])) {
            update_post_meta($post_id, '_refund_window', intval($_POST['refund_window']));
        }
    } else {
        delete_post_meta($post_id, '_refund_window'); // Remove refund window if it's not refundable
    }

    // Save button options
    if (isset($_POST['button_option'])) {
        update_post_meta($post_id, '_button_option', sanitize_text_field($_POST['button_option']));
    }

    // Save purchase shortcode
    if (isset($_POST['purchase_shortcode'])) {
        update_post_meta($post_id, '_purchase_shortcode', sanitize_text_field($_POST['purchase_shortcode']));
    }
}
add_action('save_post', 'myplugin_save_download_settings_meta');


// Shortcode to display purchase button
function myplugin_purchase_shortcode($atts) {
    $atts = shortcode_atts(array(
        'id' => 0,
    ), $atts);

    $post_id = intval($atts['id']);
    $button_option = get_post_meta($post_id, '_button_option', true);

    // Generate button HTML based on selected option
    if ($button_option === 'buy_now') {
        return '<a href="?buy_now=' . $post_id . '" class="button buy-now-button">Buy Now</a>';
    } else {
        return '<a href="?add_to_cart=' . $post_id . '" class="button add-to-cart-button">Add to Cart</a>';
    }
}
add_shortcode('myplugin_purchase', 'myplugin_purchase_shortcode');



// Register custom post type for digital products
function myplugin_create_post_type() {
    $labels = array(
        'name' => 'Downloads',
        'singular_name' => 'Download',
        'add_new_item' => 'Add New Download',
        'edit_item' => 'Edit Download',
        'new_item' => 'New Download',
        'view_item' => 'View Download',
        'not_found' => 'No downloads found'
    );

    $args = array(
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
        'menu_icon' => 'dashicons-media-document',
        'labels' => $labels
    );

    register_post_type('myplugin_download', $args);
}
add_action('init', 'myplugin_create_post_type');


// Callback function for the download meta box
function myplugin_download_meta_box_callback($post) {
    // Retrieve the current value of the custom fields
    $download_price = get_post_meta($post->ID, '_download_price', true);
    $download_file = get_post_meta($post->ID, '_download_file', true);
    $download_type = get_post_meta($post->ID, '_download_type', true); // New product type field
    $download_instruction = get_post_meta($post->ID, '_download_instruction', true);

    // Define the product types
    $product_types = array(
        'single' => 'Single Product',
        'bundle' => 'Bundle',
        'service' => 'Service'
    );

    // Output the meta box HTML
    ?>
    <label for="download_price">Price ($):</label>
    <input type="text" name="download_price" id="download_price" value="<?php echo esc_attr($download_price); ?>" /><br/><br/>

    <label for="download_file">Upload File:</label>
    <input type="file" name="download_file" id="download_file" /><br/>
    <?php
    // If a file is already uploaded, display a link to it
    if ($download_file) {
        echo '<p>Current File: <a href="' . esc_url($download_file) . '" target="_blank">Download</a></p>';
    }

    // Product Type Dropdown
    ?>
    <br/>
    <label for="download_type">Product Type:</label>
    <select name="download_type" id="download_type">
        <?php foreach ($product_types as $key => $label) : ?>
            <option value="<?php echo esc_attr($key); ?>" <?php selected($download_type, $key); ?>><?php echo esc_html($label); ?></option>
        <?php endforeach; ?>
    </select>
    <?php

     // Output the HTML for the download instruction and download files section
     ?>
     <div id="download_files_wrapper">
         <h4>Download Files</h4>
         <?php if (!empty($download_files)) : ?>
             <?php foreach ($download_files as $index => $file) : ?>
                 <div class="download-file-section">
                     <label for="download_file_id_<?php echo $index; ?>">File ID:</label>
                     <input type="text" name="download_files[<?php echo $index; ?>][id]" value="<?php echo esc_attr($file['id']); ?>" id="download_file_id_<?php echo $index; ?>" /><br/><br/>
 
                     <label for="download_file_name_<?php echo $index; ?>">File Name:</label>
                     <input type="text" name="download_files[<?php echo $index; ?>][name]" value="<?php echo esc_attr($file['name']); ?>" id="download_file_name_<?php echo $index; ?>" /><br/><br/>
 
                     <label for="download_file_url_<?php echo $index; ?>">File URL:</label>
                     <input type="text" name="download_files[<?php echo $index; ?>][url]" value="<?php echo esc_url($file['url']); ?>" id="download_file_url_<?php echo $index; ?>" /><br/><br/>
 
                     <button type="button" class="remove-file-button">Remove File</button>
                     <hr/>
                 </div>
             <?php endforeach; ?>
         <?php else : ?>
             <div class="download-file-section">
                 <label for="download_file_id_0">File ID:</label>
                 <input type="text" name="download_files[0][id]" id="download_file_id_0" /><br/><br/>
 
                 <label for="download_file_name_0">File Name:</label>
                 <input type="text" name="download_files[0][name]" id="download_file_name_0" /><br/><br/>
 
                 <label for="download_file_url_0">File URL:</label>
                 <input type="text" name="download_files[0][url]" id="download_file_url_0" /><br/><br/>
 
                 <button type="button" class="remove-file-button">Remove File</button>
                 <hr/>
             </div>
         <?php endif; ?>
     </div>
 
     <button type="button" id="add-new-file">Add New File</button>
 
     <div class="download-instruction-section">
         <h4>Download Instructions</h4>
         <label for="download_instruction">Instructions:</label>
         <textarea name="download_instruction" id="download_instruction" rows="5" cols="50"><?php echo esc_textarea($download_instruction); ?></textarea>
     </div>
 
     <?php
}


// Register admin meta boxes
function myplugin_add_download_meta_box() {
    add_meta_box('download_meta', 'Download Details', 'myplugin_download_meta_box_callback', 'myplugin_download');
}
add_action('add_meta_boxes', 'myplugin_add_download_meta_box');

// Saving meta fields
function myplugin_save_download_meta($post_id) {
    if (isset($_POST['download_price'])) {
        update_post_meta($post_id, '_download_price', sanitize_text_field($_POST['download_price']));
    }
    if (!empty($_FILES['download_file']['name'])) {
        $upload = wp_handle_upload($_FILES['download_file'], array('test_form' => false));
        if ($upload && !isset($upload['error'])) {
            update_post_meta($post_id, '_download_file', $upload['url']);
        }
    }
    // Save the product type
    if (isset($_POST['download_type'])) {
        update_post_meta($post_id, '_download_type', sanitize_text_field($_POST['download_type']));
    }

    // Save download instruction
    if (isset($_POST['download_instruction'])) {
        update_post_meta($post_id, '_download_instruction', sanitize_textarea_field($_POST['download_instruction']));
    }
}
add_action('save_post', 'myplugin_save_download_meta');

// Frontend product display and checkout
function myplugin_display_downloads() {
    $args = array('post_type' => 'myplugin_download', 'posts_per_page' => 10);
    $query = new WP_Query($args);

    if ($query->have_posts()) {
        echo '<ul class="downloads">';
        while ($query->have_posts()) {
            $query->the_post();
            $price = get_post_meta(get_the_ID(), '_download_price', true);
            $type = get_post_meta(get_the_ID(), '_download_type', true);
            echo '<li>' . get_the_title() . ' (' . ucfirst($type) . ') - $' . $price . ' <a href="?add_to_cart=' . get_the_ID() . '">Add to Cart</a></li>';
        }
        echo '</ul>';
    }
    wp_reset_postdata();
}
add_shortcode('myplugin_downloads', 'myplugin_display_downloads');


if (!function_exists('myplugin_add_settings_page')) {

    // Create a settings page for payment gateways
    function myplugin_add_settings_page() {
        add_options_page(
            'MyPlugin Settings',  // Page title
            'MyPlugin Settings',  // Menu title
            'manage_options',     // Capability
            'myplugin_settings',  // Menu slug
            'myplugin_settings_page' // Function to display the settings page
        );
    }
}

if (!function_exists('myplugin_settings_page')) {

    // Display the settings page where admins can input Stripe and PayPal keys
    function myplugin_settings_page() {
        ?>
        <div class="wrap">
            <h1>MyPlugin Payment Settings</h1>
            <form method="post" action="options.php">
                <?php
                // Output the necessary fields and security fields for the settings page
                settings_fields('myplugin_settings_group');
                do_settings_sections('myplugin_settings');
                ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">Stripe Secret Key</th>
                        <td><input type="text" name="stripe_secret_key" value="<?php echo esc_attr(get_option('stripe_secret_key')); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">PayPal Client ID</th>
                        <td><input type="text" name="paypal_client_id" value="<?php echo esc_attr(get_option('paypal_client_id')); ?>" /></td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
}

// Hook the settings registration and settings page addition into WordPress
add_action('admin_init', 'myplugin_register_settings');
add_action('admin_menu', 'myplugin_add_settings_page');

// Example: Using the Stripe Secret Key
if (!function_exists('myplugin_process_payment')) {

    // Example function to use the saved Stripe key (this would be used in your payment processing)
    function myplugin_process_payment() {
        $stripe_secret_key = get_option('stripe_secret_key');
        if ($stripe_secret_key) {
            // Use the Stripe API to process a payment
            \Stripe\Stripe::setApiKey($stripe_secret_key);
            // Add payment processing code here...
        } else {
            error_log('Stripe Secret Key is not set. Payment cannot be processed.');
        }
    }
}

// Example: Using the PayPal Client ID
if (!function_exists('myplugin_process_paypal_payment')) {

    // Example function to use the saved PayPal Client ID (this would be used in your PayPal payment processing)
    function myplugin_process_paypal_payment() {
        $paypal_client_id = get_option('paypal_client_id');
        if ($paypal_client_id) {
            // Use the PayPal API to process a payment
            // Add PayPal payment processing code here...
        } else {
            error_log('PayPal Client ID is not set. Payment cannot be processed.');
        }
    }
}
?>