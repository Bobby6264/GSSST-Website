<?php
/**
 * Authentication and Login Handler
 *
 * Handles AJAX login, role-based redirection scaffolding, and credentials verification.
 *
 * @package cic-theme
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Determine redirect URL based on user role.
 *
 * Currently redirects all users to the admin page as requested.
 * Extensible for future role-based portal routing (e.g., faculty, student, staff).
 *
 * @param WP_User $user The authenticated WP_User object.
 * @return string The destination URL.
 */
function cic_get_user_role_redirect_url($user) {
    if (!$user || is_wp_error($user)) {
        return admin_url();
    }

    $roles = (array) $user->roles;

    // Default target: Admin dashboard
    $redirect_url = admin_url();

    // Scaffolding for future role-based portals:
    // When specific role routes are established, they can be activated here:
    if (in_array('administrator', $roles, true)) {
        $redirect_url = admin_url();
    } elseif (in_array('editor', $roles, true)) {
        $redirect_url = admin_url();
    } elseif (in_array('author', $roles, true)) {
        $redirect_url = admin_url();
    } elseif (in_array('contributor', $roles, true)) {
        $redirect_url = admin_url();
    } elseif (in_array('subscriber', $roles, true)) {
        $redirect_url = admin_url();
    } elseif (in_array('faculty', $roles, true)) {
        // Future faculty portal
        $redirect_url = admin_url();
    } elseif (in_array('student', $roles, true)) {
        // Future student portal
        $redirect_url = admin_url();
    } elseif (in_array('staff', $roles, true)) {
        // Future staff portal
        $redirect_url = admin_url();
    }

    /**
     * Filter the redirect URL after successful login.
     *
     * @param string   $redirect_url The target redirect URL.
     * @param WP_User  $user         The authenticated user object.
     * @param string[] $roles        Array of user role strings.
     */
    return apply_filters('cic_login_role_redirect_url', $redirect_url, $user, $roles);
}

/**
 * AJAX Login Handler
 */
function cic_ajax_login_handler() {
    // Check CSRF nonce
    if (!check_ajax_referer('cic_login_nonce', 'security', false)) {
        wp_send_json_error(array(
            'message' => __('Security verification failed. Please refresh the page and try again.', 'cic-theme')
        ));
    }

    $raw_email = isset($_POST['email']) ? trim(wp_unslash($_POST['email'])) : '';
    $email     = sanitize_email($raw_email);
    $password  = isset($_POST['password']) ? $_POST['password'] : '';
    $remember  = !empty($_POST['remember']) && ($_POST['remember'] === 'true' || $_POST['remember'] === '1');

    if (empty($raw_email)) {
        wp_send_json_error(array(
            'message' => __('Please enter your email address.', 'cic-theme'),
            'field'   => 'email'
        ));
    }

    // Strictly enforce valid email address format (no usernames allowed)
    if (empty($email) || !is_email($email)) {
        wp_send_json_error(array(
            'message' => __('Please enter a valid email address (e.g., name@domain.com). Usernames are not accepted.', 'cic-theme'),
            'field'   => 'email'
        ));
    }

    if (empty($password)) {
        wp_send_json_error(array(
            'message' => __('Please enter your password.', 'cic-theme'),
            'field'   => 'password'
        ));
    }

    // Strictly look up user ONLY by email (do NOT look up by username or fallback to username)
    $user_obj = get_user_by('email', $email);
    if (!$user_obj) {
        wp_send_json_error(array(
            'message' => __('Invalid email or password. Please verify your credentials.', 'cic-theme')
        ));
    }

    // Verify password for this user
    if (!wp_check_password($password, $user_obj->user_pass, $user_obj->ID)) {
        wp_send_json_error(array(
            'message' => __('Invalid email or password. Please verify your credentials.', 'cic-theme')
        ));
    }

    // Authenticate and set login session
    wp_set_current_user($user_obj->ID, $user_obj->user_login);
    wp_set_auth_cookie($user_obj->ID, $remember, is_ssl());
    do_action('wp_login', $user_obj->user_login, $user_obj);

    $user = $user_obj;

    // Determine target redirect based on role
    $redirect_url = cic_get_user_role_redirect_url($user);

    wp_send_json_success(array(
        'message'      => __('Authentication successful. Redirecting...', 'cic-theme'),
        'redirect_url' => $redirect_url,
        'user_name'    => $user->display_name ?: $user->user_login
    ));
}
add_action('wp_ajax_nopriv_cic_ajax_login', 'cic_ajax_login_handler');
add_action('wp_ajax_cic_ajax_login', 'cic_ajax_login_handler');

/**
 * Always redirect to the landing page upon signing out.
 *
 * @param string  $redirect_to           The default redirect destination.
 * @param string  $requested_redirect_to The redirect destination requested by user.
 * @param WP_User $user                  The user being logged out.
 * @return string The landing page URL.
 */
function cic_logout_redirect($redirect_to, $requested_redirect_to, $user) {
    return home_url('/');
}
add_filter('logout_redirect', 'cic_logout_redirect', 99, 3);

/**
 * Filter the default WordPress login URL to use the custom internal login page.
 *
 * @param string $login_url    The default login URL.
 * @param string $redirect     The target URL to redirect to after login.
 * @param bool   $force_reauth Whether to force re-authentication.
 * @return string Custom login URL.
 */
function cic_custom_login_url($login_url, $redirect, $force_reauth) {
    $custom_url = home_url('/login/');
    if (!empty($redirect)) {
        $custom_url = add_query_arg('redirect_to', urlencode($redirect), $custom_url);
    }
    return $custom_url;
}
add_filter('login_url', 'cic_custom_login_url', 10, 3);

/**
 * Intercept wp-login.php requests:
 * 1. If user just logged out (?loggedout=true), redirect directly to landing page.
 * 2. If accessing old default login screen without an action, redirect to custom login page.
 */
function cic_redirect_old_wp_login() {
    global $pagenow;
    if ($pagenow === 'wp-login.php') {
        // If logged out, redirect immediately to landing page
        if (isset($_GET['loggedout']) && $_GET['loggedout'] === 'true') {
            wp_safe_redirect(home_url('/'));
            exit;
        }

        // If someone visits wp-login.php to view the form (GET/HEAD), redirect to custom login page
        $action = isset($_GET['action']) ? $_GET['action'] : '';
        if (empty($action) || $action === 'login') {
            if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] === 'GET' || $_SERVER['REQUEST_METHOD'] === 'HEAD')) {
                wp_safe_redirect(home_url('/login/'));
                exit;
            }
        }
    }
}
add_action('init', 'cic_redirect_old_wp_login');
add_action('login_init', 'cic_redirect_old_wp_login');

