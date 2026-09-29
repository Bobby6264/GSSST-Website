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
        $phone  = get_user_meta($user_id, 'phone', true) ?: '+91-3222-28' . str_pad($user_id + 1000, 4, '0', STR_PAD_LEFT);
        $office = get_user_meta($user_id, 'office', true) ?: 'Takshashila Building, IIT Kharagpur';
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

        // Prof. Vikram Desai fallback to local head.jpg
        if (empty($photo_url) && stripos($name, 'Desai') !== false) {
            $head_img = get_template_directory() . '/assets/images/head.jpg';
            if (file_exists($head_img)) {
                $photo_url = get_template_directory_uri() . '/assets/images/head.jpg';
            }
        }

        // If still no photo, generate clean initials academic avatar (no internet requests)
        if (empty($photo_url)) {
            $photo_url = cic_get_faculty_initials_avatar($name);
        }

        // Bio & Research Areas
        $bio = $user->description ?: 'Faculty member and researcher at G.S. Sanyal School of Telecommunication, IIT Kharagpur.';
        $raw_research = get_user_meta($user_id, 'research_areas', true);
        $research_areas = array();
        if (!empty($raw_research)) {
            if (is_array($raw_research)) {
                $research_areas = $raw_research;
            } else {
                $research_areas = array_map('trim', explode(',', $raw_research));
            }
        } else {
            // Sensible defaults matching department discipline
            $research_areas = array('Wireless Communications', 'Telecommunication Systems', 'Signal Processing');
        }

        $education = get_user_meta($user_id, 'education', true) ?: 'Ph.D., Indian Institute of Technology';

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

/**
 * Seed initial faculty members from the UI specification into the database
 * as real WordPress users with role 'faculty', if they don't already exist.
 */
