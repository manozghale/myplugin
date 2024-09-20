<?php

// Create the orders table upon plugin activation
function myplugin_create_orders_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'myplugin_orders';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        order_id bigint(20) NOT NULL AUTO_INCREMENT,
        customer_name varchar(255) NOT NULL,
        product_name varchar(255) NOT NULL,
        total_amount decimal(10,2) NOT NULL,
        order_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        order_status varchar(50) NOT NULL,
        PRIMARY KEY (order_id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'myplugin_create_orders_table');


// Callback function to display the Orders page content
function myplugin_orders_page_callback() {
    ?>
    <div class="wrap">
        <h1>Orders</h1>
        <p>Here you can view and manage your orders.</p>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th scope="col">Order ID</th>
                    <th scope="col">Customer</th>
                    <th scope="col">Product</th>
                    <th scope="col">Total</th>
                    <th scope="col">Date</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Example: Fetch orders from the database (replace this with your actual order management logic)
                global $wpdb;
                $orders = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}myplugin_orders");

                if ($orders) {
                    foreach ($orders as $order) {
                        echo '<tr>';
                        echo '<td>' . esc_html($order->order_id) . '</td>';
                        echo '<td>' . esc_html($order->customer_name) . '</td>';
                        echo '<td>' . esc_html($order->product_name) . '</td>';
                        echo '<td>' . esc_html($order->total_amount) . '</td>';
                        echo '<td>' . esc_html($order->order_date) . '</td>';
                        echo '<td>' . esc_html($order->order_status) . '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="6">No orders found.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php
}

