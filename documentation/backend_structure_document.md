# Backend Structure Document

## 1. Backend Architecture

**Overview**

Our backend is built on the Laravel framework (PHP 8.x, Laravel 9.x) and follows the Model–View–Controller (MVC) pattern. This gives us a clear separation between data (models), user interface logic (views), and application logic (controllers).

**Key Design Patterns and Frameworks**

- MVC (Model–View–Controller) via Laravel
- Repository Pattern for data access abstraction
- Service Layer for business logic (e.g., payment processing, transcoding)
- Event–Listener and Observer for notifications and analytics updates
- Queues and Jobs for long-running tasks (transcoding, email delivery)

**Tech Stack**

- PHP 8.x, Laravel 9.x
- MySQL
- DigitalOcean Spaces + CDN for storage and delivery
- FFmpeg (HLS transcoding)
- Stripe, Stripe Connect, PayPal for payments
- Blade templates, HTML5, CSS3, JavaScript
- Node.js, npm, Laravel Mix
- Git (GitHub/GitLab) for version control
- SendGrid or Mailgun (SMTP) for email delivery

**Scalability, Maintainability, Performance**

- **Scalability:** Stateless web servers behind a load balancer allow horizontal scaling. Heavy tasks (video transcoding, email) are offloaded to queue workers. Media files live in DigitalOcean Spaces/CDN.
- **Maintainability:** Clear folder structure, use of Laravel's service providers, repositories, and well-documented controllers and services. Database schema changes via migrations.
- **Performance:** Caching of config/routes/views, CDN for static assets and video segments, HLS adaptive streaming, optimized database indexes, and eager loading in Eloquent.

## 2. Database Management

**Database Technology**

- Relational database (SQL) using MySQL.
- DigitalOcean Managed Databases for MySQL with automated backups and high availability.

**Data Access and Practices**

- **Eloquent ORM:** Simplifies querying and relationships.
- **Migrations & Seeders:** Version-controlled schema changes and sample data.
- **Transactions:** Ensure atomic operations for payments and uploads.
- **Indexes:** Applied on frequently queried fields (e.g., `user_id`, `entertainment_id`, `status`).
- **Backups:** Automated daily snapshots; off-site storage for disaster recovery.

## 3. Database Schema

### Human-Readable Overview

1.  **users**: Stores all user accounts with profile information
2.  **user_providers**: Social login providers for users
3.  **user_profiles**: Extended user profile information
4.  **user_multi_profiles**: Multiple profiles per user
5.  **devices**: User device information
6.  **entertainments**: Main content table for movies/TV shows
7.  **entertainment_gener_mapping**: Mapping between entertainment and genres
8.  **entertainment_talent_mapping**: Mapping between entertainment and cast/crew
9.  **entertainment_country_mapping**: Mapping between entertainment and countries
10. **entertainment_stream_content_mapping**: Streaming content URLs and qualities
11. **entertainment_download_mapping**: Download content URLs and qualities
12. **entertainment_views**: View tracking for entertainment content
13. **entertainment_downloads**: Download tracking for entertainment content
14. **reviews**: User reviews and ratings
15. **watchlists**: User watchlists
16. **likes**: User likes/dislikes
17. **continue_watch**: Track user's watch progress
18. **user_reminder**: Content release reminders
19. **user_search_histories**: User search history
20. **user_watch_histories**: User watch history
21. **seasons**: TV show seasons
22. **episodes**: TV show episodes
23. **episode_download_mapping**: Episode download URLs and qualities
24. **videos**: Video content
25. **livetvs**: Live TV channels
26. **live_tv_category**: Live TV categories
27. **live_tv_stream_content_mapping**: Live TV streaming content
28. **genres**: Content genres
29. **plan**: Subscription plans
30. **planlimitation_mapping**: Plan limitations
31. **subscriptions**: User subscriptions
32. **settings**: System settings
33. **mobile_settings**: Mobile app settings
34. **notifications**: System notifications
35. **activity_log**: Activity logging
36. **permissions**: System permissions
37. **roles**: User roles
38. **model_has_permissions**: Permission assignments
39. **model_has_roles**: Role assignments
40. **role_has_permissions**: Role permission mappings
41. **password_resets**: Password reset tokens
42. **personal_access_tokens**: API access tokens
43. **sessions**: User sessions
44. **jobs**: Queue jobs
45. **failed_jobs**: Failed queue jobs
46. **job_batches**: Job batch tracking
47. **media**: Media files
48. **webhook_calls**: Webhook call logs

### SQL Schema Example (MySQL)

