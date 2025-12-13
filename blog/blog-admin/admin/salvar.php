<?php
// salvar.php — Salva alterações feitas em editar.php
require 'proteger.php'; // protege acesso

// Permite apenas POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Recebe dados
$id        = intval($_POST['id'] ?? 0);
$titulo    = trim($_POST['titulo'] ?? '');
$categoria = trim($_POST['categoria'] ?? 'Outro');
$conteudo  = $_POST['conteudo'] ?? '';

if ($id <= 0 || $titulo === '' || $conteudo === '') {
    die("Dados inválidos.");
}

// Caminho do JSON
$file = '../artigos.json';

// Carrega artigos
$raw = file_get_contents($file);
$artigos = json_decode($raw, true) ?: [];

$encontrado = false;

// Atualiza o artigo correto
foreach ($artigos as &$a) {
    if ($a['id'] == $id) {
        $a['titulo']     = $titulo;
        $a['categoria']  = $categoria;
        $a['conteudo']   = $conteudo;      // HTML permitido
        $a['updated_at'] = date('c');      // timestamp ISO-8601
        $encontrado = true;
        break;
    }
}

unset($a);

if (!$encontrado) {
    die("Artigo não encontrado.");
}

// Salva no JSON novamente
file_put_contents($file, json_encode($artigos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

// Retorna ao painel
header('Location: index.php');
exit;
?>
