<?php
/** Template Name: Jobs */
get_header();
$jobs = get_posts(array('post_type' => 'cic_job', 'post_status' => 'publish', 'numberposts' => -1, 'orderby' => 'date', 'order' => 'DESC'));
?>
<main id="main-content" class="department-content-page jobs-page">
    <header class="department-page-hero"><div class="container">
        <a href="<?php echo esc_url(home_url('/#academics')); ?>">Academics</a>
        <?php while (have_posts()) : the_post(); ?><h1><?php the_title(); ?></h1><div class="entry-content"><?php the_content(); ?></div><?php endwhile; ?>
    </div></header>
    <div class="container department-directory-body">
        <section aria-labelledby="job-list-title">
            <h2 id="job-list-title">Vacancies &amp; opportunities</h2>
            <div class="job-filters" hidden>
                <label>Search vacancies<input type="search" id="job-search" placeholder="Role, reference or keyword"></label>
                <label>Category<select id="job-category"><option value="all">All categories</option><?php foreach (cic_job_fields()['category'][2] as $value => $label) : ?><option value="<?php echo esc_attr($value); ?>"><?php echo esc_html($label); ?></option><?php endforeach; ?></select></label>
                <label>Status<select id="job-status"><option value="all">All listings</option><?php foreach (cic_job_fields()['status'][2] as $value => $label) : ?><option value="<?php echo esc_attr($value); ?>"><?php echo esc_html($label); ?></option><?php endforeach; ?></select></label>
            </div>
            <p class="job-result-count" aria-live="polite"></p>
            <div class="job-list">
                <?php foreach ($jobs as $job) : $data = cic_job_data($job->ID); ?>
                <article class="job-card" data-category="<?php echo esc_attr($data['category']); ?>" data-status="<?php echo esc_attr($data['status']); ?>">
                    <div class="job-card-main">
                        <div class="job-labels"><span><?php echo esc_html(cic_job_label('category', $data['category'])); ?></span><span class="job-status job-status-<?php echo esc_attr($data['status']); ?>"><?php echo esc_html(cic_job_label('status', $data['status'])); ?></span></div>
                        <h3><a href="<?php echo esc_url(get_permalink($job)); ?>"><?php echo esc_html($job->post_title); ?></a></h3>
                        <?php if (has_excerpt($job)) : ?><p><?php echo esc_html($job->post_excerpt); ?></p><?php endif; ?>
                        <ul class="job-card-facts">
                            <?php foreach (array('reference', 'employment', 'location') as $key) : if (!empty($data[$key])) : ?><li><?php echo esc_html(cic_job_fields()[$key][0] . ': ' . $data[$key]); ?></li><?php endif; endforeach; ?>
                        </ul>
                    </div>
                    <div class="job-card-action">
                        <?php if (!empty($data['deadline'])) : ?><span>Deadline <strong><?php echo esc_html(cic_job_deadline($data['deadline'])); ?></strong></span><?php elseif ($data['status'] === 'example') : ?><span>Illustrative vacancy</span><?php endif; ?>
                        <a class="job-details-link" href="<?php echo esc_url(get_permalink($job)); ?>">View details &rarr;</a>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
            <p class="department-empty job-no-results" <?php if ($jobs) echo 'hidden'; ?>>No vacancies match this selection.</p>
        </section>
    </div>
</main>
<?php get_footer(); ?>
