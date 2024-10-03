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

                    let newWindowContent = `
                        <html>
                        <head>
                            <title>Print Orders</title>
                            <style>
                                body { font-family: Arial, sans-serif; padding: 20px; }
                                table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                                th, td { border: 1px solid #000; padding: 4px; text-align: left; line-height: 1; }
                                th { background-color: #f2f2f2; }
                                .order-section { margin-bottom: 0; padding-bottom: 0; border-bottom: 2px solid #000; }
                                h2 { margin: 5px 0 0 0; }
                                img { display: inline; -webkit-user-select: none; margin: 5px; cursor: zoom-in; background-color: hsl(0, 0%, 90%); transition: background-color 300ms; }
                            </style>
                        </head>
                        <body>`;

                    response.data.forEach(function(order) {
                        newWindowContent += '<div class="order-section">';
                        newWindowContent += '<div class="tight-text"><strong>Vaidmuo: </strong>' + order.user_role + '</div>';
                        newWindowContent += '<div class="tight-text"><strong>Užsakymo ID: </strong>' + order.id + '</div>';
                        newWindowContent += '<div class="tight-text"><strong>Data: </strong>' + order.date + '</div>';
                        if (order.order_date_meta) {
                            newWindowContent += '<div class="tight-text"><strong>Pagaminti iki: </strong>' + order.order_date_meta + '</div>';
                        }
                        if (order.customer_name && order.customer_name.trim() !== "") {
                            newWindowContent += '<div class="tight-text"><strong>Vardas: </strong>' + order.customer_name + '</div>';
                        }
                        if (order.customer_email && order.customer_email.trim() !== "") {
                            newWindowContent += '<div class="tight-text"><strong>El. paštas: </strong>' + order.customer_email + '</div>';
                        }
                        if (order.customer_phone && order.customer_phone.trim() !== "") {
                            newWindowContent += '<div class="tight-text"><strong>Telefonas: </strong>' + order.customer_phone + '</div>';
                        }

                        if (Array.isArray(order.items)) {
                            let uniqueLocations = [];
                            order.items.forEach(function(item) {
                                let location = item.attributes.location;
                                if (location && !uniqueLocations.includes(location)) {
                                    uniqueLocations.push(location);
                                }
                            });

                            if (uniqueLocations.length > 0) {
                                newWindowContent += '<div class="tight-text"><strong>Atsiemimo vieta: </strong>' + uniqueLocations.join(', ') + '</div>';
                            }

                            if (order.comments) {
                                newWindowContent += '<div class="tight-text"><strong>Komentarai: </strong>' + order.comments + '</div>';
                            }

                            let categorizedItems = {};

                            order.items.forEach(function(item) {
                                let category = item.category || 'Uncategorized';
                                if (!categorizedItems[category]) {
                                    categorizedItems[category] = {};
                                }
                                let itemName = item.name || 'Unnamed Item';
                                if (!categorizedItems[category][itemName]) {
                                    categorizedItems[category][itemName] = {};
                                }
                                let size = item.attributes.size || '';
                                if (!categorizedItems[category][itemName][size]) {
                                    categorizedItems[category][itemName][size] = 0;
                                }
                                categorizedItems[category][itemName][size] += item.quantity;
                            });

                            let sortedCategories = Object.keys(categorizedItems).sort((a, b) => a.localeCompare(b, 'lt', { sensitivity: 'base' }));

                            sortedCategories.forEach(function(category) {
                                newWindowContent += '<h2>' + category + '</h2>';
                                newWindowContent += '<table class="csv-order-table">';
                                newWindowContent += '<thead><tr><th>Prekės</th><th>Kiekis</th></tr></thead>';
                                newWindowContent += '<tbody>';

                                let totalSizeQuantity = {};

                                let sortedProductNames = Object.keys(categorizedItems[category]).sort((a, b) => a.localeCompare(b, 'lt', { sensitivity: 'base' }));

                                // Iterate through sorted product names for each category
                                sortedProductNames.forEach(function(itemName) {
                                    let sizeTotals = [];
                                    let totalQuantity = 0; // Track total quantity of the product
                                    let hasSize = false; // Flag to check if the item has sizes

                                    for (let size in categorizedItems[category][itemName]) {
                                        // Get the quantity for this specific size
                                        let quantity = categorizedItems[category][itemName][size] || 0; // Default to 0 if undefined
                                        
                                        if (quantity > 0) {
                                            // If quantity is greater than 0, we proceed
                                            totalQuantity += quantity; // Add to total quantity
                                            
                                            if (size) {
                                                hasSize = true; // Set flag to true if there is a valid size
                                                
                                                // Check if the quantity is a number and add to size totals
                                                if (typeof quantity === 'number') {
                                                    // Convert size to uppercase for consistency
                                                    let sizeUpper = size.toUpperCase();
                                                    
                                                    // Check if the size is in the format of "X-Y" where X and Y are digits
                                                    const sizePattern = /^\d-\d$/; // Matches patterns like "0-5", "1-0", "1-5"
                                                    if (sizePattern.test(sizeUpper)) {
                                                        // Split the size string at the hyphen, then join with a dot
                                                        sizeUpper = sizeUpper.replace('-', '.');
                                                    }

                                                    // Add the processed size to the sizeTotals array
                                                    sizeTotals.push(quantity + ' x ' + sizeUpper);
                                                }
                                            } 
                                        }
                                    }

                                    // Prepare the row output
                                    newWindowContent += '<tr>';
                                    newWindowContent += '<td>' + itemName + '</td>';

                                    // If there are size totals, show them; otherwise, show the total quantity
                                    if (hasSize) {
                                        newWindowContent += '<td>' + sizeTotals.join(', ') + '</td>'; // Show size totals if available
                                    } else if (totalQuantity > 0) {
                                        newWindowContent += '<td>' + totalQuantity + '</td>'; // Show total quantity if no sizes
                                    } else {
                                        newWindowContent += '<td>No sizes available</td>'; // Indicate no sizes just show total quantity of that product
                                    }

                                    newWindowContent += '</tr>';
                                });

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
                            });

                            // Append special order details after the table
                            order.items.forEach(function(item) {
                                if (item.special_order_text || item.special_order_files) {
                                    newWindowContent += '<div class="tight-text"><strong>Specialus užsakymas: ID ' + item.name + '</strong></div>';
                                    if (item.special_order_text) {
                                        newWindowContent += '<div class="tight-text"><strong>Papildoma informacija: </strong>' + item.special_order_text + '</div>';
                                    }
                                    if (item.special_order_files) {
                                        newWindowContent += '<div class="tight-text"><strong>Pridėtos nuotraukos: </strong><br>';
                                        let fileLinks = Array.isArray(item.special_order_files) ? item.special_order_files : item.special_order_files.split(', ');
                                        fileLinks.forEach(function(fileUrl) {
                                            let cleanFileUrl = fileUrl.replace(/<a href="([^"]+)"[^>]*>[^<]+<\/a>/, '$1').trim();
                                            newWindowContent += '<a href="' + cleanFileUrl + '" target="_blank"><img class="zoomable-image" style="width: 100px;" src="' + cleanFileUrl + '" /></a>';
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
