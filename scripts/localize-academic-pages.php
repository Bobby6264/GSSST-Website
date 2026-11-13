<?php
/** User-approved local academic examples. Run once through the WordPress container. */
if (PHP_SAPI !== 'cli') exit;
require '/var/www/html/wp-load.php';
if (get_option('cic_local_academic_examples_v1')) { echo "Local academic pages already configured.\n"; exit; }

function cic_example_paragraph($text, $notice = false) {
    $class = $notice ? ' class="academic-sample-notice"' : '';
    $attributes = $notice ? ' {"className":"academic-sample-notice"}' : '';
    return '<!-- wp:paragraph' . $attributes . ' --><p' . $class . '>' . $text . '</p><!-- /wp:paragraph -->' . "\n";
}
function cic_example_heading($title, $id) {
    return '<!-- wp:heading {"anchor":"' . $id . '"} --><h2 class="wp-block-heading" id="' . $id . '">' . esc_html($title) . '</h2><!-- /wp:heading -->' . "\n";
}
function cic_example_table($headers, $rows) {
    $html = '<!-- wp:table {"hasFixedLayout":false} --><figure class="wp-block-table"><table><thead><tr>';
    foreach ($headers as $header) $html .= '<th>' . esc_html($header) . '</th>';
    $html .= '</tr></thead><tbody>';
    foreach ($rows as $row) {
        $html .= '<tr>';
        foreach ($row as $cell) $html .= '<td>' . esc_html($cell) . '</td>';
        $html .= '</tr>';
    }
    return $html . '</tbody></table></figure><!-- /wp:table -->' . "\n";
}
function cic_example_buttons($links) {
    $html = '<!-- wp:buttons --><div class="wp-block-buttons">';
    foreach ($links as $label => $url) $html .= '<!-- wp:button {"className":"is-style-outline"} --><div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="' . esc_url($url) . '">' . esc_html($label) . '</a></div><!-- /wp:button -->';
    return $html . '</div><!-- /wp:buttons -->' . "\n";
}
function cic_example_page($slug, $title, $content, $template, $replace = false) {
    $page = get_page_by_path($slug);
    if ($page && !$replace) return $page->ID;
    $post = array('post_type' => 'page', 'post_title' => $title, 'post_content' => $content);
    if ($page) {
        wp_save_post_revision($page->ID);
        $post['ID'] = $page->ID;
        // Preserve the original generated text separately from normal WordPress revisions.
        if (!metadata_exists('post', $page->ID, 'cic_before_local_academics')) update_post_meta($page->ID, 'cic_before_local_academics', $page->post_content);
        $id = wp_update_post($post, true);
    } else {
        $post['post_status'] = 'publish'; $post['post_name'] = $slug;
        $id = wp_insert_post($post, true);
    }
    if (is_wp_error($id)) throw new RuntimeException($id->get_error_message());
    update_post_meta($id, '_wp_page_template', $template);
    echo "Configured local page: $slug\n";
    return $id;
}

$notice = cic_example_paragraph('<strong>Illustrative sample content.</strong> The courses, credits, dates, schedules and requirements below are examples for the website layout. They are not an approved academic schedule or curriculum.', true);
$curriculum = $notice . cic_example_paragraph('Explore programme structures by batch and semester. Each table lists sample core courses, electives and research work.')
    . cic_example_buttons(array('M.Tech · 2026 batch' => '#mtech-2026', 'Joint M.Tech / PhD' => '#joint-programme', 'Research programmes' => '#research-programmes'))
    . cic_example_heading('M.Tech — 2026 batch', 'mtech-2026')
    . cic_example_paragraph('Sample specialisation: Wireless Communications and Networks. The following four-semester structure is illustrative.')
    . cic_example_table(array('Semester', 'Course code', 'Course / requirement', 'Category', 'Credits'), array(
        array('1', 'GS501', 'Digital Communication Systems', 'Core', '4'), array('1', 'GS503', 'Probability and Random Processes', 'Core', '4'),
        array('1', 'GS505', 'Communication Networks', 'Core', '3'), array('1', 'GS591', 'Communication Systems Laboratory', 'Laboratory', '2'),
        array('2', 'GS502', 'Wireless Communication', 'Core', '4'), array('2', 'GS504', 'Statistical Signal Processing', 'Core', '4'),
        array('2', 'GS506', 'Optical Networks', 'Elective', '3'), array('2', 'GS592', 'Research Seminar', 'Seminar', '2'),
        array('3', 'GS691', 'Dissertation — Stage I', 'Research', '12'), array('4', 'GS692', 'Dissertation — Stage II', 'Research', '16'),
    )) . cic_example_heading('Joint M.Tech / PhD — sample progression', 'joint-programme')
    . cic_example_table(array('Stage', 'Sample academic work', 'Review milestone'), array(
        array('Foundation', 'Postgraduate core courses and laboratory work', 'Coursework review'),
        array('Research preparation', 'Electives, literature survey and research seminar', 'Research proposal presentation'),
        array('Doctoral research', 'Original investigation with a faculty supervisor', 'Periodic progress review'),
        array('Completion', 'Thesis preparation and presentation', 'Thesis examination'),
    )) . cic_example_heading('M.S. and PhD — sample research structure', 'research-programmes')
    . cic_example_table(array('Component', 'M.S. by Research', 'PhD'), array(
        array('Coursework', 'Research methods and topic-specific electives', 'Advanced topic-specific coursework'),
        array('Research plan', 'Define a supervised research problem', 'Develop an original research proposal'),
        array('Progress', 'Seminar and progress presentation', 'Periodic doctoral progress presentations'),
        array('Final work', 'Research thesis', 'Doctoral thesis and defence'),
    ));
