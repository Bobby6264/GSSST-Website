# GSSST WordPress website

Custom WordPress theme for the G.S. Sanyal School of Telecommunication. Run locally with `docker compose up -d`, then open http://localhost:8000. Theme files are mounted into the WordPress container.

## Editing content

- **Landing Page:** hero, news/events, about and head message, department cards, academic resources, gallery, header/footer, and department social links.
- **Users → faculty user → Personal Info:** portrait, cover image, and social links. Choose a platform for a default icon or use Custom with an uploaded image symbol.
- **Users → faculty user → Other Details:** academic details and an official IIT Kharagpur profile URL to fetch research information. Only administrators can change faculty designations.
- **Projects:** publish department project records with current/completed status, investigator, sponsor, code, and related link.
- **Courses:** publish current courses or archive older ones, with faculty, code, description, and related link.
- **Pages → Academics & Research:** edit the academics introduction. The page displays published project/course records and current projects imported into faculty profiles. Exact normalized duplicate titles count once; totals describe listed records, not the full historical department portfolio. Academic resource links reuse Landing Page → Academics & Resources.

The Academics navbar and its dropdown placeholders are connected to `/academics/` and its sections. Related hero buttons, the academic footer links, and the landing-page academic cards also open the overview. Existing custom destinations are preserved. Admin initialization maintains the page, links and routes.

## Maintenance

Read [the project skill](skills/gssst-wordpress/SKILL.md) before theme changes. Future sessions discover it through [AGENTS.md](AGENTS.md). Frontend responsive rules load last from `assets/css/responsive.css`; faculty page styles live under `faculty/`.

The landing hero uses `assets/images/takashilla-outline.svg` for its white building and tree outlines. Its cropped viewBox fills the dark hero with the building spanning about 75% of its width. Roof and cursor waves are managed in `landing-page/landing-page.js`, including reduced-motion support.

WordPress database and uploaded media are stored in Docker volumes. `backups/db_backup_latest.sql` is a database snapshot; it does not contain uploaded media. Preserve Docker volumes when sharing or moving a running site.

To refresh the database backup, export to a temporary file first and replace the snapshot only after a successful export:

```sh
docker compose exec -T db sh -c 'MYSQL_PWD="$MYSQL_PASSWORD" mysqldump --single-transaction --quick --no-tablespaces -u "$MYSQL_USER" "$MYSQL_DATABASE"' > /tmp/gssst-backup.sql
# After checking that the export succeeded:
mv /tmp/gssst-backup.sql backups/db_backup_latest.sql
```

Database snapshots contain WordPress account data. Follow the requested backup and publishing scope when committing them.
