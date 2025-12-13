<?php // Full code here as provided in chat ?><?php
// excluir.php — Remove um artigo pelo ID
require 'proteger.php'; // somente admin logado pode excluir

// Recebe ID pela URL
$id = intval($_GET['id'] ?? 0);

// Verificação básica
if ($id <= 0) {
    header('Location: index.php');
    exit;
}

// Caminho do JSON
$file = '../artigos.json';

// Carrega artigos
$raw = file_get_contents($file);
$artigos = json_decode($raw, true) ?: [];

// Filtra removendo o artigo de ID solicitado
$novos = array_filter($artigos, function($a) use ($id) {
    return $a['id'] != $id;
});

// Reorganiza os índices (array_values)
$novos = array_values($novos);

// Salva novamente no JSON
file_put_contents($file, json_encode($novos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

// Redireciona para o painel
header('Location: index.php');
exit;
?>
