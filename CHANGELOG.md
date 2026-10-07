# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Changed
- Consolidated application architecture by moving all inventory views, static assets, scripts, and dependencies from nested `My_Inventory` directory into the root Laravel application.
- Updated root `routes/web.php` to render the inventory interface (`inventory.blade.php`).
- Removed duplicate default Laravel welcome view.
- Removed nested redundant `My_Inventory` directory.
