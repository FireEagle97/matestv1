# Implementation plan

## Phase 1: Environment Setup

1.  **Prevalidation**: In project root, run `test -f composer.json` to confirm you’re in the existing Laravel application directory. If missing, stop and clone the Streamit repo first. (Project Summary: Existing Application)
2.  Check PHP version is 8.x: run `php -v`. If not installed, install PHP 8.1 exactly. (Tech Stack: Backend)
3.  Check Composer is installed: run `composer --version`. If missing, install Composer v2.x. (Tech Stack: Backend)
4.  Check Node.js is v20.2.1: run `node -v`. If not, install Node.js v20.2.1. (Tech Stack: Build & Tooling)
5.  Check npm is installed: run `npm -v`. If missing, install npm bundled with Node.js. (Tech Stack: Build & Tooling)
6.  Clone the existing Streamit repo: `git clone <STREAMIT_REPO_URL> matesTv && cd matesTv` then create a feature branch: `git checkout -b feature/matesTv-initial-setup`. (Project Summary: Project Overview)
7.  Create Cursor tracking file: `mkdir -p .cursor` and `touch cursor_metrics.md` at project root, then open `cursor_project_rules.mdc` to understand metrics reporting. (IDE & AI Coding: Cursor)
8.  **Validation**: Run `composer install` and confirm `vendor/` directory exists. (Tech Stack: Backend)
9.  **Validation**: Run `npm install` and confirm `node_modules/` directory exists. (Tech Stack: Build & Tooling)
10. Copy environment file: `cp .env.example .env` and set `APP_URL=http://localhost:8000`. (Laravel Docs)
11. Generate application key: `php artisan key:generate`. (Laravel Docs)
12. Create storage symlink: `php artisan storage:link`. (Laravel Docs)
13. Configure AWS S3 in `.env`: set `FILESYSTEM_DRIVER=s3`, `AWS_ACCESS_KEY_ID=`, `AWS_SECRET_ACCESS_KEY=`, `AWS_DEFAULT_REGION=us-east-1`, `AWS_BUCKET=`, `AWS_URL=`. Ask user to obtain these from the AWS Console. (Project Summary: Video Storage)
14. Check FFmpeg is installed: run `ffmpeg -version`. If missing, install FFmpeg (>=4.4) for HLS transcoding. (Project Summary: Transcoding)
15. Update `webpack.mix.js` to include HLS JavaScript libraries for adaptive streaming. (Project Summary: Transcoding)

## Phase 2: Frontend Development

1.  Create base layout: `resources/views/layouts/app.blade.php` with dynamic nav for User/Producer/Admin. (Tech Stack: Frontend)
2.  Implement Producer registration view at `resources/views/auth/producer-register.blade.php` with email, password, terms checkbox. (Project Summary: Producer Features – Registration)
3.  Enable email verification: in `app/Providers/AuthServiceProvider.php`, register `Illuminate\Auth\Notifications\VerifyEmail`. (Project Summary: Producer Features – Registration)
4.  Implement Producer profile management view at `resources/views/producer/profile.blade.php` with name, bio, contact, profile & banner upload fields. (Project Summary: Producer Features – Profile Management)
5.  Add routes in `routes/web.php`:

`Route::middleware('auth','role:producer')->prefix('producer')->group(function(){ Route::get('profile','ProducerController@edit'); Route::post('profile','ProducerController@update'); }); `(Project Summary: Producer Features – Profile Management)

