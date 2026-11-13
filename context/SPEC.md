# Especificação — migração da apresentação para Blade + Vite com backend persistente

- **Data:** 11/11/2026.
- **Branch:** `style`.
- **Base:** `ab8010d`.
- **Origem:** `context/HANDOFF.md` (análise de 10/11/2026) e decisões tomadas em 11/11/2026.

## 1. Objetivo e contexto

O projeto mistura uma aplicação Laravel 13 com telas de um protótipo PHP em `resources/views/*.php`. O objetivo é migrar a apresentação para Blade, carregar CSS/JS pelo Vite e **persistir os dados no backend via Eloquent**, preservando a identidade visual de `public/assets`.

A migração substitui a decisão anterior de usar mocks nos models: os dados agora são lidos e gravados nas tabelas do banco. A interface chama os tutores de **Clientes**; os nomes técnicos (`Tutor`, `tutores.*`) são mantidos.

## 2. Critérios de aceite

| # | Critério |
|---|---|
| AC1 | CSS e JS carregados exclusivamente pelo Vite, na ordem `style` → `petcare`, com fontes e imagens servidas de `public/assets`. |
| AC2 | `php artisan migrate:fresh --seed` cria o esquema coerente: tutores, pets, funcionários, clínicas, atendimentos e exames. |
| AC3 | Login funcional com contas demo, sem bypass, com `users.perfil` separando `dono` e `funcionario`. |
| AC4 | Clientes (técnico: Tutor) com listagem, filtros, cadastro, edição, detalhe e exclusão persistidos. |
| AC5 | Pets com vínculo a cliente, status ativo/inativo, edição, exclusão e histórico médico. |
| AC6 | Funcionários com conta de acesso (`User`), perfil, especialidade, status e vínculo à clínica única. |
| AC7 | Consultas com agendar, reagendar, filtros e mudança de status; exames com upload real e listagem. |
| AC8 | Painel com KPIs, agenda do dia e exames recentes para `dono` e `funcionario`. |
| AC9 | As 12 views PHP legadas convertidas ou removidas; nenhuma referência a `$db` ou helpers antigos. |

## 3. Não objetivos

- Login com Google e qualquer integração OAuth.
- Notificação por e-mail em agendamento/reagendamento/cancelamento.
- Multitenancy real: a interface assume **uma clínica compartilhada**. Relações e cadastro de múltiplas clínicas ficam para etapa futura.
- Cadastro público de contas (`auth.register`), que continua indisponível nesta entrega.
- Suíte de testes automatizados: por decisão do usuário, não será executada nesta entrega.
- Regra “veterinário vê apenas a própria agenda”: não existe perfil de veterinário no domínio atual.

## 4. Regras e restrições

- A clínica é única. Seletores de clínica e vínculos múltiplos saem da interface.
- Perfis válidos: `dono` e `funcionario`. Apenas `dono` acessa o cadastro de funcionários.
- CPF e telefone são armazenados apenas com dígitos; as máscaras são aplicadas no cliente.
- Exames só podem ser anexados a consultas com status **Concluída**.
- Arquivos de exame ficam em `public/uploads/exames`; uploads reais são ignorados pelo Git, o placeholder é versionado.
- O visual de referência é o CSS do painel em `resources/css/painel` (style + petcare), preservando a ordem de aplicação.
- Não executar testes automatizados (`composer test`/Pest).

## 5. Decisões abertas

Nenhuma bloqueante. Pontos registrados para etapas futuras: cadastro público, OAuth, notificações e remoção estrutural de multitenancy.

## 6. Status de implementação

Implementado em 12/11/2026:

| # | Status | Evidência |
|---|---|---|
| AC1 | Atendido | `resources/css/app.css`, `resources/js/app.js` e `@vite` no partial de head; `npm run build` sem Tailwind. |
| AC2 | Atendido | `php artisan migrate:fresh --seed` com tutor, pet, funcionario, clinicas, atendimento e exame. |
| AC3 | Atendido | Login sem bypass com `users.perfil`; contas demo validadas (`dono` e `funcionario`). |
| AC4 | Atendido | `TutorController` e views `tutor/*` com CRUD, filtros e detalhe. |
| AC5 | Atendido | `PetController` e views `pet/*` com vínculo, status, exclusão e histórico. |
| AC6 | Atendido | `FuncionarioController` cria/atualiza `User` vinculado, perfil, especialidade, status e clínica. |
| AC7 | Atendido | `ConsultaController` e `ExameController`; upload real em `public/uploads/exames` e restrição a consultas concluídas. |
| AC8 | Atendido | `dashboards/_painel` com KPIs, agenda do dia e exames recentes para ambos os perfis. |
| AC9 | Atendido | As 12 views PHP legadas foram removidas; busca por `$db` e helpers antigos não encontrou referências. |

Sem divergências de comportamento em relação aos critérios. A única alteração de redação foi a localização do CSS de referência, agora em `resources/css/painel`.
