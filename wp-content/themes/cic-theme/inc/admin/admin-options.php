<?php
/**
 * GSSST Theme Admin Panel - Options & Settings Registration
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once get_template_directory() . '/inc/admin/admin-views.php';

/**
 * Register Top-level Admin Menu & Submenus
 */
function cic_register_admin_menus() {
    $capability = 'manage_options';

    // Top Level Menu: Landing Page
    add_menu_page(
        __('Landing Page Settings', 'cic-theme'),
        __('Landing Page', 'cic-theme'),
        $capability,
        'cic-landing-hero',
        'cic_render_hero_page',
        'dashicons-admin-generic',
        25
    );

    // Submenu: Hero Section (same as parent)
    add_submenu_page(
        'cic-landing-hero',
        __('Hero Section', 'cic-theme'),
        __('Hero Section', 'cic-theme'),
        $capability,
        'cic-landing-hero',
        'cic_render_hero_page'
    );

    // Submenu: News & Ticker
    add_submenu_page(
        'cic-landing-hero',
        __('News & Ticker Settings', 'cic-theme'),
        __('News & Ticker', 'cic-theme'),
        $capability,
        'cic-landing-news',
        'cic_render_news_page'
    );

    // Submenu: About Us & Head
    add_submenu_page(
        'cic-landing-hero',
        __('About Us & Head Message', 'cic-theme'),
        __('About & Head', 'cic-theme'),
        $capability,
        'cic-landing-about-head',
        'cic_render_about_head_page'
    );

    // Submenu: Explore Department
    add_submenu_page(
        'cic-landing-hero',
        __('Explore Our Department', 'cic-theme'),
        __('Explore Department', 'cic-theme'),
        $capability,
        'cic-landing-dept',
        'cic_render_dept_page'
    );

    // Submenu: Academics & Resources
    add_submenu_page(
        'cic-landing-hero',
        __('Academics & Resources', 'cic-theme'),
        __('Academics & Resources', 'cic-theme'),
        $capability,
        'cic-landing-academics',
        'cic_render_academics_page'
    );

    // Top Level Menu: Header & Footer (as requested, separate common page)
    add_menu_page(
        __('Header & Footer Settings', 'cic-theme'),
        __('Header & Footer', 'cic-theme'),
        $capability,
        'cic-header-footer',
        'cic_render_header_footer_page',
        'dashicons-layout',
        26
    );
}
add_action('admin_menu', 'cic_register_admin_menus');

/**
 * Enqueue Admin Assets (Media uploader, scripts, styles)
 */
function cic_admin_enqueue_assets($hook) {
    // Only load on our theme settings pages
    $allowed_pages = array(
        'toplevel_page_cic-landing-hero',
        'landing-page_page_cic-landing-news',
        'landing-page_page_cic-landing-about-head',
        'landing-page_page_cic-landing-dept',
        'landing-page_page_cic-landing-academics',
        'toplevel_page_cic-header-footer'
    );

    if (!in_array($hook, $allowed_pages, true)) {
        return;
    }

    // WordPress Media Uploader
    wp_enqueue_media();

    // FontAwesome for icon previews in admin
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), '6.4.0');

    // Admin CSS & JS
    wp_enqueue_style('cic-admin-style', get_template_directory_uri() . '/inc/admin/assets/admin-style.css', array(), time());
    wp_enqueue_script('cic-admin-script', get_template_directory_uri() . '/inc/admin/assets/admin-script.js', array('jquery'), time(), true);
}
add_action('admin_enqueue_scripts', 'cic_admin_enqueue_assets');

/**
 * Default Settings Getters
 */
function cic_get_header_settings() {
    $defaults = array(
        'dept_name'       => 'G.S. Sanyal School of Technology',
        'dept_subtitle'   => 'INDIAN INSTITUTE OF TECHNOLOGY KHARAGPUR',
        'logo_url'        => get_template_directory_uri() . '/assets/images/iitkgp-logo.png',
        'top_nav_links'   => array(
            array('title' => 'HOME', 'url' => home_url('/'), 'new_tab' => 0),
            array('title' => 'RESEARCH', 'url' => '#', 'new_tab' => 0),
            array('title' => 'STAFF', 'url' => '#', 'new_tab' => 0),
            array('title' => 'FACULTY', 'url' => '#', 'new_tab' => 0),
            array('title' => 'IIT KGP', 'url' => 'https://www.iitkgp.ac.in', 'new_tab' => 1),
        ),
        'show_login'      => 1,
        'login_text'      => 'Login',
        'login_url'       => wp_login_url(),
        'sub_nav_links'   => array(
            array('title' => 'HEAD', 'url' => '#'),
            array('title' => 'FACULTY', 'url' => '#'),
            array('title' => 'STAFF', 'url' => '#'),
            array('title' => 'ACADEMICS', 'url' => '#'),
            array('title' => 'RESEARCH', 'url' => '#'),
            array('title' => 'PUBLICATION', 'url' => '#'),
            array('title' => 'NOTABLE ALUMNI', 'url' => '#'),
            array('title' => 'FACULTY AWARDS', 'url' => '#'),
            array('title' => 'STUDENT AWARDS', 'url' => '#'),
            array('title' => 'PHOTO GALLERY', 'url' => '#'),
        )
    );

    $saved = get_option('cic_header_settings', array());
    return wp_parse_args($saved, $defaults);
}

