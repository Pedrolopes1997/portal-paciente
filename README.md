# WeCare - Portal do Paciente

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Filament](https://img.shields.io/badge/Filament_v3-F28D15?style=for-the-badge&logo=livewire&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white)

> Plataforma SaaS desenvolvida para gestão e acesso de pacientes, focada na experiência do usuário e integração hospitalar.

## 🚀 Sobre o Projeto

O **Portal do Paciente WeCare** é uma solução desenvolvida para facilitar a jornada do paciente, permitindo acesso rápido a resultados de exames, agendamentos e histórico médico.

O sistema foi construído utilizando **Laravel** e **FilamentPHP v3**, garantindo uma interface administrativa robusta, responsiva e segura. O projeto conta com painéis dedicados para administradores e pacientes.

### ✨ Principais Funcionalidades

* **Painel Administrativo:** Gestão completa de usuários e configurações via Filament.
* **Área do Paciente:** Acesso restrito e seguro aos dados médicos.
* **Integração Tasy:** Sincronização de dados (exames e agendamentos) com o sistema hospitalar Tasy.
* **Impersonate:** Funcionalidade para suporte técnico logar como o paciente para debug (via `stechstudio/filament-impersonate`).
* **Gestão de Acessos:** Controle granular de permissões e roles.

## 🛠️ Tecnologias Utilizadas

* **Backend:** PHP 8.2+, Laravel 10/11
* **Admin & UI:** FilamentPHP v3 (TALL Stack)
* **Banco de Dados:** MySQL
* **Servidor:** Ubuntu Server 24.04 LTS
* **Versionamento:** Git & GitHub

## ⚙️ Instalação Local

Para rodar o projeto em sua máquina local, siga os passos:

1. **Clone o repositório:**
   ```bash
   git clone git@github.com:SEU_USUARIO/portal-paciente.git
Instale as dependências:

Bash

composer install
npm install && npm run build
Configure o ambiente:

Bash

cp .env.example .env
php artisan key:generate
Banco de Dados: Configure as credenciais do banco no arquivo .env e rode as migrações:

Bash

php artisan migrate --seed
Inicie o servidor:

Bash

php artisan serve
🔒 Segurança
Este projeto segue as melhores práticas de segurança do Laravel.

Proteção contra CSRF & XSS.

Autenticação robusta.

Monitoramento de dependências via Composer Audit.

Desenvolvido por Pedro / WeCare Team 🏥