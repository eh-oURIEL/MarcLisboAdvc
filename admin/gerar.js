// gerar.js (com categoria + thumbnail + integração GitHub)

function getCfg() {
  return {
    token: localStorage.getItem("github_token"),
    user: localStorage.getItem("github_user"),
    repo: localStorage.getItem("github_repo"),
    branch: localStorage.getItem("github_branch") || "main",
    blogPath: localStorage.getItem("github_blog_path") || "blog"
  };
}

function toBase64(str){ return btoa(unescape(encodeURIComponent(str))); }
function fromBase64(b64){ return decodeURIComponent(escape(atob(b64))); }
function slugify(text){ 
  return text.toString().toLowerCase()
    .normalize('NFD')
    .replace(/\p{Diacritic}/gu,'')
    .replace(/[^a-z0-9 -]/g,'')
    .replace(/\s+/g,'-')
    .replace(/-+/g,'-')
    .replace(/^-|-$/g,'');
}

// Ler thumbnail e converter para Base64
function lerThumbnail() {
  return new Promise((resolve) => {
    const file = document.getElementById("thumb").files[0];
    if (!file) return resolve(null);

    const reader = new FileReader();
    reader.onload = () => resolve(reader.result);
    reader.readAsDataURL(file);
  });
}

async function ghFetch(path, opts = {}) {
  const cfg = getCfg();
  if(!cfg.token) throw new Error("Token GitHub não encontrado em localStorage.");

  const headers = Object.assign({}, opts.headers || {}, {
    "Authorization": "Bearer " + cfg.token,
    "Accept": "application/vnd.github+json"
  });

  const res = await fetch(
    `https://api.github.com/repos/${cfg.user}/${cfg.repo}/contents/${encodeURIComponent(path)}`,
    Object.assign({}, opts, { headers })
  );

  return res;
}

async function criarArquivo(path, contentB64, message, sha=null){
  const cfg = getCfg();

  const body = { message, content: contentB64, branch: cfg.branch };
  if (sha) body.sha = sha;

  const res = await ghFetch(path, {
    method: "PUT",
    body: JSON.stringify(body),
    headers: {"Content-Type":"application/json"}
  });

  const text = await res.text();
  let json;

  try { json = JSON.parse(text); }
  catch { json = text; }

  console.log("PUT", path, "status", res.status, json);
  return { ok: res.ok, status: res.status, body: json };
}

async function obterArquivo(path){
  const res = await ghFetch(path, { method: "GET" });
  const text = await res.text();

  if (!res.ok) {
    console.log("GET", path, "status", res.status, text);
    return null;
  }

  const json = JSON.parse(text);
  console.log("GET", path, "status", res.status, json);
  return json;
}

