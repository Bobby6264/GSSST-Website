<?php
/** Admin-maintained academic records and department project overview. */
if (!defined('ABSPATH')) exit;

add_action('init', function () {
    foreach (array('cic_project' => array('Projects', 'Project'), 'cic_course' => array('Courses', 'Course')) as $type => $labels) {
        register_post_type($type, array(
            'labels' => array('name' => $labels[0], 'singular_name' => $labels[1], 'add_new_item' => 'Add ' . $labels[1]),
            'public' => true, 'show_in_rest' => true, 'capability_type' => 'page', 'map_meta_cap' => true,
            'rewrite' => array('slug' => $type === 'cic_project' ? 'projects' : 'courses'),
            'supports' => array('title', 'editor', 'excerpt'), 'menu_icon' => 'dashicons-welcome-learn-more',
        ));
    }
});
add_action('add_meta_boxes', function () {
    foreach (array('cic_project', 'cic_course') as $type) add_meta_box('cic_academic_record', 'Record details', 'cic_academic_record_form', $type);
});
function cic_academic_record_form($post) {
    wp_nonce_field('cic_academic_record', 'cic_academic_nonce');
    $data = (array) get_post_meta($post->ID, 'cic_record', true);
    $statuses = $post->post_type === 'cic_project' ? array('current' => 'Current', 'completed' => 'Completed') : array('current' => 'Current', 'archived' => 'Archived');
    echo '<p><label>Status <select name="cic_record[status]">';
    foreach ($statuses as $value => $label) echo '<option value="' . esc_attr($value) . '" ' . selected($data['status'] ?? 'current', $value, false) . '>' . esc_html($label) . '</option>';
    echo '</select></label></p>';
    foreach (array('code' => 'Project or course code', 'lead' => 'Faculty / investigator', 'sponsor' => 'Sponsor or programme', 'url' => 'Related webpage URL') as $key => $label) {
        echo '<p><label>' . esc_html($label) . '<br><input class="widefat" type="' . ($key === 'url' ? 'url' : 'text') . '" name="cic_record[' . esc_attr($key) . ']" value="' . esc_attr($data[$key] ?? '') . '"></label></p>';
    }
}
add_action('save_post', function ($id) {
    if (!in_array(get_post_type($id), array('cic_project', 'cic_course'), true) || !current_user_can('edit_post', $id) || wp_is_post_revision($id) || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)) return;
    if (!isset($_POST['cic_academic_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['cic_academic_nonce'])), 'cic_academic_record')) return;
    $input = isset($_POST['cic_record']) && is_array($_POST['cic_record']) ? wp_unslash($_POST['cic_record']) : array();
    $clean = array();
    foreach (array('code', 'lead', 'sponsor') as $key) $clean[$key] = sanitize_text_field($input[$key] ?? '');
    $clean['url'] = esc_url_raw($input['url'] ?? '');
    $allowed = get_post_type($id) === 'cic_project' ? array('current', 'completed') : array('current', 'archived');
    $clean['status'] = in_array($input['status'] ?? '', $allowed, true) ? $input['status'] : 'current';
    update_post_meta($id, 'cic_record', $clean);
});
function cic_academic_projects() {
    $projects = array();
    foreach (get_posts(array('post_type' => 'cic_project', 'numberposts' => -1)) as $post) {
        $data = (array) get_post_meta($post->ID, 'cic_record', true);
        $key = strtolower(preg_replace('/\s+/', ' ', trim($post->post_title)));
        $projects[$key] = array_merge($data, array('title' => $post->post_title, 'status' => $data['status'] ?? 'current', 'url' => ($data['url'] ?? '') ?: get_permalink($post), 'description' => get_the_excerpt($post)));
    }
    foreach (cic_get_faculty_members() as $faculty) {
        foreach ($faculty['current_projects'] as $title) {
            $key = strtolower(preg_replace('/\s+/', ' ', trim($title)));
            if (isset($projects[$key])) continue;
            $projects[$key] = array('title' => $title, 'status' => 'current', 'lead' => $faculty['name'], 'sponsor' => '', 'url' => $faculty['profile_url'], 'description' => '');
        }
    }
    return array_values($projects);
}
/** Resolve an unconfigured academic link to a real overview section. */
function cic_academic_link($title, $url = '') {
    if (trim((string) $url) !== '' && trim((string) $url) !== '#') return $url;
    $page = get_page_by_path('academics');
    $base = $page ? get_permalink($page) : home_url('/academics/');
    if (preg_match('/project|research highlight/i', $title)) return $base . '#projects';
    if (preg_match('/course/i', $title)) return $base . '#courses';
    if (preg_match('/curriculum|time.?table|schedule|calendar|admission|resource/i', $title)) return $base . '#resources';
    return $base;
}

/** Connect existing placeholder links without changing administrator destinations. */
function cic_connect_academic_links() {
    $settings = cic_get_header_settings();
    $connect = function (&$items) use (&$connect) {
        foreach ($items as &$item) {
            $item['url'] = cic_academic_link($item['title'] ?? '', $item['url'] ?? '');
            if (!empty($item['subheadings'])) $connect($item['subheadings']);
            if (!empty($item['sub_subheadings'])) $connect($item['sub_subheadings']);
        }
        unset($item);
    };
    foreach ($settings['nav_headings'] as &$heading) {
        if (stripos($heading['title'], 'academic') !== false) {
            $items = array($heading);
            $connect($items);
            $heading = $items[0];
        } elseif (stripos($heading['title'], 'research') !== false && !empty($heading['subheadings'])) {
            foreach ($heading['subheadings'] as &$sub) {
                if (stripos($sub['title'], 'project') !== false) $sub['url'] = cic_academic_link($sub['title'], $sub['url'] ?? '');
            }
            unset($sub);
        }
    }
    unset($heading);
    update_option('cic_header_settings', $settings);
    $hero = cic_get_hero_settings();
    foreach ($hero['buttons'] as &$button) {
        if (preg_match('/programme|program|course|research highlight|project/i', $button['text'])) $button['url'] = cic_academic_link($button['text'], $button['url'] ?? '');
    }
    unset($button);
    update_option('cic_hero_settings', $hero);
    $footer = cic_get_footer_settings();
    foreach ($footer['academics_links'] as &$link) $link['url'] = cic_academic_link($link['title'], $link['url'] ?? '');
    unset($link);
    update_option('cic_footer_settings', $footer);
}

function cic_ensure_academics_page() {
    if (!current_user_can('manage_options')) return;
    $page = get_page_by_path('academics');
    if (!$page) wp_insert_post(array('post_type' => 'page', 'post_status' => 'publish', 'post_title' => 'Academics & Research', 'post_name' => 'academics'));
    if (!get_option('cic_academic_routes_v2')) {
        flush_rewrite_rules(false);
        update_option('cic_academic_routes_v2', 1);
    }
    if (!get_option('cic_academic_links_v1')) {
        cic_connect_academic_links();
        update_option('cic_academic_links_v1', 1);
    }
}
add_action('admin_init', 'cic_ensure_academics_page');
