jQuery(document).ready(function($) {
    $('#together-button').on('click', function() {
        var selectedOrderIds = [];
        $('input[name="post[]"]:checked').each(function() {
            selectedOrderIds.push($(this).val());
        });

        if (selectedOrderIds.length === 0) {
            alert('Please select at least one order.');
            return;
        }

        $.ajax({
            url: ajax_object_together.ajax_url,
            type: 'POST',
            data: {
                action: 'fetch_order_details_bendrai',
                order_ids: selectedOrderIds
            },
            success: function(response) {
                if (response.success) {
                    let categorizedOrders = {}; 
                    let orderDates = [];
                    let specialOrdersContent = '';
                    let normalOrdersContent = '<html><head><title>Print Orders</title><style>body { font-family: Arial, sans-serif; padding: 20px; } table { width: 100%; border-collapse: collapse; margin-bottom: 20px; } th, td { border: 1px solid #000; padding: 8px; text-align: left; } th { background-color: #f2f2f2; } .order-section { margin-bottom: 40px; padding-bottom: 20px; border-bottom: 2px solid #000; }</style></head><body>';

                    response.data.forEach(function(order) {
                        orderDates.push(order.date); // Collect order dates

                        let hasSpecialOrder = order.items.some(item => item.special_order_text || item.special_order_files);
                        let hasComment = order.comments && order.comments.trim() !== '';
                        let hasOrderDateMeta = order.order_date_meta && order.order_date_meta.trim() !== '';

                        // Skip orders that only have "Užsakymo ID" and "Data"
                        if (!hasSpecialOrder && !hasComment && !hasOrderDateMeta) {
                            return;
                        }

                        let orderContent = '<div class="order-section">';
                        orderContent += '<div><strong>Užsakymo ID: </strong>' + order.id + '</div>';
                        if (order.order_date_meta) {
                            orderContent += '<div><strong>Pagaminti iki: </strong>' + order.order_date_meta + '</div>';
                        } else {
                            orderContent += '<div><strong>Data: </strong>' + order.date + '</div>';
                        }
                        if (order.comments) {
                            orderContent += '<div><strong>Komentarai: </strong>' + order.comments + '</div>';
                        }

                        order.items.forEach(function(item) {
                            // Add special order details if present
                            if (item.special_order_text || item.special_order_files) {
                                orderContent += '<div><strong>Specialus užsakymas: ' + item.name + '</strong><br>';
                                if (item.special_order_text) {
                                    orderContent += '<div><strong>Papildoma informacija: </strong>' + item.special_order_text + '</div>';
                                }
                                if (item.special_order_files) {
                                    orderContent += '<div><strong>Pridėtos nuotraukos: </strong><br>';
                                    let fileLinks = Array.isArray(item.special_order_files) ? item.special_order_files : item.special_order_files.split(', ');
                                    fileLinks.forEach(function(fileUrl) {
                                        let cleanFileUrl = fileUrl.replace(/<a href="([^"]+)"[^>]*>[^<]+<\/a>/, '$1').trim();
                                        orderContent += '<a href="' + cleanFileUrl + '" target="_blank"><img style="display: inline; -webkit-user-select: none; margin: 5px; cursor: zoom-in; background-color: hsl(0, 0%, 90%); transition: background-color 300ms;" src="' + cleanFileUrl + '" width="200" height="200" alt="Pridėta nuotrauka"></a>';
                                    });
                                    orderContent += '</div>';
                                }
                                orderContent += '</div>';
                            }
                        });

                        orderContent += '</div>';

                        if (hasSpecialOrder || hasComment || hasOrderDateMeta) {
                            specialOrdersContent += orderContent;
                        } else {
                            normalOrdersContent += orderContent;
                        }
                    });

                    // Sort order dates from earliest to latest
                    orderDates.sort(function(a, b) {
                        return new Date(a) - new Date(b);
                    });

                    // Add earliest and latest dates to the top of the content
                    let earliestDate = orderDates[0];
                    let latestDate = orderDates[orderDates.length - 1];
                    normalOrdersContent = '<div style="border-bottom: 2px solid #000;"><strong>Užsakymų datos: </strong>' + earliestDate + ' / ' + latestDate + '</div>' + normalOrdersContent;
                    normalOrdersContent = '<div><strong>Visų užsakymų IDs: </strong>' + selectedOrderIds.join(', ') + '</div>' + normalOrdersContent;

                    // Categorize and add items to table
                    response.data.forEach(function(order) {
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

                    for (let category in categorizedOrders) {
                        normalOrdersContent += '<h2>' + category + '</h2>';
                        normalOrdersContent += '<table class="csv-order-table">';
                        normalOrdersContent += '<thead><tr><th>Prekės</th><th>Kiekis</th></tr></thead>';
                        normalOrdersContent += '<tbody>';

                        let sizeTotals = {};

                        for (let item_name in categorizedOrders[category]) {
                            let sizes = categorizedOrders[category][item_name];
                            let sizeQuantity = [];
                            let totalQuantity = 0;

                            for (let size in sizes) {
                                let upperSize = size.toUpperCase();
                                totalQuantity += sizes[size];
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

                            normalOrdersContent += '<tr>';
                            normalOrdersContent += '<td>' + item_name + '</td>';
                            normalOrdersContent += '<td>' + sizeQuantity.join(', ') + '</td>';
                            normalOrdersContent += '</tr>';
                        }

                        let totalSizeQuantity = [];
                        for (let size in sizeTotals) {
                            totalSizeQuantity.push(sizeTotals[size] + ' ' + size.toUpperCase());
                        }

                        if (totalSizeQuantity.length > 0) {
                            normalOrdersContent += '<tr>';
                            normalOrdersContent += '<td><strong>Dydžių suma</strong></td>';
                            normalOrdersContent += '<td><strong>' + totalSizeQuantity.join(', ') + '</strong></td>';
                            normalOrdersContent += '</tr>';
                        }

                        normalOrdersContent += '</tbody></table>';
                    }

                    normalOrdersContent += specialOrdersContent;
                    normalOrdersContent += '</body></html>';

                    // Open the new content in a new tab
                    var newTab = window.open();
                    newTab.document.write(normalOrdersContent);
                    newTab.document.close();
                    newTab.focus();

                    // Update order statuses
                    $.ajax({
                        url: ajax_object_together.ajax_url,
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
