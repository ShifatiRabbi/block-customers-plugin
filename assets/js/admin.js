document.addEventListener('DOMContentLoaded', function () {
    const blockTypeSelector = document.getElementById('block_type');
    const instructionText = document.getElementById('instruction_text');

    if (blockTypeSelector) {
        blockTypeSelector.addEventListener('change', function () {
            let selectedType = blockTypeSelector.value;
            let instructionMessage = '';

            switch (selectedType) {
                case 'ip':
                    instructionMessage = '<p><strong>Example:</strong> Enter the IP address (e.g., 192.168.1.1).</p>';
                    break;
                case 'zip':
                    instructionMessage = '<p><strong>Example:</strong> Enter a ZIP code (e.g., 44001).</p>';
                    break;
                case 'email':
                    instructionMessage = '<p><strong>Example:</strong> Enter an email address (e.g., example@gmail.com).</p>';
                    break;
                case 'name':
                    instructionMessage = '<p><strong>Example:</strong> Enter a name without spaces (e.g., JohnDoe).</p>';
                    break;
                case 'address':
                    instructionMessage = '<p><strong>Example:</strong> Enter a street address (e.g., 123 Main St).</p>';
                    break;
                case 'zip_street':
                    instructionMessage = '<p><strong>Example:</strong> Enter ZIP + Address combined (e.g., 44001|123 Main St).</p>';
                    break;
                default:
                    instructionMessage = '<p><em>Please select a block type to view instructions.</em></p>';
            }

            instructionText.innerHTML = instructionMessage;
        });
    }
});
// Custom admin JavaScript
jQuery(document).ready(function($) {
    // Add any custom JS logic here
});