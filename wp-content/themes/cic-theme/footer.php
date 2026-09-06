    <footer class="site-footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <div class="footer-logo">
                        <?php if (has_custom_logo()) : ?>
                            <?php the_custom_logo(); ?>
                        <?php else : ?>
                            <h2><?php bloginfo('name'); ?></h2>
                        <?php endif; ?>
                    </div>
                    <p>Indian Institute of Technology Kharagpur, West Bengal, India - 721302</p>
                </div>
                <div class="footer-col">
                    <h3>QUICK LINKS</h3>
                    <?php wp_nav_menu(array('theme_location' => 'footer_links', 'fallback_cb' => false)); ?>
                </div>
                <div class="footer-col">
                    <h3>ACADEMICS</h3>
                    <?php wp_nav_menu(array('theme_location' => 'footer_academics', 'fallback_cb' => false)); ?>
                </div>
                <div class="footer-col">
                    <h3>CONTACT US</h3>
                    <p>Email: head@gssst.iitkgp.ac.in</p>
                    <p>Phone: +91-3222-282227</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> IIT Kharagpur. All Rights Reserved.</p>
                <div class="footer-bottom-links">
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms of Use</a>
                </div>
            </div>
        </div>
    </footer>
    <?php wp_footer(); ?>
</body>
</html>

