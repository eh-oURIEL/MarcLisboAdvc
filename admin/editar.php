<?php
require 'proteger.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) die('ID inválido');

$artigos = json_decode(file_get_contents('../artigos.json'), true) ?? [];

$artigo = null;
foreach ($artigos as $a) {
  if ($a['id'] == $id) {
    $artigo = $a;
    break;
  }
}
if (!$artigo) die('Artigo não encontrado');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Editar Artigo</title>

<script src="https://cdn.tailwindcss.com"></script>

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

<script>
    var quill = new Quill('#editor', {
    theme: 'snow',
    modules: {
        toolbar: [
        [{ 'header': [1, 2, 3, false] }],      // H1, H2, H3
        ['bold', 'italic', 'underline'],       // formatação básica
        [{ 'align': [] }],                     // alinhamento
        [{ 'list': 'ordered' }, { 'list': 'bullet' }], // listas
        ['blockquote'],                        // citação
        ['link'],                              // links
        ['clean']                              // limpar formatação
        ]
    }
    });

    // Envia HTML para o PHP
    document.querySelector('form').addEventListener('submit', function () {
    document.getElementById('conteudo').value = quill.root.innerHTML;
    });
</script>

</head>

<body class="bg-gray-100">

<?php echo file_get_contents('admin_header.html'); ?>

<main class="max-w-3xl mx-auto mt-10 bg-white p-8 rounded shadow">

<h2 class="text-2xl font-bold mb-6">Editar Artigo</h2>

<form action="salvar.php" method="POST">

<input type="hidden" name="id" value="<?php echo $artigo['id']; ?>">

<label class="block mb-4">
<span class="text-sm font-medium">Título</span>
<input type="text" name="titulo" required value="<?php echo htmlspecialchars($artigo['titulo']); ?>" class="w-full border p-3 rounded">
</label>

<label class="block mb-4">
<span class="text-sm font-medium">Categoria</span>
<select name="categoria" class="w-full border p-3 rounded">
<?php
$cats = ["Civil","Saúde","Família","Penal","Consumidor","Direito Contratual","Trabalhista","Previdenciária","Tributária","Administrativa","Empresarial","Outro"];
foreach ($cats as $c) {
  $sel = $artigo['categoria'] === $c ? 'selected' : '';
  echo "<option $sel>$c</option>";
}
?>
</select>
</label>

<label class="block mb-2">
<span class="text-sm font-medium">Conteúdo</span>
<div id="editor" class="border rounded" style="min-height:300px">
<?php echo $artigo['conteudo']; ?>
</div>
<input type="hidden" name="conteudo" id="conteudo">
</label>

<label class="block mb-6">
<span class="text-sm font-medium">Inserir imagem</span>
<input type="file" id="imgUpload" accept="image/*">
</label>

<button class="bg-[#780909] text-white px-6 py-2 rounded">Salvar Alterações</button>

</form>
</main>

<script>
var quill = new Quill('#editor', {
  theme: 'snow',
  modules: {
    toolbar: [
      [{ 'header': [1, 2, 3, false] }],      // H1, H2, H3
      ['bold', 'italic', 'underline'],       // formatação básica
      [{ 'align': [] }],                     // alinhamento
      [{ 'list': 'ordered' }, { 'list': 'bullet' }], // listas
      ['blockquote'],                        // citação
      ['link'],                              // links
      ['clean']                              // limpar formatação
    ]
  }
});

// Envia HTML para o PHP
document.querySelector('form').addEventListener('submit', function () {
  document.getElementById('conteudo').value = quill.root.innerHTML;
});

document.getElementById('imgUpload').addEventListener('change', function () {
  const file = this.files[0];
  if (!file) return;

  const formData = new FormData();
  formData.append('file', file);

  fetch('upload_imagem.php', {
    method: 'POST',
    credentials: 'same-origin',
    body: formData
  })
  .then(r => r.json())
  .then(data => {
    if (data.location) {
      const range = quill.getSelection(true);
      quill.insertEmbed(range.index, 'image', data.location);
    } else {
      alert(data.error || 'Erro no upload');
    }
  });
});

</script>


</body>
</html>
