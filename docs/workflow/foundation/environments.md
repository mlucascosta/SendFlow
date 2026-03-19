# Foundation: Environments

## Local

- `php -S 127.0.0.1:8000 -t public`
- recomendado para smoke tests de UI e instalador;
- pode usar SQLite fallback para setup rápido.

## Shared hosting / VPS simples

- foco em compatibilidade com PHP puro e assets por CDN;
- `public/` deve ser o document root ideal;
- `storage/database/` não deve ficar exposto publicamente.

## Produção com MySQL/MariaDB

- caminho preferencial para uso contínuo;
- migrations principais em `install/migrations/`;
- revisar compatibilidade antes de alterar procedures, triggers ou views.

## Produção/local com SQLite

- pensado para testes, demos, validação funcional e ambientes leves;
- schema SQLite fica em `install/migrations/sqlite/`;
- mudanças em queries runtime devem considerar diferenças entre drivers.
