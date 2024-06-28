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

                    let newWindowContent = '<html><head><title>Print Orders</title><style>body { font-family: Arial, sans-serif; padding: 20px; } table { width: 100%; border-collapse: collapse; margin-bottom: 20px; } th, td { border: 1px solid #000; padding: 8px; text-align: left; } th { background-color: #f2f2f2; } .order-section { margin-bottom: 40px; padding-bottom: 20px; border-bottom: 2px solid #000; } img { display: inline; -webkit-user-select: none; margin: 5px; cursor: zoom-in; background-color: hsl(0, 0%, 90%); transition: background-color 300ms; }</style></head><body>';
                    
                    response.data.forEach(function(order) {
                        newWindowContent += '<div class="order-section">';
                        newWindowContent += '<div><strong>Vaidmuo: </strong>' + order.user_role + '</div>'; // Add user role here
                        newWindowContent += '<div><strong>Užsakymo ID: </strong>' + order.id + '</div>';
                        newWindowContent += '<div><strong>Data: </strong>' + order.date + '</div>';
                        if (order.order_date_meta) {
                            newWindowContent += '<div><strong>Pagaminti iki: </strong>' + order.order_date_meta + '</div>';
                        }
                        if (order.customer_name && order.customer_name.trim() !== "") {
                            newWindowContent += '<div><strong>Vardas: </strong>' + order.customer_name + '</div>';
                        }
                        if (order.customer_email && order.customer_email.trim() !== "") {
                            newWindowContent += '<div><strong>El. paštas: </strong>' + order.customer_email + '</div>';
                        }
                        if (order.customer_phone && order.customer_phone.trim() !== "") {
                            newWindowContent += '<div><strong>Telefonas: </strong>' + order.customer_phone + '</div>';
                        }

                        if (Array.isArray(order.items)) {
                            // Add the unique attribute_pa_atsiemimo-vieta values
                            let uniqueLocations = [];
                            order.items.forEach(function(item) {
                                let location = item.attributes.location;
                                if (location && !uniqueLocations.includes(location)) {
                                    uniqueLocations.push(location);
                                }
                            });

                            if (uniqueLocations.length > 0) {
                                newWindowContent += '<div><strong>Atsiemimo vieta: </strong>' + uniqueLocations.join(', ') + '</div>';
                            }

                            if (order.comments) {
                                newWindowContent += '<div><strong>Komentarai: </strong>' + order.comments + '</div>';
                            }

                            let categorizedItems = {};

                            order.items.forEach(function(item) {
                                let category = item.category || 'Uncategorized';

                                if (!categorizedItems[category]) {
                                    categorizedItems[category] = {};
                                }
                                if (!categorizedItems[category][item.name]) {
                                    categorizedItems[category][item.name] = {};
                                }
                                let size = item.attributes.size || '';
                                if (!categorizedItems[category][item.name][size]) {
                                    categorizedItems[category][item.name][size] = 0;
                                }
                                categorizedItems[category][item.name][size] += item.quantity;
                            });

                            for (let category in categorizedItems) {
                                newWindowContent += '<h2>' + category + '</h2>';
                                newWindowContent += '<table class="csv-order-table">';
                                newWindowContent += '<thead><tr><th>Prekės</th><th>Kiekis</th></tr></thead>';
                                newWindowContent += '<tbody>';

                                let totalSizeQuantity = {};

                                for (let itemName in categorizedItems[category]) {
                                    let sizeTotals = [];
                                    for (let size in categorizedItems[category][itemName]) {
                                        if (!totalSizeQuantity[size] && size) {
                                            totalSizeQuantity[size] = 0;
                                        }
                                        if (size) {
                                            totalSizeQuantity[size] += categorizedItems[category][itemName][size];
                                        }

                                        if (size) {
                                            sizeTotals.push(categorizedItems[category][itemName][size] + ' ' + size.toUpperCase());
                                        } else {
                                            sizeTotals.push(categorizedItems[category][itemName][size]);
                                        }
                                    }

                                    newWindowContent += '<tr>';
                                    newWindowContent += '<td>' + itemName + '</td>';
                                    newWindowContent += '<td>' + sizeTotals.join(', ') + '</td>';
                                    newWindowContent += '</tr>';
                                }

                                let sizeSum = [];
                                for (let size in totalSizeQuantity) {
                                    if (size) {
                                        sizeSum.push(totalSizeQuantity[size] + ' ' + size.toUpperCase());
                                    }
                                }

                                if (sizeSum.length > 0) {
                                    newWindowContent += '<tr>';
                                    newWindowContent += '<td><strong>Dydžių suma</strong></td>';
                                    newWindowContent += '<td><strong>' + sizeSum.join(', ') + '</strong></td>';
                                    newWindowContent += '</tr>';
                                }

                                newWindowContent += '</tbody></table>';
                            }

                            // Append special order details after the table
                            order.items.forEach(function(item) {
                                if (item.special_order_text || item.special_order_files) {
                                    newWindowContent += '<div><strong>Specialus užsakymas: ID ' + item.name + '</strong></div>';
                                    if (item.special_order_text) {
                                        newWindowContent += '<div><strong>Papildoma informacija: </strong>' + item.special_order_text + '</div>';
                                    }
                                    if (item.special_order_files) {
                                        newWindowContent += '<div><strong>Pridėtos nuotraukos: </strong><br>';
                                        // Ensure special_order_files is an array
                                        let fileLinks = Array.isArray(item.special_order_files) ? item.special_order_files : item.special_order_files.split(', ');
                                        fileLinks.forEach(function(fileUrl) {
                                            let cleanFileUrl = fileUrl.replace(/<a href="([^"]+)"[^>]*>[^<]+<\/a>/, '$1').trim();
                                            newWindowContent += '<a href="' + cleanFileUrl + '" target="_blank"><img class="zoomable-image" style="display: inline; -webkit-user-select: none; margin: 5px; cursor: zoom-in; background-color: hsl(0, 0%, 90%); transition: background-color 300ms;" src="' + cleanFileUrl + '" width="200" height="200" alt="Pridėta nuotrauka"></a>';
                                        });
                                        newWindowContent += '</div>';
                                    }
                                }
                            });
                        } else {
                            console.log("Order items is not an array:", order.items);
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
