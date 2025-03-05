<!-- import-export.php -->
<?php
if (!defined('ABSPATH')) {
    exit; // Prevent direct access
}

// Check license status
$license_status = get_option('pluginmakersr_license_status', 'inactive');
$is_license_active = ($license_status === 'active');
?>

<div class="wrap">
    <h1>Import/Export Block List</h1>

    <?php if (!$is_license_active) : ?>
        <div class="notice notice-warning">
            <p>You are using the free version. <a href="<?php echo admin_url('admin.php?page=pluginmakersr-block-customers-license'); ?>">Activate your license</a> to unlock all features.</p>
        </div>
    <?php endif; ?>

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

        .disabled-settings {
            opacity: 0.5;
            pointer-events: none;
            cursor: not-allowed;
        }

        .disabled-settings:hover::after {
            content: "Buy premium to get full access";
            position: absolute;
            background: #000;
            color: #fff;
            padding: 5px;
            border-radius: 3px;
            margin-top: 5px;
            font-size: 12px;
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
            <button type="submit" name="export_json" class="button button-primary <?php echo !$is_license_active ? 'disabled-settings' : ''; ?>" style="background-color: #FFA500; color: white;" <?php echo !$is_license_active ? 'disabled' : ''; ?>>Export as JSON</button>
            <button type="submit" name="export_excel" class="button button-primary <?php echo !$is_license_active ? 'disabled-settings' : ''; ?>" style="background-color: #008000; color: white;" <?php echo !$is_license_active ? 'disabled' : ''; ?>>Export as Excel</button>
        </form>
    </div>
    <br>
    <button type="button" class="collapsible" style="color: #FFFFFF; background-color: #0000FF; width: 30%;  text-align: center; font-weight: bold;">Import</button>
    <div class="content">
        <form method="POST" enctype="multipart/form-data" style="margin-bottom: 10px;">
            <h4>Import as JSON</h4>
            <input type="file" name="import_file_json" accept=".json" <?php echo !$is_license_active ? 'disabled' : ''; ?> class="<?php echo !$is_license_active ? 'disabled-settings' : ''; ?>">
            <button type="submit" name="import_json" class="button button-primary <?php echo !$is_license_active ? 'disabled-settings' : ''; ?>" style="background-color: #FFA500; color: white;" <?php echo !$is_license_active ? 'disabled' : ''; ?>>Import JSON</button>
        </form>

        <form method="POST" enctype="multipart/form-data" style="margin-bottom: 10px;">
            <h4>Import as Excel</h4>
            <input type="file" name="import_file_excel" accept=".csv" <?php echo !$is_license_active ? 'disabled' : ''; ?> class="<?php echo !$is_license_active ? 'disabled-settings' : ''; ?>">
            <button type="submit" name="import_excel" class="button button-primary <?php echo !$is_license_active ? 'disabled-settings' : ''; ?>" style="background-color: #008000; color: white;" <?php echo !$is_license_active ? 'disabled' : ''; ?>>Import Excel</button>
        </form>
    </div>
</div>