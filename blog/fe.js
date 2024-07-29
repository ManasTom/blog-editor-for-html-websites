// Function to upload file to local server
async function uploadFile(file) {
    const formData = new FormData();
    formData.append('file', file);

    try {
        const response = await fetch('http://localhost:3000/upload-file', {
            method: 'POST',
            body: formData
        });

        if (response.ok) {
            const data = await response.json();
            return data.url;
        } else {
            console.error('Error uploading file:', response.statusText);
        }
    } catch (error) {
        console.error('Error uploading file:', error);
    }
}

// Function to save HTML file to the server
async function saveHTMLFile(filename, content) {
    try {
        const response = await fetch('http://localhost:3000/save-html', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ filename, content })
        });

        if (response.ok) {
            console.log('File saved successfully');
        } else {
            console.error('Error saving file:', response.statusText);
        }
    } catch (error) {
        console.error('Error saving file:', error);
    }
}
 