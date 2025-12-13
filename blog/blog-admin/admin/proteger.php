<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    // IMPORTANTE: não redirecionar em uploads AJAX
    http_response_code(401);
    echo json_encode(['error' => 'Não autenticado']);
    exit;
}
