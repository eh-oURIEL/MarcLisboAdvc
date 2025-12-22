<?php
// Função para gerar slug a partir do título
function gerarSlug($texto) {
  $texto = iconv('UTF-8', 'ASCII//TRANSLIT', $texto);
  $texto = strtolower($texto);
  $texto = preg_replace('/[^a-z0-9]+/', '-', $texto);
  return trim($texto, '-');
}

// Recebe o slug pela URL amigável
$slug = $_GET['slug'] ?? null;

$artigos = json_decode(file_get_contents('artigos.json'), true) ?? [];

$artigo = null;
foreach ($artigos as $a) {
  if (gerarSlug($a['titulo']) === $slug) {
    $artigo = $a;
    break;
  }
}

if (!$artigo) {
  http_response_code(404);
  echo 'Artigo não encontrado';
  exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title><?php echo htmlspecialchars($artigo['titulo']); ?></title>

<meta name="description" content="<?php echo strip_tags(substr($artigo['conteudo'], 0, 150)); ?>">

 <link rel="icon" type="image/png" href="../img/imgLogo/logoRodape.png">

<script src="https://cdn.tailwindcss.com"></script>

<style>
.article-content h1 { font-size: 2rem; font-weight: bold; margin: 1.2rem 0; }
.article-content h2 { font-size: 1.6rem; font-weight: bold; margin: 1rem 0; }
.article-content h3 { font-size: 1.3rem; font-weight: bold; margin: 0.8rem 0; }
.article-content p  { margin-bottom: 1rem; line-height: 1.7; }
.article-content img { max-width: 100%; border-radius: 8px; margin: 1rem 0; }
.article-content blockquote {
  border-left: 4px solid #780909;
  padding-left: 1rem;
  color: #555;
  margin: 1rem 0;
}
</style>
</head>

<body class="bg-gray-100">

<?php echo file_get_contents('admin/admin_header.html'); ?>

<header class="bg-gradient-to-r from-[#780909] to-[#5f0707] text-white py-10 shadow-md border-t-4 border-yellow-800 mb-6 ">
  <div class="max-w-6xl mx-auto px-4">

    <!-- Voltar -->
    <a href="/blog"
       class="inline-flex items-center text-sm opacity-90 hover:opacity-100 transition mb-4">
      <span class="mr-2 text-lg">←</span> Voltar ao blog
    </a>

    <!-- Título -->
    <h1 class="text-3xl md:text-4xl font-bold leading-tight mt-2">
      <?php echo htmlspecialchars($artigo['titulo']); ?>
    </h1>

    <!-- Categoria -->
    <div class="mt-4">
      <span class="inline-block bg-white/15 border border-white/30 text-sm px-4 py-1 rounded-full tracking-wide">
        <?php echo htmlspecialchars($artigo['categoria']); ?>
      </span>
    </div>

  </div>
</header>

<main class="max-w-4xl mx-auto bg-white mt-8 p-8 rounded shadow">

  <div class="article-content">
    <?php
    // conteúdo HTML do Quill
    echo $artigo['conteudo'];
    ?>
  </div>

  <div class="max-w-4xl mx-auto px-4 mt-6 flex flex-wrap gap-3 items-center">

  <!-- WhatsApp -->
  <a
    href="https://wa.me/?text=<?php echo urlencode($artigo['titulo'] . ' - ' . 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>"
    target="_blank"
    class="flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-full text-sm transition">

    WhatsApp
  </a>

  <!-- LinkedIn -->
  <a
    href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>"
    target="_blank"
    class="flex items-center gap-2 bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded-full text-sm transition">

    LinkedIn
  </a>

  <!-- Copiar link -->
  <button
    onclick="navigator.clipboard.writeText('<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>')"
    class="flex items-center gap-2 bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded-full text-sm transition">

    Copiar link
  </button>

</div>

</main>

<section id="formulario" class="bg-gradient-to-t from-[#780909] to-[#810b0b] text-white py-14 flex flex-col items-center my-16 rounded-2xl shadow-xl">
  <h1 class="text-3xl font-bold mb-4 text-white">Como podemos ajudar você hoje?</h1>
  <p class="text-center max-w-md mb-6">
    Queremos tirar suas dúvidas <br>
    Nos descreva seu problema ou pergunta abaixo, <br>
    entraremos em contato o mais breve possível!
  </p>

  <form action="https://formsubmit.co/contato@marcianolisboaadvocacia.com.br"
        method="POST"
        class="w-full max-w-md space-y-4">

    <input type="hidden" name="_subject" value="Nova mensagem do site!">

    <input type="text" name="nome" placeholder="Nome" required
           class="w-full p-3 rounded-md border border-gray-300">

    <input type="text" name="telefone" placeholder="DDD + Telefone" required
           class="w-full p-3 rounded-md border border-gray-300">

    <input type="email" name="email" placeholder="E-mail" required
           class="w-full p-3 rounded-md border border-gray-300">

    <input type="text" name="assunto" placeholder="Assunto" required
           class="w-full p-3 rounded-md border border-gray-300">

    <textarea name="mensagem" placeholder="Mensagem" rows="4" required
              class="w-full p-3 rounded-md border border-gray-300"></textarea>

    <button type="submit"
            class="w-full bg-gradient-to-r from-yellow-700 to-yellow-900 text-white p-3 rounded-md hover:from-yellow-700 hover:to-yellow-700 transition">
      Enviar mensagem
    </button>
  </form>
</section>

<?php echo file_get_contents('footer_standart.html'); ?>

</body>
</html>
