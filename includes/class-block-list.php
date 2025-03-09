<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class PluginMakerSR_Block_Customers_Block_List {
    private $block_list = [];
    private $max_entries;

    public function __construct() {
        $this->block_list = get_option('pluginmakersr_block_list', []);
        $this->max_entries = $this->get_max_entries_based_on_license();
        $this->enforce_max_entries(); // Enforce max entries when the class is instantiated
    }

    private function get_max_entries_based_on_license() {
        $license_status = get_option('pluginmakersr_license_status', 'inactive');
        return ($license_status === 'active') ? 99999999 : 40;
    }

    private function enforce_max_entries() {
        $license_status = get_option('pluginmakersr_license_status', 'inactive');
        if ($license_status !== 'active' && count($this->block_list) > $this->max_entries) {
            // Trim the block list to the first 40 records
            $this->block_list = array_slice($this->block_list, 0, $this->max_entries);
            update_option('pluginmakersr_block_list', $this->block_list);

            // Show a message to the user
            add_action('admin_notices', function() {
                echo '<div class="notice notice-warning"><p>Your license is inactive. Only the first 40 records are active. <a href="' . admin_url('admin.php?page=pluginmakersr-block-customers-license') . '">Activate your license</a> to unlock all records.</p></div>';
            });
        }
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