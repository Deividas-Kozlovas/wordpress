// Function to perform case-insensitive search on the table
function searchTable() {
    // Get the input value and convert it to lowercase
    var input = document.getElementById('searchInput').value.toLowerCase();
    // Get the table rows
    var rows = document.querySelectorAll('#product_table tbody tr');
    // Loop through all table rows
    for (var i = 0; i < rows.length; i++) {
        var productCell = rows[i].getElementsByTagName('td')[2]; // Assuming the product name is in the third column
        if (productCell) {
            var productName = productCell.textContent.toLowerCase();
            // Check if the input value matches any part of the product name
            if (productName.indexOf(input) > -1) {
                // Show the row if the product name contains the search query
                rows[i].style.display = '';
            } else {
                // Hide the row if the product name does not contain the search query
                rows[i].style.display = 'none';
            }
        }
    }
}

// Add event listener for the input field
document.getElementById('searchInput').addEventListener('input', searchTable);
