<!-- settings-page.php -->
<?php
if (!defined('ABSPATH')) {
    exit; // Prevent direct access
}

// Check license status
$license_status = get_option('pluginmakersr_license_status', 'inactive');
$is_license_active = ($license_status === 'active');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $is_license_active) {
    // Save billing settings
    update_option('pluginmakersr_billing_name_field_type', sanitize_text_field($_POST['billing_name_field_type']));
    update_option('pluginmakersr_billing_name_custom_field', sanitize_text_field($_POST['billing_name_custom_field']));
    update_option('pluginmakersr_billing_email_field_type', sanitize_text_field($_POST['billing_email_field_type']));
    update_option('pluginmakersr_billing_email_custom_field', sanitize_text_field($_POST['billing_email_custom_field']));
    update_option('pluginmakersr_billing_zip_field_type', sanitize_text_field($_POST['billing_zip_field_type']));
    update_option('pluginmakersr_billing_zip_custom_field', sanitize_text_field($_POST['billing_zip_custom_field']));
    update_option('pluginmakersr_billing_address_field_type', sanitize_text_field($_POST['billing_address_field_type']));
    update_option('pluginmakersr_billing_address_custom_field', sanitize_text_field($_POST['billing_address_custom_field']));

    // Save shipping settings
    update_option('pluginmakersr_shipping_name_field_type', sanitize_text_field($_POST['shipping_name_field_type']));
    update_option('pluginmakersr_shipping_name_custom_field', sanitize_text_field($_POST['shipping_name_custom_field']));
    update_option('pluginmakersr_shipping_zip_field_type', sanitize_text_field($_POST['shipping_zip_field_type']));
    update_option('pluginmakersr_shipping_zip_custom_field', sanitize_text_field($_POST['shipping_zip_custom_field']));
    update_option('pluginmakersr_shipping_address_field_type', sanitize_text_field($_POST['shipping_address_field_type']));
    update_option('pluginmakersr_shipping_address_custom_field', sanitize_text_field($_POST['shipping_address_custom_field']));

    echo '<div class="notice notice-success"><p>Settings saved successfully.</p></div>';
}

// Retrieve billing settings
$billing_name_field_type = get_option('pluginmakersr_billing_name_field_type', 'default');
$billing_name_custom_field = get_option('pluginmakersr_billing_name_custom_field', 'billing_full_name');
$billing_email_field_type = get_option('pluginmakersr_billing_email_field_type', 'default');
$billing_email_custom_field = get_option('pluginmakersr_billing_email_custom_field', 'billing_email');
$billing_zip_field_type = get_option('pluginmakersr_billing_zip_field_type', 'default');
$billing_zip_custom_field = get_option('pluginmakersr_billing_zip_custom_field', 'billing_postcode');
$billing_address_field_type = get_option('pluginmakersr_billing_address_field_type', 'default');
$billing_address_custom_field = get_option('pluginmakersr_billing_address_custom_field', 'billing_address_1');

// Retrieve shipping settings
$shipping_name_field_type = get_option('pluginmakersr_shipping_name_field_type', 'default');
$shipping_name_custom_field = get_option('pluginmakersr_shipping_name_custom_field', 'shipping_full_name');
$shipping_zip_field_type = get_option('pluginmakersr_shipping_zip_field_type', 'default');
$shipping_zip_custom_field = get_option('pluginmakersr_shipping_zip_custom_field', 'shipping_postcode');
$shipping_address_field_type = get_option('pluginmakersr_shipping_address_field_type', 'default');
$shipping_address_custom_field = get_option('pluginmakersr_shipping_address_custom_field', 'shipping_address_1');

