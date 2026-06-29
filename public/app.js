
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


// Scroll-triggered animation for images and cards
function checkAnimations() {
  const containers = document.querySelectorAll('.image-container, .project-card');
  const windowHeight = window.innerHeight;
  containers.forEach(container => {
    const rect = container.getBoundingClientRect();
    if (rect.top >= 0 && rect.top < windowHeight * 0.9) {
      const animation = container.getAttribute('data-animation') || 'animate__fadeInUp';
      container.style.opacity = '1';
      container.classList.add(animation);
    }
  });
}
window.addEventListener('scroll', checkAnimations);
window.addEventListener('load', checkAnimations);
checkAnimations();

// ===== SCROLL PROGRESS BAR =====
window.addEventListener('scroll', () => {
    const scrollTop = window.scrollY;
    const docHeight = document.documentElement.scrollHeight - window.innerHeight;
    const progress = (scrollTop / docHeight) * 100;
    const bar = document.getElementById('progress-bar');
    if (bar) bar.style.width = progress + '%';
});

// ===== BACK TO TOP BUTTON =====
window.addEventListener('scroll', () => {
    const btn = document.getElementById('back-to-top');
    if (btn) {
        if (window.scrollY > 300) {
            btn.style.display = 'flex';
        } else {
            btn.style.display = 'none';
        }
    }
});

// ===== TYPING ANIMATION =====
const typingTexts = [
    'Custom Software Solutions',
    'Mobile App Development',
    'Web Development Services',
    'IT Consultancy',
    'Cloud & DevOps Solutions'
];
let typingIndex = 0;
let charIndex = 0;
let isDeleting = false;

function typeEffect() {
    const el = document.getElementById('typing-text');
    if (!el) return;
    const current = typingTexts[typingIndex];
    if (isDeleting) {
        el.textContent = current.substring(0, charIndex - 1);
        charIndex--;
    } else {
        el.textContent = current.substring(0, charIndex + 1);
        charIndex++;
    }
    if (!isDeleting && charIndex === current.length) {
        setTimeout(() => { isDeleting = true; }, 1500);
    } else if (isDeleting && charIndex === 0) {
        isDeleting = false;
        typingIndex = (typingIndex + 1) % typingTexts.length;
    }
    setTimeout(typeEffect, isDeleting ? 60 : 100);
}
document.addEventListener('DOMContentLoaded', typeEffect);

// ===== VISITOR COUNTER =====
fetch('/visitor.php')
    .then(r => r.json())
    .then(data => {
        const el = document.getElementById('visitor-count');
        if (el) el.textContent = data.total + ' visitors';
    })
    .catch(() => {});