```sql
-- Users Table
CREATE TABLE users (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NULL,
    first_name VARCHAR(255) NULL,
    last_name VARCHAR(255) NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    mobile VARCHAR(255) NULL,
    login_type VARCHAR(255) NULL,
    file_url TEXT NULL,
    gender VARCHAR(255) NULL,
    date_of_birth DATE NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    is_banned TINYINT DEFAULT 0,
    is_subscribe TINYINT DEFAULT 0,
    status TINYINT DEFAULT 1,
    last_notification_seen TIMESTAMP NULL,
    address LONGTEXT NULL,
    user_type VARCHAR(255) NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL
);

-- Entertainments Table
CREATE TABLE entertainments (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NULL,
    tmdb_id VARCHAR(255) NULL,
    thumbnail_url TEXT NULL,
    poster_url TEXT NULL,
    description LONGTEXT NULL,
    trailer_url_type VARCHAR(255) NULL,
    type VARCHAR(255) NULL,
    trailer_url TEXT NULL,
    movie_access VARCHAR(255) NULL,
    plan_id BIGINT UNSIGNED NULL,
    language VARCHAR(255) NULL,
    IMDb_rating VARCHAR(255) NULL,
    content_rating LONGTEXT NULL,
    duration VARCHAR(255) NULL,
    release_date DATE NULL,
    is_restricted BOOLEAN DEFAULT 0,
    video_upload_type VARCHAR(255) NULL,
    video_url_input TEXT NULL,
    video_quality_url TEXT NULL,
    enable_quality BOOLEAN DEFAULT 0,
    download_status BOOLEAN DEFAULT 0,
    download_type VARCHAR(255) NULL,
    download_url TEXT NULL,
    enable_download_quality BOOLEAN DEFAULT 0,
    status BOOLEAN DEFAULT 0,
    created_by INT UNSIGNED NULL,
    updated_by INT UNSIGNED NULL,
    deleted_by INT UNSIGNED NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL
);

-- Subscriptions Table
CREATE TABLE subscriptions (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    plan_id INT NULL,
    user_id INT NULL,
    start_date DATETIME NULL,
    end_date DATETIME NULL,
    status VARCHAR(255) NULL,
    amount DOUBLE NULL,
    discount_percentage DOUBLE NULL,
    tax_amount DOUBLE NULL,
    total_amount DOUBLE NULL,
    name VARCHAR(255) NULL,
    identifier VARCHAR(255) NULL,
    type VARCHAR(255) NULL,
    duration INT NULL,
    level BIGINT DEFAULT 0,
    plan_type LONGTEXT NULL,
    payment_id INT NULL,
    device_id VARCHAR(255) NULL,
    created_by INT UNSIGNED NULL,
    updated_by INT UNSIGNED NULL,
    deleted_by INT UNSIGNED NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL
);

-- Plan Table
CREATE TABLE plan (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    identifier VARCHAR(255) NOT NULL,
    android_identifier VARCHAR(255) NULL,
    apple_identifier VARCHAR(255) NULL,
    price DOUBLE NULL,
    discount BOOLEAN DEFAULT 0,
    discount_percentage DOUBLE NULL,
    total_price DOUBLE NULL,
    level BIGINT DEFAULT 0,
    duration VARCHAR(255) NULL,
    duration_value BIGINT DEFAULT 1,
    status BOOLEAN DEFAULT 1,
    description TEXT NULL,
    created_by INT UNSIGNED NULL,
    updated_by INT UNSIGNED NULL,
    deleted_by INT UNSIGNED NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL
);
```

## 4. API Design and Endpoints

We follow a RESTful approach, using Laravel controllers to expose JSON endpoints for AJAX and internal use.

**Authentication & User Flows**

- `POST /api/register` – Register new user
- `POST /api/login` – User login
- `POST /api/social-login` – Social media login
- `POST /api/forgot-password` – Request password reset
- `GET /api/logout` – User logout
- `GET /api/user-detail` – Get user details
- `POST /api/update-profile` – Update user profile
- `POST /api/change-password` – Change password
- `POST /api/delete-account` – Delete user account

**Entertainment Content**

- `GET /api/movie-list` – List movies
- `GET /api/movie-details` – Get movie details
- `GET /api/tvshow-list` – List TV shows
- `GET /api/tvshow-details` – Get TV show details
- `GET /api/episode-list` – List episodes
- `GET /api/episode-details` – Get episode details
- `GET /api/coming-soon` – Get upcoming content
- `GET /api/search-list` – Search content
- `GET /api/get-search` – Advanced search

**User Content Management**

- `GET /api/watch-list` – Get user's watchlist
- `POST /api/save-watchlist` – Add to watchlist
- `POST /api/delete-watchlist` – Remove from watchlist
- `GET /api/continuewatch-list` – Get continue watching list
- `POST /api/save-continuewatch` – Save watch progress
- `POST /api/delete-continuewatch` – Remove from continue watching
- `POST /api/save-reminder` – Set content reminder
- `POST /api/delete-reminder` – Remove reminder
- `POST /api/save-entertainment-views` – Track content views
- `POST /api/save-download` – Save download
- `POST /api/delete-download` – Remove download

