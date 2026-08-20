# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

Learning/demo app: a chat (Rooms + Messages) built on **Symfony 8.0 / PHP 8.4** with **Turbo (symfony/ux-turbo)** and **AssetMapper** (no Node, no bundler). Entity model is documented in `Model.mmd` (Mermaid).

## Commands

```bash
symfony server:start -d          # dev server (or: php -S localhost:8000 -t public)
bin/console debug:router         # routes
bin/console cache:clear

docker compose up -d             # Postgres + Mailpit (prod-ish config)
                                 # dev env actually overrides DATABASE_URL to SQLite in .env.dev

bin/console make:migration       # after entity changes
bin/console doctrine:migrations:migrate

bin/phpunit                      # all tests (tests/ is currently empty)
bin/phpunit --filter testName    # single test
bin/phpunit tests/Path/ToTest.php

php-cs-fixer fix src             # global install, no repo config -> default ruleset
```

## Architecture

- `src/Entity/` — `Room` 1—n `Message` (`orphanRemoval: true`). Both use the `Timestampable` trait (`src/Entity/Traits/`), which sets `createdAt`/`updatedAt` via `#[ORM\PrePersist]`/`#[ORM\PreUpdate]` — entities using it **must** carry `#[ORM\HasLifecycleCallbacks]`.
- `src/Controller/` — thin, `final`, attribute routes, `EntityManagerInterface` injected per action. Route names follow `app_<plural>_<action>`.
- `src/Twig/Extension/AppExtension.php` + `src/Twig/Runtime/AppExtensionRuntime.php` — lazy runtime pattern; add Twig functions in the Extension, implement in the Runtime.
- `templates/` — layouts in `layouts/`, one dir per controller, partials prefixed `_`.

## Turbo conventions (important)

- **Form re-render must return HTTP 422**, not 200. Turbo ignores a non-redirect 200 after a submit, so failed validation silently does nothing. Every form action ends with:
  ```php
  return $this->render('...', [...], new Response(null, $form->isSubmitted() ? 422 : 200));
  ```
- Success path always redirects (`redirectToRoute`) so Turbo does a visit.
- Non-GET/POST verbs (PUT/DELETE) come through a hidden `_method` input; the form type must be built with `'method' => 'PUT'` to match.
- Deletes are protected with a manual CSRF token (`csrf_token('rooms_deletion_' ~ id)` in the template, `isCsrfTokenValid` in the controller), not the form component.
- Framework CSRF is **stateless** (`config/packages/csrf.yaml`, `check_header: true`); token ids are whitelisted under `stateless_token_ids`.
- `<turbo-frame>` wraps the parts of a page meant to update in place (see `templates/rooms/show.html.twig`).

## Assets

`importmap.php` + AssetMapper. Add JS deps with `bin/console importmap:require <pkg>` — never npm. Stimulus controllers live in `assets/controllers/` and auto-register.

## Gotchas

- `config/packages/ux_turbo.yaml` currently holds a stray duplicate of the CSRF config, not Turbo config.
- Comments in controllers are in French; code, commits and templates in English.
