<!-- license-management.php -->
<div class="wrap">
    <h1>License Management</h1>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $license_manager = new PluginMakerSR_Block_Customers_License();

        if (isset($_POST['validate_license'])) {
            $license_key = sanitize_text_field($_POST['license_key']);
            $validation_result = $license_manager->validate_license($license_key);

            if ($validation_result['success']) {
                echo '<div class="notice notice-success"><p>License validated successfully!</p></div>';
            } else {
                echo '<div class="notice notice-error"><p>' . esc_html($validation_result['message']) . '</p></div>';
            }
        }

        if (isset($_POST['revoke_license'])) {
            $revocation_result = $license_manager->revoke_license();

            if ($revocation_result['success']) {
                echo '<div class="notice notice-success"><p>License revoked successfully!</p></div>';
            } else {
                echo '<div class="notice notice-error"><p>' . esc_html($revocation_result['message']) . '</p></div>';
            }
        }
    }

    $license_key = get_option('pluginmakersr_license_key', '');
    $license_status = get_option('pluginmakersr_license_status', 'inactive');
    $license_data = get_option('pluginmakersr_license_data', []);
    ?>

    <div class="license-info">
        <p><strong>License Status:</strong> <?php echo esc_html($license_status === 'active' ? 'Active' : 'Inactive'); ?></p>
        <p><strong>License Key:</strong> <?php echo esc_html($license_key); ?></p>
        <?php if (!empty($license_data)) : ?>
            <p><strong>Remaining Days:</strong> <?php echo esc_html($license_data['remaining_days']); ?></p>
            <p><strong>Remaining Websites:</strong> <?php echo esc_html($license_data['remaining_websites']); ?></p>
        <?php endif; ?>
    </div>

    <form method="POST" class="license-form">
        <label for="license_key"><strong>License Key:</strong></label>
        <input type="text" name="license_key" id="license_key" value="<?php echo esc_attr($license_key); ?>" required>
        <button type="submit" name="validate_license" class="button button-primary">Validate License</button>
    </form>
    <br>
    <form method="POST" class="license-form">
        <button type="submit" name="revoke_license" class="button button-secondary">Revoke License</button>
    </form>
    <br>
</div>