<?php
/** Template Name: Academic Resource */
get_header(); ?>
<main id="main-content" class="department-content-page academic-resource-page">
    <?php while (have_posts()) : the_post(); ?>
    <header class="department-page-hero"><div class="container">
        <a href="<?php echo esc_url(home_url('/#academics')); ?>">Academics &amp; resources</a>
        <h1><?php the_title(); ?></h1>
    </div></header>
    <div class="container programme-content entry-content">
        <div class="academic-resource-search" hidden>
            <label for="academic-resource-query">Search this page</label>
            <input type="search" id="academic-resource-query" placeholder="Search by course, semester, instructor or venue…">
            <p class="academic-resource-results" aria-live="polite"></p>
        </div>
        <?php the_content(); ?>
    </div>
    <?php endwhile; ?>
</main>
<?php get_footer(); ?>
