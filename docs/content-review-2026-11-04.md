# Department website content review — 4 November 2026

## Latest correction — distribute the overview and add Jobs

The professor requested that the combined Academics overview be removed. Academics and Programmes Offered now act as dropdown headings. Programme descriptions stay on their respective pages, curriculum/timetable/calendar stay on their local resource pages, and the deduplicated department project list moved to `/research/#projects`. The duplicate programme/resource listings and statistics from the overview were removed. The homepage keeps navigation links; the extra link to the former overview was removed. Existing `/academics/` bookmarks redirect to the homepage academic links without deleting the saved WordPress page or records.

The user also added **Academics → Jobs**, superseding the earlier choice to omit hiring. `/jobs/` contains searchable vacancy cards with category/status filters. Each advertisement has a local detail page with editable role description, responsibilities, eligibility, application instructions and required documents. Administrators manage category, reference, appointment, duration, location, salary, deadline, application URL and contact. Statuses are Example, Open, Upcoming and Closed; open listings close after their deadline. Four labelled examples demonstrate faculty, staff, project and postdoctoral roles, with no application actions. The layout follows the information types reviewed on IIT Bombay’s [faculty](https://www.ee.iitb.ac.in/jobs/faculty-hiring/), [staff](https://www.ee.iitb.ac.in/jobs/staff-hiring/) and [postdoctoral](https://www.ee.iitb.ac.in/jobs/post-doc-hiring/) pages; their recruitment criteria were not copied into GSSST.

Migration: `scripts/distribute-academics-and-jobs.php`. Functional verification: `scripts/check-academic-distribution.php`.

## Later user correction — local academic content

The user replaced the earlier choice of institute-hosted academic links with an end-to-end local content requirement. Curriculum, Timetable & Exam Schedules and Academic Calendar now live at `/curriculum/`, `/timetable/` and `/academic-calendar/`. They contain clearly labelled illustrative content in editable WordPress heading, button and table blocks. Programme pages now include sample structure, requirements, thesis and guidance sections and link to those local resources. `scripts/localize-academic-pages.php` applies this change once, preserving the original generated programme text. The earlier comparison below records the initial choices; references to externally hosted academic resources are superseded by this correction.

The research/publication/project count tiles directly below “Research & scholarship” were also removed from individual faculty profiles. The actual research, publication and project content remains.

The IIT Bombay Electrical Engineering website was reviewed through its seven live desktop dropdowns, programme/admissions submenus, destination pages and six research-group pages. This is a content reference; the GSSST theme, header editor, nested custom URLs, authentication and existing faculty pages are retained. The separate local reference at [localhost:8080/research](http://localhost:8080/research/) was also inspected, including a research-area faculty listing.

## Page-by-page comparison and decisions

| Reference page | Information displayed there | GSSST before review | Approved result |
|---|---|---|---|
| About → [Our History](https://www.ee.iitb.ac.in/our-history/) | Timeline, educational/research development, former heads | History & Vision placeholder | Remove history link; use Alumni for former faculty, students and staff |
| About → [Life @ EE](https://www.ee.iitb.ac.in/course/~eesa/) | Student association introduction and activity photographs | No page | Leave out |
| [Head’s Message](https://www.ee.iitb.ac.in/heads-message/) (homepage link) | Full welcome message and head’s profile link | Message already on homepage, navbar anchor absent | Keep homepage message and repair section link |
| People → [Faculty](https://www.ee.iitb.ac.in/people/faculty/) | Portrait, name, research interests, profile link | Working faculty directory and profiles | Preserve |
| People → [Emeritus Faculty](https://www.ee.iitb.ac.in/people/emeritus/) | Former faculty profiles and research summaries | No page | Former Faculty category within Alumni |
| People → [Staff](https://www.ee.iitb.ac.in/people/staff/) | Office information, grouped staff, photos and official contacts | Placeholder | Staff & Administration directory |
| Research → [Areas](https://www.ee.iitb.ac.in/research/) | Research groups with summaries and links | Placeholder | One Research page, topic links with faculty counts and selected-topic faculty cards |
| Research → [Communication & Signal Processing](https://www.ee.iitb.ac.in/research/ee1/) | Themes, overview, labs, projects and associated faculty | Faculty research interests already saved | Use GSSST’s own topics and faculty; no copied IITB specialisations |
| Research → [Control & Computing](https://www.ee.iitb.ac.in/research/ee2/) | Group overview, related labs/projects/faculty | No group page | Same single-page Research structure |
| Research → [Power Electronics & Power Systems](https://www.ee.iitb.ac.in/research/ee3/) | Group overview and related research resources | No group page | Same single-page Research structure |
| Research → [Electronic Systems](https://www.ee.iitb.ac.in/research/ee5/) | Overview and linked research infrastructure | No group page | Same single-page Research structure |
| Research → [Integrated Circuits & Systems](https://www.ee.iitb.ac.in/research/ee6/) | Overview, labs and faculty | No group page | Same single-page Research structure |
| Research → [Solid State Devices](https://www.ee.iitb.ac.in/research/ee7/) | Overview, labs and faculty | No group page | Same single-page Research structure |
| Research → [Labs](https://www.ee.iitb.ac.in/research/labs/) | Laboratories grouped by research area, lab links | Placeholder | Admin-editable Labs & Facilities directory |
| Research → [Projects](https://www.ee.iitb.ac.in/research/projects/) | Project summaries, sponsors and associated faculty | Existing Academics project overview combines saved projects and faculty ERP projects | Reuse existing overview |
| Academics → Programs → [B.Tech](https://www.ee.iitb.ac.in/programs/btech/) | Overview, rules, support, internships, exchange, batch curricula | No approved GSSST undergraduate offering | Do not introduce a B.Tech page |
| Academics → Programs → [Dual Degree](https://www.ee.iitb.ac.in/programs/dual-degree/) | B.Tech–M.Tech specialisations, thesis, support, curricula | GSSST navigation instead lists Joint M.Tech/PhD | Create the GSSST joint programme page; do not copy the different IITB degree |
| Academics → Programs → [M.Tech](https://www.ee.iitb.ac.in/programs/mtech/) | Overview, specialisations, thesis, support, fees links, curriculum | All programme links opened the same overview | Individual editable M.Tech page with official curriculum/reference links |
| Academics → Programs → [PhD](https://www.ee.iitb.ac.in/programs/phd/) | Research specialisations, coursework/thesis requirements, guidance, policies | Generic overview link | Individual editable PhD page |
| Academics → Programs → [ePGD](https://www.ee.iitb.ac.in/~epgd/) | Professional diploma offerings and detail links | No approved equivalent | Leave out |
| Academics → [Courses](https://www.ee.iitb.ac.in/academics/courses/) | Searchable catalogue, level filters, codes and descriptions | Course post type exists, no published course records | Use official institute links and files; remove empty public course listing, preserve saved course infrastructure |
| Academics → [Curriculum](https://www.ee.iitb.ac.in/programs/curriculum/) | Programme/batch/specialisation course structures | Generic resource link | Official IIT Kharagpur PG curriculum link; admin can replace links or attach uploaded file URLs |
| Academics → Admissions → [Undergraduation](https://www.ee.iitb.ac.in/admissions/undergraduation/) | Entry requirements, programme categories, application references | No page | Leave Admissions out |
| Academics → Admissions → [Postgraduation](https://www.ee.iitb.ac.in/admissions/postgraduation/) | Eligibility/categories, specialisations, application process | Footer placeholder | Leave Admissions out; remove footer link |
| Academics → Admissions → [PhD](https://www.ee.iitb.ac.in/admissions/phd/) | Categories, research areas, application and selection information | No page | Leave Admissions out |
| [Timetable](https://www.ee.iitb.ac.in/academics/timetable/) (homepage link) | Course/exam schedule views, search, selection controls | Placeholder resource link | Official ERP link, with curriculum and calendar resources |
| Jobs → [Faculty Hiring](https://www.ee.iitb.ac.in/jobs/faculty-hiring/) | Position types, application routes, recruitment contacts | Unconnected career card | Remove career card; no Jobs section |
| Jobs → [Staff Hiring](https://www.ee.iitb.ac.in/jobs/staff-hiring/) | Vacancy state and institute careers link | No page | Leave out |
| Jobs → [Post-Doc Hiring](https://www.ee.iitb.ac.in/jobs/post-doc-hiring/) | Faculty host and institute application guidance | No page | Leave out |
| Engage → [Alumni](https://www.ee.iitb.ac.in/engage/alumni/) | Ways to stay involved and contact details | Notable Alumni placeholder | A directory, as requested: Former Faculty / Former Students / Former Staff; photo, programme/role, years, affiliation, optional profile link |
| Engage → [Contribute](https://www.ee.iitb.ac.in/engage/contribute/) | Funding opportunities, past contributions, contact | No page | Leave out |
| Engage → [Collaborate](https://www.ee.iitb.ac.in/engage/collaborate/) | Collaboration process, faculty matching, institutional contact | No page | Leave out |
| Engage → [Consult](https://www.ee.iitb.ac.in/engage/consult/) | Consulting process and expert contact route | No page | Leave out |
| Engage → [Seek Training](https://www.ee.iitb.ac.in/engage/seek-training/) | Workshops, training and internship enquiries | No page | Leave out |
| Internal → [EE Webmail](https://www.ee.iitb.ac.in/webmail/) | Department email login | Working department Login page | Keep Login only |
| Internal → [IITB Webmail](https://webmail.iitb.ac.in/) | Institute email access/help | No link | Leave out of Internal |
| Internal → Room Booking | Destination could not be fetched; linked from the live dropdown | No room-booking system | Leave out; protected application content was not inspected |
| Internal → [PhD Committee Portal](https://www.ee.iitb.ac.in/phdadmission2026_committee/) | Faculty-only sign-in entry for admission review | No equivalent | Leave out; no sign-in attempted |

## Current navigation and homepage

Top level: **About Us · People · Academics · Research · Internal**. The logo opens Home. IIT KGP remains in the footer. The existing admin editor still controls headings, nested links, order and custom destinations.

- About Us: Head’s Message and Our Department, both using valid homepage anchors.
- People: Faculty Members, Staff & Administration, Alumni.
- Academics: programme overview with individual M.Tech, Joint M.Tech/PhD and PhD destinations; curriculum and timetable use institute resources. Existing M.S. by Research landing link now has its own editable page.
- Research: Research Areas, Laboratories & Facilities, Funded Projects. Publications remain within faculty profiles.
- Internal: Login.
- Removed as requested: Home menu item, IIT KGP menu item, Awards menu/card, career card, History & Vision link, department-level Publications link and Admissions footer link.
- News, Events, bottom gallery, faculty accounts and published articles are preserved.
- Homepage cards now have a small lift/zoom and one passing wave on pointer hover or keyboard focus. The wave does not intercept clicks. Reduced-motion and coarse-pointer settings suppress movement.

## Editing and content still needed

Use **Pages** for programme copy and directory introductions. Use **Alumni**, **Staff & Administration**, **Laboratories & Facilities**, and **Research Areas** in the WordPress sidebar for their records. These new record editors require administrator capabilities. Existing faculty self-edit permissions are unchanged.

Research topics were seeded from the saved faculty research interests. Empty faculty selection matches current faculty interests by topic name; explicit selections let the administrator override membership. Counts describe this site's records, so they can differ from the separate port-8080 site. Similar topic labels are not automatically merged.

Alumni and Staff directories have no invented people. Add their actual records and photographs in WordPress. Four lab names were verified on [IIT Kharagpur’s GSSST page](https://www.iitkgp.ac.in/department/GS); equipment, faculty-in-charge and lab URLs remain for the administrator to supply.

Programme pages contain starter copy and official references, with all text editable. Detailed duration, credit, thesis, funding and contact information should be completed from current institute-approved material. The [official programme listing](https://gateoffice.iitkgp.ac.in/jmp/d/?p=108) confirms Wireless Communications and Networks (GS1). Use the [PG curriculum page](https://www.iitkgp.ac.in/curricula-pg), [academic calendar](https://www.iitkgp.ac.in/academic-calendar-ug) and [ERP](https://erp.iitkgp.ac.in/) for maintained academic information. No IIT Bombay regulations, deadlines or numerical programme requirements were copied into GSSST.

The one-time migration is `scripts/apply-content-review.php`; it preserves existing pages and custom URLs, records completion in WordPress options, and does not reapply changes during normal page loads. Docker volumes hold the new content; theme files alone do not contain database records.

## Validation

PHP syntax, JavaScript syntax, directory capability/nonce/sanitization checks, programme/page existence and research-to-faculty matching were checked. Disposable draft records used by `scripts/check-content-review.php` are removed after the checks. Existing Redis constant warnings in the local WordPress configuration are outside this change.

Home, Research, Alumni, Staff, Labs and M.Tech were checked in actual browser frames at 360, 390, 768 and 1440 pixels; no horizontal overflow was detected. Topic grids use one, two and three columns at those breakpoints. The expanded 360-pixel menu and nested programme links were checked. First tap now expands a mobile submenu even when its heading has a valid URL; a second tap on the label follows that URL. The homepage card interaction was verified to apply a 1.012 scale, 3-pixel lift, `landingCardWave` animation and a non-interactive overlay. Screenshots and viewport measurements are in `artifacts/content-review/`.

The reference review covers publicly readable page content. It does not claim access to protected mail, booking or admission committee data, or to every linked external application and historical curriculum document.
