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
    wp_enqueue_style('cic-main-style', get_template_directory_uri() . '/assets/css/main.css', array(), '1.0');
    wp_enqueue_script('cic-main-script', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), '1.0', true);
}
add_action('wp_enqueue_scripts', 'cic_enqueue_scripts');

function cic_register_cpts() {
    register_post_type('notice', array(
        'labels' => array('name' => 'Notices', 'singular_name' => 'Notice'),
        'public' => true,
        'has_archive' => false,
        'supports' => array('title', 'editor'),
        'menu_icon' => 'dashicons-megaphone',
    ));
    register_post_type('news', array(
        'labels' => array('name' => 'News & Events', 'singular_name' => 'News/Event'),
        'public' => true,
        'has_archive' => false,
        'supports' => array('title', 'editor', 'thumbnail'),
        'menu_icon' => 'dashicons-calendar-alt',
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

