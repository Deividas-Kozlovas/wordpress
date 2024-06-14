// search.js

document.addEventListener('DOMContentLoaded', function () {
    // Function to perform case-insensitive search on the table
    function searchTable() {
        var input = document.getElementById('searchInput').value.toLowerCase();
        var category = document.getElementById('search-category').value.toLowerCase();
        var rows = document.querySelectorAll('#product_table tbody tr');

        for (var i = 0; i < rows.length; i++) {
            var productCell = rows[i].getElementsByTagName('td')[1]; // Assuming the product name is in the second column
            var categoryCell = rows[i].getElementsByTagName('td')[2]; // Assuming the category name is in the third column
            if (productCell && categoryCell) {
                var productName = productCell.textContent.toLowerCase();
                var categoryNames = categoryCell.textContent.toLowerCase().split(', ').map(function(name) { return name.trim(); });

                var productMatch = productName.indexOf(input) > -1 || input === "";
                var categoryMatch = category === "" || categoryNames.includes(category);

                if (productMatch && categoryMatch) {
                    rows[i].style.display = "";
                } else {
                    rows[i].style.display = "none";
                }
            }
        }
    }

    function clearSearch() {
        document.getElementById('searchInput').value = '';
        document.getElementById('search-category').value = '';
        searchTable();
    }

    document.getElementById('searchInput').addEventListener('input', searchTable);
    document.getElementById('search-category').addEventListener('change', searchTable);
    document.getElementById('clearSearch').addEventListener('click', clearSearch);
});
