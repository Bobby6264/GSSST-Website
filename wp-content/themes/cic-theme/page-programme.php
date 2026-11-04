<?php
/** Template Name: Programme Information */
get_header(); ?>
<main id="main-content" class="department-content-page">
    <header class="department-page-hero"><div class="container">
        <a href="<?php echo esc_url(home_url('/#academics')); ?>">Academics &amp; resources</a>
        <?php while (have_posts()) : the_post(); ?><h1><?php the_title(); ?></h1>
    </div></header>
    <div class="container programme-content entry-content"><?php the_content(); ?></div>
    <?php endwhile; ?>
</main>
<?php get_footer(); ?>
