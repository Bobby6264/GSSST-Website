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
                        <img src="<?php echo esc_url($logo_url); ?>" alt="Logo" width="48" height="48" style="object-fit: contain;">
                    </span>
                    <span class="logo-text">
                        <span class="logo-title"><?php echo esc_html($header_settings['dept_name']); ?></span>
                        <span class="logo-subtitle"><?php echo esc_html($header_settings['dept_subtitle']); ?></span>
                    </span>
                </a>
            </div>
            <div class="top-menu">
                <ul class="top-nav-links">
                    <?php 
                    if (!empty($header_settings['top_nav_links'])) :
                        foreach ($header_settings['top_nav_links'] as $t_link) : 
                            $t_target = !empty($t_link['new_tab']) ? ' target="_blank" rel="noopener noreferrer"' : '';
                    ?>
                        <li><a href="<?php echo esc_url($t_link['url']); ?>"<?php echo $t_target; ?>><?php echo esc_html($t_link['title']); ?></a></li>
                    <?php 
                        endforeach;
                    endif; 
                    ?>
                </ul>
                <span class="top-divider"></span>
                <button class="dark-mode-toggle" aria-label="Toggle dark mode">
                    <i class="fas fa-sun"></i>
                </button>
                <?php if (!empty($header_settings['show_login'])) : ?>
                    <a href="<?php echo esc_url($header_settings['login_url']); ?>" class="btn-login"><?php echo esc_html($header_settings['login_text']); ?> <i class="fas fa-arrow-right"></i></a>
                <?php endif; ?>
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

    <!-- Sub Navigation Bar (Scrolls with page content) -->
    <nav class="sub-nav-bar">
        <div class="container">
            <ul class="sub-nav-links">
                <?php 
                if (!empty($header_settings['sub_nav_links'])) :
                    foreach ($header_settings['sub_nav_links'] as $s_link) :
                ?>
                    <li><a href="<?php echo esc_url($s_link['url']); ?>"><?php echo esc_html($s_link['title']); ?></a></li>
                <?php 
                    endforeach;
                endif; 
                ?>
            </ul>
        </div>
    </nav>
