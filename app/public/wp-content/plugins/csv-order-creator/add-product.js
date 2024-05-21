document.addEventListener("DOMContentLoaded", function() {
    var addButtons = document.querySelectorAll(".add-product");
    addButtons.forEach(function(button) {
        button.addEventListener("click", function(event) {
            event.preventDefault(); // Prevent default form submission behavior
            // Get the product ID from the data-product-id attribute
            var productId = this.getAttribute("data-product-id");
            // Find the product row
            var productRow = this.closest("tr");
            // Clone the product row
            var clonedRow = productRow.cloneNode(true);
            // Reset the quantity input to 0
            var quantityInput = clonedRow.querySelector("input[type=number]");
            if (quantityInput) {
                quantityInput.value = 0;
            }
            // Replace the "+" button with a "-" button
            var minusButton = document.createElement("button");
            minusButton.classList.add("remove-product");
            minusButton.textContent = "-";
            minusButton.addEventListener("click", function() {
                // Remove the cloned row when the "-" button is clicked
                clonedRow.remove();
                // Call a function to update prices if needed
                updatePrices();
            });
            var addButton = clonedRow.querySelector(".add-product");
            if (addButton) {
                addButton.parentNode.replaceChild(minusButton, addButton);
            }
            // Append the cloned row after the original row
            productRow.parentNode.insertBefore(clonedRow, productRow.nextSibling);
        });
    });
});
