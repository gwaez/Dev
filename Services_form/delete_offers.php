<?php
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$offerIds = $input['ids'] ?? [];

if (empty($offerIds)) {
    echo json_encode(['success' => false, 'message' => 'No offer IDs provided.']);
    exit;
}

// Path to your offers.json file
$offersFile = __DIR__ . '/offers.json';

if (!file_exists($offersFile)) {
    echo json_encode(['success' => false, 'message' => 'offers.json not found.']);
    exit;
}

$offers = json_decode(file_get_contents($offersFile), true);

if ($offers === null && json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['success' => false, 'message' => 'Error decoding offers.json.']);
    exit;
}

$initialCount = count($offers);
$updatedOffers = array_filter($offers, function($offer) use ($offerIds) {
    return !in_array($offer['id'], $offerIds);
});

if (file_put_contents($offersFile, json_encode(array_values($updatedOffers), JSON_PRETTY_PRINT)) === false) {
    echo json_encode(['success' => false, 'message' => 'Failed to write to offers.json.']);
    exit;
}

$deletedCount = $initialCount - count($updatedOffers);
echo json_encode(['success' => true, 'message' => "Successfully deleted {$deletedCount} offers."]);

?>