const express = require('express');
const bodyParser = require('body-parser');
const fs = require('fs');
const path = require('path');
const cors = require('cors');
const multer = require('multer'); // Multer is used for handling file uploads

const app = express();
const port = 3000;

// Configure multer for file uploads
const upload = multer({ dest: path.join(__dirname, 'uploads') }); // Files will be saved to the 'uploads' folder in the root directory

app.use(bodyParser.json({ limit: '10mb' }));
app.use(cors());

// Endpoint to save HTML file
app.post('/save-html', (req, res) => {
    const { filename, content } = req.body;
    const filePath = path.join(__dirname, filename); // Save to the root directory

    fs.writeFile(filePath, content, (err) => {
        if (err) {
            console.error('Error saving file:', err);
            res.status(500).send('Error saving file');
        } else {
            console.log('File saved:', filePath);
            res.send('File saved successfully');
        }
    });
});

// Endpoint to handle file uploads
app.post('/upload-file', upload.single('file'), (req, res) => {
    const file = req.file;
    const targetPath = path.join(__dirname, 'uploads', file.originalname);

    fs.rename(file.path, targetPath, (err) => {
        if (err) {
            console.error('Error moving file:', err);
            res.status(500).send('Error moving file');
        } else {
            const fileUrl = `/uploads/${file.originalname}`;
            console.log('File uploaded:', fileUrl);
            res.json({ url: fileUrl });
        }
    });
});

app.listen(port, () => {
    console.log(`Server running at http://localhost:${port}`);
});
