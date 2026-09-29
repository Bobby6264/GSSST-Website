<?php
/**
 * Template Name: Login Page
 *
 * A premium, modern, distraction-free login portal for
 * G.S. Sanyal School of Technology, IIT Kharagpur.
 *
 * @package cic-theme
 */

if (!defined('ABSPATH')) {
    exit;
}

// Handle standard server-side POST authentication fallback
$login_error = '';
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cic_login_submit'])) {
    if (!isset($_POST['cic_login_nonce']) || !wp_verify_nonce($_POST['cic_login_nonce'], 'cic_login_action')) {
        $login_error = __('Security verification failed. Please refresh and try again.', 'cic-theme');
    } else {
        $raw_email = isset($_POST['user_email']) ? trim(wp_unslash($_POST['user_email'])) : '';
        $email     = sanitize_email($raw_email);
        $password  = isset($_POST['user_password']) ? $_POST['user_password'] : '';
        $remember  = !empty($_POST['remember_me']);

        if (empty($raw_email)) {
            $login_error = __('Please enter your email address.', 'cic-theme');
        } elseif (empty($email) || !is_email($email)) {
            $login_error = __('Please enter a valid email address (e.g., name@domain.com). Usernames are not accepted.', 'cic-theme');
        } elseif (empty($password)) {
            $login_error = __('Please enter your password.', 'cic-theme');
        } else {
            // Strictly look up user ONLY by email (do not look up by username or fallback to username)
            $user_obj = get_user_by('email', $email);

            if (!$user_obj || !wp_check_password($password, $user_obj->user_pass, $user_obj->ID)) {
                $login_error = __('Invalid email or password. Please verify your credentials.', 'cic-theme');
            } else {
                wp_set_current_user($user_obj->ID, $user_obj->user_login);
                wp_set_auth_cookie($user_obj->ID, $remember, is_ssl());
                do_action('wp_login', $user_obj->user_login, $user_obj);

                $redirect_url = cic_get_user_role_redirect_url($user_obj);
                wp_safe_redirect($redirect_url);
                exit;
            }
        }
    }
}

get_header();

$header_settings = cic_get_header_settings();
$logo_url        = !empty($header_settings['logo_url']) ? $header_settings['logo_url'] : get_template_directory_uri() . '/assets/images/iitkgp-logo.png';
$dept_name       = !empty($header_settings['dept_name']) ? $header_settings['dept_name'] : 'G.S. Sanyal School of Technology';
$dept_subtitle   = !empty($header_settings['dept_subtitle']) ? $header_settings['dept_subtitle'] : 'INDIAN INSTITUTE OF TECHNOLOGY KHARAGPUR';
$is_logged_in    = is_user_logged_in();
$current_user    = $is_logged_in ? wp_get_current_user() : null;
?>

