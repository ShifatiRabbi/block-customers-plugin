<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class PluginMakerSR_Block_Customers_License {
    private $api_url = 'https://inovitstore.com/wp-json/block-customers/v1/';

    public function validate_license($license_key) {
        $response = wp_remote_post($this->api_url . 'validate_license', [
            'body' => [
                'license_key' => $license_key,
                'site_url' => home_url(),
            ],
        ]);

        if (is_wp_error($response)) {
            return [
                'success' => false,
                'message' => 'Failed to connect to the license server.',
            ];
        }

        $body = json_decode($response['body'], true);

        if ($body['success']) {
            update_option('pluginmakersr_license_key', $license_key);
            update_option('pluginmakersr_license_status', 'active');
            update_option('pluginmakersr_license_data', $body['data']);
        }

        return $body;
    }

    public function revoke_license() {
        $license_key = get_option('pluginmakersr_license_key', '');

        if (empty($license_key)) {
            return [
                'success' => false,
                'message' => 'No license key found.',
            ];
        }

        $response = wp_remote_post($this->api_url . 'revoke_license', [
            'body' => [
                'license_key' => $license_key,
                'site_url' => home_url(),
            ],
        ]);

        if (is_wp_error($response)) {
            return [
                'success' => false,
                'message' => 'Failed to connect to the license server.',
            ];
        }

        $body = json_decode($response['body'], true);

        if ($body['success']) {
            delete_option('pluginmakersr_license_key');
            update_option('pluginmakersr_license_status', 'inactive');
            delete_option('pluginmakersr_license_data');
        }

        return $body;
    }

    public function get_license_data() {
        return get_option('pluginmakersr_license_data', []);
    }
}