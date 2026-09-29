/**
 * CIC Theme - Login Page Interactive Behaviors
 *
 * Handles AJAX authentication, password visibility toggling,
 * real-time feedback, and role-based redirects.
 *
 * @package cic-theme
 */

(function($) {
    'use strict';

    $(function() {
        const $form         = $('#cic_login_form');
        const $emailInput   = $('#cic_user_email');
        const $passInput    = $('#cic_user_password');
        const $rememberBox  = $('#cic_remember_me');
        const $toggleBtn    = $('#cic_toggle_password');
        const $submitBtn    = $('#cic_submit_btn');
        const $alert        = $('#cic_login_alert');
        const $alertText    = $('#cic_alert_text');
        const $alertIcon    = $alert.find('.cic-alert-icon i');

        // Check if required elements exist
        if (!$form.length) {
            return;
        }

        /**
         * Helper: Show Alert
         */
        function showAlert(message, type) {
            $alert.removeClass('is-error is-success cic-shake-animation');
            
            if (type === 'success') {
                $alert.addClass('is-success is-visible');
                $alertIcon.attr('class', 'fa-solid fa-circle-check');
            } else {
                $alert.addClass('is-error is-visible');
                $alertIcon.attr('class', 'fa-solid fa-circle-exclamation');
            }

            $alertText.text(message);

            // Trigger reflow to restart CSS shake animation
            void $alert[0].offsetWidth;
            $alert.addClass('cic-shake-animation');
        }

        /**
         * Helper: Clear Alert
         */
        function clearAlert() {
            $alert.removeClass('is-visible is-error is-success cic-shake-animation');
            $alertText.text('');
        }

        /**
         * Helper: Set Button Loading State
         */
        function setLoadingState(isLoading, successMessage) {
            if (isLoading) {
                $submitBtn.addClass('is-loading').prop('disabled', true);
                $emailInput.prop('readonly', true);
                $passInput.prop('readonly', true);
            } else {
                $submitBtn.removeClass('is-loading').prop('disabled', false);
                $emailInput.prop('readonly', false);
                $passInput.prop('readonly', false);
            }

            if (successMessage) {
                $submitBtn.addClass('is-success');
                $submitBtn.find('.cic-spinnerText').text(successMessage);
            }
        }

        /**
         * 1. Toggle Password Visibility
         */
        if ($toggleBtn.length && $passInput.length) {
            $toggleBtn.on('click', function(e) {
                e.preventDefault();
                const isPassword = $passInput.attr('type') === 'password';
                const $icon = $toggleBtn.find('i');

                if (isPassword) {
                    $passInput.attr('type', 'text');
                    $icon.removeClass('fa-eye').addClass('fa-eye-slash');
                    $toggleBtn.attr('aria-pressed', 'true').attr('aria-label', 'Hide password');
                } else {
                    $passInput.attr('type', 'password');
                    $icon.removeClass('fa-eye-slash').addClass('fa-eye');
                    $toggleBtn.attr('aria-pressed', 'false').attr('aria-label', 'Show password');
                }

                $passInput.focus();
            });
        }

        /**
         * 2. Clear alert when user starts typing again
         */
        $emailInput.add($passInput).on('input', function() {
            if ($alert.hasClass('is-visible') && !$alert.hasClass('is-success')) {
                $alert.removeClass('cic-shake-animation');
            }
        });

        /**
         * 3. Handle Form Submission via AJAX
         */
        $form.on('submit', function(e) {
            // Check if AJAX localization is available
            if (typeof cic_login_ajax === 'undefined' || !cic_login_ajax.ajax_url) {
                // Fallback to standard POST submission
                return true;
            }

            e.preventDefault();

            const email    = $.trim($emailInput.val());
            const password = $passInput.val();
            const remember = $rememberBox.is(':checked');

            // Strict client-side email validation
            const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

            if (!email) {
                showAlert('Please enter your email address.', 'error');
                $emailInput.focus();
                return;
            }

            if (!emailRegex.test(email)) {
                showAlert('Please enter a valid email address (e.g., name@domain.com). Usernames are not accepted.', 'error');
                $emailInput.focus().select();
                return;
            }

            if (!password) {
                showAlert('Please enter your password.', 'error');
                $passInput.focus();
                return;
            }

            // Set loading state
            setLoadingState(true);
            clearAlert();

            // AJAX request to backend authentication handler
            $.ajax({
                url: cic_login_ajax.ajax_url,
                type: 'POST',
                dataType: 'json',
                data: {
                    action:   'cic_ajax_login',
                    security: cic_login_ajax.nonce,
                    email:    email,
                    password: password,
                    remember: remember
                },
                success: function(response) {
                    if (response && response.success) {
                        const redirectUrl = response.data && response.data.redirect_url 
                            ? response.data.redirect_url 
                            : '/wp-admin/';

                        showAlert(response.data.message || 'Authentication successful. Redirecting...', 'success');
                        setLoadingState(true, 'Redirecting...');

                        // Smooth transition delay before redirect
                        setTimeout(function() {
                            window.location.href = redirectUrl;
                        }, 650);
                    } else {
                        const errMsg = (response && response.data && response.data.message)
                            ? response.data.message
                            : 'Invalid credentials. Please verify your email and password.';
                        
                        setLoadingState(false);
                        showAlert(errMsg, 'error');
                        $passInput.focus().select();
                    }
                },
                error: function(xhr, status, error) {
                    setLoadingState(false);
                    showAlert('A network error occurred while connecting to the portal. Please try again.', 'error');
                    $passInput.focus();
                }
            });
        });

        // Auto-focus on email field if empty
        if (!$emailInput.val()) {
            setTimeout(function() {
                $emailInput.focus();
            }, 100);
        }
    });

})(jQuery);
