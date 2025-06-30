<?php
$file = 'offers.json';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['id']) && isset($data['status'])) {
        $offerId = $data['id'];
        $newStatus = $data['status'];
        
        if (file_exists($file)) {
            $offers = json_decode(file_get_contents($file), true);
            $offerFound = false;

            foreach ($offers as &$offer) {
                if ($offer['id'] === $offerId) {
                    $offer['status'] = $newStatus;
                    $offerFound = true;
                    break;
                }
            }

            if ($offerFound) {
                file_put_contents($file, json_encode($offers, JSON_PRETTY_PRINT));
                http_response_code(200);
                echo json_encode(['message' => 'Offer status updated successfully.']);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Offer not found.']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Offers file not found.']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['message' => 'Invalid data. Offer ID and status are required.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['message' => 'Method not allowed.']);
}
?>