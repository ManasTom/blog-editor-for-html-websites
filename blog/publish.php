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
    $title = htmlspecialchars($_POST['title']);
    $content = $_POST['content'];
    $focusKeyphrase = htmlspecialchars($_POST['focusKeyphrase']);
    $seoTitle = htmlspecialchars($_POST['seoTitle']);
    $slug = htmlspecialchars($_POST['slug']);
    $metaDescription = htmlspecialchars($_POST['metaDescription']);
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

    // User-defined global variables
    $domainName = 'https://yourdomainname.com/';
    $rootPath = 'https://yourdomainname.com/blog/';
    $language = 'en_US';
    $openGraphType = 'article';
    $publisherUrl = 'https://any-social-media-profile-link';
    $publisherName = 'your-company-name';
    $publisherTwitterId = '@yourTwitterId';
    $publisherLogo = 'globals/your-logo.png';
    $publisherTagline = 'example tagline for your company or organisation';
    $favioconLink = 'globals/favicon.png';
    $blogHome = 'blog.html';
    $facebookProfileLink = 'https://your-facebook-profile-link';
    $instagramProfileLink = 'https://your-instagram-profile-link';
    $threadsProfileLink = 'https://your-threads-profile-link';
    $twitterProfileLink = 'https://your-twitter-profile-link';
    $linkedinProfileLink = 'https://your-linkedin-profile-link';
    $whatsappProfileLink = 'https://wa.me/12345678899';
    $youtubeProfileLink = 'https://your-youtube-profile-link';
    $publisherAddress = 'Example Villa, Example Street, Example Town, Example District, Example Country';
    $publisherMobile = '9876543210';
    $publisherEmail = 'example@gmail.com';
    $privacyPolicy = 'privacy-policy.html';
    $termsAndCondition = 'terms-and-condition.html';
    $siteMap = 'sitemap.html';

    // Processed variables
    $canonicalUrl = $rootPath.$slug . '.html';
    $CurrentDateTime = date('c');
    $featuredImageUrl = $rootPath.$featuredImage;
    $logoImageUrl = $rootPath.$publisherLogo;
    $formattedPublishDate = date('F j, Y');
    $blogHomeUrl = $domainName.$blogHome;
    $privacyPolicyUrl = $domainName.$privacyPolicy;
    $termsAndConditionUrl = $domainName.$termsAndCondition;
    $siteMapUrl = $domainName.$siteMap;

    // Read the existing tags.json file
    $tagsFilePath = __DIR__ . "/tags.json";
    $tagsData = file_exists($tagsFilePath) ? json_decode(file_get_contents($tagsFilePath), true) : ["hashtags" => []];

    // Process each tag and update the tags.json structure
    $tagsArray = explode(',', $tags);
    $formattedTagsForJson = array_map(function($tag) {
        return '"' . trim($tag) . '"';
    }, $tagsArray);
    $formattedTagsString = implode(',', $formattedTagsForJson);
    
    $postFileName = $slug . ".html"; // The name of the HTML file being created
    foreach ($tagsArray as $tag) {
        $tag = trim($tag); // Trim any whitespace around the tag

        if (!isset($tagsData["hashtags"][$tag])) {
            $tagsData["hashtags"][$tag] = [];
        }

        // Append or update the data under the filename
        $tagsData["hashtags"][$tag][$postFileName] = [
            "title" => $title,
            "featuredImage" => $featuredImageUrl,
            "url" => $canonicalUrl
        ];
    }

    // Write the updated data back to tags.json
    if (file_put_contents($tagsFilePath, json_encode($tagsData, JSON_PRETTY_PRINT)) === false) {
        die("Error: Unable to update tags.json.");
    }

    // Generate hashtag links
    $tagLinks = array_map(function($tag) {
        return '<a href="hashtagposts.html?tag=' . urlencode(trim($tag)) . '">#' . htmlspecialchars(trim($tag)) . '</a>';
    }, $tagsArray);
    $tagLinksString = implode(', ', $tagLinks);

    // Create the blog post content with updated styling and hashtag links
    $blogPostContent = <<<HTML
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="robots" content="index, follow" />
        <title>$seoTitle</title>
        <link rel="shortcut icon" type="image/jpg" href="$favioconLink" />
        <meta name="description" content="$metaDescription" />
        <link rel="canonical" href="$canonicalUrl" />
        <meta property="og:locale" content="$language" />
        <meta property="og:type" content="$openGraphType" />
        <meta property="og:title" content="$seoTitle" />
        <meta property="og:description" content="$metaDescription" />
        <meta property="og:url" content="$canonicalUrl" />
        <meta property="article:publisher" content="$publisherUrl" />
        <meta property="article:published_time" content="$CurrentDateTime" />
        <meta name="author" content="$publisherName" />
        <meta property="og:image:type" content="image/jpeg" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:creator" content="$publisherTwitterId" />
        <meta name="twitter:site" content="$publisherTwitterId" />
        <meta name="twitter:label1" content="Written by" />
        <meta name="twitter:data1" content="$publisherName" />
        <meta name="twitter:label2" content="Est. reading time" />
        <meta name="twitter:data2" content="4 minutes" />
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@graph": [
                {
                    "@type": "$openGraphType",
                    "@id": "$canonicalUrl/#$openGraphType",
                    "isPartOf": {
                        "@id": "$canonicalUrl/"
                    },
                    "author": {
                        "name": "$publisherName",
                        "@id": "$blogHomeUrl"
                    },
                    "headline": "$title",
                    "datePublished": "$CurrentDateTime",
                    "mainEntityOfPage": {
                        "@id": "$canonicalUrl/"
                    },
                    "wordCount": 365,
                    "commentCount": 0,
                    "publisher": {
                        "@id": "$blogHomeUrl"
                    },
                    "image": {
                        "@id": "$canonicalUrl/#primaryimage"
                    },
                    "thumbnailUrl": "$featuredImageUrl",
                    "keywords": [
                        $formattedTagsString
                    ],
                    "articleSection": [
                        "Blog"
                    ],
                    "inLanguage": "$language"
                },
                {
                    "@type": "WebPage",
                    "@id": "$canonicalUrl/",
                    "url": "$canonicalUrl/",
                    "name": "$seoTitle",
                    "isPartOf": {
                        "@id": "$blogHomeUrl"
                    },
                    "primaryImageOfPage": {
                        "@id": "$canonicalUrl/#primaryimage"
                    },
                    "image": {
                        "@id": "$canonicalUrl/#primaryimage"
                    },
                    "thumbnailUrl": "$featuredImageUrl",
                    "datePublished": "$CurrentDateTime",
                    "description": "$metaDescription.",
                    "breadcrumb": {
                        "@id": "$canonicalUrl/#breadcrumb"
                    },
                    "inLanguage": "$language",
                    "potentialAction": [
                        {
                            "@type": "ReadAction",
                            "target": [
                                "$canonicalUrl/"
                            ]
                        }
                    ]
                },
                {
                    "@type": "ImageObject",
                    "inLanguage": "$language",
                    "@id": "$canonicalUrl/#primaryimage",
                    "url": "$featuredImageUrl",
                    "contentUrl": "$featuredImageUrl",
                    "caption": "$title"
                },
                {
                    "@type": "BreadcrumbList",
                    "@id": "$canonicalUrl/#breadcrumb",
                    "itemListElement": [
                        {
                            "@type": "ListItem",
                            "position": 1,
                            "name": "Home",
                            "item": "$blogHomeUrl"
                        },
                        {
                            "@type": "ListItem",
                            "position": 2,
                            "name": "$title"
                        }
                    ]
                },
                {
                    "@type": "WebSite",
                    "@id": "$blogHomeUrl/#website",
                    "url": "$blogHomeUrl/",
                    "name": "$publisherName",
                    "description": "$publisherTagline",
                    "publisher": {
                        "@id": "$blogHomeUrl/#organization"
                    },
                    "inLanguage": "$language"
                },
                {
                    "@type": "Organization",
                    "@id": "$blogHomeUrl/#organization",
                    "name": "$publisherName",
                    "alternateName": "$publisherName",
                    "url": "$blogHomeUrl",
                    "logo": {
                        "@type": "ImageObject",
                        "inLanguage": "$language",
                        "@id": "$blogHomeUrl",
                        "url": "$logoImageUrl",
                        "contentUrl": "$logoImageUrl",
                        "caption": "$publisherName"
                    },
                    "image": {
                        "@id": "$blogHomeUrl"
                    },
                    "sameAs": [
                        "$facebookProfileLink",
                        "$threadsProfileLink",
                        "$instagramProfileLink",
                        "$linkedinProfileLink"
                    ]
                },
                {
                    "@type": "Person",
                    "@id": "$blogHomeUrl",
                    "name": "$publisherName"
                }
            ]
        }
        </script>
        <link rel="stylesheet" href="blog.css"/>
        <link rel="stylesheet" href="stylesheet.css"/>
        <!-- bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <!-- Font Awesome link -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
        <script src="https://kit.fontawesome.com/6c53136549.js" crossorigin="anonymous"></script>
    </head>
    <body>
    <header class="header">
        <div class="row">
            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-6 col-6 header_col1">
                <img src="globals/simpliALogo.png" alt="">
            </div>
            <div class="col-xl-10 col-lg-10 col-md-10 col-sm-0 col-0 header_col2">
                <a href="">xxxxx</a>
                <a href="">xxxxx</a>
                <a href="">xxxxx</a>
                <a href="">xxxxx</a>
                <a href="">xxxxx</a>
            </div>
            <div class="col-xl-0 col-lg-0 col-md-0 col-sm-6 col-6 header_col3">
                <a><i onclick="hamburgerToggle()" class="fa-solid fa-bars"></i></a>
            </div>
        </div>
        <div id="mobileMenu" class="mobile_menu">
            <ul>
                <li><a href="">xxxxx</a></li>
                <li><a href="">xxxxx</a></li>
                <li><a href="">xxxxx</a></li>
                <li><a href="">xxxxx</a></li>
                <li><a href="">xxxxx</a></li>
            </ul>
        </div>
    </header>
    <div class="container">
        <img src="$featuredImage" class="featured-image" alt="Featured Image">
        <h1 class="post-title">$title</h1>
        <p class="post-meta">By $publisherName | $formattedPublishDate</p>
        <div class="post-content">$content</div>
        <p class="post-tags">Tags: $tagLinksString</p>
    </div>

    <footer class="footer">
        <div class="footer_sec">
            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 footer_col1">
                    <div class="footer_logo">
                        <img src="$publisherLogo" alt="">
                    </div>
                    <div class="footer_tagline">
                        <h5>$publisherTagline</h5>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 footer_col2">
                    <a href="">xxxxxx</a>
                    <a href="">xxxxxx</a>
                    <a href="">xxxxxx</a>
                    <a href="">xxxxxx</a>
                    <a href="">xxxxxx</a>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 footer_col3">
                    <p><span style="font-weight: bold;">Address: </span>$publisherAddress</p>
                    <p><span style="font-weight: bold;">Mobile: </span>$publisherMobile</p>
                    <p><span style="font-weight: bold;">Email: </span>$publisherEmail</p>
                </div>
            </div>
        </div>
        <div class="footer_section">
            <div class="row">
                <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12 footer_section_col1">
                    <p>Copyright © 2024</p>
                    <a href="$privacyPolicyUrl">Privacy Policy</a>
                    <a href="$termsAndConditionUrl">Terms & Conditions</a>
                    <a href="$siteMapUrl">Sitemap</a>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 footer_section_col2">
                    <a href="$facebookProfileLink"><i class="fa-brands fa-facebook"></i></a>
                    <a href="$instagramProfileLink"><i class="fa-brands fa-instagram"></i></a>
                    <a href="$twitterProfileLink"><i class="fa-brands fa-x-twitter"></i></a>
                    <a href="$linkedinProfileLink"><i class="fa-brands fa-linkedin"></i></a>
                    <a href="$youtubeProfileLink"><i class="fa-brands fa-youtube"></i></a>
                    <a href="$whatsappProfileLink"><i class="fa-brands fa-whatsapp"></i></a>
                </div>
            </div>
        </div>
    </footer>
    <!-- bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script>
        function hamburgerToggle() {
            var menu = document.getElementById("mobileMenu");
            if (menu.style.display === "block") {
                menu.style.display = "none";
            } else {
                menu.style.display = "block";
            }
        }
    </script>
    </body>
    </html>
HTML;

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
