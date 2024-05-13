<?php
/*
Plugin Name: CSV Order Table
Description: Display CSV order data in a table format.
Version: 1.0
Author: Bellatoscana
*/

// Enqueue necessary scripts and styles
function csv_order_table_enqueue_scripts()
{
    wp_enqueue_style('csv-order-table-style', plugin_dir_url(__FILE__) . '/style.css');
    wp_enqueue_script('csv-order-table-script', plugin_dir_url(__FILE__) . '/script.js', array('jquery'), null, true);
    wp_localize_script('csv-order-table-script', 'csv_order_table_script_vars', array('stylesheet_url' => plugin_dir_url(__FILE__) . '/style.css'));
}
add_action('admin_enqueue_scripts', 'csv_order_table_enqueue_scripts');

// Add a menu item in the admin menu
function csv_order_table_menu()
{
    add_menu_page(
        'Konvertuoti užsakymus',
        'Konvertuoti užsakymus',
        'manage_options',
        'csv-order-table',
        'csv_order_table_page'
    );
}
add_action('admin_menu', 'csv_order_table_menu');

// Function to display the admin page
function csv_order_table_page()
{
?>
    <div class="wrap">
        <h1>Konvertuoti užsakymus</h1>
        <form method="post" enctype="multipart/form-data">
            <label for="csv_file">Įkelti CSV failą:</label>
            <input type="file" id="csv_file" name="csv_file">
            <input type="submit" name="submit" value="Įkelti">
        </form>
        <?php
        if (isset($_POST['submit'])) {
            if ($_FILES['csv_file']['error'] == UPLOAD_ERR_OK) {
                $csv_file = $_FILES['csv_file']['tmp_name'];
                if (($handle = fopen($csv_file, 'r')) !== FALSE) {
                    $order_data = array();
                    while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                        $order_data[] = $data;
                    }
                    fclose($handle);

                    // Initialize arrays to store data
                    $orders_by_category = array();

                    // Process CSV data
                    foreach ($order_data as $order) {
                        $name = $order[0];
                        $quantity = intval($order[1]);
                        $size = $order[4];
                        $item_name = $order[3];
                        $category = str_replace('Privatus klientas,', '', $order[5]); // Remove 'Privatus klientas' and comma
                        $category = str_replace(',Privatus klientas', '', $category); // Remove ',Privatus klientas'

                        // Skip if size, category, or name is empty
                        if (empty($size) || empty($category) || empty($item_name)) {
                            continue;
                        }

                        // Skip if category is 'Category' or size is 'Size0'
                        if ($category == 'Category' || $size == '0-Size') {
                            continue;
                        }

                        // Sum quantity by category, item name, and size
                        if (!isset($orders_by_category[$category][$item_name][$size])) {
                            $orders_by_category[$category][$item_name][$size] = 0;
                        }
                        $orders_by_category[$category][$item_name][$size] += $quantity;
                    }

                    // Display table of orders by category
                    foreach ($orders_by_category as $category => $items) {
                        echo '<h2>' . $category . '</h2>';
                        echo '<table class="csv-order-table">';
                        echo '<thead><tr><th>Prekės</th><th>Kiekis</th><th>Suma</th></tr></thead>';
                        echo '<tbody>';
                        foreach ($items as $item_name => $sizes) {
                            echo '<tr>';
                            echo '<td>' . $item_name . '</td>';
                            echo '<td>';
                            $size_quantity = array();
                            foreach ($sizes as $size => $quantity) {
                                $size_quantity[] = $quantity . '-' . $size;
                            }
                            echo implode(', ', $size_quantity);
                            echo '</td>';
                            echo '<td>' . array_sum($sizes) . '</td>'; // Calculate and display total quantity
                            echo '</tr>';
                        }
                        echo '</tbody>';
                        echo '</table>';
                    }
                }
            } else {
                echo '<p>Klaida įkeliant failą.</p>';
            }
        }
        ?>
        <button id="print-all-button">Spausdinti visus</button> <!-- Add print all button -->
    </div>
<?php
}

// Shortcode to display the CSV order table on front end
function generate_csv_order_table()
{
    return csv_order_table_page();
}
add_shortcode('csv_order_table', 'generate_csv_order_table');
?>