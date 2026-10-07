# Skills.md — Inventory System

## Languages & Frameworks
- PHP 8.2.12
- Laravel Framework 12.69.3
- JavaScript (Vanilla ES6+)
- CSS3 / HTML5
- Node.js 24.15.0 & npm 11.12.1

## Libraries & Tools (and why)
- Laravel Framework: Robust web application foundation with routing, Blade templating, and SQLite support.
- Vite & Laravel Vite Plugin: Fast asset bundling and modern frontend development.
- TailwindCSS (v4 devDependency): Utility CSS framework available for future styling extensions.
- FontAwesome 6.5.1 CDN: Standardized UI iconography for navigation and inventory action indicators.
- PHPUnit: Automated unit and feature testing.

## Domain Concepts
- Entities:
  - Product: Item in inventory with name, SKU, category, quantity, price.
  - Stock Movement: Inbound/outbound stock events modifying available quantities.
  - Category / Supplier: Metadata grouping products and supply sources.
- Business rules:
  - Stock quantity cannot be negative.
  - Low stock warning triggered when quantity drops below the defined threshold.
- Glossary:
  - Stock In: Receiving additional inventory items.
  - Stock Out: Dispatching or selling inventory items.
  - Threshold: Minimum safe quantity before alert triggers.

## Skills to Apply
- HCI / UX: Clean desktop topbar navigation, responsive mobile bottom tabs, clear visual status indicators.
- Accessibility (WCAG): ARIA attributes on buttons, high-contrast readable text, keyboard accessible dropdowns.
- Security: CSRF protection, input sanitation, strict route validation, secure environment configuration.
- Testing: Automated feature tests verifying route health and blade rendering.
- Performance: Static asset caching, lightweight vanilla JS interactions without heavy runtime overhead.
- Maintainability / refactoring: Clean file structure at repository root, eliminating nested duplicate projects.

## Skills Explicitly Out of Scope
- Machine learning or predictive inventory AI models.
- Native mobile development (iOS/Android).
- Distributed cloud orchestration or microservices.
