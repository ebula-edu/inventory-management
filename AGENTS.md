# AGENTS.md — Inventory System

## Purpose
A web-based inventory management system designed for store owners and warehouse staff to track stock levels, monitor low stock alerts, manage product catalogs, and handle stock-in / stock-out operations cleanly and reliably.

## Tech Stack (locked)
- Language / version: PHP 8.2.12, JavaScript (ES6+), HTML5, CSS3
- Framework / version: Laravel 12.69.3
- Package manager: Composer 2.x, npm 11.12.1 (Node v24.15.0)
- Database: SQLite (database/database.sqlite)
- Testing: PHPUnit 11.x (`php artisan test`)
- Lint / format: Laravel Pint / EditorConfig

## Structure
- /app → application core (Models, Http Controllers, Providers)
- /bootstrap → framework bootstrapping and application configuration
- /config → application configuration files
- /database → database migrations, seeders, factories, SQLite store
- /docs → all documentation (default)
- /public → publicly accessible assets (css, js, images, index.php)
- /resources → views (Blade templates), raw CSS, raw JS
- /routes → web and console route definitions
- /storage → logs, framework caches, uploaded files
- /tests → Feature and Unit test suites
- Root: README.md, CHANGELOG.md, AGENTS.md, Skills.md, composer.json, package.json, artisan

## Commands
- Install: `composer install && npm install`
- Dev: `php artisan serve` (and `npm run dev` for frontend asset development)
- Build: `npm run build`
- Test: `php artisan test`
- Lint: `./vendor/bin/pint`

## Standards
- Naming: PascalCase for PHP classes/models/controllers, camelCase for methods/variables, kebab-case for views/CSS classes/routes
- Style: PSR-12 standard via Laravel Pint
- JSDoc/PHPDoc: required on all public APIs and core functions
- Comments: why, not what

## Engineering Principles (in priority order)
KISS · YAGNI · DRY (rule of three) · SRP · SoC · Least Astonishment ·
Fail Fast · Composition > Inheritance · Explicit > Implicit ·
Readability > Cleverness · Boy Scout · Convention > Config ·
Don't Break Contracts · Security by Default.
Tiebreaker: whatever is easiest for the next maintainer.

## Maintainer Mindset
- Optimize for the reader.
- Onboarding in minutes.
- No hidden magic. No surprises.
- Tests are documentation.
- Public surfaces are contracts.
- Non-obvious decisions → note in /docs/decisions/.

## Scope

### In scope
- Inventory overview, product listing, stock in/out tracking, and low-stock alerts.
- Single root-level Laravel application hosting the inventory interface and assets.
- Clean separation of public static assets and Blade templates without directory nesting.

### Out of scope
- Multi-tenant enterprise cloud integrations.
- Distributed microservices architecture.
- Mobile native apps.

### Deferred (with triggers)
- Relational MySQL/PostgreSQL migration (trigger: production deployment requirements).
- Backend REST API endpoints for stock CRUD operations (trigger: backend implementation phase).

## Rules of Engagement
- Ask before guessing.
- Flag out-of-scope; don't build it.
- No overengineering. YAGNI.
- Docs derived from code, validated before writing.
- Changelog only for meaningful changes.
- Optimize for future maintainers.
