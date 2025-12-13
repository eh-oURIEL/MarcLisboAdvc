<?php
require 'proteger.php';
$id = intval($_GET['id'] ?? 0);
if ($id <= 0) { die('ID inválido.'); }

$file = '../artigos.json';
$raw = file_get_contents($file);
$artigos = json_decode($raw, true) ?: [];

$artigo = null;
foreach ($artigos as $a) {
    if ($a['id'] == $id) { $artigo = $a; break; }
}
if (!$artigo) { die('Artigo não encontrado.'); }

$categorias = [
    "Civil", "Saúde", "Família", "Penal", "Consumidor",
    "Direito Contratual", "Trabalhista", "Previdenciária",
    "Tributária", "Administrativa", "Empresarial", "Outro"
];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Artigo - Painel Administrativo</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/2a1053f0dc.js" crossorigin="anonymous"></script>

    <!-- TinyMCE -->
    <!-- TinyMCE -->
<script src="https://cdn.tiny.cloud/1/30h7ibpvu01ij42kokwy3ss8y4e9mfjloimx5yknkf45eupa/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    tinymce.init({
      selector: 'textarea[name="conteudo"]',
      height: 480,
      menubar: true,
      plugins: [
        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview', 'anchor',
        'searchreplace', 'visualblocks', 'code', 'fullscreen', 'insertdatetime', 'media',
        'table', 'help', 'wordcount'
      ],
      toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | code | help',
      images_upload_url: '/admin/upload_imagem.php',
      automatic_uploads: true,
      images_upload_credentials: true
    });
  });
</script>


</head>

<body class="bg-gradient-to-b from-[#780909] to-[#810b0b] min-h-screen text-[#1a1a1a]">

    <!-- HEADER ADMIN -->
    <?php echo file_get_contents('../admin_header.html'); ?>

    <main class="max-w-3xl mx-auto mt-12 px-4">

        <section class="bg-white rounded-2xl shadow-xl p-8">

            <h2 class="text-3xl font-bold text-[#780909] mb-6">
                Editar Artigo
            </h2>

            <form action="salvar.php" method="POST" class="space-y-4">
                <input type="hidden" name="id" value="<?php echo $artigo['id']; ?>">

                <label class="block">
                    <span class="text-sm font-medium">Título do Artigo</span>
                    <input type="text" name="titulo" required value="<?php echo htmlspecialchars($artigo['titulo']); ?>" class="mt-1 p-3 w-full rounded border">
                </label>

                <label class="block">
                    <span class="text-sm font-medium">Categoria</span>
                    <select name="categoria" required class="mt-1 p-3 w-full rounded border">
                        <?php
                        foreach ($categorias as $c) {
                            $selected = ($artigo['categoria'] === $c) ? 'selected' : '';
                            echo "<option value=\"".htmlspecialchars($c)."\" $selected>".htmlspecialchars($c)."</option>";
                        }
                        ?>
                    </select>
                </label>

                <label class="block">
                    <span class="text-sm font-medium">Conteúdo (HTML permitido)</span>
                    <textarea name="conteudo" rows="12" required class="mt-1 p-3 w-full rounded border"><?php echo htmlspecialchars($artigo['conteudo']); ?></textarea>
                    <p class="text-xs text-gray-500 mt-1">Use o editor visual acima. Para inserir imagens, use o botão de imagem (máx 5MB).</p>
                </label>

                <div class="flex gap-3">
                    <button type="submit" class="px-4 py-2 bg-gradient-to-r from-[#a67a03] to-[#644805] text-white rounded">Salvar Alterações</button>
                    <a href="index.php" class="px-4 py-2 bg-gray-200 rounded">Cancelar</a>
                </div>
            </form>

        </section>

    </main>

    <!-- FOOTER -->
    <?php echo file_get_contents('../admin_footer.html'); ?>

</body>
</html>
