<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class PluginMakerSR_Block_Customers_Block_List {
    private $block_list = [];
    private $max_entries = 30;

    public function __construct() {
        $this->block_list = get_option('pluginmakersr_block_list', []);
    }

    private function normalize_phone($phone) {
        // Remove all non-numeric characters (e.g., spaces, dashes, plus signs)
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Remove leading zeros (if any) and ensure the phone number starts with the country code
        // Example: 01712345678 -> 1712345678 (for Bangladesh)
        // You can customize this logic based on your country's phone number format
        if (strlen($phone) > 10) {
            $phone = ltrim($phone, '0');
        }

        return $phone;
    }

    public function add_block($type, $value) {
        if (count($this->block_list) >= $this->max_entries) {
            return false; // Max entries reached
        }

        // Normalize the value before saving
        $normalized_value = strtolower(str_replace([' ', ','], '', $value));

        if ($type === 'phone') {
            $normalized_value = $this->normalize_phone($value);
        }

        $new_block = [
            'type' => sanitize_text_field($type),
            'value' => $normalized_value,
        ];

        $this->block_list[] = $new_block;
        update_option('pluginmakersr_block_list', $this->block_list);
        return true;
    }

    public function remove_block($index) {
        unset($this->block_list[$index]);
        $this->block_list = array_values($this->block_list); // Re-index the array
        update_option('pluginmakersr_block_list', $this->block_list);
    }

    public function get_block_list() {
        return $this->block_list;
    }
}