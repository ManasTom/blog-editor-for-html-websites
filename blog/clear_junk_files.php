<?php
// Path to the timestamp.json file and the uploads directory
$timestampFilePath = __DIR__ . '/timestamp.json';
$uploadsDir = __DIR__ . '/uploads/';

// Initialize an array to store the list of used featured images
$usedImages = [];

// Check if the timestamp.json file exists
if (file_exists($timestampFilePath)) {
    // Decode the JSON data from timestamp.json
    $timestampData = json_decode(file_get_contents($timestampFilePath), true);

    // Loop through each post and collect the featured images
    foreach ($timestampData as $timestamp => $data) {
        if (isset($data['featuredImage'])) {
            // Extract the image filename from the full URL and add it to the usedImages array
            $imagePath = str_replace('https://demo.illforddigital.com/blog/', '', $data['featuredImage']);
            $usedImages[] = basename($imagePath);
        }
    }
}

// Remove duplicates from the usedImages array
$usedImages = array_unique($usedImages);

// Get the list of all files in the uploads directory
$uploadFiles = array_diff(scandir($uploadsDir), array('.', '..'));

// Loop through the uploads directory files
foreach ($uploadFiles as $file) {
    // Check if the file is not in the usedImages array
    if (!in_array($file, $usedImages)) {
        // Delete the unused file
        $filePath = $uploadsDir . $file;
        if (file_exists($filePath)) {
            unlink($filePath);
            echo "Deleted unused file: $filePath\n";
        }
    }
}

echo "Cleanup completed!";
?>
