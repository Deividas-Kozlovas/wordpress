jQuery(document).ready(function($) {
    $('#separately-button').on('click', function() {
        var selectedOrderIds = [];
        $('input[name="post[]"]:checked').each(function() {
            selectedOrderIds.push($(this).val());
        });

        if (selectedOrderIds.length === 0) {
            alert('Please select at least one order.');
            return;
        }

        $.ajax({
            url: ajax_object_separately.ajax_url,
            type: 'POST',
            data: {
                action: 'fetch_order_details_separately',
                order_ids: selectedOrderIds
            },
            success: function(response) {
                if (response.success) {
                    console.log("Response data:", response.data); // Debugging full response data

                    let newWindowContent = '<html><head><title>Print Orders</title><style>body { font-family: Arial, sans-serif; padding: 20px; } table { width: 100%; border-collapse: collapse; margin-bottom: 20px; } th, td { border: 1px solid #000; padding: 8px; text-align: left; } th { background-color: #f2f2f2; } .order-section { margin-bottom: 40px; padding-bottom: 20px; border-bottom: 2px solid #000; }</style></head><body>';
                    
                    response.data.forEach(function(order) {
                        newWindowContent += '<div class="order-section">';
                        newWindowContent += '<div><strong>Užsakymo ID: </strong>' + order.id + '</div>';
                        newWindowContent += '<div><strong>Data: </strong>' + order.date + '</div>';
                        newWindowContent += '<div><strong>Vardas: </strong>' + order.customer_name + '</div>';
                        newWindowContent += '<div><strong>El. paštas: </strong>' + order.customer_email + '</div>';
                        newWindowContent += '<div><strong>Telefonas: </strong>' + order.customer_phone + '</div>';

                        if (order.comments) {
                            newWindowContent += '<div><strong>Komentarai: </strong>' + order.comments + '</div>';
                        }

                        let categorizedItems = {};

                        order.items.forEach(function(item) {
                            let category = item.category;

                            if (!categorizedItems[category]) {
                                categorizedItems[category] = {};
                            }
                            if (!categorizedItems[category][item.name]) {
                                categorizedItems[category][item.name] = {};
                            }
                            if (!categorizedItems[category][item.name][item.attributes.size]) {
                                categorizedItems[category][item.name][item.attributes.size] = 0;
                            }
                            categorizedItems[category][item.name][item.attributes.size] += item.quantity;
                        });

                        for (let category in categorizedItems) {
                            newWindowContent += '<h2>' + category + '</h2>';
                            newWindowContent += '<table class="csv-order-table">';
                            newWindowContent += '<thead><tr><th>Prekės</th><th>Kiekis</th></tr></thead>';
                            newWindowContent += '<tbody>';

                            let sizeTotals = {};

                            for (let item_name in categorizedItems[category]) {
                                let sizes = categorizedItems[category][item_name];
                                let sizeQuantity = [];

                                for (let size in sizes) {
                                    let upperSize = size.toUpperCase();
                                    if (upperSize && /[A-Z]/.test(upperSize)) { // Check if size is not empty and contains letters
                                        sizeQuantity.push(sizes[size] + ' ' + upperSize);
                                        if (!sizeTotals[upperSize]) {
                                            sizeTotals[upperSize] = 0;
                                        }
                                        sizeTotals[upperSize] += sizes[size];
                                    } else {
                                        sizeQuantity.push(sizes[size]); // Add quantity without size
                                    }
                                }

                                newWindowContent += '<tr>';
                                newWindowContent += '<td>' + item_name + '</td>';
                                newWindowContent += '<td>' + sizeQuantity.join(', ') + '</td>';
                                newWindowContent += '</tr>';
                            }

                            let totalSizeQuantity = [];
                            for (let size in sizeTotals) {
                                totalSizeQuantity.push(sizeTotals[size] + ' ' + size.toUpperCase());
                            }

                            if (totalSizeQuantity.length > 0) {
                                newWindowContent += '<tr>';
                                newWindowContent += '<td><strong>Dydžių suma</strong></td>';
                                newWindowContent += '<td><strong>' + totalSizeQuantity.join(', ') + '</strong></td>';
                                newWindowContent += '</tr>';
                            }

                            newWindowContent += '</tbody></table>';
                        }

                        newWindowContent += '</div>'; // Close order section
                    });

                    newWindowContent += '</body></html>';

                    // Open the new content in a new tab
                    var newTab = window.open();
                    newTab.document.write(newWindowContent);
                    newTab.document.close();
                    newTab.focus();

                    // Update order statuses
                    $.ajax({
                        url: ajax_object_separately.ajax_url,
                        type: 'POST',
                        data: {
                            action: 'update_order_statuses',
                            order_ids: selectedOrderIds
                        },
                        success: function(response) {
                            if (response.success) {
                                console.log("Order statuses updated successfully.");
                                location.reload(); // Reload the page to make the effect visible
                            } else {
                                alert('Failed to update order statuses: ' + response.data);
                            }
                        },
                        error: function(xhr, status, error) {
                            alert('AJAX error: ' + error);
                        }
                    });
                } else {
                    alert('Failed to fetch order details: ' + response.data);
                }
            },
            error: function(xhr, status, error) {
                alert('AJAX error: ' + error);
            }
        });
    });
});
