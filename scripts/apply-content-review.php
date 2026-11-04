<?php
/** Apply the choices approved in the November 2026 content review, once.
 * Run through Docker: docker compose exec -T wordpress php < scripts/apply-content-review.php
 */
if (PHP_SAPI !== 'cli') exit;
require '/var/www/html/wp-load.php';
// Names verified on IIT Kharagpur's GSSST page; equipment and contacts await the administrator.
if (!get_option('cic_review_labs_seeded_20261104')) {
    $labs = get_posts(array('post_type' => 'cic_lab', 'post_status' => array('publish', 'draft', 'pending', 'private'), 'numberposts' => -1));
    $names = array_map(function ($lab) { return strtolower(trim($lab->post_title)); }, $labs);
    foreach (array('Hardware Laboratory', 'MSPC Lab', 'Network Lab', 'Project Lab') as $name) {
        if (in_array(strtolower($name), $names, true)) continue;
        $id = wp_insert_post(array('post_type' => 'cic_lab', 'post_status' => 'publish', 'post_title' => $name, 'post_content' => '<p>Listed on <a href="https://www.iitkgp.ac.in/department/GS">IIT Kharagpur’s GSSST page</a>.</p>'), true);
        if (is_wp_error($id)) throw new RuntimeException($id->get_error_message());
    }
    update_option('cic_review_labs_seeded_20261104', 1);
}
if (get_option('cic_content_review_20261104')) { echo "Content review already applied.\n"; exit; }

function cic_review_page($slug, $title, $content, $template, $type = '') {
    $page = get_page_by_path($slug);
    if ($page) return $page->ID; // Preserve any existing page, content and publication state.
    $id = wp_insert_post(array('post_type' => 'page', 'post_status' => 'publish', 'post_name' => $slug, 'post_title' => $title, 'post_content' => $content), true);
    if (is_wp_error($id)) throw new RuntimeException($id->get_error_message());
    update_post_meta($id, '_wp_page_template', $template);
    if ($type) update_post_meta($id, 'cic_directory_type', $type);
    echo "Created page: $slug\n";
    return $id;
}

foreach (cic_directory_types() as $type => $config) {
    $intro = array(
        'cic_alumni' => 'The GSSST community brings together former faculty, students and staff. Explore the people who have been part of the School.',
        'cic_staff' => 'Meet the staff supporting the academic and administrative work of the School.',
        'cic_lab' => 'Explore the laboratories and facilities supporting teaching and research at the School.',
        'cic_research_area' => 'Explore the research interests of our faculty, from communication systems and signal processing to networks and emerging wireless technologies.',
    );
    cic_review_page($config['slug'], $type === 'cic_research_area' ? 'Research' : $config['label'], '<p>' . $intro[$type] . '</p>', 'page-department-directory.php', $type);
}

