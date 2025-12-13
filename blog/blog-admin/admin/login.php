<?php
// login.php - Admin login page
session_start();
if (!empty($_SESSION['admin_user'])) { header('Location: index.php'); exit; }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Login - Painel Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://kit.fontawesome.com/2a1053f0dc.js" crossorigin="anonymous"></script>
</head>
<body class="bg-gradient-to-b from-[#780909] to-[#810b0b] min-h-screen flex items-center justify-center">
  <div class="w-full max-w-md p-6 bg-white rounded-2xl shadow-xl">
    <div class="text-center mb-6">
      <img src="../img/imgLogo/LogoMLAdvc.png" class="mx-auto w-28 h-28 object-contain" alt="Logo">
      <h1 class="text-2xl font-bold text-[#780909] mt-4">Painel Administrativo</h1>
      <p class="text-sm text-gray-600">Faça login para gerenciar os artigos</p>
    </div>

    <form action="auth.php" method="POST" class="space-y-4">
      <label class="block">
        <span class="text-sm">Usuário</span>
        <input type="text" name="user" required class="mt-1 p-3 w-full rounded border" placeholder="Usuário">
      </label>

      <label class="block">
        <span class="text-sm">Senha</span>
        <input type="password" name="pass" required class="mt-1 p-3 w-full rounded border" placeholder="Senha">
      </label>

      <button type="submit" class="w-full px-4 py-2 bg-gradient-to-r from-[#a67a03] to-[#644805] text-white rounded">Entrar</button>
      <div class="text-center text-xs text-gray-500 mt-2">Usuário: <strong>MarcLisbo</strong></div>
    </form>
  </div>
</body>
</html>
