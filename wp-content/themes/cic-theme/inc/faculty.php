<?php
/**
 * Faculty Management & Data Helper
 *
 * Handles faculty role querying, metadata, seeding, profile fields,
 * and database photo resolution.
 *
 * @package cic-theme
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Generate a local, elegant SVG avatar with initials for faculty without an uploaded photo.
 * Ensures 100% self-contained rendering with ZERO internet dependencies.
 *
 * @param string $name Faculty display name.
 * @return string Data URI of the SVG avatar.
 */
function cic_get_faculty_initials_avatar($name) {
    // Extract initials (e.g. "Prof. Vikram Desai" -> "VD", "Dr. Elena Rostova" -> "ER")
    $clean_name = trim(preg_replace('/^(Prof\.|Dr\.|Mr\.|Ms\.|Mrs\.)\s+/i', '', $name));
    $parts = preg_split('/\s+/', $clean_name);
    $initials = '';
    if (count($parts) >= 2) {
        $initials = mb_substr($parts[0], 0, 1) . mb_substr($parts[count($parts) - 1], 0, 1);
    } elseif (!empty($parts[0])) {
        $initials = mb_substr($parts[0], 0, 2);
    } else {
        $initials = 'FA';
    }
    $initials = strtoupper($initials);

    // Pick consistent subtle colors based on initials
    $hash = crc32($initials);
    $gradients = array(
        array('#0f172a', '#1e3a8a'),
        array('#1e293b', '#2563eb'),
        array('#1e1b4b', '#3730a3'),
        array('#064e3b', '#0d9488'),
        array('#312e81', '#1d4ed8'),
    );
    $c = $gradients[abs($hash) % count($gradients)];

    $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 400" width="400" height="400">' .
        '<defs>' .
        '<linearGradient id="grad" x1="0%" y1="0%" x2="100%" y2="100%">' .
        '<stop offset="0%" stop-color="' . $c[0] . '"/>' .
        '<stop offset="100%" stop-color="' . $c[1] . '"/>' .
        '</linearGradient>' .
        '</defs>' .
        '<rect width="400" height="400" fill="url(#grad)"/>' .
        '<circle cx="200" cy="200" r="160" fill="none" stroke="rgba(255,255,255,0.12)" stroke-width="2"/>' .
        '<path d="M200 80 L260 115 L200 150 L140 115 Z" fill="none" stroke="rgba(255,255,255,0.2)" stroke-width="2"/>' .
        '<path d="M165 130 L165 170 Q200 195 235 170 L235 130" fill="none" stroke="rgba(255,255,255,0.2)" stroke-width="2"/>' .
        '<text x="200" y="245" font-family="-apple-system, BlinkMacSystemFont, Segoe UI, Roboto, sans-serif" font-size="82" font-weight="700" fill="#ffffff" text-anchor="middle" letter-spacing="4">' . esc_html($initials) . '</text>' .
        '</svg>';

    return 'data:image/svg+xml;utf8,' . rawurlencode($svg);
}

/**
 * Retrieve all faculty members from the WordPress database.
 * Queries all users assigned the 'faculty' role.
 *
 * @return array Array of formatted faculty member associative arrays.
 */
