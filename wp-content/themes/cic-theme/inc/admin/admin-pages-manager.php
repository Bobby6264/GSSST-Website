<?php
/**
 * GSSST Theme Admin - Pages Manager & Admin Cleanup
 *
 * Handles:
 * 1. Removal of Posts and Comments from Admin UI & Dashboard.
 * 2. Complete visibility and management of all pages in edit.php?post_type=page.
 * 3. Read-only / Open-only protection for Code/JS template pages (Landing, Login).
 * 4. Full editability and custom rendering for user-created pages (e.g., Blog).
 * 5. Display of live Page URLs and quick copy buttons for all pages.
 *
 * @package cic-theme
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * --------------------------------------------------------------------------
 * 1. REMOVE POSTS FROM ADMIN
 * --------------------------------------------------------------------------
 */

// Remove "Posts" from Admin Sidebar Menu
function cic_remove_posts_admin_menu() {
    remove_menu_page('edit.php');
}
add_action('admin_menu', 'cic_remove_posts_admin_menu');

// Remove "+ New -> Post" from Admin Bar
function cic_remove_posts_admin_bar($wp_admin_bar) {
    $wp_admin_bar->remove_node('new-post');
}
add_action('admin_bar_menu', 'cic_remove_posts_admin_bar', 999);

// Block direct access to edit.php / post-new.php for default 'post' post type
function cic_block_posts_direct_access() {
    global $pagenow;
    if ($pagenow === 'edit.php' && (!isset($_GET['post_type']) || $_GET['post_type'] === 'post')) {
        wp_safe_redirect(admin_url('edit.php?post_type=page'));
        exit;
    }
    if ($pagenow === 'post-new.php' && (!isset($_GET['post_type']) || $_GET['post_type'] === 'post')) {
        wp_safe_redirect(admin_url('post-new.php?post_type=page'));
        exit;
    }
}
add_action('admin_init', 'cic_block_posts_direct_access');

// Remove post widgets from Dashboard
function cic_remove_posts_dashboard_widgets() {
    remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
}
add_action('wp_dashboard_setup', 'cic_remove_posts_dashboard_widgets');


/**
 * --------------------------------------------------------------------------
 * 2. REMOVE COMMENTS COMPLETELY
 * --------------------------------------------------------------------------
 */

// Remove "Comments" from Admin Sidebar Menu
function cic_remove_comments_admin_menu() {
    remove_menu_page('edit-comments.php');
}
add_action('admin_menu', 'cic_remove_comments_admin_menu');

// Remove Comments icon from Admin Bar
function cic_remove_comments_admin_bar($wp_admin_bar) {
    $wp_admin_bar->remove_node('comments');
}
add_action('admin_bar_menu', 'cic_remove_comments_admin_bar', 999);

// Remove Comments & Trackbacks metaboxes from edit screens
function cic_remove_comments_metaboxes() {
    remove_meta_box('commentstatusdiv', 'page', 'normal');
    remove_meta_box('commentsdiv', 'page', 'normal');
    remove_meta_box('trackbacksdiv', 'page', 'normal');
}
add_action('admin_init', 'cic_remove_comments_metaboxes');

// Remove post type support for comments and trackbacks
function cic_remove_comments_post_type_support() {
    remove_post_type_support('page', 'comments');
    remove_post_type_support('page', 'trackbacks');
    remove_post_type_support('post', 'comments');
    remove_post_type_support('post', 'trackbacks');
}
add_action('init', 'cic_remove_comments_post_type_support');

// Remove comments from Dashboard
function cic_remove_comments_dashboard_widgets() {
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
}
add_action('wp_dashboard_setup', 'cic_remove_comments_dashboard_widgets');

// Close comments globally on frontend
add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open', '__return_false', 20, 2);
add_filter('comments_array', '__return_empty_array', 10, 2);

// Block direct access to edit-comments.php and comment.php
function cic_block_comments_direct_access() {
    global $pagenow;
    if ($pagenow === 'edit-comments.php' || $pagenow === 'comment.php') {
        wp_safe_redirect(admin_url('edit.php?post_type=page'));
        exit;
    }
}
add_action('admin_init', 'cic_block_comments_direct_access');


