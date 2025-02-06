<div class="wrap"><h1>Block Customers</h1>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $block_list = new PluginMakerSR_Block_Customers_Block_List();

    if (isset($_POST['add_block'])) {
        $type = sanitize_text_field($_POST['block_type']);
        $value = sanitize_text_field($_POST['block_value']);

        if ($block_list->add_block($type, $value)) {
            echo '<div class="updated"><p>Block added successfully.</p></div>';
        } else {
            echo '
            <div class="error" style="background-color: red; font-size: 16px !important; font-weight: 600; color: white;">
                <p>Max block entries reached.
                    <button style="background-color: white; padding: 10px 15px;"> 
                        <a href="https://pluginmaker.3dimensionbd.com/product/33/" target="_blank" style="text-decoration: none; color: black; padding: 10px 15px;">Please buy the premium pack</a>
                    </button> to add unlimited entries.
                </p>
            </div>';
        }
    }

    if (isset($_POST['remove_block'])) {
        $index = intval($_POST['block_index']);
        $block_list->remove_block($index);
        echo '<div class="updated"><p>Block removed successfully.</p></div>';
    }
}

$block_list = new PluginMakerSR_Block_Customers_Block_List();
$blocks = $block_list->get_block_list();
?>

<form method="POST" style="margin-bottom:20px; padding:20px; border: 1px solid #ddd; background-color: #f9f9f9;">
    <label for="block_type"><strong>Block Type:</strong></label>
    <select id="block_type" name="block_type" style="width: 200px; margin-bottom: 10px;">
        <option value="ip">IP Address</option>
        <option value="zip">ZIP Code</option>
        <option value="email">Email Address</option>
        <option value="name">Name</option>
        <option value="address">Street Address</option>
        <option value="zip_street">ZIP + Street Address</option>
    </select>

    <div id="instruction_text" style="margin-bottom: 20px;">
        <p><em>Select a block type to view instructions.</em></p>
    </div>

    <input type="text" name="block_value" placeholder="Enter value to block" required style="width: 100%; padding: 8px; margin-bottom: 10px;">
    <button type="submit" name="add_block" class="button button-primary" style="margin-top: 10px;">Add Block</button>
</form>

<script>
    document.getElementById("block_type").addEventListener("change", function() {
        var selectedType = this.value;
        var instructionText = "";
        switch (selectedType) {
            case "ip":
                instructionText = "<p><strong>Example:</strong> Enter the IP address you wish to block (e.g., 192.168.1.1). This will block all orders from this specific IP.</p>";
                break;
            case "zip":
                instructionText = "<p><strong>Example:</strong> Enter the ZIP code to block customers from a specific region (e.g., 44001). This will block all customers from that area.</p>";
                break;
            case "email":
                instructionText = "<p><strong>Example:</strong> Enter the email address you wish to block (e.g., example@gmail.com). This will block any user with that email address.</p>";
                break;
            case "name":
                instructionText = "<p><strong>Example:</strong> Enter the combined name without spaces (e.g., MatthewJohn). This will block the user with that exact name combination.</p>";
                break;
            case "address":
                instructionText = "<p><strong>Example:</strong> Enter the street address (e.g., 123 Main St). The address should be normalized without spaces or commas.</p>";
                break;
            case "zip_street":
                instructionText = "<p><strong>Example:</strong> Enter both ZIP and Street Address combined (e.g., 44001|123 Main St). This will block customers matching both ZIP and street address.</p>";
                break;
            default:
                instructionText = "<p><em>Please select a block type to view instructions.</em></p>";
        }
        document.getElementById("instruction_text").innerHTML = instructionText;
    });
</script>

<?php
if (!empty($blocks)) {
    echo '<table class="widefat fixed"><thead><tr><th>Type</th><th>Value</th><th>Action</th></tr></thead><tbody>';
    foreach ($blocks as $index => $block) {
        echo '<tr><td>' . esc_html($block['type']) . '</td><td>' . esc_html($block['value']) . '</td>';
        echo '<td><form method="POST" style="display:inline;"><input type="hidden" name="block_index" value="' . $index . '">';
        echo '<button type="submit" name="remove_block" class="button button-secondary">Remove</button>';
        echo '</form></td></tr>';
    }
    echo '</tbody></table>';
} else {
    echo '<p>No blocks added yet.</p>';
}
?>
</div>