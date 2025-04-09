# Backend API - Perfect Pay Test

### Pontos que não foi possível concluir

- Fluxo de carrinho, os "produtos" apresentados no front estão fixos
- 100% dos testes, coloquei alguns exemplos de testes para algumas camadas (Código ficou com 36% de cobertura apenas :( )
- Front foi feito em apenas uma tela para não ser algo tão complexo

### Como rodar o projeto

No back basta ir na pasta do projeto pelo terminal e executar `docker compose up -d`, após os containers ficarem de pé será necessário entrar dentro do container `docker exec -it perfectpay-test-laravel.test-1  /bin/bash` e executar os seguintes comandos:

- `cp .env.example .env`
- `composer install`
- `php artisan key:generate`
- `php artisan migrate`

Por se tratar de um projeto de testes, já deixei no .env as credenciais de acesso do banco.

No arquivo .env será necessário colocar a chave de acesso a API da ASAAS, na variável `ASAAS_TOKEN`;

A API subirá na porta 80 por padrão, caso seja necessário mudar a porta deverá ser alterada no projeto do front também no arquivo `pages/index.vue:60`.

Qualquer dúvida fico a disposição, desde já agradeço a oportunidade de participar do processo