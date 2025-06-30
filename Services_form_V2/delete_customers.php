<?php
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$customerIds = $input['ids'] ?? [];

if (empty($customerIds)) {
    echo json_encode(['success' => false, 'message' => 'No customer IDs provided.']);
    exit;
}

$file = 'customers.json';

if (!file_exists($file)) {
    echo json_encode(['success' => false, 'message' => 'customers.json not found.']);
    exit;
}

$customers = json_decode(file_get_contents($file), true);

if ($customers === null && json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['success' => false, 'message' => 'Error decoding customers.json.']);
    exit;
}

$initialCount = count($customers);
$updatedCustomers = array_filter($customers, function($customer) use ($customerIds) {
    return !in_array($customer['id'], $customerIds);
});

if (file_put_contents($file, json_encode(array_values($updatedCustomers), JSON_PRETTY_PRINT)) === false) {
    echo json_encode(['success' => false, 'message' => 'Failed to write to customers.json.']);
    exit;
}

$deletedCount = $initialCount - count($updatedCustomers);
echo json_encode(['success' => true, 'message' => "Successfully deleted {$deletedCount} customers."]);

?>