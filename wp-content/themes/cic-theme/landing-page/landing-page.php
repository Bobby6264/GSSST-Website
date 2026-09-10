<?php
/**
 * Template Name: Landing Page
 */

get_header(); 

$hero_settings       = cic_get_hero_settings();
$news_settings       = cic_get_news_settings();
$about_head_settings = cic_get_about_head_settings();
$dept_settings       = cic_get_dept_settings();
$academics_settings  = cic_get_academics_settings();

if (!function_exists('cic_render_dept_icon')) {
    function cic_render_dept_icon($type = 'chip') {
        switch ($type) {
            case 'users':
                return '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>';
            case 'award':
                return '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"></circle><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"></path></svg>';
            case 'book':
                return '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"></path><path d="M6 6h10"></path><path d="M6 10h10"></path></svg>';
            case 'flask':
                return '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 2v7.31L4.17 19.89A2 2 0 0 0 5.86 23h12.28a2 2 0 0 0 1.69-3.11L14 9.31V2"></path><path d="M8.5 2h7"></path><path d="M14 9.3a6.5 6.5 0 0 0-4 0"></path></svg>';
            case 'laptop':
                return '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="12" x="3" y="4" rx="2"></rect><line x1="2" x2="22" y1="20" y2="20"></line></svg>';
            case 'chip':
            default:
                return '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="16" height="16" x="4" y="4" rx="2"></rect><rect width="6" height="6" x="9" y="9" rx="1"></rect><path d="M15 2v2"></path><path d="M15 20v2"></path><path d="M2 15h2"></path><path d="M2 9h2"></path><path d="M20 15h2"></path><path d="M20 9h2"></path><path d="M9 2v2"></path><path d="M9 20v2"></path></svg>';
        }
    }
}
?>

<main id="main-content">

    <!-- Hero Section -->
    <div class="hero-container-wrapper container">
        <section class="hero-section">
            <?php 
                $hero_images = array();
                if (!empty($hero_settings['slides'])) {
                    foreach ($hero_settings['slides'] as $s) {
                        if (!empty($s['image_url'])) {
                            $hero_images[] = $s['image_url'];
                        }
                    }
                }
                if (empty($hero_images)) {
                    $hero_images = array('https://images.unsplash.com/photo-1541339907198-e08756dedf3f?auto=format&fit=crop&q=80&w=2000');
                }
            ?>
            <?php foreach ($hero_images as $index => $img_url) : ?>
                <div class="hero-slide <?php echo $index === 0 ? 'active' : ''; ?>" style="background-image: url('<?php echo esc_url_raw($img_url); ?>');"></div>
            <?php endforeach; ?>
            <div class="hero-overlay-layer"></div>
            <div class="hero-content-wrapper">
                <h1 class="hero-main-title"><?php echo esc_html($hero_settings['title']); ?></h1>
                <p class="hero-subtitle-text"><?php echo esc_html($hero_settings['subtitle']); ?></p>
                <div class="hero-action-buttons">
                    <?php 
                    if (!empty($hero_settings['buttons'])) :
                        foreach ($hero_settings['buttons'] as $h_btn) :
                            $b_style = isset($h_btn['style']) ? $h_btn['style'] : 'primary';
                            $btn_cls = ($b_style === 'secondary') ? 'btn-hero-secondary' : (($b_style === 'outline') ? 'btn-hero-outline' : 'btn-hero-primary');
                            $b_target = !empty($h_btn['new_tab']) ? ' target="_blank" rel="noopener noreferrer"' : '';
                    ?>
                        <a href="<?php echo esc_url($h_btn['url']); ?>" class="btn <?php echo esc_attr($btn_cls); ?>"<?php echo $b_target; ?>><?php echo esc_html($h_btn['text']); ?></a>
                    <?php 
                        endforeach;
                    endif; 
                    ?>
                </div>
            </div>
        </section>
    </div>

    <!-- Notices Ticker Section -->
    <div class="notices-outer-wrapper container">
        <section class="notices-section">
            <div class="notices-inner">
                <div class="latest-label">
                    <span class="latest-dot"></span> LATEST
                </div>
                <div class="notices-track-container">
                <div class="notices-track">
                    <?php
                    $ticker_limit = !empty($news_settings['ticker_count']) ? $news_settings['ticker_count'] : 10;
                    $notices_items = array();
                    $notices_query = new WP_Query(array(
                        'post_type'      => array('news', 'event'),
                        'posts_per_page' => $ticker_limit,
                        'meta_query'     => array(
                            'relation' => 'OR',
                            array(
                                'key'     => '_show_in_latest',
                                'value'   => '1',
                                'compare' => '='
                            ),
                            array(
                                'key'     => '_show_in_latest',
                                'compare' => 'NOT EXISTS'
                            )
                        ),
                        'orderby'        => 'date',
                        'order'          => 'DESC'
                    ));
                    if ($notices_query->have_posts()) {
                        while ($notices_query->have_posts()) {
                            $notices_query->the_post();
                            $notices_items[] = array(
                                'date'  => get_the_date('M j, Y'),
                                'title' => get_the_title(),
                                'url'   => get_permalink()
                            );
                        }
                        wp_reset_postdata();
                    } else {
                        $notices_items = array(
                            array('date' => 'MAR 6, 2026', 'title' => 'M.Tech. Admission 2026-27 Applications open', 'url' => '#'),
                            array('date' => 'FEB 28, 2026', 'title' => 'PhD Admission Schedule 2026-27 (Autumn Session)', 'url' => '#'),
                            array('date' => 'FEB 15, 2026', 'title' => 'Convocation 2026 — Official Date, Schedule & Registration Details', 'url' => '#')
                        );
                    }
                    // Render twice for seamless continuous infinite scroll
                    for ($loop = 0; $loop < 2; $loop++) :
                        foreach ($notices_items as $item) :
                    ?>
                        <div class="notice-entry">
                            <span class="entry-date"><?php echo esc_html($item['date']); ?></span>
                            <a href="<?php echo esc_url($item['url']); ?>" class="entry-link"><?php echo esc_html($item['title']); ?></a>
                            <span class="entry-dot">•</span>
                        </div>
                    <?php 
                        endforeach;
                    endfor; 
                    ?>
                </div>
            </div>
        </div>
    </section>
