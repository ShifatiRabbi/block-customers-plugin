<div class="wrap">
    <h1>Block Customers</h1>

    <?php
    PluginMakerSR_Block_Customers_Import_Export::render_buttons();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $block_list = new PluginMakerSR_Block_Customers_Block_List();

        if (isset($_POST['add_block'])) {
            $type = sanitize_text_field($_POST['block_type']);
            $value = '';

            if ($type === 'full_data') {
                $name = isset($_POST['full_data_name']) ? sanitize_text_field($_POST['full_data_name']) : '';
                $email = isset($_POST['full_data_email']) ? sanitize_text_field($_POST['full_data_email']) : '';
                $phone = isset($_POST['full_data_phone']) ? sanitize_text_field($_POST['full_data_phone']) : '';
                $street_address = isset($_POST['full_data_street_address']) ? sanitize_text_field($_POST['full_data_street_address']) : '';
                $zip = isset($_POST['full_data_zip']) ? sanitize_text_field($_POST['full_data_zip']) : '';

                $value = implode('|', array_filter([$name, $email, $phone, $street_address, $zip]));
            } else {
                $value = sanitize_text_field($_POST['block_value']);
            }

            if ($block_list->add_block($type, $value)) {
                echo '<div class="notice notice-success"><p>Block added successfully.</p></div>';
            } else {
                echo '
                <div class="notice notice-error">
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
            echo '<div class="notice notice-success"><p>Block removed successfully.</p></div>';
        }
    }

    $block_list = new PluginMakerSR_Block_Customers_Block_List();
    $blocks = $block_list->get_block_list();
    ?>

    <!-- Add Block Form -->
    <form method="POST" style="margin-bottom:20px; padding:20px; border: 1px solid #ddd; background-color: #f9f9f9;">
        <label for="block_type"><strong>Block Type:</strong></label>
        <select id="block_type" name="block_type" style="width: 200px; margin-bottom: 10px;">
            <option value="ip">IP Address</option>
            <option value="zip">ZIP Code</option>
            <option value="email">Email Address</option>
            <option value="name">Name</option>
            <option value="address">Street Address</option>
            <option value="zip_street">ZIP + Street Address</option>
            <option value="phone">Phone Number</option>
            <option value="full_data">Block by Full Data</option> <!-- New block type -->
        </select>

        <div id="instruction_text" style="margin-bottom: 20px;">
            <p><em>Select a block type to view instructions.</em></p>
        </div>

        <!-- Input fields for "Block by Full Data" -->
        <div id="full_data_fields" style="display: none;">
            <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                <input type="text" name="full_data_name" placeholder="Full Name" style="width: 20%;">
                <input type="text" name="full_data_email" placeholder="Email" style="width: 20%;">
                <input type="text" name="full_data_phone" placeholder="Phone" style="width: 20%;">
                <input type="text" name="full_data_street_address" placeholder="Street Address" style="width: 20%;">
                <input type="text" name="full_data_zip" placeholder="ZIP" style="width: 20%;">
            </div>
        </div>

        <!-- Default input field for other block types -->
        <div id="default_field">
            <input type="text" name="block_value" placeholder="Enter value to block" required style="width: 100%; padding: 8px; margin-bottom: 10px;">
        </div>

        <button type="submit" name="add_block" class="button button-primary" style="margin-top: 10px;">Add Block</button>
    </form>

    <br><br>

    <!-- Filter Section -->
    <div class="filter-section">
        <label for="filter_type"><strong>Filter by Type:</strong></label>
        <select id="filter_type">
            <option value="all">All</option>
            <option value="ip">IP Address</option>
            <option value="zip">ZIP Code</option>
            <option value="email">Email Address</option>
            <option value="name">Name</option>
            <option value="address">Street Address</option>
            <option value="zip_street">ZIP + Street Address</option>
            <option value="phone">Phone Number</option>
            <option value="full_data">Block by Full Data</option> <!-- New filter option -->
        </select>
        <button id="filter_button" class="button button-primary">Filter</button>
    </div>
    <br>

    <!-- Block List Table -->
    <div id="block_list_table">
        <?php if (!empty($blocks)) : ?>
            <?php foreach ($blocks as $index => $block) : ?>
                <?php if ($block['type'] === 'full_data') : ?>
                    <!-- Card layout for "Block by Full Data" -->
                    <div class="block-card" data-type="<?php echo esc_attr($block['type']); ?>">
                        <?php
                        $values = explode('|', $block['value']);
                        $labels = ['Full Name', 'Email', 'Phone', 'Street Address', 'ZIP'];
                        ?>
                        <?php foreach ($values as $i => $value) : ?>
                            <?php if (!empty($value)) : ?>
                                <div class="block-card-item">
                                    <strong><?php echo esc_html($labels[$i]); ?>:</strong> <?php echo esc_html($value); ?>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="block_index" value="<?php echo $index; ?>">
                            <button type="submit" name="remove_block" class="button button-secondary">Remove</button>
                        </form>
                    </div>
                <?php else : ?>
                    <!-- Default table row for other block types -->
                    <div class="block-row" data-type="<?php echo esc_attr($block['type']); ?>">
                        <div><?php echo esc_html($block['type']); ?></div>
                        <div><?php echo esc_html($block['value']); ?></div>
                        <div>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="block_index" value="<?php echo $index; ?>">
                                <button type="submit" name="remove_block" class="button button-secondary">Remove</button>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else : ?>
            <p>No blocks added yet.</p>
        <?php endif; ?>
    </div>
</div>

<script>
    document.getElementById("block_type").addEventListener("change", function() {
        var selectedType = this.value;
        var instructionText = "";
        var fullDataFields = document.getElementById("full_data_fields");
        var defaultField = document.getElementById("default_field");

        if (selectedType === "full_data") {
            fullDataFields.style.display = "block";
            defaultField.style.display = "none";
            instructionText = "<p><strong>Example:</strong> Enter all details to block a customer by full data.</p>";
        } else {
            fullDataFields.style.display = "none";
            defaultField.style.display = "block";
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
                case "phone":
                    instructionText = "<p><strong>Example:</strong> Enter the phone number you wish to block (e.g., +8801712345678 or 01712345678). This will block all orders from this specific phone number.</p>";
                    break;
                default:
                    instructionText = "<p><em>Please select a block type to view instructions.</em></p>";
            }
        }

        document.getElementById("instruction_text").innerHTML = instructionText;
    });

    // Filter functionality
    document.getElementById("filter_button").addEventListener("click", function() {
        var filterType = document.getElementById("filter_type").value;
        var blocks = document.querySelectorAll("#block_list_table .block-card, #block_list_table .block-row");

        blocks.forEach(function(block) {
            var blockType = block.getAttribute("data-type");
            if (filterType === "all" || blockType === filterType) {
                block.style.display = "";
            } else {
                block.style.display = "none";
            }
        });
    });
</script>