function cic_get_footer_settings() {
    $defaults = array(
        'brand_title'     => 'G.S. Sanyal School of Technology',
        'brand_address'   => 'Indian Institute of Technology Kharagpur, West Bengal, India - 721302',
        'brand_logo_url'  => get_template_directory_uri() . '/assets/images/iitkgp-logo.png',
        'quick_links'     => array(
            array('title' => 'Institute Home', 'url' => 'https://www.iitkgp.ac.in'),
            array('title' => 'ERP Portal', 'url' => '#'),
            array('title' => 'Central Library', 'url' => '#'),
        ),
        'academics_links' => array(
            array('title' => 'Programmes', 'url' => '#'),
            array('title' => 'Admissions', 'url' => '#'),
            array('title' => 'Academic Calendar', 'url' => '#'),
        ),
        'contact_email'   => 'head@gssst.iitkgp.ac.in',
        'contact_phone'   => '+91-3222-282227',
        'copyright_text'  => '© ' . date('Y') . ' IIT Kharagpur. All Rights Reserved.',
        'legal_links'     => array(
            array('title' => 'Privacy Policy', 'url' => '#'),
            array('title' => 'Terms of Use', 'url' => '#'),
        )
    );

    $saved = get_option('cic_footer_settings', array());
    return wp_parse_args($saved, $defaults);
}