</div>

    <!-- About & News Section -->
    <section class="about-news-section section-spacing">
        <div class="container about-news-grid">
            <!-- Left: About Card -->
            <div class="about-column">
                <div class="about-school-card">
                    <div class="about-card-left">
                        <h2 class="about-card-title"><?php echo esc_html($about_head_settings['about_title']); ?></h2>
                        <?php if (!empty($about_head_settings['established_text'])) : ?>
                            <div class="established-pill"><?php echo esc_html($about_head_settings['established_text']); ?></div>
                        <?php endif; ?>
                        
                        <div class="details-card-box">
                            <?php 
                            if (!empty($about_head_settings['details_table'])) :
                                foreach ($about_head_settings['details_table'] as $d_row) :
                            ?>
                                <div class="details-row">
                                    <span class="details-label"><?php echo esc_html($d_row['label']); ?></span>
                                    <span class="details-value"><?php echo esc_html($d_row['value']); ?></span>
                                </div>
                            <?php 
                                endforeach;
                            endif; 
                            ?>
                        </div>
                    </div>
                    <div class="about-card-right">
                        <?php 
                        $visible_lines = !empty($about_head_settings['visible_lines']) ? intval($about_head_settings['visible_lines']) : 0;
                        $clamp_style = ($visible_lines > 0) ? 'style="display: -webkit-box; -webkit-line-clamp: ' . $visible_lines . '; -webkit-box-orient: vertical; overflow: hidden;"' : '';
                        ?>
                        <div class="about-body-text" <?php echo $clamp_style; ?>>
                            <?php echo wpautop(wp_kses_post($about_head_settings['about_body'])); ?>
                        </div>
                        <?php if (!empty($about_head_settings['read_more_text'])) : ?>
                            <a href="<?php echo esc_url($about_head_settings['read_more_url']); ?>" class="arrow-text-link"><?php echo esc_html($about_head_settings['read_more_text']); ?> <i class="fas fa-arrow-right"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Message Section (Moved here for sticky sidebar layout) -->
                <div class="head-message-card" style="margin-top: 32px;">
                    <div class="head-message-content">
                        <?php if (!empty($about_head_settings['head_tagline'])) : ?>
                            <span class="head-gold-tag"><?php echo esc_html($about_head_settings['head_tagline']); ?></span>
                        <?php endif; ?>
                        <h2 class="head-card-title"><?php echo wp_kses_post($about_head_settings['head_title']); ?></h2>
                        <div class="head-body-text">
                            <?php echo wpautop(wp_kses_post($about_head_settings['head_message'])); ?>
                        </div>
                        <div class="head-card-divider"></div>
                        <div class="head-author-row">
                            <div class="head-author-info">
                                <h4 class="head-author-name"><?php echo esc_html($about_head_settings['head_name']); ?></h4>
                                <p class="head-author-role"><?php echo esc_html($about_head_settings['head_role']); ?></p>
                                <?php if (!empty($about_head_settings['head_email'])) : ?>
                                    <div class="head-author-email">
                                        <i class="far fa-envelope"></i> <a href="mailto:<?php echo esc_attr($about_head_settings['head_email']); ?>"><?php echo esc_html($about_head_settings['head_email']); ?></a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="head-message-photo">
                        <div class="head-photo-frame">
                            <?php 
                            $head_photo = !empty($about_head_settings['head_photo_url']) ? $about_head_settings['head_photo_url'] : get_template_directory_uri() . '/assets/images/head.jpg';
                            ?>
                            <img src="<?php echo esc_url($head_photo); ?>" alt="<?php echo esc_attr($about_head_settings['head_name']); ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: News & Events Box -->
            <div class="news-column">
                <div class="news-events-card">
                    <div class="news-tabs-header">
                        <button class="news-tab-btn active" data-tab="events"><i class="far fa-calendar-alt"></i> Events</button>
                        <button class="news-tab-btn" data-tab="news"><i class="far fa-file-alt"></i> News</button>
                    </div>
                    <div class="news-tab-content active" id="tab-events">
                        <div class="circular-scroll-wrapper">
                            <div class="circular-scroll-track">
                                <?php
                                $events_limit = !empty($news_settings['events_count']) ? $news_settings['events_count'] : 10;
                                $events_query = new WP_Query(array('post_type' => 'event', 'posts_per_page' => $events_limit));
                                $events_items = array();
                                if ($events_query->have_posts()) {
                                    while ($events_query->have_posts()) {
                                        $events_query->the_post();
                                        $events_items[] = array(
                                            'title' => get_the_title(),
                                            'url'   => get_permalink()
                                        );
                                    }
                                    wp_reset_postdata();
                                }
                                if (empty($events_items)) {
                                    $events_items = array(
                                        array('title' => 'Annual Technology Symposium 2026: Call for Papers & Registrations.', 'url' => '#'),
                                        array('title' => 'International Workshop on Quantum Computing and High-Speed Photonics.', 'url' => '#'),
                                        array('title' => 'Distinguished Guest Lecture on Next-Gen Edge AI Architecture by Dr. Rajesh Patel.', 'url' => '#'),
                                        array('title' => 'GSSST Annual Industry-Academia Conclave & Innovation Showcase 2026.', 'url' => '#')
                                    );
                                }
                                foreach ($events_items as $item) :
                                ?>
                                    <div class="news-single-item">
                                        <span class="news-item-icon"><i class="far fa-calendar-alt"></i></span>
                                        <div class="news-item-content">
                                            <a href="<?php echo esc_url($item['url']); ?>"><?php echo esc_html($item['title']); ?></a>
                                        </div>
                                    </div>
                                <?php
                                endforeach;
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="news-tab-content" id="tab-news" style="display: none;">
                        <div class="circular-scroll-wrapper">
                            <div class="circular-scroll-track">
                                <?php
                                $news_limit = !empty($news_settings['news_count']) ? $news_settings['news_count'] : 10;
                                $news_query = new WP_Query(array('post_type' => 'news', 'posts_per_page' => $news_limit));
                                $news_items = array();
                                if ($news_query->have_posts()) {
                                    while ($news_query->have_posts()) {
                                        $news_query->the_post();
                                        $news_items[] = array(
                                            'title' => get_the_title(),
                                            'url'   => get_permalink()
                                        );
                                    }
                                    wp_reset_postdata();
                                }
                                if (empty($news_items)) {
                                    $news_items = array(
                                        array('title' => 'Prof. Arundhati Sharma Appointed to IEEE Editorial Board on Wireless Communications.', 'url' => '#'),
                                        array('title' => 'GSSST Research Team Secures ₹4.5 Crore National Grant for Next-Gen 6G Testbed.', 'url' => '#'),
                                        array('title' => 'School of Technology Students Win First Prize at National Smart Infrastructure Hackathon.', 'url' => '#'),
                                        array('title' => 'New Scholar Program at M.Tech level initiated in areas of Advanced Communications.', 'url' => '#')
                                    );
                                }
                                foreach ($news_items as $item) :
                                ?>
                                    <div class="news-single-item">
                                        <span class="news-item-icon"><i class="far fa-file-alt"></i></span>
                                        <div class="news-item-content">
                                            <a href="<?php echo esc_url($item['url']); ?>"><?php echo esc_html($item['title']); ?></a>
                                        </div>
                                    </div>
                                <?php
                                endforeach;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <?php
    $dept_cards = array();
    if (!empty($dept_settings['cards']) && is_array($dept_settings['cards'])) {
        foreach ($dept_settings['cards'] as $card) {
            if (!empty($card['title'])) {
                $dept_cards[] = $card;
            }
        }
    }
    $dept_card_count = count($dept_cards);
    if ($dept_card_count > 0) :
    ?>
    <!-- Departments Section -->
    <section class="departments-section section-spacing">
        <div class="container">
            <div class="section-title-center">
                <h2 class="section-heading"><?php echo esc_html($dept_settings['section_title']); ?></h2>
            </div>
            <div class="dept-cards-row cards-count-<?php echo intval($dept_card_count); ?>">
                <?php foreach ($dept_cards as $card) : ?>
                    <div class="department-card">
                        <div class="dept-icon-circle">
                            <?php echo cic_render_dept_icon(isset($card['icon_type']) ? $card['icon_type'] : 'chip'); ?>
                        </div>
                        <h3 class="dept-card-title"><?php echo esc_html($card['title']); ?></h3>
                        <p class="dept-card-desc"><?php echo esc_html($card['description']); ?></p>
                        <?php if (!empty($card['btn_text'])) : ?>
                            <a href="<?php echo esc_url(!empty($card['btn_url']) ? $card['btn_url'] : '#'); ?>" class="dept-action-btn"><?php echo esc_html($card['btn_text']); ?></a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php 
    $acad_columns = array();
    if (!empty($academics_settings['columns']) && is_array($academics_settings['columns'])) {
        foreach ($academics_settings['columns'] as $col) {
            $valid_items = array();
            if (!empty($col['items']) && is_array($col['items'])) {
                foreach ($col['items'] as $item) {
                    if (!empty($item['title'])) {
                        $valid_items[] = $item;
                    }
                }
            }
            if (!empty($valid_items) || !empty($col['col_title'])) {
                $col['items'] = $valid_items;
                $acad_columns[] = $col;
            }
        }
    }
    $acad_col_count = count($acad_columns);
    if ($acad_col_count > 0) :
    ?>
    <!-- Academics Section -->
    <section class="academics-section section-spacing">
        <div class="container">
            <div class="academics-card-box">
                <h2 class="section-heading"><?php echo esc_html($academics_settings['section_title']); ?></h2>
                <div class="academics-columns-row cols-count-<?php echo intval($acad_col_count); ?>">
                    <?php foreach ($acad_columns as $col) : ?>
                        <div class="academics-col">
                            <?php if (!empty($col['col_title'])) : ?>
                                <h3 class="acad-column-title"><?php echo esc_html($col['col_title']); ?></h3>
                            <?php endif; ?>
                            <?php if (!empty($col['items'])) : ?>
                                <div class="acad-items-stack">
                                    <?php foreach ($col['items'] as $item) : ?>
                                        <div class="acad-item-card">
                                            <a href="<?php echo esc_url(!empty($item['url']) ? $item['url'] : '#'); ?>">
                                                <span><?php echo esc_html($item['title']); ?></span>
                                                <i class="fas fa-chevron-right"></i>
                                            </a>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

</main>

<?php get_footer(); ?>
