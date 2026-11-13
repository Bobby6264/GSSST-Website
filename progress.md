# GSSST website progress

Updated: 13 November 2026

## Completed updates

- Split the header logos into separate links: GSSST opens the home page; IIT KGP opens https://www.iitkgp.ac.in/department/GS/. The footer IIT KGP logo uses the same department destination.
- Added a subtle zoom-out effect on logo hover, visible keyboard focus, and reduced-motion support.
- Applied a faint blue-white card background (`#f8fbfe`) to About, Head’s Message, News & Events, Explore Department, and academic item cards. Retained dark-mode colors.
- Confirmed the typography: Lora for main headings, Inter for body text, and Plus Jakarta Sans for navigation, buttons, tabs, and news headlines.
- Updated the landing layout and card hover effects, rotating editable hero headings, and News & Events carousel with an in-card archive view.
- Updated responsive layouts and faculty/staff directory presentation.
- Added staff category controls, public search/filter behavior, a source manifest, and an explicit staff import script that preserves existing records and photographs.
- Added a one-time script to promote Jobs to the main navigation while preserving other links.
- Updated department-name wording and the repository WordPress maintenance skill.

## Validation and deployment notes

- Header and footer PHP syntax checks passed in the WordPress Docker container; their rendered logo destinations were verified on the local home page.
- CSS brace checks and Git whitespace checks passed for the logo/card updates. All modified theme PHP files passed Docker syntax checks; landing-page and staff-directory JavaScript passed Node syntax checks.
- Database content is not included in this source-code update. Staff imports and navigation migrations are explicit operations; pushing code does not run them.
- Included all working-tree changes at the user’s request, including local artwork, the staff source manifest, and the reference PDF.
