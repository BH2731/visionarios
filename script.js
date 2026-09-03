document.addEventListener("DOMContentLoaded", () => {
  const elements = document.querySelectorAll(".fade-in, .slide-in-left, .slide-in-right");

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add("visible");
        observer.unobserve(entry.target); // evita repetir animação
      }
    });
  }, { threshold: 0.1 });

  elements.forEach(el => observer.observe(el));
});

function applyFilter(type) {
  const body = document.body;
  body.classList.add('filtered');
  switch(type) {
    case 'daltonismo':
      body.style.filter = 'url(#daltonismo)';
      break;
    case 'deuteranopia':
      body.style.filter = 'url(#deuteranopia)';
      break;
    case 'protanopia':
      body.style.filter = 'url(#protanopia)';
      break;
    case 'tritanopia':
      body.style.filter = 'url(#tritanopia)';
      break;
    case 'monochromacy':
      body.style.filter = 'grayscale(100%)';
      break;
    default:
      body.style.filter = 'none';
  }
}