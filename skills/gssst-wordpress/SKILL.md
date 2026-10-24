---
name: gssst-wordpress
description: Maintain this GSSST WordPress theme, its admin-editable academic records, faculty profiles, social links, and responsive layouts.
---

Use the existing WordPress theme at `wp-content/themes/cic-theme`; this project runs through Docker Compose on port 8000.

- `inc/admin/admin-options.php` and `admin-views.php` own landing settings; the Customizer is not a content source.
- The landing hero uses `assets/images/takashilla-outline.svg`, a clean white architectural drawing based on `landingPage.jpeg`, over a dark background. Its cropped `150 70 1300 640` viewBox stretches to fill the hero, with the building spanning 75% of the width and trees at the sides. Keep the roof wave coordinates in `landing-page/landing-page.js` aligned with that viewBox. Preserve at least three roof sources, cursor-originating waves, reduced-motion support, and editable hero copy.
- Faculty are WordPress users with the faculty role. Their public profiles resolve at `/faculty/?member=ID`. Profile controls live in `inc/faculty.php` and `profile-tabs.js`.
- Designations require the administrator role plus manage_options. Faculty may edit their own cover, photo, social links, and academic information, but must not gain designation editing through a new endpoint.
- ERP research data is imported on demand, never on a public page render. Keep fetch failures from destroying saved records. The official faculty slug can contain dots. Reuse `inc/erp-profile.php`.
- Projects and courses are `cic_project` and `cic_course` posts. `inc/academics.php` combines published project records with deduplicated faculty ERP projects. Counts describe listed records, not inferred historical totals. Academic navigation includes nested dropdowns, landing cards, hero buttons and footer links; connect placeholder destinations through `cic_academic_link` without replacing custom URLs.
- Reuse `inc/social-links.php` for department and faculty social editors. Default platform icons use a fixed mapping; custom symbols are image attachments. Never accept raw SVG or icon HTML from profile fields.
- The faculty stylesheet has base rules followed by directory and personal-profile rules. Edit the responsible rule rather than appending competing overrides. `assets/css/responsive.css` loads last for shared breakpoints and article layouts.
- Directory cards follow the supplied faculty reference: inset portrait photos in 4:5 frames, filled with cover and top alignment to show more of each face and shoulders, centered sans-serif names, three-line research summaries, and aligned outlined profile buttons. Contact details belong on the individual profile. Keep five columns on wide desktops and reduce columns at smaller widths. Check 360, 390, 768, and desktop widths, including open mobile navigation and long names.
- Personal profiles use a wide three-column layout with a section rail, readable research content and a 4:5 portrait/contact card. Build navigation and summary counts only from available records; omit empty sections. Collapse the rail and stack the contact/content layout on tablets and phones.
- Preserve published content and user accounts during cleanup. Remove demo generation and unused callers rather than deleting user data. Keep third-party plugin code separate from custom theme maintenance.

Validate PHP using the running WordPress container and JavaScript with Node. For functional changes, check the public page and the corresponding administrator controls. Database backups contain account data; follow the user's actual backup/publishing authorization and any approval review result.
