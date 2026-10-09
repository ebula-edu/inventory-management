# Deploying to Vercel

## Project setup

Import the repository into Vercel and keep the project root at the repository root. The repository's `vercel.json` builds the Vite assets and routes requests through the Laravel PHP function.

Set these environment variables in the Vercel project for every deployment environment:

| Variable | Value |
| --- | --- |
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_KEY` | Generate locally with `php artisan key:generate --show` and add the resulting key as a Vercel secret. |
| `APP_URL` | The deployed Vercel URL, including `https://`. |
| `DB_CONNECTION` | `mysql` |
| `DB_HOST` | Hostname of a production MySQL-compatible database. |
| `DB_PORT` | Database port, usually `3306`. |
| `DB_DATABASE` | Production database name. |
| `DB_USERNAME` | Database username. |
| `DB_PASSWORD` | Database password. Store this as a secret. |
| `SESSION_DRIVER` | `database` |
| `CACHE_STORE` | `database` |
| `LOG_CHANNEL` | `stderr` |

The checked-in SQLite configuration is for local development and must not be used for production inventory data. Vercel function storage is temporary; the application database must be an external persistent database reachable from Vercel. Configure a MySQL-compatible provider and allow inbound connections from Vercel as required by that provider.

Run the migrations against the production database before serving traffic:

```powershell
$env:DB_CONNECTION = 'mysql'
$env:DB_HOST = '...'
$env:DB_PORT = '3306'
$env:DB_DATABASE = '...'
$env:DB_USERNAME = '...'
$env:DB_PASSWORD = '...'
php artisan migrate --force
```

Use the production database credentials in the shell environment before running the migration command; do not commit them. The migration set includes Laravel's database session and cache tables.

## Deploy

Push the deployment branch to the connected Git repository, or deploy with the Vercel CLI:

```powershell
npm install --global vercel
vercel login
vercel
```

Vercel's PHP runtime is `vercel-php@0.9.0` (PHP 8.5). Confirm the deployment build succeeds and test the dashboard, product listing, and a database-backed write before directing users to the deployment.