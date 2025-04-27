# Tech Stack Document

This document explains, in everyday language, the technology choices behind the Laravel-based movie streaming platform. It shows what each tool does and why it was picked, so you can understand how they work together without needing a technical background.

## 1. Frontend Technologies

These are the tools that build what you see and interact with in your web browser.

*   **HTML, CSS & JavaScript**\
    The basic building blocks of any web page. HTML structures content, CSS styles it, and JavaScript makes it interactive.
*   **Blade Templates**\
    Laravel’s built-in page templates. They let us mix simple dynamic data (like movie titles and user names) into HTML without complicated code.
*   **Node.js & npm**\
    A small runtime (Node.js) and its package manager (npm) help us install and manage frontend libraries (like date pickers or sliders).
*   **Laravel Mix**\
    A tool that combines and optimizes CSS and JavaScript files for faster page loads. It turns raw source code into efficient bundles.

How this helps your users:

*   Pages look consistent and load quickly.
*   New visual features (carousels, filters) can be added without breaking existing pages.

## 2. Backend Technologies

These power all the behind-the-scenes work: data storage, user accounts, video processing, and payments.

*   **PHP & Laravel Framework**\
    PHP is the server-side language that runs our application. Laravel is a popular framework that provides structure, security features, and common tools (like user authentication) out of the box.
*   **MySQL Database**\
    A reliable place to store your users, movies, sales records, and all related data in an organized way.
*   **FFmpeg**\
    A tool that converts uploaded video files into HLS streams (adaptive video files), making sure videos play smoothly on any device.
*   **Stripe, Stripe Connect & PayPal**\
    Payment gateways that let users and producers pay securely. Stripe Connect handles split payments, automatically taking Matestv’s 50% commission before sending the rest to the producer.

How these work together:

1.  Users sign up or buy a movie & Laravel handles their requests.
2.  FFmpeg processes the upload into streaming formats.
3.  MySQL saves all movie, user, and payment information.
4.  Stripe/PayPal execute the transaction, with Laravel recording the outcome.

## 3. Infrastructure and Deployment

These decisions ensure the app stays up, fast, and easy to update.

*   **Hosting Platform**\
    DigitalOcean droplets (virtual servers) run our Laravel code. They’re reliable and easy to scale up if traffic grows.

*   **File Storage & CDN**

    *   **DigitalOcean Spaces** or **AWS S3** store large video and document files safely.
    *   **Amazon CloudFront** (CDN) caches and delivers videos around the world, so playback is quick no matter where a user is.

*   **Version Control with Git**\
    All code changes are tracked with Git, so we always know who did what, and can roll back if needed.

*   **Composer**\
    The PHP package manager. It keeps Laravel and related libraries up to date.

*   **CI/CD Pipeline**\
    Automated steps (for example, via GitHub Actions or GitLab CI) run tests and deploy code to production whenever we merge a change—making updates fast and reliable.

## 4. Third-Party Integrations

External services that add specialized features, saving development time.

*   **Stripe & PayPal** Secure payment processing, including split payments and recurring billing.
*   **AWS S3 / DigitalOcean Spaces & CloudFront** Scalable file storage and global content delivery.
*   **Email Service (SMTP or SendGrid)** Sends branded notifications: account verification, password resets, movie approvals, purchase receipts, and payment updates.
*   **Legal Template Service (optional)** Generates or hosts placeholder Terms and Conditions pages until final text is ready.

## 5. Security and Performance Considerations

Measures taken to keep user data safe and the site running smoothly.

*   **HTTPS Everywhere** All data in transit is encrypted with SSL certificates.
*   **Laravel Authentication & Password Hashing** Secure sign-up, login, email verification, and password reset flows.
*   **Security Questions & Email Verification** Added layers to protect accounts and confirm user identities.
*   **File Upload Restrictions** Automated checks on document and video uploads (file type and size limits) to prevent abuse.
*   **Database Backups** Regular snapshots of the MySQL database to guard against data loss.
*   **Caching & Asset Optimization** Laravel cache and CDN caching speed up repeated requests. Minified CSS and JS reduce page size.
*   **Adaptive Bitrate Streaming (HLS)** Ensures videos adjust to each user’s internet speed, preventing buffering issues.

## 6. Conclusion and Overall Tech Stack Summary

We chose this combination of technologies to meet three main goals:

1.  **User-Friendly Experience** Clean frontend tooling (Blade, Laravel Mix) and a fast CDN ensure smooth browsing and playback.
2.  **Reliable & Secure Operations** Laravel’s robust framework, SSL encryption, and secure payment gateways protect user data and transactions.
3.  **Scalable Growth** Cloud storage (AWS S3/DigitalOcean Spaces), a CDN, and CI/CD pipelines prepare the platform for future traffic spikes and feature additions.

Unique strengths of this setup:

*   Automatic video transcoding to HLS for any device.
*   Split payments handled seamlessly by Stripe Connect.
*   Clear separation of roles (User, Producer, Admin) with tailored dashboards.
*   A foundation ready for future public APIs or mobile app support.

With these technologies in place, the platform is ready to support producers, delight users, and grow predictably.
