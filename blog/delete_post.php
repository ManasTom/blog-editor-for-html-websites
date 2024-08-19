<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the slug from the POST data
    $slug = $_POST['slug'];

    // Define paths to the JSON files
    $timestampFilePath = __DIR__ . '/timestamp.json';
    $tagsFilePath = __DIR__ . '/tags.json';

    // Load and decode timestamp.json
    if (file_exists($timestampFilePath)) {
        $timestampData = json_decode(file_get_contents($timestampFilePath), true);

        // Find the entry to delete
        foreach ($timestampData as $timestamp => $data) {
            if ($data['slug'] === $slug) {
                // Delete the HTML file
                $postFileName = __DIR__ . '/' . $slug . '.html';
                if (file_exists($postFileName)) {
                    unlink($postFileName);
                }

                // Delete the featured image
                $featuredImagePath = str_replace('https://demo.illforddigital.com/blog/', __DIR__ . '/', $data['featuredImage']);
                if (file_exists($featuredImagePath)) {
                    unlink($featuredImagePath);
                }

                // Remove the entry from timestamp.json
                unset($timestampData[$timestamp]);

                // Save the updated timestamp.json
                file_put_contents($timestampFilePath, json_encode($timestampData, JSON_PRETTY_PRINT));

                break;
            }
        }
    }

    // Load and decode tags.json
    if (file_exists($tagsFilePath)) {
        $tagsData = json_decode(file_get_contents($tagsFilePath), true);

        // Remove the post from the tags.json
        foreach ($tagsData['hashtags'] as $tag => $posts) {
            if (isset($posts[$slug . '.html'])) {
                unset($tagsData['hashtags'][$tag][$slug . '.html']);
                if (empty($tagsData['hashtags'][$tag])) {
                    unset($tagsData['hashtags'][$tag]);
                }
            }
        }

        // Save the updated tags.json
        file_put_contents($tagsFilePath, json_encode($tagsData, JSON_PRETTY_PRINT));
    }

    // Return a success response
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
