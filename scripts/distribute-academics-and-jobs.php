<?php
/** Apply the user's menu-only Academics structure and local Jobs page once. */
if (PHP_SAPI !== 'cli') exit;
require '/var/www/html/wp-load.php';
if (get_option('cic_academics_distribution_v1')) { echo "Academic distribution already applied.\n"; exit; }

$job_page = get_page_by_path('jobs');
if (!$job_page) {
    $id = wp_insert_post(array('post_type' => 'page', 'post_status' => 'publish', 'post_name' => 'jobs', 'post_title' => 'Jobs', 'post_content' => '<!-- wp:paragraph --><p>Explore faculty, staff, project and postdoctoral opportunities at the School. The initial listings below are illustrative examples and are not accepting applications.</p><!-- /wp:paragraph -->'), true);
    if (is_wp_error($id)) throw new RuntimeException($id->get_error_message());
    update_post_meta($id, '_wp_page_template', 'page-jobs.php');
}
$examples = array(
    'example-faculty-position' => array('Faculty position — Communications & Networks', 'faculty', 'EXAMPLE/FAC/01', 'Academic appointment', 'Teaching, research and student supervision in communications and networks.'),
    'example-project-researcher' => array('Project Research Associate — Wireless Systems', 'project', 'EXAMPLE/RES/01', 'Fixed-term research appointment', 'Support experimental studies, system prototyping and research reporting.'),
    'example-postdoctoral-fellow' => array('Postdoctoral Fellow — Signal Processing', 'postdoc', 'EXAMPLE/PDF/01', 'Research fellowship', 'Develop independent research in signal processing and collaborate with a faculty host.'),
    'example-technical-staff' => array('Technical Assistant — Communication Laboratory', 'staff', 'EXAMPLE/STAFF/01', 'Technical appointment', 'Support laboratory teaching, equipment maintenance and day-to-day experimental work.'),
);
foreach ($examples as $slug => $example) {
    if (get_page_by_path($slug, OBJECT, 'cic_job')) continue;
    $content = '<!-- wp:heading --><h2 class="wp-block-heading">About the role</h2><!-- /wp:heading -->'
        . '<!-- wp:paragraph --><p>' . esc_html($example[4]) . '</p><!-- /wp:paragraph -->'
        . '<!-- wp:heading --><h2 class="wp-block-heading">Responsibilities</h2><!-- /wp:heading -->'
        . '<!-- wp:paragraph --><p>Illustrative duties: contribute to the relevant academic or research team, document work, collaborate with colleagues and participate in scheduled reviews.</p><!-- /wp:paragraph -->'
        . '<!-- wp:heading --><h2 class="wp-block-heading">Eligibility &amp; experience</h2><!-- /wp:heading -->'
        . '<!-- wp:paragraph --><p>Sample format: a relevant qualification, subject knowledge and experience appropriate to the role. Replace this section with the approved essential and desirable criteria.</p><!-- /wp:paragraph -->'
        . '<!-- wp:heading --><h2 class="wp-block-heading">How to apply</h2><!-- /wp:heading -->'
        . '<!-- wp:paragraph --><p>This example is not accepting applications. A real advertisement should state the submission method, deadline, selection stages and recruitment contact here.</p><!-- /wp:paragraph -->'
        . '<!-- wp:heading --><h2 class="wp-block-heading">Documents &amp; selection</h2><!-- /wp:heading -->'
        . '<!-- wp:paragraph --><p>Sample checklist: curriculum vitae, statement of interest, qualifications and relevant work. Include any role-specific forms, advertisement download, shortlisting process and interview information in this section.</p><!-- /wp:paragraph -->';
    $id = wp_insert_post(array('post_type' => 'cic_job', 'post_status' => 'publish', 'post_name' => $slug, 'post_title' => $example[0], 'post_excerpt' => $example[4], 'post_content' => $content), true);
    if (is_wp_error($id)) throw new RuntimeException($id->get_error_message());
    update_post_meta($id, 'cic_job', array('category' => $example[1], 'status' => 'example', 'reference' => $example[2], 'employment' => $example[3], 'location' => 'GSSST, IIT Kharagpur', 'duration' => 'To be specified in a real advertisement', 'salary' => 'To be specified in a real advertisement'));
}

$replace_link = function ($url) {
    $replacements = array(
        home_url('/academics/#projects') => home_url('/research/#projects'),
        home_url('/academics/#courses') => home_url('/curriculum/'),
        home_url('/academics/#resources') => home_url('/#academics'),
        home_url('/academics/#programmes') => home_url('/#academics'),
        home_url('/academics/') => home_url('/#academics'),
        home_url('/academics') => home_url('/#academics'),
    );
    return $replacements[$url] ?? $url;
};
$walk = function (&$items) use (&$walk, $replace_link) {
    foreach ($items as &$item) {
        foreach (array('url', 'btn_url') as $key) if (isset($item[$key])) $item[$key] = $replace_link($item[$key]);
        foreach (array('subheadings', 'sub_subheadings', 'items') as $key) if (!empty($item[$key])) $walk($item[$key]);
    }
    unset($item);
};
$header = cic_get_header_settings();
foreach ($header['nav_headings'] as &$heading) {
    if (stripos($heading['title'], 'academic') !== false) {
        if (in_array($heading['url'], array('', '#', home_url('/academics/'), home_url('/academics')), true)) $heading['url'] = '#';
        $has_jobs = false;
        foreach ($heading['subheadings'] as &$sub) {
            if (stripos($sub['title'], 'programme') !== false && !empty($sub['sub_subheadings']) && in_array($sub['url'], array('', '#', home_url('/academics/')), true)) $sub['url'] = '#';
            if (strcasecmp($sub['title'], 'Jobs') === 0) $has_jobs = true;
        }
        unset($sub);
        if (!$has_jobs) $heading['subheadings'][] = array('title' => 'Jobs', 'url' => home_url('/jobs/'), 'sub_subheadings' => array());
    }
}
unset($heading);
$walk($header['nav_headings']); update_option('cic_header_settings', $header);
$hero = cic_get_hero_settings(); $walk($hero['buttons']); update_option('cic_hero_settings', $hero);
$footer = cic_get_footer_settings(); $walk($footer['academics_links']); update_option('cic_footer_settings', $footer);
$academics = cic_get_academics_settings(); $walk($academics['columns']); update_option('cic_academics_settings', $academics);
$dept = cic_get_dept_settings(); $walk($dept['cards']); update_option('cic_dept_settings', $dept);

// Replace only legacy destinations inside existing page copy, preserving other content.
foreach (get_posts(array('post_type' => 'page', 'post_status' => array('publish', 'draft', 'private'), 'numberposts' => -1)) as $page) {
    $content = $page->post_content;
    foreach (array('#projects', '#courses', '#resources', '#programmes', '') as $fragment) {
        $old = home_url('/academics/') . $fragment;
        $content = str_replace($old, $replace_link($old), $content);
    }
    if ($content !== $page->post_content) { wp_save_post_revision($page->ID); wp_update_post(array('ID' => $page->ID, 'post_content' => $content)); }
}
flush_rewrite_rules(false);
update_option('cic_academics_distribution_v1', 1);
echo "Projects moved to Research; Academics is a menu group; Jobs and example advertisements are ready.\n";