$curriculum = 'https://www.iitkgp.ac.in/curricula-pg';
$calendar = 'https://www.iitkgp.ac.in/academic-calendar-ug';
$erp = 'https://erp.iitkgp.ac.in/';
$source = '<p><a href="https://www.iitkgp.ac.in/department/GS">GSSST at IIT Kharagpur</a></p>';
$resources = '<h2 id="curriculum">Curriculum &amp; course structure</h2><p><a href="' . $curriculum . '">Official postgraduate curricula</a> &middot; <a href="' . $calendar . '">Academic calendar</a></p>';
$support = '<h2 id="guidance">Guidance &amp; support</h2><p><a href="' . home_url('/faculty/') . '">Meet the faculty</a> for academic guidance and research interests. For programme-specific information, contact the School through the <a href="' . home_url('/#head') . '">department contact section</a>.</p>';
$programmes = array(
    'mtech' => array('M.Tech — Wireless Communications and Networks', '<h2 id="overview">Programme overview</h2><p>GSSST offers postgraduate study in Wireless Communications and Networks (GS1), listed in IIT Kharagpur’s official programme catalogue.</p><h2 id="structure">Programme structure</h2><p>The M.Tech stage combines advanced coursework and project work. Refer to the official curriculum for the current course structure and academic requirements.</p><p><a href="https://gateoffice.iitkgp.ac.in/jmp/d/?p=108">Official programme listing</a></p>'),
    'joint-mtech-phd' => array('Joint M.Tech / PhD', '<h2 id="overview">Programme overview</h2><p>IIT Kharagpur offers a joint M.Tech–PhD framework. GSSST’s Wireless Communications and Networks programme is listed under this framework.</p><h2 id="structure">Coursework &amp; research</h2><p>Programme progression and doctoral registration follow institute regulations. Consult the official programme information for the current framework.</p><p><a href="https://gateoffice.iitkgp.ac.in/jmp/">Official joint programme information</a></p>'),
    'phd' => array('PhD Programme', '<h2 id="overview">Programme overview</h2><p>Doctoral research at GSSST focuses on telecommunications and related fields. Explore the School’s research areas and faculty profiles to identify relevant expertise.</p><h2 id="research">Research &amp; supervision</h2><p>The programme combines research training with an original thesis, under faculty supervision and institute academic regulations.</p><p><a href="' . home_url('/research/') . '">Explore research areas and associated faculty</a></p>'),
    'ms-research' => array('M.S. by Research', '<h2 id="overview">Programme overview</h2><p>M.S. research is part of the School’s research activities, as described on IIT Kharagpur’s official GSSST page.</p><h2 id="research">Research &amp; supervision</h2><p>Explore faculty expertise and discuss potential research topics with a suitable supervisor. Current academic requirements follow institute regulations.</p>'),
);
foreach ($programmes as $slug => $programme) {
    cic_review_page($slug, $programme[0], $programme[1] . $resources . $support . '<h2 id="reference">Official information</h2>' . $source, 'page-programme.php');
}

// Seed area names only from saved faculty records, without fetching or inventing research.
$existing_areas = get_posts(array('post_type' => 'cic_research_area', 'numberposts' => -1, 'post_status' => array('publish', 'draft', 'pending', 'private')));
$seen = array_map(function ($area) { return strtolower(trim($area->post_title)); }, $existing_areas);
foreach (cic_get_faculty_members() as $faculty) {
    foreach ($faculty['research_areas'] as $area) {
        $key = strtolower(trim($area));
        if (!$key || in_array($key, $seen, true)) continue;
        $id = wp_insert_post(array('post_type' => 'cic_research_area', 'post_status' => 'publish', 'post_title' => $area), true);
        if (is_wp_error($id)) throw new RuntimeException($id->get_error_message());
        $seen[] = $key;
    }
}

