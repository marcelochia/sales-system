# SISTEMA DE VENDAS

### Tecnologias
**Back-end**
- PHP 8.2
- Laravel 10
(sales-web-system-api)

**Front-end**
- Vue.js 3

### Rodando a aplicação

- No arquivo .env na raiz do projeto preencher com o usuário e id do sistema para o Composer executar os comandos sem problema

- Copiar o arquivo `sales-web-system-api/.env.example` para `sales-web-system-api/.env`

- Suba a aplicação:

    `docker compose -f docker/docker-compose.yml up -d`

- Instale os pacotes com o Composer

    `docker exec sales-web-system-api composer install`

- Rode as migrations

    `docker exec sales-web-system-api php artisan migrate --seed`

- Inicie o schedule do Laravel

    `docker exec sales-web-system-api php artisan schedule:work`

> IMPORTANTE: as schedules são para o envio automático do relatório de vendas, enviado uma vez por dia no horário definido na variável de ambiente `SCHEDULE_TIME` presente no arquivo `./sales-web-system-api/.env`. Se a variável não tiver valor será enviado às 23:59.

- Configure o envio de email nas variáveis de ambiente de prefixo `MAIL_`

- A API estará rodando em http://localhost:8001/api.
- Os endpoints estão no arquivo README.md do diretório `sales-web-system-api`

- A aplicação estará rodando em http://localhost:8002