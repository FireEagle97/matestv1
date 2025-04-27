# Security Guidelines for matesTv (Streamit) Laravel Application

This document defines security controls and best practices for the matesTv video-streaming platform built on Laravel. It aligns with the core principles of Security by Design, Least Privilege, Defense in Depth, and Secure Defaults.

## 1. Threat Model & Objectives

### 1.1 Primary Assets

*   **User & Producer Accounts** (credentials, PII)
*   **Video & Ownership Documents** (copyrighted media, legal proofs)
*   **Payment Data & Transactions** (Stripe/PayPal integration)
*   **Analytics & Financial Reports**

### 1.2 Key Threats

*   Unauthorized access (account takeover, privilege escalation)
*   SQL/NoSQL injection, XSS, CSRF
*   File‐upload abuse (malware, path traversal)
*   CDN/storage misconfiguration exposing private media
*   Sensitive-data leakage (PII, secrets, logs)
*   Payment fraud or data interception
*   Denial-of-Service or brute-force attacks

### 1.3 Security Objectives

*   Enforce strong authentication & RBAC
*   Validate & sanitize all inputs
*   Harden file‐upload pipeline
*   Encrypt sensitive data in transit & at rest
*   Protect payment workflows under PCI-compliant patterns
*   Apply security headers & rate limiting
*   Maintain secrets via a secure vault
*   Log, monitor, and respond to incidents

## 2. Architecture & Design Principles

*   **Defense in Depth:** Multiple controls at network, application, and data layers.
*   **Least Privilege:** Grant minimal permissions to users, services, and AWS IAM roles.
*   **Secure Defaults:** Use Laravel’s default secure configurations (e.g., CSRF tokens, HTTPS).
*   **Fail Securely:** Error pages do not leak stack traces or internal paths.

## 3. Authentication & Access Control

### 3.1 Authentication

*   Use Laravel’s built-in authentication scaffolding with **verified** email and **password reset** flows.
*   Enforce **strong password policies** (min. 12 characters, complexity) via custom validation rules.
*   Support **2FA** (e.g. Google Authenticator) for producers/admins.
*   Generate **secure, random session identifiers**; store sessions in Redis or database.
*   Configure `SESSION_SECURE_COOKIE=true`, `SESSION_HTTP_ONLY=true`, `SESSION_SAME_SITE=strict`.

### 3.2 Authorization & RBAC

*   Define explicit roles: **User**, **Producer**, **Admin**.
*   Implement Laravel **Policies** and **Gates** for resource-level checks (movies, payments).
*   Validate role membership on every sensitive endpoint (server-side).
*   Separate dashboards & route groups with middleware (`auth`, `role:producer`, `role:admin`).

## 4. Input Validation & Output Encoding

*   Use **Form Request** classes for all input validation.
*   Apply strict rules (e.g., `email`, `string|max:255`, `mimes:mp4,mkv`, `max:51200`).
*   **Sanitize** rich‐text fields (movie descriptions) with a whitelisting HTML sanitizer (e.g., `bleach`).
*   Escape output in Blade templates using `{{ }}` and avoid `{!! !!}` unless sanitized.
*   Implement **Content Security Policy (CSP)** header to mitigate XSS.

## 5. File Upload & Media Processing

### 5.1 File Upload Controls

*   Validate file type, size, and MIME using Laravel’s validation rules.
*   Store uploads in **private S3 buckets** or outside the webroot; generate **time-limited** presigned URLs for access.
*   Rename files to random UUIDs; never trust client‐supplied filenames.
*   Scan uploads with antivirus (ClamAV or third-party API) before processing.

### 5.2 Transcoding & Delivery

*   Queue uploads for **FFmpeg** transcoding jobs via Laravel Queues/Horizon.
*   Sanitize all FFmpeg command parameters to prevent command injection.
*   Produce HLS segments stored on S3; expose through CloudFront with signed URLs.
*   Enforce HTTPS on CDN endpoints and restrict CORS to your domains.

## 6. Data Protection & Privacy

*   Enforce **TLS 1.2+** (HTTPS) for all requests; redirect HTTP to HTTPS.
*   Enable **database encryption** for highly sensitive fields (e.g., legal docs metadata).
*   Hash passwords with **Argon2** (Laravel default).
*   Never store raw payment card data; rely on Stripe/PayPal tokens.
*   Mask or truncate PII in logs; configure **Monolog** to remove sensitive context.
*   Comply with **GDPR/CCPA**: provide data‐export and deletion endpoints.

## 7. API & Service Security

*   Version all API routes (`/api/v1/...`) and require token‐based authentication (Laravel Sanctum or Passport).
*   Apply **rate limiting** on sensitive endpoints (login, registration, uploads) via Laravel’s `ThrottleRequests` middleware.
*   Validate **CORS** policies restrictively (allow only your frontend origin).
*   Ensure proper HTTP verbs: GET (read), POST (create), PUT/PATCH (update), DELETE (remove).

## 8. Web Application Hygiene

*   **CSRF Protection:** Laravel ships with CSRF tokens for all state-changing forms.

*   **Security Headers:** Set via middleware or web server:

    *   `Strict-Transport-Security: max-age=31536000; includeSubDomains`
    *   `X-Content-Type-Options: nosniff`
    *   `X-Frame-Options: DENY`
    *   `Referrer-Policy: no-referrer-when-downgrade`
    *   `Content-Security-Policy` (tighten script and style sources)

*   Secure cookies with `HttpOnly`, `Secure`, `SameSite=strict`.

## 9. Infrastructure & Configuration Management

*   **Environment Segregation:** Separate configs for dev, staging, and production.
*   Store secrets (DB credentials, AWS keys) in environment variables or a secrets manager (AWS Secrets Manager / Vault).
*   Use **IAM Roles** for EC2/Lambda to access S3 instead of long-lived keys.
*   Harden servers: disable unused services, apply security patches regularly.
*   Disable debug mode (`APP_DEBUG=false`) in production.

## 10. Dependency & Code Quality

*   Track versions with `composer.lock` and `package-lock.json` / `yarn.lock`.
*   Run automated **SCA** scans (e.g., Dependabot, Snyk) to catch vulnerable packages.
*   Integrate **static analysis** (PHPStan/Psalm) and **linting** (PHP CS Fixer, ESLint) in CI/CD.

## 11. DevOps & CI/CD Security

*   Enforce branch protection rules and code reviews for all merges.
*   Use pipeline secrets securely (GitHub Actions Secrets / GitLab CI Variables).
*   Scan pull requests for secret leaks (TruffleHog).
*   Automate deployments with minimal credentials (e.g., deploy user with scoped permissions).
*   Run end-to-end security tests (ZAP, OWASP Dependency-Check) pre-deployment.

## 12. Monitoring, Logging & Incident Response

*   Collect application logs centrally (Datadog / ELK Stack) and monitor for anomalies.
*   Implement real-time alerting for failed logins, high error rates, and suspicious file uploads.
*   Maintain an **Incident Response Plan**: define roles, communication channels, and recovery procedures.
*   Periodically perform **penetration tests** and **security audits**.

## 13. Ongoing Governance

*   Conduct **quarterly security reviews** and update dependencies.
*   Refresh cryptographic keys and rotate secrets every 90 days.
*   Keep documentation and runbooks up to date.

By adhering to these guidelines, matesTv will maintain a strong security posture, safeguard user data, and ensure safe, reliable streaming and payment experiences for all stakeholders.
