<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class PluginMakerSR_Block_Customers_Checkout {
    private $block_list;

    public function __construct() {
        add_action('woocommerce_after_checkout_validation', [$this, 'check_blocked_customers'], 10, 2);
    }

    public function check_blocked_customers($data, $errors) {
        error_log('check_blocked_customers hook fired'); // Debug statement
        $this->block_list = get_option('pluginmakersr_block_list', []);
        error_log('Block list: ' . print_r($this->block_list, true)); // Debug statement

        // Normalize customer data (billing and shipping)
        $customer_ip = $_SERVER['REMOTE_ADDR'];

        // Billing data
        $billing_zip = strtolower(str_replace([' ', ','], '', $data['billing_postcode'])); // Normalize billing ZIP
        $billing_email = strtolower(trim($data['billing_email'])); // Normalize billing email
        $billing_name = strtolower(str_replace([' ', ','], '', $data['billing_first_name'] . $data['billing_last_name'])); // Normalize billing name
        $billing_address = strtolower(str_replace([' ', ','], '', $data['billing_address_1'])); // Normalize billing address

        // Shipping data
        $shipping_zip = strtolower(str_replace([' ', ','], '', $data['shipping_postcode'])); // Normalize shipping ZIP
        $shipping_name = strtolower(str_replace([' ', ','], '', $data['shipping_first_name'] . $data['shipping_last_name'])); // Normalize shipping name
        $shipping_address = strtolower(str_replace([' ', ','], '', $data['shipping_address_1'])); // Normalize shipping address

        // Debug customer data
        error_log('Customer IP: ' . $customer_ip);
        error_log('Billing ZIP: ' . $billing_zip);
        error_log('Billing Email: ' . $billing_email);
        error_log('Billing Name: ' . $billing_name);
        error_log('Billing Address: ' . $billing_address);
        error_log('Shipping ZIP: ' . $shipping_zip);
        error_log('Shipping Name: ' . $shipping_name);
        error_log('Shipping Address: ' . $shipping_address);

        foreach ($this->block_list as $block) {
            error_log('Checking block: ' . print_r($block, true)); // Debug statement

            // Normalize block value
            $block_value = strtolower(str_replace([' ', ','], '', $block['value']));

            switch ($block['type']) {
                case 'ip':
                    if ($customer_ip === $block_value) {
                        $errors->add('validation', 'You are not allowed to place orders.');
                        break 2;
                    }
                    break;
                case 'zip':
                    if ($billing_zip === $block_value || $shipping_zip === $block_value) {
                        $errors->add('validation', 'You are not allowed to place orders.');
                        break 2;
                    }
                    break;
                case 'email':
                    if ($billing_email === $block_value) {
                        $errors->add('validation', 'You are not allowed to place orders.');
                        break 2;
                    }
                    break;
                case 'name':
                    if ($billing_name === $block_value || $shipping_name === $block_value) {
                        $errors->add('validation', 'You are not allowed to place orders.');
                        break 2;
                    }
                    break;
                case 'address':
                    if ($billing_address === $block_value || $shipping_address === $block_value) {
                        $errors->add('validation', 'You are not allowed to place orders.');
                        break 2;
                    }
                    break;
                case 'zip_street':
                    // Normalize ZIP and Street Address separately for billing and shipping
                    $billing_zip_street_combo = $billing_zip . '|' . $billing_address;
                    $shipping_zip_street_combo = $shipping_zip . '|' . $shipping_address;

                    // Compare with block value
                    if ($billing_zip_street_combo === $block_value || $shipping_zip_street_combo === $block_value) {
                        $errors->add('validation', 'You are not allowed to place orders.');
                        break 2;
                    }
                    break;
            }
        }
    }
}