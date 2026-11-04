<?php
get_header(); ?>
<main id="main-content" class="department-content-page jobs-page">
    <?php while (have_posts()) : the_post(); $data = cic_job_data(get_the_ID()); ?>
    <header class="department-page-hero"><div class="container">
        <a href="<?php echo esc_url(home_url('/jobs/')); ?>">All jobs</a><h1><?php the_title(); ?></h1>
        <div class="job-labels"><span><?php echo esc_html(cic_job_label('category', $data['category'])); ?></span><span class="job-status job-status-<?php echo esc_attr($data['status']); ?>"><?php echo esc_html(cic_job_label('status', $data['status'])); ?></span></div>
    </div></header>
    <div class="container job-detail-layout">
        <article class="job-description entry-content">
            <?php if ($data['status'] === 'example') : ?><p class="job-example-notice">Illustrative vacancy — this example is not accepting applications.</p><?php elseif ($data['status'] === 'closed') : ?><p class="job-example-notice">Applications for this vacancy are closed.</p><?php endif; ?>
            <?php the_content(); ?>
        </article>
        <aside class="job-detail-summary" aria-label="Vacancy summary">
            <h2>At a glance</h2><dl>
                <?php foreach (array('reference', 'employment', 'duration', 'location', 'salary', 'deadline') as $key) : if (!empty($data[$key])) : ?>
                <div><dt><?php echo esc_html(cic_job_fields()[$key][0]); ?></dt><dd><?php echo esc_html($key === 'deadline' ? cic_job_deadline($data[$key]) : $data[$key]); ?></dd></div>
                <?php endif; endforeach; ?>
            </dl>
            <?php if ($data['status'] === 'open' && !empty($data['apply_url'])) : ?><a class="job-details-link job-apply" href="<?php echo esc_url($data['apply_url']); ?>">Apply for this role &rarr;</a><?php endif; ?>
            <?php if (!empty($data['contact']) && $data['status'] !== 'example') : ?><p>Recruitment enquiries<br><a href="<?php echo esc_url('mailto:' . $data['contact']); ?>"><?php echo esc_html($data['contact']); ?></a></p><?php endif; ?>
        </aside>
    </div>
    <?php endwhile; ?>
</main>
<?php get_footer(); ?>
