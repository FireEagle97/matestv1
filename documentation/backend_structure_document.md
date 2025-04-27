# Backend Structure Document

## 1. Backend Architecture

**Overview**

Our backend is built on the Laravel framework (PHP 8.x, Laravel 9.x) and follows the Model–View–Controller (MVC) pattern. This gives us a clear separation between data (models), user interface logic (views), and application logic (controllers).

**Key Design Patterns and Frameworks**

*   MVC (Model–View–Controller) via Laravel
*   Repository Pattern for data access abstraction
*   Service Layer for business logic (e.g., payment processing, transcoding)
*   Event–Listener and Observer for notifications and analytics updates
*   Queues and Jobs for long-running tasks (transcoding, email delivery)

**Tech Stack**

*   PHP 8.x, Laravel 9.x
*   MySQL
*   AWS S3 (or DigitalOcean Spaces) + CDN (CloudFront or equivalent)
*   FFmpeg (HLS transcoding)
*   Stripe, Stripe Connect, PayPal
*   Blade templates, HTML5, CSS3, JavaScript
*   Node.js, npm, Laravel Mix
*   Git (GitHub/GitLab) for version control
*   SendGrid or Mailgun (SMTP) for email delivery

**Scalability, Maintainability, Performance**

*   **Scalability:** Stateless web servers behind a load balancer allow horizontal scaling. Heavy tasks (video transcoding, email) are offloaded to queue workers. Media files live in S3/CDN.
*   **Maintainability:** Clear folder structure, use of Laravel’s service providers, repositories, and well-documented controllers and services. Database schema changes via migrations.
*   **Performance:** Caching of config/routes/views, CDN for static assets and video segments, HLS adaptive streaming, optimized database indexes, and eager loading in Eloquent.

## 2. Database Management

**Database Technology**

*   Relational database (SQL) using MySQL.
*   Managed service (AWS RDS Multi-AZ for high availability) or self-hosted MySQL cluster, or DigitalOcean Managed Databases for MySQL.

**Data Access and Practices**

*   **Eloquent ORM:** Simplifies querying and relationships.
*   **Migrations & Seeders:** Version-controlled schema changes and sample data.
*   **Transactions:** Ensure atomic operations for payments and uploads.
*   **Indexes:** Applied on frequently queried fields (e.g., `user_id`, `movie_id`, `status`).
*   **Backups:** Automated daily snapshots of RDS; off-site storage for disaster recovery.

## 3. Database Schema

### Human-Readable Overview

1.  **users**: Stores all user accounts (roles: user, producer, admin).
2.  **movies**: Metadata and status for each video (belongs to a producer).
3.  **movie_files**: Paths and types for uploaded video, audio, subtitles, proofs.
4.  **sales**: Records user purchases of movies.
5.  **views**: Time-stamped view records for analytics.
6.  **payment_requests**: Producers’ payout requests and statuses.
7.  **transactions**: Stripe/PayPal transaction logs.
8.  **featured_movies**: Movies promoted in the featured section.
9.  **notifications**: In-app and email notification queue.
10. **password_resets**: Standard Laravel password reset tokens.

### SQL Schema Example (MySQL)

