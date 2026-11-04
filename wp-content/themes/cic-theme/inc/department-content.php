<?php
/** Administrator-maintained public directories. Records do not create login accounts. */
if (!defined('ABSPATH')) exit;

function cic_directory_types() {
    return array(
        'cic_alumni' => array('label' => 'Alumni', 'slug' => 'alumni', 'fields' => array(
            'category' => array('Category', 'select', array('faculty' => 'Former Faculty', 'students' => 'Former Students', 'staff' => 'Former Staff')),
            'programme_role' => array('Programme / former role', 'text'), 'years' => array('Years / graduating batch', 'text'),
            'affiliation' => array('Current affiliation', 'text'), 'url' => array('Public profile link (optional)', 'url'),
        )),
        'cic_staff' => array('label' => 'Staff & Administration', 'slug' => 'staff', 'fields' => array(
            'role' => array('Role / designation', 'text'), 'office' => array('Office / location', 'text'),
            'email' => array('Official email', 'email'), 'phone' => array('Official phone', 'text'),
        )),
        'cic_lab' => array('label' => 'Laboratories & Facilities', 'slug' => 'laboratories', 'fields' => array(
            'facilities' => array('Facilities / equipment', 'textarea'), 'lead' => array('Faculty in charge', 'text'),
            'url' => array('Lab website (optional)', 'url'),
        )),
        'cic_research_area' => array('label' => 'Research Areas', 'slug' => 'research', 'fields' => array(
            'faculty_ids' => array('Associated faculty', 'faculty'),
        )),
    );
}

/** Mark parent menus for any linked department page, including custom menu entries. */
function cic_nav_item_is_current($item) {
    if (!is_singular()) return false;
    $url = $item['url'] ?? '';
    $parts = wp_parse_url($url);
    $current = wp_parse_url(is_singular('cic_job') ? home_url('/jobs/') : get_permalink(get_queried_object_id()));
    if ($url && $url[0] !== '#' && empty($parts['fragment']) && !empty($parts['path']) &&
        (empty($parts['host']) || ($parts['host'] === ($current['host'] ?? '') && ($parts['port'] ?? null) === ($current['port'] ?? null))) &&
        rtrim($parts['path'], '/') === rtrim($current['path'] ?? '', '/')) return true;
    foreach (array('subheadings', 'sub_subheadings') as $key) {
        foreach ($item[$key] ?? array() as $child) if (cic_nav_item_is_current($child)) return true;
    }
    return false;
}

add_action('init', function () {
    $caps = array_fill_keys(array('edit_post', 'edit_posts', 'edit_others_posts', 'publish_posts', 'read_private_posts', 'delete_post', 'delete_posts', 'delete_private_posts', 'delete_published_posts', 'delete_others_posts', 'edit_private_posts', 'edit_published_posts', 'create_posts'), 'manage_options');
    $caps['read_post'] = 'read';
    foreach (cic_directory_types() as $type => $config) {
        register_post_type($type, array(
            'labels' => array('name' => $config['label'], 'singular_name' => $config['label'] . ' record', 'add_new_item' => 'Add ' . $config['label'] . ' record'),
            'public' => false, 'show_ui' => true, 'show_in_rest' => true,
            'publicly_queryable' => false, 'rewrite' => false, 'query_var' => false,
            'capabilities' => $caps, 'map_meta_cap' => false,
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
            'menu_icon' => $type === 'cic_lab' ? 'dashicons-building' : 'dashicons-groups',
        ));
    }
});

add_action('add_meta_boxes', function () {
    foreach (cic_directory_types() as $type => $config) {
        add_meta_box('cic_directory_details', 'Directory details', 'cic_directory_record_form', $type);
    }
});

