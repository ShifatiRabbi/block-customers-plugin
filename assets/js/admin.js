document.addEventListener("DOMContentLoaded", function() {
    // Filter functionality
    document.getElementById("filter_button").addEventListener("click", function() {
        var filterType = document.getElementById("filter_type").value;
        var rows = document.querySelectorAll("#block_list_table tr");

        rows.forEach(function(row) {
            var rowType = row.getAttribute("data-type");
            if (filterType === "all" || rowType === filterType) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });

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
});