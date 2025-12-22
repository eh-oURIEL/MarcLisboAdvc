<?php
function gerarSlug($texto) {
  $texto = iconv('UTF-8', 'ASCII//TRANSLIT', $texto);
  $texto = strtolower($texto);
  $texto = preg_replace('/[^a-z0-9]+/', '-', $texto);
  return trim($texto, '-');
}

$artigos = json_decode(file_get_contents('artigos.json'), true) ?? [];

// ordena do mais recente para o mais antigo
usort($artigos, fn($a, $b) => $b['id'] <=> $a['id']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Marciano Lisboa - Blog Jurídico</title>
<link rel="icon" type="image/png" href="img/imgLogo/logoRodape.png">

<meta name="description" content="Artigos jurídicos e informativos.">

        <script src="https://kit.fontawesome.com/2a1053f0dc.js" crossorigin="anonymous"></script>


<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gradient-to-b from-[#7a0b0b] to-[#4d0505]">

<?php echo file_get_contents('header.html'); ?>

<!-- TOPO -->
<div class="w-full bg-[#5f0707] py-6 mb-10 shadow-lg">
  <div class="max-w-5xl mx-auto px-4 text-center text-white">
    <h2 class="text-xl font-bold my-3">Conhecimento que transforma.</h2>
    <p class="opacity-80 text-sm">
      Artigos, análises e orientações produzidas pelo escritório Marciano Lisboa Advocacia.
    </p>
  </div>
</div>

<!-- CONTAINER PRINCIPAL -->
<div class="max-w-5xl mx-auto bg-white rounded-2xl mt-10 mb-10 p-8 shadow-2xl">

  <h1 class="text-3xl font-bold text-center text-[#780909] mb-6">
    Artigos Publicados
  </h1>

  <!-- FILTRO (MODELO ORIGINAL) -->
  <div class="mb-8 flex justify-center">
    <select id="filtroCategoria"
      class="border rounded-lg px-4 py-2 w-full max-w-xs">
      <option value="todas">Todas as categorias</option>
      <option value="Direito Civil">Direito Civil</option>
      <option value="Família">Direito da Família</option>
      <option value="Penal">Direito Penal</option>
      <option value="Consumidor">Direito do Consumidor</option>
      <option value="Saúde">Direito da Saúde</option>
      <option value="Contratual">Direito Contratual</option>
      <option value="Trabalhista">Direito Trabalhista</option>
      <option value="Previdenciária">Direito Previdenciário</option>
      <option value="Tributária">Direito Tributário</option>
      <option value="Administrativa">Direito Administrativo</option>
      <option value="Empresarial">Direito Empresarial</option>
    </select>
  </div>

  <!-- LISTA DE ARTIGOS -->
  <div class="space-y-6" id="listaArtigos">

    <?php foreach ($artigos as $a): ?>
      <?php $slug = gerarSlug($a['titulo']); ?>

      <article
        class="artigo bg-gray-100 p-6 rounded shadow hover:shadow-lg transition"
        data-categoria="<?= htmlspecialchars($a['categoria']) ?>">

        <h2 class="text-xl font-bold mb-2">
          <?= htmlspecialchars($a['titulo']) ?>
        </h2>

        <p class="text-sm text-gray-500 mb-4">
          Categoria: <?= htmlspecialchars($a['categoria']) ?>
        </p>

        <a href="blog/<?= $slug ?>" class="text-[#780909] font-semibold hover:underline">
          Ler artigo →
        </a>

      </article>
    <?php endforeach; ?>

  </div>
</div>

<!-- FORMULÁRIO -->
<section id="formulario" class=" text-white py-14 flex flex-col items-center my-16 rounded-2xl shadow-xl">
  <h1 class="text-3xl font-bold mb-4">Como podemos ajudar você hoje?</h1>
  <p class="text-center max-w-md mb-6">
    Nos descreva seu problema ou dúvida.<br>
    Entraremos em contato o mais breve possível!
  </p>

  <form action="https://formsubmit.co/contato@marcianolisboaadvocacia.com.br"
        method="POST"
        class="w-full max-w-md space-y-4">

    <input type="hidden" name="_subject" value="Nova mensagem do site!">

    <input type="text" name="nome" placeholder="Nome" required class="w-full p-3 rounded-md border">
    <input type="text" name="telefone" placeholder="DDD + Telefone" required class="w-full p-3 rounded-md border">
    <input type="email" name="email" placeholder="E-mail" required class="w-full p-3 rounded-md border">
    <input type="text" name="assunto" placeholder="Assunto" required class="w-full p-3 rounded-md border">
    <textarea name="mensagem" rows="4" placeholder="Mensagem" required class="w-full p-3 rounded-md border"></textarea>

    <button type="submit"
      class="w-full bg-gradient-to-r from-yellow-700 to-yellow-900 text-white p-3 rounded-md hover:from-yellow-700 hover:to-yellow-700 transition">
      Enviar mensagem
    </button>
  </form>
</section>

<?php echo file_get_contents('footer.html'); ?>

<!-- SCRIPT DO FILTRO -->
<script>
const filtro = document.getElementById('filtroCategoria');
const artigos = document.querySelectorAll('.artigo');

filtro.addEventListener('change', () => {
  const categoria = filtro.value;

  artigos.forEach(artigo => {
    artigo.style.display =
      categoria === 'todas' || artigo.dataset.categoria === categoria
        ? 'block'
        : 'none';
  });
});
</script>

</body>
</html>