**Reviews and Ratings**

- `GET /api/get-rating` – Get content ratings
- `POST /api/save-rating` – Add rating
- `POST /api/delete-rating` – Remove rating
- `PUT /api/update-rating` – Update rating
- `POST /api/save-likes` – Like/dislike content

**User Profiles**

- `GET /api/user-profile-list` – List user profiles
- `POST /api/save-userprofile` – Create user profile
- `GET /api/get-userprofile/{id}` – Get profile details
- `POST /api/delete-userprofile` – Delete profile
- `GET /api/select-userprofile/{id}` – Switch active profile

**Search History**

- `GET /api/search-list` – Get search history
- `POST /api/save-search` – Save search
- `GET /api/delete-search` – Clear search history

**Live TV**

- `GET /api/livetv-category-list` – List Live TV categories
- `GET /api/livetv-dashboard` – Get Live TV dashboard
- `GET /api/livetv-details` – Get channel details
- `GET /api/channel-list` – List channels

**Video Content**

- `GET /api/video-list` – List videos
- `GET /api/video-details` – Get video details

**System**

- `GET /api/dashboard-detail` – Get dashboard data
- `GET /api/dashboard-detail-data` – Get detailed dashboard data
- `GET /api/get-tranding-data` – Get trending content
- `GET /api/app-configuration` – Get app configuration
- `POST /api/store-access-token` – Store access token
- `POST /api/token-revoke` – Revoke access token
- `GET /api/page-list` – Get pages
- `GET /api/faq-list` – Get FAQs

Each endpoint uses standard HTTP verbs to indicate intent (GET for read, POST for create, PUT for update, DELETE for removal). JSON is returned for API calls.

## 5. Hosting Solutions

**Compute & Application Servers**

- DigitalOcean Droplets running PHP-FPM + Nginx
- Load Balancer distributes traffic

**Database**

- DigitalOcean Managed Databases for MySQL with automated backups and high availability

**Storage & CDN**

- DigitalOcean Spaces for video, audio, subtitle, and proof document storage
- DigitalOcean CDN to cache static assets and HLS streams globally

**Benefits**

- **Reliability:** High availability MySQL and redundant Droplets
- **Scalability:** Auto-scaling web tier; read replicas for database
- **Cost-Effectiveness:** Pay-as-you-go; separate storage/compute billing

## 6. Infrastructure Components

- **Load Balancer:** Distributes client requests; health checks keep bad instances out.
- **CDN (DigitalOcean CDN):** Caches video segments, static assets; reduces latency.
- **Object Storage (Spaces):** Durable, cost-effective media storage.
- **Transcoding Workers:** Droplets running FFmpeg jobs from a queue.
- **Queue System:** Laravel Queue with database driver; Supervisor manages workers.
- **Email Service:** SendGrid/Mailgun for transactional emails.
- **Backup & Recovery:** Automated database snapshots; Spaces lifecycle policies for retention.

## 7. Security Measures

- **Transport Security:** HTTPS enforced via SSL/TLS certificates (Let's Encrypt).
- **Authentication:** Laravel's secure password hashing (bcrypt), email verification, security questions.
- **Authorization:** Role-based middleware (user, producer, admin) protecting routes.
- **Input Validation & Sanitization:** Laravel Form Requests guard against SQL injection and XSS.
- **CSRF Protection:** Enabled globally for web routes.
- **Data Encryption:** Spaces server-side encryption; database encryption at rest.
- **Payment Compliance:** PCI-DSS adherence by using Stripe/PayPal tokens—no credit card data stored on our servers.
- **OWASP Best Practices:** Rate limiting, secure headers (CSP, HSTS), dependency scanning.

## 8. Monitoring and Maintenance

- **Logging & Monitoring:**

  - DigitalOcean Monitoring for Droplet metrics
  - Laravel logs (monitored via centralized logging)
  - Exception tracking with Sentry

- **Alerts:** Email notifications for high error rates, CPU spikes, low disk space

- **Maintenance:**

  - CI/CD pipeline for automated testing, code style checks, and zero-downtime deployments
  - Scheduled database maintenance windows
  - Regular dependency updates and security patches

## 9. Conclusion and Overall Backend Summary

The MatesTV backend is a robust, scalable, and secure platform built on Laravel and MySQL. It separates concerns cleanly via MVC and service layers, offloads heavy tasks through queues, and delivers media globally with Spaces+CDN. Our RESTful API, clear data schema, and automated infrastructure ensure producers can upload and monetize content, users can purchase and stream seamlessly, and admins can manage the platform efficiently. Security best practices and monitoring guarantee reliability and compliance. This backend design aligns perfectly with business goals: performance, scalability, and a smooth user experience for all roles.
