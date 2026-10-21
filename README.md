# LocalPro

LocalPro is a Laravel marketplace MVP for finding trusted local service professionals in Ho and the Volta Region.

## MVP features

- Responsive public marketplace homepage with service search and location selection.
- Service categories loaded from the database.
- Public listings show approved provider profiles only; no sample professionals or dashboard figures are fabricated.
- Customer and provider registration, role based login, password reset, and logout.
- Provider profiles, service listings, private identity document uploads, and an admin approval queue.
- Search filters for service, town, rating, and indicative starting price.
- Customer service requests, provider quotes and scheduling, status tracking, and request scoped messaging.
- In-app notifications and customer reviews after completed jobs.
- A support inbox for account, verification, payment, safety, and booking concerns. Customers can open a linked dispute from an accepted or completed request; admins can track the case, update its status, and email a reply.
- Provider payout requests against recorded customer payments, with administrator review and external transfer reference tracking. No bank details are collected and no transfer is sent by LocalPro.
- Customer, provider, and admin dashboards, plus admin account, category, review, request, and payment ledger screens.
- SQLite is configured for local development. MySQL remains available through Laravel's database configuration for deployment.

## Run locally

1. Install PHP dependencies with `composer install` and frontend dependencies with `npm install`.
2. Copy `.env.example` to `.env` if needed and generate an application key: `php artisan key:generate`.
3. Create an empty `database/database.sqlite` file if it does not exist.
4. Create the schema and local service categories with `php artisan migrate --seed`.
5. Compile assets with `npm run build` (or use `npm run dev` while developing).
6. Create the initial admin interactively with `php artisan localpro:make-admin`.
7. Start Laravel with `php artisan serve` and open the address it prints.

## Deploy on Render with Docker

The repository includes a multi-stage `Dockerfile` that installs PHP dependencies, compiles the Vite assets, and serves Laravel with Apache on Render's default port `10000`. The container start script caches Laravel configuration, runs pending migrations with `--force`, creates Laravel's public storage link, and starts Apache.

1. Push this repository to GitHub. `.env` is excluded from the Docker build and must never be committed.
2. In Render, create a **Web Service**, connect the GitHub repository, and choose **Docker** as the runtime. Render will use the repository's `Dockerfile`; leave custom build and start commands unset.
3. Add the production environment variables in Render's **Environment** page:

   | Key | Value |
   | --- | --- |
   | `APP_ENV` | `production` |
   | `APP_DEBUG` | `false` |
   | `APP_KEY` | A new key from `php artisan key:generate --show` (do not reuse the local key) |
   | `APP_URL` | The HTTPS URL Render assigns to the service (for example, `https://your-service.onrender.com`) |
   | `ASSET_URL` | Leave unset, or use the same HTTPS origin as `APP_URL` |
   | `SESSION_SECURE_COOKIE` | `true` |
   | `DB_CONNECTION` | `pgsql` |
   | `DATABASE_URL` | Your database's PostgreSQL connection URL; use the Render internal URL for a same-region Render database, or your Supabase connection URL |
   | `DB_SSLMODE` | `require` when your database requires TLS |
   | `DB_SEARCH_PATH` | `laravel` if your tables are in the Supabase `laravel` schema; otherwise use the schema containing your tables, often `public` |
   | `FILESYSTEM_DISK` | `r2` |
   | `R2_ACCESS_KEY_ID`, `R2_SECRET_ACCESS_KEY`, `R2_BUCKET`, `R2_ENDPOINT` | The credentials, bucket name, and S3 endpoint from Cloudflare R2 |
   | `R2_DEFAULT_REGION` | `auto` |

   Add database passwords and R2 credentials as private environment values in Render. Do not put them in this README or commit them. For Supabase, use its PostgreSQL connection URI. For Render Postgres, create the database in the same region as the web service and copy its **internal** URL.
4. Deploy. On startup, the container runs `php artisan migrate --force`; check Render's deploy logs for migration or connection errors.
5. After deployment, create the first administrator through the Render service shell with `php artisan localpro:make-admin`. Test registration, login, password reset with a configured production mailer, provider document upload/download, and the admin verification flow.