function cic_get_hero_settings() {
    $defaults = array(
        'title'    => 'Advancing the Frontiers of Technology',
        'subtitle' => 'Interdisciplinary research, world-class academic programs, and industry collaborations shape the engineering leaders of tomorrow.',
        'slides'   => array(
            array('image_url' => 'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?auto=format&fit=crop&q=80&w=2000'),
            array('image_url' => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&q=80&w=2000'),
            array('image_url' => 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&q=80&w=2000'),
        ),
        'buttons'  => array(
            array('text' => 'Explore Programmes', 'url' => '#', 'style' => 'primary', 'new_tab' => 0),
            array('text' => 'Research Highlights', 'url' => '#', 'style' => 'secondary', 'new_tab' => 0),
        )
    );

    $saved = get_option('cic_hero_settings', array());
    return wp_parse_args($saved, $defaults);
}

function cic_get_news_settings() {
    $defaults = array(
        'ticker_count' => 10,
        'events_count' => 10,
        'news_count'   => 10,
    );

    $saved = get_option('cic_news_settings', array());
    return wp_parse_args($saved, $defaults);
}

function cic_get_about_head_settings() {
    $defaults = array(
        'about_title'        => 'About the School',
        'established_text'   => 'Established 1996',
        'about_body'         => "The G.S. Sanyal School of Technology was established to lead global technological advancements. The school acts as a nexus for interdisciplinary collaboration across telecommunications, artificial intelligence, and physical infrastructure.\n\nStudents benefit from close faculty mentorship, well-equipped research laboratories, and a culture of rigorous inquiry.",
        'visible_lines'      => 0, // 0 = all lines
        'read_more_text'     => 'Read more about the School',
        'read_more_url'      => '#',
        'details_table'      => array(
            array('label' => 'Location', 'value' => 'IIT Campus, Kharagpur'),
            array('label' => 'Affiliation', 'value' => 'IIT Kharagpur'),
        ),
        // Head Message Section
        'head_tagline'       => '— MESSAGE FROM THE HEAD',
        'head_title'         => 'Welcome to the <em>G.S. Sanyal School of Technology</em>',
        'head_message'       => "The G.S. Sanyal School of Technology at IIT Kharagpur was established with a singular purpose: to pursue rigorous, impactful research while nurturing the next generation of engineers and scientists who will shape the future.\n\nWhether you are a prospective student, a research collaborator, or a partner from industry, I invite you to explore what GSSST has to offer.",
        'head_name'          => 'Prof. Vikram Desai',
        'head_role'          => 'Professor & Head, G.S. Sanyal School of Technology',
        'head_email'         => 'vdesai@gssst.iitkgp.ac.in',
        'head_photo_url'     => 'https://images.unsplash.com/photo-1568602471122-7832951cc4c5?auto=format&fit=crop&q=80&w=600&h=750'
    );

    $saved = get_option('cic_about_head_settings', array());
    return wp_parse_args($saved, $defaults);
}

function cic_get_dept_settings() {
    $defaults = array(
        'section_title' => 'Explore Our Department',
        'cards'         => array(
            array(
                'title'       => 'Research & Innovation',
                'description' => 'Engage with cutting-edge research that addresses global challenges.',
                'btn_text'    => 'Explore',
                'btn_url'     => '#',
                'icon_type'   => 'chip'
            ),
            array(
                'title'       => 'Faculty & Staff',
                'description' => 'Meet our distinguished faculty members committed to student success.',
                'btn_text'    => 'Directory',
                'btn_url'     => '#',
                'icon_type'   => 'users'
            ),
            array(
                'title'       => 'Awards',
                'description' => 'Celebrating the outstanding achievements of our students and faculty.',
                'btn_text'    => 'View All',
                'btn_url'     => '#',
                'icon_type'   => 'award'
            ),
        )
    );

    $saved = get_option('cic_dept_settings', array());
    return wp_parse_args($saved, $defaults);
}

function cic_get_academics_settings() {
    $defaults = array(
        'section_title' => 'Academics & Resources',
        'columns'       => array(
            array(
                'col_title' => 'Postgraduate Taught',
                'items'     => array(
                    array('title' => 'M.Tech Programs', 'url' => '#'),
                    array('title' => 'Joint M.Tech/PhD', 'url' => '#'),
                )
            ),
            array(
                'col_title' => 'Postgraduate Research',
                'items'     => array(
                    array('title' => 'M.S. by Research', 'url' => '#'),
                    array('title' => 'PhD Program', 'url' => '#'),
                )
            ),
            array(
                'col_title' => 'Resources',
                'items'     => array(
                    array('title' => 'Academic Curriculum', 'url' => '#'),
                    array('title' => 'Time Table', 'url' => '#'),
                )
            )
        )
    );

    $saved = get_option('cic_academics_settings', array());
    return wp_parse_args($saved, $defaults);
}

/**
 * Register Settings with WP Settings API & Sanitization Callbacks
 */
function cic_register_settings() {
    // 1. Header Settings
    register_setting('cic_header_settings_group', 'cic_header_settings', array(
        'sanitize_callback' => 'cic_sanitize_header_settings'
    ));

    // 2. Footer Settings
    register_setting('cic_footer_settings_group', 'cic_footer_settings', array(
        'sanitize_callback' => 'cic_sanitize_footer_settings'
    ));

    // 3. Hero Settings
    register_setting('cic_hero_settings_group', 'cic_hero_settings', array(
        'sanitize_callback' => 'cic_sanitize_hero_settings'
    ));

    // 4. News Settings
    register_setting('cic_news_settings_group', 'cic_news_settings', array(
        'sanitize_callback' => 'cic_sanitize_news_settings'
    ));

    // 5. About & Head Settings
    register_setting('cic_about_head_settings_group', 'cic_about_head_settings', array(
        'sanitize_callback' => 'cic_sanitize_about_head_settings'
    ));

    // 6. Explore Department Settings
    register_setting('cic_dept_settings_group', 'cic_dept_settings', array(
        'sanitize_callback' => 'cic_sanitize_dept_settings'
    ));

    // 7. Academics Settings
    register_setting('cic_academics_settings_group', 'cic_academics_settings', array(
        'sanitize_callback' => 'cic_sanitize_academics_settings'
    ));
}
add_action('admin_init', 'cic_register_settings');

/**
 * Sanitization Helpers
 */
function cic_sanitize_header_settings($input) {
    $clean = array();
    $clean['dept_name'] = isset($input['dept_name']) ? sanitize_text_field($input['dept_name']) : '';
    $clean['dept_subtitle'] = isset($input['dept_subtitle']) ? sanitize_text_field($input['dept_subtitle']) : '';
    $clean['logo_url'] = isset($input['logo_url']) ? esc_url_raw($input['logo_url']) : '';
    
    // Top Nav links
    $clean['top_nav_links'] = array();
    if (!empty($input['top_nav_links']) && is_array($input['top_nav_links'])) {
        foreach ($input['top_nav_links'] as $item) {
            $title = isset($item['title']) ? sanitize_text_field($item['title']) : '';
            $url = isset($item['url']) ? esc_url_raw($item['url']) : '#';
            $new_tab = !empty($item['new_tab']) ? 1 : 0;
            if ($title !== '') {
                $clean['top_nav_links'][] = array('title' => $title, 'url' => $url, 'new_tab' => $new_tab);
            }
        }
    }

    $clean['show_login'] = !empty($input['show_login']) ? 1 : 0;
    $clean['login_text'] = isset($input['login_text']) ? sanitize_text_field($input['login_text']) : 'Login';
    $clean['login_url'] = isset($input['login_url']) ? esc_url_raw($input['login_url']) : '#';

    // Sub Nav links
    $clean['sub_nav_links'] = array();
    if (!empty($input['sub_nav_links']) && is_array($input['sub_nav_links'])) {
        foreach ($input['sub_nav_links'] as $item) {
            $title = isset($item['title']) ? sanitize_text_field($item['title']) : '';
            $url = isset($item['url']) ? esc_url_raw($item['url']) : '#';
            if ($title !== '') {
                $clean['sub_nav_links'][] = array('title' => $title, 'url' => $url);
            }
        }
    }

    return $clean;
}

function cic_sanitize_footer_settings($input) {
    $clean = array();
    $clean['brand_title'] = isset($input['brand_title']) ? sanitize_text_field($input['brand_title']) : '';
    $clean['brand_address'] = isset($input['brand_address']) ? sanitize_textarea_field($input['brand_address']) : '';
    $clean['brand_logo_url'] = isset($input['brand_logo_url']) ? esc_url_raw($input['brand_logo_url']) : '';

    $clean['quick_links'] = array();
    if (!empty($input['quick_links']) && is_array($input['quick_links'])) {
        foreach ($input['quick_links'] as $item) {
            $title = isset($item['title']) ? sanitize_text_field($item['title']) : '';
            $url = isset($item['url']) ? esc_url_raw($item['url']) : '#';
            if ($title !== '') {
                $clean['quick_links'][] = array('title' => $title, 'url' => $url);
            }
        }
    }

    $clean['academics_links'] = array();
    if (!empty($input['academics_links']) && is_array($input['academics_links'])) {
        foreach ($input['academics_links'] as $item) {
            $title = isset($item['title']) ? sanitize_text_field($item['title']) : '';
            $url = isset($item['url']) ? esc_url_raw($item['url']) : '#';
            if ($title !== '') {
                $clean['academics_links'][] = array('title' => $title, 'url' => $url);
            }
        }
    }

    $clean['contact_email'] = isset($input['contact_email']) ? sanitize_email($input['contact_email']) : '';
    $clean['contact_phone'] = isset($input['contact_phone']) ? sanitize_text_field($input['contact_phone']) : '';
    $clean['copyright_text'] = isset($input['copyright_text']) ? wp_kses_post($input['copyright_text']) : '';

    $clean['legal_links'] = array();
    if (!empty($input['legal_links']) && is_array($input['legal_links'])) {
        foreach ($input['legal_links'] as $item) {
            $title = isset($item['title']) ? sanitize_text_field($item['title']) : '';
            $url = isset($item['url']) ? esc_url_raw($item['url']) : '#';
            if ($title !== '') {
                $clean['legal_links'][] = array('title' => $title, 'url' => $url);
            }
        }
    }

    return $clean;
}

function cic_sanitize_hero_settings($input) {
    $clean = array();
    $clean['title'] = isset($input['title']) ? sanitize_text_field($input['title']) : '';
    $clean['subtitle'] = isset($input['subtitle']) ? sanitize_textarea_field($input['subtitle']) : '';

    $clean['slides'] = array();
    if (!empty($input['slides']) && is_array($input['slides'])) {
        foreach ($input['slides'] as $item) {
            $img = isset($item['image_url']) ? esc_url_raw($item['image_url']) : '';
            if ($img !== '') {
                $clean['slides'][] = array('image_url' => $img);
            }
        }
    }

    $clean['buttons'] = array();
    if (!empty($input['buttons']) && is_array($input['buttons'])) {
        foreach ($input['buttons'] as $btn) {
            $text = isset($btn['text']) ? sanitize_text_field($btn['text']) : '';
            $url = isset($btn['url']) ? esc_url_raw($btn['url']) : '#';
            $style = isset($btn['style']) && in_array($btn['style'], array('primary', 'secondary', 'outline'), true) ? $btn['style'] : 'primary';
            $new_tab = !empty($btn['new_tab']) ? 1 : 0;
            if ($text !== '') {
                $clean['buttons'][] = array('text' => $text, 'url' => $url, 'style' => $style, 'new_tab' => $new_tab);
            }
        }
    }

    return $clean;
}

function cic_sanitize_news_settings($input) {
    $clean = array();
    $clean['ticker_count'] = isset($input['ticker_count']) ? max(1, min(50, absint($input['ticker_count']))) : 10;
    $clean['events_count'] = isset($input['events_count']) ? max(1, min(50, absint($input['events_count']))) : 10;
    $clean['news_count']   = isset($input['news_count']) ? max(1, min(50, absint($input['news_count']))) : 10;
    return $clean;
}

function cic_sanitize_about_head_settings($input) {
    $clean = array();
    $clean['about_title'] = isset($input['about_title']) ? sanitize_text_field($input['about_title']) : '';
    $clean['established_text'] = isset($input['established_text']) ? sanitize_text_field($input['established_text']) : '';
    $clean['about_body'] = isset($input['about_body']) ? wp_kses_post($input['about_body']) : '';
    $clean['visible_lines'] = isset($input['visible_lines']) ? absint($input['visible_lines']) : 0;
    $clean['read_more_text'] = isset($input['read_more_text']) ? sanitize_text_field($input['read_more_text']) : '';
    $clean['read_more_url'] = isset($input['read_more_url']) ? esc_url_raw($input['read_more_url']) : '#';

    $clean['details_table'] = array();
    if (!empty($input['details_table']) && is_array($input['details_table'])) {
        foreach ($input['details_table'] as $row) {
            $label = isset($row['label']) ? sanitize_text_field($row['label']) : '';
            $value = isset($row['value']) ? sanitize_text_field($row['value']) : '';
            if ($label !== '' || $value !== '') {
                $clean['details_table'][] = array('label' => $label, 'value' => $value);
            }
        }
    }

    // Head message
    $clean['head_tagline'] = isset($input['head_tagline']) ? sanitize_text_field($input['head_tagline']) : '';
    $clean['head_title'] = isset($input['head_title']) ? wp_kses_post($input['head_title']) : '';
    $clean['head_message'] = isset($input['head_message']) ? wp_kses_post($input['head_message']) : '';
    $clean['head_name'] = isset($input['head_name']) ? sanitize_text_field($input['head_name']) : '';
    $clean['head_role'] = isset($input['head_role']) ? sanitize_text_field($input['head_role']) : '';
    $clean['head_email'] = isset($input['head_email']) ? sanitize_email($input['head_email']) : '';
    $clean['head_photo_url'] = isset($input['head_photo_url']) ? esc_url_raw($input['head_photo_url']) : '';

    return $clean;
}

function cic_sanitize_dept_settings($input) {
    $clean = array();
    $clean['section_title'] = isset($input['section_title']) ? sanitize_text_field($input['section_title']) : '';
    
    $clean['cards'] = array();
    if (!empty($input['cards']) && is_array($input['cards'])) {
        foreach ($input['cards'] as $card) {
            $title = isset($card['title']) ? sanitize_text_field($card['title']) : '';
            $desc = isset($card['description']) ? sanitize_textarea_field($card['description']) : '';
            $btn_text = isset($card['btn_text']) ? sanitize_text_field($card['btn_text']) : '';
            $btn_url = isset($card['btn_url']) ? esc_url_raw($card['btn_url']) : '#';
            $icon = isset($card['icon_type']) ? sanitize_text_field($card['icon_type']) : 'chip';
            if ($title !== '') {
                $clean['cards'][] = array(
                    'title'       => $title,
                    'description' => $desc,
                    'btn_text'    => $btn_text,
                    'btn_url'     => $btn_url,
                    'icon_type'   => $icon
                );
            }
        }
    }

    return $clean;
}

function cic_sanitize_academics_settings($input) {
    $clean = array();
    $clean['section_title'] = isset($input['section_title']) ? sanitize_text_field($input['section_title']) : '';
    
    $clean['columns'] = array();
    if (!empty($input['columns']) && is_array($input['columns'])) {
        foreach ($input['columns'] as $col) {
            $col_title = isset($col['col_title']) ? sanitize_text_field($col['col_title']) : '';
            $items = array();
            if (!empty($col['items']) && is_array($col['items'])) {
                foreach ($col['items'] as $item) {
                    $t = isset($item['title']) ? sanitize_text_field($item['title']) : '';
                    $u = isset($item['url']) ? esc_url_raw($item['url']) : '#';
                    if ($t !== '') {
                        $items[] = array('title' => $t, 'url' => $u);
                    }
                }
            }
            if ($col_title !== '' || !empty($items)) {
                $clean['columns'][] = array(
                    'col_title' => $col_title,
                    'items'     => $items
                );
            }
        }
    }

    return $clean;
}