/**
 * --------------------------------------------------------------------------
 * 3. CODE / JS PAGES DETECTION & GUARANTEE
 * --------------------------------------------------------------------------
 */

/**
 * Check if a post is a Code / JS template page (Landing Page, Login Page).
 *
 * @param int|WP_Post $post Post ID or post object.
 * @return bool
 */
function cic_is_code_page($post) {
    if (!$post) {
        return false;
    }
    $post_obj = is_numeric($post) ? get_post($post) : $post;
    if (!$post_obj || $post_obj->post_type !== 'page') {
        return false;
    }

    // Front page is always the Landing Page
    $front_page_id = (int) get_option('page_on_front');
    if ($front_page_id && (int) $post_obj->ID === $front_page_id) {
        return true;
    }

    // Slugs reserved for JS code pages
    $slug = $post_obj->post_name;
    if (in_array($slug, array('landing-page', 'login', 'faculty'), true)) {
        return true;
    }

    // Templates designated for code pages
    $template = get_post_meta($post_obj->ID, '_wp_page_template', true);
    if (in_array($template, array('landing-page/landing-page.php', 'login/login-page.php', 'page-login.php', 'faculty/faculty-page.php', 'page-faculty.php'), true)) {
        return true;
    }

    return false;
}

/**
 * Ensure system code pages (Landing Page & Login) always exist, are published,
 * and are visible in the Pages list table.
 */
function cic_ensure_system_pages() {
    // 1. Ensure Landing Page exists and is published
    $front_id = (int) get_option('page_on_front');
    $landing_page = $front_id ? get_post($front_id) : null;
    if (!$landing_page || $landing_page->post_status === 'trash') {
        $found = get_page_by_path('landing-page');
        if ($found) {
            if ($found->post_status === 'trash') {
                wp_untrash_post($found->ID);
            }
            $landing_id = $found->ID;
        } else {
            $landing_id = wp_insert_post(array(
                'post_title'     => 'Landing Page',
                'post_name'      => 'landing-page',
                'post_status'    => 'publish',
                'post_type'      => 'page',
                'post_content'   => '',
                'comment_status' => 'closed',
                'ping_status'    => 'closed',
            ));
        }
        update_post_meta($landing_id, '_wp_page_template', 'landing-page/landing-page.php');
        update_option('show_on_front', 'page');
        update_option('page_on_front', $landing_id);
    } else {
        update_post_meta($landing_page->ID, '_wp_page_template', 'landing-page/landing-page.php');
    }

    // 2. Ensure Login Page exists and is published
    $login_page = get_page_by_path('login');
    if (!$login_page) {
        $login_id = wp_insert_post(array(
            'post_title'     => 'Login',
            'post_name'      => 'login',
            'post_status'    => 'publish',
            'post_type'      => 'page',
            'post_content'   => '',
            'comment_status' => 'closed',
            'ping_status'    => 'closed',
        ));
        update_post_meta($login_id, '_wp_page_template', 'login/login-page.php');
    } elseif ($login_page->post_status === 'trash') {
        wp_untrash_post($login_page->ID);
        update_post_meta($login_page->ID, '_wp_page_template', 'login/login-page.php');
    }
    // 3. Ensure Faculty Directory Page exists and is published
    $faculty_page = get_page_by_path('faculty');
    if (!$faculty_page) {
        $faculty_id = wp_insert_post(array(
            'post_title'     => 'Faculty Directory',
            'post_name'      => 'faculty',
            'post_status'    => 'publish',
            'post_type'      => 'page',
            'post_content'   => '',
            'comment_status' => 'closed',
            'ping_status'    => 'closed',
        ));
        update_post_meta($faculty_id, '_wp_page_template', 'faculty/faculty-page.php');
    } elseif ($faculty_page->post_status === 'trash') {
        wp_untrash_post($faculty_page->ID);
        update_post_meta($faculty_page->ID, '_wp_page_template', 'faculty/faculty-page.php');
    }
}
add_action('admin_init', 'cic_ensure_system_pages');