`CREATE TABLE users ( id BIGINT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100) NOT NULL, email VARCHAR(150) UNIQUE NOT NULL, password VARCHAR(255) NOT NULL, role ENUM('user','producer','admin') NOT NULL DEFAULT 'user', email_verified_at DATETIME NULL, security_questions JSON NULL, profile_picture VARCHAR(255) NULL, banner_image VARCHAR(255) NULL, created_at DATETIME, updated_at DATETIME ); CREATE TABLE movies ( id BIGINT AUTO_INCREMENT PRIMARY KEY, producer_id BIGINT NOT NULL, title VARCHAR(255) NOT NULL, description TEXT NULL, price DECIMAL(8,2) NOT NULL, metadata JSON NULL, approval_status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending', visibility ENUM('public','private') NOT NULL DEFAULT 'public', proof_document_path VARCHAR(255), created_at DATETIME, updated_at DATETIME, FOREIGN KEY (producer_id) REFERENCES users(id) ); CREATE TABLE movie_files ( id BIGINT AUTO_INCREMENT PRIMARY KEY, movie_id BIGINT NOT NULL, file_type ENUM('video','audio','subtitle','proof') NOT NULL, file_path VARCHAR(255) NOT NULL, created_at DATETIME, FOREIGN KEY (movie_id) REFERENCES movies(id) ); CREATE TABLE sales ( id BIGINT AUTO_INCREMENT PRIMARY KEY, movie_id BIGINT NOT NULL, buyer_id BIGINT NOT NULL, sale_price DECIMAL(8,2) NOT NULL, sale_date DATETIME NOT NULL, FOREIGN KEY (movie_id) REFERENCES movies(id), FOREIGN KEY (buyer_id) REFERENCES users(id) ); CREATE TABLE views ( id BIGINT AUTO_INCREMENT PRIMARY KEY, movie_id BIGINT NOT NULL, viewer_id BIGINT NULL, ip_address VARCHAR(45) NULL, location VARCHAR(100) NULL, viewed_at DATETIME NOT NULL, FOREIGN KEY (movie_id) REFERENCES movies(id) ); CREATE TABLE payment_requests ( id BIGINT AUTO_INCREMENT PRIMARY KEY, producer_id BIGINT NOT NULL, amount DECIMAL(10,2) NOT NULL, status ENUM('pending','paid','rejected') NOT NULL DEFAULT 'pending', requested_at DATETIME NOT NULL, processed_at DATETIME NULL, FOREIGN KEY (producer_id) REFERENCES users(id) ); CREATE TABLE transactions ( id BIGINT AUTO_INCREMENT PRIMARY KEY, user_id BIGINT NOT NULL, payment_gateway ENUM('stripe','paypal') NOT NULL, gateway_transaction_id VARCHAR(255) NOT NULL, amount DECIMAL(10,2) NOT NULL, currency VARCHAR(10) NOT NULL, status VARCHAR(50) NOT NULL, created_at DATETIME, FOREIGN KEY (user_id) REFERENCES users(id) ); CREATE TABLE featured_movies ( id BIGINT AUTO_INCREMENT PRIMARY KEY, movie_id BIGINT NOT NULL, fee_paid DECIMAL(8,2) NOT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, FOREIGN KEY (movie_id) REFERENCES movies(id) ); CREATE TABLE notifications ( id BIGINT AUTO_INCREMENT PRIMARY KEY, user_id BIGINT NOT NULL, type VARCHAR(100) NOT NULL, data JSON NOT NULL, read_at DATETIME NULL, created_at DATETIME, FOREIGN KEY (user_id) REFERENCES users(id) ); CREATE TABLE password_resets ( email VARCHAR(150) NOT NULL, token VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL );`

## 4. API Design and Endpoints

We follow a RESTful approach, using Laravel controllers to expose JSON endpoints for AJAX and internal use.

**Authentication & User Flows**

*   `POST /api/register` – Register as user or producer (role determined by request data)
*   `POST /api/login` – User login (returns session or token)
*   `POST /api/logout` – Invalidate session

**Producer Endpoints**

*   `GET /api/producer/profile` – Fetch producer profile
*   `PUT /api/producer/profile` – Update profile info
*   `POST /api/producer/movies` – Upload new movie (multipart/form-data)
*   `GET /api/producer/movies` – List uploaded movies
*   `GET /api/producer/movies/{id}` – View movie details
*   `POST /api/producer/payment-requests` – Request payout
*   `GET /api/producer/payment-requests` – View payout history

**User Endpoints**

*   `GET /api/movies` – List public movies (with pagination/filters)
*   `GET /api/movies/{id}` – Movie details
*   `POST /api/movies/{id}/purchase` – Purchase a movie
*   `GET /api/library` – List purchased movies

**Admin Endpoints**

