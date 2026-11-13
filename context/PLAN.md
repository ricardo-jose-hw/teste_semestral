# Plano de implementação — migração Blade + Vite com backend persistente

Aprovado em 11/11/2026. Especificação em `context/SPEC.md`.

## Status

- [x] M1 — Assets via Vite
- [x] M2 — Banco, models, seeder e login
- [x] M3 — Controllers e rotas
- [x] M4 — Views Blade
- [x] M5 — Remoção das views legadas e documentação

## Verificação final

Executada em 12/11/2026, sem testes automatizados (decisão registrada na especificação):

- `php -l` em controllers, models, rotas e configuração: sem erros.
- `php artisan route:list --except-vendor`: 47 rotas registradas.
- `php artisan view:clear` e `php artisan view:cache`: templates compilados sem erros.
- `npm run build`: build concluído; CSS e JS gerados pelo Vite.
- `php artisan migrate:fresh --seed`: esquema recriado e seed demo aplicado.
- Smoke manual via `php artisan serve` com `dono@arizonapetcare.test` e `funcionario@arizonapetcare.test` (senha `senha12345`): login, painel, listagens, detalhes, formulários, cadastro de cliente/pet/consulta/funcionário, mudança de status, upload de exame e bloqueio 403 do perfil `funcionario` em `/funcionarios`.
- `grep` por `$db`, helpers antigos e nomes das views legadas: nenhuma referência no código.
- Estado demo restaurado com `migrate:fresh --seed` após o smoke.

## Observações de implementação

- `context/HANDOFF.md` foi mantido fora deste commit por ser um arquivo pré-existente não rastreado; o status da migração está registrado aqui, em `context/PLAN.md` e em `context/SPEC.md`.
- Não foi criado o cadastro público (`auth.register`), conforme os não objetivos.
- O CSS de referência foi movido de `public/assets/css` para `resources/css/painel`, preservando a ordem `style` → `petcare`.

## M1 — Assets via Vite

- Mover `public/assets/css/style.css` e `petcare.css` para `resources/css/painel/`, ajustando `url()` para `/assets/...`.
- `resources/css/app.css`: importar os dois arquivos na ordem, remover Tailwind/preflight.
- `resources/js/app.js`: unir o script do painel (menu, confirmação, máscaras, autosubmit, filtro de pets, linhas de exame) ao filtro AJAX de clientes.
- `vite.config.js`: remover fontes Bunny e plugin Tailwind; manter entradas `app.css` e `app.js`.
- `partials/head.blade.php`: usar `@vite`; remover tags de script dos layouts.
- `components/botao-acao.blade.php`: mover `data-confirmar` para o botão.
- Remover as cópias em `public/assets/css` e `public/assets/js`.

Verificação: `npm run build`; conferir manifest e referências.

## M2 — Banco, models, seeder e login

- Consolidar migrations: tutor, pet, funcionario, atendimento; renomear a migration de clínicas; criar `exame`.
- Ajustar models (`Tutor`, `Pet`, `Funcionario`, `Clinica`, `User`) e criar `Consulta` (tabela `atendimento`) e `Exame`.
- Corrigir `AuthService::login()` e `AuthController::login()`; adicionar `User::iniciais`.
- Seeder demo: clínica, contas dono/funcionário, clientes, pets, consultas e exames.
- Placeholder de exame em `public/uploads/exames`.

Verificação: `php -l`; `php artisan migrate:fresh --seed`; login pela conta demo.

## M3 — Controllers e rotas

- `TutorController`, `PetController`, `FuncionarioController`, `DashboardController` sobre Eloquent.
- Novos `ConsultaController` e `ExameController`.
- Rotas de pets (global + por cliente), status, consultas e exames; menu atualizado.

Verificação: `php -l`; `php artisan route:list`; smoke manual.

## M4 — Views Blade

- `tutor/*`, `pet/*`, `funcionarios/*`, `consulta/*`, `exame/*`, `dashboards/_painel`, `errors/*`.
- Reutilizar layouts e componentes existentes; rótulo **Clientes**.

Verificação: `php artisan view:cache`; smoke manual; `npm run build`.

## M5 — Remoção das views legadas e documentação

- Remover as 12 views `.php` e `resources/views/painel.php`.
- Reconciliar spec/plano com o implementado e atualizar `context/HANDOFF.md`.

Verificação: `grep` por `$db`/helpers; `git diff` revisado.
