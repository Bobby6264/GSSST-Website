<?php

function cic_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    
    register_nav_menus(array(
        'top_menu' => 'Top Menu',
        'primary_menu' => 'Primary Menu',
        'footer_links' => 'Footer Links',
        'footer_academics' => 'Footer Academics',
    ));
}
add_action('after_setup_theme', 'cic_theme_setup');

function cic_enqueue_scripts() {
    // Common / Global Assets (Loaded on all pages)
    wp_enqueue_style('cic-main-style', get_template_directory_uri() . '/assets/css/main.css', array(), time());
    wp_enqueue_script('cic-main-script', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), time(), true);

    // Landing Page Specific Assets (Loaded only on Landing Page)
    if (is_front_page() || is_home() || is_page_template('landing-page/landing-page.php')) {
        wp_enqueue_style('cic-landing-style', get_template_directory_uri() . '/landing-page/landing-page.css', array('cic-main-style'), time());
        wp_enqueue_script('cic-landing-script', get_template_directory_uri() . '/landing-page/landing-page.js', array('jquery', 'cic-main-script'), time(), true);
    }
}
add_action('wp_enqueue_scripts', 'cic_enqueue_scripts');

// Route Front Page to landing-page/landing-page.php
function cic_template_include($template) {
    if (is_front_page() || is_home()) {
        $landing_page = get_template_directory() . '/landing-page/landing-page.php';
        if (file_exists($landing_page)) {
            return $landing_page;
        }
    }
    return $template;
}
add_filter('template_include', 'cic_template_include');

// Include Theme Admin Panel
require_once get_template_directory() . '/inc/admin/admin-options.php';

function cic_register_cpts() {
    register_post_type('notice', array(
        'labels' => array(
            'name'          => 'Notices & Announcements',
            'singular_name' => 'Notice / Announcement',
            'add_new_item'  => 'Add New Notice / Announcement',
            'edit_item'     => 'Edit Notice / Announcement'
        ),
        'public'       => true,
        'has_archive'  => true,
        'show_in_menu' => true,
        'supports'     => array('title', 'editor', 'thumbnail', 'excerpt'),
        'menu_icon'    => 'dashicons-megaphone',
    ));

    register_post_type('announcement', array(
        'labels' => array(
            'name'          => 'Announcements',
            'singular_name' => 'Announcement',
            'add_new_item'  => 'Add New Announcement',
            'edit_item'     => 'Edit Announcement'
        ),
        'public'       => true,
        'has_archive'  => true,
        'show_in_menu' => false,
        'supports'     => array('title', 'editor', 'thumbnail', 'excerpt'),
        'menu_icon'    => 'dashicons-megaphone',
    ));

    register_post_type('news', array(
        'labels' => array(
            'name'          => 'News',
            'singular_name' => 'News Item',
            'add_new_item'  => 'Add New News Item',
            'edit_item'     => 'Edit News Item'
        ),
        'public'       => true,
        'has_archive'  => true,
        'show_in_menu' => true,
        'supports'     => array('title', 'editor', 'thumbnail', 'excerpt'),
        'menu_icon'    => 'dashicons-format-aside',
    ));

    register_post_type('event', array(
        'labels' => array(
            'name'          => 'Events',
            'singular_name' => 'Event',
            'add_new_item'  => 'Add New Event',
            'edit_item'     => 'Edit Event'
        ),
        'public'       => true,
        'has_archive'  => true,
        'show_in_menu' => true,
        'supports'     => array('title', 'editor', 'thumbnail', 'excerpt'),
        'menu_icon'    => 'dashicons-calendar-alt',
    ));
}
add_action('init', 'cic_register_cpts');

