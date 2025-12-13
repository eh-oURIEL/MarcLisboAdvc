
Blog CRUD PHP - Painel Administrativo
-------------------------------------
Arquivos gerados em /admin e artigos.json na raiz.

Credenciais administrativas:
  Usuário: MarcLisbo
  Senha: AdmBlogAdriana

Implementação:
  - A autenticação neste pacote usa SHA-256 para armazenar/verificar a senha (hash gerado automaticamente).
  - O hash SHA-256 armazenado: d4eac906a9f43e7f77e2acacd7e73d07a4fa5eec9003e805d64c4450872b8a4b

Instruções de deploy:
  1. Faça upload da pasta 'admin' e do arquivo 'artigos.json' para o servidor (mesma pasta ou ajustando caminhos).
  2. Garanta que o PHP possa gravar em artigos.json: chmod 664 artigos.json (ou 666 se necessário).
  3. Acesse /admin/login.php para entrar no painel.
  4. Para maior segurança, substitua a verificação por password_hash/password_verify e use HTTPS.

Recomendações de segurança:
  - Use HTTPS (SSL/TLS).
  - Considere migrar para password_hash() em PHP para armazenar senhas com bcrypt/argon2.
  - Se exposto ao público, sanitize HTML dos artigos usando HTMLPurifier antes de salvar/exibir.
  - Faça backup de artigos.json regularmente.
