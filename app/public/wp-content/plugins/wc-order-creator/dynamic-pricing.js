document.addEventListener('DOMContentLoaded', function () {
    const locationSelect = document.getElementById('selected_location');
    const table = document.getElementById('product_table');

    function updatePricesAndStockStatus() {
        let totalQty = 0;
        let totalCost = 0;

        // Store current quantities
        const quantities = {};
        table.querySelectorAll('tr[data-product-id]').forEach(row => {
            const productId = row.getAttribute('data-product-id');
            quantities[productId] = [];
            row.querySelectorAll('input[type="number"][name^="quantity[' + productId + ']"]').forEach((input, index) => {
                quantities[productId][index] = input.value;
            });
        });

        table.querySelectorAll('tr[data-product-id]').forEach(row => {
            const productId = row.getAttribute('data-product-id');
            const sizeSelect = row.querySelector('.product-size');
            const quantityInputs = row.querySelectorAll('input[type="number"][name^="quantity[' + productId + ']"]');
            const stockStatusCell = row.querySelector('.stock-status');
            const basePriceCell = row.querySelector('.product-base-price');
            const totalPriceCell = row.querySelector('.product-total-price');

            const selectedSizeSlug = sizeSelect ? sizeSelect.value : null;
            const selectedLocationSlug = locationSelect ? locationSelect.value : null;

            let isPriceAvailable = false;
            let isInStock = false;

            if (selectedSizeSlug && selectedLocationSlug) {
                isPriceAvailable = sizeLocationPrice[productId] &&
                                   sizeLocationPrice[productId][selectedSizeSlug] &&
                                   sizeLocationPrice[productId][selectedSizeSlug][selectedLocationSlug];
                isInStock = stockStatus[productId] &&
                            stockStatus[productId][selectedSizeSlug] &&
                            stockStatus[productId][selectedSizeSlug][selectedLocationSlug];
            } else if (selectedLocationSlug) {
                isPriceAvailable = sizeLocationPrice[productId][""] &&
                                   sizeLocationPrice[productId][""][selectedLocationSlug] !== undefined;
                isInStock = stockStatus[productId][""] &&
                            stockStatus[productId][""][selectedLocationSlug] !== undefined;
            }

            if (isInStock && isPriceAvailable) {
                stockStatusCell.innerHTML = '<input type="number" name="quantity[' + productId + '][]" min="0" value="0" style="width: 60px;">';
            } else {
                stockStatusCell.textContent = 'Nebeturime';
            }

            if (basePriceCell && totalPriceCell) {
                const basePrice = (selectedSizeSlug && selectedLocationSlug && isPriceAvailable)
                                ? parseFloat(sizeLocationPrice[productId][selectedSizeSlug][selectedLocationSlug])
                                : (selectedLocationSlug && isPriceAvailable)
                                ? parseFloat(sizeLocationPrice[productId][selectedLocationSlug])
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
                } else {
                    basePriceCell.textContent = 'Unavailable';
                    totalPriceCell.textContent = 'Unavailable';
                }
            }

            if (sizeSelect) {
                let existingSizeInput = row.querySelector('input[name="selected_attributes[' + productId + '][size][]"]');
                if (existingSizeInput) {
                    existingSizeInput.value = selectedSizeSlug;
                } else {
                    const sizeInput = document.createElement('input');
                    sizeInput.type = 'hidden';
                    sizeInput.name = 'selected_attributes[' + productId + '][size][]';
                    sizeInput.value = selectedSizeSlug;
                    row.appendChild(sizeInput);
                }
            }

            let existingLocationInput = row.querySelector('input[name="selected_attributes[' + productId + '][location][]"]');
            if (existingLocationInput) {
                existingLocationInput.value = selectedLocationSlug;
            } else {
                const locationInput = document.createElement('input');
                locationInput.type = 'hidden';
                locationInput.name = 'selected_attributes[' + productId + '][location][]';
                locationInput.value = selectedLocationSlug;
                row.appendChild(locationInput);
            }
        });

        // Restore previous quantities
        table.querySelectorAll('tr[data-product-id]').forEach(row => {
            const productId = row.getAttribute('data-product-id');
            if (quantities[productId]) {
                row.querySelectorAll('input[type="number"][name^="quantity[' + productId + ']"]').forEach((input, index) => {
                    if (quantities[productId][index] !== undefined) {
                        input.value = quantities[productId][index];
                    }
                });
            }
        });

        document.getElementById('total-quantity').textContent = totalQty;
        document.getElementById('total-price').textContent = totalCost.toFixed(2) + ' €';
    }

    function updateProductPrice(event) {
        const target = event.target;
        if (target.classList.contains('product-size') || target.id === 'selected_location') {
            updatePricesAndStockStatus();
        }
    }

    locationSelect.addEventListener('change', updatePricesAndStockStatus);
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

                const quantityInput = clonedRow.querySelector('input[type=number]');
                if (quantityInput) {
                    quantityInput.value = 0;
                }

                const minusButton = document.createElement('button');
                minusButton.classList.add('remove-product');
                minusButton.textContent = '-';
                minusButton.addEventListener('click', function() {
                    clonedRow.remove();
                    productRow.setAttribute('data-clone-count', --cloneCount);
                    updatePricesAndStockStatus();
                });

                const addButton = clonedRow.querySelector('.add-product');
                if (addButton) {
                    addButton.parentNode.replaceChild(minusButton, addButton);
                }

                productRow.parentNode.insertBefore(clonedRow, productRow.nextSibling);
                productRow.setAttribute('data-clone-count', ++cloneCount);
                updatePricesAndStockStatus();
            } else {
                alert('Daugiau pasirinkimų nėra.');
            }
        }
    });

    document.querySelector('form').addEventListener('submit', function(event) {
        const addedProducts = table.querySelectorAll('tr[data-product-id]');
        this.querySelectorAll('input[type=hidden]').forEach(input => input.remove());

        const processedProducts = new Set();
        let hasDuplicates = false;

        addedProducts.forEach(function(row) {
            const productId = row.getAttribute('data-product-id');
            const quantityInputs = row.querySelectorAll('input[type="number"][name^="quantity[' + productId + ']"]');
            const sizeSelect = row.querySelector('.product-size');
            const selectedLocationSlug = locationSelect.value;

            quantityInputs.forEach(quantityInput => {
                const quantity = parseInt(quantityInput.value) || 0;
                if (quantity > 0) {
                    const sizeValue = sizeSelect ? sizeSelect.value : '';
                    const productKey = `${productId}-${sizeValue}-${selectedLocationSlug}`;

                    if (processedProducts.has(productKey)) {
                        hasDuplicates = true;
                    } else {
                        processedProducts.add(productKey);
                    }

                    const quantityField = document.createElement('input');
                    quantityField.type = 'hidden';
                    quantityField.name = 'quantity[' + productId + '][]';
                    quantityField.value = quantity;
                    this.appendChild(quantityField);

                    if (sizeSelect) {
                        const selectedSizeSlug = sizeSelect.value;
                        const sizeField = document.createElement('input');
                        sizeField.type = 'hidden';
                        sizeField.name = 'selected_attributes[' + productId + '][size][]';
                        sizeField.value = selectedSizeSlug;
                        this.appendChild(sizeField);
                    }

                    const locationField = document.createElement('input');
                    locationField.type = 'hidden';
                    locationField.name = 'selected_attributes[' + productId + '][location][]';
                    locationField.value = selectedLocationSlug;
                    this.appendChild(locationField);
                }
            });
        }.bind(this));

        if (hasDuplicates) {
            alert("Užsakyme yra produktų su pasikartojančiais dydžiais.");
            event.preventDefault(); // Prevent form submission
        } else {
            this.submit();
        }
    });

    updatePricesAndStockStatus();
});
