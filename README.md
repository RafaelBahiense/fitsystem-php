# Fitsystem

O Fitsystem é uma aplicação web desenvolvida para gerenciar clientes, aulas e acompanhar o progresso dos clientes em um centro de fitness. Este projeto foi desenvolvido utilizando PHP, MySQL, TailwindCSS e JQuery.

## Funcionalidades

- **Gerenciamento de clientes**: Cadastro e visualização de clientes ativos.
- **Agendamento de aulas**: Organização e inscrição em aulas de fitness.
- **Acompanhamento de progresso**: Visualização gráfica do progresso dos clientes.

## Pré-requisitos

- PHP (XAMPP recomendado para facilitar a configuração)
- Apache (XAMPP recomendado para facilitar a configuração)
- MySQL (XAMPP recomendado para facilitar a configuração)
- Node.js

## Instalação

1. **Clone o repositório:**
   ```bash
   git clone https://github.com/RafaelBahiense/fitsystem-php
   ```
2. **Navegue até o diretório do projeto:**
   ```bash
   cd fitsystem
   ```
3. **Instale as dependências de desenvolvimento do Node.js:**
   ```bash
   npm install
   ```
4. **Configure o banco de dados MySQL:**
   - Importe o arquivo `setup.sql` para criar as tabelas necessárias.
   - Configure o arquivo `src/database/config.php` com as informações de conexão do seu banco de dados.

5. **Link simbólico para o XAMPP:**
   - Para usuários de Linux: `npm run link:xampp:linux`
   - Para usuários de Windows: `npm run link:xampp:windows`

6. **(Linux) Defina as permissões apropriadas para o diretório `src`:**
   ```bash
   npm run set:perms
   ```

## Uso

Inicie o servidor Apache através do XAMPP e acesse o projeto através do navegador em `http://localhost/fitsystem`.