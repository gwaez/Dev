<?php
header('Content-Type: application/json');

$file = 'customers.json';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_customer = json_decode(file_get_contents('php://input'), true);

    if (json_last_error() === JSON_ERROR_NONE && $new_customer) {
        $customers = [];
        if (file_exists($file)) {
            $customers = json_decode(file_get_contents($file), true);
        }

        // Assign a unique ID
        $new_customer['id'] = 'CUST-' . time() . '-' . strtoupper(bin2hex(random_bytes(3)));
        $new_customer['timestamp'] = date('Y-m-d H:i:s');

        $customers[] = $new_customer;

        file_put_contents($file, json_encode($customers, JSON_PRETTY_PRINT));
        
        http_response_code(200);
        echo json_encode(['message' => 'Customer created successfully.', 'customer' => $new_customer]);
    } else {
        http_response_code(400);
        echo json_encode(['message' => 'Invalid JSON data.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['message' => 'Method not allowed.']);
}
?>