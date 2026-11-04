<?php
/** Local, administrator-managed vacancies and example advertisements. */
if (!defined('ABSPATH')) exit;

function cic_job_fields() {
    return array(
        'category' => array('Category', 'select', array('faculty' => 'Faculty', 'staff' => 'Staff', 'project' => 'Project & research staff', 'postdoc' => 'Postdoctoral')),
        'status' => array('Listing status', 'select', array('example' => 'Example listing', 'open' => 'Open', 'upcoming' => 'Upcoming', 'closed' => 'Closed')),
        'reference' => array('Advertisement reference', 'text'), 'employment' => array('Appointment type', 'text'),
        'duration' => array('Duration', 'text'), 'location' => array('Location', 'text'),
        'salary' => array('Salary / fellowship', 'text'), 'deadline' => array('Application deadline', 'date'),
        'apply_url' => array('Application link (optional)', 'url'), 'contact' => array('Recruitment contact email (optional)', 'email'),
    );
}
add_action('init', function () {
    $caps = array_fill_keys(array('edit_post', 'edit_posts', 'edit_others_posts', 'publish_posts', 'read_private_posts', 'delete_post', 'delete_posts', 'delete_private_posts', 'delete_published_posts', 'delete_others_posts', 'edit_private_posts', 'edit_published_posts', 'create_posts'), 'manage_options');
    $caps['read_post'] = 'read';
    register_post_type('cic_job', array(
        'labels' => array('name' => 'Jobs', 'singular_name' => 'Job', 'add_new_item' => 'Add vacancy', 'edit_item' => 'Edit vacancy'),
        'public' => true, 'show_in_rest' => true, 'rewrite' => array('slug' => 'jobs', 'with_front' => false),
        'has_archive' => false, 'supports' => array('title', 'editor', 'excerpt', 'revisions'),
        'capabilities' => $caps, 'map_meta_cap' => false, 'menu_icon' => 'dashicons-businessperson',
    ));
});
add_action('add_meta_boxes', function () {
    add_meta_box('cic_job_details', 'Vacancy details', 'cic_job_editor', 'cic_job');
});
function cic_job_editor($post) {
    wp_nonce_field('cic_save_job', 'cic_job_nonce');
    $data = (array) get_post_meta($post->ID, 'cic_job', true);
    echo '<p>Use the editor for the role description, responsibilities, eligibility, documents and application instructions. Use the excerpt for the listing summary. Example listings never show an Apply button. Open listings close automatically after their deadline in the site timezone.</p>';
    foreach (cic_job_fields() as $key => $field) {
        $id = 'cic_job_' . $key;
        echo '<p><label for="' . esc_attr($id) . '"><strong>' . esc_html($field[0]) . '</strong></label><br>';
        if ($field[1] === 'select') {
            echo '<select id="' . esc_attr($id) . '" name="cic_job[' . esc_attr($key) . ']">';
            foreach ($field[2] as $value => $label) echo '<option value="' . esc_attr($value) . '" ' . selected($data[$key] ?? array_key_first($field[2]), $value, false) . '>' . esc_html($label) . '</option>';
            echo '</select>';
        } else {
            echo '<input class="widefat" id="' . esc_attr($id) . '" type="' . esc_attr($field[1]) . '" name="cic_job[' . esc_attr($key) . ']" value="' . esc_attr($data[$key] ?? '') . '">';
        }
        echo '</p>';
    }
}
add_action('save_post_cic_job', function ($id) {
    if (!current_user_can('manage_options') || !current_user_can('edit_post', $id) || wp_is_post_revision($id) || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)) return;
    if (!isset($_POST['cic_job_nonce']) || !is_string($_POST['cic_job_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['cic_job_nonce'])), 'cic_save_job')) return;
    $input = isset($_POST['cic_job']) && is_array($_POST['cic_job']) ? wp_unslash($_POST['cic_job']) : array();
    $clean = array();
    foreach (cic_job_fields() as $key => $field) {
        $value = isset($input[$key]) && is_string($input[$key]) ? $input[$key] : '';
        if ($field[1] === 'select') $clean[$key] = isset($field[2][$value]) ? $value : array_key_first($field[2]);
        elseif ($field[1] === 'url') $clean[$key] = esc_url_raw($value, array('https', 'http'));
        elseif ($field[1] === 'email') $clean[$key] = sanitize_email($value);
        elseif ($field[1] === 'date') {
            $date = preg_match('/^\d{4}-\d{2}-\d{2}$/D', $value) ? DateTimeImmutable::createFromFormat('!Y-m-d', $value) : false;
            $clean[$key] = $date && $date->format('Y-m-d') === $value ? $value : '';
        } else $clean[$key] = sanitize_text_field($value);
    }
    update_post_meta($id, 'cic_job', $clean);
});
function cic_job_data($id) {
    $data = (array) get_post_meta($id, 'cic_job', true);
    foreach (cic_job_fields() as $key => $field) {
        if ($field[1] === 'select' && !isset($field[2][$data[$key] ?? ''])) $data[$key] = array_key_first($field[2]);
    }
    if ($data['status'] === 'open' && !empty($data['deadline']) && $data['deadline'] < wp_date('Y-m-d')) $data['status'] = 'closed';
    return $data;
}
function cic_job_label($key, $value) {
    return cic_job_fields()[$key][2][$value] ?? $value;
}
function cic_job_deadline($value) {
    return wp_date(get_option('date_format'), strtotime($value . ' 12:00:00 UTC'), new DateTimeZone('UTC'));
}
add_action('wp_enqueue_scripts', function () {
    if (is_page('jobs') || is_page_template('page-jobs.php') || is_singular('cic_job')) {
        wp_enqueue_style('cic-department-content', get_template_directory_uri() . '/assets/css/department-content.css', array('cic-main-style'), filemtime(get_template_directory() . '/assets/css/department-content.css'));
        wp_enqueue_style('cic-jobs', get_template_directory_uri() . '/assets/css/jobs.css', array('cic-department-content'), filemtime(get_template_directory() . '/assets/css/jobs.css'));
    }
    if (is_page('jobs') || is_page_template('page-jobs.php')) wp_enqueue_script('cic-jobs', get_template_directory_uri() . '/assets/js/jobs.js', array(), filemtime(get_template_directory() . '/assets/js/jobs.js'), true);
});
