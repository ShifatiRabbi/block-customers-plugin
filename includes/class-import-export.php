<!-- class-import-export.php -->
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

    /**
     * Render import/export buttons and collapsible sections.
     */
    public static function render_buttons() {
        ?>
        <style>
            .collapsible {
                background-color: #f1f1f1;
                color: #333;
                cursor: pointer;
                padding: 10px;
                width: 100%;
                border: none;
                text-align: left;
                outline: none;
                font-size: 15px;
                margin-bottom: 10px;
            }

            .collapsible:hover {
                background-color: #ddd;
            }

            .content {
                padding: 10px;
                display: none;
                overflow: hidden;
                background-color: #f9f9f9;
                border: 1px solid #ddd;
                margin-bottom: 10px;
            }
        </style>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var coll = document.getElementsByClassName('collapsible');
                for (var i = 0; i < coll.length; i++) {
                    coll[i].addEventListener('click', function() {
                        this.classList.toggle('active');
                        var content = this.nextElementSibling;
                        if (content.style.display === 'block') {
                            content.style.display = 'none';
                        } else {
                            content.style.display = 'block';
                        }
                    });
                }
            });
        </script>

        <button type="button" class="collapsible" style="background-color: #FF0000; width: 30%; text-align: center; font-weight: bold;">Export</button>
        <div class="content">
            <form method="POST" style="margin-bottom: 10px;">
                <button type="submit" name="export_json" class="button button-primary" style="background-color: #FFA500; color: white;">Export as JSON</button>
                <button type="submit" name="export_excel" class="button button-primary" style="background-color: #008000; color: white;">Export as Excel</button>
            </form>
        </div>
        <br>
        <button type="button" class="collapsible" style="color: #FFFFFF; background-color: #0000FF; width: 30%;  text-align: center; font-weight: bold;">Import</button>
        <div class="content">
            <form method="POST" enctype="multipart/form-data" style="margin-bottom: 10px;">
                <h4>Import as JSON</h4>
                <input type="file" name="import_file_json" accept=".json" required>
                <button type="submit" name="import_json" class="button button-primary" style="background-color: #FFA500; color: white;">Import JSON</button>
            </form>

            <form method="POST" enctype="multipart/form-data" style="margin-bottom: 10px;">
                <h4>Import as Excel</h4>
                <input type="file" name="import_file_excel" accept=".csv" required>
                <button type="submit" name="import_excel" class="button button-primary" style="background-color: #008000; color: white;">Import Excel</button>
            </form>
        </div>
        <?php
    }
}