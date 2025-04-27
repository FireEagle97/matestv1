# Project Requirements Document (PRD)

## 1. Project Overview

**Streamit → matesTv** is a Laravel-based web application that lets independent producers upload, sell, and stream movies to registered users. Producers register, verify their identity, upload movie files (video, audio, subtitles), submit ownership documents, set prices, and track sales. Users browse, purchase, and stream movies on demand. Administrators (matesTv) oversee sign-ups, approve content, process payouts, and manage featured promotions.

This upgrade modifies the existing Streamit app to:

*   Introduce a split-payment flow (Stripe Connect + PayPal) with a 15% platform commission and $50 payout threshold
*   Add flat-fee “Featured Movies” placements
*   Enhance analytics dashboards (view counts, sales charts, geo-metrics, exportable CSVs)
*   Implement cloud storage (Digital Ocean Spaces + CDN) and automatic HLS transcoding
*   Provide clear brand guidelines, placeholder legal pages, and consistent email templates

**Key Objectives / Success Criteria**

*   Producers can easily onboard, upload content, and receive timely payments
*   Users enjoy seamless browsing, checkout, and uninterrupted streaming
*   Admins manage the ecosystem efficiently with minimal manual overhead
*   Platform scales globally with fast video delivery, adaptive streaming, and robust security

## 2. In-Scope vs. Out-of-Scope

### In-Scope (Version 1)

*   Producer module: registration, profile, video upload, metadata entry, proof-of-ownership upload, earnings dashboard, payout requests
*   User module: registration, profile, movie browsing, purchase flow, personal library
*   Admin panel: approve/reject producers & movies, payment request processing, sales counter reset, featured movies management
*   Cloud storage on DigitalOcean Spaces + CDN (DigitalOcean CDN)
*   Automatic HLS transcoding via FFmpeg
*   Payment integrations: Stripe, Stripe Connect (15% commission, $50 threshold), PayPal
*   Email notifications: account verification, password reset, movie approval/rejection, purchase receipts, payment status
*   Placeholder pages for Producers Terms and Users Terms
*   Analytics: time-series views, sales charts, revenue by movie, geo-location metrics, CSV export

### Out-of-Scope (Later Phases)

*   Public REST API or mobile app support
*   Third-party automated document verification
*   Additional user roles (moderator, content reviewer)
*   Advanced DRM or digital watermarking
*   Real-time chat or social features

## 3. User Flow

**Producer Journey (Paragraph 1)**\
A new producer lands on the sign-up page, provides email, password, security questions, and agrees to the Producers Terms. They verify their email via a confirmation link, then complete profile setup by adding name, bio, contact details, profile picture, and banner. Next, they configure payment settings—choosing Stripe or PayPal and entering account details. Once verified, they upload a movie: dragging video, audio, and subtitle files triggers HLS transcoding in the background. They fill in metadata (title, description, genre, price), attach proof-of-ownership docs, then submit for admin review. Status updates (Pending, Approved, Rejected) and earnings analytics appear on their Dashboard. When earnings exceed $50, they click “Request Payout,” which triggers a Stripe Connect split payment.

**User & Admin Journey (Paragraph 2)**\
A new user registers similarly—email, password, security questions, and agrees to the Users Terms—then verifies their email and sets up a profile picture. They browse the movie catalog (including a “Featured” carousel), filter by genre, preview details, and click “Buy.” The integrated checkout (Stripe or PayPal) completes the transaction; a receipt email is sent automatically. Purchased titles appear in “My Library” and stream via HLS even if later taken down by a producer.\
Admins log into the secure Admin Panel to review pending producer and movie applications. They manually inspect proof documents, approve or reject content, and send automated notifications. In the Payment Management section, they view payout requests, process them manually or via gateway, reset counters, and notify producers. They also manage the Featured Movies module by setting flat fees, adding/removing content, and debiting producer accounts.

## 4. Core Features

*   **Authentication & Authorization**\
    • Producer, User, Admin roles with distinct dashboards\
    • Email verification, password reset, security questions
