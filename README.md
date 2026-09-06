Build a WordPress website fully inside Docker.

Requirements:
- Docker + Docker Compose
- WordPress
- PHP
- MySQL
- Redis (properly integrated with WordPress)

Initially, the website should contain only the landing page based EXACTLY on the provided reference images:
- img1 = Desktop design
- img2 = Mobile design

Reproduce the design, layout, responsiveness, colors, fonts, sections, navbar, hero, notices, buttons, cards, footer, etc. as closely as possible.

The entire landing page must be manageable from WordPress Admin.

Admin should be able to:
- Add/edit/delete notices and banners
- Add/edit/delete/reorder navbar items
- Choose which sections appear or are hidden
- Edit text, images, buttons and links
- Change where each button/navbar item links
- Add/edit/delete landing-page content without touching code

Also, the system should be designed so the Admin can create different user types/roles later and assign specific permissions/access to them.

Use a clean custom WordPress theme/plugin architecture. Do not hardcode content that the admin should be able to manage.

Everything must run through Docker.