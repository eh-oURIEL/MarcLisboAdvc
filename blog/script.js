let artigos = [];
let paginaAtual = 1;
const itensPorPagina = 5;
let categoriaSelecionada = "todas";

// Carregar artigos
fetch("artigos.json")
  .then(r => r.json())
  .then(data => {
    artigos = data;
    renderizar();
  });

// Filtro por categoria
const botoesCategorias = document.querySelectorAll(".cat-btn");

botoesCategorias.forEach(btn => {
  btn.addEventListener("click", () => {

    // Define a categoria selecionada
    categoriaSelecionada = btn.dataset.cat;
    paginaAtual = 1;

    // Remove estilos de "ativo" de todos os botões
    botoesCategorias.forEach(b => {
      b.classList.remove("bg-[#780909]", "text-white", "bg-gray-200", "hover:bg-gray-300");
      // garante que o estado visual volte ao padrão cinza
      b.classList.add("bg-gray-200");
    });

    // Ativa o botão clicado (fundo vermelho + texto branco)
    btn.classList.remove("bg-gray-200");
    btn.classList.add("bg-[#780909]", "text-white");

    renderizar();
  });
});

// Ativar o botão "Todas" na carga inicial
const botTodas = document.querySelector('[data-cat="todas"]');
if (botTodas) {
  // limpa qualquer classe conflituosa e aplica o estado ativo
  botoesCategorias.forEach(b => {
    b.classList.remove("bg-[#780909]", "text-white");
    b.classList.add("bg-gray-200");
  });

  botTodas.classList.remove("bg-gray-200");
  botTodas.classList.add("bg-[#780909]", "text-white");
}

function renderizar() {
  const lista = document.getElementById("lista");
  lista.innerHTML = "";

  let filtrados = categoriaSelecionada === "todas"
    ? artigos
    : artigos.filter(a => a.categoria === categoriaSelecionada);

  // Paginação
  const inicio = (paginaAtual - 1) * itensPorPagina;
  const pagina = filtrados.slice(inicio, inicio + itensPorPagina);

  pagina.forEach(a => {
    const card = document.createElement("div");
    card.className = "card rounded-xl p-6";

    card.innerHTML = `
      <div class="flex gap-4">

        <!-- THUMBNAIL -->
        <img src="${a.thumbnail || 'thumb-default.jpg'}"
             class="w-24 h-24 object-cover rounded-lg shadow">

        <div class="flex flex-col">

          <h3 class="text-2xl font-semibold text-[#780909] mb-2">
            ${a.titulo}
          </h3>

          <p class="text-gray-600 text-sm mb-2">
            Publicado em: <strong>${formatarData(a.data)}</strong>
          </p>

          <a href="${a.arquivo}"
            class="inline-block bg-[#780909] text-white py-2 px-5 mt-auto rounded-lg shadow
                   hover:bg-[#5f0707] transition">
            Ler artigo
          </a>

        </div>
      </div>
    `;

    lista.appendChild(card);
  });
}

document.getElementById("prev").onclick = () => {
  if (paginaAtual > 1) {
    paginaAtual--;
    renderizar();
  }
};

document.getElementById("next").onclick = () => {
  paginaAtual++;
  renderizar();
};

function formatarData(dateStr) {
  const data = new Date(dateStr);
  return data.toLocaleDateString("pt-BR", {
    day: "2-digit",
    month: "long",
    year: "numeric"
  });
}
