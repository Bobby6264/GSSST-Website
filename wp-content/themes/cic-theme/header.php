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
                    if (!empty($header_settings['nav_headings']) && is_array($header_settings['nav_headings'])) :
                        foreach ($header_settings['nav_headings'] as $heading) : 
                            $has_subs = !empty($heading['subheadings']) && is_array($heading['subheadings']);
                            $li_class = $has_subs ? 'has-dropdown' : '';
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
                                        $sub_li_class = $has_sub_subs ? 'has-submenu' : '';
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
