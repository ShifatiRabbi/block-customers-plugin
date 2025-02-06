<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class PluginMakerSR_Block_Customers_Admin {
    public function __construct() {
        add_action('admin_menu', [$this, 'add_admin_menu']);
    }

    public function add_admin_menu() {
        add_menu_page(
            'Block Customers',
            'Block Customers',
            'manage_options',
            'pluginmakersr-block-customers',
            [$this, 'admin_page'],
            'dashicons-shield-alt',
            20
        );
    }

    public function admin_page() {
        if (!current_user_can('manage_options')) {
            return;
        }

        // Load the admin page template
        include plugin_dir_path(__FILE__) . '../templates/admin-page.php';
    }
}