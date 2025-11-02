const express = require('express');
const bodyParser = require('body-parser');
const fetch = require('node-fetch'); // <-- যোগ করো (নিচে দেখো)

const app = express();
const port = process.env.PORT || 3000;

// Middleware
app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());
app.use(express.static('public'));

// Contact Form → PHP → MySQL
app.post('/submit', async (req, res) => {
    try {
        const response = await fetch('http://php:80/submit.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(req.body)
        });
        const data = await response.json();
        res.json(data);
    } catch (error) {
        console.error('PHP Error:', error.message);
        res.json({ success: false, message: 'Server error' });
    }
});

// Job Application → PHP → MySQL
app.post('/apply', async (req, res) => {
    try {
        const response = await fetch('http://php:80/submit.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(req.body)
        });
        const data = await response.json();
        res.json(data);
    } catch (error) {
        console.error('PHP Error:', error.message);
        res.json({ success: false, message: 'Server error' });
    }
});

// Start Server
app.listen(port, () => {
    console.log(`Node.js API running at http://localhost:${port}`);
});