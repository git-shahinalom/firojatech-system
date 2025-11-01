
const express = require('express');
const bodyParser = require('body-parser');


// Middleware to parse form data
app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());

// Serve static files (HTML, CSS, JS)
app.use(express.static('public'));

// Handle form submission
app.post('/submit', (req, res) => {
  const { name, email, message } = req.body;
  console.log('Form Data:', { name, email, message });

  // Here you can add logic to save to a database or send an email
  res.json({ success: true, message: 'Form submitted successfully!' });
});

// Start server
app.listen(port, () => {
  console.log(`Server running at http://localhost:${80}`);
});



const express = require('express');
const bodyParser = require('body-parser');
const app = express();
const port = process.env.PORT || 3000;

app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());
app.use(express.static('public'));

// Handle contact form
app.post('/submit', (req, res) => {
    const { name, email, message } = req.body;
    console.log('Contact Form Data:', { name, email, message });
    res.json({ success: true, message: 'Form submitted successfully!' });
});

// Handle job application
app.post('/apply', (req, res) => {
    const { name, email, position, coverLetter } = req.body;
    console.log('Job Application Data:', { name, email, position, coverLetter });
    res.json({ success: true, message: 'Application submitted successfully!' });
});

app.listen(port, () => {
    console.log(`Server running at http://localhost:${port}`);
});