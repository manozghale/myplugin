<?php

// Example: Add a new discount
function myplugin_add_discount($discount_name, $discount_status, $discount_code, $discount_amount, $uses, $start_date, $end_date) {
    global $wpdb;

    // Insert a new discount record
    $wpdb->insert(
        "{$wpdb->prefix}myplugin_discounts",
        array(
            'name'        => $discount_name,
            'status'      => $discount_status,
            'code'        => $discount_code,
            'amount'      => $discount_amount,
            'uses'        => $uses,
            'start_date'  => $start_date,
            'end_date'    => $end_date
        )
    );
}

// Create the discounts table upon plugin activation
function myplugin_create_discounts_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'myplugin_discounts';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        discount_id bigint(20) NOT NULL AUTO_INCREMENT,
        name varchar(255) NOT NULL,
        status varchar(50) NOT NULL,
        code varchar(50) NOT NULL,
        amount decimal(10,2) NOT NULL,
        uses int(11) NOT NULL,
        start_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        end_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        PRIMARY KEY (discount_id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'myplugin_create_discounts_table');

// Callback function to display the Discounts page content
function myplugin_discounts_page_callback() {
    ?>
    <div class="wrap">
        <h1>Discounts</h1>
        <p>Here you can view and manage your discount codes.</p>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Status</th>
                    <th scope="col">Code</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Uses</th>
                    <th scope="col">Start Date</th>
                    <th scope="col">End Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Example: Fetch discounts from the database (replace this with your actual discount management logic)
                global $wpdb;
                $discounts = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}myplugin_discounts");

                if ($discounts) {
                    foreach ($discounts as $discount) {
                        echo '<tr>';
                        echo '<td>' . esc_html($discount->name) . '</td>';
                        echo '<td>' . esc_html($discount->status) . '</td>';
                        echo '<td>' . esc_html($discount->code) . '</td>';
                        echo '<td>' . esc_html($discount->amount) . '</td>';
                        echo '<td>' . esc_html($discount->uses) . '</td>';
                        echo '<td>' . esc_html($discount->start_date) . '</td>';
                        echo '<td>' . esc_html($discount->end_date) . '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="7">No discounts found.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php
}