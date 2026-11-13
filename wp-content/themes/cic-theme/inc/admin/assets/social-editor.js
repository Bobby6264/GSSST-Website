jQuery(function ($) {
    $('.cic-social-editor').each(function () {
        const editor = $(this);
        let index = Date.now();
        editor.on('click', '.cic-social-add', function () {
            editor.find('.cic-social-rows').append(editor.find('template').html().replaceAll('__INDEX__', String(index++)));
        });
        editor.on('click', '.cic-social-remove', function () { $(this).closest('.cic-social-editor-row').remove(); });
        function defaultIcon(row) {
            row.find('.cic-social-preview').empty().append($('<i aria-hidden="true">').attr('class', row.find('option:selected').attr('data-icon')));
        }
        editor.on('change', 'select', function () {
            const row = $(this).closest('.cic-social-editor-row');
            if (!row.find('.cic-social-image').val()) defaultIcon(row);
        });
        editor.on('click', '.cic-social-image-clear', function () {
            const row = $(this).closest('.cic-social-editor-row');
            row.find('.cic-social-image').val('');
            defaultIcon(row);
            $(this).siblings('.cic-social-image-picker').text('Choose symbol');
        });
        editor.on('click', '.cic-social-image-picker', function () {
            const button = $(this), row = button.closest('.cic-social-editor-row');
            const frame = wp.media({title: 'Choose link symbol', library: {type: 'image'}, multiple: false});
            frame.on('select', function () {
                const attachment = frame.state().get('selection').first().toJSON();
                row.find('.cic-social-image').val(attachment.id);
                row.find('.cic-social-preview').empty().append($('<img alt="">').attr('src', attachment.url));
                button.text('Change symbol');
            });
            frame.open();
        });
    });
});
