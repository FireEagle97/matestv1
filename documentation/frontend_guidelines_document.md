# Frontend Guideline Document

This document outlines the frontend setup for the matesTv platform, a customized version of the Streamit Laravel web app. It covers how we build and organize our user interface, the design principles we follow, and the tools and techniques we use to keep everything scalable, maintainable, and performant.

## 1. Frontend Architecture

**Overview**

*   **Server-Rendered Views**: We use Laravel Blade templates to generate HTML on the server. Each page extends a master layout and includes partials (header, footer, modals) for consistency.

*   **Asset Bundling**: We use **Node.js**, **npm**, and **Laravel Mix** to compile, minify, and version our CSS and JavaScript. This ensures assets are optimized and cache-busted automatically.

*   **Directory Structure**:

    *   `resources/views/` – Blade templates (`layouts/`, `components/`, `pages/`).
    *   `resources/js/` – JavaScript modules and entry points.
    *   `resources/sass/` – SCSS files (variables, base, components).
    *   `public/` – Compiled assets (CSS/JS/images).

**Scalability & Maintainability**

*   **Modular Templates**: Breaking pages into reusable Blade components (`<x-navbar>`, `<x-card>`) keeps markup consistent and easy to update.
*   **Versioned Assets**: Laravel Mix adds a hash to filenames, preventing stale files in browsers when we deploy updates.
*   **Clear Folder Layout**: Separating views, styles, and scripts into dedicated folders helps new team members find and update code quickly.

**Performance**

*   **Minified & Bundled Files**: CSS and JS are compressed into a few files to reduce HTTP requests.
*   **CDN Delivery**: We serve videos via AWS S3 + CloudFront (or DigitalOcean Spaces), and could likewise host static assets on a CDN.

## 2. Design Principles

We follow three guiding principles to make matesTv intuitive and inclusive:

1.  **Usability**

    *   Clear navigation menus and calls to action.
    *   Consistent button styles and iconography.
    *   Inline form validation to guide users.

2.  **Accessibility**

    *   Semantic HTML (proper headings, landmarks).
    *   ARIA roles for dynamic components (modals, tabs).
    *   Keyboard-navigable elements and high contrast text.

3.  **Responsiveness**

    *   Mobile-first breakpoints (320px, 480px, 768px, 1024px).
    *   Flexible grids and media queries ensure layouts adapt to phones, tablets, and desktops.

**Applying Principles**

*   Forms (registration, login, upload) use clear labels and focus states.
*   All images (posters, banners) include `alt` text.
*   Interactive elements (buttons, links) have at least a 44×44px tap target on touch devices.

## 3. Styling and Theming

**CSS Methodology**

*   We use **SCSS** (SASS) and follow the **BEM** naming convention (Block–Element–Modifier) for clarity. Example:

`.card { &__title { /* element */ } &--featured { /* modifier */ } }`

*   Global variables and mixins live in `resources/sass/_variables.scss` and `resources/sass/_mixins.scss`.

**Theming & Style**

*   Overall style: **Modern flat design** with subtle glassmorphism for movie cards (light frosted backgrounds, soft shadows).

*   **Color Palette**:

    *   Primary: #E50914 (Vibrant red accent)
    *   Dark Background: #141414
    *   Neutral Gray: #444444 (Text, icons)
    *   Light Gray: #F5F5F5 (Cards, surfaces)
    *   White: #FFFFFF (Text on dark)

*   **Typography**:

    *   Headings: **Montserrat**, sans-serif, bold.
    *   Body: **Open Sans**, sans-serif, regular.
    *   Base font size: 16px; line height: 1.5.

*   **Spacing & Grids**:

    *   Use an 8px scale for margin/padding (8, 16, 24, 32px).
    *   12-column grid for desktop; collapse to single column on mobile.

## 4. Component Structure

**Blade Components & Partials**

*   **Layouts**: `layouts/app.blade.php` contains `<head>`, global scripts, and a `@yield('content')` area.

*   **Shared Components** (in `resources/views/components/`):

    *   `navbar.blade.php`, `footer.blade.php`, `movie-card.blade.php`, `modal.blade.php`.