function cic_directory_record_form($post) {
    $config = cic_directory_types()[$post->post_type];
    $data = (array) get_post_meta($post->ID, 'cic_directory_record', true);
    wp_nonce_field('cic_directory_record', 'cic_directory_nonce');
    echo '<p>Use the title for the name, the editor for the description, and Featured image for the photo. Publish only information intended for the public directory.</p>';
    foreach ($config['fields'] as $key => $field) {
        $id = 'cic_directory_' . $key;
        $name = 'cic_directory_record[' . $key . ']';
        echo '<p><label for="' . esc_attr($id) . '"><strong>' . esc_html($field[0]) . '</strong></label><br>';
        if ($field[1] === 'select') {
            echo '<select id="' . esc_attr($id) . '" name="' . esc_attr($name) . '">';
            foreach ($field[2] as $value => $label) echo '<option value="' . esc_attr($value) . '" ' . selected($data[$key] ?? 'students', $value, false) . '>' . esc_html($label) . '</option>';
            echo '</select>';
        } elseif ($field[1] === 'faculty') {
            echo '<select multiple size="6" class="widefat" id="' . esc_attr($id) . '" name="' . esc_attr($name) . '[]">';
            foreach (get_users(array('role' => 'faculty', 'orderby' => 'display_name')) as $faculty) {
                echo '<option value="' . esc_attr($faculty->ID) . '" ' . selected(in_array($faculty->ID, array_map('intval', (array) ($data[$key] ?? array())), true), true, false) . '>' . esc_html($faculty->display_name) . '</option>';
            }
            echo '</select><span class="description">Hold Ctrl or Command to select multiple faculty. Leave empty to match the research area name against current faculty profiles automatically.</span>';
        } elseif ($field[1] === 'textarea') {
            echo '<textarea rows="4" class="widefat" id="' . esc_attr($id) . '" name="' . esc_attr($name) . '">' . esc_textarea($data[$key] ?? '') . '</textarea>';
        } else {
            echo '<input class="widefat" id="' . esc_attr($id) . '" type="' . esc_attr($field[1]) . '" name="' . esc_attr($name) . '" value="' . esc_attr($data[$key] ?? '') . '">';
        }
        echo '</p>';
    }
}

add_action('save_post', function ($id) {
    $types = cic_directory_types();
    $type = get_post_type($id);
    if (!isset($types[$type]) || !current_user_can('manage_options') || !current_user_can('edit_post', $id) || wp_is_post_revision($id) || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)) return;
    if (!isset($_POST['cic_directory_nonce']) || !is_string($_POST['cic_directory_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['cic_directory_nonce'])), 'cic_directory_record')) return;
    $input = isset($_POST['cic_directory_record']) && is_array($_POST['cic_directory_record']) ? wp_unslash($_POST['cic_directory_record']) : array();
    $clean = array();
    foreach ($types[$type]['fields'] as $key => $field) {
        $value = $input[$key] ?? '';
        if ($field[1] === 'faculty') {
            $clean[$key] = array_values(array_filter(array_unique(array_map('absint', (array) $value)), function ($user_id) {
                $user = get_userdata($user_id);
                return $user && in_array('faculty', (array) $user->roles, true);
            }));
        } else {
            $value = is_string($value) ? $value : '';
            if ($field[1] === 'select') $clean[$key] = isset($field[2][$value]) ? $value : 'students';
            elseif ($field[1] === 'url') $clean[$key] = esc_url_raw($value, array('http', 'https'));
            elseif ($field[1] === 'email') $clean[$key] = sanitize_email($value);
            elseif ($field[1] === 'textarea') $clean[$key] = sanitize_textarea_field($value);
            else $clean[$key] = sanitize_text_field($value);
        }
    }
    update_post_meta($id, 'cic_directory_record', $clean);
});

function cic_research_area_faculty($area, $faculty) {
    $data = (array) get_post_meta($area->ID, 'cic_directory_record', true);
    $ids = array_map('intval', (array) ($data['faculty_ids'] ?? array()));
    $normalize = function ($value) { return strtolower(trim(preg_replace('/\s+/', ' ', $value))); };
    return array_values(array_filter($faculty, function ($member) use ($ids, $area, $normalize) {
        if ($ids) return in_array((int) $member['id'], $ids, true);
        foreach ($member['research_areas'] as $name) {
            if ($normalize($name) === $normalize($area->post_title)) return true;
        }
        return false;
    }));
}

add_action('wp_enqueue_scripts', function () {
    if (is_page(array('alumni', 'staff', 'laboratories', 'research')) || is_page_template('page-department-directory.php') || is_page_template('page-programme.php') || is_page_template('page-academic-resource.php')) {
        wp_enqueue_style('cic-department-content', get_template_directory_uri() . '/assets/css/department-content.css', array('cic-main-style'), filemtime(get_template_directory() . '/assets/css/department-content.css'));
    }
    if (is_page_template('page-academic-resource.php')) {
        wp_enqueue_script('cic-academic-resource', get_template_directory_uri() . '/assets/js/academic-resource.js', array(), filemtime(get_template_directory() . '/assets/js/academic-resource.js'), true);
    }
});
