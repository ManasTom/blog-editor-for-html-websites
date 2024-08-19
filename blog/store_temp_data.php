<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode($_POST['data'], true);
    $tempFilePath = __DIR__ . '/temp.json';

    // Load existing temp data
    $tempData = file_exists($tempFilePath) ? json_decode(file_get_contents($tempFilePath), true) : [];

    // Generate a random ID for the new entry
    $randomId = uniqid();

    // Add the new entry
    $tempData[$randomId] = $data;

    // Save the updated temp data
    if (file_put_contents($tempFilePath, json_encode($tempData, JSON_PRETTY_PRINT)) !== false) {
        echo json_encode(['status' => 'success', 'tempId' => $randomId]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to store temp data.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
