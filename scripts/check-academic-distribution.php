<?php
/** Functional checks using one disposable draft vacancy. */
if (PHP_SAPI !== 'cli') exit;
require '/var/www/html/wp-load.php';
function distribution_check($value, $message) {
    if (!$value) throw new RuntimeException($message);
    echo "PASS: $message\n";
}
$admin = get_users(array('role' => 'administrator', 'number' => 1))[0];
$faculty = get_users(array('role' => 'faculty', 'number' => 1))[0];
$id = 0;
try {
    $headings = cic_get_header_settings()['nav_headings'];
    $academic = array_values(array_filter($headings, function ($item) { return stripos($item['title'], 'academic') !== false; }))[0];
    distribution_check($academic['url'] === '#', 'Academics is a dropdown heading');
    $jobs = array_values(array_filter($academic['subheadings'], function ($item) { return $item['title'] === 'Jobs'; }));
    distribution_check(count($jobs) === 1 && $jobs[0]['url'] === home_url('/jobs/'), 'Jobs appears once under Academics');
    distribution_check(cic_academic_link('Department projects') === home_url('/research/#projects'), 'Project placeholders resolve to Research');
    distribution_check(cic_academic_link('Projects', 'https://example.org/custom') === 'https://example.org/custom', 'Custom destinations are preserved');
    $projects = cic_academic_projects();
    $titles = array_map(function ($project) { return strtolower(preg_replace('/\s+/', ' ', trim($project['title']))); }, $projects);
    distribution_check(count($titles) === count(array_unique($titles)), 'Department project titles remain deduplicated');
    distribution_check(get_page_template_slug(get_page_by_path('jobs')) === 'page-jobs.php', 'Local Jobs page uses its own template');
    wp_set_current_user($admin->ID);
    distribution_check(current_user_can(get_post_type_object('cic_job')->cap->create_posts), 'Administrator can create vacancies');
    $id = wp_insert_post(array('post_type' => 'cic_job', 'post_status' => 'draft', 'post_title' => 'Disposable vacancy verification'));
    $_POST = array('cic_job_nonce' => wp_create_nonce('cic_save_job'), 'cic_job' => array('category' => 'project', 'status' => 'open', 'deadline' => wp_date('Y-m-d', time() - DAY_IN_SECONDS), 'reference' => '<script>x</script>TEST', 'apply_url' => 'javascript:alert(1)'));
    wp_update_post(array('ID' => $id));
    distribution_check(cic_job_data($id)['status'] === 'closed', 'Open vacancy closes after its deadline');
    $saved = get_post_meta($id, 'cic_job', true);
    distribution_check($saved['apply_url'] === '' && $saved['reference'] === 'TEST', 'Vacancy fields reject scripts and unsafe URLs');
    $_POST['cic_job']['status'] = 'example'; wp_update_post(array('ID' => $id));
    distribution_check(cic_job_data($id)['status'] === 'example', 'Examples retain their example status');
    $_POST['cic_job']['deadline'] = '2026-02-31'; wp_update_post(array('ID' => $id));
    distribution_check(get_post_meta($id, 'cic_job', true)['deadline'] === '', 'Invalid calendar dates are rejected');
    ob_start(); cic_job_editor(get_post($id)); $form = ob_get_clean();
    distribution_check(strpos($form, 'Application deadline') !== false && strpos($form, 'Recruitment contact') !== false, 'Administrator editor exposes vacancy and application fields');
    $_POST['cic_job_nonce'] = 'invalid'; $_POST['cic_job']['reference'] = 'MUST NOT SAVE'; wp_update_post(array('ID' => $id));
    distribution_check(get_post_meta($id, 'cic_job', true)['reference'] === 'TEST', 'Invalid nonce cannot update vacancy details');
    wp_set_current_user($faculty->ID);
    distribution_check(!current_user_can(get_post_type_object('cic_job')->cap->create_posts), 'Faculty do not gain vacancy editing access');
    $_POST['cic_job_nonce'] = wp_create_nonce('cic_save_job'); wp_update_post(array('ID' => $id));
    distribution_check(get_post_meta($id, 'cic_job', true)['reference'] === 'TEST', 'Faculty cannot overwrite vacancy metadata');
    echo "Academic distribution and Jobs checks passed.\n";
} finally {
    $_POST = array(); wp_set_current_user($admin->ID);
    if ($id) wp_delete_post($id, true);
}
