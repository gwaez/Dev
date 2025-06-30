<?php
$file = 'services.json';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (json_last_error() === JSON_ERROR_NONE) {
        file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
        http_response_code(200);
        echo json_encode(['message' => 'Services updated successfully.']);
    } else {
        http_response_code(400);
        echo json_encode(['message' => 'Invalid JSON data.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['message' => 'Method not allowed.']);
}
?>