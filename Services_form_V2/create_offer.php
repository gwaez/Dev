<?php
$file = 'offers.json';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_offer = json_decode(file_get_contents('php://input'), true);

    if (json_last_error() === JSON_ERROR_NONE && $new_offer) {
        $offers = [];
        if (file_exists($file)) {
            $offers = json_decode(file_get_contents($file), true);
        }

        // Assign a unique ID
        $new_offer['id'] = 'OFFER-' . time() . '-' . strtoupper(bin2hex(random_bytes(3)));
        $new_offer['status'] = 'Awaiting Payment';
        $new_offer['timestamp'] = date('Y-m-d H:i:s');

        $offers[] = $new_offer;

        file_put_contents($file, json_encode($offers, JSON_PRETTY_PRINT));
        
        http_response_code(200);
        echo json_encode(['message' => 'Offer created successfully.', 'offer' => $new_offer]);
    } else {
        http_response_code(400);
        echo json_encode(['message' => 'Invalid JSON data.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['message' => 'Method not allowed.']);
}
?>