function cic_get_faculty_members() {
    $args = array(
        'role'    => 'faculty',
        'orderby' => 'display_name',
        'order'   => 'ASC',
    );

    $users = get_users($args);
    $faculty_list = array();

    foreach ($users as $user) {
        $user_id = $user->ID;
        $name    = $user->display_name ?: trim($user->first_name . ' ' . $user->last_name);
        if (empty($name)) {
            $name = $user->user_login;
        }

        $designation = get_user_meta($user_id, 'designation', true);
        if (empty($designation)) {
            $designation = 'Faculty Member';
        }

        $email  = $user->user_email;
        $phone  = get_user_meta($user_id, 'phone', true);
        $office = get_user_meta($user_id, 'office', true);
        $dept   = get_user_meta($user_id, 'department', true) ?: 'G.S. Sanyal School of Telecommunication';

        // Resolve photo from database or local assets (ZERO external internet images)
        $photo_url = '';
        $raw_photo = get_user_meta($user_id, 'faculty_photo', true);

        if (!empty($raw_photo)) {
            if (is_numeric($raw_photo)) {
                $img_src = wp_get_attachment_image_url((int) $raw_photo, 'large');
                if ($img_src) {
                    $photo_url = $img_src;
                }
            } elseif (strpos($raw_photo, 'http') === 0) {
                // If it points to local host/media, use it
                $photo_url = $raw_photo;
            } elseif ($raw_photo === 'head.jpg') {
                $photo_url = get_template_directory_uri() . '/assets/images/head.jpg';
            }
        }

        // If still no photo, generate clean initials academic avatar (no internet requests)
        if (empty($photo_url)) {
            $photo_url = cic_get_faculty_initials_avatar($name);
        }

        // Bio & Research Areas
        $bio = $user->description ?: '';
        $raw_research = get_user_meta($user_id, 'research_areas', true);
        $research_areas = array();
        if (!empty($raw_research)) {
            if (is_array($raw_research)) {
                $research_areas = $raw_research;
            } else {
                $research_areas = array_map('trim', explode(',', $raw_research));
            }
        }

        $education = get_user_meta($user_id, 'education', true);
        $cover_id = absint(get_user_meta($user_id, 'faculty_cover_image', true));
        $cover_url = $cover_id ? wp_get_attachment_image_url($cover_id, 'full') : '';
        $social_links = get_user_meta($user_id, 'faculty_social_links', true);
        if (!is_array($social_links)) $social_links = array();
        $erp_areas = get_user_meta($user_id, 'erp_research_areas', true);
        if (is_array($erp_areas) && $erp_areas) $research_areas = $erp_areas;

        // Priority weighting for sorting:
        // Head / Professor & Head -> 1
        // Professor -> 2
        // Associate Professor -> 3
        // Assistant Professor -> 4
        // Others -> 5
        $prio = 5;
        $desig_lower = strtolower($designation);
        if (strpos($desig_lower, 'head') !== false) {
            $prio = 1;
        } elseif (strpos($desig_lower, 'associate') !== false) {
            $prio = 3;
        } elseif (strpos($desig_lower, 'assistant') !== false) {
            $prio = 4;
        } elseif (strpos($desig_lower, 'professor') !== false) {
            $prio = 2;
        }

        $faculty_list[] = array(
            'id'             => $user_id,
            'username'       => $user->user_login,
            'name'           => $name,
            'designation'    => $designation,
            'priority'       => $prio,
            'email'          => $email,
            'phone'          => $phone,
            'office'         => $office,
            'department'     => $dept,
            'photo_url'      => $photo_url,
            'bio'            => $bio,
            'research_areas' => $research_areas,
            'education'      => $education,
            'cover_url'      => $cover_url,
            'social_links'   => $social_links,
            'research_statement' => get_user_meta($user_id, 'erp_research_statement', true),
            'selected_publications' => array_filter((array) get_user_meta($user_id, 'erp_selected_publications', true)),
            'current_projects' => array_filter((array) get_user_meta($user_id, 'erp_current_projects', true)),
            'iitkgp_profile_url' => get_user_meta($user_id, 'iitkgp_profile_url', true),
            'website_url'    => get_user_meta($user_id, 'website_url', true),
            'profile_url'    => add_query_arg('member', $user_id, home_url('/faculty/')),
        );
    }

    // Sort by priority first (Head -> Professor -> Associate -> Assistant), then by Name
    usort($faculty_list, function($a, $b) {
        if ($a['priority'] === $b['priority']) {
            return strcmp($a['name'], $b['name']);
        }
        return ($a['priority'] < $b['priority']) ? -1 : 1;
    });

    return $faculty_list;
}

/**
 * Retrieve a single faculty member by ID or username/slug.
 *
 * @param int|string $id_or_slug Member user ID or login slug.
 * @return array|null Faculty array or null if not found.
 */
function cic_get_faculty_member($id_or_slug) {
    $members = cic_get_faculty_members();
    foreach ($members as $m) {
        if ((string)$m['id'] === (string)$id_or_slug || strcasecmp($m['username'], (string)$id_or_slug) === 0) {
            return $m;
        }
    }
    return null;
}



/** Map older free-text faculty titles to the administrator's fixed choices. */
function cic_faculty_designation_key($designation) {
    $title = strtolower(trim((string) $designation));
    if (strpos($title, 'head') !== false) return 'head';
    if (strpos($title, 'associate') !== false) return 'associate';
    if (strpos($title, 'assistant') !== false) return 'assistant';
    if (strpos($title, 'professor') !== false) return 'professor';
    return '';
}

