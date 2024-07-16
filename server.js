const express = require('express');
const bodyParser = require('body-parser');
const fs = require('fs');
const path = require('path');
const cors = require('cors'); // Import cors package

const app = express();
const port = 3000;

app.use(bodyParser.json({ limit: '10mb' }));
app.use(cors()); // Enable CORS for all routes

// Endpoint to save HTML file
app.post('/save-html', (req, res) => {
    const { filename, content } = req.body;
    const filePath = path.join(__dirname, filename);

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

app.listen(port, () => {
    console.log(`Server running at http://localhost:${port}`);
});
