
const express = require('express');
const bodyParser = require('body-parser');

const app = express();
const port = process.env.PORT || 3000;

// Middleware
app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());

// Serve static files
app.use(express.static('public'));

// Contact Form Submission
app.post('/submit', (req, res) => {
    const { name, email, message } = req.body;
    console.log('Contact Form Data:', { name, email, message });
    res.json({ success: true, message: 'Form submitted successfully!' });
});

// Job Application Submission
app.post('/apply', (req, res) => {
    const { name, email, position, coverLetter } = req.body;
    console.log('Job Application Data:', { name, email, position, coverLetter });
    res.json({ success: true, message: 'Application submitted successfully!' });
});

// Start Server
app.listen(port, () => {
    console.log(`Node.js API running at http://localhost:${port}`);
});