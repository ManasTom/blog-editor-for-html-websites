<?php
if (isset($_GET['slug'])) {
    $slug = htmlspecialchars($_GET['slug']);
    $filePath = __DIR__ . '/' . $slug . '.html';
    $response = ['exists' => false];

    if (file_exists($filePath)) {
        $response['exists'] = true;
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
