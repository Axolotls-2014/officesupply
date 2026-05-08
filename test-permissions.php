<?php
// Test to see what your ajax_list returns
error_reporting(E_ALL);
ini_set('display_errors', 1);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8080/officesupply/sale/ajax_list');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'sales_payment' => '',
    'csrf_test_name' => '' // You may need the actual CSRF token
]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Status: " . $http_code . "\n\n";
echo "Response:\n";
echo $response;