function cic_faculty_designation_choices() {
    return array(
        'head' => 'Head of School',
        'professor' => 'Professor',
        'associate' => 'Associate Professor',
        'assistant' => 'Assistant Professor',
    );
}

function cic_current_user_is_school_admin() {
    return current_user_can('manage_options') && in_array('administrator', (array) wp_get_current_user()->roles, true);
}

/**
 * Add custom Faculty Profile Fields to WordPress admin profile screen.
 * Allows admin to edit designation, phone, office, and upload photos.
 */
function cic_show_faculty_user_fields($user) {
    if (!current_user_can('edit_user', $user->ID)) {
        return;
    }
    $designation    = get_user_meta($user->ID, 'designation', true);
    $phone          = get_user_meta($user->ID, 'phone', true);
    $office         = get_user_meta($user->ID, 'office', true);
    $department     = get_user_meta($user->ID, 'department', true) ?: 'G.S. Sanyal School of Telecommunication';
    $research_areas = get_user_meta($user->ID, 'research_areas', true);
    $education      = get_user_meta($user->ID, 'education', true);
    $photo_id       = get_user_meta($user->ID, 'faculty_photo', true);
    $cover_id       = absint(get_user_meta($user->ID, 'faculty_cover_image', true));
    $cover_preview  = $cover_id ? wp_get_attachment_image_url($cover_id, 'large') : '';
    $social_links   = get_user_meta($user->ID, 'faculty_social_links', true);
    if (!is_array($social_links)) $social_links = array();
    $photo_preview  = '';
    if (is_numeric($photo_id)) {
        $photo_preview = wp_get_attachment_image_url((int)$photo_id, 'thumbnail');
    } elseif ($photo_id === 'head.jpg') {
        $photo_preview = get_template_directory_uri() . '/assets/images/head.jpg';
    }
    ?>
    <h2>Faculty Details</h2>
    <table class="form-table">
        <tr>
            <th><label for="designation">Academic Designation</label></th>
            <td>
                <?php if (cic_current_user_is_school_admin() && in_array('faculty', (array) $user->roles, true)) : ?>
                    <select name="designation" id="designation">
                        <option value="">Select designation</option>
                        <?php foreach (cic_faculty_designation_choices() as $key => $label) : ?>
                            <option value="<?php echo esc_attr($key); ?>" <?php selected(cic_faculty_designation_key($designation), $key); ?>><?php echo esc_html($label); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <p class="description">Only administrators can change this. Choosing Head of School replaces the current head.</p>
                <?php else : ?>
                    <strong id="designation"><?php echo esc_html($designation ?: 'Not assigned'); ?></strong>
                    <p class="description">The school administrator manages this designation.</p>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <th><label for="department">Department</label></th>
            <td>
                <input type="text" name="department" id="department" value="<?php echo esc_attr($department); ?>" class="regular-text">
            </td>
        </tr>
        <tr>
            <th><label for="phone">Phone Number</label></th>
            <td>
                <input type="text" name="phone" id="phone" value="<?php echo esc_attr($phone); ?>" class="regular-text" placeholder="+91-3222-281001">
            </td>
        </tr>
        <tr>
            <th><label for="office">Office / Cabin Location</label></th>
            <td>
                <input type="text" name="office" id="office" value="<?php echo esc_attr($office); ?>" class="regular-text" placeholder="Takshashila Building, Room 204">
            </td>
        </tr>
        <tr>
            <th><label for="research_areas">Research Areas</label></th>
            <td>
                <input type="text" name="research_areas" id="research_areas" value="<?php echo esc_attr($research_areas); ?>" class="large-text" placeholder="e.g. Wireless Networks, 6G, IoT, AI/ML (comma-separated)">
            </td>
        </tr>
        <tr>
            <th><label for="education">Education & Degrees</label></th>
            <td>
                <input type="text" name="education" id="education" value="<?php echo esc_attr($education); ?>" class="large-text" placeholder="e.g. Ph.D., IIT Kharagpur; M.Tech., IIT Bombay">
            </td>
        </tr>
        <tr>
            <th><label for="website_url">Personal Website URL</label></th>
            <td>
                <input type="url" name="website_url" id="website_url" value="<?php echo esc_attr(get_user_meta($user->ID, 'website_url', true)); ?>" class="regular-text" placeholder="https://example.com">
                <p class="description">Link to your personal homepage or Google Scholar profile.</p>
            </td>
        </tr>
        <tr>
            <th><label for="iitkgp_profile_url">IIT Kharagpur Profile URL</label></th>
            <td>
                <input type="url" name="iitkgp_profile_url" id="iitkgp_profile_url" value="<?php echo esc_attr(get_user_meta($user->ID, 'iitkgp_profile_url', true)); ?>" class="regular-text" placeholder="https://www.iitkgp.ac.in/...?">
                <p class="description">Paste the official IIT Kharagpur faculty page, then fetch its research information.</p>
                <input type="hidden" id="cic-erp-user-id" value="<?php echo esc_attr($user->ID); ?>">
                <button type="button" class="button button-primary" id="cic-preview-erp-profile">Fetch research information</button>
                <span id="cic-erp-preview-status" class="description" aria-live="polite"></span>
                <div id="cic-erp-preview" class="cic-erp-preview" hidden></div>
            </td>
        </tr>
        <tr>
            <th><label for="faculty_photo">Profile Photo</label></th>
            <td>
                <input type="hidden" name="faculty_photo" id="faculty_photo" value="<?php echo esc_attr($photo_id); ?>">
                <div id="faculty-photo-preview" style="margin-bottom: 10px;">
                    <?php if (!empty($photo_preview)) : ?>
                        <img src="<?php echo esc_url($photo_preview); ?>" style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px; border: 1px solid #cbd5e1; display: block;">
                    <?php else : ?>
                        <div style="width: 100px; height: 100px; background: #e2e8f0; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #64748b; font-size: 11px; text-align: center; padding: 4px;">No Photo</div>
                    <?php endif; ?>
                </div>
                <button type="button" class="button" id="cic-upload-photo-btn">Choose Photo from Media Library</button>
                <button type="button" class="button" id="cic-remove-photo-btn" <?php echo empty($photo_preview) ? 'style="display:none;"' : ''; ?>>Remove Photo</button>
                <p class="description">Select or upload a photo directly to the WordPress database/media library.</p>
            </td>
        </tr>
        <tr>
            <th><label for="faculty_cover_image">Profile Cover Image</label></th>
            <td>
                <input type="hidden" name="faculty_cover_image" id="faculty_cover_image" value="<?php echo esc_attr($cover_id); ?>">
                <div id="faculty-cover-preview" class="cic-cover-preview"<?php if ($cover_preview) : ?> style="background-image:url('<?php echo esc_url($cover_preview); ?>')"<?php endif; ?>></div>
                <button type="button" class="button" id="cic-upload-cover-btn">Choose Cover Image</button>
                <button type="button" class="button" id="cic-remove-cover-btn" <?php echo $cover_id ? '' : 'style="display:none;"'; ?>>Remove Cover</button>
                <p class="description">A wide landscape photo works best. The default navy design appears when no image is selected.</p>
            </td>
        </tr>
        <tr>
            <th>Social &amp; Web Links</th>
            <td>
                <div id="cic_social_links"><?php cic_social_editor('cic_social_links', $social_links); ?></div>
            </td>
        </tr>
    </table>
    <script>
    jQuery(document).ready(function($){
        var frame;
        $('#cic-upload-photo-btn').on('click', function(e){
            e.preventDefault();
            if (frame) { frame.open(); return; }
            frame = wp.media({
                title: 'Select Faculty Profile Photo',
                button: { text: 'Use this photo' },
                multiple: false
            });
            frame.on('select', function(){
                var attachment = frame.state().get('selection').first().toJSON();
                $('#faculty_photo').val(attachment.id);
                var thumb = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
                $('#faculty-photo-preview').html('<img src="' + thumb + '" style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px; border: 1px solid #cbd5e1; display: block;">');
                $('#cic-remove-photo-btn').show();
            });
            frame.open();
        });
        $('#cic-remove-photo-btn').on('click', function(e){
            e.preventDefault();
            $('#faculty_photo').val('');
            $('#faculty-photo-preview').html('<div style="width: 100px; height: 100px; background: #e2e8f0; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #64748b; font-size: 11px; text-align: center; padding: 4px;">No Photo</div>');
            $(this).hide();
        });

        var coverFrame;
        $('#cic-upload-cover-btn').on('click', function(e){
            e.preventDefault();
            if (coverFrame) { coverFrame.open(); return; }
            coverFrame = wp.media({title: 'Select Profile Cover Image', button: {text: 'Use as cover'}, library: {type: 'image'}, multiple: false});
            coverFrame.on('select', function(){
                var attachment = coverFrame.state().get('selection').first().toJSON();
                $('#faculty_cover_image').val(attachment.id);
                $('#faculty-cover-preview').css('background-image', 'url("' + attachment.url + '")');
                $('#cic-remove-cover-btn').show();
            });
            coverFrame.open();
        });
        $('#cic-remove-cover-btn').on('click', function(e){
            e.preventDefault();
            $('#faculty_cover_image').val('');
            $('#faculty-cover-preview').css('background-image', 'none');
            $(this).hide();
        });

        $('#cic-preview-erp-profile').on('click', function(){
            var url = $('#iitkgp_profile_url').val();
            var $button = $(this);
            var $status = $('#cic-erp-preview-status');
            var $preview = $('#cic-erp-preview');
            if (!url) { $status.text('Enter the official profile URL first.'); return; }
            $status.text('Fetching research information…');
            $preview.prop('hidden', true).empty();
            $button.prop('disabled', true);
            $.post(ajaxurl, {
                action: 'cic_preview_erp_profile',
                nonce: '<?php echo esc_js(wp_create_nonce('cic_preview_erp_profile')); ?>',
                user_id: $('#cic-erp-user-id').val(),
                url: url
            }).done(function(response){
                if (!response || !response.success) {
                    $status.text(response && response.data && response.data.message ? response.data.message : 'Could not read this page.');
                    return;
                }
                var data = response.data;
                $status.text('Research information saved to this faculty profile.');
                var html = '<h3>Imported research information</h3><p class="cic-erp-preview-url"></p><dl></dl>';
                $preview.html(html);
                $preview.find('.cic-erp-preview-url').text(data.final_url || url);
                var $list = $preview.find('dl');
                $.each({
                    'Research Areas': data.research_areas,
                    'Research Statement': data.research_statement,
                    'Selected Publications': data.selected_publications,
                    'Current Projects': data.current_projects
                }, function(label, value){
                    $('<dt>').text(label).appendTo($list);
                    $('<dd>').text($.isArray(value) ? (value.length ? value.join(' • ') : 'No records found') : (value || 'No records found')).appendTo($list);
                });
                $preview.prop('hidden', false);
            }).fail(function(xhr){
                var message = xhr.responseJSON && xhr.responseJSON.data && xhr.responseJSON.data.message;
                $status.text(message || 'Could not fetch this page.');
            }).always(function(){ $button.prop('disabled', false); });
        });
    });
    </script>
    <?php
}
add_action('show_user_profile', 'cic_show_faculty_user_fields');
add_action('edit_user_profile', 'cic_show_faculty_user_fields');

