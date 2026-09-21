# Migração do Prova-email para SPA com Svelte

Este pacote é um patch para o repositório `mape0s/Prova-email`.

## O que foi alterado

- Svelte 5 integrado ao Vite existente.
- `/` passa a carregar uma SPA Svelte.
- Login da SPA usa `POST /api/login`.
- Token Sanctum é usado nas chamadas autenticadas.
- Dashboard com:
  - saldo;
  - limite;
  - status da conta;
  - extrato;
  - PIX por e-mail;
  - aplicação;
  - resgate;
  - logout.
- PIX agora recebe `email` e transfere o valor entre as contas dos dois clientes.
- A transferência gera uma movimentação de saída para quem envia e uma movimentação de entrada para quem recebe.
- As rotas administrativas e o `/dashboard` Blade existentes são preservados.

## Instalação

1. Extraia este ZIP dentro da raiz do projeto ou mantenha o arquivo `instalar-svelte.ps1` junto dos arquivos do patch.
2. Abra o PowerShell na raiz do Laravel.
3. Execute:

```powershell
powershell -ExecutionPolicy Bypass -File .\instalar-svelte.ps1
```

4. Inicie o Laravel:

```powershell
php artisan serve
```

5. Acesse `http://127.0.0.1:8000/`.

## Endpoints usados pela SPA

- `POST /api/login`
- `GET /api/user`
- `GET /api/saldo`
- `GET /api/extrato`
- `POST /api/pix`
- `POST /api/aplicar`
- `POST /api/resgatar`
- `POST /api/logout`

## Observação

O token fica no `localStorage` para simplificar a demonstração acadêmica da SPA consumindo a API RESTful. Para um sistema bancário real, o desenho de autenticação deve ser endurecido.
