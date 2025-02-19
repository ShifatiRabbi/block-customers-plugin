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

        // Add a submenu for settings
        add_submenu_page(
            'pluginmakersr-block-customers',
            'Settings',
            'Settings',
            'manage_options',
            'pluginmakersr-block-customers-settings',
            [$this, 'settings_page']
        );

        // Add a submenu for license management
        add_submenu_page(
            'pluginmakersr-block-customers',
            'License Management',
            'License Management',
            'manage_options',
            'pluginmakersr-block-customers-license',
            [$this, 'license_page']
        );
    }

    public function admin_page() {
        if (!current_user_can('manage_options')) {
            return;
        }

        // Load the admin page template
        include plugin_dir_path(__FILE__) . '../templates/admin-page.php';
    }

    public function settings_page() {
        if (!current_user_can('manage_options')) {
            return;
        }

        // Load the settings page template
        include plugin_dir_path(__FILE__) . '../templates/settings-page.php';
    }

    public function license_page() {
        if (!current_user_can('manage_options')) {
            return;
        }

        // Load the license management page template
        include plugin_dir_path(__FILE__) . '../templates/license-management.php';
    }
}