?>
<div class="wrap">
    <h1>Settings</h1>
    <?php if (!$is_license_active) : ?>
        <div class="notice notice-warning">
            <p>You are using the free version. <a href="<?php echo admin_url('admin.php?page=pluginmakersr-block-customers-license'); ?>">Activate your license</a> to unlock all features.</p>
        </div>
    <?php endif; ?>
    <form method="POST">
        <h2>Billing Details</h2>
        <table class="form-table">
            <tr>
                <th scope="row">Name Field</th>
                <td>
                    <label>
                        <input type="radio" name="billing_name_field_type" value="default" <?php checked($billing_name_field_type, 'default'); ?> <?php echo !$is_license_active ? 'disabled' : ''; ?>> Default
                    </label>
                    <label>
                        <input type="radio" name="billing_name_field_type" value="custom" <?php checked($billing_name_field_type, 'custom'); ?> <?php echo !$is_license_active ? 'disabled' : ''; ?>> Custom
                    </label>
                    <input type="text" name="billing_name_custom_field" value="<?php echo esc_attr($billing_name_custom_field); ?>" <?php echo ($billing_name_field_type === 'default' || !$is_license_active) ? 'disabled' : ''; ?>>
                </td>
            </tr>
            <tr>
                <th scope="row">Email Field</th>
                <td>
                    <label>
                        <input type="radio" name="billing_email_field_type" value="default" <?php checked($billing_email_field_type, 'default'); ?> <?php echo !$is_license_active ? 'disabled' : ''; ?>> Default
                    </label>
                    <label>
                        <input type="radio" name="billing_email_field_type" value="custom" <?php checked($billing_email_field_type, 'custom'); ?> <?php echo !$is_license_active ? 'disabled' : ''; ?>> Custom
                    </label>
                    <input type="text" name="billing_email_custom_field" value="<?php echo esc_attr($billing_email_custom_field); ?>" <?php echo ($billing_email_field_type === 'default' || !$is_license_active) ? 'disabled' : ''; ?>>
                </td>
            </tr>
            <tr>
                <th scope="row">ZIP Code Field</th>
                <td>
                    <label>
                        <input type="radio" name="billing_zip_field_type" value="default" <?php checked($billing_zip_field_type, 'default'); ?> <?php echo !$is_license_active ? 'disabled' : ''; ?>> Default
                    </label>
                    <label>
                        <input type="radio" name="billing_zip_field_type" value="custom" <?php checked($billing_zip_field_type, 'custom'); ?> <?php echo !$is_license_active ? 'disabled' : ''; ?>> Custom
                    </label>
                    <input type="text" name="billing_zip_custom_field" value="<?php echo esc_attr($billing_zip_custom_field); ?>" <?php echo ($billing_zip_field_type === 'default' || !$is_license_active) ? 'disabled' : ''; ?>>
                </td>
            </tr>
            <tr>
                <th scope="row">Address Field</th>
                <td>
                    <label>
                        <input type="radio" name="billing_address_field_type" value="default" <?php checked($billing_address_field_type, 'default'); ?> <?php echo !$is_license_active ? 'disabled' : ''; ?>> Default
                    </label>
                    <label>
                        <input type="radio" name="billing_address_field_type" value="custom" <?php checked($billing_address_field_type, 'custom'); ?> <?php echo !$is_license_active ? 'disabled' : ''; ?>> Custom
                    </label>
                    <input type="text" name="billing_address_custom_field" value="<?php echo esc_attr($billing_address_custom_field); ?>" <?php echo ($billing_address_field_type === 'default' || !$is_license_active) ? 'disabled' : ''; ?>>
                </td>
            </tr>
        </table>

        <h2>Shipping Details</h2>
        <table class="form-table">
            <tr>
                <th scope="row">Name Field</th>
                <td>
                    <label>
                        <input type="radio" name="shipping_name_field_type" value="default" <?php checked($shipping_name_field_type, 'default'); ?>> <?php echo !$is_license_active ? 'disabled' : ''; ?>> Default
                    </label>
                    <label>
                        <input type="radio" name="shipping_name_field_type" value="custom" <?php checked($shipping_name_field_type, 'custom'); ?>> <?php echo !$is_license_active ? 'disabled' : ''; ?>> Custom
                    </label>
                    <input type="text" name="shipping_name_custom_field" value="<?php echo esc_attr($shipping_name_custom_field); ?>" <?php echo ($shipping_name_field_type === 'default' || !$is_license_active) ? 'disabled' : ''; ?>>
                </td>
            </tr>
            <tr>
                <th scope="row">ZIP Code Field</th>
                <td>
                    <label>
                        <input type="radio" name="shipping_zip_field_type" value="default" <?php checked($shipping_zip_field_type, 'default'); ?>> <?php echo !$is_license_active ? 'disabled' : ''; ?>> Default
                    </label>
                    <label>
                        <input type="radio" name="shipping_zip_field_type" value="custom" <?php checked($shipping_zip_field_type, 'custom'); ?>> <?php echo !$is_license_active ? 'disabled' : ''; ?>> Custom
                    </label>
                    <input type="text" name="shipping_zip_custom_field" value="<?php echo esc_attr($shipping_zip_custom_field); ?>" <?php echo ($shipping_zip_field_type === 'default' || !$is_license_active) ? 'disabled' : ''; ?>>
                </td>
            </tr>
            <tr>
                <th scope="row">Address Field</th>
                <td>
                    <label>
                        <input type="radio" name="shipping_address_field_type" value="default" <?php checked($shipping_address_field_type, 'default'); ?>> <?php echo !$is_license_active ? 'disabled' : ''; ?>> Default
                    </label>
                    <label>
                        <input type="radio" name="shipping_address_field_type" value="custom" <?php checked($shipping_address_field_type, 'custom'); ?>> <?php echo !$is_license_active ? 'disabled' : ''; ?>> Custom
                    </label>
                    <input type="text" name="shipping_address_custom_field" value="<?php echo esc_attr($shipping_address_custom_field); ?>" <?php echo ($shipping_address_field_type === 'default' || !$is_license_active) ? 'disabled' : ''; ?>>
                </td>
            </tr>
        </table>

        <button type="submit" class="button button-primary" <?php echo !$is_license_active ? 'disabled' : ''; ?>>Save Settings</button>
    </form>
</div>


<style>
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
    document.addEventListener("DOMContentLoaded", function() {
        // Enable/disable custom fields based on radio button selection
        function toggleCustomField(radio, customField) {
            if (radio.value === 'custom') {
                customField.disabled = false;
            } else {
                customField.disabled = true;
            }
        }

        // Billing fields
        document.querySelectorAll('input[name="billing_name_field_type"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                toggleCustomField(radio, document.querySelector('input[name="billing_name_custom_field"]'));
            });
        });

        document.querySelectorAll('input[name="billing_email_field_type"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                toggleCustomField(radio, document.querySelector('input[name="billing_email_custom_field"]'));
            });
        });

        document.querySelectorAll('input[name="billing_zip_field_type"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                toggleCustomField(radio, document.querySelector('input[name="billing_zip_custom_field"]'));
            });
        });

        document.querySelectorAll('input[name="billing_address_field_type"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                toggleCustomField(radio, document.querySelector('input[name="billing_address_custom_field"]'));
            });
        });

        // Shipping fields
        document.querySelectorAll('input[name="shipping_name_field_type"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                toggleCustomField(radio, document.querySelector('input[name="shipping_name_custom_field"]'));
            });
        });

        document.querySelectorAll('input[name="shipping_zip_field_type"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                toggleCustomField(radio, document.querySelector('input[name="shipping_zip_custom_field"]'));
            });
        });

        document.querySelectorAll('input[name="shipping_address_field_type"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                toggleCustomField(radio, document.querySelector('input[name="shipping_address_custom_field"]'));
            });
        });
    });
</script>

