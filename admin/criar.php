<?php // Full code here as provided in chat ?><?php
// criar.php — Recebe o POST de novo.php e salva novo artigo
require 'proteger.php'; // garante que somente logado acesse

// Permite apenas POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: novo.php');
    exit;
}

$titulo = trim($_POST['titulo'] ?? '');
$categoria = trim($_POST['categoria'] ?? 'Outro');
$conteudo = $_POST['conteudo'] ?? '';

// Valida campos obrigatórios
if ($titulo === '' || $conteudo === '') {
    die('Título e conteúdo são obrigatórios.');
}

// Caminho do arquivo JSON (volta 1 nível pois admin/ está em subpasta)
$file = '../artigos.json';

// Carrega arquivo
$raw = file_get_contents($file);
$artigos = json_decode($raw, true) ?: [];

// Define novo ID
$ids = array_column($artigos, 'id');
$newId = $ids ? max($ids) + 1 : 1;

// Monta novo artigo
$novo = [
    'id'        => $newId,
    'titulo'    => $titulo,
    'categoria' => $categoria,
    'conteudo'  => $conteudo,     // HTML permitido
    'created_at'=> date('c')      // ISO-8601
];

// Adiciona ao array
$artigos[] = $novo;

// Salva no JSON
file_put_contents($file, json_encode($artigos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

// Retorna ao painel
header('Location: index.php');
exit;
?>
