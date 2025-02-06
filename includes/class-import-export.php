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

        // Export JSON
        if (isset($_POST['export_json'])) {
            $this->export_json();
        }

        // Import JSON
        if (isset($_POST['import_json']) && !empty($_FILES['import_file']['tmp_name'])) {
            $this->import_json($_FILES['import_file']['tmp_name']);
        }

        // Export Excel (CSV)
        if (isset($_POST['export_excel'])) {
            $this->export_excel();
        }

        // Import Excel (CSV)
        if (isset($_POST['import_excel']) && !empty($_FILES['import_file']['tmp_name'])) {
            $this->import_excel($_FILES['import_file']['tmp_name']);
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
     * Import block list from JSON.
     */
    private function import_json($file_path) {
        $file_content = file_get_contents($file_path);
        $block_list = json_decode($file_content, true);

        if (is_array($block_list)) {
            update_option('pluginmakersr_block_list', $block_list);
            add_action('admin_notices', function() {
                echo '<div class="notice notice-success"><p>Block list imported successfully!</p></div>';
            });
        } else {
            add_action('admin_notices', function() {
                echo '<div class="notice notice-error"><p>Invalid JSON file.</p></div>';
            });
        }
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
     * Import block list from Excel (CSV).
     */
    private function import_excel($file_path) {
        $block_list = [];
        $file = fopen($file_path, 'r');

        // Skip the header row
        fgetcsv($file);

        while (($data = fgetcsv($file)) !== false) {
            $block_list[] = [
                'type' => sanitize_text_field($data[0]),
                'value' => sanitize_text_field($data[1]),
            ];
        }

        fclose($file);

        if (!empty($block_list)) {
            update_option('pluginmakersr_block_list', $block_list);
            add_action('admin_notices', function() {
                echo '<div class="notice notice-success"><p>Block list imported successfully!</p></div>';
            });
        } else {
            add_action('admin_notices', function() {
                echo '<div class="notice notice-error"><p>Invalid CSV file.</p></div>';
            });
        }
    }

    /**
     * Render import/export buttons.
     */
    public static function render_buttons() {
        ?>
        <form method="POST" style="margin-bottom: 20px;">
            <button type="submit" name="export_json" class="button button-primary" style="background-color: green; color: white;">Export JSON</button>
            <button type="submit" name="export_excel" class="button button-primary" style="background-color: blue; color: white;">Export Excel</button>
            <input type="file" name="import_file" accept=".json,.csv" required>
            <button type="submit" name="import_json" class="button button-primary" style="background-color: orange; color: white;">Import JSON</button>
            <button type="submit" name="import_excel" class="button button-primary" style="background-color: red; color: white;">Import Excel</button>
        </form>
        <?php
    }
}