<main id="primary" class="cic-login-viewport" role="main">
    <!-- Ambient Atmospheric Background Elements -->
    <div class="cic-login-bg-decor" aria-hidden="true">
        <div class="cic-bg-glow cic-bg-glow-1"></div>
        <div class="cic-bg-glow cic-bg-glow-2"></div>
        <div class="cic-bg-grid"></div>
    </div>

    <div class="cic-login-shell">
        <div class="cic-login-card">
            <!-- Brand Accent Bar -->
            <div class="cic-card-accent-bar" aria-hidden="true"></div>

            <div class="cic-card-inner">
                <!-- Portal Badge -->
                <div class="cic-portal-badge-wrap">
                    <span class="cic-portal-badge">
                        <i class="fa-solid fa-shield-halved" aria-hidden="true"></i>
                        <span>INTERNAL ACCESS</span>
                    </span>
                </div>

                <!-- Logo & Institution Identity -->
                <div class="cic-brand-header">
                    <div class="cic-logo-crest-wrap">
                        <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr($dept_name); ?> Logo" class="cic-login-crest" width="56" height="56">
                    </div>
                    <h1 class="cic-portal-title"><?php esc_html_e('Internal Portal', 'cic-theme'); ?></h1>
                    <p class="cic-portal-subtitle"><?php echo esc_html($dept_name); ?></p>
                    <span class="cic-institute-name"><?php echo esc_html($dept_subtitle); ?></span>
                </div>

                <?php if ($is_logged_in && $current_user) : ?>
                    <!-- State: Already Signed In -->
                    <div class="cic-logged-in-panel">
                        <div class="cic-user-avatar-wrap">
                            <?php echo get_avatar($current_user->ID, 64, '', esc_attr($current_user->display_name), array('class' => 'cic-user-avatar')); ?>
                        </div>
                        <h2 class="cic-welcome-name">
                            <?php 
                            /* translators: %s: user display name */
                            printf(esc_html__('Welcome back, %s', 'cic-theme'), esc_html($current_user->display_name ?: $current_user->user_login)); 
                            ?>
                        </h2>
                        <p class="cic-user-email"><?php echo esc_html($current_user->user_email); ?></p>
                        
                        <?php 
                        $roles = (array) $current_user->roles;
                        $display_role = !empty($roles) ? ucfirst($roles[0]) : 'User';
                        ?>
                        <span class="cic-role-pill"><?php echo esc_html($display_role); ?></span>

                        <div class="cic-logged-in-actions">
                            <a href="<?php echo esc_url(cic_get_user_role_redirect_url($current_user)); ?>" class="cic-btn-primary">
                                <span><?php esc_html_e('Go to Admin Dashboard', 'cic-theme'); ?></span>
                                <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                            </a>
                            <a href="<?php echo esc_url(wp_logout_url(home_url('/'))); ?>" class="cic-btn-secondary">
                                <i class="fa-solid fa-arrow-right-from-bracket" aria-hidden="true"></i>
                                <span><?php esc_html_e('Sign Out', 'cic-theme'); ?></span>
                            </a>
                        </div>
                    </div>

                <?php else : ?>
                    <!-- State: Sign In Form -->
                    
                    <!-- Dynamic Alert Container (AJAX and Server-side) -->
                    <div id="cic_login_alert" class="cic-login-alert <?php echo !empty($login_error) ? 'is-visible is-error' : ''; ?>" role="alert" aria-live="polite">
                        <div class="cic-alert-icon">
                            <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
                        </div>
                        <div class="cic-alert-message" id="cic_alert_text">
                            <?php echo !empty($login_error) ? esc_html($login_error) : ''; ?>
                        </div>
                    </div>

                    <form id="cic_login_form" class="cic-login-form" method="POST" action="" novalidate>
                        <?php wp_nonce_field('cic_login_action', 'cic_login_nonce'); ?>

                        <!-- Email Address Field -->
                        <div class="cic-field-group">
                            <label for="cic_user_email" class="cic-field-label">
                                <span><?php esc_html_e('Email Address', 'cic-theme'); ?></span>
                            </label>
                            <div class="cic-input-wrapper">
                                <span class="cic-input-affix" aria-hidden="true">
                                    <i class="fa-regular fa-envelope"></i>
                                </span>
                                <input 
                                    type="email" 
                                    id="cic_user_email" 
                                    name="user_email" 
                                    class="cic-text-input" 
                                    placeholder="<?php esc_attr_e('name@iitkgp.ac.in', 'cic-theme'); ?>" 
                                    autocomplete="email" 
                                    inputmode="email"
                                    pattern="[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}"
                                    title="<?php esc_attr_e('Please enter a valid email address (e.g., name@domain.com)', 'cic-theme'); ?>"
                                    required 
                                    spellcheck="false"
                                    value="<?php echo isset($_POST['user_email']) ? esc_attr($_POST['user_email']) : ''; ?>"
                                >
                            </div>
                        </div>

                        <!-- Password Field with Show/Hide Toggle -->
                        <div class="cic-field-group">
                            <label for="cic_user_password" class="cic-field-label">
                                <span><?php esc_html_e('Password', 'cic-theme'); ?></span>
                            </label>
                            <div class="cic-input-wrapper">
                                <span class="cic-input-affix" aria-hidden="true">
                                    <i class="fa-solid fa-lock"></i>
                                </span>
                                <input 
                                    type="password" 
                                    id="cic_user_password" 
                                    name="user_password" 
                                    class="cic-text-input cic-password-input" 
                                    placeholder="••••••••••••" 
                                    autocomplete="current-password" 
                                    required
                                >
                                <button 
                                    type="button" 
                                    id="cic_toggle_password" 
                                    class="cic-password-toggle-btn" 
                                    aria-label="<?php esc_attr_e('Show password', 'cic-theme'); ?>"
                                    aria-pressed="false"
                                    tabindex="0"
                                >
                                    <i class="fa-regular fa-eye" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Remember Me Option -->
                        <div class="cic-form-auxiliary">
                            <label class="cic-remember-control" for="cic_remember_me">
                                <input 
                                    type="checkbox" 
                                    id="cic_remember_me" 
                                    name="remember_me" 
                                    value="1" 
                                    class="cic-checkbox-input"
                                    <?php checked(isset($_POST['remember_me'])); ?>
                                >
                                <span class="cic-custom-check" aria-hidden="true">
                                    <i class="fa-solid fa-check"></i>
                                </span>
                                <span class="cic-remember-label-text"><?php esc_html_e('Remember me', 'cic-theme'); ?></span>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <div class="cic-submit-wrap">
                            <button type="submit" id="cic_submit_btn" name="cic_login_submit" class="cic-submit-button">
                                <span class="cic-btn-label">
                                    <span><?php esc_html_e('Sign In', 'cic-theme'); ?></span>
                                    <i class="fa-solid fa-arrow-right cic-btn-arrow" aria-hidden="true"></i>
                                </span>
                                <span class="cic-btn-spinner-state" aria-hidden="true">
                                    <span class="cic-spinner-ring"></span>
                                    <span class="cic-spinnerText"><?php esc_html_e('Authenticating...', 'cic-theme'); ?></span>
                                </span>
                            </button>
                        </div>
                    </form>

                    <!-- Security & Administrative Footnote -->
                    <div class="cic-card-security-footer">
                        <i class="fa-solid fa-shield-halved cic-shield-icon" aria-hidden="true"></i>
                        <p class="cic-security-text">
                            <?php esc_html_e('Authorized access only. User accounts are created and configured exclusively by the system administrator.', 'cic-theme'); ?>
                        </p>
                    </div>

                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php
get_footer();