function cic_save_faculty_user_fields($user_id) {
    if (!current_user_can('edit_user', $user_id)) {
        return;
    }
    $faculty_user = get_userdata($user_id);
    if (cic_current_user_is_school_admin() && $faculty_user && in_array('faculty', (array) $faculty_user->roles, true) && isset($_POST['designation'])) {
        $selected = sanitize_key(wp_unslash($_POST['designation']));
        $choices = cic_faculty_designation_choices();
        if (isset($choices[$selected])) {
            if ($selected === 'head') {
                foreach (get_users(array('role' => 'faculty', 'fields' => 'ID')) as $other_id) {
                    if ((int) $other_id !== (int) $user_id && cic_faculty_designation_key(get_user_meta($other_id, 'designation', true)) === 'head') {
                        update_user_meta($other_id, 'designation', $choices['professor']);
                    }
                }
            }
            update_user_meta($user_id, 'designation', $choices[$selected]);
        }
    }
    if (isset($_POST['department'])) {
        update_user_meta($user_id, 'department', sanitize_text_field($_POST['department']));
    }
    if (isset($_POST['phone'])) {
        update_user_meta($user_id, 'phone', sanitize_text_field($_POST['phone']));
    }
    if (isset($_POST['office'])) {
        update_user_meta($user_id, 'office', sanitize_text_field($_POST['office']));
    }
    if (isset($_POST['research_areas'])) {
        update_user_meta($user_id, 'research_areas', sanitize_text_field($_POST['research_areas']));
    }
    if (isset($_POST['education'])) {
        update_user_meta($user_id, 'education', sanitize_text_field($_POST['education']));
    }
    if (isset($_POST['website_url'])) {
        update_user_meta($user_id, 'website_url', esc_url_raw($_POST['website_url']));
    }
    if (isset($_POST['faculty_photo'])) {
        update_user_meta($user_id, 'faculty_photo', sanitize_text_field($_POST['faculty_photo']));
    }
    if (isset($_POST['faculty_cover_image'])) {
        $cover_id = absint($_POST['faculty_cover_image']);
        $old_cover_id = absint(get_user_meta($user_id, 'faculty_cover_image', true));
        if (!$cover_id || (wp_attachment_is_image($cover_id) && ($cover_id === $old_cover_id || current_user_can('edit_post', $cover_id)))) {
            update_user_meta($user_id, 'faculty_cover_image', $cover_id);
        }
    }
    $posted_links = isset($_POST['cic_social_links']) && is_array($_POST['cic_social_links']) ? wp_unslash($_POST['cic_social_links']) : array();
    update_user_meta($user_id, 'faculty_social_links', cic_clean_social_links($posted_links));
}
add_action('personal_options_update', 'cic_save_faculty_user_fields');
add_action('edit_user_profile_update', 'cic_save_faculty_user_fields');

