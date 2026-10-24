<?php
get_header();
$projects = cic_academic_projects();
$current = array_filter($projects, function ($project) { return $project['status'] === 'current'; });
$courses = get_posts(array('post_type' => 'cic_course', 'numberposts' => -1));
$courses = array_filter($courses, function ($post) { $data = (array) get_post_meta($post->ID, 'cic_record', true); return ($data['status'] ?? 'current') === 'current'; });
?>
<main id="main-content" class="academic-page">
    <header class="academic-hero"><div class="container">
        <a href="<?php echo esc_url(home_url('/')); ?>">Home</a><span> / Academics</span>
        <?php while (have_posts()) : the_post(); ?><h1><?php the_title(); ?></h1><div class="entry-content"><?php the_content(); ?></div><?php endwhile; ?>
        <nav class="academic-nav" aria-label="Academic sections"><a href="#projects">Projects</a><a href="#courses">Current courses</a><a href="#resources">Resources &amp; links</a></nav>
    </div></header>
    <div class="container academic-body">
        <div class="academic-stats">
            <?php foreach (array('Listed projects' => count($projects), 'Current projects' => count($current), 'Completed projects' => count($projects) - count($current), 'Current courses' => count($courses)) as $label => $count) : ?>
            <div><strong><?php echo esc_html($count); ?></strong><span><?php echo esc_html($label); ?></span></div>
            <?php endforeach; ?>
        </div>
        <section id="projects"><h2>Department projects</h2><p>Published project records and current projects imported from faculty profiles. Counts reflect the records listed here.</p>
            <div class="academic-grid">
            <?php foreach ($projects as $project) : ?>
                <article class="academic-card"><span class="academic-status"><?php echo $project['status'] === 'current' ? 'Current' : 'Completed'; ?></span><h3><?php echo esc_html($project['title']); ?></h3>
                    <?php if (!empty($project['lead'])) : ?><p><?php echo esc_html($project['lead']); ?></p><?php endif; ?>
                    <?php if (!empty($project['sponsor'])) : ?><p><?php echo esc_html($project['sponsor']); ?></p><?php endif; ?>
                    <?php if (!empty($project['description'])) : ?><p><?php echo esc_html($project['description']); ?></p><?php endif; ?>
                    <a href="<?php echo esc_url($project['url']); ?>">Project details &rarr;</a>
                </article>
            <?php endforeach; ?>
            </div><?php if (!$projects) : ?><p>No projects have been published yet.</p><?php endif; ?>
        </section>
        <section id="courses"><h2>Current courses</h2><div class="academic-grid">
            <?php foreach ($courses as $course) : $data = (array) get_post_meta($course->ID, 'cic_record', true); ?>
                <article class="academic-card"><span class="academic-status"><?php echo esc_html($data['code'] ?? 'Course'); ?></span><h3><?php echo esc_html($course->post_title); ?></h3><p><?php echo esc_html($data['lead'] ?? ''); ?></p><p><?php echo esc_html(get_the_excerpt($course)); ?></p><a href="<?php echo esc_url(($data['url'] ?? '') ?: get_permalink($course)); ?>">Course information &rarr;</a></article>
            <?php endforeach; ?>
        </div><?php if (!$courses) : ?><p>No current courses have been published yet.</p><?php endif; ?></section>
        <section id="resources"><h2>Resources &amp; links</h2><div class="academic-grid">
        <?php $settings = cic_get_academics_settings(); $has_resources = false; foreach ($settings['columns'] ?? array() as $column) :
            $items = array_filter($column['items'] ?? array(), function ($link) { return !empty($link['url']) && $link['url'] !== '#'; });
            if (!$items) continue; $has_resources = true;
        ?>
            <div class="academic-card"><h3><?php echo esc_html($column['col_title']); ?></h3><ul>
                <?php foreach ($items as $link) : ?>
                <li><a href="<?php echo esc_url($link['url']); ?>"><?php echo esc_html($link['title']); ?></a></li>
                <?php endforeach; ?>
            </ul></div>
        <?php endforeach; ?>
        </div><?php if (!$has_resources) : ?><p>No resource links have been published yet.</p><?php endif; ?></section>
    </div>
</main>
<?php get_footer(); ?>
