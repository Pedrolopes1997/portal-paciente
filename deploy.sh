#!/bin/bash

# 1. Ativa o modo de manutenção (ninguém acessa enquanto atualiza)
php artisan down || true

# 2. Baixa as novidades do GitHub
git pull origin main

# 3. Instala dependências novas do PHP (se houver)
composer install --no-interaction --prefer-dist --optimize-autoloader

# 4. Atualiza a estrutura do banco de dados (Cria tabelas/colunas novas)
php artisan migrate --force

# 5. Limpa e recria os caches (Para o sistema ficar rápido e pegar as configs novas)
php artisan optimize:clear
php artisan optimize

# 6. Atualiza permissões de pastas (Sempre bom garantir)
chmod -R 775 storage bootstrap/cache

# 7. Reinicia as filas (Para o envio de e-mail pegar o código novo)
php artisan queue:restart

# 8. Volta o sistema para o ar
php artisan up

echo "🚀 Deploy realizado com sucesso!"