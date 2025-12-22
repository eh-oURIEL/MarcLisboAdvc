<?php
require 'proteger.php';

$uploadDir = __DIR__ . '/uploads/';
$baseUrl   = dirname($_SERVER['SCRIPT_NAME']) . '/uploads/';

$maxFileSize = 5 * 1024 * 1024;
$allowedExts = ['jpg','jpeg','png','gif','webp'];

header('Content-Type: application/json; charset=utf-8');

if (!isset($_FILES['file'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Nenhum arquivo enviado']);
    exit;
}

$file = $_FILES['file'];

if ($file['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(['error' => 'Erro no upload']);
    exit;
}

if ($file['size'] > $maxFileSize) {
    http_response_code(400);
    echo json_encode(['error' => 'Arquivo muito grande']);
    exit;
}

$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
if (!in_array($ext, $allowedExts)) {
    http_response_code(400);
    echo json_encode(['error' => 'Tipo não permitido']);
    exit;
}

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$filename = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
$dest = $uploadDir . $filename;

if (!move_uploaded_file($file['tmp_name'], $dest)) {
    http_response_code(500);
    echo json_encode(['error' => 'Falha ao salvar']);
    exit;
}

echo json_encode([
    'location' => $baseUrl . $filename
]);
exit;
?>
