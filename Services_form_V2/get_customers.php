<?php
header('Content-Type: application/json');

$file = 'customers.json';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (file_exists($file)) {
        $customers = json_decode(file_get_contents($file), true);
        // Sort customers by timestamp descending (newest first)
        usort($customers, function($a, $b) {
            return strtotime($b['timestamp']) - strtotime($a['timestamp']);
        });
        http_response_code(200);
        echo json_encode($customers);
    } else {
        http_response_code(200);
        echo json_encode([]); // Return empty array if file doesn't exist
    }
} else {
    http_response_code(405);
    echo json_encode(['message' => 'Method not allowed.']);
}
?>