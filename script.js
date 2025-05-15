const menuBtn = document.getElementById('menu-btn');
          const mobileMenu = document.getElementById('mobile-menu');
      
          menuBtn.addEventListener('click', () => {
              mobileMenu.classList.toggle('hidden');
          });

    function toggleCard(card) {
    const img = card.querySelector('.card-img');
    const title = card.querySelector('.card-title');
    const desc = card.querySelector('.card-desc');
        
    // Se a descrição está oculta → mostrar descrição e ocultar imagem + título
    if (desc.classList.contains('hidden')) {
        img.classList.add('hidden');
        title.classList.add('hidden');
       desc.classList.remove('hidden');
   } else {
        // Voltar para o estado inicial
        img.classList.remove('hidden');
        title.classList.remove('hidden');
        desc.classList.add('hidden');
    }
}

const slides = document.getElementById('slides');
const totalSlides = slides.children.length;
let index = 0;

document.getElementById('next').addEventListener('click', () => {
  index = (index + 1) % totalSlides;
  slides.style.transform = `translateX(-${index * 100}%)`;
});

document.getElementById('prev').addEventListener('click', () => {
  index = (index - 1 + totalSlides) % totalSlides;
  slides.style.transform = `translateX(-${index * 100}%)`;
});