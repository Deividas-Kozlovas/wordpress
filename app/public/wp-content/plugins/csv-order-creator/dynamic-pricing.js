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
            } else {
                basePriceCell.textContent = 'Unavailable';
                totalPriceCell.textContent = 'Unavailable';
            }
        });

        totalQuantity.textContent = totalQty;
        totalPrice.textContent = totalCost.toFixed(2) + ' €';
    }

    locationSelect.addEventListener('change', updatePrices);
    table.querySelectorAll('.product-size, input[type="number"]').forEach(element => {
        element.addEventListener('change', updatePrices);
        element.addEventListener('input', updatePrices);
    });

    updatePrices(); // Initial call to set prices based on default selections
});
