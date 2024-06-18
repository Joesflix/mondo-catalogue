<?php
// WooCommerce REST API credentials
$consumerKey = 'ck_dc5940b5eadcd61185fb1cd061cc318b308198f7';
$consumerSecret = 'cs_5039adf872d1dd8bc793fe51212996c50496ac56';
$storeUrl = 'https://mondobydefunc2.wpenginepowered.com/wp-content/uploads/catalogue.json';

// Function to fetch product data
function fetchWooCommerceProducts($url, $consumerKey, $consumerSecret) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url . '?per_page=100'); // Adjust per_page as needed
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, $consumerKey . ":" . $consumerSecret);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// Fetch product data
$products = fetchWooCommerceProducts($storeUrl, $consumerKey, $consumerSecret);

// Format the data as needed for the catalog
$catalog = [];
foreach ($products as $product) {
    $catalog[] = [
        'Item_ID' => $product['id'],
        'SKU' => $product['sku'],
        'Name' => $product['name'],
        'Description' => $product['description'],
        'Category' => !empty($product['categories']) ? $product['categories'][0]['name'] : '',
        'Price' => $product['price'],
        'Currency' => 'SEK', // Adjust as necessary
        'Image_URL' => !empty($product['images']) ? $product['images'][0]['src'] : '',
        'Product_URL' => $product['permalink'],
    ];
}

// Convert the catalog to JSON
$catalogJson = json_encode($catalog);

// Save the JSON to a file
file_put_contents('/path/to/mondobydefunc2.wpenginepowered.com/wp-content/uploads/catalogue.json/wp-content/uploads/catalogue.json', $catalogJson);

// Output the JSON
header('Content-Type: application/json');
echo $catalogJson;
?>
