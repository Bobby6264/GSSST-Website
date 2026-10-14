jQuery(function ($) {
    var $form = $('#your-profile');
    if (!$form.length) return;

    var $header = $('<div class="cic-admin-header-bar cic-profile-header"><div class="cic-admin-brand"><span class="dashicons dashicons-id"></span><div><h2>Profile Settings</h2><p class="cic-admin-tagline">Manage your account and directory information</p></div></div><nav class="cic-admin-tabs" aria-label="Profile sections"></nav></div>');
    var tabs = [
        {id: 'personal', title: 'Personal Info', icon: 'dashicons-admin-users'},
        {id: 'password', title: 'Change Password', icon: 'dashicons-lock'},
        {id: 'other', title: 'Other Details', icon: 'dashicons-database'}
    ];
    var panels = {};
    tabs.forEach(function (tab) {
        $('<a href="#' + tab.id + '" class="cic-tab-link"><span class="dashicons ' + tab.icon + '"></span>' + tab.title + '</a>').appendTo($header.find('nav'));
        panels[tab.id] = $('<section class="cic-profile-panel" id="cic-profile-' + tab.id + '"><h2>' + tab.title + '</h2><table class="form-table" role="presentation"><tbody></tbody></table></section>').appendTo($form);
    });
    var $submit = $form.children('p.submit').first();
    if ($submit.length) tabs.forEach(function (tab) { panels[tab.id].insertBefore($submit); });
    $form.before($header);

    function moveRow(selector, tab) {
        var $row = $form.find(selector).first();
        if ($row.length) $row.appendTo(panels[tab].find('tbody'));
    }
    // WordPress account fields keep their native names and saving behavior.
    [
        '.user-user-login-wrap', '.user-first-name-wrap', '.user-last-name-wrap',
        '.user-nickname-wrap', '.user-display-name-wrap', '.user-email-wrap'
    ].forEach(function (selector) { moveRow(selector, 'personal'); });
    [
        '.user-pass1-wrap', '.user-pass2-wrap', '.user-pass1', '.user-pass2'
    ].forEach(function (selector) { moveRow(selector, 'password'); });
    [
        '.user-designation-wrap', '.user-department-wrap', '.user-phone-wrap',
        '.user-office-wrap', '.user-research_areas-wrap', '.user-education-wrap'
    ].forEach(function (selector) { moveRow(selector, 'other'); });

    // Theme profile rows have no core row classes, so locate them by their input IDs.
    ['designation', 'department', 'phone', 'office', 'research_areas', 'education', 'website_url'].forEach(function (id) {
        var $field = $form.find('#' + id).first();
        if ($field.length) $field.closest('tr').appendTo(panels.other.find('tbody'));
    });
    $form.find('#faculty_photo').closest('tr').appendTo(panels.personal.find('tbody'));
    $form.find('#faculty_cover_image').closest('tr').appendTo(panels.personal.find('tbody'));
    $form.find('#cic_social_links').closest('tr').appendTo(panels.personal.find('tbody'));
    $form.find('#iitkgp_profile_url').closest('tr').appendTo(panels.other.find('tbody'));

    // Preserve other plugin fields and WordPress controls inside an appropriate tab.
    $form.children('table.form-table').each(function () {
        $(this).find('tr').each(function () {
            var $row = $(this);
            if ($row.find('input[name="pass1"], input[name="pass2"], #password').length) {
                $row.appendTo(panels.password.find('tbody'));
            } else if ($row.find('input[name="email"], input[name="first_name"], input[name="last_name"]').length) {
                $row.appendTo(panels.personal.find('tbody'));
            } else {
                $row.appendTo(panels.other.find('tbody'));
            }
        });
        $(this).hide();
    });
    $form.children('h2, h3').hide();
    $form.children('.application-passwords, .user-sessions-wrap').hide();

    function activate(id) {
        if (!panels[id]) id = 'personal';
        $header.find('.cic-tab-link').removeClass('active').removeAttr('aria-current');
        $header.find('a[href="#' + id + '"]').addClass('active').attr('aria-current', 'page');
        $.each(panels, function (key, $panel) { $panel.toggle(key === id); });
        try { sessionStorage.setItem('cic-profile-tab', id); } catch (e) {}
    }
    $header.on('click', '.cic-tab-link', function (event) {
        event.preventDefault();
        activate(this.hash.slice(1));
    });
    var initial = location.hash.slice(1);
    if (!panels[initial]) {
        try { initial = sessionStorage.getItem('cic-profile-tab'); } catch (e) {}
    }
    activate(initial || 'personal');
});
