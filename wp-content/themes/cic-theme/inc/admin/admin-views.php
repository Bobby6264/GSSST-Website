<?php
/**
 * GSSST Theme Admin Panel - Page View Renderers
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Common Admin Navigation Tabs Bar
 */
function cic_admin_nav_tabs($active_tab = 'hero') {
    $tabs = array(
        'hero'           => array('title' => __('Hero Section', 'cic-theme'), 'url' => admin_url('admin.php?page=cic-landing-hero'), 'icon' => 'dashicons-format-image'),
        'news'           => array('title' => __('News & Ticker', 'cic-theme'), 'url' => admin_url('admin.php?page=cic-landing-news'), 'icon' => 'dashicons-megaphone'),
        'about-head'     => array('title' => __('About & Head', 'cic-theme'), 'url' => admin_url('admin.php?page=cic-landing-about-head'), 'icon' => 'dashicons-id-alt'),
        'dept'           => array('title' => __('Explore Department', 'cic-theme'), 'url' => admin_url('admin.php?page=cic-landing-dept'), 'icon' => 'dashicons-grid-view'),
        'academics'      => array('title' => __('Academics & Resources', 'cic-theme'), 'url' => admin_url('admin.php?page=cic-landing-academics'), 'icon' => 'dashicons-welcome-learn-more'),
        'header-footer'  => array('title' => __('Header & Footer', 'cic-theme'), 'url' => admin_url('admin.php?page=cic-header-footer'), 'icon' => 'dashicons-layout'),
    );
    ?>
    <div class="cic-admin-header-bar">
        <div class="cic-admin-brand">
            <span class="dashicons dashicons-welcome-widgets-menus"></span>
            <div>
                <h2><?php esc_html_e('GSSST Website Settings', 'cic-theme'); ?></h2>
                <p class="cic-admin-tagline"><?php esc_html_e('Manage Landing Page content, Navigation menus, and Site-wide Header & Footer', 'cic-theme'); ?></p>
            </div>
        </div>
        <nav class="cic-admin-tabs">
            <?php foreach ($tabs as $key => $tab) : ?>
                <a href="<?php echo esc_url($tab['url']); ?>" class="cic-tab-link <?php echo $active_tab === $key ? 'active' : ''; ?>">
                    <span class="dashicons <?php echo esc_attr($tab['icon']); ?>"></span>
                    <?php echo esc_html($tab['title']); ?>
                </a>
            <?php endforeach; ?>
        </nav>
    </div>
    <?php
}

/**
 * 1. Hero Section Settings Page
 */
function cic_render_hero_page() {
    $settings = cic_get_hero_settings();
    ?>
    <div class="wrap cic-admin-wrap">
        <?php cic_admin_nav_tabs('hero'); ?>

        <?php settings_errors(); ?>

        <form method="post" action="options.php" class="cic-admin-form">
            <?php
            settings_fields('cic_hero_settings_group');
            ?>
            <div class="cic-admin-card">
                <div class="cic-card-header">
                    <h3><span class="dashicons dashicons-format-aside"></span> <?php esc_html_e('Hero Copy & Headlines', 'cic-theme'); ?></h3>
                    <p><?php esc_html_e('Customize the prominent text overlay displayed on the landing page hero slider.', 'cic-theme'); ?></p>
                </div>
                <div class="cic-card-body">
                    <div class="cic-field-group">
                        <label for="hero_title"><?php esc_html_e('Hero Main Title', 'cic-theme'); ?></label>
                        <input type="text" id="hero_title" name="cic_hero_settings[title]" value="<?php echo esc_attr($settings['title']); ?>" class="large-text" placeholder="Advancing the Frontiers of Technology" required>
                    </div>

                    <div class="cic-field-group">
                        <label for="hero_subtitle"><?php esc_html_e('Hero Subtitle / Description', 'cic-theme'); ?></label>
                        <textarea id="hero_subtitle" name="cic_hero_settings[subtitle]" rows="3" class="large-text"><?php echo esc_textarea($settings['subtitle']); ?></textarea>
                    </div>
                </div>
            </div>


            <!-- Action Buttons -->
            <div class="cic-admin-card">
                <div class="cic-card-header">
                    <h3><span class="dashicons dashicons-button"></span> <?php esc_html_e('Hero Call-to-Action Buttons', 'cic-theme'); ?></h3>
                    <p><?php esc_html_e('Manage buttons displayed on the hero banner. You can edit names, URLs, add more buttons, or remove them.', 'cic-theme'); ?></p>
                </div>
                <div class="cic-card-body">
                    <div class="cic-repeater" id="hero-buttons-repeater">
                        <table class="widefat cic-repeater-table">
                            <thead>
                                <tr>
                                    <th style="width: 30px;"></th>
                                    <th><?php esc_html_e('Button Text', 'cic-theme'); ?></th>
                                    <th><?php esc_html_e('Destination URL', 'cic-theme'); ?></th>
                                    <th style="width: 130px;"><?php esc_html_e('Style', 'cic-theme'); ?></th>
                                    <th style="width: 100px;"><?php esc_html_e('New Tab?', 'cic-theme'); ?></th>
                                    <th style="width: 60px;"></th>
                                </tr>
                            </thead>
                            <tbody class="cic-repeater-items" data-name="cic_hero_settings[buttons]">
                                <?php 
                                if (!empty($settings['buttons'])) :
                                    foreach ($settings['buttons'] as $index => $btn) :
                                        $btn_text = isset($btn['text']) ? $btn['text'] : '';
                                        $btn_url  = isset($btn['url']) ? $btn['url'] : '';
                                        $btn_style = isset($btn['style']) ? $btn['style'] : 'primary';
                                        $new_tab  = !empty($btn['new_tab']) ? 1 : 0;
                                ?>
                                    <tr class="cic-repeater-row">
                                        <td class="cic-row-handle"><span class="dashicons dashicons-menu"></span></td>
                                        <td>
                                            <input type="text" name="cic_hero_settings[buttons][<?php echo $index; ?>][text]" value="<?php echo esc_attr($btn_text); ?>" class="regular-text" placeholder="e.g. Explore Programmes" required>
                                        </td>
                                        <td>
                                            <input type="text" name="cic_hero_settings[buttons][<?php echo $index; ?>][url]" value="<?php echo esc_attr($btn_url); ?>" class="regular-text" placeholder="https://... or #" required>
                                        </td>
                                        <td>
                                            <select name="cic_hero_settings[buttons][<?php echo $index; ?>][style]">
                                                <option value="primary" <?php selected($btn_style, 'primary'); ?>>Primary (Blue)</option>
                                                <option value="secondary" <?php selected($btn_style, 'secondary'); ?>>Secondary (Glass)</option>
                                                <option value="outline" <?php selected($btn_style, 'outline'); ?>>Outline</option>
                                            </select>
                                        </td>
                                        <td style="text-align:center;">
                                            <input type="checkbox" name="cic_hero_settings[buttons][<?php echo $index; ?>][new_tab]" value="1" <?php checked($new_tab, 1); ?>>
                                        </td>
                                        <td>
                                            <button type="button" class="button-link cic-row-delete-btn text-danger" title="Delete Button"><span class="dashicons dashicons-trash"></span></button>
                                        </td>
                                    </tr>
                                <?php 
                                    endforeach;
                                endif; 
                                ?>
                            </tbody>
                        </table>
                        <div style="margin-top: 12px;">
                            <button type="button" class="button button-secondary cic-repeater-add-btn-btn"><span class="dashicons dashicons-plus-alt2"></span> <?php esc_html_e('Add Action Button', 'cic-theme'); ?></button>
                        </div>
                    </div>
                </div>
            </div>

            <?php submit_button(__('Save Hero Changes', 'cic-theme'), 'primary button-hero-save'); ?>
        </form>
    </div>
    <?php
}