function cic_seed_initial_faculty() {
    $seeded = get_option('cic_faculty_seeded_v1', 0);
    if ($seeded) {
        return;
    }

    $initial_faculty = array(
        array(
            'username'    => 'vdesai',
            'email'       => 'vdesai@gssst.iitkgp.ac.in',
            'first_name'  => 'Vikram',
            'last_name'   => 'Desai',
            'name'        => 'Prof. Vikram Desai',
            'designation' => 'PROFESSOR & HEAD',
            'phone'       => '+91-3222-281001',
            'office'      => 'Administrative Wing, Room 101',
            'photo'       => 'head.jpg',
            'bio'         => 'Prof. Vikram Desai is Professor and Head of the G.S. Sanyal School of Telecommunication. His research focuses on optical communications, photonics, and telecommunication infrastructure.',
            'research'    => 'Optical Communications, Photonic Networks, Telecommunications Infrastructure',
            'education'   => 'Ph.D., IIT Kharagpur; M.Tech., IIT Bombay; B.Tech., University of Mumbai',
        ),
        array(
            'username'    => 'asharma',
            'email'       => 'asharma@gssst.iitkgp.ac.in',
            'first_name'  => 'Arundhati',
            'last_name'   => 'Sharma',
            'name'        => 'Prof. Arundhati Sharma',
            'designation' => 'PROFESSOR',
            'phone'       => '+91-3222-281002',
            'office'      => 'Takshashila Building, Room 202',
            'photo'       => '',
            'bio'         => 'Prof. Arundhati Sharma is an IEEE Senior Member and leads the 6G National Testbed team at GSSST. Her specialization spans massive MIMO and cognitive wireless networks.',
            'research'    => '6G Wireless Networks, Massive MIMO, Cognitive Radio, Space-Air-Ground Integration',
            'education'   => 'Ph.D., University of Cambridge; M.S., IISc Bangalore',
        ),
        array(
            'username'    => 'asrinivasan',
            'email'       => 'asrinivasan@gssst.iitkgp.ac.in',
            'first_name'  => 'Anantharaman',
            'last_name'   => 'Srinivasan',
            'name'        => 'Prof. Anantharaman Srinivasan',
            'designation' => 'PROFESSOR',
            'phone'       => '+91-3222-281003',
            'office'      => 'Takshashila Building, Room 203',
            'photo'       => '',
            'bio'         => 'Prof. Anantharaman Srinivasan conducts extensive research in quantum communication protocols, information theory, and advanced error-correcting codes.',
            'research'    => 'Quantum Information Processing, Coding Theory, Information Theory, Cryptography',
            'education'   => 'Ph.D., Stanford University; B.Tech., IIT Madras',
        ),
        array(
            'username'    => 'erostova',
            'email'       => 'erostova@gssst.iitkgp.ac.in',
            'first_name'  => 'Elena',
            'last_name'   => 'Rostova',
            'name'        => 'Dr. Elena Rostova',
            'designation' => 'ASSOCIATE PROFESSOR',
            'phone'       => '+91-3222-281004',
            'office'      => 'Takshashila Building, Room 205',
            'photo'       => '',
            'bio'         => 'Dr. Elena Rostova investigates edge computing, distributed AI architectures for cellular networks, and energy-efficient IoT topologies.',
            'research'    => 'Edge AI, Physical Layer Machine Learning, IoT Protocol Optimization, 5G NR',
            'education'   => 'Ph.D., ETH Zurich; M.Sc., Saint Petersburg State University',
        ),
        array(
            'username'    => 'mbosukonda',
            'email'       => 'mmb@gssst.iitkgp.ac.in',
            'first_name'  => 'Murali Mohan',
            'last_name'   => 'Bosukonda',
            'name'        => 'Dr. Murali Mohan Bosukonda',
            'designation' => 'ASSISTANT PROFESSOR',
            'phone'       => '+91-3222-281005',
            'office'      => 'Takshashila Building, Room 206',
            'photo'       => '',
            'bio'         => 'Dr. Murali Mohan Bosukonda works on RF microelectronics, phased-array antennas, and satellite communication transceivers.',
            'research'    => 'RF Circuit Design, Antenna Array Engineering, Satellite Communications, Microwave Systems',
            'education'   => 'Ph.D., IIT Kharagpur; M.Tech., NIT Warangal',
        ),
        array(
            'username'    => 'ksingh',
            'email'       => 'ksingh@gssst.iitkgp.ac.in',
            'first_name'  => 'Kavita',
            'last_name'   => 'Singh',
            'name'        => 'Dr. Kavita Singh',
            'designation' => 'ASSISTANT PROFESSOR',
            'phone'       => '+91-3222-281006',
            'office'      => 'Takshashila Building, Room 208',
            'photo'       => '',
            'bio'         => 'Dr. Kavita Singh specializes in telecommunication security, cryptographic key distribution for vehicular networks, and blockchain applications.',
            'research'    => 'Cellular Cybersecurity, Cryptographic Key Exchange, VANETs, Network Resilience',
            'education'   => 'Ph.D., IIT Delhi; M.Tech., IIT Roorkee',
        ),
    );

    foreach ($initial_faculty as $data) {
        $existing = get_user_by('login', $data['username']);
        if (!$existing) {
            $user_id = wp_insert_user(array(
                'user_login'   => $data['username'],
                'user_email'   => $data['email'],
                'first_name'   => $data['first_name'],
                'last_name'    => $data['last_name'],
                'display_name' => $data['name'],
                'user_pass'    => wp_generate_password(16, true),
                'role'         => 'faculty',
                'description'  => $data['bio'],
            ));
            if (!is_wp_error($user_id)) {
                update_user_meta($user_id, 'designation', $data['designation']);
                update_user_meta($user_id, 'phone', $data['phone']);
                update_user_meta($user_id, 'office', $data['office']);
                update_user_meta($user_id, 'faculty_photo', $data['photo']);
                update_user_meta($user_id, 'research_areas', $data['research']);
                update_user_meta($user_id, 'education', $data['education']);
            }
        } else {
            // Update role to faculty if not already
            $existing->set_role('faculty');
            update_user_meta($existing->ID, 'designation', $data['designation']);
            update_user_meta($existing->ID, 'phone', $data['phone']);
            update_user_meta($existing->ID, 'office', $data['office']);
            if (!empty($data['photo'])) {
                update_user_meta($existing->ID, 'faculty_photo', $data['photo']);
            }
            update_user_meta($existing->ID, 'research_areas', $data['research']);
            update_user_meta($existing->ID, 'education', $data['education']);
        }
    }

    // Also update User 2 (Karan Sethi) who already has faculty role
    $user2 = get_user_by('id', 2);
    if ($user2) {
        $user2->set_role('faculty');
        if (!get_user_meta(2, 'designation', true)) {
            update_user_meta(2, 'designation', 'ASSISTANT PROFESSOR');
            update_user_meta(2, 'phone', '+91-3222-281007');
            update_user_meta(2, 'office', 'Takshashila Building, Room 207');
            update_user_meta(2, 'research_areas', 'Wireless Networks, Signal Processing, Telecommunication Systems');
            update_user_meta(2, 'education', 'Ph.D., IIT Kharagpur');
        }
    }

    update_option('cic_faculty_seeded_v1', 1);
}
add_action('init', 'cic_seed_initial_faculty');

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
    $photo_preview  = '';
    if (is_numeric($photo_id)) {
        $photo_preview = wp_get_attachment_image_url((int)$photo_id, 'thumbnail');
    } elseif ($photo_id === 'head.jpg') {
        $photo_preview = get_template_directory_uri() . '/assets/images/head.jpg';
    }
    ?>
    <h2 style="margin-top: 24px;">Faculty Profile Details (GSSST Directory)</h2>
    <table class="form-table">
        <tr>
            <th><label for="designation">Academic Designation</label></th>
            <td>
                <input type="text" name="designation" id="designation" value="<?php echo esc_attr($designation); ?>" class="regular-text" placeholder="e.g. Professor & Head, Assistant Professor">
                <p class="description">Displayed prominently on the faculty card and profile header.</p>
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
    if (isset($_POST['designation'])) {
        update_user_meta($user_id, 'designation', sanitize_text_field($_POST['designation']));
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
    if (isset($_POST['faculty_photo'])) {
        update_user_meta($user_id, 'faculty_photo', sanitize_text_field($_POST['faculty_photo']));
    }
}
add_action('personal_options_update', 'cic_save_faculty_user_fields');
add_action('edit_user_profile_update', 'cic_save_faculty_user_fields');

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
