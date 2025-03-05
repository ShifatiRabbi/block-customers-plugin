<?php
/*
Plugin Name: Block Customers
Plugin URI: https://PluginMakerSR.com
Description: Block, suspend, or ban customers based on IP, ZIP code, email address, name, or combined ZIP + Street Address from ordering in your website. These people can visit but can't place any order. It's an advanced plugin for helping avoid spamming orders.
Version: 1.7.1
Author: PluginMakerSR
Author URI: https://PluginMakerSR.com
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Load necessary files
require_once plugin_dir_path(__FILE__) . 'includes/class-admin.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-checkout.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-block-list.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-import-export.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-license.php';

// Initialize the plugin
function pluginmakersr_block_customers_init() {
    new PluginMakerSR_Block_Customers_Admin();
    new PluginMakerSR_Block_Customers_Checkout();
    new PluginMakerSR_Block_Customers_Import_Export();
    new PluginMakerSR_Block_Customers_License();
}
add_action('plugins_loaded', 'pluginmakersr_block_customers_init');

function my_custom_checkout_styles() {
    if (is_checkout()) {
        wp_enqueue_style('my-custom-checkout-css', plugin_dir_url(__FILE__) . 'assets/css/custom-checkout.css');
    }
}
add_action('wp_enqueue_scripts', 'my_custom_checkout_styles');
