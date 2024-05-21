document.addEventListener('DOMContentLoaded', function () {
    const locationSelect = document.getElementById('selected_location');
    const table = document.getElementById('product_table');
    const totalQuantity = document.getElementById('total-quantity');
    const totalPrice = document.getElementById('total-price');

    function updatePrices() {
        let totalQty = 0;
        let totalCost = 0;

        table.querySelectorAll('tr[data-product-id]').forEach(row => {
            const productId = row.getAttribute('data-product-id');
            const sizeSelect = row.querySelector('.product-size');
            const quantityInput = row.querySelector('input[type="number"][name="quantity[' + productId + ']"]');
            const basePriceCell = row.querySelector('.product-base-price');
            const totalPriceCell = row.querySelector('.product-total-price');

            if (!sizeSelect || !quantityInput || !basePriceCell || !totalPriceCell) {
                console.error('Essential elements are missing from the row:', productId);
                return;
            }

            const selectedSizeSlug = sizeSelect.value;
            const selectedLocationSlug = locationSelect.value;
            const basePrice = sizeLocationPrice[productId] &&
                              sizeLocationPrice[productId][selectedSizeSlug] &&
                              sizeLocationPrice[productId][selectedSizeSlug][selectedLocationSlug]
                              ? parseFloat(sizeLocationPrice[productId][selectedSizeSlug][selectedLocationSlug])
                              : 'Unavailable';

            if (basePrice !== 'Unavailable') {
                basePriceCell.textContent = basePrice.toFixed(2) + ' €';
                const quantity = parseInt(quantityInput.value) || 0;
                const totalPrice = (basePrice * quantity).toFixed(2);
                totalPriceCell.textContent = quantity > 0 ? totalPrice + ' €' : '0 €';
                totalQty += quantity;
                totalCost += parseFloat(totalPrice);

                // Update hidden input fields with selected attributes
                const sizeInput = document.createElement('input');
                sizeInput.type = 'hidden';
                sizeInput.name = 'selected_attributes[' + productId + '][size]';
                sizeInput.value = selectedSizeSlug;
                row.appendChild(sizeInput);

                const locationInput = document.createElement('input');
                locationInput.type = 'hidden';
                locationInput.name = 'selected_attributes[' + productId + '][location]';
                locationInput.value = selectedLocationSlug;
                row.appendChild(locationInput);
            } else {
                basePriceCell.textContent = 'Unavailable';
                totalPriceCell.textContent = 'Unavailable';
            }
        });

        totalQuantity.textContent = totalQty;
        totalPrice.textContent = totalCost.toFixed(2) + ' €';
    }

    function updateProductPrice(event) {
        const target = event.target;
        if (target.classList.contains('add-product') || target.classList.contains('product-size') || target.type === 'number') {
            updatePrices();
        }
    }

    locationSelect.addEventListener('change', updatePrices);
    table.addEventListener('change', updateProductPrice);
    table.addEventListener('input', updateProductPrice);

    // Add event listeners for dynamically added products
    table.addEventListener('click', function(event) {
        const target = event.target;
        if (target.classList.contains('add-product')) {
            updatePrices(); // Update prices when a new product is added
        }
    });

    // Intercept form submission to include added products
    document.querySelector('form').addEventListener('submit', function(event) {
        const addedProducts = table.querySelectorAll('.add-product');
        addedProducts.forEach(function(button) {
            const productId = button.getAttribute('data-product-id');
            const quantityInput = document.querySelector('input[type="number"][name="quantity[' + productId + ']"]');
            const sizeSelect = document.querySelector('.product-size[name="size[' + productId + ']"]');
            const locationSelect = document.getElementById('selected_location');
            if (quantityInput && sizeSelect && locationSelect) {
                const quantity = parseInt(quantityInput.value) || 0;
                const selectedSizeSlug = sizeSelect.value;
                const selectedLocationSlug = locationSelect.value;
                const attributesField = document.createElement('input');
                attributesField.type = 'hidden';
                attributesField.name = 'quantity[' + productId + ']';
                attributesField.value = quantity;
                this.appendChild(attributesField);

                const sizeField = document.createElement('input');
                sizeField.type = 'hidden';
                sizeField.name = 'selected_attributes[' + productId + '][size]';
                sizeField.value = selectedSizeSlug;
                this.appendChild(sizeField);

                const locationField = document.createElement('input');
                locationField.type = 'hidden';
                locationField.name = 'selected_attributes[' + productId + '][location]';
                locationField.value = selectedLocationSlug;
                this.appendChild(locationField);
            }
        });
    });

    updatePrices(); // Initial call to set prices based on default selections
});
