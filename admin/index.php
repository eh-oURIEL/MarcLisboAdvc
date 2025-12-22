<?php // Full code here as provided in chat ?><?php
require 'proteger.php'; // protege o painel (login obrigatório)

// Carrega artigos do JSON
$file = '../artigos.json';
$raw = file_get_contents($file);
$artigos = json_decode($raw, true) ?: [];

// Ordena por data (mais recentes primeiro)
usort($artigos, function($a, $b) {
    return strtotime($b['created_at']) - strtotime($a['created_at']);
});
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel Administrativo - Artigos</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/2a1053f0dc.js" crossorigin="anonymous"></script>
</head>

<body class="bg-gradient-to-b from-[#780909] to-[#810b0b] min-h-screen text-[#1a1a1a]">

    <!-- HEADER ADMIN -->
    <?php echo file_get_contents('admin_header.html'); ?>

    <main class="max-w-5xl mx-auto mt-12 px-4">

        <section class="bg-white rounded-2xl shadow-xl p-8">

            <div class="flex justify-between items-center mb-6">
                <h2 class="text-3xl font-bold text-[#780909]">Gerenciar Artigos</h2>

                <a href="novo.php" 
                   class="px-4 py-2 bg-gradient-to-r from-[#a67a03] to-[#644805] text-white rounded shadow hover:opacity-90 transition">
                    <i class="fa-solid fa-plus"></i> Novo Artigo
                </a>
            </div>

            <?php if (count($artigos) == 0): ?>
                <p class="text-gray-600 text-center py-6">Nenhum artigo encontrado.</p>
            <?php else: ?>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="p-3 text-left">Título</th>
                            <th class="p-3 text-left">Categoria</th>
                            <th class="p-3 text-left">Data</th>
                            <th class="p-3 text-center">Ações</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($artigos as $a): ?>
                        <tr class="border-b">
                            <td class="p-3 font-medium">
                                <?= htmlspecialchars($a['titulo']); ?>
                            </td>

                            <td class="p-3">
                                <span class="px-2 py-1 text-sm bg-[#780909] text-white rounded">
                                    <?= htmlspecialchars($a['categoria']); ?>
                                </span>
                            </td>

                            <td class="p-3 text-gray-600">
                                <?= date('d/m/Y H:i', strtotime($a['created_at'])); ?>
                            </td>

                            <td class="p-3 text-center">
                                <a href="editar.php?id=<?= $a['id']; ?>"
                                   class="text-blue-600 hover:underline mx-2">
                                    <i class="fa-solid fa-pen-to-square"></i> Editar
                                </a>

                                <a href="excluir.php?id=<?= $a['id']; ?>"
                                   onclick="return confirm('Tem certeza que deseja excluir este artigo?');"
                                   class="text-red-600 hover:underline mx-2">
                                    <i class="fa-solid fa-trash"></i> Excluir
                                </a>
                            </td>

                        </tr>
                        <?php endforeach; ?>
                    </tbody>

                </table>
            </div>

            <?php endif; ?>

        </section>

    </main>

    <!-- FOOTER -->

</body>
</html>