/** Fetch a public IIT Kharagpur profile page and return a safe, read-only preview. */
function cic_preview_erp_profile() {
    check_ajax_referer('cic_preview_erp_profile', 'nonce');
    $user_id = isset($_POST['user_id']) ? absint($_POST['user_id']) : 0;
    if (!$user_id || !current_user_can('edit_user', $user_id)) wp_send_json_error(array('message' => 'You cannot update this faculty profile.'), 403);
    $url = isset($_POST['url']) ? esc_url_raw(wp_unslash($_POST['url'])) : '';
    $parts = wp_parse_url($url);
    $host = isset($parts['host']) ? strtolower($parts['host']) : '';
    $path = isset($parts['path']) ? $parts['path'] : '';
    if (!$url || !in_array($host, array('iitkgp.ac.in', 'www.iitkgp.ac.in'), true) || ($parts['scheme'] ?? '') !== 'https' || !preg_match('~^/department/[A-Za-z0-9_-]+/faculty/[A-Za-z0-9_-]+(?:\.[A-Za-z0-9_-]+)*/?$~', $path)) {
        wp_send_json_error(array('message' => 'Enter an HTTPS IIT Kharagpur faculty profile URL.'), 400);
    }
    $request_args = array(
        'timeout' => 20,
        'redirection' => 3,
        'limit_response_size' => 1500000,
        'user-agent' => 'Mozilla/5.0 (compatible; GSSST-Faculty-Profile/1.0)',
        'headers' => array('Accept' => 'text/html,application/xhtml+xml'),
    );
    $response = wp_safe_remote_get($url, $request_args);
    // The institute host sometimes drops the first connection; a second request succeeds.
    if (is_wp_error($response) && in_array($response->get_error_code(), array('http_request_failed', 'http_request_not_executed'), true)) {
        $response = wp_safe_remote_get($url, $request_args);
    }
    if (is_wp_error($response)) {
        wp_send_json_error(array('message' => 'Request failed: ' . $response->get_error_message()), 502);
    }
    $code = wp_remote_retrieve_response_code($response);
    $html = wp_remote_retrieve_body($response);
    if ($code < 200 || $code >= 300 || !$html) {
        wp_send_json_error(array('message' => 'The profile page returned HTTP ' . (int) $code . '.'), 502);
    }
    $data = cic_erp_parse_profile($html);
    if (is_wp_error($data)) wp_send_json_error(array('message' => $data->get_error_message()), 500);
    if (!$data['_found']['research_areas'] && !$data['_found']['research_statement'] && !$data['_found']['selected_publications'] && !$data['_found']['current_projects']) {
        wp_send_json_error(array('message' => 'The page loaded, but no research sections were found. Existing profile data was kept.'), 422);
    }
    // A section with "No Record Found" clears an older imported list.
    foreach (array('research_areas', 'research_statement', 'selected_publications', 'current_projects') as $key) {
        if ($data['_found'][$key]) update_user_meta($user_id, 'erp_' . $key, $data[$key]);
    }
    update_user_meta($user_id, 'iitkgp_profile_url', $url);
    update_user_meta($user_id, 'erp_last_fetched', current_time('mysql'));
    $data['final_url'] = $url;
    unset($data['_found']);
    wp_send_json_success($data);
}
add_action('wp_ajax_cic_preview_erp_profile', 'cic_preview_erp_profile');

