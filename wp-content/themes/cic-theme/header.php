<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <header class="site-header">
        <div class="top-bar">
            <div class="container">
                <div class="logo">
                    <?php if (has_custom_logo()) : ?>
                        <?php the_custom_logo(); ?>
                    <?php else : ?>
                        <h1><a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a></h1>
                    <?php endif; ?>
                </div>
                <div class="top-menu">
                    <?php wp_nav_menu(array('theme_location' => 'top_menu', 'fallback_cb' => false)); ?>
                    <button class="dark-mode-toggle"><i class="fas fa-moon"></i></button>
                    <a href="#" class="btn-login">Login <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
        <div class="main-nav">
            <div class="container">
                <?php wp_nav_menu(array('theme_location' => 'primary_menu', 'fallback_cb' => false)); ?>
            </div>
        </div>
    </header>

