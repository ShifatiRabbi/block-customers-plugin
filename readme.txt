=== Plugin Name: Block Customers ===
Contributors: PluginMakerSR  
Tags: block customers, IP block, ZIP block, email block, WooCommerce block  
Requires at least: 5.0  
Tested up to: 6.3  
Stable tag: 1.6  
Requires PHP: 7.4  
License: GPL v2 or later  
License URI: https://www.gnu.org/licenses/gpl-2.0.html  

Block, suspend, or ban customers based on IP, ZIP code, email address, name, or combined ZIP + Street Address from placing orders. This plugin helps prevent spamming and unwanted orders.

== Description ==

The **Block Customers** plugin allows you to block specific customers from placing orders in WooCommerce based on:
- IP Address
- ZIP Code
- Email Address
- Name
- Street Address
- Combined ZIP + Street Address

Blocked customers can still visit your site but are restricted from completing any orders.

### Key Features:
- Simple admin interface for managing blocked entries.
- Supports multiple block types.
- Limits free version to 3 blocks (upgrade to premium for unlimited blocks).
- Displays custom error messages during checkout.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/pluginmakersr-block-customers` directory or install the plugin through the WordPress Plugins screen.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Go to **Block Customers** in the WordPress admin menu to configure your block settings.

== Usage ==

1. Navigate to the "Block Customers" admin page in your WordPress dashboard.
2. Add new blocks by selecting the type (IP, ZIP, etc.) and entering the value to block.
3. Manage your block list by removing or editing existing entries.
4. For unlimited block entries, upgrade to the premium version.

== Upgrade to Premium ==

Upgrade to the premium version to:
- Remove the 3-block limit.
- Unlock advanced features like bulk import/export.

Visit [our website](https://pluginmaker.3dimensionbd.com/product/33/) to learn more.

== Changelog ==

= 1.6 =
- Initial release.

== Support ==

For any issues or feature requests, contact us at [support@pluginmakersr.com](mailto:support@pluginmakersr.com).

1. Main Plugin File (pluginmakersr-block-customers.php)
This file will initialize the plugin and load the necessary classes.

2. Admin Class (includes/class-admin.php)
This file will handle the admin menu and page rendering.

3. Checkout Class (includes/class-checkout.php)
This file will handle the checkout validation logic.

4. Block List Class (includes/class-block-list.php)
This file will handle the block list management.

5. Admin Page Template (templates/admin-page.php)
This file will handle the HTML for the admin page.

6. Uninstall File (uninstall.php)
This file will handle cleanup when the plugin is uninstalled.

7. CSS File (assets/css/admin.css)
This file contains custom styles for the admin page.

8. JavaScript File (assets/js/admin.js)
This file contains custom JavaScript for the admin page.

9. Language File (languages/pluginmakersr-block-customers.pot)
This file is used for translations.