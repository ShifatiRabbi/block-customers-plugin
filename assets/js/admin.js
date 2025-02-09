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
});