1.  Create Movie upload view at `resources/views/producer/movie-upload.blade.php` with inputs for video, audio, subtitles, ownership docs, title, description, genre, price. (Project Summary: Producer Features – Movie Uploads)
2.  Add client-side validation: in `resources/js/producer-upload.js`, use Bootstrap/Tailwind validation classes. (Project Summary: Producer Features – Movie Uploads)
3.  Implement User registration view at `resources/views/auth/user-register.blade.php` with email, password, terms checkbox. (Project Summary: User Features – Registration)
4.  Implement browse movies list at `resources/views/movies/index.blade.php` showing thumbnails, titles, prices, and a “Buy” button. (Project Summary: User Features – Browse and Purchase)
5.  Implement movie detail and purchase view at `resources/views/movies/show.blade.php` with “Purchase” form. (Project Summary: User Features – Browse and Purchase)
6.  Implement User library at `resources/views/library/index.blade.php` listing purchased movies with HLS player embed. (Project Summary: User Features – Movie Library)
7.  Add Featured Movies section in `resources/views/home.blade.php` displaying admin-selected featured content. (Project Summary: Admin Features – Featured Movies)
8.  Ensure all views extend `layouts/app.blade.php` and include CSRF tokens on forms. (Tech Stack: Frontend)
9.  **Validation**: Run `npm run dev` and confirm no JS/CSS compilation errors. (Tech Stack: Build & Tooling)
10. **Validation**: Navigate to each new route in browser to confirm pages render (<http://localhost:8000>). (Integration)

## Phase 3: Backend Development

1.  Create migrations in `/database/migrations/` for:

    *   `producers` (id, user_id, bio, contact_info, profile_url, banner_url, stripe_account_id, paypal_email, terms_agreed_at)
    *   `movies` (id, producer_id, title, description, genre, price, status, featured)
    *   `movie_assets` (id, movie_id, type [video/audio/subtitle/doc], url)
    *   `purchases` (id, user_id, movie_id, price_paid, commission_amount, commission_pct, purchased_at)
    *   `payouts` (id, producer_id, amount, status, requested_at, processed_at)
    *   `featured_movies` (id, movie_id, fee_paid, start_date, end_date) (Project Summary: Data Model)

2.  **Validation**: Run `php artisan migrate` and verify tables exist in MySQL. (Validation)

3.  Create Eloquent models in `app/Models`: `Producer.php`, `Movie.php`, `MovieAsset.php`, `Purchase.php`, `Payout.php`, `FeaturedMovie.php`, defining relationships (`hasMany`, `belongsTo`). (Project Summary: Data Model)

4.  Create controllers in `app/Http/Controllers`: `ProducerController.php`, `MovieController.php`, `PurchaseController.php`, `AdminController.php`. (Project Summary: Feature Controllers)

5.  In `ProducerController@register`, implement registration logic with validation rules, `User::create()`, and `event(new Registered($user))`. (Project Summary: Producer Features – Registration)

6.  In `MovieController@store`, implement file upload handling:

    *   Validate file types/sizes
    *   Store raw files on S3 via `Storage::disk('s3')->putFile()`
    *   Invoke FFmpeg (`exec('ffmpeg ...')`) for HLS segments
    *   Save segment URLs to `movie_assets` table (Project Summary: Producer Features – Movie Uploads)

7.  In `PurchaseController@store`, implement purchase flow:

    *   Integrate Stripe SDK for payment intent
    *   Capture payment, calculate 50% commission
    *   Transfer remainder via Stripe Connect to producer
    *   Save record in `purchases` table (Project Summary: Payment Gateway Integration)

8.  In `ProducerController@connectStripe`, implement Stripe Connect account linking using OAuth flow, save `stripe_account_id`. (Project Summary: Payment Settings)

9.  In `AdminController@approveProducer` and `@approveMovie`, implement approval endpoints and update `status` fields. (Project Summary: Admin Features – Approval)

10. Create Mailables in `app/Mail`: `ProducerApprovedMail`, `MovieApprovedMail`, `PurchaseReceiptMail`, `PayoutProcessedMail` and queue them in respective controller actions. (Project Summary: Email Notifications)

11. **Validation**: Run `php artisan test` to execute PHPUnit tests for controllers and models. (Validation)

## Phase 4: Integration

1.  Define routes in `routes/web.php` for all controllers with proper middleware (`auth`, `role:producer`, `role:admin`). (Project Summary: Routes)

2.  Update Blade form `action` attributes and include `@method`/`@csrf` directives to point to newly defined routes. (Project Summary: Integration)

3.  Configure CORS in `config/cors.php` to allow `http://localhost:8000`. (Tech Stack: Security)

4.  **Validation**: End-to-end test:

    1.  Register a producer
    2.  Upload a movie
    3.  Approve as admin
    4.  Register a user and purchase the movie
    5.  Confirm HLS streaming works (Integration)

## Phase 5: Deployment

1.  Create AWS S3 bucket `matesTv-videos` in `us-east-1` and CloudFront distribution for HLS origin. (Project Summary: Video Storage)

2.  In `config/filesystems.php`, ensure `s3` disk is configured with `env()` values from `.env`. (Project Summary: Video Storage)

3.  Create GitHub Actions workflow at `.github/workflows/deploy.yml` to:

    *   Run tests
    *   Deploy code to AWS Elastic Beanstalk (PHP 8.1 environment)
    *   Invalidate CloudFront cache (Project Summary: Deployment)

4.  Configure environment variables in Elastic Beanstalk console, including AWS keys, Stripe & PayPal secrets, SMTP credentials. (Project Summary: Deployment)

5.  **Validation**: After deployment, run a smoke test:

    *   Register producer & user
    *   Upload, approve, purchase, and stream a movie in production
    *   Check email notifications (Project Summary: Pre-Launch Checklist)
