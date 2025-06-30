<?php
header('Content-Type: application/json');

$file = 'customers.json';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['id']) && $data['customer']) {
        $customerId = $data['id'];
        $updatedCustomerData = $data['customer'];
        
        if (file_exists($file)) {
            $customers = json_decode(file_get_contents($file), true);
            $customerFound = false;

            foreach ($customers as &$customer) {
                if ($customer['id'] === $customerId) {
                    // Update customer fields
                    foreach ($updatedCustomerData as $key => $value) {
                        $customer[$key] = $value;
                    }
                    $customerFound = true;
                    break;
                }
            }

            if ($customerFound) {
                file_put_contents($file, json_encode($customers, JSON_PRETTY_PRINT));
                http_response_code(200);
                echo json_encode(['message' => 'Customer updated successfully.']);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Customer not found.']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Customers file not found.']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['message' => 'Invalid data. Customer ID and data are required.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['message' => 'Method not allowed.']);
}
?>