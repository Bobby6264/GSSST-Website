<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <div class="site-desktop-wrapper">
        <?php $header_settings = cic_get_header_settings(); ?>
        <!-- Top Bar: Sticky Navbar -->
        <header class="site-header top-bar">
        <div class="container">
            <div class="logo">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="logo-link">
                    <span class="logo-crest">
                        <?php 
                        $logo_url = !empty($header_settings['logo_url']) ? $header_settings['logo_url'] : get_template_directory_uri() . '/assets/images/iitkgp-logo.png';
                        ?>
                        <img src="<?php echo esc_url($logo_url); ?>" alt="IIT KGP Logo" width="48" height="48" style="object-fit: contain;">
                    </span>
                    <?php if (!empty($header_settings['dept_logo_url'])) : ?>
                        <span class="logo-divider" aria-hidden="true">|</span>
                        <span class="logo-crest">
                            <img src="<?php echo esc_url($header_settings['dept_logo_url']); ?>" alt="Department Logo" class="dept-logo-img">
                        </span>
                    <?php endif; ?>
                    <span class="logo-text">
                        <span class="logo-title"><?php echo esc_html($header_settings['dept_name']); ?></span>
                        <span class="logo-subtitle"><?php echo esc_html($header_settings['dept_subtitle']); ?></span>
                    </span>
                </a>
            </div>
            <div class="top-menu">
                <ul class="top-nav-links">
                    <?php 
                    if (!empty($header_settings['nav_headings']) && is_array($header_settings['nav_headings'])) :
                        $is_login_page = is_page('login') || is_page_template('login/login-page.php') || is_page_template('page-login.php');
                        $is_faculty_page = is_page('faculty') || is_page_template('faculty/faculty-page.php') || is_page_template('page-faculty.php');
                        foreach ($header_settings['nav_headings'] as $heading) : 
                            $has_subs = !empty($heading['subheadings']) && is_array($heading['subheadings']);
                            $is_heading_active = false;
                            
                            if ($is_login_page && (strcasecmp($heading['title'], 'Internal') === 0 || (!empty($heading['url']) && strpos($heading['url'], '/login') !== false))) {
                                $is_heading_active = true;
                            } elseif (is_front_page() && (strcasecmp($heading['title'], 'Home') === 0 || (!empty($heading['url']) && rtrim($heading['url'], '/') === rtrim(home_url('/'), '/')))) {
                                $is_heading_active = true;
                            } elseif ($is_faculty_page && (strcasecmp($heading['title'], 'Faculty') === 0 || (!empty($heading['url']) && strpos($heading['url'], '/faculty') !== false))) {
                                $is_heading_active = true;
                            }

                            $classes = array();
                            if ($has_subs) $classes[] = 'has-dropdown';
                            if ($is_heading_active) $classes[] = 'active';
                            $li_class = implode(' ', $classes);
                    ?>
                        <li class="<?php echo esc_attr($li_class); ?>">
                            <a href="<?php echo esc_url(!empty($heading['url']) ? $heading['url'] : '#'); ?>">
                                <span><?php echo esc_html($heading['title']); ?></span>
                                <?php if ($has_subs) : ?>
                                    <i class="fas fa-chevron-down nav-arrow"></i>
                                <?php endif; ?>
                            </a>
                            <?php if ($has_subs) : ?>
                                <ul class="dropdown-menu">
                                    <?php 
                                    foreach ($heading['subheadings'] as $sub) : 
                                        $has_sub_subs = !empty($sub['sub_subheadings']) && is_array($sub['sub_subheadings']);
                                        $is_sub_active = ($is_login_page && (strcasecmp($sub['title'], 'Login') === 0 || (!empty($sub['url']) && strpos($sub['url'], '/login') !== false))) || ($is_faculty_page && (stripos($sub['title'], 'faculty') !== false || (!empty($sub['url']) && strpos($sub['url'], '/faculty') !== false)));
                                        $sub_classes = array();
                                        if ($has_sub_subs) $sub_classes[] = 'has-submenu';
                                        if ($is_sub_active) $sub_classes[] = 'active';
                                        $sub_li_class = implode(' ', $sub_classes);
                                    ?>
                                        <li class="<?php echo esc_attr($sub_li_class); ?>">
                                            <a href="<?php echo esc_url(!empty($sub['url']) ? $sub['url'] : '#'); ?>">
                                                <span><?php echo esc_html($sub['title']); ?></span>
                                                <?php if ($has_sub_subs) : ?>
                                                    <i class="fas fa-chevron-right nav-arrow"></i>
                                                <?php endif; ?>
                                            </a>
                                            <?php if ($has_sub_subs) : ?>
                                                <ul class="dropdown-submenu">
                                                    <?php foreach ($sub['sub_subheadings'] as $sub3) : ?>
                                                        <li>
                                                            <a href="<?php echo esc_url(!empty($sub3['url']) ? $sub3['url'] : '#'); ?>">
                                                                <span><?php echo esc_html($sub3['title']); ?></span>
                                                            </a>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endif; ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </li>
                    <?php 
                        endforeach;
                    endif; 
                    ?>
                </ul>
                <span class="top-divider"></span>
                <button class="dark-mode-toggle" aria-label="Toggle dark mode">
                    <i class="fas fa-sun"></i>
                </button>
            </div>
            <div class="mobile-actions">
                <button class="dark-mode-toggle mobile-dark-toggle" aria-label="Toggle dark mode">
                    <i class="fas fa-sun"></i>
                </button>
                <button class="mobile-menu-toggle" aria-label="Toggle menu">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
    </header>


