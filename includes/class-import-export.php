<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class PluginMakerSR_Block_Customers_Import_Export {
    public function __construct() {
        add_action('admin_init', [$this, 'handle_import_export']);
    }

    /**
     * Handle import and export actions.
     */
    public function handle_import_export() {
        if (!current_user_can('manage_options')) {
            return;
        }

        // Check license status
        $license_status = get_option('pluginmakersr_license_status', 'inactive');
        $is_license_active = ($license_status === 'active');

        if (!$is_license_active) {
            return; // Exit if license is not active
        }

        // Export JSON
        if (isset($_POST['export_json'])) {
            $this->export_json();
        }

        // Export Excel (CSV)
        if (isset($_POST['export_excel'])) {
            $this->export_excel();
        }

        // Import JSON
        if (isset($_POST['import_json']) && !empty($_FILES['import_file_json']['tmp_name'])) {
            $this->import_json($_FILES['import_file_json']['tmp_name']);
        }

        // Import Excel (CSV)
        if (isset($_POST['import_excel']) && !empty($_FILES['import_file_excel']['tmp_name'])) {
            $this->import_excel($_FILES['import_file_excel']['tmp_name']);
        }
    }

    /**
     * Export block list as JSON.
     */
    private function export_json() {
        $block_list = get_option('pluginmakersr_block_list', []);
        $filename = 'block-customers-export-' . date('Y-m-d') . '.json';

        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename=' . $filename);
        echo json_encode($block_list, JSON_PRETTY_PRINT);
        exit;
    }

    /**
     * Export block list as Excel (CSV).
     */
    private function export_excel() {
        $block_list = get_option('pluginmakersr_block_list', []);
        $filename = 'block-customers-export-' . date('Y-m-d') . '.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename=' . $filename);

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Type', 'Value']); // CSV header

        foreach ($block_list as $block) {
            fputcsv($output, [$block['type'], $block['value']]);
        }

        fclose($output);
        exit;
    }

    /**
     * Import block list from JSON.
     */
    private function import_json($file_path) {
        error_log('Importing JSON file: ' . $file_path); // Debug statement
        $file_content = file_get_contents($file_path);
        error_log('File content: ' . $file_content); // Debug statement
        $imported_data = json_decode($file_content, true);
    
        if (is_array($imported_data)) {
            error_log('Imported data: ' . print_r($imported_data, true)); // Debug statement
            $existing_data = get_option('pluginmakersr_block_list', []);
    
            // Merge data and avoid duplicates
            foreach ($imported_data as $new_block) {
                $is_duplicate = false;
                foreach ($existing_data as $existing_block) {
                    if ($new_block['type'] === $existing_block['type'] && $new_block['value'] === $existing_block['value']) {
                        $is_duplicate = true;
                        break;
                    }
                }
                if (!$is_duplicate) {
                    $existing_data[] = $new_block;
                }
            }
    
            update_option('pluginmakersr_block_list', $existing_data);
    
            add_action('admin_notices', function() {
                echo '<div class="notice notice-success"><p>Block list imported successfully!</p></div>';
            });
        } else {
            add_action('admin_notices', function() {
                echo '<div class="notice notice-error"><p>Invalid JSON file.</p></div>';
            });
        }
    }
    
    private function import_excel($file_path) {
        error_log('Importing Excel file: ' . $file_path); // Debug statement
        $imported_data = [];
        $file = fopen($file_path, 'r');
    
        // Skip the header row
        fgetcsv($file);
    
        while (($data = fgetcsv($file)) !== false) {
            $imported_data[] = [
                'type' => sanitize_text_field($data[0]),
                'value' => sanitize_text_field($data[1]),
            ];
        }
    
        fclose($file);
    
        if (!empty($imported_data)) {
            error_log('Imported data: ' . print_r($imported_data, true)); // Debug statement
            $existing_data = get_option('pluginmakersr_block_list', []);
    
            // Merge data and avoid duplicates
            foreach ($imported_data as $new_block) {
                $is_duplicate = false;
                foreach ($existing_data as $existing_block) {
                    if ($new_block['type'] === $existing_block['type'] && $new_block['value'] === $existing_block['value']) {
                        $is_duplicate = true;
                        break;
                    }
                }
                if (!$is_duplicate) {
                    $existing_data[] = $new_block;
                }
            }
    
            update_option('pluginmakersr_block_list', $existing_data);
    
            add_action('admin_notices', function() {
                echo '<div class="notice notice-success"><p>Block list imported successfully!</p></div>';
            });
        } else {
            add_action('admin_notices', function() {
                echo '<div class="notice notice-error"><p>Invalid CSV file.</p></div>';
            });
        }
    }

}