*   **Producer Features**\
    • Account signup & profile management\
    • Payment settings (Stripe, Stripe Connect, PayPal)\
    • Movie upload: video/audio/subtitle files + auto HLS transcoding\
    • Metadata entry & pricing, proof-of-ownership upload\
    • Dashboard: status labels, view counts, sales charts, earnings\
    • Payout requests with $50 minimum threshold & 15% platform commission
*   **User Features**\
    • Account signup & profile management\
    • Browse & filter catalogue, movie detail pages\
    • Purchase flow (Stripe/PayPal), email receipts\
    • Secure personal library, on-demand HLS streaming
*   **Admin Features**\
    • Approve/reject producer registrations & movie uploads\
    • Inspect metadata and proof documents manually\
    • Manage payments: view requests, process payouts, reset counters\
    • Featured Movies: set flat fees, add/remove content, manage durations\
    • Trigger branded email notifications\
    • View analytics: geo-metrics, revenue breakdowns, export CSV
*   **General**\
    • DigitalOcean Spaces + CDN (DigitalOcean CDN) for file storage & CDN delivery\
    • FFmpeg for HLS transcoding (adaptive bitrate)\
    • Placeholder terms pages for legal text\
    • Branded HTML email templates

## 5. Tech Stack & Tools

*   **Backend**: PHP 8.x, Laravel 9.x
*   **Database**: MySQL
*   **Storage & CDN**: DigitalOcean Spaces + CDN (DigitalOcean CDN)
*   **Transcoding**: FFmpeg for HLS adaptive streaming
*   **Payments**: Stripe, Stripe Connect, PayPal
*   **Frontend**: Blade templates, HTML5, CSS3, JavaScript, Bootstrap/Tailwind (as preferred)
*   **Build & Tooling**: Node.js, npm, Laravel Mix
*   **Version Control**: Git (GitHub/GitLab)
*   **IDE & AI Coding**: VS Code with Cursor plugin for AI-powered suggestions
*   **Email**: SMTP (SendGrid/Mailgun) with HTML templates

## 6. Non-Functional Requirements

*   **Performance**:\
    • 95th-percentile page load < 1.5s under normal load\
    • Video start-time < 3s via CDN + HLS
*   **Scalability**:\
    • Auto-scale storage on Spaces, CDN caching\
    • Queue jobs for transcoding & email via Laravel Horizon
*   **Security & Compliance**:\
    • HTTPS everywhere (SSL/TLS)\
    • OWASP Top 10 protections (CSRF, XSS, SQLi)\
    • PCI-DSS compliance for payment data (use Stripe/PayPal hosted fields)\
    • GDPR-compliant data handling and privacy policy
*   **Usability & Accessibility**:\
    • Responsive UI for desktop/tablet/mobile breakpoints\
    • WCAG 2.1 AA accessibility standards
*   **Reliability & Monitoring**:\
    • Uptime > 99.5%\
    • Application monitoring (New Relic/Datadog) + error tracking (Sentry)

## 7. Constraints & Assumptions

*   **Constraints**\
    • Must build on existing Laravel codebase (Streamit)\
    • No third-party document verification beyond file checks\
    • Manual admin review for approvals\
    • Placeholder legal text until finalized
*   **Assumptions**\
    • DigitalOcean Spaces + CDN (DigitalOcean CDN) accounts available\
    • Stripe Connect and PayPal accounts set up with required credentials\
    • Producers and users will provide valid email addresses\
    • Future need for mobile or API support is not immediate

## 8. Known Issues & Potential Pitfalls

*   **Transcoding Delays**: Large files may take time to convert—use background queues and notify producers when complete.
*   **Upload Failures**: Network interruptions—implement resumable uploads (e.g., S3 multipart).
*   **Payment Disputes**: Chargebacks—log all transactions and implement retry/fallback for failed payouts.
*   **Admin Overload**: Manual approvals can bottleneck—consider adding bulk-action tools or workflow statuses.
*   **Email Deliverability**: Spam folder issues—use verified domains, DKIM/SPF records, and monitor bounce rates.
*   **Scalability**: Unexpected traffic spikes—set up auto-scaling rules for worker instances and CDN caching policies.

This PRD provides clear, unambiguous guidance on scope, features, flows, and technical choices. It ensures any subsequent document—tech stack specs, frontend guidelines, backend structure, or implementation plan—can be developed without guesswork.
