<?php
/**
 * Template Name: Faculty Directory & Profile
 *
 * @package cic-theme
 */

get_header();

$member_query  = isset($_GET['member']) ? sanitize_text_field(wp_unslash($_GET['member'])) : '';
$single_member = !empty($member_query) ? cic_get_faculty_member($member_query) : null;
$faculty_list  = cic_get_faculty_members();

$poster_bg       = get_option('cic_faculty_banner_img', get_template_directory_uri() . '/assets/images/hero-bg.jpg');
$poster_title    = get_option('cic_faculty_banner_title', 'Faculty Directory');
$poster_subtitle = get_option('cic_faculty_banner_subtitle', 'Meet the distinguished professors, scholars, and researchers leading technological excellence at the G.S. Sanyal School of Telecommunication.');
$dir_main_title  = get_option('cic_faculty_dir_title', 'Faculty Directory');
$dir_main_desc   = get_option('cic_faculty_dir_desc', 'Meet the academics driving excellence at our school.');
?>

<main id="main-content" class="faculty-page-wrapper<?php echo $single_member ? ' faculty-single-page' : ''; ?>">

<?php if ($single_member) : ?>
    <!-- ====================================================================
         INDIVIDUAL FACULTY PROFILE VIEW
         ==================================================================== -->
    <section class="faculty-personal-cover"<?php if (!empty($single_member['cover_url'])) : ?> style="background-image:linear-gradient(90deg,rgba(12,32,48,.88),rgba(12,32,48,.40)),url('<?php echo esc_url($single_member['cover_url']); ?>')"<?php endif; ?>>
        <div class="container faculty-cover-inner">
            <nav class="faculty-cover-breadcrumbs" aria-label="Breadcrumbs">
                <a href="<?php echo esc_url(home_url('/')); ?>">Home</a><span>/</span>
                <a href="<?php echo esc_url(home_url('/faculty/')); ?>">Faculty</a><span>/</span>
                <span aria-current="page"><?php echo esc_html($single_member['name']); ?></span>
            </nav>
            <div class="faculty-cover-heading">
                <span class="faculty-cover-eyebrow">G.S. Sanyal School of Telecommunication</span>
                <h1><?php echo esc_html($single_member['name']); ?></h1>
                <p><?php echo esc_html($single_member['designation']); ?></p>
            </div>
            <a href="<?php echo esc_url(home_url('/faculty/')); ?>" class="faculty-cover-back"><i class="fas fa-arrow-left" aria-hidden="true"></i> All faculty</a>
        </div>
    </section>

    <div class="container faculty-profile-layout">
        <!-- Sidebar Column -->
        <aside class="faculty-profile-sidebar">
            <div class="faculty-sidebar-card">
                <div class="faculty-sidebar-photo-wrap">
                    <?php echo cic_render_faculty_photo($single_member, 'faculty-sidebar-photo'); ?>
                </div>
                <span class="faculty-sidebar-kicker">Faculty profile</span>
                <h2 class="faculty-sidebar-name"><?php echo esc_html($single_member['name']); ?></h2>
                <span class="faculty-sidebar-badge"><?php echo esc_html($single_member['designation']); ?></span>
                <p class="faculty-sidebar-department"><?php echo esc_html($single_member['department']); ?></p>
                
                <hr class="faculty-sidebar-divider">

                <div class="faculty-sidebar-contact">
                    <div class="contact-row">
                        <i class="far fa-envelope contact-icon"></i>
                        <div class="contact-details">
                            <span class="contact-label">Email</span>
                            <a href="mailto:<?php echo esc_attr($single_member['email']); ?>" class="contact-value"><?php echo esc_html($single_member['email']); ?></a>
                        </div>
                    </div>

                    <?php if (!empty($single_member['phone'])) : ?>
                    <div class="contact-row">
                        <i class="fas fa-phone-alt contact-icon"></i>
                        <div class="contact-details">
                            <span class="contact-label">Phone</span>
                            <a href="tel:<?php echo esc_attr($single_member['phone']); ?>" class="contact-value"><?php echo esc_html($single_member['phone']); ?></a>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($single_member['office'])) : ?>
                    <div class="contact-row">
                        <i class="fas fa-building contact-icon"></i>
                        <div class="contact-details">
                            <span class="contact-label">Office / Cabin</span>
                            <span class="contact-value"><?php echo esc_html($single_member['office']); ?></span>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="contact-row">
                        <i class="fas fa-university contact-icon"></i>
                        <div class="contact-details">
                            <span class="contact-label">Affiliation</span>
                            <span class="contact-value"><?php echo esc_html($single_member['department']); ?>, IIT Kharagpur</span>
                        </div>
                    </div>
                </div>
                <?php if (!empty($single_member['social_links']) || !empty($single_member['website_url'])) : ?>
                <div class="faculty-social-block">
                    <h3>Connect online</h3>
                    <div class="faculty-social-links">
                        <?php foreach ($single_member['social_links'] as $link) :
                            $social_label = isset($link['label']) ? trim($link['label']) : '';
                            $social_url = isset($link['url']) ? $link['url'] : '';
                            if (!$social_label || !$social_url) continue;
                        ?>
                            <a href="<?php echo esc_url($social_url); ?>" target="_blank" rel="noopener noreferrer"><i class="<?php echo esc_attr(cic_faculty_social_icon($social_label)); ?>" aria-hidden="true"></i><span><?php echo esc_html($social_label); ?></span><i class="fas fa-arrow-up-right-from-square faculty-social-external" aria-hidden="true"></i></a>
                        <?php endforeach; ?>
                        <?php if (!empty($single_member['website_url'])) : ?>
                            <a href="<?php echo esc_url($single_member['website_url']); ?>" target="_blank" rel="noopener noreferrer"><i class="fas fa-globe" aria-hidden="true"></i><span>Personal website</span><i class="fas fa-arrow-up-right-from-square faculty-social-external" aria-hidden="true"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
                <?php if (!empty($single_member['email'])) : ?>
                    <a class="faculty-sidebar-email-button" href="mailto:<?php echo esc_attr($single_member['email']); ?>"><i class="far fa-envelope" aria-hidden="true"></i> Send an email</a>
                <?php endif; ?>
            </div>
        </aside>

        <!-- Main Content Column -->
        <section class="faculty-profile-main">
            <div class="faculty-main-intro">
                <span>Academic profile</span>
                <h2>Research &amp; scholarship</h2>
                <p>Explore <?php echo esc_html($single_member['name']); ?>’s areas of work, current projects, and academic background.</p>
            </div>
            <!-- Biography Card -->
            <?php if (!empty($single_member['bio'])) : ?>
            <div class="faculty-content-card">
                <h3 class="faculty-section-title"><i class="fas fa-user-circle"></i> <?php esc_html_e('About', 'cic-theme'); ?></h3>
                <div class="faculty-bio-text">
                    <p><?php echo nl2br(esc_html($single_member['bio'])); ?></p>
                </div>
            </div>
            <?php endif; ?>

            <!-- Research Interests Card -->
            <?php if (!empty($single_member['research_areas'])) : ?>
            <div class="faculty-content-card">
                <h3 class="faculty-section-title"><i class="fas fa-microscope"></i> <?php esc_html_e('Research interests', 'cic-theme'); ?></h3>
                <div class="faculty-research-pills">
                    <?php foreach ($single_member['research_areas'] as $area) : ?>
                        <span class="research-pill"><i class="fas fa-check"></i> <?php echo esc_html($area); ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($single_member['research_statement'])) : ?>
            <div class="faculty-content-card">
                <h3 class="faculty-section-title"><i class="fas fa-file-alt"></i> Research Statement</h3>
                <div class="faculty-bio-text"><?php echo wpautop(esc_html($single_member['research_statement'])); ?></div>
            </div>
            <?php endif; ?>

            <?php if (!empty($single_member['selected_publications'])) : ?>
            <div class="faculty-content-card">
                <h3 class="faculty-section-title"><i class="fas fa-book"></i> Selected Publications</h3>
                <ul class="cic-erp-list">
                    <?php foreach ($single_member['selected_publications'] as $publication) : ?>
                        <li><?php echo esc_html($publication); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <?php if (!empty($single_member['current_projects'])) : ?>
            <div class="faculty-content-card">
                <h3 class="faculty-section-title"><i class="fas fa-tasks"></i> Current Projects</h3>
                <ul class="cic-erp-list">
                    <?php foreach ($single_member['current_projects'] as $project) : ?>
                        <li><?php echo esc_html($project); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <?php if (!empty($single_member['iitkgp_profile_url'])) : ?>
            <p class="faculty-erp-source"><a href="<?php echo esc_url($single_member['iitkgp_profile_url']); ?>" target="_blank" rel="noopener noreferrer">View official IIT Kharagpur profile <i class="fas fa-external-link-alt" aria-hidden="true"></i></a></p>
            <?php endif; ?>

            <!-- Education Card -->
            <?php if (!empty($single_member['education'])) : ?>
            <div class="faculty-content-card">
                <h3 class="faculty-section-title"><i class="fas fa-graduation-cap"></i> <?php esc_html_e('Education', 'cic-theme'); ?></h3>
                <div class="faculty-education-list">
                    <?php 
                    $degrees = explode(';', $single_member['education']);
                    foreach ($degrees as $deg) :
                        $deg = trim($deg);
                        if (!empty($deg)) :
                    ?>
                        <div class="education-item">
                            <i class="fas fa-award education-bullet"></i>
                            <span class="education-degree"><?php echo esc_html($deg); ?></span>
                        </div>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                </div>
            </div>
            <?php endif; ?>

        </section>
    </div>

