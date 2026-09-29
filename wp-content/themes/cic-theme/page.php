<?php
/**
 * The template for displaying standard pages
 *
 * Used for custom pages created via the WordPress admin (e.g. Blog, custom content pages).
 *
 * @package cic-theme
 */

get_header(); ?>

<main id="main-content" class="standard-page-layout" style="min-height: 60vh; padding: 40px 0 80px 0; background: #f8fafc;">
    <div class="container" style="max-width: 960px; margin: 0 auto; padding: 0 20px;">
        
        <!-- Breadcrumb Navigation -->
        <div class="page-navigation" style="margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between;">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="back-link" style="display: inline-flex; align-items: center; gap: 8px; color: #2563eb; font-weight: 600; text-decoration: none; font-size: 14px;">
                <i class="fas fa-arrow-left"></i> <?php esc_html_e('Back to Home', 'cic-theme'); ?>
            </a>
            <span class="page-type-badge" style="background: #e2e8f0; color: #475569; padding: 4px 12px; border-radius: 9999px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">
                <?php esc_html_e('Page', 'cic-theme'); ?>
            </span>
        </div>

        <?php while ( have_posts() ) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('page-content-card'); ?> style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); padding: 40px 48px; overflow: hidden;">
                
                <header class="entry-header" style="margin-bottom: 28px; border-bottom: 1px solid #f1f5f9; padding-bottom: 24px;">
                    <h1 class="entry-title" style="font-size: 32px; line-height: 1.3; color: #0f172a; margin: 0; font-weight: 800;">
                        <?php the_title(); ?>
                    </h1>
                </header>

                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="entry-featured-image" style="margin-bottom: 32px; border-radius: 8px; overflow: hidden; max-height: 480px;">
                        <?php the_post_thumbnail('large', array('style' => 'width: 100%; height: auto; object-fit: cover; display: block;')); ?>
                    </div>
                <?php endif; ?>

                <div class="entry-content" style="color: #334155; font-size: 16px; line-height: 1.85;">
                    <?php 
                    the_content(); 
                    
                    wp_link_pages( array(
                        'before' => '<div class="page-links" style="margin-top: 24px; font-weight: 600;">' . esc_html__( 'Pages:', 'cic-theme' ),
                        'after'  => '</div>',
                    ) );
                    ?>
                </div>

                <footer class="entry-footer" style="margin-top: 48px; padding-top: 24px; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="btn-return" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 18px; background: #0f172a; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 14px; transition: background 0.2s ease;">
                        <i class="fas fa-home"></i> <?php esc_html_e('Return to Department Home', 'cic-theme'); ?>
                    </a>
                </footer>
            </article>
        <?php endwhile; ?>

    </div>
</main>

<?php get_footer(); ?>
