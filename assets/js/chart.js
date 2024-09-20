function myplugin_enqueue_chart_js() {
    wp_enqueue_script('chart-js', 'https://cdn.jsdelivr.net/npm/chart.js', array(), null, true);
}
add_action('admin_enqueue_scripts', 'myplugin_enqueue_chart_js');
