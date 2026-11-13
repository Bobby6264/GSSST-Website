# GSSST WordPress website

Custom WordPress theme for the G.S. Sanyal School of Telecommunication. Run locally with `docker compose up -d`, then open http://localhost:8000. Theme files are mounted into the WordPress container.

## Editing content

- **Landing Page:** hero, news/events, about and head message, department cards, academic resources, gallery, header/footer, and department social links.
- **Landing Page → Explore Department:** edit the section heading/introduction and add, remove or reorder the Research, Faculty, Labs and Alumni cards, including their icons, descriptions and links. Cards fill four desktop columns, two tablet columns and one phone column. Short News/Events lists shrink to their content instead of keeping an empty fixed-height panel.
- **Users → faculty user → Personal Info:** portrait, cover image, and social links. Choose a platform for a default icon or use Custom with an uploaded image symbol.
- **Users → faculty user → Other Details:** academic details and an official IIT Kharagpur profile URL to fetch research information. Only administrators can change faculty designations.
- **Projects:** publish department project records with current/completed status, investigator, sponsor, code, and related link.
- **Courses:** publish current courses or archive older ones, with faculty, code, description, and related link.
- **Research:** department project records and projects imported into faculty profiles appear once at `/research/#projects`, with normalized duplicate titles combined. Academics is a menu heading, and its child pages hold the programme and resource content. The old `/academics/` page redirects to the homepage academic links.
- **Jobs:** administrators manage vacancy details, categories, status, deadline, excerpt and full advertisement text. **Pages → Jobs** controls the introduction. The examples are labelled and do not accept applications. Open listings close automatically after their deadline; an Apply button appears only for an open listing with an application URL.
- **Pages → programme pages:** edit all M.Tech, Joint M.Tech/PhD, PhD and M.S. by Research text, section headings and reference links.
- **Pages → Academic Curriculum / Timetable & Exam Schedules / Academic Calendar:** edit the local sample headings, section buttons and tables directly in the WordPress block editor. These pages include table search; their sample courses, credits and dates are illustrative. Programme pages also contain editable sample requirements and local resource links. The local-content migration is `scripts/localize-academic-pages.php`; it replaces the earlier institute-linked resources and preserves the old programme copy in revisions/post metadata.
- **Alumni / Staff & Administration / Laboratories & Facilities / Research Areas:** administrator-managed directory records. Photos use Featured image. Alumni categories cover former faculty, students and staff. Research topics show faculty counts; selecting a topic lists the matching faculty profiles. Leave the faculty selection empty for automatic matching to saved research interests, or select faculty explicitly.

The Academics dropdown links directly to programme pages, curriculum, timetable and Jobs. Homepage/footer programme links point to `/#academics`; project links point to `/research/#projects`. No combined Academics overview is displayed. Existing custom destinations are preserved. `scripts/distribute-academics-and-jobs.php` applies this structure once through Docker, and `scripts/check-academic-distribution.php` verifies Jobs permissions, deadlines and navigation.

The approved November 2026 structure uses About Us, People, Academics, Research and Internal. Individual programme destinations, Alumni, Staff and Labs are connected through the existing header settings. [The comparison and decisions](docs/content-review-2026-11-04.md) document every IIT Bombay reference page reviewed and the remaining content to supply. The one-time migration is `scripts/apply-content-review.php`; run it only through Docker against the intended WordPress instance. It preserves existing pages and records its completion in the database. `scripts/check-content-review.php` checks permissions, sanitized saves and the configured pages using disposable draft records.

## Maintenance

Read [the project skill](skills/gssst-wordpress/SKILL.md) before theme changes. Future sessions discover it through [AGENTS.md](AGENTS.md). Frontend responsive rules load last from `assets/css/responsive.css`; faculty page styles live under `faculty/`.

The landing hero uses the approved 2172 × 724 `assets/images/hero-trees-towers-v3.png` composition with centered cover sizing. Its building, trees and two telecom towers contain no static wave arcs. `landing-page/landing-page.js` projects the image's antenna-tip coordinates into the canvas for animated tower signals and cursor waves, including reduced-motion support. Separate homepage card interactions use a short CSS wave and gentle lift/zoom, disabled for reduced motion and coarse pointers. The old SVG artwork is retained for reference.

WordPress database and uploaded media are stored in Docker volumes. `backups/db_backup_latest.sql` is a database snapshot; it does not contain uploaded media. Preserve Docker volumes when sharing or moving a running site.

To refresh the database backup, export to a temporary file first and replace the snapshot only after a successful export:

```sh
docker compose exec -T db sh -c 'MYSQL_PWD="$MYSQL_PASSWORD" mysqldump --single-transaction --quick --no-tablespaces -u "$MYSQL_USER" "$MYSQL_DATABASE"' > /tmp/gssst-backup.sql
# After checking that the export succeeded:
mv /tmp/gssst-backup.sql backups/db_backup_latest.sql
```

Database snapshots contain WordPress account data. Follow the requested backup and publishing scope when committing them.
