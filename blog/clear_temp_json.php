<?php
// Define the path to the temp.json file
$tempFilePath = __DIR__ . '/temp.json';

// Check if the file exists
if (file_exists($tempFilePath)) {
    // Clear the content of temp.json by writing an empty JSON object
    file_put_contents($tempFilePath, json_encode([]));
    echo "temp.json has been cleared.\n";
} else {
    echo "temp.json does not exist.\n";
}
?>
