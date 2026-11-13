<?php
/** Import the five public source profiles. Run explicitly via Docker, never on a page render.
 * Stage source photos named {slug}.png and manifest.json in /tmp/gssst-staff-import.
 * Existing descriptions, nonempty directory fields and photos are preserved.
 */
if (PHP_SAPI !== 'cli') exit;
require '/var/www/html/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/image.php';
$directory = getenv('GSSST_STAFF_IMPORT_DIR') ?: '/tmp/gssst-staff-import';
$records = json_decode(file_get_contents($directory . '/manifest.json'), true, 512, JSON_THROW_ON_ERROR);
if (count($records) !== 5) throw new RuntimeException('Expected the five source staff records.');
foreach ($records as $record) {
    if (!preg_match('/^[a-z0-9-]+$/', $record['slug']) || !is_file($directory . '/' . $record['slug'] . '.png') || !getimagesize($directory . '/' . $record['slug'] . '.png')) throw new RuntimeException('Missing or invalid staff photo.');
}
foreach ($records as $record) {
    $matches = get_posts(array('post_type' => 'cic_staff', 'post_status' => array('publish', 'draft', 'private', 'pending'), 'numberposts' => -1, 'meta_key' => '_cic_staff_source', 'meta_value' => $record['source']));
    if (!$matches) {
        $matches = array_filter(get_posts(array('post_type' => 'cic_staff', 'post_status' => array('publish', 'draft', 'private', 'pending'), 'numberposts' => -1)), function ($post) use ($record) {
            $data = (array) get_post_meta($post->ID, 'cic_directory_record', true);
            return strcasecmp(trim($post->post_title), $record['name']) === 0 || (!empty($data['email']) && strcasecmp($data['email'], $record['email']) === 0);
        });
    }
    if (count($matches) > 1) throw new RuntimeException('Multiple existing matches for ' . $record['name']);
    $existing = $matches ? reset($matches) : null;
    $id = $existing ? $existing->ID : wp_insert_post(array('post_type' => 'cic_staff', 'post_status' => 'publish', 'post_title' => $record['name'], 'post_name' => $record['slug']), true);
    if (is_wp_error($id)) throw new RuntimeException($id->get_error_message());
    $data = (array) get_post_meta($id, 'cic_directory_record', true);
    foreach (array('role', 'email', 'phone', 'category') as $field) {
        if (empty($data[$field])) $data[$field] = $field === 'email' ? sanitize_email($record[$field]) : sanitize_text_field($record[$field]);
    }
    update_post_meta($id, 'cic_directory_record', $data);
    update_post_meta($id, '_cic_staff_source', esc_url_raw($record['source']));
    if (!has_post_thumbnail($id)) {
        $source = $directory . '/' . $record['slug'] . '.png';
        $temporary = wp_tempnam(basename($source));
        if (!$temporary || !copy($source, $temporary)) throw new RuntimeException('Unable to stage photo.');
        $attachment = media_handle_sideload(array('name' => basename($source), 'tmp_name' => $temporary), $id, $record['name']);
        if (is_wp_error($attachment)) { @unlink($temporary); throw new RuntimeException($attachment->get_error_message()); }
        update_post_meta($attachment, '_wp_attachment_image_alt', $record['name']);
        update_post_meta($attachment, '_cic_staff_source_photo', esc_url_raw($record['photo']));
        set_post_thumbnail($id, $attachment);
    }
    echo $record['name'] . ': record ' . $id . ', photo ' . get_post_thumbnail_id($id) . PHP_EOL;
}
