<?php
// auth.php - authenticate credentials, start session
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: login.php'); exit; }

// Configurado com usuário e hash (SHA256) gerado previamente
$ADMIN_USER = 'MarcLisbo';
$ADMIN_PASS_HASH = 'd4eac906a9f43e7f77e2acacd7e73d07a4fa5eec9003e805d64c4450872b8a4b'; // SHA-256

$u = $_POST['user'] ?? '';
$p = $_POST['pass'] ?? '';

// comparar usuário e hash da senha
if ($u === $ADMIN_USER && hash('sha256', $p) === $ADMIN_PASS_HASH) {
    // login ok
    session_regenerate_id(true);
    $_SESSION['admin_user'] = $ADMIN_USER;
    header('Location: index.php');
    exit;
} else {
    // falha
    $_SESSION['login_error'] = 'Usuário ou senha inválidos.';
    header('Location: login.php');
    exit;
}
?>