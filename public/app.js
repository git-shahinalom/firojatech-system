
document.getElementById('contactForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  const formData = new FormData(e.target);
  const submitButton = e.submitter;
  const target = submitButton.getAttribute('data-target');
  let url = '';
  if (target === 'php') {
    url = '/submit.php';
  } else if (target === 'node') {
    url = '/submit';
  }
  if (url) {
    const response = await fetch(url, {
      method: 'POST',
      body: formData
    });
    const result = await response.json();
    alert(result.message || 'Submission failed!');
    e.target.reset();  // ← এটা add করো
  }
});


// Job Application Form
document.getElementById('applyForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  const formData = new FormData(e.target);
  const response = await fetch('/submit.php', {
    method: 'POST',
    body: formData
  });
  const result = await response.json();
  alert(result.message || 'Submission failed!');
  e.target.reset();
});