/**
 * 2. News & Ticker Settings Page (with direct management of Events/News/Notices)
 */
function cic_render_news_page() {
    $settings = cic_get_news_settings();
    ?>
    <div class="wrap cic-admin-wrap">
        <?php cic_admin_nav_tabs('news'); ?>

        <?php settings_errors(); ?>

        <form method="post" action="options.php" class="cic-admin-form">
            <?php
            settings_fields('cic_news_settings_group');
            ?>
            <div class="cic-admin-card">
                <div class="cic-card-header">
                    <h3><span class="dashicons dashicons-admin-settings"></span> <?php esc_html_e('Display Counts on Landing Page', 'cic-theme'); ?></h3>
                    <p><?php esc_html_e('Control how many items are fetched and displayed across the Latest ticker and the News/Events tabbed box.', 'cic-theme'); ?></p>
                </div>
                <div class="cic-card-body">
                    <div class="cic-fields-grid-3">
                        <div class="cic-field-group">
                            <label for="ticker_count"><?php esc_html_e('Latest Ticker Messages Count', 'cic-theme'); ?></label>
                            <input type="number" id="ticker_count" name="cic_news_settings[ticker_count]" value="<?php echo esc_attr($settings['ticker_count']); ?>" min="1" max="50" class="small-text">
                            <p class="description"><?php esc_html_e('Number of notices/announcements scrolling in the ticker.', 'cic-theme'); ?></p>
                        </div>

                        <div class="cic-field-group">
                            <label for="events_count"><?php esc_html_e('Events Tab Items Count', 'cic-theme'); ?></label>
                            <input type="number" id="events_count" name="cic_news_settings[events_count]" value="<?php echo esc_attr($settings['events_count']); ?>" min="1" max="50" class="small-text">
                            <p class="description"><?php esc_html_e('Maximum events shown in the Events tab.', 'cic-theme'); ?></p>
                        </div>

                        <div class="cic-field-group">
                            <label for="news_count"><?php esc_html_e('News Tab Items Count', 'cic-theme'); ?></label>
                            <input type="number" id="news_count" name="cic_news_settings[news_count]" value="<?php echo esc_attr($settings['news_count']); ?>" min="1" max="50" class="small-text">
                            <p class="description"><?php esc_html_e('Maximum news items shown in the News tab.', 'cic-theme'); ?></p>
                        </div>
                    </div>
                </div>
                <div class="cic-card-footer">
                    <?php submit_button(__('Save Count Settings', 'cic-theme'), 'primary', 'submit', false); ?>
                </div>
            </div>
        </form>

        <!-- Direct News, Events, and Notices Management Dashboard -->
        <div class="cic-admin-grid-2">
            <!-- Events Section -->
            <div class="cic-admin-card">
                <div class="cic-card-header cic-header-between">
                    <div>
                        <h3><span class="dashicons dashicons-calendar-alt"></span> <?php esc_html_e('Events Management', 'cic-theme'); ?></h3>
                        <p><?php esc_html_e('Manage events linked directly to detailed single event pages.', 'cic-theme'); ?></p>
                    </div>
                    <a href="<?php echo esc_url(admin_url('post-new.php?post_type=event')); ?>" class="button button-primary"><span class="dashicons dashicons-plus"></span> <?php esc_html_e('Add New Event', 'cic-theme'); ?></a>
                </div>
                <div class="cic-card-body cic-no-padding">
                    <?php
                    $events_query = new WP_Query(array('post_type' => 'event', 'posts_per_page' => 5));
                    if ($events_query->have_posts()) :
                    ?>
                        <table class="widefat striped">
                            <thead>
                                <tr>
                                    <th><?php esc_html_e('Title', 'cic-theme'); ?></th>
                                    <th style="width: 100px;"><?php esc_html_e('Date', 'cic-theme'); ?></th>
                                    <th style="width: 120px;"><?php esc_html_e('Actions', 'cic-theme'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($events_query->have_posts()) : $events_query->the_post(); ?>
                                    <tr>
                                        <td><strong><a href="<?php echo esc_url(get_edit_post_link()); ?>"><?php the_title(); ?></a></strong></td>
                                        <td><?php echo get_the_date('M j, Y'); ?></td>
                                        <td>
                                            <a href="<?php echo esc_url(get_edit_post_link()); ?>" class="button button-small" title="Edit Event"><span class="dashicons dashicons-edit"></span></a>
                                            <a href="<?php echo esc_url(get_permalink()); ?>" target="_blank" class="button button-small" title="View Event"><span class="dashicons dashicons-visibility"></span></a>
                                            <a href="<?php echo esc_url(get_delete_post_link()); ?>" class="button button-small text-danger" onclick="return confirm('Are you sure you want to delete this event?');" title="Delete"><span class="dashicons dashicons-trash"></span></a>
                                        </td>
                                    </tr>
                                <?php endwhile; wp_reset_postdata(); ?>
                            </tbody>
                        </table>
                        <div class="cic-card-footer-link">
                            <a href="<?php echo esc_url(admin_url('edit.php?post_type=event')); ?>"><?php esc_html_e('View all Events →', 'cic-theme'); ?></a>
                        </div>
                    <?php else : ?>
                        <div class="cic-empty-state">
                            <p><?php esc_html_e('No events published yet. Fallback demo events are currently being displayed on the homepage.', 'cic-theme'); ?></p>
                            <a href="<?php echo esc_url(admin_url('post-new.php?post_type=event')); ?>" class="button"><?php esc_html_e('Create your first Event', 'cic-theme'); ?></a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- News Section -->
            <div class="cic-admin-card">
                <div class="cic-card-header cic-header-between">
                    <div>
                        <h3><span class="dashicons dashicons-format-aside"></span> <?php esc_html_e('News Management', 'cic-theme'); ?></h3>
                        <p><?php esc_html_e('Manage news stories with full articles and descriptions.', 'cic-theme'); ?></p>
                    </div>
                    <a href="<?php echo esc_url(admin_url('post-new.php?post_type=news')); ?>" class="button button-primary"><span class="dashicons dashicons-plus"></span> <?php esc_html_e('Add New News Item', 'cic-theme'); ?></a>
                </div>
                <div class="cic-card-body cic-no-padding">
                    <?php
                    $news_query = new WP_Query(array('post_type' => 'news', 'posts_per_page' => 5));
                    if ($news_query->have_posts()) :
                    ?>
                        <table class="widefat striped">
                            <thead>
                                <tr>
                                    <th><?php esc_html_e('Title', 'cic-theme'); ?></th>
                                    <th style="width: 100px;"><?php esc_html_e('Date', 'cic-theme'); ?></th>
                                    <th style="width: 120px;"><?php esc_html_e('Actions', 'cic-theme'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($news_query->have_posts()) : $news_query->the_post(); ?>
                                    <tr>
                                        <td><strong><a href="<?php echo esc_url(get_edit_post_link()); ?>"><?php the_title(); ?></a></strong></td>
                                        <td><?php echo get_the_date('M j, Y'); ?></td>
                                        <td>
                                            <a href="<?php echo esc_url(get_edit_post_link()); ?>" class="button button-small" title="Edit News"><span class="dashicons dashicons-edit"></span></a>
                                            <a href="<?php echo esc_url(get_permalink()); ?>" target="_blank" class="button button-small" title="View News"><span class="dashicons dashicons-visibility"></span></a>
                                            <a href="<?php echo esc_url(get_delete_post_link()); ?>" class="button button-small text-danger" onclick="return confirm('Are you sure you want to delete this news item?');" title="Delete"><span class="dashicons dashicons-trash"></span></a>
                                        </td>
                                    </tr>
                                <?php endwhile; wp_reset_postdata(); ?>
                            </tbody>
                        </table>
                        <div class="cic-card-footer-link">
                            <a href="<?php echo esc_url(admin_url('edit.php?post_type=news')); ?>"><?php esc_html_e('View all News →', 'cic-theme'); ?></a>
                        </div>
                    <?php else : ?>
                        <div class="cic-empty-state">
                            <p><?php esc_html_e('No news items published yet. Fallback demo news are currently shown on the homepage.', 'cic-theme'); ?></p>
                            <a href="<?php echo esc_url(admin_url('post-new.php?post_type=news')); ?>" class="button"><?php esc_html_e('Create your first News Item', 'cic-theme'); ?></a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Notices Section -->
        <div class="cic-admin-card" style="margin-top: 20px;">
            <div class="cic-card-header cic-header-between">
                <div>
                    <h3><span class="dashicons dashicons-megaphone"></span> <?php esc_html_e('Notices & Announcements for Latest Ticker', 'cic-theme'); ?></h3>
                    <p><?php esc_html_e('Notices published here automatically stream inside the continuous infinite scrolling LATEST ticker.', 'cic-theme'); ?></p>
                </div>
                <a href="<?php echo esc_url(admin_url('post-new.php?post_type=notice')); ?>" class="button button-primary"><span class="dashicons dashicons-plus"></span> <?php esc_html_e('Add New Notice', 'cic-theme'); ?></a>
            </div>
            <div class="cic-card-body cic-no-padding">
                <?php
                $notices_query = new WP_Query(array('post_type' => array('notice', 'announcement'), 'posts_per_page' => 5));
                if ($notices_query->have_posts()) :
                ?>
                    <table class="widefat striped">
                        <thead>
                            <tr>
                                <th><?php esc_html_e('Title', 'cic-theme'); ?></th>
                                <th style="width: 100px;"><?php esc_html_e('Date', 'cic-theme'); ?></th>
                                <th style="width: 120px;"><?php esc_html_e('Actions', 'cic-theme'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($notices_query->have_posts()) : $notices_query->the_post(); ?>
                                <tr>
                                    <td><strong><a href="<?php echo esc_url(get_edit_post_link()); ?>"><?php the_title(); ?></a></strong></td>
                                    <td><?php echo get_the_date('M j, Y'); ?></td>
                                    <td>
                                        <a href="<?php echo esc_url(get_edit_post_link()); ?>" class="button button-small" title="Edit Notice"><span class="dashicons dashicons-edit"></span></a>
                                        <a href="<?php echo esc_url(get_permalink()); ?>" target="_blank" class="button button-small" title="View Notice"><span class="dashicons dashicons-visibility"></span></a>
                                        <a href="<?php echo esc_url(get_delete_post_link()); ?>" class="button button-small text-danger" onclick="return confirm('Are you sure you want to delete this notice?');" title="Delete"><span class="dashicons dashicons-trash"></span></a>
                                    </td>
                                </tr>
                            <?php endwhile; wp_reset_postdata(); ?>
                        </tbody>
                    </table>
                    <div class="cic-card-footer-link">
                        <a href="<?php echo esc_url(admin_url('edit.php?post_type=notice')); ?>"><?php esc_html_e('View all Notices & Announcements →', 'cic-theme'); ?></a>
                    </div>
                <?php else : ?>
                    <div class="cic-empty-state">
                        <p><?php esc_html_e('No notices published yet. Fallback notices are active.', 'cic-theme'); ?></p>
                        <a href="<?php echo esc_url(admin_url('post-new.php?post_type=notice')); ?>" class="button"><?php esc_html_e('Create your first Notice', 'cic-theme'); ?></a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
}

/**
 * 3. About Us & Head's Message Settings Page
 */
function cic_render_about_head_page() {
    $settings = cic_get_about_head_settings();
    ?>
    <div class="wrap cic-admin-wrap">
        <?php cic_admin_nav_tabs('about-head'); ?>

        <?php settings_errors(); ?>

        <form method="post" action="options.php" class="cic-admin-form">
            <?php
            settings_fields('cic_about_head_settings_group');
            ?>
            <!-- About Us Section -->
            <div class="cic-admin-card">
                <div class="cic-card-header">
                    <h3><span class="dashicons dashicons-welcome-learn-more"></span> <?php esc_html_e('About the School Card Settings', 'cic-theme'); ?></h3>
                    <p><?php esc_html_e('Configure the introductory description, visibility line limits, and key info table.', 'cic-theme'); ?></p>
                </div>
                <div class="cic-card-body">
                    <div class="cic-fields-grid-2">
                        <div class="cic-field-group">
                            <label for="about_title"><?php esc_html_e('Section Title', 'cic-theme'); ?></label>
                            <input type="text" id="about_title" name="cic_about_head_settings[about_title]" value="<?php echo esc_attr($settings['about_title']); ?>" class="large-text" placeholder="About the School">
                        </div>

                        <div class="cic-field-group">
                            <label for="established_text"><?php esc_html_e('Established Tag / Pill', 'cic-theme'); ?></label>
                            <input type="text" id="established_text" name="cic_about_head_settings[established_text]" value="<?php echo esc_attr($settings['established_text']); ?>" class="regular-text" placeholder="Established 1996">
                        </div>
                    </div>

                    <div class="cic-field-group">
                        <label for="about_body"><?php esc_html_e('About Us Paragraphs / Content', 'cic-theme'); ?></label>
                        <textarea id="about_body" name="cic_about_head_settings[about_body]" rows="5" class="large-text"><?php echo esc_textarea($settings['about_body']); ?></textarea>
                    </div>

                    <div class="cic-fields-grid-3">
                        <div class="cic-field-group">
                            <label for="visible_lines"><?php esc_html_e('Visible Lines Limit', 'cic-theme'); ?></label>
                            <select id="visible_lines" name="cic_about_head_settings[visible_lines]">
                                <option value="0" <?php selected($settings['visible_lines'], 0); ?>>Show All Lines (No limit)</option>
                                <option value="2" <?php selected($settings['visible_lines'], 2); ?>>2 Lines</option>
                                <option value="3" <?php selected($settings['visible_lines'], 3); ?>>3 Lines</option>
                                <option value="4" <?php selected($settings['visible_lines'], 4); ?>>4 Lines</option>
                                <option value="5" <?php selected($settings['visible_lines'], 5); ?>>5 Lines</option>
                                <option value="6" <?php selected($settings['visible_lines'], 6); ?>>6 Lines</option>
                                <option value="8" <?php selected($settings['visible_lines'], 8); ?>>8 Lines</option>
                            </select>
                            <p class="description"><?php esc_html_e('Choose how many lines of text are visible before truncation.', 'cic-theme'); ?></p>
                        </div>

                        <div class="cic-field-group">
                            <label for="read_more_text"><?php esc_html_e('Read More Button Label', 'cic-theme'); ?></label>
                            <input type="text" id="read_more_text" name="cic_about_head_settings[read_more_text]" value="<?php echo esc_attr($settings['read_more_text']); ?>" class="regular-text" placeholder="Read more about the School">
                        </div>

                        <div class="cic-field-group">
                            <label for="read_more_url"><?php esc_html_e('Read More Button URL', 'cic-theme'); ?></label>
                            <input type="text" id="read_more_url" name="cic_about_head_settings[read_more_url]" value="<?php echo esc_attr($settings['read_more_url']); ?>" class="regular-text" placeholder="https://... or #">
                        </div>
                    </div>

                    <!-- Details Table Repeater -->
                    <div class="cic-repeater" id="about-details-repeater" style="margin-top: 20px;">
                        <label><strong><?php esc_html_e('About the School Key Details Table (Edit, Delete, Update)', 'cic-theme'); ?></strong></label>
                        <p class="description"><?php esc_html_e('Rows shown in the key information box (e.g. Location, Affiliation, Accreditation).', 'cic-theme'); ?></p>
                        
                        <table class="widefat cic-repeater-table" style="margin-top: 8px;">
                            <thead>
                                <tr>
                                    <th style="width: 30px;"></th>
                                    <th><?php esc_html_e('Label (e.g. Location)', 'cic-theme'); ?></th>
                                    <th><?php esc_html_e('Value (e.g. IIT Campus, Kharagpur)', 'cic-theme'); ?></th>
                                    <th style="width: 50px;"></th>
                                </tr>
                            </thead>
                            <tbody class="cic-repeater-items" data-name="cic_about_head_settings[details_table]">
                                <?php
                                if (!empty($settings['details_table'])) :
                                    foreach ($settings['details_table'] as $index => $row) :
                                        $label = isset($row['label']) ? $row['label'] : '';
                                        $value = isset($row['value']) ? $row['value'] : '';
                                ?>
                                    <tr class="cic-repeater-row">
                                        <td class="cic-row-handle"><span class="dashicons dashicons-menu"></span></td>
                                        <td>
                                            <input type="text" name="cic_about_head_settings[details_table][<?php echo $index; ?>][label]" value="<?php echo esc_attr($label); ?>" class="regular-text" placeholder="Label">
                                        </td>
                                        <td>
                                            <input type="text" name="cic_about_head_settings[details_table][<?php echo $index; ?>][value]" value="<?php echo esc_attr($value); ?>" class="regular-text" placeholder="Value">
                                        </td>
                                        <td>
                                            <button type="button" class="button-link cic-row-delete-btn text-danger" title="Delete Row"><span class="dashicons dashicons-trash"></span></button>
                                        </td>
                                    </tr>
                                <?php
                                    endforeach;
                                endif;
                                ?>
                            </tbody>
                        </table>
                        <div style="margin-top: 8px;">
                            <button type="button" class="button button-secondary cic-repeater-add-detail-btn"><span class="dashicons dashicons-plus-alt2"></span> <?php esc_html_e('Add Detail Row', 'cic-theme'); ?></button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Head's Message Section -->
            <div class="cic-admin-card">
                <div class="cic-card-header">
                    <h3><span class="dashicons dashicons-id-alt"></span> <?php esc_html_e('Message from the Head Section', 'cic-theme'); ?></h3>
                    <p><?php esc_html_e('Edit the official message from the Head of Department and profile details.', 'cic-theme'); ?></p>
                </div>
                <div class="cic-card-body">
                    <div class="cic-fields-grid-2">
                        <div class="cic-field-group">
                            <label for="head_tagline"><?php esc_html_e('Section Tagline', 'cic-theme'); ?></label>
                            <input type="text" id="head_tagline" name="cic_about_head_settings[head_tagline]" value="<?php echo esc_attr($settings['head_tagline']); ?>" class="large-text" placeholder="— MESSAGE FROM THE HEAD">
                        </div>

                        <div class="cic-field-group">
                            <label for="head_title"><?php esc_html_e('Heading Title (HTML allowed)', 'cic-theme'); ?></label>
                            <input type="text" id="head_title" name="cic_about_head_settings[head_title]" value="<?php echo esc_attr($settings['head_title']); ?>" class="large-text" placeholder="Welcome to the G.S. Sanyal School of Technology">
                        </div>
                    </div>

                    <div class="cic-field-group">
                        <label for="head_message"><?php esc_html_e('Head Message Content', 'cic-theme'); ?></label>
                        <textarea id="head_message" name="cic_about_head_settings[head_message]" rows="6" class="large-text"><?php echo esc_textarea($settings['head_message']); ?></textarea>
                        <p class="description"><?php esc_html_e('You can write multiple paragraphs. Separate them with empty lines.', 'cic-theme'); ?></p>
                    </div>

                    <div class="cic-fields-grid-3" style="margin-top: 15px;">
                        <div class="cic-field-group">
                            <label for="head_name"><?php esc_html_e('Head Name', 'cic-theme'); ?></label>
                            <input type="text" id="head_name" name="cic_about_head_settings[head_name]" value="<?php echo esc_attr($settings['head_name']); ?>" class="regular-text" placeholder="Prof. Vikram Desai">
                        </div>

                        <div class="cic-field-group">
                            <label for="head_role"><?php esc_html_e('Designation / Role', 'cic-theme'); ?></label>
                            <input type="text" id="head_role" name="cic_about_head_settings[head_role]" value="<?php echo esc_attr($settings['head_role']); ?>" class="regular-text" placeholder="Professor & Head, G.S. Sanyal School of Technology">
                        </div>

                        <div class="cic-field-group">
                            <label for="head_email"><?php esc_html_e('Head Email Address', 'cic-theme'); ?></label>
                            <input type="email" id="head_email" name="cic_about_head_settings[head_email]" value="<?php echo esc_attr($settings['head_email']); ?>" class="regular-text" placeholder="vdesai@gssst.iitkgp.ac.in">
                        </div>
                    </div>

                    <!-- Head Photo -->
                    <div class="cic-field-group" style="margin-top: 15px;">
                        <label><?php esc_html_e('Head Portrait Photo', 'cic-theme'); ?></label>
                        <div class="cic-media-picker-group">
                            <div class="cic-media-preview-box cic-portrait-preview">
                                <img src="<?php echo esc_url($settings['head_photo_url']); ?>" alt="Head Photo" class="cic-img-preview" style="<?php echo empty($settings['head_photo_url']) ? 'display:none;' : ''; ?>">
                                <div class="cic-placeholder" style="<?php echo !empty($settings['head_photo_url']) ? 'display:none;' : ''; ?>"><span class="dashicons dashicons-businessman"></span></div>
                            </div>
                            <div>
                                <input type="text" name="cic_about_head_settings[head_photo_url]" value="<?php echo esc_attr($settings['head_photo_url']); ?>" class="regular-text cic-media-url-input" placeholder="Image URL or upload from Media Library">
                                <button type="button" class="button cic-media-upload-btn"><span class="dashicons dashicons-upload"></span> <?php esc_html_e('Upload / Select Photo', 'cic-theme'); ?></button>
                                <button type="button" class="button-link cic-media-remove-btn text-danger" style="<?php echo empty($settings['head_photo_url']) ? 'display:none;' : ''; ?>"><?php esc_html_e('Remove Photo', 'cic-theme'); ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php submit_button(__('Save About & Head Changes', 'cic-theme'), 'primary'); ?>
        </form>
    </div>
    <?php
}

/**
 * 4. Explore Our Department Settings Page
 */
function cic_render_dept_page() {
    $settings = cic_get_dept_settings();
    ?>
    <div class="wrap cic-admin-wrap">
        <?php cic_admin_nav_tabs('dept'); ?>

        <?php settings_errors(); ?>

        <form method="post" action="options.php" class="cic-admin-form">
            <?php
            settings_fields('cic_dept_settings_group');
            ?>
            <div class="cic-admin-card">
                <div class="cic-card-header">
                    <h3><span class="dashicons dashicons-grid-view"></span> <?php esc_html_e('Explore Department Section', 'cic-theme'); ?></h3>
                    <p><?php esc_html_e('Add, edit, or delete the cards shown under Explore Our Department.', 'cic-theme'); ?></p>
                </div>
                <div class="cic-card-body">
                    <div class="cic-field-group">
                        <label for="section_title"><?php esc_html_e('Section Heading', 'cic-theme'); ?></label>
                        <input type="text" id="section_title" name="cic_dept_settings[section_title]" value="<?php echo esc_attr($settings['section_title']); ?>" class="large-text" placeholder="Explore Our Department">
                    </div>

                    <!-- Department Cards Repeater -->
                    <div class="cic-repeater" id="dept-cards-repeater" style="margin-top: 20px;">
                        <label><strong><?php esc_html_e('Department Cards (Add, Edit, Delete, Reorder)', 'cic-theme'); ?></strong></label>
                        
                        <div class="cic-repeater-cards cic-repeater-items" data-name="cic_dept_settings[cards]">
                            <?php 
                            if (!empty($settings['cards'])) :
                                foreach ($settings['cards'] as $index => $card) :
                                    $title = isset($card['title']) ? $card['title'] : '';
                                    $desc  = isset($card['description']) ? $card['description'] : '';
                                    $b_text = isset($card['btn_text']) ? $card['btn_text'] : '';
                                    $b_url  = isset($card['btn_url']) ? $card['btn_url'] : '';
                                    $icon   = isset($card['icon_type']) ? $card['icon_type'] : 'chip';
                            ?>
                                <div class="cic-repeater-row cic-card-item">
                                    <div class="cic-card-item-header">
                                        <span class="cic-row-handle"><span class="dashicons dashicons-menu"></span></span>
                                        <h4 class="cic-card-item-title"><?php echo !empty($title) ? esc_html($title) : 'Card #' . ($index + 1); ?></h4>
                                        <button type="button" class="button-link cic-row-delete-btn text-danger" title="Delete Card"><span class="dashicons dashicons-trash"></span></button>
                                    </div>
                                    <div class="cic-card-item-body">
                                        <div class="cic-fields-grid-2">
                                            <div class="cic-field-group">
                                                <label><?php esc_html_e('Card Title', 'cic-theme'); ?></label>
                                                <input type="text" name="cic_dept_settings[cards][<?php echo $index; ?>][title]" value="<?php echo esc_attr($title); ?>" class="regular-text card-title-input" placeholder="e.g. Research & Innovation" required>
                                            </div>
                                            <div class="cic-field-group">
                                                <label><?php esc_html_e('Card Icon', 'cic-theme'); ?></label>
                                                <select name="cic_dept_settings[cards][<?php echo $index; ?>][icon_type]">
                                                    <option value="chip" <?php selected($icon, 'chip'); ?>>Microchip / Processor (Research)</option>
                                                    <option value="users" <?php selected($icon, 'users'); ?>>Users / Team (Faculty & Staff)</option>
                                                    <option value="award" <?php selected($icon, 'award'); ?>>Award / Trophy (Awards)</option>
                                                    <option value="book" <?php selected($icon, 'book'); ?>>Book / Graduation (Academics)</option>
                                                    <option value="flask" <?php selected($icon, 'flask'); ?>>Flask / Science (Labs)</option>
                                                    <option value="laptop" <?php selected($icon, 'laptop'); ?>>Laptop / Code (Technology)</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="cic-field-group">
                                            <label><?php esc_html_e('Card Description', 'cic-theme'); ?></label>
                                            <textarea name="cic_dept_settings[cards][<?php echo $index; ?>][description]" rows="2" class="large-text" placeholder="Short description..."><?php echo esc_textarea($desc); ?></textarea>
                                        </div>

                                        <div class="cic-fields-grid-2">
                                            <div class="cic-field-group">
                                                <label><?php esc_html_e('Button Text', 'cic-theme'); ?></label>
                                                <input type="text" name="cic_dept_settings[cards][<?php echo $index; ?>][btn_text]" value="<?php echo esc_attr($b_text); ?>" class="regular-text" placeholder="Explore">
                                            </div>
                                            <div class="cic-field-group">
                                                <label><?php esc_html_e('Button Destination URL', 'cic-theme'); ?></label>
                                                <input type="text" name="cic_dept_settings[cards][<?php echo $index; ?>][btn_url]" value="<?php echo esc_attr($b_url); ?>" class="regular-text" placeholder="https://... or #">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php 
                                endforeach;
                            endif; 
                            ?>
                        </div>
                        <div style="margin-top: 15px;">
                            <button type="button" class="button button-secondary cic-repeater-add-dept-card-btn"><span class="dashicons dashicons-plus-alt2"></span> <?php esc_html_e('Add Department Card', 'cic-theme'); ?></button>
                        </div>
                    </div>
                </div>
            </div>

            <?php submit_button(__('Save Department Changes', 'cic-theme'), 'primary'); ?>
        </form>
    </div>
    <?php
}

/**
 * 5. Academics & Resources Settings Page
 */
function cic_render_academics_page() {
    $settings = cic_get_academics_settings();
    ?>
    <div class="wrap cic-admin-wrap">
        <?php cic_admin_nav_tabs('academics'); ?>

        <?php settings_errors(); ?>

        <form method="post" action="options.php" class="cic-admin-form">
            <?php
            settings_fields('cic_academics_settings_group');
            ?>
            <div class="cic-admin-card">
                <div class="cic-card-header">
                    <h3><span class="dashicons dashicons-welcome-learn-more"></span> <?php esc_html_e('Academics & Resources Section', 'cic-theme'); ?></h3>
                    <p><?php esc_html_e('Manage columns and resource links for the bottom landing page section.', 'cic-theme'); ?></p>
                </div>
                <div class="cic-card-body">
                    <div class="cic-field-group">
                        <label for="acad_section_title"><?php esc_html_e('Section Heading', 'cic-theme'); ?></label>
                        <input type="text" id="acad_section_title" name="cic_academics_settings[section_title]" value="<?php echo esc_attr($settings['section_title']); ?>" class="large-text" placeholder="Academics & Resources">
                    </div>

                    <!-- Columns Repeater -->
                    <div class="cic-repeater" id="academics-cols-repeater" style="margin-top: 20px;">
                        <label><strong><?php esc_html_e('Columns & Academic Items', 'cic-theme'); ?></strong></label>
                        
                        <div class="cic-repeater-cards cic-repeater-items" data-name="cic_academics_settings[columns]">
                            <?php 
                            if (!empty($settings['columns'])) :
                                foreach ($settings['columns'] as $c_idx => $col) :
                                    $c_title = isset($col['col_title']) ? $col['col_title'] : '';
                                    $items   = isset($col['items']) ? $col['items'] : array();
                            ?>
                                <div class="cic-repeater-row cic-card-item cic-acad-col-item">
                                    <div class="cic-card-item-header">
                                        <span class="cic-row-handle"><span class="dashicons dashicons-menu"></span></span>
                                        <h4 class="cic-card-item-title"><?php echo !empty($c_title) ? esc_html($c_title) : 'Column #' . ($c_idx + 1); ?></h4>
                                        <button type="button" class="button-link cic-row-delete-btn text-danger" title="Delete Column"><span class="dashicons dashicons-trash"></span></button>
                                    </div>
                                    <div class="cic-card-item-body">
                                        <div class="cic-field-group">
                                            <label><?php esc_html_e('Column Title', 'cic-theme'); ?></label>
                                            <input type="text" name="cic_academics_settings[columns][<?php echo $c_idx; ?>][col_title]" value="<?php echo esc_attr($c_title); ?>" class="regular-text acad-col-title-input" placeholder="e.g. Postgraduate Taught" required>
                                        </div>

                                        <!-- Sub items for this column -->
                                        <div class="cic-sub-repeater" style="margin-top: 10px;">
                                            <label><?php esc_html_e('Items / Programs in this column:', 'cic-theme'); ?></label>
                                            <table class="widefat cic-repeater-table" style="margin-top: 5px;">
                                                <thead>
                                                    <tr>
                                                        <th><?php esc_html_e('Link Title', 'cic-theme'); ?></th>
                                                        <th><?php esc_html_e('Link Destination URL', 'cic-theme'); ?></th>
                                                        <th style="width: 40px;"></th>
                                                    </tr>
                                                </thead>
                                                <tbody class="cic-sub-repeater-items" data-parent-idx="<?php echo $c_idx; ?>">
                                                    <?php 
                                                    if (!empty($items)) :
                                                        foreach ($items as $i_idx => $item) :
                                                            $i_title = isset($item['title']) ? $item['title'] : '';
                                                            $i_url   = isset($item['url']) ? $item['url'] : '';
                                                    ?>
                                                        <tr class="cic-sub-row">
                                                            <td>
                                                                <input type="text" name="cic_academics_settings[columns][<?php echo $c_idx; ?>][items][<?php echo $i_idx; ?>][title]" value="<?php echo esc_attr($i_title); ?>" class="regular-text" placeholder="Title">
                                                            </td>
                                                            <td>
                                                                <input type="text" name="cic_academics_settings[columns][<?php echo $c_idx; ?>][items][<?php echo $i_idx; ?>][url]" value="<?php echo esc_attr($i_url); ?>" class="regular-text" placeholder="URL">
                                                            </td>
                                                            <td>
                                                                <button type="button" class="button-link cic-sub-row-delete-btn text-danger" title="Delete Item"><span class="dashicons dashicons-trash"></span></button>
                                                            </td>
                                                        </tr>
                                                    <?php 
                                                        endforeach;
                                                    endif; 
                                                    ?>
                                                </tbody>
                                            </table>
                                            <div style="margin-top: 5px;">
                                                <button type="button" class="button button-small cic-sub-add-item-btn" data-parent-idx="<?php echo $c_idx; ?>"><span class="dashicons dashicons-plus"></span> <?php esc_html_e('Add Item', 'cic-theme'); ?></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php 
                                endforeach;
                            endif; 
                            ?>
                        </div>
                        <div style="margin-top: 15px;">
                            <button type="button" class="button button-secondary cic-repeater-add-acad-col-btn"><span class="dashicons dashicons-plus-alt2"></span> <?php esc_html_e('Add Column', 'cic-theme'); ?></button>
                        </div>
                    </div>
                </div>
            </div>

            <?php submit_button(__('Save Academics Changes', 'cic-theme'), 'primary'); ?>
        </form>
    </div>
    <?php
}

/**
 * 6. Header & Footer Settings Page (Common to all pages)
 */
function cic_render_header_footer_page() {
    $header = cic_get_header_settings();
    $footer = cic_get_footer_settings();
    ?>
    <div class="wrap cic-admin-wrap">
        <?php cic_admin_nav_tabs('header-footer'); ?>

        <?php settings_errors(); ?>

        <!-- HEADER SETTINGS -->
        <form method="post" action="options.php" class="cic-admin-form" style="margin-bottom: 40px;">
            <?php settings_fields('cic_header_settings_group'); ?>
            
            <div class="cic-admin-card">
                <div class="cic-card-header">
                    <h3><span class="dashicons dashicons-heading"></span> <?php esc_html_e('Header: Branding & Logo', 'cic-theme'); ?></h3>
                    <p><?php esc_html_e('Change the department name, institute subtitle, and logo shown in the top header.', 'cic-theme'); ?></p>
                </div>
                <div class="cic-card-body">
                    <div class="cic-fields-grid-2">
                        <div class="cic-field-group">
                            <label for="dept_name"><?php esc_html_e('Department Name (Title)', 'cic-theme'); ?></label>
                            <input type="text" id="dept_name" name="cic_header_settings[dept_name]" value="<?php echo esc_attr($header['dept_name']); ?>" class="large-text" placeholder="G.S. Sanyal School of Technology" required>
                        </div>

                        <div class="cic-field-group">
                            <label for="dept_subtitle"><?php esc_html_e('Institution Subtitle', 'cic-theme'); ?></label>
                            <input type="text" id="dept_subtitle" name="cic_header_settings[dept_subtitle]" value="<?php echo esc_attr($header['dept_subtitle']); ?>" class="large-text" placeholder="INDIAN INSTITUTE OF TECHNOLOGY KHARAGPUR" required>
                        </div>
                    </div>

                    <!-- Logo Upload -->
                    <div class="cic-field-group" style="margin-top: 15px;">
                        <label><?php esc_html_e('Header Logo Image', 'cic-theme'); ?></label>
                        <div class="cic-media-picker-group">
                            <div class="cic-media-preview-box cic-logo-preview">
                                <img src="<?php echo esc_url($header['logo_url']); ?>" alt="Logo Preview" class="cic-img-preview" style="<?php echo empty($header['logo_url']) ? 'display:none;' : ''; ?>">
                                <div class="cic-placeholder" style="<?php echo !empty($header['logo_url']) ? 'display:none;' : ''; ?>"><span class="dashicons dashicons-format-image"></span></div>
                            </div>
                            <div>
                                <input type="text" name="cic_header_settings[logo_url]" value="<?php echo esc_attr($header['logo_url']); ?>" class="regular-text cic-media-url-input" placeholder="Logo image URL">
                                <button type="button" class="button cic-media-upload-btn"><span class="dashicons dashicons-upload"></span> <?php esc_html_e('Upload / Select Logo', 'cic-theme'); ?></button>
                                <button type="button" class="button-link cic-media-remove-btn text-danger" style="<?php echo empty($header['logo_url']) ? 'display:none;' : ''; ?>"><?php esc_html_e('Reset / Remove', 'cic-theme'); ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Navigation Links Repeater (HOME, RESEARCH, STAFF, FACULTY...) -->
            <div class="cic-admin-card">
                <div class="cic-card-header">
                    <h3><span class="dashicons dashicons-menu-alt"></span> <?php esc_html_e('Header: Top Navigation Menu Links', 'cic-theme'); ?></h3>
                    <p><?php esc_html_e('Edit, delete, and add links in the top header bar (e.g. HOME, RESEARCH, STAFF, FACULTY, IIT KGP).', 'cic-theme'); ?></p>
                </div>
                <div class="cic-card-body">
                    <div class="cic-repeater" id="top-nav-repeater">
                        <table class="widefat cic-repeater-table">
                            <thead>
                                <tr>
                                    <th style="width: 30px;"></th>
                                    <th><?php esc_html_e('Link Label', 'cic-theme'); ?></th>
                                    <th><?php esc_html_e('Destination URL', 'cic-theme'); ?></th>
                                    <th style="width: 100px;"><?php esc_html_e('New Tab?', 'cic-theme'); ?></th>
                                    <th style="width: 50px;"></th>
                                </tr>
                            </thead>
                            <tbody class="cic-repeater-items" data-name="cic_header_settings[top_nav_links]">
                                <?php 
                                if (!empty($header['top_nav_links'])) :
                                    foreach ($header['top_nav_links'] as $index => $item) :
                                        $t = isset($item['title']) ? $item['title'] : '';
                                        $u = isset($item['url']) ? $item['url'] : '';
                                        $nt = !empty($item['new_tab']) ? 1 : 0;
                                ?>
                                    <tr class="cic-repeater-row">
                                        <td class="cic-row-handle"><span class="dashicons dashicons-menu"></span></td>
                                        <td>
                                            <input type="text" name="cic_header_settings[top_nav_links][<?php echo $index; ?>][title]" value="<?php echo esc_attr($t); ?>" class="regular-text" placeholder="HOME" required>
                                        </td>
                                        <td>
                                            <input type="text" name="cic_header_settings[top_nav_links][<?php echo $index; ?>][url]" value="<?php echo esc_attr($u); ?>" class="regular-text" placeholder="https://... or #" required>
                                        </td>
                                        <td style="text-align:center;">
                                            <input type="checkbox" name="cic_header_settings[top_nav_links][<?php echo $index; ?>][new_tab]" value="1" <?php checked($nt, 1); ?>>
                                        </td>
                                        <td>
                                            <button type="button" class="button-link cic-row-delete-btn text-danger" title="Delete Link"><span class="dashicons dashicons-trash"></span></button>
                                        </td>
                                    </tr>
                                <?php 
                                    endforeach;
                                endif; 
                                ?>
                            </tbody>
                        </table>
                        <div style="margin-top: 10px;">
                            <button type="button" class="button button-secondary cic-repeater-add-top-link-btn"><span class="dashicons dashicons-plus-alt2"></span> <?php esc_html_e('Add Navigation Link', 'cic-theme'); ?></button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Login Button & Redirection -->
            <div class="cic-admin-card">
                <div class="cic-card-header">
                    <h3><span class="dashicons dashicons-lock"></span> <?php esc_html_e('Header: Login Button & Redirection', 'cic-theme'); ?></h3>
                    <p><?php esc_html_e('Configure whether the login button is shown, its display text, and the destination page.', 'cic-theme'); ?></p>
                </div>
                <div class="cic-card-body">
                    <div class="cic-field-group">
                        <label>
                            <input type="checkbox" name="cic_header_settings[show_login]" value="1" <?php checked($header['show_login'], 1); ?>>
                            <strong><?php esc_html_e('Display Login Button in Top Header', 'cic-theme'); ?></strong>
                        </label>
                    </div>

                    <div class="cic-fields-grid-2" style="margin-top: 10px;">
                        <div class="cic-field-group">
                            <label for="login_text"><?php esc_html_e('Login Button Text', 'cic-theme'); ?></label>
                            <input type="text" id="login_text" name="cic_header_settings[login_text]" value="<?php echo esc_attr($header['login_text']); ?>" class="regular-text" placeholder="Login">
                        </div>

                        <div class="cic-field-group">
                            <label for="login_url"><?php esc_html_e('Login Button Redirect URL', 'cic-theme'); ?></label>
                            <input type="text" id="login_url" name="cic_header_settings[login_url]" value="<?php echo esc_attr($header['login_url']); ?>" class="regular-text" placeholder="<?php echo esc_attr(wp_login_url()); ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sub Navigation Bar (Primary Menu: HEAD, FACULTY, STAFF, ACADEMICS...) -->
            <div class="cic-admin-card">
                <div class="cic-card-header">
                    <h3><span class="dashicons dashicons-category"></span> <?php esc_html_e('Header: Sub Navigation Bar (HEAD, FACULTY, ACADEMICS...)', 'cic-theme'); ?></h3>
                    <p><?php esc_html_e('Add, edit, delete, and reorder the department navigation links displayed in the secondary white nav bar.', 'cic-theme'); ?></p>
                </div>
                <div class="cic-card-body">
                    <div class="cic-repeater" id="sub-nav-repeater">
                        <table class="widefat cic-repeater-table">
                            <thead>
                                <tr>
                                    <th style="width: 30px;"></th>
                                    <th><?php esc_html_e('Item Label (e.g. HEAD, FACULTY)', 'cic-theme'); ?></th>
                                    <th><?php esc_html_e('Destination URL', 'cic-theme'); ?></th>
                                    <th style="width: 50px;"></th>
                                </tr>
                            </thead>
                            <tbody class="cic-repeater-items" data-name="cic_header_settings[sub_nav_links]">
                                <?php 
                                if (!empty($header['sub_nav_links'])) :
                                    foreach ($header['sub_nav_links'] as $index => $item) :
                                        $t = isset($item['title']) ? $item['title'] : '';
                                        $u = isset($item['url']) ? $item['url'] : '';
                                ?>
                                    <tr class="cic-repeater-row">
                                        <td class="cic-row-handle"><span class="dashicons dashicons-menu"></span></td>
                                        <td>
                                            <input type="text" name="cic_header_settings[sub_nav_links][<?php echo $index; ?>][title]" value="<?php echo esc_attr($t); ?>" class="regular-text" placeholder="HEAD" required>
                                        </td>
                                        <td>
                                            <input type="text" name="cic_header_settings[sub_nav_links][<?php echo $index; ?>][url]" value="<?php echo esc_attr($u); ?>" class="regular-text" placeholder="https://... or #" required>
                                        </td>
                                        <td>
                                            <button type="button" class="button-link cic-row-delete-btn text-danger" title="Delete Item"><span class="dashicons dashicons-trash"></span></button>
                                        </td>
                                    </tr>
                                <?php 
                                    endforeach;
                                endif; 
                                ?>
                            </tbody>
                        </table>
                        <div style="margin-top: 10px;">
                            <button type="button" class="button button-secondary cic-repeater-add-sub-link-btn"><span class="dashicons dashicons-plus-alt2"></span> <?php esc_html_e('Add Sub-Nav Link', 'cic-theme'); ?></button>
                        </div>
                    </div>
                </div>
            </div>

            <?php submit_button(__('Save Header Settings', 'cic-theme'), 'primary'); ?>
        </form>

        <hr style="margin: 40px 0; border: 0; border-top: 2px dashed #ccd0d4;">

        <!-- FOOTER SETTINGS -->
        <form method="post" action="options.php" class="cic-admin-form">
            <?php settings_fields('cic_footer_settings_group'); ?>

            <div class="cic-admin-card">
                <div class="cic-card-header">
                    <h3><span class="dashicons dashicons-editor-kitchensink"></span> <?php esc_html_e('Footer: Branding & Address', 'cic-theme'); ?></h3>
                    <p><?php esc_html_e('Configure the department information in the footer.', 'cic-theme'); ?></p>
                </div>
                <div class="cic-card-body">
                    <div class="cic-field-group">
                        <label for="footer_brand_title"><?php esc_html_e('Footer Brand Title', 'cic-theme'); ?></label>
                        <input type="text" id="footer_brand_title" name="cic_footer_settings[brand_title]" value="<?php echo esc_attr($footer['brand_title']); ?>" class="large-text" placeholder="G.S. Sanyal School of Technology">
                    </div>

                    <div class="cic-field-group">
                        <label for="footer_brand_address"><?php esc_html_e('Footer Address', 'cic-theme'); ?></label>
                        <textarea id="footer_brand_address" name="cic_footer_settings[brand_address]" rows="2" class="large-text"><?php echo esc_textarea($footer['brand_address']); ?></textarea>
                    </div>

                    <div class="cic-field-group">
                        <label><?php esc_html_e('Footer Crest / Logo Image', 'cic-theme'); ?></label>
                        <div class="cic-media-picker-group">
                            <div class="cic-media-preview-box cic-logo-preview">
                                <img src="<?php echo esc_url($footer['brand_logo_url']); ?>" alt="Footer Logo" class="cic-img-preview" style="<?php echo empty($footer['brand_logo_url']) ? 'display:none;' : ''; ?>">
                                <div class="cic-placeholder" style="<?php echo !empty($footer['brand_logo_url']) ? 'display:none;' : ''; ?>"><span class="dashicons dashicons-format-image"></span></div>
                            </div>
                            <div>
                                <input type="text" name="cic_footer_settings[brand_logo_url]" value="<?php echo esc_attr($footer['brand_logo_url']); ?>" class="regular-text cic-media-url-input" placeholder="Logo URL">
                                <button type="button" class="button cic-media-upload-btn"><span class="dashicons dashicons-upload"></span> <?php esc_html_e('Choose Logo', 'cic-theme'); ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Links Columns -->
            <div class="cic-admin-grid-2">
                <!-- Quick Links -->
                <div class="cic-admin-card">
                    <div class="cic-card-header">
                        <h3><span class="dashicons dashicons-admin-links"></span> <?php esc_html_e('Footer Quick Links', 'cic-theme'); ?></h3>
                    </div>
                    <div class="cic-card-body">
                        <div class="cic-repeater" id="footer-quick-links-repeater">
                            <table class="widefat cic-repeater-table">
                                <thead>
                                    <tr>
                                        <th><?php esc_html_e('Link Title', 'cic-theme'); ?></th>
                                        <th><?php esc_html_e('Destination URL', 'cic-theme'); ?></th>
                                        <th style="width: 40px;"></th>
                                    </tr>
                                </thead>
                                <tbody class="cic-repeater-items" data-name="cic_footer_settings[quick_links]">
                                    <?php 
                                    if (!empty($footer['quick_links'])) :
                                        foreach ($footer['quick_links'] as $index => $item) :
                                            $t = isset($item['title']) ? $item['title'] : '';
                                            $u = isset($item['url']) ? $item['url'] : '';
                                    ?>
                                        <tr class="cic-repeater-row">
                                            <td><input type="text" name="cic_footer_settings[quick_links][<?php echo $index; ?>][title]" value="<?php echo esc_attr($t); ?>" class="regular-text" placeholder="Title"></td>
                                            <td><input type="text" name="cic_footer_settings[quick_links][<?php echo $index; ?>][url]" value="<?php echo esc_attr($u); ?>" class="regular-text" placeholder="URL"></td>
                                            <td><button type="button" class="button-link cic-row-delete-btn text-danger"><span class="dashicons dashicons-trash"></span></button></td>
                                        </tr>
                                    <?php 
                                        endforeach;
                                    endif; 
                                    ?>
                                </tbody>
                            </table>
                            <div style="margin-top: 8px;">
                                <button type="button" class="button button-secondary cic-repeater-add-quick-btn"><span class="dashicons dashicons-plus-alt2"></span> <?php esc_html_e('Add Quick Link', 'cic-theme'); ?></button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Academics Links -->
                <div class="cic-admin-card">
                    <div class="cic-card-header">
                        <h3><span class="dashicons dashicons-book"></span> <?php esc_html_e('Footer Academics Links', 'cic-theme'); ?></h3>
                    </div>
                    <div class="cic-card-body">
                        <div class="cic-repeater" id="footer-acad-links-repeater">
                            <table class="widefat cic-repeater-table">
                                <thead>
                                    <tr>
                                        <th><?php esc_html_e('Link Title', 'cic-theme'); ?></th>
                                        <th><?php esc_html_e('Destination URL', 'cic-theme'); ?></th>
                                        <th style="width: 40px;"></th>
                                    </tr>
                                </thead>
                                <tbody class="cic-repeater-items" data-name="cic_footer_settings[academics_links]">
                                    <?php 
                                    if (!empty($footer['academics_links'])) :
                                        foreach ($footer['academics_links'] as $index => $item) :
                                            $t = isset($item['title']) ? $item['title'] : '';
                                            $u = isset($item['url']) ? $item['url'] : '';
                                    ?>
                                        <tr class="cic-repeater-row">
                                            <td><input type="text" name="cic_footer_settings[academics_links][<?php echo $index; ?>][title]" value="<?php echo esc_attr($t); ?>" class="regular-text" placeholder="Title"></td>
                                            <td><input type="text" name="cic_footer_settings[academics_links][<?php echo $index; ?>][url]" value="<?php echo esc_attr($u); ?>" class="regular-text" placeholder="URL"></td>
                                            <td><button type="button" class="button-link cic-row-delete-btn text-danger"><span class="dashicons dashicons-trash"></span></button></td>
                                        </tr>
                                    <?php 
                                        endforeach;
                                    endif; 
                                    ?>
                                </tbody>
                            </table>
                            <div style="margin-top: 8px;">
                                <button type="button" class="button button-secondary cic-repeater-add-acad-btn"><span class="dashicons dashicons-plus-alt2"></span> <?php esc_html_e('Add Academics Link', 'cic-theme'); ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information & Bottom Row -->
            <div class="cic-admin-card">
                <div class="cic-card-header">
                    <h3><span class="dashicons dashicons-email-alt"></span> <?php esc_html_e('Footer Contact Us & Legal', 'cic-theme'); ?></h3>
                </div>
                <div class="cic-card-body">
                    <div class="cic-fields-grid-2">
                        <div class="cic-field-group">
                            <label for="contact_email"><?php esc_html_e('Contact Email Address', 'cic-theme'); ?></label>
                            <input type="email" id="contact_email" name="cic_footer_settings[contact_email]" value="<?php echo esc_attr($footer['contact_email']); ?>" class="regular-text" placeholder="head@gssst.iitkgp.ac.in">
                        </div>

                        <div class="cic-field-group">
                            <label for="contact_phone"><?php esc_html_e('Contact Phone Number', 'cic-theme'); ?></label>
                            <input type="text" id="contact_phone" name="cic_footer_settings[contact_phone]" value="<?php echo esc_attr($footer['contact_phone']); ?>" class="regular-text" placeholder="+91-3222-282227">
                        </div>
                    </div>

                    <div class="cic-field-group" style="margin-top: 15px;">
                        <label for="copyright_text"><?php esc_html_e('Copyright Notice', 'cic-theme'); ?></label>
                        <input type="text" id="copyright_text" name="cic_footer_settings[copyright_text]" value="<?php echo esc_attr($footer['copyright_text']); ?>" class="large-text" placeholder="© 2026 IIT Kharagpur. All Rights Reserved.">
                    </div>

                    <!-- Legal links repeater -->
                    <div class="cic-repeater" id="footer-legal-links-repeater" style="margin-top: 15px;">
                        <label><strong><?php esc_html_e('Footer Legal Links (e.g. Privacy Policy, Terms of Use)', 'cic-theme'); ?></strong></label>
                        <table class="widefat cic-repeater-table" style="margin-top: 8px;">
                            <thead>
                                <tr>
                                    <th><?php esc_html_e('Link Title', 'cic-theme'); ?></th>
                                    <th><?php esc_html_e('Destination URL', 'cic-theme'); ?></th>
                                    <th style="width: 40px;"></th>
                                </tr>
                            </thead>
                            <tbody class="cic-repeater-items" data-name="cic_footer_settings[legal_links]">
                                <?php 
                                if (!empty($footer['legal_links'])) :
                                    foreach ($footer['legal_links'] as $index => $item) :
                                        $t = isset($item['title']) ? $item['title'] : '';
                                        $u = isset($item['url']) ? $item['url'] : '';
                                ?>
                                    <tr class="cic-repeater-row">
                                        <td><input type="text" name="cic_footer_settings[legal_links][<?php echo $index; ?>][title]" value="<?php echo esc_attr($t); ?>" class="regular-text" placeholder="Privacy Policy"></td>
                                        <td><input type="text" name="cic_footer_settings[legal_links][<?php echo $index; ?>][url]" value="<?php echo esc_attr($u); ?>" class="regular-text" placeholder="#"></td>
                                        <td><button type="button" class="button-link cic-row-delete-btn text-danger"><span class="dashicons dashicons-trash"></span></button></td>
                                    </tr>
                                <?php 
                                    endforeach;
                                endif; 
                                ?>
                            </tbody>
                        </table>
                        <div style="margin-top: 8px;">
                            <button type="button" class="button button-secondary cic-repeater-add-legal-btn"><span class="dashicons dashicons-plus-alt2"></span> <?php esc_html_e('Add Legal Link', 'cic-theme'); ?></button>
                        </div>
                    </div>
                </div>
            </div>

            <?php submit_button(__('Save Footer Settings', 'cic-theme'), 'primary'); ?>
        </form>
    </div>
    <?php
}