*   **Usage**:

`<x-navbar :user="Auth::user()" /> <x-movie-card :movie="$movie" featured />`

**Why Components Matter**

*   **Reusability**: One update to `movie-card` applies everywhere we show a movie preview.
*   **Consistency**: All buttons, links, and forms follow the same markup and styling.
*   **Isolation**: Changes in one component don’t accidentally break another part of the UI.

## 5. State Management

Because matesTv is server-rendered, most state lives on the backend and is passed into views.

*   **Server State**: User session, purchase history, and uploaded movies are managed by Laravel and injected into Blade templates.

*   **Client-Side State**:

    *   Simple interactivity (dropdowns, modals) uses vanilla JavaScript modules or optional lightweight libraries (e.g., Alpine.js).
    *   Transient UI state (open/closed modals) can be tracked with data attributes and event listeners.
    *   For persistent UI preferences (dark mode), we store a flag in `localStorage` and apply a CSS class on `<body>`.

This approach keeps complexity low while ensuring pages stay snappy and easy to debug.

## 6. Routing and Navigation

**Server-Side Routing**

*   Defined in `routes/web.php` using Laravel’s route and controller system.
*   Named routes make it easy to link in Blade:

`<a href="{{ route('movies.show', $movie->id) }}">Watch Now</a>`

**Navigation Structure**

*   **Primary Nav**: Links for Home, Browse, My Library, Upload (Producer only), Dashboard (Producer/Admin), Profile.
*   **Breadcrumbs**: Show current page path (e.g., Home / Genre / Movie) for easy back-tracking.

Blade partials ensure the same menu appears on every page, and highlights the active link based on the current route.

## 7. Performance Optimization

1.  **Asset Optimization**

    *   Minify CSS/JS via Laravel Mix.
    *   Split JS into multiple entry points (`app.js`, `upload.js`) so pages only load what they need.

2.  **Lazy Loading**

    *   Use the `loading="lazy"` attribute for images (movie posters) and iframes.
    *   For video previews, load only the poster until the user clicks play.

3.  **Code Splitting & Caching**

    *   Versioned filenames ensure browsers fetch new files after deploy.
    *   Leverage CloudFront (or CDN) caching headers for static assets.

4.  **Optimized Transcoding**

    *   FFmpeg produces HLS streams: small segments (200–400 KB) adapt to network speed.

These measures reduce page load times and ensure smooth playback.

## 8. Testing and Quality Assurance

**Automated Testing**

*   **Laravel Dusk**: End-to-end browser tests simulate user flows (registration, purchase, streaming).
*   **PHPUnit**: Unit tests for any JavaScript-driven helpers via `php artisan test`.

**JavaScript Testing** (optional)

*   **Jest**: For unit testing standalone JS modules (e.g., modal controller).
*   **Cypress**: For additional E2E tests focusing on UI interactions (dark mode toggle, infinite scroll).

**Quality Tools**

*   **ESLint**: Enforce consistent JavaScript style.
*   **Stylelint**: Lint SCSS against our BEM rules.
*   **Accessibility Audit**: Use browser tools (Lighthouse, axe) to catch contrast or ARIA issues.

**Manual QA**

*   Regular cross-browser testing (Chrome, Firefox, Safari) and device checks (iOS, Android).
*   Review after each deploy to staging, focusing on key flows: upload, purchase, playback.

## 9. Conclusion and Overall Frontend Summary

matesTv’s frontend is a server-rendered, Blade-based application enhanced with modern tooling (Laravel Mix, SCSS, JavaScript modules). We rely on clear design principles—usability, accessibility, responsiveness—and a consistent component structure to make the codebase easy to maintain and extend. Performance optimizations (asset minification, lazy loading, CDN delivery) keep the user experience fast, while our testing strategy (Dusk, linting, manual QA) ensures reliability.

By following these guidelines, any developer or designer on the project can quickly understand how to build new features or update existing ones, all in service of a smooth, branded experience for producers and viewers alike.

*End of Frontend Guideline Document*
