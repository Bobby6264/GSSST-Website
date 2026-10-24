# Current project state

- Dockerized WordPress, MySQL and Redis; custom `cic-theme`.
- Landing sections managed through custom WordPress settings.
- Dark landing hero with a white Takashilla architectural outline, side trees, three roof wave sources, and cursor/click waves. Building spans approximately 75% of the hero width; reduced-motion preferences are respected.
- Faculty directory with compact cards, filled 4:5 portrait frames, search and designation filters.
- Faculty profiles with per-user cover, portrait, social links and imported ERP research sections.
- Administrator-controlled faculty designations and single Head of School assignment.
- Shared social editor with platform icons and optional custom image symbols.
- Academics overview with published projects, current/completed counts, current courses and resource links.
- Shared responsive stylesheet for navigation, landing sections, faculty profiles, articles and academics.
- Removed demo faculty generation, fabricated empty-state news/events, unused Customizer settings and obsolete patch utility. Published database content is preserved.
- Repository-local maintenance skill linked through AGENTS.md.

Database snapshot refreshed on 24 October 2026 from the running WordPress database (12 tables). Theme PHP and JavaScript syntax checks passed before preparing the GitHub update requested by the user. The SQL snapshot does not include uploaded media from the Docker volume.

See README.md for editing locations, backup commands, and data-source rules.
