<?php
// Increase maximum execution time and memory limit if needed
ini_set('max_execution_time', '300');
ini_set('memory_limit', '512M');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ensure all form fields are present
    $required_fields = ['title', 'content', 'focusKeyphrase', 'seoTitle', 'slug', 'metaDescription', 'tags', 'visibility'];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field])) {
            die("Error: Missing $field");
        }
    }

    // Get form data
    $title = $_POST['title'];
    $content = $_POST['content'];
    $focusKeyphrase = $_POST['focusKeyphrase'];
    $seoTitle = $_POST['seoTitle'];
    $slug = $_POST['slug'];
    $metaDescription = $_POST['metaDescription'];
    $tags = $_POST['tags'];
    $visibility = $_POST['visibility'];

    // Handle image upload
    $targetDir = "uploads/";
    $featuredImage = "";
    if (!empty($_FILES['featuredImage']['name'])) {
        $targetFile = $targetDir . basename($_FILES["featuredImage"]["name"]);
        if (move_uploaded_file($_FILES["featuredImage"]["tmp_name"], $targetFile)) {
            $featuredImage = $targetFile;
        } else {
            die("Error: Unable to upload image.");
        }
    }

    // Create the blog post content with updated styling
    $blogPostContent = "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>$seoTitle</title>
        <meta name='description' content='$metaDescription'>
        <link rel='stylesheet' href='blog.css'>
        
    <!-- bootstrap -->
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet'
        integrity='sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH' crossorigin='anonymous'>
    <!-- Font Awesome link -->
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css'>
    <script src='https://kit.fontawesome.com/6c53136549.js' crossorigin='anonymous'></script>

        <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .featured-image {
            width: 100%;
            height: auto;
        }

        .post-title {
            font-size: 2em;
            margin: 20px 0;
        }

        .post-meta {
            color: #777;
            margin-bottom: 20px;
        }

        .post-content {
            font-size: 1.1em;
        }

        .post-content img {
            max-width: 100%;
            height: auto;
        }

        .post-tags {
            margin-top: 20px;
            font-style: italic;
            color: #555;
        }

        .header {
            width: 100%;
            height: max-content;
            box-shadow: 0px 7px 10px rgba(0, 0, 0, 0.1);
        }

        .header_col1 img {
            width: 100%;
            height: auto;
            padding: 5%;
        }

        .header_col2 {
            display: flex;
            align-items: center;
            justify-content: space-around;
        }

        .header_col3 {
            display: flex;
            align-items: center;
            justify-content: end;
        }

        .header_col3 a {
            color: #000000;
            font-size: 300%;
        }

        .mobile_menu {
            width: 100%;
            height: max-content;
            display: none;
        }

        .mobile_menu a {
            text-decoration: none;
            color: #000000;
            font-weight: 600;
        }

        .mobile_menu li {
            list-style-type: none;
        }

        @media (max-width: 768px) {
            .header_col2 {
                display: none;
            }
        }

        @media (min-width: 769px) {
            .header_col3 {
                display: none;
            }
        }
    </style>
    </head>
    <body>
    <header class='header'>
        <div class='row'>
            <div class='col-xl-2 col-lg-2 col-md-2 col-sm-6 col-6 header_col1'>
                <img src='globals/simpliALogo.png' alt=''>
            </div>
            <div class='col-xl-10 col-lg-10 col-md-10 col-sm-0 col-0 header_col2'>
                <a href=''>xxxxx</a>
                <a href=''>xxxxx</a>
                <a href=''>xxxxx</a>
                <a href=''>xxxxx</a>
                <a href=''>xxxxx</a>
            </div>
            <div class='col-xl-0 col-lg-0 col-md-0 col-sm-6 col-6 header_col3'>
                <a><i onclick='hamburgerToggle()' class='fa-solid fa-bars'></i></a>
            </div>
        </div>
        <div id='mobileMenu' class='mobile_menu'>
            <ul>
                <li><a href=''>xxxxx</a></li>
                <li><a href=''>xxxxx</a></li>
                <li><a href=''>xxxxx</a></li>
                <li><a href=''>xxxxx</a></li>
                <li><a href=''>xxxxx</a></li>
            </ul>
        </div>
    </header>
        <div class='container'>
            <img src='$featuredImage' class='featured-image' alt='Featured Image'>
            <h1 class='post-title'>$title</h1>
            <p class='post-meta'>By Your Name | July 14, 2024</p>
            <div class='post-content'>$content</div>
            <p class='post-tags'>Tags: $tags</p>
        </div>

        
    <!-- bootstrap -->
    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js'
        integrity='sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz'
        crossorigin='anonymous'></script>
    <script src='https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js'
        integrity='sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r'
        crossorigin='anonymous'></script>
    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js'
        integrity='sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy'
        crossorigin='anonymous'></script>



    <script>
        // ***********************************************************************
        // Function to toggle hamburger menu
        // ***********************************************************************

        function hamburgerToggle() {
            var menu = document.getElementById('mobileMenu');
            if (menu.style.display === 'block') {
                menu.style.display = 'none';
            } else {
                menu.style.display = 'block';
            }
        }
    </script>
    </body>
    </html>";

    // Save the blog post content to a file in the root directory
    $postFileName = __DIR__ . "/" . $slug . ".html";
    if (file_put_contents($postFileName, $blogPostContent) === false) {
        die("Error: Unable to save the blog post.");
    }

    echo "Post published successfully!";
} else {
    echo "Error: Invalid request method.";
}
?>
