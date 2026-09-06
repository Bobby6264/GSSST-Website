<?php get_header(); ?>

<main id="main-content">

    <?php if(get_theme_mod('hero_show', true)) : ?>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-slider">
            <?php 
                $img1 = get_theme_mod('hero_bg_image_1', get_template_directory_uri() . '/assets/images/hero-bg.jpg');
                $img2 = get_theme_mod('hero_bg_image_2');
                $img3 = get_theme_mod('hero_bg_image_3');
            ?>
            <div class="hero-slide active" style="background-image: url('<?php echo esc_url($img1); ?>');"></div>
            <?php if($img2): ?><div class="hero-slide" style="background-image: url('<?php echo esc_url($img2); ?>');"></div><?php endif; ?>
            <?php if($img3): ?><div class="hero-slide" style="background-image: url('<?php echo esc_url($img3); ?>');"></div><?php endif; ?>
        </div>
        <div class="container hero-content">
            <h1><?php echo esc_html(get_theme_mod('hero_title', 'Advancing the Frontiers of Technology')); ?></h1>
            <p><?php echo esc_html(get_theme_mod('hero_subtitle', 'Interdisciplinary research, world-class academic programs, and industry collaborations shape the engineering leaders of tomorrow.')); ?></p>
            <div class="hero-buttons">
                <a href="<?php echo esc_url(get_theme_mod('hero_btn1_url', '#')); ?>" class="btn btn-primary"><?php echo esc_html(get_theme_mod('hero_btn1_text', 'Explore Programmes')); ?></a>
                <a href="<?php echo esc_url(get_theme_mod('hero_btn2_url', '#')); ?>" class="btn btn-secondary"><?php echo esc_html(get_theme_mod('hero_btn2_text', 'Research Highlights')); ?></a>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Notices Ticker -->
    <section class="notices-section">
        <div class="container notices-wrapper">
            <span class="latest-badge"><i class="fas fa-circle"></i> LATEST</span>
            <marquee behavior="scroll" direction="left">
                <?php
                $notices = new WP_Query(array('post_type' => 'notice', 'posts_per_page' => 5));
                if ($notices->have_posts()) :
                    while ($notices->have_posts()) : $notices->the_post();
                ?>
                    <span class="notice-item">
                        <span class="notice-date"><?php echo get_the_date('M d, Y'); ?></span>
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </span>
                <?php
                    endwhile;
                    wp_reset_postdata();
                else:
                ?>
                    <span class="notice-item">No new notices at the moment.</span>
                <?php endif; ?>
            </marquee>
        </div>
    </section>

    <?php if(get_theme_mod('about_show', true)) : ?>
    <!-- About & News Section -->
    <section class="about-section section-padding">
        <div class="container about-grid">
            <div class="about-content">
                <span class="section-subtitle">WELCOME</span>
                <h2><?php echo esc_html(get_theme_mod('about_title', 'About the School')); ?></h2>
                <div class="text-content">
                    <?php echo wpautop(wp_kses_post(get_theme_mod('about_text', 'The G.S. Sanyal School of Technology was established to lead global technological advancements...'))); ?>
                </div>
                <table class="about-table">
                    <tr>
                        <th>Location</th>
                        <td>IIT Campus, Kharagpur</td>
                    </tr>
                    <tr>
                        <th>Affiliation</th>
                        <td>IIT Kharagpur</td>
                    </tr>
                </table>
                <a href="#" class="read-more">Read more about the School <i class="fas fa-arrow-right"></i></a>
            </div>
            
            <div class="news-content">
                <div class="news-tabs">
                    <button class="tab-btn active"><i class="fas fa-newspaper"></i> News</button>
                    <button class="tab-btn"><i class="fas fa-calendar-alt"></i> Events</button>
                </div>
                <div class="news-list">
                    <?php
                    $news = new WP_Query(array('post_type' => 'news', 'posts_per_page' => 3));
                    if ($news->have_posts()) :
                        while ($news->have_posts()) : $news->the_post();
                    ?>
                        <div class="news-item">
                            <div class="news-icon"><i class="fas fa-bookmark"></i></div>
                            <div class="news-text"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
                        </div>
                    <?php
                        endwhile;
                        wp_reset_postdata();
                    else:
                    ?>
                        <div class="news-item">No latest news.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php if(get_theme_mod('message_show', true)) : ?>
    <!-- Message Section -->
    <section class="message-section section-padding bg-light">
        <div class="container message-grid">
            <div class="message-content">
                <span class="section-subtitle">MESSAGE FROM THE HEAD</span>
                <h2><?php echo esc_html(get_theme_mod('message_title', 'Welcome to the School of Technology')); ?></h2>
                <blockquote>
                    <?php echo wpautop(esc_html(get_theme_mod('message_text', '"At GSSST, our mandate goes beyond traditional research boundaries..."'))); ?>
                </blockquote>
                <div class="author-info">
                    <h4><?php echo esc_html(get_theme_mod('message_author', 'Prof. Vikram Desai')); ?></h4>
                    <p><?php echo esc_html(get_theme_mod('message_designation', 'Professor & Head, GSSST')); ?></p>
                </div>
            </div>
            <div class="message-image">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/head.jpg" alt="Head of School">
                <div class="image-caption">
                    <h4><?php echo esc_html(get_theme_mod('message_author', 'Prof. Vikram Desai')); ?></h4>
                    <p><?php echo esc_html(get_theme_mod('message_designation', 'Professor & Head, GSSST')); ?></p>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Departments Section -->
    <section class="departments-section section-padding">
        <div class="container text-center">
            <span class="section-subtitle text-center">DEPARTMENTS</span>
            <h2><?php echo esc_html(get_theme_mod('dep_title', 'Explore Our Department')); ?></h2>
            
            <div class="dept-grid">
                <div class="dept-card">
                    <div class="icon"><i class="fas fa-flask"></i></div>
                    <h3>Research & Innovation</h3>
                    <p>Engage with cutting-edge research modules that address critical global tech challenges.</p>
                    <a href="#" class="btn btn-outline">Explore <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="dept-card">
                    <div class="icon"><i class="fas fa-users"></i></div>
                    <h3>Faculty & Staff</h3>
                    <p>Meet our globally distinguished faculty members committed to high academic standards.</p>
                    <a href="#" class="btn btn-outline">Directory <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="dept-card">
                    <div class="icon"><i class="fas fa-award"></i></div>
                    <h3>Awards</h3>
                    <p>Celebrating the outstanding academic and institutional achievements of our pupils.</p>
                    <a href="#" class="btn btn-outline">View All <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </section>

    <!-- Academics Section -->
    <section class="academics-section section-padding bg-light">
        <div class="container">
            <span class="section-subtitle">PROGRAMMES</span>
            <h2><?php echo esc_html(get_theme_mod('academics_title', 'Academics & Resources')); ?></h2>
            
            <div class="acad-grid">
                <div class="acad-col">
                    <h3 class="col-title">Postgraduate Taught</h3>
                    <ul class="acad-list">
                        <li><a href="#">M.Tech Programs <i class="fas fa-chevron-right"></i></a></li>
                        <li><a href="#">Joint M.Tech/PhD <i class="fas fa-chevron-right"></i></a></li>
                    </ul>
                </div>
                <div class="acad-col">
                    <h3 class="col-title">Postgraduate Research</h3>
                    <ul class="acad-list">
                        <li><a href="#">M.S. by Research <i class="fas fa-chevron-right"></i></a></li>
                        <li><a href="#">PhD Program <i class="fas fa-chevron-right"></i></a></li>
                    </ul>
                </div>
                <div class="acad-col">
                    <h3 class="col-title">Resources</h3>
                    <ul class="acad-list">
                        <li><a href="#">Academic Curriculum <i class="fas fa-chevron-right"></i></a></li>
                        <li><a href="#">Time Table <i class="fas fa-chevron-right"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

</main>

<?php get_footer(); ?>

