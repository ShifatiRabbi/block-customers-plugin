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

    public function add_block($type, $value) {
        if (count($this->block_list) >= $this->max_entries) {
            return false; // Max entries reached
        }

        $new_block = [
            'type' => sanitize_text_field($type),
            'value' => sanitize_text_field($value)
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