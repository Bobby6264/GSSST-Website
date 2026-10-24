<?php
/** Shared social link editor for department and faculty profiles. */
if (!defined('ABSPATH')) exit;

/** Pick an icon from a fixed list; custom link labels use the link icon. */
function cic_faculty_social_icon($label) {
    $name = strtolower(trim((string) $label));
    $icons = array(
        'linkedin' => 'fab fa-linkedin-in',
        'youtube' => 'fab fa-youtube',
        'instagram' => 'fab fa-instagram',
        'facebook' => 'fab fa-facebook-f',
        'twitter' => 'fab fa-twitter',
        'x' => 'fab fa-twitter',
        'github' => 'fab fa-github',
        'google scholar' => 'fas fa-graduation-cap',
        'orcid' => 'fab fa-orcid',
        'website' => 'fas fa-globe',
    );
    return isset($icons[$name]) ? $icons[$name] : 'fas fa-link';
}

function cic_social_platforms() {
    return array('Instagram', 'LinkedIn', 'Twitter', 'YouTube', 'GitHub', 'Google Scholar', 'Facebook', 'ORCID', 'Website');
}
function cic_clean_social_links($input) {
    $links = array();
    foreach (is_array($input) ? $input : array() as $link) {
        if (!is_array($link)) continue;
        $label = sanitize_text_field(($link['label'] ?? '') === 'Custom' ? ($link['custom_label'] ?? '') : ($link['label'] ?? ''));
        $url = esc_url_raw($link['url'] ?? '', array('https', 'http'));
        if (!$label || !$url || !wp_parse_url($url, PHP_URL_HOST)) continue;
        $image = absint($link['icon_image'] ?? 0);
        if ($image && !wp_attachment_is_image($image)) $image = 0;
        $links[] = array('label' => $label, 'url' => $url, 'icon_image' => $image);
    }
    return $links;
}
function cic_social_editor($name, $links) {
    $links = is_array($links) ? $links : array();
    ?>
    <div class="cic-social-editor" data-name="<?php echo esc_attr($name); ?>">
        <div class="cic-social-rows">
        <?php foreach ($links as $index => $link) cic_social_editor_row($name, $index, $link); ?>
        </div>
        <template><?php cic_social_editor_row($name, '__INDEX__', array()); ?></template>
        <button type="button" class="button cic-social-add">Add link</button>
        <p class="description">Choose a platform for its default icon. For another website, select Custom and choose an image as its symbol.</p>
    </div>
    <?php
}
function cic_social_editor_row($name, $index, $link) {
    $prefix = $name . '[' . $index . ']';
    $label = $link['label'] ?? 'LinkedIn';
    $custom = !in_array($label, cic_social_platforms(), true);
    ?>
    <div class="cic-social-editor-row">
        <span class="cic-social-preview"><?php echo cic_social_symbol(array_merge($link, array('label' => $label))); ?></span>
        <select name="<?php echo esc_attr($prefix); ?>[label]" aria-label="Platform">
            <?php foreach (array_merge(cic_social_platforms(), array('Custom')) as $platform) : ?>
            <option data-icon="<?php echo esc_attr(cic_faculty_social_icon($platform)); ?>" <?php selected($custom ? 'Custom' : $label, $platform); ?>><?php echo esc_html($platform); ?></option>
            <?php endforeach; ?>
        </select>
        <input type="text" name="<?php echo esc_attr($prefix); ?>[custom_label]" value="<?php echo esc_attr($custom ? $label : ''); ?>" placeholder="Custom name" aria-label="Custom link name">
        <input type="url" name="<?php echo esc_attr($prefix); ?>[url]" value="<?php echo esc_attr($link['url'] ?? ''); ?>" placeholder="https://" aria-label="Link URL">
        <input type="hidden" class="cic-social-image" name="<?php echo esc_attr($prefix); ?>[icon_image]" value="<?php echo absint($link['icon_image'] ?? 0); ?>">
        <button type="button" class="button cic-social-image-picker"><?php echo empty($link['icon_image']) ? 'Choose symbol' : 'Change symbol'; ?></button>
        <button type="button" class="button cic-social-image-clear">Default icon</button>
        <button type="button" class="button cic-social-remove">Remove link</button>
    </div>
    <?php
}
function cic_social_symbol($link) {
    $image = absint($link['icon_image'] ?? 0);
    if ($image) {
        $html = wp_get_attachment_image($image, 'thumbnail', false, array('class' => 'cic-social-symbol', 'alt' => ''));
        if ($html) return $html;
    }
    return '<i class="' . esc_attr(cic_faculty_social_icon($link['label'] ?? '')) . '" aria-hidden="true"></i>';
}
add_action('admin_menu', function () {
    add_submenu_page('cic-landing-hero', 'Social links', 'Social Links', 'manage_options', 'cic-social-links', function () {
        echo '<div class="wrap"><h1>Department social links</h1><form action="options.php" method="post">';
        settings_fields('cic_social_links');
        cic_social_editor('cic_department_social_links', get_option('cic_department_social_links', array()));
        submit_button();
        echo '</form></div>';
    });
});
add_action('admin_init', function () {
    register_setting('cic_social_links', 'cic_department_social_links', array('sanitize_callback' => 'cic_clean_social_links', 'default' => array()));
});
add_action('admin_enqueue_scripts', function ($hook) {
    if (!in_array($hook, array('profile.php', 'user-edit.php'), true) && strpos($hook, 'cic-social-links') === false) return;
    wp_enqueue_media();
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), '6.4.0');
    wp_enqueue_script('cic-social-editor', get_template_directory_uri() . '/inc/admin/assets/social-editor.js', array('jquery'), filemtime(__DIR__ . '/admin/assets/social-editor.js'), true);
});
