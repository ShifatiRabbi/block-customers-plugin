<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit; // Exit if accessed directly
}

// Delete the block list option
delete_option('pluginmakersr_block_list');