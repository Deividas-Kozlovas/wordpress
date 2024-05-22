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
                    var newWindow = window.open('', '_blank', 'width=800,height=600');
                    newWindow.document.write('<html><head><title>Print Orders</title><style>body { font-family: Arial, sans-serif; padding: 20px; } table { width: 100%; border-collapse: collapse; margin-bottom: 20px; } th, td { border: 1px solid #000; padding: 8px; text-align: left; } th { background-color: #f2f2f2; }</style></head><body>');

                    let categorizedOrders = {};

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
                        newWindow.document.write('<h2>' + category + '</h2>');
                        newWindow.document.write('<table class="csv-order-table">');
                        newWindow.document.write('<thead><tr><th>Prekės</th><th>Kiekis</th></tr></thead>');
                        newWindow.document.write('<tbody>');

                        let sizeTotals = {};

                        for (let item_name in categorizedOrders[category]) {
                            let sizes = categorizedOrders[category][item_name];
                            let sizeQuantity = [];
                            for (let size in sizes) {
                                sizeQuantity.push(sizes[size] + '-' + size);
                                if (!sizeTotals[size]) {
                                    sizeTotals[size] = 0;
                                }
                                sizeTotals[size] += sizes[size];
                            }

                            newWindow.document.write('<tr>');
                            newWindow.document.write('<td>' + item_name + '</td>');
                            newWindow.document.write('<td>' + sizeQuantity.join(', ') + '</td>');
                            newWindow.document.write('</tr>');
                        }

                        let totalSizeQuantity = [];
                        for (let size in sizeTotals) {
                            totalSizeQuantity.push(sizeTotals[size] + '-' + size.toUpperCase());
                        }

                        newWindow.document.write('<tr>');
                        newWindow.document.write('<td><strong>Dydžių suma</strong></td>');
                        newWindow.document.write('<td><strong>' + totalSizeQuantity.join(', ') + '</strong></td>');
                        newWindow.document.write('</tr>');

                        newWindow.document.write('</tbody>');
                        newWindow.document.write('</table>');
                    }

                    newWindow.document.write('</body></html>');
                    newWindow.document.close();
                    newWindow.focus();
                    newWindow.print();
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
