// Generate particles dynamically
const particlesContainer = document.querySelector('.particles');
if (!particlesContainer) {
  console.error('Particles container not found. Check if .particles div exists in HTML.');
} else {
  for (let i = 0; i < 20; i++) {
    const span = document.createElement('span');
    span.classList.add('particle');
    span.style.left = Math.random() * 100 + 'vw';
    span.style.animationDuration = (10 + Math.random() * 15) + 's';
    span.style.animationDelay = (Math.random() * 10) + 's';
    particlesContainer.appendChild(span);
  }
}

// Form submission handling
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
  }
});

// Show popup after 2 seconds
window.onload = function() {
  setTimeout(() => {
    const popup = document.getElementById('popup-message');
    if (popup) {
      popup.classList.add('show');
    } else {
      console.error('Popup message element not found.');
    }
  }, 2000);
};

// Close popup
function closePopup() {
  const popup = document.getElementById('popup-message');
  if (popup) {
    popup.classList.remove('show');
  }
}

// Close popup when clicking outside
document.addEventListener('click', function(event) {
  const popup = document.getElementById('popup-message');
  if (popup && !popup.contains(event.target) && popup.classList.contains('show')) {
    closePopup();
  }
});

// Scroll-triggered animation for images and cards
function checkAnimations() {
  const containers = document.querySelectorAll('.image-container, .project-card');
  const windowHeight = window.innerHeight;

  containers.forEach(container => {
    const rect = container.getBoundingClientRect();
    if (rect.top >= 0 && rect.top < windowHeight * 0.75) {
      const animation = container.getAttribute('data-animation') || 'animate__fadeInUp';
      const delay = container.getAttribute('data-delay') || '0s';
      container.style.opacity = '1';
      container.classList.add(animation);
      container.style.transitionDelay = delay;
    }
  });
}

window.addEventListener('scroll', checkAnimations);
window.addEventListener('load', checkAnimations);