$base = home_url('/academics/');
$placeholder = function ($url) use ($base) {
    return in_array(trim((string) $url), array('', '#', $base, rtrim($base, '/'), $base . '#resources'), true);
};
$programme_link = function ($title) {
    if (preg_match('/joint.*m\.?tech/i', $title)) return home_url('/joint-mtech-phd/');
    if (preg_match('/m\.?s\..*research/i', $title)) return home_url('/ms-research/');
    if (preg_match('/ph\.?d/i', $title)) return home_url('/phd/');
    if (preg_match('/m\.?tech/i', $title)) return home_url('/mtech/');
    return '';
};
$header = cic_get_header_settings();
$header['nav_headings'] = array_values(array_filter($header['nav_headings'], function ($heading) {
    return !in_array(strtoupper(trim($heading['title'])), array('HOME', 'AWARDS', 'IIT KGP'), true);
}));
foreach ($header['nav_headings'] as &$heading) {
    $title = strtoupper(trim($heading['title']));
    if ($title === 'ABOUT US') {
        if ($placeholder($heading['url'])) $heading['url'] = home_url('/#about');
        $heading['subheadings'] = array_values(array_filter($heading['subheadings'], function ($sub) {
            return strcasecmp($sub['title'], 'History & Vision') !== 0;
        }));
        foreach ($heading['subheadings'] as &$sub) {
            if (strcasecmp($sub['title'], "Head's Message") === 0 && in_array($sub['url'], array('#', '#head'), true)) $sub['url'] = home_url('/#head');
            if (strcasecmp($sub['title'], 'Our Department') === 0 && in_array($sub['url'], array('#', '#dept'), true)) $sub['url'] = home_url('/#about');
        }
        unset($sub);
    } elseif ($title === 'PEOPLE') {
        foreach ($heading['subheadings'] as &$sub) {
            if (strcasecmp($sub['title'], 'Staff & Administration') === 0 && $placeholder($sub['url'])) $sub['url'] = home_url('/staff/');
            if (strcasecmp($sub['title'], 'Notable Alumni') === 0 && $placeholder($sub['url'])) { $sub['title'] = 'Alumni'; $sub['url'] = home_url('/alumni/'); }
        }
        unset($sub);
        if ($placeholder($heading['url'])) $heading['url'] = home_url('/faculty/');
    } elseif ($title === 'ACADEMICS') {
        foreach ($heading['subheadings'] as &$sub) {
            if (preg_match('/curriculum/i', $sub['title']) && $placeholder($sub['url'])) $sub['url'] = $curriculum;
            if (preg_match('/time.?table|schedule/i', $sub['title']) && $placeholder($sub['url'])) $sub['url'] = $erp;
            foreach ($sub['sub_subheadings'] ?? array() as $i => $child) {
                $url = $programme_link($child['title']);
                if ($url && $placeholder($child['url'])) $sub['sub_subheadings'][$i]['url'] = $url;
            }
        }
        unset($sub);
    } elseif ($title === 'RESEARCH') {
        if ($placeholder($heading['url'])) $heading['url'] = home_url('/research/');
        $heading['subheadings'] = array_values(array_filter($heading['subheadings'], function ($sub) { return strcasecmp($sub['title'], 'Publications') !== 0; }));
        foreach ($heading['subheadings'] as &$sub) {
            if (strcasecmp($sub['title'], 'Research Areas') === 0 && $placeholder($sub['url'])) $sub['url'] = home_url('/research/');
            if (strcasecmp($sub['title'], 'Laboratories & Facilities') === 0 && $placeholder($sub['url'])) $sub['url'] = home_url('/laboratories/');
        }
        unset($sub);
    } elseif ($title === 'INTERNAL' && $placeholder($heading['url'])) $heading['url'] = home_url('/login/');
}
unset($heading);
update_option('cic_header_settings', $header);

$dept = cic_get_dept_settings();
$dept['cards'] = array_values(array_filter($dept['cards'], function ($card) { return !preg_match('/^(awards|carrer|career|careers)$/i', trim($card['title'])); }));
foreach ($dept['cards'] as &$card) {
    if (!$placeholder($card['btn_url'] ?? '')) continue;
    if (stripos($card['title'], 'research') !== false) $card['btn_url'] = home_url('/research/');
    if (stripos($card['title'], 'faculty') !== false) $card['btn_url'] = home_url('/faculty/');
}
unset($card);
update_option('cic_dept_settings', $dept);

$academics = cic_get_academics_settings();
foreach ($academics['columns'] as &$column) {
    foreach ($column['items'] as &$item) {
        if (!$placeholder($item['url'])) continue;
        $url = $programme_link($item['title']);
        if ($url) $item['url'] = $url;
        elseif (preg_match('/curriculum/i', $item['title'])) $item['url'] = $curriculum;
        elseif (preg_match('/time.?table|schedule/i', $item['title'])) $item['url'] = $erp;
    }
    unset($item);
}
unset($column);
update_option('cic_academics_settings', $academics);
$footer = cic_get_footer_settings();
$footer['academics_links'] = array_values(array_filter($footer['academics_links'], function ($link) { return stripos($link['title'], 'admission') === false; }));
foreach ($footer['academics_links'] as &$link) if (stripos($link['title'], 'calendar') !== false && $placeholder($link['url'])) $link['url'] = $calendar;
unset($link);
update_option('cic_footer_settings', $footer);
update_option('cic_content_review_20261104', 1);
echo "Approved content and navigation changes applied. Existing users, articles and custom destinations retained.\n";