/**
 * --------------------------------------------------------------------------
 * 4. PAGES TABLE CUSTOMIZATION (URL Display, Types, Badges)
 * --------------------------------------------------------------------------
 */

/**
 * Customize columns in edit.php?post_type=page:
 * - Remove Comments column
 * - Add "Page URL" column
 * - Add "Page Type" column
 */
function cic_manage_pages_columns($columns) {
    $new_columns = array();
    foreach ($columns as $key => $title) {
        if ($key === 'comments') {
            continue; // Remove comments column
        }
        $new_columns[$key] = $title;
        // Insert Page URL and Page Type immediately after Title
        if ($key === 'title') {
            $new_columns['page_url']  = __('Page URL', 'cic-theme');
            $new_columns['page_type'] = __('Page Type', 'cic-theme');
        }
    }
    return $new_columns;
}
add_filter('manage_pages_columns', 'cic_manage_pages_columns');

/**
 * Render custom column content:
 * - Page URL: clickable URL link with external icon + one-click Copy button
 * - Page Type: badge showing Code Template vs Custom Editable Page
 */
function cic_manage_pages_custom_column($column_name, $post_id) {
    $is_code = cic_is_code_page($post_id);
    $url     = get_permalink($post_id);

    if ($column_name === 'page_url') {
        ?>
        <div class="cic-url-cell" style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
            <a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener noreferrer" class="cic-url-link" style="color: #2563eb; font-weight: 500; font-family: monospace; font-size: 13px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;" title="<?php esc_attr_e('Open live page in new tab', 'cic-theme'); ?>">
                <span><?php echo esc_html($url); ?></span>
                <span class="dashicons dashicons-external" style="font-size: 14px; width: 14px; height: 14px; line-height: 14px;"></span>
            </a>
            <button type="button" class="button button-small cic-copy-url-btn" data-url="<?php echo esc_attr($url); ?>" style="display: inline-flex; align-items: center; gap: 4px; font-size: 12px; height: 24px; line-height: 22px; padding: 0 8px;" title="<?php esc_attr_e('Copy URL to clipboard', 'cic-theme'); ?>">
                <span class="dashicons dashicons-admin-page" style="font-size: 13px; width: 13px; height: 13px; line-height: 13px;"></span>
                <span class="btn-text"><?php esc_html_e('Copy', 'cic-theme'); ?></span>
            </button>
        </div>
        <?php
    }

    if ($column_name === 'page_type') {
        if ($is_code) {
            ?>
            <span class="cic-badge cic-badge-code" style="display: inline-flex; align-items: center; gap: 4px; background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.03em;" title="<?php esc_attr_e('Custom JS/PHP template page. Only openable from here, content is managed via code/settings.', 'cic-theme'); ?>">
                <span class="dashicons dashicons-lock" style="font-size: 13px; width: 13px; height: 13px; line-height: 13px;"></span>
                <?php esc_html_e('Code Template (Open Only)', 'cic-theme'); ?>
            </span>
            <?php
        } else {
            ?>
            <span class="cic-badge cic-badge-custom" style="display: inline-flex; align-items: center; gap: 4px; background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.03em;" title="<?php esc_attr_e('User-created standard page. Fully editable.', 'cic-theme'); ?>">
                <span class="dashicons dashicons-edit" style="font-size: 13px; width: 13px; height: 13px; line-height: 13px;"></span>
                <?php esc_html_e('Custom Page (Editable)', 'cic-theme'); ?>
            </span>
            <?php
        }
    }
}
add_action('manage_pages_custom_column', 'cic_manage_pages_custom_column', 10, 2);


/**
 * --------------------------------------------------------------------------
 * 5. ROW ACTIONS & EDIT RESTRICTIONS FOR CODE PAGES
 * --------------------------------------------------------------------------
 */

