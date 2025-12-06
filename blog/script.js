// ===============================
// IMAGENS FIXAS POR CATEGORIA
// ===============================
const imagensCategorias = {
  civil: "../img/imgCategoria/civil.png",
  saude: "../img/imgCategoria/saude.png",
  familia: "../img/imgCategoria/familia.png",
  penal: "../img/imgCategoria/penal.png",
  consumidor: "../img/imgCategoria/consumidor.png",
  default: "../img/imgCategoria/default.png"
};

// ===============================
// VARIÁVEIS DO SISTEMA
// ===============================
let artigos = [];
let paginaAtual = 1;
const itensPorPagina = 5;
let categoriaSelecionada = "todas";

// ===============================
// CARREGAR ARTIGOS
// ===============================
fetch("artigos.json")
  .then(r => r.json())
  .then(data => {
    artigos = data;
    renderizar();
  });

// ===============================
// FILTRO DE CATEGORIAS
// ===============================
const botoesCategorias = document.querySelectorAll(".cat-btn");

botoesCategorias.forEach(btn => {
  btn.addEventListener("click", () => {

    categoriaSelecionada = btn.dataset.cat;
    paginaAtual = 1;

    // Remove estilos de ativo
    botoesCategorias.forEach(b => {
      b.classList.remove("bg-[#780909]", "text-white");
      b.classList.add("bg-gray-200");
    });

    // Ativa o botão clicado
    btn.classList.remove("bg-gray-200");
    btn.classList.add("bg-[#780909]", "text-white");

    renderizar();
  });
});

// Ativar categoria "todas" ao iniciar
const botTodas = document.querySelector('[data-cat="todas"]');
if (botTodas) {
  botoesCategorias.forEach(b => {
    b.classList.remove("bg-[#780909]", "text-white");
    b.classList.add("bg-gray-200");
  });
  botTodas.classList.remove("bg-gray-200");
  botTodas.classList.add("bg-[#780909]", "text-white");
}

// ===============================
// FUNÇÃO PARA RENDERIZAR ARTIGOS
// ===============================
function renderizar() {
  const lista = document.getElementById("lista");
  lista.innerHTML = "";

  let filtrados = categoriaSelecionada === "todas"
    ? artigos
    : artigos.filter(a => a.categoria === categoriaSelecionada);

  const inicio = (paginaAtual - 1) * itensPorPagina;
  const pagina = filtrados.slice(inicio, inicio + itensPorPagina);

  pagina.forEach(a => {
    const card = document.createElement("div");
    card.className = "card rounded-xl p-6";

    // Seleciona a imagem da categoria
    const imgCategoria = imagensCategorias[a.categoria] || imagensCategorias.default;

    card.innerHTML = `
      <div class="flex gap-4">

        <!-- IMAGEM REPRESENTATIVA DA CATEGORIA -->
        <img src="${imgCategoria}"
             class="w-24 h-24 object-contain rounded-lg shadow">

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

// ===============================
// PAGINAÇÃO
// ===============================
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

// ===============================
// FORMATAÇÃO DE DATAS
// ===============================
function formatarData(dateStr) {
  const data = new Date(dateStr);
  return data.toLocaleDateString("pt-BR", {
    day: "2-digit",
    month: "long",
    year: "numeric"
  });
}
