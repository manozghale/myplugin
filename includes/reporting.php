<?php

// Callback function for Overview page
function myplugin_reports_overview_page() {
    ?>
    <div class="wrap">
        <h1>Reports - Overview</h1>
        <div class="myplugin-report-container">
            <div class="myplugin-sidebar">
                <ul>
                    <li><a href="?page=myplugin_reports" class="<?php echo myplugin_get_active_class('myplugin_reports'); ?>">Overview</a></li>
                    <li><a href="?page=myplugin_reports_downloads" class="<?php echo myplugin_get_active_class('myplugin_reports_downloads'); ?>">Downloads</a></li>
                    <li><a href="?page=myplugin_reports_refunds" class="<?php echo myplugin_get_active_class('myplugin_reports_refunds'); ?>">Refunds</a></li>
                </ul>
            </div>
            <div class="myplugin-content">
                <h2>Overview</h2>
                <!-- Add report content here -->
            </div>
        </div>
    </div>
    <?php
}

// Downloads page
function myplugin_reports_downloads_page() {
    ?>
    <div class="wrap">
        <h1>Reports - Downloads</h1>
        <div class="myplugin-report-container">
            <div class="myplugin-sidebar">
                <ul>
                    <li><a href="?page=myplugin_reports" class="<?php echo myplugin_get_active_class('myplugin_reports'); ?>">Overview</a></li>
                    <li><a href="?page=myplugin_reports_downloads" class="<?php echo myplugin_get_active_class('myplugin_reports_downloads'); ?>">Downloads</a></li>
                    <li><a href="?page=myplugin_reports_refunds" class="<?php echo myplugin_get_active_class('myplugin_reports_refunds'); ?>">Refunds</a></li>
                </ul>
            </div>
            <div class="myplugin-content">
                <h2>Downloads Report</h2>
                <!-- Add Downloads report content here -->
            </div>
        </div>
    </div>
    <?php
}

// Refunds page
function myplugin_reports_refunds_page() {
    ?>
    <div class="wrap">
        <h1>Reports - Refunds</h1>
        <div class="myplugin-report-container">
            <div class="myplugin-sidebar">
                <ul>
                    <li><a href="?page=myplugin_reports" class="<?php echo myplugin_get_active_class('myplugin_reports'); ?>">Overview</a></li>
                    <li><a href="?page=myplugin_reports_downloads" class="<?php echo myplugin_get_active_class('myplugin_reports_downloads'); ?>">Downloads</a></li>
                    <li><a href="?page=myplugin_reports_refunds" class="<?php echo myplugin_get_active_class('myplugin_reports_refunds'); ?>">Refunds</a></li>
                </ul>
            </div>
            <div class="myplugin-content">
                <h2>Refunds Report</h2>
                <!-- Add Refunds report content here -->
            </div>
        </div>
    </div>
    <?php
}


