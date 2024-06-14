// dynamic-pricing.js

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
            const quantityInputs = row.querySelectorAll('input[type="number"][name^="quantity[' + productId + ']"]');
            const basePriceCell = row.querySelector('.product-base-price');
            const totalPriceCell = row.querySelector('.product-total-price');

            if (!sizeSelect || !quantityInputs || !basePriceCell || !totalPriceCell) {
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
                let rowTotalQty = 0;
                let rowTotalPrice = 0;
                quantityInputs.forEach(quantityInput => {
                    const quantity = parseInt(quantityInput.value) || 0;
                    rowTotalQty += quantity;
                    rowTotalPrice += basePrice * quantity;
                });
                totalPriceCell.textContent = rowTotalQty > 0 ? rowTotalPrice.toFixed(2) + ' €' : '0 €';
                totalQty += rowTotalQty;
                totalCost += rowTotalPrice;

                // Update hidden input fields with selected attributes
                const sizeInput = document.createElement('input');
                sizeInput.type = 'hidden';
                sizeInput.name = 'selected_attributes[' + productId + '][size][]';
                sizeInput.value = selectedSizeSlug;
                row.appendChild(sizeInput);

                const locationInput = document.createElement('input');
                locationInput.type = 'hidden';
                locationInput.name = 'selected_attributes[' + productId + '][location][]';
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

    function updateStockStatus() {
        const rows = table.querySelectorAll('tr[data-product-id]');
        rows.forEach(row => {
            const productId = row.getAttribute('data-product-id');
            const sizeSelect = row.querySelector('.product-size');
            const stockStatusCell = row.querySelector('.stock-status');

            if (sizeSelect) {
                const size = sizeSelect.value;
                const location = locationSelect.value;
                const isPriceAvailable = sizeLocationPrice[productId] && 
                                         sizeLocationPrice[productId][size] &&
                                         sizeLocationPrice[productId][size][location];

                if (stockStatus[productId] && stockStatus[productId][size] && stockStatus[productId][size][location] !== undefined) {
                    if (stockStatus[productId][size][location] && isPriceAvailable) {
                        stockStatusCell.innerHTML = '<input type="number" name="quantity[' + productId + '][]" min="0" value="0" style="width: 60px;">';
                    } else {
                        stockStatusCell.textContent = 'Nebeturime';
                    }
                } else {
                    stockStatusCell.textContent = 'Nebeturime';
                }
            } else {
                const isPriceAvailable = sizeLocationPrice[productId];

                if (stockStatus[productId] && isPriceAvailable) {
                    stockStatusCell.innerHTML = '<input type="number" name="quantity[' + productId + '][]" min="0" value="0" style="width: 60px;">';
                } else {
                    stockStatusCell.textContent = 'Nebeturime';
                }
            }
        });
    }

    function updateProductPrice(event) {
        const target = event.target;
        if (target.classList.contains('product-size') || target.id === 'selected_location') {
            updatePrices();
            updateStockStatus();
        }
    }

    locationSelect.addEventListener('change', updatePrices);
    locationSelect.addEventListener('change', updateStockStatus);
    table.addEventListener('change', updateProductPrice);
    table.addEventListener('input', updateProductPrice);

    table.addEventListener('click', function(event) {
        const target = event.target;
        if (target.classList.contains('add-product')) {
            event.preventDefault();

            const productId = target.getAttribute('data-product-id');
            const productRow = target.closest('tr');
            const maxClones = parseInt(productRow.getAttribute('data-max-clones'), 10) || 0;
            let cloneCount = parseInt(productRow.getAttribute('data-clone-count'), 10) || 0;

            if (cloneCount < maxClones) {
                const clonedRow = productRow.cloneNode(true);

                // Reset the quantity input to 0
                const quantityInput = clonedRow.querySelector('input[type=number]');
                if (quantityInput) {
                    quantityInput.value = 0;
                }

                // Replace the "+" button with a "-" button
                const minusButton = document.createElement('button');
                minusButton.classList.add('remove-product');
                minusButton.textContent = '-';
                minusButton.addEventListener('click', function() {
                    clonedRow.remove();
                    productRow.setAttribute('data-clone-count', --cloneCount);
                    updatePrices();
                    updateStockStatus();
                });

                const addButton = clonedRow.querySelector('.add-product');
                if (addButton) {
                    addButton.parentNode.replaceChild(minusButton, addButton);
                }

                productRow.parentNode.insertBefore(clonedRow, productRow.nextSibling);
                productRow.setAttribute('data-clone-count', ++cloneCount);
                updatePrices();
                updateStockStatus();
            } else {
                alert('Daugiau pasirinkimų nėra.');
            }
        }
    });

    document.querySelector('form').addEventListener('submit', function(event) {
        const addedProducts = table.querySelectorAll('tr[data-product-id]');
        addedProducts.forEach(function(row) {
            const productId = row.getAttribute('data-product-id');
            const quantityInputs = row.querySelectorAll('input[type="number"][name^="quantity[' + productId + ']"]');
            const sizeSelect = row.querySelector('.product-size');
            const selectedLocationSlug = locationSelect.value;

            if (quantityInputs && sizeSelect) {
                quantityInputs.forEach(quantityInput => {
                    const quantity = parseInt(quantityInput.value) || 0;
                    const selectedSizeSlug = sizeSelect.value;

                    const quantityField = document.createElement('input');
                    quantityField.type = 'hidden';
                    quantityField.name = 'quantity[' + productId + '][]';
                    quantityField.value = quantity;
                    this.appendChild(quantityField);

                    const sizeField = document.createElement('input');
                    sizeField.type = 'hidden';
                    sizeField.name = 'selected_attributes[' + productId + '][size][]';
                    sizeField.value = selectedSizeSlug;
                    this.appendChild(sizeField);

                    const locationField = document.createElement('input');
                    locationField.type = 'hidden';
                    locationField.name = 'selected_attributes[' + productId + '][location][]';
                    locationField.value = selectedLocationSlug;
                    this.appendChild(locationField);
                });
            }
        }.bind(this));
    });

    updatePrices();
    updateStockStatus();
});
