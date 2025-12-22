<?php
session_start();

/*
 |---------------------------------------------------
 | CREDENCIAIS DO PAINEL ADMIN
 |---------------------------------------------------
 */
$ADMIN_USER = 'MarcLisbo';

// 🔐 Hash da senha (gerado com password_hash)
$ADMIN_PASS_HASH = '$2y$10$W74VFYFJUySmdEH8q2flmOXZAFx/RA5DOBrZVj7OMcIedBWe6Dmxi';

/*
 |---------------------------------------------------
 | DADOS DO FORMULÁRIO
 |---------------------------------------------------
 */
$usuario = $_POST['usuario'] ?? '';
$senha   = $_POST['senha'] ?? '';

/*
 |---------------------------------------------------
 | VALIDA LOGIN
 |---------------------------------------------------
 */
if ($usuario === $ADMIN_USER && password_verify($senha, $ADMIN_PASS_HASH)) {

    $_SESSION['logado']  = true;
    $_SESSION['usuario'] = $usuario;

    header('Location: index.php');
    exit;
}

// Login inválido
header('Location: login.php?erro=1');
exit;




