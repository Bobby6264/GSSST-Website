<?php
/** Functional checks for the approved directories and navigation. Uses disposable draft records. */
if (PHP_SAPI !== 'cli') exit;
require '/var/www/html/wp-load.php';
function review_check($condition, $message) {
    if (!$condition) throw new RuntimeException($message);
    echo "PASS: $message\n";
}
$admins = get_users(array('role' => 'administrator', 'number' => 1));
$faculty_users = get_users(array('role' => 'faculty', 'number' => 1));
review_check($admins && $faculty_users, 'Existing administrator and faculty accounts are available');
$ids = array();
try {
    wp_set_current_user($admins[0]->ID);
    foreach (cic_directory_types() as $type => $config) {
        review_check(current_user_can(get_post_type_object($type)->cap->create_posts), "Administrator can manage $type");
        $page = get_page_by_path($config['slug']);
        review_check($page && $page->post_status === 'publish' && get_page_template_slug($page) === 'page-department-directory.php', "Published directory page: {$config['slug']}");
    }
    foreach (array('mtech', 'joint-mtech-phd', 'phd', 'ms-research') as $slug) {
        $page = get_page_by_path($slug);
        review_check($page && get_page_template_slug($page) === 'page-programme.php' && strlen($page->post_content) > 200, "Editable programme content: $slug");
    }
    if (get_option('cic_local_academic_examples_v1')) {
        foreach (array('curriculum', 'timetable', 'academic-calendar') as $slug) {
            $page = get_page_by_path($slug);
            $tables = array_filter(parse_blocks($page ? $page->post_content : ''), function ($block) { return $block['blockName'] === 'core/table'; });
            review_check($page && get_page_template_slug($page) === 'page-academic-resource.php' && $tables && current_user_can('edit_post', $page->ID), "Local academic page has admin-editable table blocks: $slug");
        }
        review_check(strpos(file_get_contents(get_template_directory() . '/faculty/faculty-page.php'), 'faculty-profile-facts') === false, 'Faculty profile statistics markup has been removed');
    }
    $id = wp_insert_post(array('post_type' => 'cic_alumni', 'post_status' => 'draft', 'post_title' => 'Disposable directory verification record'));
    $ids[] = $id;
    $_POST = array('cic_directory_nonce' => wp_create_nonce('cic_directory_record'), 'cic_directory_record' => array('category' => 'faculty', 'programme_role' => '<script>alert(1)</script>Professor', 'years' => '2000–2010', 'affiliation' => 'Example', 'url' => 'javascript:alert(1)'));
    wp_update_post(array('ID' => $id));
    $data = get_post_meta($id, 'cic_directory_record', true);
    review_check($data['category'] === 'faculty' && $data['programme_role'] === 'Professor' && $data['url'] === '', 'Admin save sanitizes public directory fields and rejects script URLs');
    ob_start(); cic_directory_record_form(get_post($id)); $form = ob_get_clean();
    review_check(strpos($form, 'Former Staff') !== false && strpos($form, 'Current affiliation') !== false && strpos($form, 'cic_directory_nonce') !== false, 'Alumni editor renders approved fields and a nonce');
    $_POST['cic_directory_nonce'] = 'invalid';
    $_POST['cic_directory_record']['years'] = 'MUST NOT SAVE';
    wp_update_post(array('ID' => $id));
    review_check(get_post_meta($id, 'cic_directory_record', true)['years'] === '2000–2010', 'Invalid nonce cannot overwrite directory metadata');
    wp_set_current_user($faculty_users[0]->ID);
    foreach (array_keys(cic_directory_types()) as $type) review_check(!current_user_can(get_post_type_object($type)->cap->create_posts), "Faculty cannot manage $type");
    $_POST['cic_directory_nonce'] = wp_create_nonce('cic_directory_record');
    wp_update_post(array('ID' => $id));
    review_check(get_post_meta($id, 'cic_directory_record', true)['years'] === '2000–2010', 'Faculty cannot overwrite directory metadata');
    $titles = array_map(function ($heading) { return strtoupper($heading['title']); }, cic_get_header_settings()['nav_headings']);
    review_check(!array_intersect($titles, array('HOME', 'AWARDS', 'IIT KGP', 'JOBS')), 'Approved top-level menu removals are applied');
    $area = get_page_by_title('Cellular Networks', OBJECT, 'cic_research_area');
    review_check($area && count(cic_research_area_faculty($area, cic_get_faculty_members())) === 3, 'Cellular Networks resolves to its three existing faculty profiles');
    echo "All content review checks passed.\n";
} finally {
    $_POST = array();
    wp_set_current_user($admins[0]->ID);
    foreach ($ids as $id) wp_delete_post($id, true);
}
