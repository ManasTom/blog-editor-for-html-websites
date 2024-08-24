<?php
if (isset($_GET['slug'])) {
    $slug = htmlspecialchars($_GET['slug']);
    $postFileName = __DIR__ . "/" . $slug . ".html";

    // Check if a file with the slug name exists in the root directory
    if (file_exists($postFileName)) {
        echo json_encode(['exists' => true]);
    } else {
        echo json_encode(['exists' => false]);
    }
} else {
    echo json_encode(['error' => 'No slug provided']);
}
?>