/**
 * Filter row actions on edit.php?post_type=page:
 * For Code/JS template pages:
 * - Remove 'edit', 'quick edit' ('inline hide-if-no-js'), and 'trash'.
 * - Provide a prominent 'Open Page' link with external icon.
 * For User-created pages:
 * - Retain normal Edit, Quick Edit, Trash, and View.
 */
function cic_filter_page_row_actions($actions, $post) {
    if (cic_is_code_page($post->ID)) {
        unset($actions['edit']);
        unset($actions['inline hide-if-no-js']);
        unset($actions['trash']);

        $url = get_permalink($post->ID);
        $actions['view'] = sprintf(
            '<a href="%s" target="_blank" rel="noopener noreferrer" style="color: #2563eb; font-weight: 600; display: inline-flex; align-items: center; gap: 2px;"><span class="dashicons dashicons-external" style="font-size: 14px; width: 14px; height: 14px; line-height: 14px;"></span> %s</a>',
            esc_url($url),
            esc_html__('Open Page', 'cic-theme')
        );
    }
    return $actions;
}
add_filter('page_row_actions', 'cic_filter_page_row_actions', 10, 2);

/**
 * Intercept direct navigation to post.php for Code/JS pages:
 * If a user tries to edit a Code page directly via post.php?post=ID&action=edit,
 * block with an informative message and direct links to Open Live Page or Back to Pages.
 */
function cic_intercept_code_page_edit() {
    global $pagenow;
    if ($pagenow === 'post.php' && isset($_GET['post'])) {
        $post_id = absint($_GET['post']);
        if (cic_is_code_page($post_id)) {
            $post = get_post($post_id);
            $page_name = $post ? $post->post_title : 'This Page';
            $permalink = get_permalink($post_id);
            wp_die(
                '<div style="max-width: 600px; margin: 40px auto; font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, sans-serif; text-align: center; background: #ffffff; padding: 40px; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">' .
                '<div style="font-size: 48px; margin-bottom: 16px;">⚡</div>' .
                '<h2 style="color: #0f172a; margin: 0 0 12px 0; font-size: 24px; font-weight: 800;">' . esc_html(sprintf(__('%s is a Code Template Page', 'cic-theme'), $page_name)) . '</h2>' .
                '<p style="color: #64748b; font-size: 15px; line-height: 1.6; margin: 0 0 28px 0;">' . 
                esc_html__('This page is built with custom JavaScript, styles, and PHP theme templates. It is openable from the Pages list but cannot be edited in the WordPress post editor. Landing page content can be configured via the "Landing Page" admin menu.', 'cic-theme') . 
                '</p>' .
                '<div style="display: flex; justify-content: center; gap: 12px;">' .
                '<a href="' . esc_url($permalink) . '" target="_blank" class="button button-primary button-hero" style="font-weight: 600;">' . esc_html__('Open Live Page', 'cic-theme') . ' &rarr;</a>' .
                '<a href="' . esc_url(admin_url('edit.php?post_type=page')) . '" class="button button-secondary button-hero">' . esc_html__('Back to Pages List', 'cic-theme') . '</a>' .
                '</div>' .
                '</div>',
                esc_html__('Page Not Editable', 'cic-theme'),
                array('response' => 403, 'back_link' => true)
            );
        }
    }
}
add_action('load-post.php', 'cic_intercept_code_page_edit');

/**
 * Prevent trashing or deleting Code/JS template pages.
 */
function cic_prevent_code_page_trash($post_id) {
    if (cic_is_code_page($post_id)) {
        wp_die(
            esc_html__('System/Code template pages cannot be moved to trash or deleted.', 'cic-theme'),
            esc_html__('Action Not Allowed', 'cic-theme'),
            array('back_link' => true)
        );
    }
}
add_action('wp_trash_post', 'cic_prevent_code_page_trash');
add_action('before_delete_post', 'cic_prevent_code_page_trash');


/**
 * --------------------------------------------------------------------------
 * 6. ADMIN SCRIPT & STYLES FOR PAGES SCREEN
 * --------------------------------------------------------------------------
 */