// Customizer
function cic_customize_register($wp_customize) {
    // Hero Section
    $wp_customize->add_section('hero_section', array('title' => 'Hero Section', 'priority' => 30));
    $wp_customize->add_setting('hero_bg_image_1');
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'hero_bg_image_1', array('label' => 'Hero Image 1', 'section' => 'hero_section')));
    $wp_customize->add_setting('hero_bg_image_2');
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'hero_bg_image_2', array('label' => 'Hero Image 2 (Slider)', 'section' => 'hero_section')));
    $wp_customize->add_setting('hero_bg_image_3');
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'hero_bg_image_3', array('label' => 'Hero Image 3 (Slider)', 'section' => 'hero_section')));
    $wp_customize->add_setting('hero_title', array('default' => 'Advancing the Frontiers of Technology'));
    $wp_customize->add_control('hero_title', array('label' => 'Title', 'section' => 'hero_section'));
    $wp_customize->add_setting('hero_subtitle', array('default' => 'Interdisciplinary research, world-class academic programs, and industry collaborations shape the engineering leaders of tomorrow.'));
    $wp_customize->add_control('hero_subtitle', array('label' => 'Subtitle', 'section' => 'hero_section', 'type' => 'textarea'));
    $wp_customize->add_setting('hero_btn1_text', array('default' => 'Explore Programmes'));
    $wp_customize->add_control('hero_btn1_text', array('label' => 'Button 1 Text', 'section' => 'hero_section'));
    $wp_customize->add_setting('hero_btn1_url', array('default' => '#'));
    $wp_customize->add_control('hero_btn1_url', array('label' => 'Button 1 URL', 'section' => 'hero_section'));
    $wp_customize->add_setting('hero_btn2_text', array('default' => 'Research Highlights'));
    $wp_customize->add_control('hero_btn2_text', array('label' => 'Button 2 Text', 'section' => 'hero_section'));
    $wp_customize->add_setting('hero_btn2_url', array('default' => '#'));
    $wp_customize->add_control('hero_btn2_url', array('label' => 'Button 2 URL', 'section' => 'hero_section'));
    
    // About Section
    $wp_customize->add_section('about_section', array('title' => 'About Section', 'priority' => 31));
    $wp_customize->add_setting('about_title', array('default' => 'About the School'));
    $wp_customize->add_control('about_title', array('label' => 'Title', 'section' => 'about_section'));
    $wp_customize->add_setting('about_text', array('default' => 'The G.S. Sanyal School of Technology was established to lead global technological advancements...'));
    $wp_customize->add_control('about_text', array('label' => 'Text', 'section' => 'about_section', 'type' => 'textarea'));
    
    // Message Section
    $wp_customize->add_section('message_section', array('title' => 'Message Section', 'priority' => 32));
    $wp_customize->add_setting('message_title', array('default' => 'Welcome to the School of Technology'));
    $wp_customize->add_control('message_title', array('label' => 'Title', 'section' => 'message_section'));
    $wp_customize->add_setting('message_text', array('default' => '"At GSSST, our mandate goes beyond traditional research boundaries..."'));
    $wp_customize->add_control('message_text', array('label' => 'Text', 'section' => 'message_section', 'type' => 'textarea'));
    $wp_customize->add_setting('message_author', array('default' => 'Prof. Vikram Desai'));
    $wp_customize->add_control('message_author', array('label' => 'Author', 'section' => 'message_section'));
    $wp_customize->add_setting('message_designation', array('default' => 'Professor & Head, GSSST'));
    $wp_customize->add_control('message_designation', array('label' => 'Designation', 'section' => 'message_section'));

    // Departments Section
    $wp_customize->add_section('departments_section', array('title' => 'Departments Section', 'priority' => 33));
    $wp_customize->add_setting('dep_title', array('default' => 'Explore Our Department'));
    $wp_customize->add_control('dep_title', array('label' => 'Title', 'section' => 'departments_section'));

    // Academics Section
    $wp_customize->add_section('academics_section', array('title' => 'Academics & Resources Section', 'priority' => 34));
    $wp_customize->add_setting('academics_title', array('default' => 'Academics & Resources'));
    $wp_customize->add_control('academics_title', array('label' => 'Title', 'section' => 'academics_section'));
}
add_action('customize_register', 'cic_customize_register');

// Fallback menus for footer when no menu is assigned
function cic_footer_quick_links_fallback() {
    echo '<ul>';
    echo '<li><a href="#">Institute Home</a></li>';
    echo '<li><a href="#">ERP Portal</a></li>';
    echo '<li><a href="#">Central Library</a></li>';
    echo '</ul>';
}

function cic_footer_academics_fallback() {
    echo '<ul>';
    echo '<li><a href="#">Programmes</a></li>';
    echo '<li><a href="#">Admissions</a></li>';
    echo '<li><a href="#">Academic Calendar</a></li>';
    echo '</ul>';
}