*   `GET /api/admin/producers` – List pending/approved producers
*   `PUT /api/admin/producers/{id}/approve` – Approve producer
*   `PUT /api/admin/producers/{id}/reject` – Reject producer
*   `GET /api/admin/movies` – List all uploads (pending/approved)
*   `PUT /api/admin/movies/{id}/approve` – Approve movie
*   `PUT /api/admin/movies/{id}/reject` – Reject movie
*   `GET /api/admin/payment-requests` – View all payout requests
*   `PUT /api/admin/payment-requests/{id}/process` – Mark payout as paid
*   `GET /api/admin/featured` – List featured movies
*   `POST /api/admin/featured` – Add movie to featured
*   `DELETE /api/admin/featured/{id}` – Remove from featured

Each endpoint uses standard HTTP verbs to indicate intent (GET for read, POST for create, PUT for update, DELETE for removal). JSON is returned for API calls.

## 5. Hosting Solutions

**Compute & Application Servers**

*   AWS EC2 Auto Scaling Group (or DigitalOcean Droplets) running PHP-FPM + Nginx
*   Elastic Load Balancer (AWS ELB) distributes traffic

**Database**

*   AWS RDS for MySQL (Multi-AZ deployment, automated backups, read replicas for scaling)

**Storage & CDN**

*   AWS S3 for video, audio, subtitle, and proof document storage
*   Amazon CloudFront (or DigitalOcean CDN) to cache static assets and HLS streams globally

**Benefits**

*   **Reliability:** Multi-AZ MySQL and redundant EC2 instances
*   **Scalability:** Auto-scaling web tier; RDS read replicas
*   **Cost-Effectiveness:** Pay-as-you-go; separate storage/compute billing

## 6. Infrastructure Components

*   **Load Balancer:** Distributes client requests; health checks keep bad instances out.
*   **CDN (CloudFront):** Caches video segments, static assets; reduces latency.
*   **Object Storage (S3/Spaces):** Durable, cost-effective media storage.
*   **Transcoding Workers:** EC2 instances (or ECS tasks) running FFmpeg jobs from a queue.
*   **Queue System:** Laravel Queue with database (or SQS) driver; Supervisor manages workers.
*   **Email Service:** SendGrid/Mailgun for transactional emails.
*   **Backup & Recovery:** Automated RDS snapshots; S3 lifecycle policies for retention.

## 7. Security Measures

*   **Transport Security:** HTTPS enforced via SSL/TLS certificates (AWS ACM or Let’s Encrypt).
*   **Authentication:** Laravel’s secure password hashing (bcrypt), email verification, security questions.
*   **Authorization:** Role-based middleware (user, producer, admin) protecting routes.
*   **Input Validation & Sanitization:** Laravel Form Requests guard against SQL injection and XSS.
*   **CSRF Protection:** Enabled globally for web routes.
*   **Data Encryption:** S3 server-side encryption (AES-256); RDS encryption at rest.
*   **Payment Compliance:** PCI-DSS adherence by using Stripe/PayPal tokens—no credit card data stored on our servers.
*   **OWASP Best Practices:** Rate limiting, secure headers (CSP, HSTS), dependency scanning.

## 8. Monitoring and Maintenance

*   **Logging & Monitoring:**

    *   AWS CloudWatch for EC2/RDS metrics and logs
    *   Laravel logs (monitored via CloudWatch Logs or a centralized ELK stack)
    *   Exception tracking with Sentry or New Relic

*   **Alerts:** SNS or PagerDuty notifications for high error rates, CPU spikes, low disk space

*   **Maintenance:**

    *   CI/CD pipeline (GitHub Actions or GitLab CI) for automated testing, code style checks, and zero-downtime deployments
    *   Scheduled database maintenance windows
    *   Regular dependency updates and security patches

## 9. Conclusion and Overall Backend Summary

The MatesTV backend is a robust, scalable, and secure platform built on Laravel and MySQL. It separates concerns cleanly via MVC and service layers, offloads heavy tasks through queues, and delivers media globally with S3+CDN. Our RESTful API, clear data schema, and automated infrastructure ensure producers can upload and monetize content, users can purchase and stream seamlessly, and admins can manage the platform efficiently. Security best practices and monitoring guarantee reliability and compliance. This backend design aligns perfectly with business goals: performance, scalability, and a smooth user experience for all roles.
