<?php
require 'proteger.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Novo Artigo</title>

<script src="https://cdn.tailwindcss.com"></script>

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
</head>

<body class="bg-gray-100">

<?php echo file_get_contents('admin_header.html'); ?>

<main class="max-w-3xl mx-auto mt-10 bg-white p-8 rounded shadow">

<h2 class="text-2xl font-bold mb-6">Novo Artigo</h2>

<form action="salvar.php" method="POST">

<label class="block mb-4">
<span class="text-sm font-medium">Título</span>
<input type="text" name="titulo" required class="w-full border p-3 rounded">
</label>

<label class="block mb-4">
<span class="text-sm font-medium">Categoria</span>
<select name="categoria" required class="w-full border p-3 rounded">
<option>Civil</option>
<option>Saúde</option>
<option>Família</option>
<option>Penal</option>
<option>Consumidor</option>
<option>Direito Contratual</option>
<option>Trabalhista</option>
<option>Previdenciária</option>
<option>Tributária</option>
<option>Administrativa</option>
<option>Empresarial</option>
<option>Outro</option>
</select>
</label>

<label class="block mb-2">
<span class="text-sm font-medium">Conteúdo</span>
<div id="editor" class="border rounded" style="min-height:300px"></div>
<input type="hidden" name="conteudo" id="conteudo">
</label>

<label class="block mb-6">
<span class="text-sm font-medium">Inserir imagem</span>
<input type="file" id="imgUpload" accept="image/*">
</label>

<button class="bg-[#780909] text-white px-6 py-2 rounded">Salvar</button>

</form>
</main>

<script>
var quill = new Quill('#editor', {
  theme: 'snow',
  modules: {
    toolbar: [
      ['bold', 'italic', 'underline'],
      [{ list: 'ordered' }, { list: 'bullet' }],
      ['link'],
      ['clean']
    ]
  }
});

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
<?php
?>