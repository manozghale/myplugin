<?php

// Create the customers table upon plugin activation
function myplugin_create_customers_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'myplugin_customers';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        customer_id bigint(20) NOT NULL AUTO_INCREMENT,
        name varchar(255) NOT NULL,
        email varchar(255) NOT NULL,
        orders int(11) NOT NULL,
        spent decimal(10,2) NOT NULL,
        date_registered datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        PRIMARY KEY (customer_id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'myplugin_create_customers_table');


// Example: Add a new customer after an order is placed
function myplugin_add_customer($customer_name, $customer_email, $order_total) {
    global $wpdb;
    
    // Check if the customer already exists
    $existing_customer = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}myplugin_customers WHERE email = %s", $customer_email
    ));

    if ($existing_customer) {
        // Update the existing customer record
        $wpdb->update(
            "{$wpdb->prefix}myplugin_customers",
            array(
                'orders' => $existing_customer->orders + 1,
                'spent'  => $existing_customer->spent + $order_total,
            ),
            array('email' => $customer_email)
        );
    } else {
        // Insert a new customer record
        $wpdb->insert(
            "{$wpdb->prefix}myplugin_customers",
            array(
                'name'            => $customer_name,
                'email'           => $customer_email,
                'orders'          => 1,
                'spent'           => $order_total,
                'date_registered' => current_time('mysql'),
            )
        );
    }
}

// Callback function to display the Customers page content
function myplugin_customers_page_callback() {
    ?>
    <div class="wrap">
        <h1>Customers</h1>
        <p>Here you can view and manage your customers.</p>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Orders</th>
                    <th scope="col">Total Spent</th>
                    <th scope="col">Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Example: Fetch customers from the database (replace this with your actual customer management logic)
                global $wpdb;
                $customers = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}myplugin_customers");

                if ($customers) {
                    foreach ($customers as $customer) {
                        echo '<tr>';
                        echo '<td>' . esc_html($customer->name) . '</td>';
                        echo '<td>' . esc_html($customer->email) . '</td>';
                        echo '<td>' . esc_html($customer->orders) . '</td>';
                        echo '<td>$' . esc_html($customer->spent) . '</td>';
                        echo '<td>' . esc_html($customer->date_registered) . '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="5">No customers found.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php
}