<?php else : ?>
    <!-- ====================================================================
         FACULTY DIRECTORY LISTING VIEW
         ==================================================================== -->
    <section class="faculty-poster-banner" style="background-image: linear-gradient(135deg, rgba(15, 23, 42, 0.90) 0%, rgba(30, 58, 138, 0.88) 100%), url('<?php echo esc_url($poster_bg); ?>');">
        <div class="container faculty-poster-container">
            <nav class="faculty-breadcrumbs" aria-label="Breadcrumbs">
                <a href="<?php echo esc_url(home_url('/')); ?>"><i class="fas fa-home"></i> Home</a>
                <span class="sep">/</span>
                <span>People</span>
                <span class="sep">/</span>
                <span class="current">Faculty Directory</span>
            </nav>
            <div class="faculty-poster-header-content">
                <h1 class="faculty-poster-title"><?php echo esc_html($poster_title); ?></h1>
                <p class="faculty-poster-subtitle">
                    <?php echo esc_html($poster_subtitle); ?>
                </p>
            </div>
        </div>
    </section>

    <div class="container faculty-directory-container">
        
        <!-- Directory Header Controls (Title + Search Bar as in screenshot) -->
        <div class="faculty-controls-row">
            <div class="faculty-heading-col">
                <h2 class="directory-main-title"><?php echo esc_html($dir_main_title); ?></h2>
                <p class="directory-main-desc"><?php echo esc_html($dir_main_desc); ?></p>
            </div>
            
            <div class="faculty-search-col">
                <div class="faculty-search-box">
                    <span class="search-icon"><i class="fas fa-search"></i></span>
                    <input type="text" id="facultySearchInput" class="faculty-search-input" placeholder="<?php esc_attr_e('Search by name...', 'cic-theme'); ?>" autocomplete="off">
                    <button type="button" id="facultySearchClear" class="search-clear-btn" style="display:none;" title="<?php esc_attr_e('Clear search', 'cic-theme'); ?>"><i class="fas fa-times"></i></button>
                </div>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="faculty-filter-tabs">
            <button type="button" class="filter-tab active" data-filter="all">
                <?php esc_html_e('All Faculty', 'cic-theme'); ?> <span class="tab-count">(<?php echo count($faculty_list); ?>)</span>
            </button>
            <button type="button" class="filter-tab" data-filter="head">
                <?php esc_html_e('Head of School', 'cic-theme'); ?>
            </button>
            <button type="button" class="filter-tab" data-filter="professor">
                <?php esc_html_e('Professors', 'cic-theme'); ?>
            </button>
            <button type="button" class="filter-tab" data-filter="associate">
                <?php esc_html_e('Associate Professors', 'cic-theme'); ?>
            </button>
            <button type="button" class="filter-tab" data-filter="assistant">
                <?php esc_html_e('Assistant Professors', 'cic-theme'); ?>
            </button>
        </div>

        <!-- Cards Grid (4 columns as in screenshot) -->
        <div class="faculty-cards-grid" id="facultyCardsGrid">
            <?php if (!empty($faculty_list)) : ?>
                <?php foreach ($faculty_list as $member) : 
                    $role_tag = 'other';
                    $desig_lower = strtolower($member['designation']);
                    if (strpos($desig_lower, 'head') !== false) {
                        $role_tag = 'head';
                    } elseif (strpos($desig_lower, 'associate') !== false) {
                        $role_tag = 'associate';
                    } elseif (strpos($desig_lower, 'assistant') !== false) {
                        $role_tag = 'assistant';
                    } elseif (strpos($desig_lower, 'professor') !== false) {
                        $role_tag = 'professor';
                    }
                    $search_keywords = strtolower($member['name'] . ' ' . $member['designation'] . ' ' . implode(' ', $member['research_areas']) . ' ' . $member['email']);
                ?>
                    <article class="faculty-card" data-role="<?php echo esc_attr($role_tag); ?>" data-search="<?php echo esc_attr($search_keywords); ?>" onclick="location.href='<?php echo esc_url($member['profile_url']); ?>';">
                        
                        <!-- Top Image -->
                        <div class="faculty-card-photo-wrapper">
                            <?php echo cic_render_faculty_photo($member, 'faculty-card-photo'); ?>
                        </div>

                        <!-- Card Body -->
                        <div class="faculty-card-body">
                            <h3 class="faculty-card-name">
                                <a href="<?php echo esc_url($member['profile_url']); ?>"><?php echo esc_html($member['name']); ?></a>
                            </h3>
                            
                            <div class="faculty-card-designation"><?php echo esc_html($member['designation']); ?></div>

                            <div class="faculty-card-meta">
                                <?php if (!empty($member['email'])) : ?>
                                <div class="meta-row meta-email">
                                    <i class="far fa-envelope meta-icon"></i>
                                    <a href="mailto:<?php echo esc_attr($member['email']); ?>" onclick="event.stopPropagation();"><?php echo esc_html($member['email']); ?></a>
                                </div>
                                <?php endif; ?>

                                <?php if (!empty($member['phone'])) : ?>
                                <div class="meta-row meta-phone">
                                    <i class="fas fa-phone-alt meta-icon"></i>
                                    <a href="tel:<?php echo esc_attr($member['phone']); ?>" onclick="event.stopPropagation();"><?php echo esc_html($member['phone']); ?></a>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                    </article>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="faculty-empty-state">
                    <i class="fas fa-user-graduate empty-icon"></i>
                    <p><?php esc_html_e('No faculty members found. Assign the "faculty" role to users in the WordPress admin panel to display them here.', 'cic-theme'); ?></p>
                </div>
            <?php endif; ?>
        </div>

        <!-- No search results notification -->
        <div id="facultyNoResults" class="faculty-no-results" style="display:none;">
            <i class="fas fa-search-minus no-results-icon"></i>
            <h3><?php esc_html_e('No matching faculty found', 'cic-theme'); ?></h3>
            <p><?php esc_html_e('Try searching with a different name or clear your search term.', 'cic-theme'); ?></p>
            <button type="button" id="facultyResetFiltersBtn" class="btn btn-hero-secondary" style="padding: 8px 18px; font-size: 13px;">
                <?php esc_html_e('Reset Search & Filters', 'cic-theme'); ?>
            </button>
        </div>

    </div>

<?php endif; ?>

</main>

<?php get_footer(); ?>
