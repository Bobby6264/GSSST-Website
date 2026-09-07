<?php $footer_settings = cic_get_footer_settings(); ?>
<footer class="site-footer">
        <div class="container">
            <div class="footer-card-box">
                <div class="footer-columns-grid">
                    <!-- Brand Column -->
                    <div class="footer-brand-column">
                        <div class="footer-crest-box">
                            <?php 
                            $f_logo = !empty($footer_settings['brand_logo_url']) ? $footer_settings['brand_logo_url'] : get_template_directory_uri() . '/assets/images/iitkgp-logo.png';
                            ?>
                            <img src="<?php echo esc_url($f_logo); ?>" alt="Logo" width="44" height="44" style="object-fit: contain;">
                        </div>
                        <h3 class="footer-brand-title"><?php echo esc_html($footer_settings['brand_title']); ?></h3>
                        <p class="footer-brand-address"><?php echo nl2br(esc_html($footer_settings['brand_address'])); ?></p>
                    </div>

                    <!-- Middle Column (Middle Part) -->
                    <div class="footer-middle-column">
                        <!-- Quick Links Column -->
                        <div class="footer-links-column">
                            <h4 class="footer-col-header">QUICK LINKS</h4>
                            <ul class="footer-menu-list">
                                <?php 
                                if (!empty($footer_settings['quick_links'])) :
                                    foreach ($footer_settings['quick_links'] as $q_link) :
                                ?>
                                    <li><a href="<?php echo esc_url($q_link['url']); ?>"><?php echo esc_html($q_link['title']); ?></a></li>
                                <?php 
                                    endforeach;
                                endif; 
                                ?>
                            </ul>
                        </div>

                        <!-- Academics Column -->
                        <div class="footer-links-column">
                            <h4 class="footer-col-header">ACADEMICS</h4>
                            <ul class="footer-menu-list">
                                <?php 
                                if (!empty($footer_settings['academics_links'])) :
                                    foreach ($footer_settings['academics_links'] as $a_link) :
                                ?>
                                    <li><a href="<?php echo esc_url($a_link['url']); ?>"><?php echo esc_html($a_link['title']); ?></a></li>
                                <?php 
                                    endforeach;
                                endif; 
                                ?>
                            </ul>
                        </div>
                    </div>

                    <!-- Contact Column -->
                    <div class="footer-contact-column">
                        <h4 class="footer-col-header">CONTACT US</h4>
                        <div class="footer-contact-details">
                            <?php if (!empty($footer_settings['contact_email'])) : ?>
                                <p>Email: <a href="mailto:<?php echo esc_attr($footer_settings['contact_email']); ?>"><?php echo esc_html($footer_settings['contact_email']); ?></a></p>
                            <?php endif; ?>
                            <?php if (!empty($footer_settings['contact_phone'])) : ?>
                                <p>Phone: <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $footer_settings['contact_phone'])); ?>"><?php echo esc_html($footer_settings['contact_phone']); ?></a></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Footer Bottom -->
                <div class="footer-bottom-row">
                    <?php 
                    $copy = !empty($footer_settings['copyright_text']) ? $footer_settings['copyright_text'] : '&copy; ' . date('Y') . ' IIT Kharagpur. All Rights Reserved.';
                    $copy = str_replace(array('{year}', '%YEAR%'), date('Y'), $copy);
                    ?>
                    <p class="copyright-text"><?php echo wp_kses_post($copy); ?></p>
                    <div class="footer-legal-links">
                        <?php 
                        if (!empty($footer_settings['legal_links'])) :
                            foreach ($footer_settings['legal_links'] as $l_link) :
                        ?>
                            <a href="<?php echo esc_url($l_link['url']); ?>"><?php echo esc_html($l_link['title']); ?></a>
                        <?php 
                            endforeach;
                        endif; 
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    </div><!-- /.site-desktop-wrapper -->
    <?php wp_footer(); ?>
</body>
</html>
