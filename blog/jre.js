// Function to extract and upload embedded files (images, videos) from the Quill editor content
async function processQuillContent(content) {
    const parser = new DOMParser();
    const doc = parser.parseFromString(content, 'text/html');
    const elements = doc.querySelectorAll('img, video');

    for (const element of elements) {
        const src = element.getAttribute('src');

        if (src.startsWith('data:')) {
            // Convert base64 to a file
            const response = await fetch(src);
            const blob = await response.blob();
            const file = new File([blob], `embedded_file_${Date.now()}`);
            const url = await uploadFile(file);

            // Replace src with the uploaded file URL
            element.setAttribute('src', url);
        }
    }

    return doc.body.innerHTML;
}

// Function to process blog data and create HTML
async function processBlogData() {
    const title = document.getElementById('postTitle').value;
    const content = quill.root.innerHTML; // Assuming you're using Quill.js for the editor
    const processedContent = await processQuillContent(content);
    const focusKeyphrase = document.getElementById('focusKeyphrase').value;
    const seoTitle = document.getElementById('seoTitle').value;
    const slug = document.getElementById('slug').value;
    const metaDescription = document.getElementById('MetaDescription').value;

    // Collect featured image (if any)
    const imageInput = document.getElementById('image-input');
    const file = imageInput.files[0];
    let imageURL = '';

    if (file) {
        imageURL = await uploadFile(file);
    }

    // Create HTML content
    const htmlContent = `
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>${seoTitle}</title>
        <meta name="description" content="${metaDescription}">
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <h1>${title}</h1>
        <img src="${imageURL}" alt="Featured Image">
        <div>${processedContent}</div>
    </body>
    </html>
    `;

    const filename = `${slug}.html`;
    await saveHTMLFile(filename, htmlContent);
}

// Add event listener to publish button
document.getElementById('publish').addEventListener('click', processBlogData);
