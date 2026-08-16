# Automotiva Tec

SaaS multi-tenant para lojas de estetica automotiva, com painel master e painel tenant em Filament.

## Desenvolvimento com Docker

Suba Postgres 17, Redis e o app PHP-FPM:

```bash
docker compose up --build
```

Em outro terminal, rode as migrations e seeders:

```bash
docker compose exec app php artisan migrate:fresh --seed
```

Para servir HTTP em desenvolvimento:

```bash
docker compose exec app php artisan serve --host=0.0.0.0 --port=8000
```

Credenciais seed:

- Master: `admin@sistema.test` / `password`
- Loja: `dono@lojateste.test` / `password`

Paineis:

- `/master`: administracao SaaS, sem tenancy.
- `/app`: painel tenant por loja.

## Testes

```bash
docker compose exec app php artisan test
```