cic_example_page('curriculum', 'Academic Curriculum', $curriculum, 'page-academic-resource.php');

$timetable = $notice . cic_example_paragraph('Browse the sample teaching timetable and examination schedule. Search by course code, title, instructor, day or venue.')
    . cic_example_buttons(array('Course timetable' => '#course-timetable', 'Exam schedule' => '#exam-schedule'))
    . cic_example_heading('Course timetable — Autumn 2026', 'course-timetable')
    . cic_example_table(array('Day', 'Time', 'Code', 'Course', 'Instructor', 'Venue'), array(
        array('Monday', '09:00–10:00', 'GS501', 'Digital Communication Systems', 'Sample Faculty A', 'Lecture Room 101'),
        array('Monday', '11:00–12:00', 'GS503', 'Probability and Random Processes', 'Sample Faculty B', 'Lecture Room 102'),
        array('Tuesday', '10:00–11:00', 'GS505', 'Communication Networks', 'Sample Faculty C', 'Lecture Room 101'),
        array('Wednesday', '14:00–17:00', 'GS591', 'Communication Systems Laboratory', 'Sample Faculty D', 'Teaching Lab'),
        array('Thursday', '09:00–10:00', 'GS502', 'Wireless Communication', 'Sample Faculty A', 'Lecture Room 102'),
        array('Friday', '15:00–16:00', 'GS592', 'Research Seminar', 'Sample Faculty B', 'Seminar Hall'),
    )) . cic_example_heading('Exam schedule — sample dates', 'exam-schedule')
    . cic_example_table(array('Sample date', 'Time', 'Code', 'Course', 'Venue'), array(
        array('16 November 2026', '09:00–12:00', 'GS501', 'Digital Communication Systems', 'Exam Hall A'),
        array('18 November 2026', '09:00–12:00', 'GS503', 'Probability and Random Processes', 'Exam Hall A'),
        array('20 November 2026', '14:00–17:00', 'GS505', 'Communication Networks', 'Exam Hall B'),
    ));
cic_example_page('timetable', 'Timetable & Exam Schedules', $timetable, 'page-academic-resource.php');

$calendar = $notice . cic_example_paragraph('A sample year-at-a-glance view of registration, teaching, examinations and research reviews.')
    . cic_example_heading('Academic year 2026–27 — sample calendar', 'academic-year')
    . cic_example_table(array('Sample date / period', 'Activity', 'Semester'), array(
        array('20–24 July 2026', 'Registration and orientation', 'Autumn'), array('27 July 2026', 'Classes begin', 'Autumn'),
        array('21–25 September 2026', 'Mid-semester examinations', 'Autumn'), array('16–27 November 2026', 'End-semester examinations', 'Autumn'),
        array('4–8 January 2027', 'Semester registration', 'Spring'), array('11 January 2027', 'Classes begin', 'Spring'),
        array('1–5 March 2027', 'Mid-semester examinations', 'Spring'), array('19–30 April 2027', 'End-semester examinations', 'Spring'),
    )) . cic_example_heading('Research milestones — sample planning', 'research-milestones')
    . cic_example_table(array('Period', 'Sample milestone'), array(array('August', 'Research proposal discussions'), array('December', 'Semester progress presentations'), array('May', 'Annual research review')));