async function gerarArtigo() {
  try {
    const cfg = getCfg();

    const titulo = document.getElementById("titulo").value.trim();
    const data = document.getElementById("data").value || new Date().toISOString().slice(0,10);
    const categoria = document.getElementById("categoria").value;
    const conteudo = quill.root.innerHTML;

    if (!titulo) return alert("Preencha o título.");
    if (!categoria) return alert("Selecione uma categoria.");

    const thumbnail = await lerThumbnail();

    const slug = slugify(titulo) || `artigo-${Date.now()}`;
    const fileName = `${slug}.html`;

    const articlePath = `${cfg.blogPath}/${fileName}`;
    const jsonPath = `${cfg.blogPath}/artigos.json`;

    // HTML final do artigo
    const html = `
<!doctype html>
<html lang="pt-BR" class="bg-gray-100">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>${titulo}</title>

<link rel="stylesheet" href="/styles.css">
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://kit.fontawesome.com/2a1053f0dc.js" crossorigin="anonymous"></script>

<link rel="icon" type="image/png" href="../../img/imgLogo/LogoMLAdvc.png">


</head>
<body class="font-sans leading-relaxed text-gray-800">

<!-- HEADER -->
  <header class="bg-[#780909] p-2">
          <div class="flex items-center justify-between"> 
            <a href="../../index.html"><img src="../../img/imgLogo/LogoMLAdvc.png" alt="" class="w-[150px] h-[150px] object-contain"></a>
              <!-- Botão hamburguer -->
              <button id="menu-btn" class="text-white md:hidden mr-5">
                  <i class="fas fa-bars text-2xl"></i>
              </button>
              <!-- Navegação normal em telas médias+ -->
              <nav class="hidden md:flex items-center space-x-8 mr-5 p-5">
                <a href="https://wa.me/5561998321740?text=Ol%C3%A1%2C%20tudo%20bem%3F%20Gostaria%20de%20entrar%20em%20contato%20para%20saber%20mais%20sobre%20os%20servi%C3%A7os%20jur%C3%ADdicos%20oferecidos.%20Poderiam%20me%20orientar%20sobre%20como%20proceder%3F">
                  <button class="flex items-center space-x-2 text-white border border-white rounded-full px-4 py-2 hover:bg-white hover:text-[#780909] transition duration-500">
                      <i class="fa-brands fa-whatsapp"></i>
                      <p>Fale Conosco</p>
                  </button>
              </a>
              <a href="https://www.instagram.com/marcianolisboaadvocacia/" target="_blank">
                <button class="flex items-center space-x-2 text-white border border-white rounded-full px-4 py-2 hover:bg-white hover:text-[#780909] transition duration-500">
                    <i class="fa-brands fa-instagram"></i>
                    <p>Instagram</p>
                </button>
            </a>
                  <a href="../index.html" class="text-white">Home</a>
                  <a href="index.html" class="text-white">Blog</a>
              </nav>
          </div>
      
          <!-- Menu mobile -->
          <nav id="mobile-menu" class="hidden flex-col space-y-2 mt-4 md:hidden transition duration-1500 p-5">
              <a href="../index.html" class="text-white">Home</a>
              <a href="index.html" class="text-white block">Blog</a>
              <a href="https://wa.me/5561998321740?text=Ol%C3%A1%2C%20tudo%20bem%3F%20Gostaria%20de%20entrar%20em%20contato%20para%20saber%20mais%20sobre%20os%20servi%C3%A7os%20jur%C3%ADdicos%20oferecidos.%20Poderiam%20me%20orientar%20sobre%20como%20proceder%3F" class="text-white block">
                  <button class="flex items-center space-x-2 text-white">
                      <i class="fa-brands fa-whatsapp py-3"></i>
                      <p>Whatsapp</p>
                  </button>
              </a>
              <a href="https://www.instagram.com/marcianolisboaadvocacia/" target="_blank" class="text-white block">
                <button class="flex items-center space-x-2 text-white">
                    <i class="fa-brands fa-instagram"></i>
                    <p>Instagram</p>
                </button>
            </a>
          </nav>
      </header>

<article class="max-w-3xl mx-auto bg-white shadow-lg rounded-xl p-8 my-10">

<header class="my-6 border-b pb-4">
    <h1 class="text-4xl font-bold text-[#780909] mb-2">${titulo}</h1>

    <div class="flex gap-4 text-sm text-gray-500">
        <time datetime="${data}">${data}</time>
        <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full">
            ${categoria.toUpperCase()}
        </span>
    </div>
</header>

${thumbnail ? `<img src="${thumbnail}" class="rounded-lg w-full mb-6 shadow">` : ""}

<section class="prose prose-lg max-w-none">
    ${conteudo}
</section>
</article>

          <section>
            <div class="bg-[#780909] rounded-2xl shadow-xl p-8 my-16 text-center text-white">
              <h2 class="text-3xl font-bold mb-8 ">
                Tem mais dúvidas? Fale conosco!
              </h2>
              <p class="text-center mt-5 mb-10">
                Entre em contato pelo Whatsapp para obter mais informações sobre nossos serviços jurídicos e como podemos ajudar você.
              </p>
              <a href="https://wa.me/5561998321740?text=Ol%C3%A1%2C%20tudo%20bem%3F%20Gostaria%20de%20entrar%20em%20contato%20para%20saber%20mais%20sobre%20os%20servi%C3%A7os%20jur%C3%ADdicos%20oferecidos.%20Poderiam%20me%20orientar%20sobre%20como%20proceder%3F" 
              class="bg-gradient-to-r from-[#1a1f59] to-[#191b30] font-semibold px-6 py-3 rounded shadow-lg hover:from-blue-800 hover:to-blue-900 transition duration-500">
                Entre em contato pelo Whatsapp
              </a>
          </section>

   <footer class="bg-white text-[#780909]">
          <div class="flex flex-col lg:flex-row justify-between items-center px-6 py-8 max-w-6xl mx-auto">
              <!-- Logo e nome -->
              <div class="flex flex-col items-center lg:items-start mb-6 lg:mb-0">
                  <img src="../img/imgLogo/logoRodape.png" alt="Logo" class="w-[150px] h-auto mb-4">
              </div>
      
              <!-- Linha vertical para separar no desktop -->
              <div class="hidden lg:block h-40 w-px bg-[#780909] mx-8"></div>
      
              <!-- Informações -->
              <div class="flex flex-col space-y-4">
                  <a href="https://wa.me/5561998321740?text=Ol%C3%A1%2C%20tudo%20bem%3F%20Gostaria%20de%20entrar%20em%20contato%20para%20saber%20mais%20sobre%20os%20servi%C3%A7os%20jur%C3%ADdicos%20oferecidos.%20Poderiam%20me%20orientar%20sobre%20como%20proceder%3F" class="flex items-center space-x-2">
                      <i class="fa-solid fa-mobile-screen-button"></i>
                      <p>(61) 9 9832-1740</p>
                  </a>
                  <a href="mailto:contato@marcianolisboaadvocacia.com.br?subject=Olá, tudo bem? Gostaria de entrar em contato para saber mais sobre os serviços jurídicos oferecidos. Poderiam me orientar sobre como proceder?" class="flex items-center space-x-2">
                      <i class="fa-solid fa-envelope"></i>
                      <p>contato@marcianolisboaadvocacia.com.br</p>
                  </a>
                  <a href="https://www.instagram.com/marcianolisboaadvocacia/" class="flex items-center space-x-2">
                      <i class="fa-brands fa-instagram"></i>
                      <p>Marcianolisboaadvocacia</p>
                  </a>
                  <a href="https://maps.app.goo.gl/cVFQwt3W9vKN3YFt9" class="flex items-center space-x-2">
                      <i class="fa-solid fa-location-dot"></i>
                      <p>Santa Maria-DF: QC 01, Conjunto N, Lote 08 Loja 01</p>
                  </a>
                  <a href="https://maps.app.goo.gl/NirJG6GaN31TzzJ5A" class="flex items-center space-x-2">
                      <i class="fa-solid fa-location-dot"></i>
                      <p>Gama-DF: Quadra 01, Conjunto B, Lote 320, Setor Norte</p>
                  </a>
              </div>
          </div>
      
          <!-- Faixa inferior -->
          <div class="bg-[#780909] h-5 w-full text-center pt-5 pb-20 text-white text-sm">
            <p  class="">Marciano Lisboa Advocacia © 2025 - Todos os direitos reservados</p>
            <p class="mt-4">Desenvolvido por: <a href="https://www.instagram.com/uriel_dsz">Uriel de Souza</a></p>
          </div>
      </footer>

 <!-- JS EXTERNO -->
  <script src="../script.js"></script>
  <script src="../../script.js"></script>

</body>
</html>`;

    const htmlB64 = toBase64(html);

    console.log("Enviando artigo para:", articlePath);
    const resCreate = await criarArquivo(articlePath, htmlB64, `Add article ${fileName}`);

    if (!resCreate.ok) {
      alert("Erro criando artigo. Confira o console.");
      return;
    }

    // Atualizar artigos.json
    console.log("Buscando", jsonPath);
    const jsonFile = await obterArquivo(jsonPath);

    let lista = [];
    let sha = null;

    if (jsonFile) {
      sha = jsonFile.sha;

      try { lista = JSON.parse(fromBase64(jsonFile.content)); }
      catch { lista = []; }
    }

    // remove duplicados
    lista = lista.filter(x => x.arquivo !== fileName);

    // adicionar nova entrada
    lista.unshift({
      titulo,
      data,
      categoria,
      thumbnail,
      arquivo: fileName
    });

    const novoJsonB64 = toBase64(JSON.stringify(lista, null, 2));

    const resUpdate = await criarArquivo(jsonPath, novoJsonB64, `Update artigos.json`, sha);

    if (!resUpdate.ok) {
      alert("Erro ao atualizar artigos.json.");
      return;
    }

    alert("Artigo criado com sucesso!");
    
  } catch (err) {
    console.error("Erro em gerarArtigo:", err);
    alert("Erro: " + err.message);
  }
}

// PREVIEW
function visualizarArtigo() {
    const titulo = document.getElementById("titulo").value;
    const data = document.getElementById("data").value;
    const categoria = document.getElementById("categoria").value;
    const conteudo = quill.root.innerHTML;

    document.getElementById("previewTitulo").textContent = titulo;
    document.getElementById("previewData").textContent = new Date(data).toLocaleDateString("pt-BR");
    document.getElementById("previewTexto").innerHTML = conteudo;

    document.getElementById("previewModal").style.display = "flex";
}

function fecharPreview() {
    document.getElementById("previewModal").style.display = "none";
}