/**
 * Injects styling and clipboard script on edit.php?post_type=page:
 * - Redirects row title clicks on Code pages to open live URL in new tab.
 * - Disables checkbox on Code pages to prevent bulk trashing.
 * - Handles one-click URL copying with visual confirmation.
 */
function cic_pages_admin_assets() {
    global $pagenow, $post_type;
    if ($pagenow !== 'edit.php' || $post_type !== 'page') {
        return;
    }

    // Get all code page IDs to pass to JS
    $front_id = (int) get_option('page_on_front');
    $login_page = get_page_by_path('login');
    $code_ids = array_filter(array($front_id, $login_page ? $login_page->ID : 0));
    ?>
    <style>
        .fixed .column-page_url {
            width: 32%;
        }
        .fixed .column-page_type {
            width: 20%;
        }
        .cic-url-link:hover {
            text-decoration: underline !important;
        }
        .cic-copy-url-btn.copied {
            background: #16a34a !important;
            border-color: #16a34a !important;
            color: #ffffff !important;
        }
        .cic-code-row strong .row-title {
            color: #1e293b;
            cursor: pointer;
        }
        .cic-code-row strong .row-title:hover {
            color: #2563eb;
        }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var codePageIds = <?php echo json_encode(array_values($code_ids)); ?>;

        // Process each code page row
        codePageIds.forEach(function(id) {
            var row = document.getElementById('post-' + id);
            if (!row) return;

            row.classList.add('cic-code-row');

            // 1. Disable checkbox to prevent bulk actions
            var checkInput = row.querySelector('th.check-column input[type="checkbox"]');
            if (checkInput) {
                checkInput.disabled = true;
                checkInput.title = 'System page cannot be bulk deleted';
                checkInput.style.opacity = '0.3';
                checkInput.style.cursor = 'not-allowed';
            }

            // 2. Adjust title link to open live frontend page instead of editor
            var titleLink = row.querySelector('.row-title');
            var urlLink = row.querySelector('.cic-url-link');
            if (titleLink && urlLink) {
                titleLink.href = urlLink.href;
                titleLink.target = '_blank';
                titleLink.rel = 'noopener noreferrer';
                titleLink.title = 'Click to open live page in new tab (Code Template - Open Only)';
                
                // Add a small external icon next to title
                var extIcon = document.createElement('span');
                extIcon.className = 'dashicons dashicons-external';
                extIcon.style.fontSize = '14px';
                extIcon.style.width = '14px';
                extIcon.style.height = '14px';
                extIcon.style.verticalAlign = 'middle';
                extIcon.style.marginLeft = '4px';
                extIcon.style.color = '#64748b';
                titleLink.appendChild(extIcon);
            }
        });

        // 3. One-click URL copy functionality
        document.addEventListener('click', function(e) {
            var btn = e.target.closest('.cic-copy-url-btn');
            if (!btn) return;
            e.preventDefault();

            var url = btn.getAttribute('data-url');
            if (!url) return;

            var originalHtml = btn.innerHTML;

            function showCopied() {
                btn.innerHTML = '<span class="dashicons dashicons-yes" style="font-size:13px;width:13px;height:13px;line-height:13px;"></span> Copied!';
                btn.classList.add('copied');
                setTimeout(function() {
                    btn.innerHTML = originalHtml;
                    btn.classList.remove('copied');
                }, 2000);
            }

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(url).then(showCopied).catch(function() {
                    fallbackCopy(url);
                });
            } else {
                fallbackCopy(url);
            }

            function fallbackCopy(text) {
                var textArea = document.createElement('textarea');
                textArea.value = text;
                textArea.style.position = 'fixed';
                textArea.style.left = '-999999px';
                textArea.style.top = '-999999px';
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                try {
                    document.execCommand('copy');
                    showCopied();
                } catch (err) {}
                document.body.removeChild(textArea);
            }
        });
    });
    </script>
    <?php
}
add_action('admin_footer', 'cic_pages_admin_assets');
