<?php
// admin/upload_imagem.php
// Recebe upload via TinyMCE e salva em /admin/uploads/
// Requer sessão (apenas admin logado)
require 'proteger.php';

// Config
$uploadDir = __DIR__ . '/uploads/'; // caminho absoluto para admin/uploads
$baseUrl   = '/admin/uploads/';     // caminho público (ajuste se seu site usa subpastas)

// Limites
$maxFileSize = 5 * 1024 * 1024; // 5 MB
$allowedExts = ['jpg','jpeg','png','gif','webp'];

// Cabeçalhos
header('Content-Type: application/json; charset=utf-8');

// Verifica se veio arquivo
if (empty($_FILES) || !isset($_FILES['file'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Nenhum arquivo enviado.']);
    exit;
}

$file = $_FILES['file'];

// Erros do PHP upload
if ($file['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(['error' => 'Erro no envio do arquivo (code '.$file['error'].').']);
    exit;
}

// Tamanho
if ($file['size'] > $maxFileSize) {
    http_response_code(400);
    echo json_encode(['error' => 'Arquivo muito grande. Máx 5MB.']);
    exit;
}

// Extensão
$origName = $file['name'];
$ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
if (!in_array($ext, $allowedExts)) {
    http_response_code(400);
    echo json_encode(['error' => 'Tipo de arquivo não permitido.']);
    exit;
}

// Gera nome único
$filename = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;

// Cria pasta se não existir
if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0755, true)) {
        http_response_code(500);
        echo json_encode(['error' => 'Falha ao criar diretório de upload.']);
        exit;
    }
}

// Move arquivo
$dest = $uploadDir . $filename;
if (!move_uploaded_file($file['tmp_name'], $dest)) {
    http_response_code(500);
    echo json_encode(['error' => 'Falha ao salvar o arquivo.']);
    exit;
}

// Retorna JSON no formato que TinyMCE aceita
$location = $baseUrl . $filename;
http_response_code(200);
echo json_encode(['location' => $location]);
exit;
