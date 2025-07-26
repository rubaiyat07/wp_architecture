<?php 
/**
 * Plugin Name: WP Architecture Plugin
 * Description: Plugin for contact form with map, architect profile, slider, project progress, and shortcode admin panel.
 * Version: 1.1
 * Author: Rubaiyat Afreen
 */

if (!defined('ABSPATH')) exit;

// Admin Shortcode Page
require_once plugin_dir_path(__FILE__) . 'admin/shortcode-list-page.php';

// Feature Includes
require_once plugin_dir_path(__FILE__) . 'includes/wp-architecture-contact-map.php';
require_once plugin_dir_path(__FILE__) . 'includes/wp-architecture-profile.php';
require_once plugin_dir_path(__FILE__) . 'includes/wp-architecture-slider.php';
require_once plugin_dir_path(__FILE__) . 'includes/wp-architecture-project-progress.php';

// Create all tables on plugin activation
register_activation_hook(__FILE__, 'wp_architecture_create_tables');

function wp_architecture_create_tables() {
    global $wpdb;
    $feedback_table = $wpdb->prefix . 'wp_architecture_feedback';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $feedback_table (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name tinytext NOT NULL,
        email varchar(100) NOT NULL,
        company varchar(100) DEFAULT NULL,
        message_type varchar(20) NOT NULL DEFAULT 'contact',
        message text NOT NULL,
        submitted_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    // Create project progress table
    $progress_plugin = new WP_Architecture_Project_Progress();
    $progress_plugin->create_table();
}

// Initialize all components
function wp_architecture_init_components() {
    new WP_Architecture_Project_Progress();
    new WP_Architecture_Slider();
    // Initialize other components as needed
}
add_action('plugins_loaded', 'wp_architecture_init_components');