function cic_save_erp_profile_url($user_id) {
    if (!current_user_can('edit_user', $user_id) || !isset($_POST['iitkgp_profile_url'])) return;
    update_user_meta($user_id, 'iitkgp_profile_url', esc_url_raw(wp_unslash($_POST['iitkgp_profile_url'])));
}
add_action('personal_options_update', 'cic_save_erp_profile_url');
add_action('edit_user_profile_update', 'cic_save_erp_profile_url');

/**
 * Render faculty photo or high-fidelity local academic SVG avatar.
 * Guarantees zero external internet requests and zero broken images.
 *
 * @param array $member Faculty member data array.
 * @param string $class CSS class for the image element.
 * @return string HTML img or svg string.
 */
function cic_render_faculty_photo($member, $class = 'faculty-card-photo') {
    $photo_url = !empty($member['photo_url']) ? $member['photo_url'] : '';
    $name = !empty($member['name']) ? $member['name'] : 'Faculty';

    if (!empty($photo_url) && (strpos($photo_url, 'http') === 0 || strpos($photo_url, '/') === 0)) {
        return sprintf(
            '<img src="%s" alt="%s" class="%s" loading="lazy">',
            esc_url($photo_url),
            esc_attr($name),
            esc_attr($class)
        );
    }

    // Extract initials (e.g. "Prof. Arundhati Sharma" -> "AS", "Dr. Elena Rostova" -> "ER")
    $clean_name = trim(preg_replace('/^(Prof\.|Dr\.|Mr\.|Ms\.|Mrs\.)\s+/i', '', $name));
    $parts = preg_split('/\s+/', $clean_name);
    $initials = '';
    if (count($parts) >= 2) {
        $initials = mb_substr($parts[0], 0, 1) . mb_substr($parts[count($parts) - 1], 0, 1);
    } elseif (!empty($parts[0])) {
        $initials = mb_substr($parts[0], 0, 2);
    } else {
        $initials = 'FA';
    }
    $initials = strtoupper($initials);

    $hash = crc32($initials);
    $gradients = array(
        array('#0f172a', '#1e3a8a'),
        array('#1e293b', '#2563eb'),
        array('#1e1b4b', '#3730a3'),
        array('#064e3b', '#0d9488'),
        array('#312e81', '#1d4ed8'),
        array('#7c2d12', '#ea580c'),
    );
    $c = $gradients[abs($hash) % count($gradients)];
    $grad_id = 'grad_' . substr(md5($name), 0, 8);

    return sprintf(
        '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 340" class="%s faculty-avatar-svg" style="width:100%%;height:100%%;display:block;">' .
        '<defs><linearGradient id="%s" x1="0%%" y1="0%%" x2="100%%" y2="100%%">' .
        '<stop offset="0%%" stop-color="%s"/><stop offset="100%%" stop-color="%s"/>' .
        '</linearGradient></defs>' .
        '<rect width="400" height="340" fill="url(#%s)"/>' .
        '<circle cx="200" cy="170" r="130" fill="none" stroke="rgba(255,255,255,0.12)" stroke-width="2"/>' .
        '<path d="M200 65 L260 98 L200 130 L140 98 Z" fill="none" stroke="rgba(255,255,255,0.22)" stroke-width="2.5"/>' .
        '<path d="M165 112 L165 145 Q200 170 235 145 L235 112" fill="none" stroke="rgba(255,255,255,0.22)" stroke-width="2.5"/>' .
        '<text x="200" y="235" font-family="-apple-system, BlinkMacSystemFont, Segoe UI, Roboto, sans-serif" font-size="76" font-weight="800" fill="#ffffff" text-anchor="middle" letter-spacing="4">%s</text>' .
        '</svg>',
        esc_attr($class),
        esc_attr($grad_id),
        esc_attr($c[0]),
        esc_attr($c[1]),
        esc_attr($grad_id),
        esc_html($initials)
    );
}