cic_example_page('academic-calendar', 'Academic Calendar', $calendar, 'page-academic-resource.php');

$programme_examples = array(
    'mtech' => array('M.Tech — Wireless Communications and Networks', 'An illustrative postgraduate programme combining communication theory, networks, practical laboratories and a supervised dissertation.', 'Four semesters', 'Core coursework, electives, laboratory work and dissertation'),
    'joint-mtech-phd' => array('Joint M.Tech / PhD', 'An illustrative pathway combining postgraduate study with doctoral research in telecommunications and related fields.', 'Coursework followed by doctoral research', 'Advanced coursework, research preparation, progress reviews and thesis'),
    'phd' => array('PhD Programme', 'An illustrative doctoral programme focused on original research in communication systems, signal processing and networks.', 'Research-based duration', 'Coursework, research proposal, periodic reviews and doctoral thesis'),
    'ms-research' => array('M.S. by Research', 'An illustrative research degree combining topic-specific study with a supervised investigation and thesis.', 'Research-based duration', 'Research methods, selected electives, seminars and thesis'),
);
foreach ($programme_examples as $slug => $details) {
    $body = $notice . cic_example_heading('Programme overview', 'overview') . cic_example_paragraph($details[1])
        . cic_example_buttons(array('Structure & requirements' => '#structure', 'Thesis & research' => '#thesis', 'Guidance & support' => '#guidance'))
        . cic_example_heading('Structure & requirements', 'structure')
        . cic_example_table(array('Item', 'Illustrative requirement'), array(array('Duration', $details[2]), array('Academic components', $details[3]), array('Review process', 'Semester academic review and research presentations')))
        . cic_example_heading('Curriculum & course structure', 'curriculum')
        . cic_example_paragraph('The sample curriculum groups core study, electives, laboratory experience and research work. <a href="' . home_url('/curriculum/') . '">View the local curriculum</a>.')
        . cic_example_heading('Thesis & research', 'thesis')
        . cic_example_paragraph('Sample format: choose a research topic with a faculty supervisor, prepare a literature review and work plan, present progress at agreed milestones, and submit a thesis with a final presentation.')
        . cic_example_paragraph('<a href="' . home_url('/research/') . '">Explore research areas and faculty</a>.')
        . cic_example_heading('Guidance & support', 'guidance')
        . cic_example_paragraph('Sample format: an academic adviser supports course planning, while a research supervisor guides project work. Programme coordination, assessment and student support details can be added here.')
        . cic_example_heading('Timetable & academic calendar', 'schedules')
        . cic_example_paragraph('<a href="' . home_url('/timetable/') . '">Course and exam schedules</a> · <a href="' . home_url('/academic-calendar/') . '">Academic calendar</a>');
    cic_example_page($slug, $details[0], $body, 'page-programme.php', true);
}

// Persist destinations in the existing editors; do not impose runtime overrides on custom links.
$local_url = function ($title, $url) {
    $host = wp_parse_url($url, PHP_URL_HOST) ?: '';
    if ($url !== '#' && $url !== '' && !preg_match('/(^|\.)iitkgp\.ac\.in$/i', $host)) return $url;
    if (preg_match('/curricul/i', $title)) return home_url('/curriculum/');
    if (preg_match('/time.?table|schedule/i', $title)) return home_url('/timetable/');
    if (preg_match('/calendar/i', $title)) return home_url('/academic-calendar/');
    return $url;
};
$walk = function (&$items) use (&$walk, $local_url) {
    foreach ($items as &$item) {
        if (isset($item['url'])) $item['url'] = $local_url($item['title'] ?? '', $item['url']);
        foreach (array('subheadings', 'sub_subheadings', 'items') as $key) if (!empty($item[$key])) $walk($item[$key]);
    }
    unset($item);
};
$header = cic_get_header_settings();
foreach ($header['nav_headings'] as &$heading) if (stripos($heading['title'], 'academic') !== false) $walk($heading['subheadings']);
unset($heading);
update_option('cic_header_settings', $header);
$settings = cic_get_academics_settings(); $walk($settings['columns']); update_option('cic_academics_settings', $settings);
$footer = cic_get_footer_settings(); $walk($footer['academics_links']); update_option('cic_footer_settings', $footer);
update_option('cic_local_academic_examples_v1', 1);
echo "Local editable academic content and destinations saved.\n";
