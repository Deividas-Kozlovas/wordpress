jQuery(document).ready(function($) {
    $('#spauzdinti-button').on('click', function() {
        var selectedOrderIds = [];
        $('input[name="post[]"]:checked').each(function() {
            selectedOrderIds.push($(this).val());
        });

        if (selectedOrderIds.length === 0) {
            alert('Please select at least one order.');
            return;
        }

        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'fetch_order_details',
                order_ids: selectedOrderIds
            },
            success: function(response) {
                if (response.success) {
                    console.log("Response data:", response.data); // Debugging full response data

                    let categorizedOrders = {};
                    let orderDates = [];
                    let newWindowContent = '<html><head><title>Print Orders</title><style>body { font-family: Arial, sans-serif; padding: 20px; } table { width: 100%; border-collapse: collapse; margin-bottom: 20px; } th, td { border: 1px solid #000; padding: 8px; text-align: left; } th { background-color: #f2f2f2; }</style></head><body>';
                    response.data.forEach(function(order) {
                        orderDates.push(order.date); // Collect order dates
                        order.items.forEach(function(item) {
                            let category = item.category;

                            if (!categorizedOrders[category]) {
                                categorizedOrders[category] = {};
                            }
                            if (!categorizedOrders[category][item.name]) {
                                categorizedOrders[category][item.name] = {};
                            }
                            if (!categorizedOrders[category][item.name][item.attributes.size]) {
                                categorizedOrders[category][item.name][item.attributes.size] = 0;
                            }
                            categorizedOrders[category][item.name][item.attributes.size] += item.quantity;
                        });
                    });

                    // Sort order dates from earliest to latest
                    orderDates.sort(function(a, b) {
                        return new Date(a) - new Date(b);
                    });

                    // Add earliest and latest dates to the top of the content
                    let earliestDate = orderDates[0];
                    let latestDate = orderDates[orderDates.length - 1];
                    newWindowContent += '<div><strong>Užsakymų datos: </strong>' + earliestDate + ' / ' + latestDate + '</div>';

                    for (let category in categorizedOrders) {
                        newWindowContent += '<h2>' + category + '</h2>';
                        newWindowContent += '<table class="csv-order-table">';
                        newWindowContent += '<thead><tr><th>Prekės</th><th>Kiekis</th></tr></thead>';
                        newWindowContent += '<tbody>';

                        let sizeTotals = {};

                        for (let item_name in categorizedOrders[category]) {
                            let sizes = categorizedOrders[category][item_name];
                            let sizeQuantity = [];
                            for (let size in sizes) {
                                let upperSize = size.toUpperCase();
                                sizeQuantity.push(sizes[size] + '-' + upperSize);
                                if (!sizeTotals[upperSize]) {
                                    sizeTotals[upperSize] = 0;
                                }
                                sizeTotals[upperSize] += sizes[size];
                            }

                            newWindowContent += '<tr>';
                            newWindowContent += '<td>' + item_name + '</td>';
                            newWindowContent += '<td>' + sizeQuantity.join(', ') + '</td>';
                            newWindowContent += '</tr>';
                        }

                        let totalSizeQuantity = [];
                        for (let size in sizeTotals) {
                            totalSizeQuantity.push(sizeTotals[size] + '-' + size.toUpperCase());
                        }

                        newWindowContent += '<tr>';
                        newWindowContent += '<td><strong>Dydžių suma</strong></td>';
                        newWindowContent += '<td><strong>' + totalSizeQuantity.join(', ') + '</strong></td>';
                        newWindowContent += '</tr>';

                        newWindowContent += '</tbody>';
                        newWindowContent += '</table>';
                    }

                    // Add order IDs at the bottom of the content
                    newWindowContent += '<div><strong>Užsakymų IDs: </strong>' + selectedOrderIds.join(', ') + '</div>';

                    newWindowContent += '</body></html>';

                    // Open the new content in a new tab
                    var newTab = window.open();
                    newTab.document.write(newWindowContent);
                    newTab.document.close();
                    newTab.focus();

                    // Update order statuses
                    $.ajax({
                        url: ajax_object.ajax_url,
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
