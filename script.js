// script.js

// Toggle mobile menu
const menuBtn = document.getElementById('menu-btn');
const mobileMenu = document.getElementById('mobile-menu');
if (menuBtn && mobileMenu) {
  menuBtn.addEventListener('click', () => {
    mobileMenu.classList.toggle('hidden');
  });
}

// Card toggle function
function toggleCard(card) {
  const img = card.querySelector('.card-img');
  const title = card.querySelector('.card-title');
  const desc = card.querySelector('.card-desc');

  if (desc.classList.contains('hidden')) {
    img.classList.add('hidden');
    title.classList.add('hidden');
    desc.classList.remove('hidden');
  } else {
    img.classList.remove('hidden');
    title.classList.remove('hidden');
    desc.classList.add('hidden');
  }
}

// Slider logic (with existence check) citeturn1file10
const slides = document.getElementById('slides');
let totalSlides = 0;
let index = 0;

if (slides) {
  totalSlides = slides.children.length;
  // Place your slider logic here, e.g.:
  // setInterval(() => {
  //   slides.children[index].classList.remove('active');
  //   index = (index + 1) % totalSlides;
  //   slides.children[index].classList.add('active');
  // }, 5000);
}

// MutationObserver to remove Elfsight badge citeturn1file10
const observer = new MutationObserver((mutations) => {
  mutations.forEach(({ addedNodes }) => {
    addedNodes.forEach(node => {
      if (node.nodeType === 1) {
        // Direct <a> badge
        if (
          node.tagName === 'A' &&
          node.href &&
          node.href.includes('elfsight.com/google-reviews-widget')
        ) {
          node.remove();
        }
        // Badge nested within a subtree
        if (node.querySelector) {
          const inside = node.querySelector('a[href*="elfsight.com/google-reviews-widget"]');
          if (inside) inside.remove();
        }
      }
    });
  });
});

// Start observing the body for dynamic injections
observer.observe(document.body, {
  childList: true,
  subtree: true
});
