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
$gallery_settings    = cic_get_gallery_settings();

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
            
            <!-- The approved artwork contains no waves; the canvas animates the tower signals. -->
            <img class="hero-building" src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/hero-trees-towers-v3.png'); ?>" alt="" aria-hidden="true" width="2172" height="724" data-signal-points="[[391,198],[1784,198]]" fetchpriority="high">
            <canvas id="telecomCanvas" aria-hidden="true"></canvas>

            <div class="hero-content-wrapper">
                <?php $hero_headings = array_values(array_filter(array_merge(array($hero_settings['title']), preg_split('/\r\n|\r|\n/', $hero_settings['headings'])), 'strlen')); ?>
                <h1 class="hero-main-title" data-headings="<?php echo esc_attr(wp_json_encode($hero_headings)); ?>"><?php echo esc_html($hero_settings['title']); ?></h1>
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
                <div class="about-school-card landing-motion-card" id="about">
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
                <div class="head-message-card landing-motion-card" id="head">
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
                            $head_photo = !empty($about_head_settings['head_photo_url']) ? $about_head_settings['head_photo_url'] : '';
                            ?>
                            <?php if ($head_photo) : ?><img src="<?php echo esc_url($head_photo); ?>" alt="<?php echo esc_attr($about_head_settings['head_name']); ?>"><?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: a single rotating News & Events card. -->
            <section class="news-column" aria-label="News and events">
                <div class="news-events-card landing-motion-card" data-announcements>
                    <div class="announcements-toolbar">
                        <div class="announcement-tabs" role="tablist" aria-label="Announcement category">
                            <button type="button" class="announcement-tab is-active" id="announcement-tab-events" role="tab" aria-selected="true" aria-controls="announcement-panel-events" data-category="events">Events</button>
                            <button type="button" class="announcement-tab" id="announcement-tab-news" role="tab" aria-selected="false" tabindex="-1" aria-controls="announcement-panel-news" data-category="news">News</button>
                        </div>
                        <a class="announcement-view-all" data-announcement-explore aria-expanded="false" aria-controls="announcement-list-events" href="<?php echo esc_url(get_post_type_archive_link('event')); ?>">Explore events <span aria-hidden="true">↗</span></a>
                    </div>
                    <?php foreach (array('events' => 'event', 'news' => 'news') as $category => $post_type) :
                        $limit = max(1, min(50, (int) ($news_settings[$category . '_count'] ?? 10)));
                        $all_items = get_posts(array('post_type' => $post_type, 'post_status' => 'publish', 'numberposts' => -1));
                        $items = array_slice($all_items, 0, $limit);
                        $label = $category === 'events' ? 'Events' : 'News';
                    ?>
                    <div class="announcement-panel" id="announcement-panel-<?php echo esc_attr($category); ?>" role="tabpanel" data-archive="<?php echo esc_url(get_post_type_archive_link($post_type)); ?>" data-category-label="<?php echo esc_attr(strtolower($label)); ?>" aria-labelledby="announcement-tab-<?php echo esc_attr($category); ?>" <?php if ($category !== 'events') echo 'hidden'; ?>>
                        <div class="announcement-feature">
                        <?php if ($items) : ?>
                        <div class="announcement-layout">
                            <div class="announcement-detail">
                                <?php foreach ($items as $index => $item) : ?>
                                <article class="announcement-slide<?php if (!$index) echo ' is-active'; ?>" id="announcement-<?php echo esc_attr($category . '-' . $index); ?>" aria-hidden="<?php echo $index ? 'true' : 'false'; ?>" <?php if ($index) echo 'inert'; ?>>
                                    <div class="announcement-meta">
                                        <time class="announcement-date" datetime="<?php echo esc_attr(get_the_date('c', $item)); ?>"><strong><?php echo esc_html(get_the_date('d', $item)); ?></strong><span><?php echo esc_html(get_the_date('M Y', $item)); ?></span></time>
                                        <span class="announcement-type"><?php echo esc_html($label); ?></span>
                                        <span class="announcement-published">Published <?php echo esc_html(get_the_date('j M Y', $item)); ?></span>
                                    </div>
                                    <h3><a href="<?php echo esc_url(get_permalink($item)); ?>"><?php echo esc_html(get_the_title($item)); ?></a></h3>
                                    <p class="announcement-summary"><?php echo esc_html(wp_trim_words(wp_strip_all_tags(get_the_excerpt($item)), 48)); ?></p>
                                    <a class="announcement-read" href="<?php echo esc_url(get_permalink($item)); ?>">Read announcement <span aria-hidden="true">→</span></a>
                                </article>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="announcement-controls">
                            <div class="announcement-dots" role="group" aria-label="Choose <?php echo esc_attr(strtolower($label)); ?> announcement">
                                <?php foreach ($items as $index => $item) : ?>
                                <button type="button" class="announcement-dot<?php if (!$index) echo ' is-active'; ?>" data-index="<?php echo esc_attr($index); ?>" aria-label="<?php echo esc_attr(get_the_title($item)); ?>" aria-controls="announcement-<?php echo esc_attr($category . '-' . $index); ?>" aria-pressed="<?php echo $index ? 'false' : 'true'; ?>"></button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php else : ?>
                            <p class="announcement-empty">No <?php echo esc_html(strtolower($label)); ?> published yet.</p>
                        <?php endif; ?>
                        </div>
                        <div class="announcement-list" id="announcement-list-<?php echo esc_attr($category); ?>" tabindex="-1" aria-label="All <?php echo esc_attr(strtolower($label)); ?>" hidden>
                            <p class="announcement-list-label">All <?php echo esc_html(strtolower($label)); ?></p>
                            <?php if ($all_items) : ?>
                            <ul>
                                <?php foreach ($all_items as $item) : ?>
                                <li><a href="<?php echo esc_url(get_permalink($item)); ?>"><span><?php echo esc_html(get_the_title($item)); ?></span><time datetime="<?php echo esc_attr(get_the_date('c', $item)); ?>"><?php echo esc_html(get_the_date('j M Y', $item)); ?></time></a></li>
                                <?php endforeach; ?>
                            </ul>
                            <?php else : ?>
                                <p class="announcement-empty">No <?php echo esc_html(strtolower($label)); ?> published yet.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>


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
    <section class="departments-section section-spacing" id="dept">
        <div class="container">
            <div class="section-title-center">
                <h2 class="section-heading"><?php echo esc_html($dept_settings['section_title']); ?></h2>
                <?php if (!empty($dept_settings['section_description'])) : ?><p class="department-section-description"><?php echo esc_html($dept_settings['section_description']); ?></p><?php endif; ?>
            </div>
            <div class="dept-cards-row cards-count-<?php echo intval($dept_card_count); ?>">
                <?php foreach ($dept_cards as $card) : ?>
                    <div class="department-card landing-motion-card">
                        <div class="dept-icon-circle">
                            <?php echo cic_render_dept_icon(isset($card['icon_type']) ? $card['icon_type'] : 'chip'); ?>
                        </div>
                        <h3 class="dept-card-title"><?php echo esc_html($card['title']); ?></h3>
                        <p class="dept-card-desc"><?php echo esc_html($card['description']); ?></p>
                        <?php if (!empty($card['btn_text'])) : ?>
                            <a href="<?php echo esc_url(!empty($card['btn_url']) ? $card['btn_url'] : '#'); ?>" class="dept-action-btn"><?php echo esc_html($card['btn_text']); ?><i class="fas fa-arrow-right" aria-hidden="true"></i></a>
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
    <section class="academics-section section-spacing" id="academics">
        <div class="container">
            <div class="academics-card-box landing-motion-card">
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
                                        <div class="acad-item-card landing-motion-card">
                                            <a href="<?php echo esc_url(cic_academic_link($item['title'], $item['url'] ?? '')); ?>">
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

    <?php
    $gallery_images = array();
    if (!empty($gallery_settings['images']) && is_array($gallery_settings['images'])) {
        foreach ($gallery_settings['images'] as $g_item) {
            if (!empty($g_item['image_url'])) {
                $gallery_images[] = $g_item;
            }
        }
    }

    // If no image is there, nothing will render
    if (!empty($gallery_images)) :
        // Seamless repeat logic: pad small lists so that one cycle has at least 14 items
        $loop_items = $gallery_images;
        while (count($loop_items) < 14) {
            $loop_items = array_merge($loop_items, $gallery_images);
        }

        $speed_val = !empty($gallery_settings['speed']) ? $gallery_settings['speed'] : 'normal';
        $pause_val = !empty($gallery_settings['pause_on_hover']) ? 'pause-on-hover' : '';
    ?>
    <!-- Photo Gallery Marquee Section (Between Academics & Resources and Footer) -->
    <section class="landing-gallery-section" aria-label="<?php esc_attr_e('Department Photo Gallery', 'cic-theme'); ?>">
        <div class="landing-gallery-marquee-container <?php echo esc_attr($pause_val); ?>">
            <div class="landing-gallery-track speed-<?php echo esc_attr($speed_val); ?>">
                <!-- Group 1 -->
                <div class="landing-gallery-group">
                    <?php foreach ($loop_items as $item) : ?>
                        <div class="landing-gallery-card landing-motion-card">
                            <img src="<?php echo esc_url($item['image_url']); ?>" alt="<?php echo esc_attr(!empty($item['alt']) ? $item['alt'] : 'Gallery Photo'); ?>" loading="lazy">
                        </div>
                    <?php endforeach; ?>
                </div>
                <!-- Group 2 (Duplicate for continuous infinite right-to-left marquee) -->
                <div class="landing-gallery-group" aria-hidden="true">
                    <?php foreach ($loop_items as $item) : ?>
                        <div class="landing-gallery-card landing-motion-card">
                            <img src="<?php echo esc_url($item['image_url']); ?>" alt="<?php echo esc_attr(!empty($item['alt']) ? $item['alt'] : 'Gallery Photo'); ?>" loading="lazy">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

</main>

<?php get_footer(); ?>
