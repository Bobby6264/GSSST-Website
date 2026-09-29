# Project Progress

## Purpose
To build a fully editable, Dockerized WordPress landing page that exactly matches the provided UI designs without using hardcoded content.

## Completed Tasks
- [x] Configured `docker-compose.yml` with WordPress, MySQL, and Redis containers.
- [x] Connected Redis Object Cache for performance optimization.
- [x] Built a clean, custom WordPress theme (`cic-theme`) from scratch.
- [x] Replicated the Desktop and Mobile UI designs using responsive CSS.
- [x] Integrated WP Customizer to make all homepage text, links, and sections editable.
- [x] Registered Custom Post Types for managing dynamic "Notices" and "News/Events".
- [x] Configured standard WP Menus for dynamic header and footer navigation.
- [x] Automated initial WordPress and plugin setup via WP-CLI.
- [x] Built the Faculty Directory page matching provided design with responsive 4-column cards grid.
- [x] Integrated role-based faculty system with custom user profile fields (designation, phone, office, photo).
- [x] Added image poster header banner as the standard non-editable page layout.
- [x] Linked top navigation dropdown faculty items to /faculty/.
- [x] Removed redundant blue sub-navigation bar markup, theme menu location, styles, and scripts.
- [x] Implemented individual faculty profile page (/faculty/?member=<id>) showing detailed bio, research, and contact.
- [x] Ensured zero external internet images by using database-stored photos and self-contained academic avatars.
