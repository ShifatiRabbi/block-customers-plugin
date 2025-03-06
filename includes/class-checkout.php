<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class PluginMakerSR_Block_Customers_Checkout {
    private $block_list;

    public function __construct() {
        add_action('woocommerce_after_checkout_validation', [$this, 'check_blocked_customers'], 10, 2);
    }

    private function normalize_phone($phone) {
        // Remove all non-numeric characters (e.g., spaces, dashes, plus signs)
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Remove leading zeros (if any) and ensure the phone number starts with the country code
        if (strlen($phone) > 10) {
            $phone = ltrim($phone, '0');
        }

        return $phone;
    }

    public function check_blocked_customers($data, $errors) {
        error_log('check_blocked_customers hook fired'); // Debug statement
        $this->block_list = get_option('pluginmakersr_block_list', []);
        error_log('Block list: ' . print_r($this->block_list, true)); // Debug statement

        // Retrieve billing settings
        $billing_name_field_type = get_option('pluginmakersr_billing_name_field_type', 'default');
        $billing_name_custom_field = get_option('pluginmakersr_billing_name_custom_field', 'billing_full_name');
        $billing_email_field_type = get_option('pluginmakersr_billing_email_field_type', 'default');
        $billing_email_custom_field = get_option('pluginmakersr_billing_email_custom_field', 'billing_email');
        $billing_zip_field_type = get_option('pluginmakersr_billing_zip_field_type', 'default');
        $billing_zip_custom_field = get_option('pluginmakersr_billing_zip_custom_field', 'billing_postcode');
        $billing_address_field_type = get_option('pluginmakersr_billing_address_field_type', 'default');
        $billing_address_custom_field = get_option('pluginmakersr_billing_address_custom_field', 'billing_address_1');
        $billing_phone_field_type = get_option('pluginmakersr_billing_phone_field_type', 'default');
        $billing_phone_custom_field = get_option('pluginmakersr_billing_phone_custom_field', 'billing_phone');

        // Retrieve shipping settings
        $shipping_name_field_type = get_option('pluginmakersr_shipping_name_field_type', 'default');
        $shipping_name_custom_field = get_option('pluginmakersr_shipping_name_custom_field', 'shipping_full_name');
        $shipping_zip_field_type = get_option('pluginmakersr_shipping_zip_field_type', 'default');
        $shipping_zip_custom_field = get_option('pluginmakersr_shipping_zip_custom_field', 'shipping_postcode');
        $shipping_address_field_type = get_option('pluginmakersr_shipping_address_field_type', 'default');
        $shipping_address_custom_field = get_option('pluginmakersr_shipping_address_custom_field', 'shipping_address_1');

        // Normalize customer data
        $customer_ip = $_SERVER['REMOTE_ADDR'];

        // Billing data
        $customer_email = $this->get_field_value($data, $billing_email_field_type, $billing_email_custom_field, 'billing_email');
        $customer_name = $this->get_field_value($data, $billing_name_field_type, $billing_name_custom_field, 'billing_first_name', 'billing_last_name');
        $customer_zip = $this->get_field_value($data, $billing_zip_field_type, $billing_zip_custom_field, 'billing_postcode');
        $customer_address = $this->get_field_value($data, $billing_address_field_type, $billing_address_custom_field, 'billing_address_1');
        $customer_phone = $this->get_field_value($data, $billing_phone_field_type, $billing_phone_custom_field, 'billing_phone');

        // Shipping data
        $shipping_name = $this->get_field_value($data, $shipping_name_field_type, $shipping_name_custom_field, 'shipping_first_name', 'shipping_last_name');
        $shipping_zip = $this->get_field_value($data, $shipping_zip_field_type, $shipping_zip_custom_field, 'shipping_postcode');
        $shipping_address = $this->get_field_value($data, $shipping_address_field_type, $shipping_address_custom_field, 'shipping_address_1');

        // Debug customer data
        error_log('Customer IP: ' . $customer_ip);
        error_log('Customer Email: ' . $customer_email);
        error_log('Customer Name: ' . $customer_name);
        error_log('Customer ZIP: ' . $customer_zip);
        error_log('Customer Address: ' . $customer_address);
        error_log('Customer Phone: ' . $customer_phone);
        error_log('Shipping Name: ' . $shipping_name);
        error_log('Shipping ZIP: ' . $shipping_zip);
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
                    if ($customer_zip === $block_value || $shipping_zip === $block_value) {
                        $errors->add('validation', 'You are not allowed to place orders.');
                        break 2;
                    }
                    break;
                case 'email':
                    if ($customer_email === $block_value) {
                        $errors->add('validation', 'You are not allowed to place orders.');
                        break 2;
                    }
                    break;
                case 'name':
                    if ($customer_name === $block_value || $shipping_name === $block_value) {
                        $errors->add('validation', 'You are not allowed to place orders.');
                        break 2;
                    }
                    break;
                case 'address':
                    if ($customer_address === $block_value || $shipping_address === $block_value) {
                        $errors->add('validation', 'You are not allowed to place orders.');
                        break 2;
                    }
                    break;
                case 'zip_street':
                    $zip_street_combo = $customer_zip . '|' . $customer_address;
                    $shipping_zip_street_combo = $shipping_zip . '|' . $shipping_address;
                    if ($zip_street_combo === $block_value || $shipping_zip_street_combo === $block_value) {
                        $errors->add('validation', 'You are not allowed to place orders.');
                        break 2;
                    }
                    break;
                case 'phone':
                    $block_phone = $this->normalize_phone($block_value);
                    $customer_phone_normalized = $this->normalize_phone($customer_phone);
                    if ($customer_phone_normalized === $block_phone) {
                        $errors->add('validation', 'You are not allowed to place orders.');
                        break 2;
                    }
                    break;
            }
        }
    }

    /**
     * Get the value of a field based on settings.
     */
    private function get_field_value($data, $field_type, $custom_field, $default_field1, $default_field2 = '') {
        if ($field_type === 'custom') {
            return isset($data[$custom_field]) ? strtolower(str_replace([' ', ','], '', $data[$custom_field])) : '';
        } else {
            if ($default_field2) {
                return isset($data[$default_field1]) && isset($data[$default_field2]) ? strtolower(str_replace([' ', ','], '', $data[$default_field1] . $data[$default_field2])) : '';
            } else {
                return isset($data[$default_field1]) ? strtolower(str_replace([' ', ','], '', $data[$default_field1])) : '';
            }
        }
    }
}