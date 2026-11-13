<?php
/** Template Name: Department Directory */
get_header();
$type = get_post_meta(get_queried_object_id(), 'cic_directory_type', true);
$types = cic_directory_types();
if (!isset($types[$type])) {
    $type = 'cic_alumni';
    foreach ($types as $candidate => $config) {
        if ($config['slug'] === get_post_field('post_name', get_queried_object_id())) $type = $candidate;
    }
}
$records = get_posts(array('post_type' => $type, 'post_status' => 'publish', 'numberposts' => -1, 'orderby' => 'title', 'order' => 'ASC'));
$faculty = $type === 'cic_research_area' ? cic_get_faculty_members() : array();
$selected_area = null;
if ($type === 'cic_research_area' && isset($_GET['area']) && is_scalar($_GET['area'])) {
    foreach ($records as $record) if ($record->ID === absint($_GET['area'])) $selected_area = $record;
}
$groups = $type === 'cic_alumni' ? $types[$type]['fields']['category'][2] : array('all' => $types[$type]['label']);
?>
<main id="main-content" class="department-content-page<?php if ($type === 'cic_staff') echo ' staff-directory'; ?>">
    <header class="department-page-hero"><div class="container">
        <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
        <?php while (have_posts()) : the_post(); ?><h1><?php the_title(); ?></h1><div class="entry-content"><?php the_content(); ?></div><?php endwhile; ?>
        <?php if ($type === 'cic_alumni') : ?><nav class="department-section-nav" aria-label="Alumni categories">
            <?php foreach ($groups as $key => $label) : ?><a href="#<?php echo esc_attr($key); ?>"><?php echo esc_html($label); ?></a><?php endforeach; ?>
        </nav><?php endif; ?>
    </div></header>
    <div class="container department-directory-body">
        <?php if ($type === 'cic_staff') :
            $staff_categories = $types[$type]['fields']['category'][2];
            $staff_counts = array_fill_keys(array_keys($staff_categories), 0);
            foreach ($records as $staff_record) {
                $staff_data = (array) get_post_meta($staff_record->ID, 'cic_directory_record', true);
                $category = isset($staff_categories[$staff_data['category'] ?? '']) ? $staff_data['category'] : 'other';
                $staff_counts[$category]++;
            }
        ?>
        <div class="staff-directory-controls" hidden>
            <div class="staff-directory-search"><label for="staff-search">Search staff</label><input type="search" id="staff-search" placeholder="Search by name or designation" autocomplete="off"></div>
            <fieldset class="staff-directory-filters"><legend>Filter staff</legend>
                <label><input type="checkbox" value="all" checked> All staff <span>(<?php echo count($records); ?>)</span></label>
                <?php foreach ($staff_categories as $category => $category_label) : if (!$staff_counts[$category]) continue; ?>
                <label><input type="checkbox" value="<?php echo esc_attr($category); ?>"> <?php echo esc_html($category_label); ?> <span>(<?php echo $staff_counts[$category]; ?>)</span></label>
                <?php endforeach; ?>
            </fieldset>
            <p class="staff-directory-results" role="status" aria-live="polite"></p>
        </div>
        <?php endif; ?>
        <?php if ($type === 'cic_research_area') : ?>
        <section class="department-directory-section" aria-labelledby="research-areas-title">
            <h2 id="research-areas-title">Research Areas</h2><p>Select an area to meet the faculty working in it.</p>
            <ul class="research-area-links">
                <?php foreach ($records as $area) : $members = cic_research_area_faculty($area, $faculty); ?>
                <li><a href="<?php echo esc_url(add_query_arg('area', $area->ID, get_permalink()) . '#research-faculty'); ?>" <?php if ($selected_area && $selected_area->ID === $area->ID) echo 'aria-current="true"'; ?>>
                    <span><?php echo esc_html($area->post_title); ?></span><span class="research-faculty-count"><?php echo esc_html(count($members)); ?><span class="screen-reader-text"><?php echo count($members) === 1 ? ' faculty member' : ' faculty members'; ?></span></span>
                </a></li>
                <?php endforeach; ?>
            </ul>
            <?php if (!$records) : ?><p class="department-empty">No research areas have been published yet.</p><?php endif; ?>
        </section>
        <?php if ($selected_area) : $members = cic_research_area_faculty($selected_area, $faculty); ?>
        <section id="research-faculty" class="department-directory-section">
            <h2><?php echo esc_html($selected_area->post_title); ?></h2>
            <?php if ($selected_area->post_content) : ?><div class="entry-content"><?php echo apply_filters('the_content', $selected_area->post_content); ?></div><?php endif; ?>
            <h3 class="research-faculty-heading">Faculty working in this area</h3>
            <div class="department-record-grid">
                <?php foreach ($members as $member) : ?>
                <article class="department-record-card">
                    <?php if ($member['photo_url']) : ?><div class="department-record-photo"><img src="<?php echo esc_url($member['photo_url']); ?>" alt="<?php echo esc_attr($member['name']); ?>" loading="lazy"></div><?php endif; ?>
                    <div class="department-record-copy"><h3><a href="<?php echo esc_url($member['profile_url']); ?>"><?php echo esc_html($member['name']); ?></a></h3>
                        <?php if ($member['designation']) : ?><p><?php echo esc_html($member['designation']); ?></p><?php endif; ?>
                        <p class="department-record-detail"><?php echo esc_html(implode(' · ', $member['research_areas'])); ?></p>
                        <a class="department-record-link" href="<?php echo esc_url($member['profile_url']); ?>">View faculty profile &rarr;</a>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
            <?php if (!$members) : ?><p class="department-empty">No faculty members are currently listed for this area.</p><?php endif; ?>
        </section>
        <?php endif; ?>
        <?php else : ?>
        <?php foreach ($groups as $key => $label) :
            $items = array_filter($records, function ($record) use ($type, $key) {
                $data = (array) get_post_meta($record->ID, 'cic_directory_record', true);
                return $type !== 'cic_alumni' || ($data['category'] ?? 'students') === $key;
            });
        ?>
        <section id="<?php echo esc_attr($key); ?>" class="department-directory-section">
            <h2><?php echo esc_html($label); ?></h2>
            <?php if (!$items) : ?><p class="department-empty">No <?php echo esc_html(strtolower($label)); ?> records have been published yet.</p><?php else : ?>
            <div class="department-record-grid">
            <?php foreach ($items as $record) : $data = (array) get_post_meta($record->ID, 'cic_directory_record', true); ?>
                <article class="department-record-card"<?php if ($type === 'cic_staff') : ?> data-staff-category="<?php echo esc_attr(isset($staff_categories[$data['category'] ?? '']) ? $data['category'] : 'other'); ?>" data-staff-search="<?php echo esc_attr($record->post_title . ' ' . ($data['role'] ?? '')); ?>"<?php endif; ?>>
                    <?php if (has_post_thumbnail($record)) : ?><div class="department-record-photo <?php echo $type === 'cic_lab' ? 'lab-photo' : ''; ?>"><?php echo get_the_post_thumbnail($record, 'medium_large', array('loading' => 'lazy')); ?></div><?php endif; ?>
                    <div class="department-record-copy"><h3><?php echo esc_html($record->post_title); ?></h3>
                    <?php foreach ($types[$type]['fields'] as $field_key => $field) :
                        if (empty($data[$field_key]) || in_array($field_key, array('category', 'url', 'faculty_ids'), true)) continue;
                    ?>
                        <p class="department-record-detail"><strong><?php echo esc_html($field[0]); ?>:</strong>
                        <?php if ($field[1] === 'email') : ?><a href="<?php echo esc_url('mailto:' . $data[$field_key]); ?>"><?php echo esc_html($data[$field_key]); ?></a>
                        <?php elseif ($field_key === 'phone') : ?><a href="<?php echo esc_url('tel:' . preg_replace('/[^+0-9]/', '', $data[$field_key])); ?>"><?php echo esc_html($data[$field_key]); ?></a>
                        <?php else : echo nl2br(esc_html($data[$field_key])); endif; ?></p>
                    <?php endforeach; ?>
                    <?php if (trim($record->post_content)) : ?><div class="entry-content department-record-description"><?php echo apply_filters('the_content', $record->post_content); ?></div><?php endif; ?>
                    <?php if (!empty($data['url'])) : ?><a class="department-record-link" href="<?php echo esc_url($data['url']); ?>"><?php echo $type === 'cic_lab' ? 'Visit lab website' : 'View public profile'; ?> &rarr;</a><?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
            </div><?php if ($type === 'cic_staff') : ?><p class="staff-directory-empty department-empty" hidden>No staff match these filters.</p><?php endif; ?><?php endif; ?>
        </section>
        <?php endforeach; ?>
        <?php endif; ?>
        <?php if ($type === 'cic_research_area') : $projects = cic_academic_projects(); ?>
        <section id="projects" class="department-directory-section research-projects">
            <h2>Department projects</h2>
            <div class="department-record-grid">
                <?php foreach ($projects as $project) : ?>
                <article class="department-record-card"><div class="department-record-copy">
                    <span class="academic-status"><?php echo $project['status'] === 'current' ? 'Current' : 'Completed'; ?></span>
                    <h3><?php echo esc_html($project['title']); ?></h3>
                    <?php foreach (array('lead', 'sponsor', 'description') as $key) : if (!empty($project[$key])) : ?><p class="department-record-detail"><?php echo esc_html($project[$key]); ?></p><?php endif; endforeach; ?>
                    <a class="department-record-link" href="<?php echo esc_url($project['url']); ?>">Project details &rarr;</a>
                </div></article>
                <?php endforeach; ?>
            </div>
            <?php if (!$projects) : ?><p class="department-empty">No projects have been published yet.</p><?php endif; ?>
        </section>
        <div class="department-section-nav"><a href="<?php echo esc_url(home_url('/faculty/')); ?>">Faculty research &amp; publications &rarr;</a><a href="<?php echo esc_url(home_url('/laboratories/')); ?>">Laboratories &amp; facilities &rarr;</a></div>
        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>