The app uses file cache, file sessions, and a synchronous queue by default, so Memcached and Redis are not required for a single Render instance. Set `FILESYSTEM_DISK=r2` and configure the R2 credentials before accepting provider photos or verification documents; Render's normal container filesystem is ephemeral, so local-disk uploads do not survive deployments. If existing provider photos were uploaded to the local disk, upload them again after R2 is configured. Configure SMTP credentials in Render as well if password-reset emails need to reach users.

## Before inviting real users

- Set `APP_NAME`, `APP_URL`, and a private `APP_KEY` for the target environment. Never reuse a local key in a public deployment.
- Set `APP_ENV=production` and `APP_DEBUG=false` on the public server. Keep `.env` and uploaded verification documents out of the web root and source control.
- Use a supported production database, HTTPS, scheduled database and private-file backups, and a process for restoring them.
- Configure a real mail transport. Password-reset messages and admin support replies use Laravel mail; local Mailpit settings are only for development.
- Create the first administrator with `php artisan localpro:make-admin`. Do not create admin accounts through public registration.
- Run `php artisan migrate --force` during deployment and `php artisan config:cache`, `php artisan route:cache`, and `php artisan view:cache` after environment values are set.
- Have the provider verification process, support response ownership, review moderation, cancellation handling, and customer/provider terms ready before onboarding the public.

## Payments and payouts

Paystack checkout is available after configuration. For local development, put a Paystack **test** secret key in the private `.env` as `PAYSTACK_SECRET_KEY=sk_test_...`; never commit or share the key. Set `APP_URL` to the URL customers use to reach the app. The return callback works during local testing, but Paystack webhooks need a publicly reachable HTTPS URL. After deployment, configure the Paystack webhook as `https://your-domain/api/paystack/webhook`. The callback and webhook verify transaction status, reference, amount, currency, and webhook signature before recording a payment. The Paystack merchant account receives the full collected amount. Providers can submit payout requests from the earnings page and administrators can approve, reject, or record them as paid after an external transfer. LocalPro does not collect bank details or send the transfer. Provider split settlement, automated transfers, refunds, chargeback handling, and payout account onboarding are not implemented. Decide commission, payout, refund, and transfer-verification rules before enabling live payments.

## Current service boundaries

- Search uses the towns and service areas entered in provider profiles; map pins and geocoding are not configured.
- Messages and notifications are stored in the application and shown in the signed-in experience. Real-time delivery and SMS are not configured.
- Provider identity files use Laravel's private local disk. For a public deployment, configure private object storage, access controls, retention rules, and backups before accepting documents.
- Support cases are saved for admins. Admin replies are emailed only when the configured mail transport can send them; the case is still saved if delivery fails.
- Payout requests reserve eligible paid customer collections and are tracked for provider/admin review. The record does not itself move funds; only record a payout as paid after verifying an external transfer.

## Pre-launch walkthrough

Create one customer, one provider, and one administrator account in a private staging database. Submit a provider verification document, approve the profile as admin, search and filter for that provider, send a request, accept and schedule it, exchange messages, mark it complete, submit a review, and submit a support case. If Paystack is enabled, use test keys and test the callback and webhook before considering live credentials. Automated tests exist for selected flows; expand coverage for support, payment verification, permissions, and failure handling before a public launch.

## Google sign-in setup

Google sign-in uses Laravel Socialite. Add `GOOGLE_CLIENT_ID` and `GOOGLE_CLIENT_SECRET` to the deployment environment, and set `GOOGLE_REDIRECT_URI` to the exact URL registered in Google Cloud Console (for example, `http://localhost:8000/auth/google/callback` during local development). Run `php artisan migrate` to add the Google account ID column. Google sign-in will create customer accounts by default; choosing provider registration starts a pending provider profile that still needs completion and identity verification. Without Google credentials, the email and password forms remain available and the Google button displays a setup message.
