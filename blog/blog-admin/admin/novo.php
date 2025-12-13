<?php
require 'proteger.php'; // protege o painel

// Categorias disponíveis (mesmas usadas no CRUD)
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
    <title>Novo Artigo - Painel Administrativo</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/2a1053f0dc.js" crossorigin="anonymous"></script>
</head>

<body class="bg-gradient-to-b from-[#780909] to-[#810b0b] min-h-screen text-[#1a1a1a]">

    <!-- HEADER -->
    <?php echo file_get_contents('../admin_header.html'); ?>

    <main class="max-w-3xl mx-auto mt-12 px-4">

        <section class="bg-white rounded-2xl shadow-xl p-8">

            <h2 class="text-3xl font-bold text-[#780909] mb-6">
                Criar Novo Artigo
            </h2>

            <form action="criar.php" method="POST" class="space-y-4">

                <!-- TÍTULO -->
                <label class="block">
                    <span class="text-sm font-medium">Título do Artigo</span>
                    <input
                        type="text"
                        name="titulo"
                        required
                        class="mt-1 p-3 w-full rounded border">
                </label>

                <!-- CATEGORIA -->
                <label class="block">
                    <span class="text-sm font-medium">Categoria</span>
                    <select name="categoria" required class="mt-1 p-3 w-full rounded border">
                        <?php foreach ($categorias as $c): ?>
                            <option value="<?= htmlspecialchars($c); ?>">
                                <?= htmlspecialchars($c); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label>

                <!-- CONTEÚDO -->
                <label class="block">
                    <span class="text-sm font-medium">Conteúdo do Artigo (HTML permitido)</span>

                    <textarea
                        name="conteudo"
                        rows="12"
                        required
                        class="mt-1 p-3 w-full rounded border"></textarea>

                    <p class="text-xs text-gray-500 mt-1">
                        Você pode usar <strong>HTML</strong> como &lt;p&gt;, &lt;h2&gt;, &lt;strong&gt;, &lt;img&gt;, &lt;a&gt;, etc.
                    </p>
                </label>

                <!-- BOTÕES -->
                <div class="flex gap-3">
                    <button type="submit"
                        class="px-4 py-2 bg-gradient-to-r from-[#a67a03] to-[#644805] text-white rounded">
                        Criar Artigo
                    </button>

                    <a href="index.php" class="px-4 py-2 bg-gray-200 rounded">
                        Cancelar
                    </a>
                </div>

            </form>

        </section>

    </main>

    <!-- FOOTER -->
    <?php echo file_get_contents('../admin_footer.html'); ?>

</body>
</html>
