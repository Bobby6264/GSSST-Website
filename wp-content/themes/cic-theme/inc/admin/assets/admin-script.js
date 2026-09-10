/**
 * GSSST Theme Admin Panel JavaScript
 * Handles dynamic repeaters (Add, Delete, Reorder) and Media Uploader
 */
jQuery(document).ready(function($) {
    'use strict';

    // Helper: Re-index input names in a repeater
    function reindexRepeater($container) {
        var baseName = $container.data('name');
        if (!baseName) return;

        $container.children('.cic-repeater-row').each(function(rowIndex) {
            $(this).find('input, select, textarea').each(function() {
                var name = $(this).attr('name');
                if (name) {
                    // Replace the index in pattern baseName[0][field]
                    var newName = name.replace(new RegExp('^' + escapeRegExp(baseName) + '\\[\\d+\\]'), baseName + '[' + rowIndex + ']');
                    $(this).attr('name', newName);
                }
            });
        });
    }

    function escapeRegExp(string) {
        return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }

    // Generic row deletion
    $(document).on('click', '.cic-row-delete-btn', function(e) {
        e.preventDefault();
        var $row = $(this).closest('.cic-repeater-row');
        var $container = $row.closest('.cic-repeater-items');
        $row.fadeOut(200, function() {
            $(this).remove();
            reindexRepeater($container);
        });
    });

    // 1. Hero Slides Repeater: Add Slide
    $('.cic-repeater-add-slide-btn').on('click', function(e) {
        e.preventDefault();
        var $container = $('#hero-slides-repeater .cic-repeater-items');
        var index = $container.children('.cic-repeater-row').length;
        var rowHtml = `
            <div class="cic-repeater-row cic-media-row">
                <div class="cic-row-handle"><span class="dashicons dashicons-menu"></span></div>
                <div class="cic-media-preview-box">
                    <img src="" alt="Slide Preview" class="cic-img-preview" style="display:none;">
                    <div class="cic-placeholder"><span class="dashicons dashicons-format-image"></span></div>
                </div>
                <div class="cic-row-fields">
                    <input type="text" name="cic_hero_settings[slides][${index}][image_url]" value="" class="regular-text cic-media-url-input" placeholder="Image URL or choose from Media Library">
                    <button type="button" class="button cic-media-upload-btn"><span class="dashicons dashicons-upload"></span> Choose Image</button>
                </div>
                <div class="cic-row-actions">
                    <button type="button" class="button-link cic-row-delete-btn text-danger" title="Delete Slide"><span class="dashicons dashicons-trash"></span></button>
                </div>
            </div>`;
        $container.append(rowHtml);
        reindexRepeater($container);
    });

    // 2. Hero Action Buttons: Add Button
    $('.cic-repeater-add-btn-btn').on('click', function(e) {
        e.preventDefault();
        var $container = $('#hero-buttons-repeater .cic-repeater-items');
        var index = $container.children('.cic-repeater-row').length;
        var rowHtml = `
            <tr class="cic-repeater-row">
                <td class="cic-row-handle"><span class="dashicons dashicons-menu"></span></td>
                <td>
                    <input type="text" name="cic_hero_settings[buttons][${index}][text]" value="" class="regular-text" placeholder="e.g. New Button" required>
                </td>
                <td>
                    <input type="text" name="cic_hero_settings[buttons][${index}][url]" value="#" class="regular-text" placeholder="https://... or #" required>
                </td>
                <td>
                    <select name="cic_hero_settings[buttons][${index}][style]">
                        <option value="primary">Primary (Blue)</option>
                        <option value="secondary">Secondary (Glass)</option>
                        <option value="outline">Outline</option>
                    </select>
                </td>
                <td style="text-align:center;">
                    <input type="checkbox" name="cic_hero_settings[buttons][${index}][new_tab]" value="1">
                </td>
                <td>
                    <button type="button" class="button-link cic-row-delete-btn text-danger" title="Delete Button"><span class="dashicons dashicons-trash"></span></button>
                </td>
            </tr>`;
        $container.append(rowHtml);
        reindexRepeater($container);
    });

    // 3. About Us Details Table: Add Row
    $('.cic-repeater-add-detail-btn').on('click', function(e) {
        e.preventDefault();
        var $container = $('#about-details-repeater .cic-repeater-items');
        var index = $container.children('.cic-repeater-row').length;
        var rowHtml = `
            <tr class="cic-repeater-row">
                <td class="cic-row-handle"><span class="dashicons dashicons-menu"></span></td>
                <td>
                    <input type="text" name="cic_about_head_settings[details_table][${index}][label]" value="" class="regular-text" placeholder="Label">
                </td>
                <td>
                    <input type="text" name="cic_about_head_settings[details_table][${index}][value]" value="" class="regular-text" placeholder="Value">
                </td>
                <td>
                    <button type="button" class="button-link cic-row-delete-btn text-danger" title="Delete Row"><span class="dashicons dashicons-trash"></span></button>
                </td>
            </tr>`;
        $container.append(rowHtml);
        reindexRepeater($container);
    });

    // 4. Explore Department Cards: Add Card
    $('.cic-repeater-add-dept-card-btn').on('click', function(e) {
        e.preventDefault();
        var $container = $('#dept-cards-repeater .cic-repeater-items');
        var index = $container.children('.cic-repeater-row').length;
        var rowHtml = `
            <div class="cic-repeater-row cic-card-item">
                <div class="cic-card-item-header">
                    <span class="cic-row-handle"><span class="dashicons dashicons-menu"></span></span>
                    <h4 class="cic-card-item-title">Card #${index + 1}</h4>
                    <button type="button" class="button-link cic-row-delete-btn text-danger" title="Delete Card"><span class="dashicons dashicons-trash"></span></button>
                </div>
                <div class="cic-card-item-body">
                    <div class="cic-fields-grid-2">
                        <div class="cic-field-group">
                            <label>Card Title</label>
                            <input type="text" name="cic_dept_settings[cards][${index}][title]" value="" class="regular-text card-title-input" placeholder="e.g. Innovation Hub" required>
                        </div>
                        <div class="cic-field-group">
                            <label>Card Icon</label>
                            <select name="cic_dept_settings[cards][${index}][icon_type]">
                                <option value="chip">Microchip / Processor (Research)</option>
                                <option value="users">Users / Team (Faculty & Staff)</option>
                                <option value="award">Award / Trophy (Awards)</option>
                                <option value="book">Book / Graduation (Academics)</option>
                                <option value="flask">Flask / Science (Labs)</option>
                                <option value="laptop">Laptop / Code (Technology)</option>
                            </select>
                        </div>
                    </div>
                    <div class="cic-field-group">
                        <label>Card Description</label>
                        <textarea name="cic_dept_settings[cards][${index}][description]" rows="2" class="large-text" placeholder="Short description..."></textarea>
                    </div>
                    <div class="cic-fields-grid-2">
                        <div class="cic-field-group">
                            <label>Button Text</label>
                            <input type="text" name="cic_dept_settings[cards][${index}][btn_text]" value="Explore" class="regular-text" placeholder="Explore">
                        </div>
                        <div class="cic-field-group">
                            <label>Button Destination URL</label>
                            <input type="text" name="cic_dept_settings[cards][${index}][btn_url]" value="#" class="regular-text" placeholder="https://... or #">
                        </div>
                    </div>
                </div>
            </div>`;
        $container.append(rowHtml);
        reindexRepeater($container);
    });

    $(document).on('input', '.card-title-input', function() {
        var val = $(this).val();
        $(this).closest('.cic-card-item').find('.cic-card-item-title').text(val ? val : 'Untitled Card');
    });

    // 5. Academics: Add Column & Sub items
    $('.cic-repeater-add-acad-col-btn').on('click', function(e) {
        e.preventDefault();
        var $container = $('#academics-cols-repeater .cic-repeater-items');
        var index = $container.children('.cic-repeater-row').length;
        var rowHtml = `
            <div class="cic-repeater-row cic-card-item cic-acad-col-item">
                <div class="cic-card-item-header">
                    <span class="cic-row-handle"><span class="dashicons dashicons-menu"></span></span>
                    <h4 class="cic-card-item-title">Column #${index + 1}</h4>
                    <button type="button" class="button-link cic-row-delete-btn text-danger" title="Delete Column"><span class="dashicons dashicons-trash"></span></button>
                </div>
                <div class="cic-card-item-body">
                    <div class="cic-field-group">
                        <label>Column Title</label>
                        <input type="text" name="cic_academics_settings[columns][${index}][col_title]" value="" class="regular-text acad-col-title-input" placeholder="e.g. New Academic Category" required>
                    </div>
                    <div class="cic-sub-repeater" style="margin-top: 10px;">
                        <label>Items / Programs in this column:</label>
                        <table class="widefat cic-repeater-table" style="margin-top: 5px;">
                            <thead>
                                <tr>
                                    <th>Link Title</th>
                                    <th>Link Destination URL</th>
                                    <th style="width: 40px;"></th>
                                </tr>
                            </thead>
                            <tbody class="cic-sub-repeater-items" data-parent-idx="${index}"></tbody>
                        </table>
                        <div style="margin-top: 5px;">
                            <button type="button" class="button button-small cic-sub-add-item-btn" data-parent-idx="${index}"><span class="dashicons dashicons-plus"></span> Add Item</button>
                        </div>
                    </div>
                </div>
            </div>`;
        $container.append(rowHtml);
        reindexRepeater($container);
    });

    $(document).on('click', '.cic-sub-add-item-btn', function(e) {
        e.preventDefault();
        var parentIdx = $(this).data('parent-idx');
        var $tbody = $(this).closest('.cic-sub-repeater').find('.cic-sub-repeater-items');
        var subIdx = $tbody.children('.cic-sub-row').length;
        var subHtml = `
            <tr class="cic-sub-row">
                <td>
                    <input type="text" name="cic_academics_settings[columns][${parentIdx}][items][${subIdx}][title]" value="" class="regular-text" placeholder="Title">
                </td>
                <td>
                    <input type="text" name="cic_academics_settings[columns][${parentIdx}][items][${subIdx}][url]" value="#" class="regular-text" placeholder="URL">
                </td>
                <td>
                    <button type="button" class="button-link cic-sub-row-delete-btn text-danger" title="Delete Item"><span class="dashicons dashicons-trash"></span></button>
                </td>
            </tr>`;
        $tbody.append(subHtml);
    });

    $(document).on('click', '.cic-sub-row-delete-btn', function(e) {
        e.preventDefault();
        $(this).closest('.cic-sub-row').remove();
    });

    // ==========================================
    // 6. Hierarchical Navigation Menu Builder Handlers
    // ==========================================

    function reindexNavBuilder() {
        $('#cic-nav-builder .cic-nav-headings-list > .cic-heading-item').each(function(hIdx) {
            var $heading = $(this);
            $heading.attr('data-heading-idx', hIdx);

            // Reindex Heading inputs
            $heading.find('> .cic-tree-card-body > .cic-fields-grid-2 input').each(function() {
                var name = $(this).attr('name');
                if (!name) return;
                if (name.indexOf('[title]') !== -1) {
                    $(this).attr('name', 'cic_header_settings[nav_headings][' + hIdx + '][title]');
                } else if (name.indexOf('[url]') !== -1) {
                    $(this).attr('name', 'cic_header_settings[nav_headings][' + hIdx + '][url]');
                }
            });

            // Reindex subheadings
            $heading.find('.cic-subheadings-list > .cic-subheading-item').each(function(sIdx) {
                var $sub = $(this);
                $sub.attr('data-sub-idx', sIdx);

                $sub.find('> .cic-tree-card-body > .cic-fields-grid-2 input').each(function() {
                    var name = $(this).attr('name');
                    if (!name) return;
                    if (name.indexOf('[title]') !== -1) {
                        $(this).attr('name', 'cic_header_settings[nav_headings][' + hIdx + '][subheadings][' + sIdx + '][title]');
                    } else if (name.indexOf('[url]') !== -1) {
                        $(this).attr('name', 'cic_header_settings[nav_headings][' + hIdx + '][subheadings][' + sIdx + '][url]');
                    }
                });

                // Reindex sub-subheadings
                $sub.find('.cic-subsubheadings-list > .cic-subsubheading-row').each(function(ssIdx) {
                    var $sub3 = $(this);
                    $sub3.find('input').each(function() {
                        var name = $(this).attr('name');
                        if (!name) return;
                        if (name.indexOf('[title]') !== -1) {
                            $(this).attr('name', 'cic_header_settings[nav_headings][' + hIdx + '][subheadings][' + sIdx + '][sub_subheadings][' + ssIdx + '][title]');
                        } else if (name.indexOf('[url]') !== -1) {
                            $(this).attr('name', 'cic_header_settings[nav_headings][' + hIdx + '][subheadings][' + sIdx + '][sub_subheadings][' + ssIdx + '][url]');
                        }
                    });
                });
            });
        });
    }

    // Live update heading & subheading previews
    $(document).on('input', '.cic-heading-title-input', function() {
        var val = $(this).val().trim();
        $(this).closest('.cic-heading-item').find('> .cic-heading-header .cic-heading-label-preview').text(val ? val : 'New Heading');
    });

    $(document).on('input', '.cic-subheading-title-input', function() {
        var val = $(this).val().trim();
        $(this).closest('.cic-subheading-item').find('> .cic-subheading-header .cic-subheading-label-preview').text(val ? val : 'New Subheading');
    });

    // Delete Heading
    $(document).on('click', '.cic-delete-heading-btn', function(e) {
        e.preventDefault();
        if (confirm('Are you sure you want to delete this Heading and all its dropdown subheadings?')) {
            var $item = $(this).closest('.cic-heading-item');
            $item.fadeOut(200, function() {
                $item.remove();
                reindexNavBuilder();
            });
        }
    });

    // Delete Subheading
    $(document).on('click', '.cic-delete-subheading-btn', function(e) {
        e.preventDefault();
        var $item = $(this).closest('.cic-subheading-item');
        $item.fadeOut(200, function() {
            $item.remove();
            reindexNavBuilder();
        });
    });

    // Delete Sub-subheading
    $(document).on('click', '.cic-delete-subsubheading-btn', function(e) {
        e.preventDefault();
        var $row = $(this).closest('.cic-subsubheading-row');
        $row.fadeOut(150, function() {
            $row.remove();
            reindexNavBuilder();
        });
    });

    // Add Heading
    $(document).on('click', '.cic-add-heading-btn', function(e) {
        e.preventDefault();
        var $list = $('#cic-nav-builder .cic-nav-headings-list');
        var hIdx = $list.children('.cic-heading-item').length;
        var headingHtml = `
            <div class="cic-heading-item cic-tree-card" data-heading-idx="${hIdx}">
                <div class="cic-tree-card-header cic-heading-header">
                    <span class="cic-tree-badge badge-heading">HEADING</span>
                    <strong class="cic-heading-label-preview">New Heading</strong>
                    <div class="cic-tree-actions">
                        <button type="button" class="button-link cic-delete-heading-btn text-danger" title="Delete Heading"><span class="dashicons dashicons-trash"></span> Delete Heading</button>
                    </div>
                </div>
                <div class="cic-tree-card-body">
                    <div class="cic-fields-grid-2">
                        <div class="cic-field-group">
                            <label>Heading Label (Top-level Button)</label>
                            <input type="text" name="cic_header_settings[nav_headings][${hIdx}][title]" value="" class="regular-text cic-heading-title-input" placeholder="e.g. PEOPLE, ACADEMICS" required>
                        </div>
                        <div class="cic-field-group">
                            <label>Destination URL</label>
                            <input type="text" name="cic_header_settings[nav_headings][${hIdx}][url]" value="#" class="regular-text" placeholder="https://... or #">
                        </div>
                    </div>

                    <div class="cic-subheadings-wrapper">
                        <div class="cic-level-header">
                            <label><strong><span class="dashicons dashicons-arrow-down-alt2"></span> Dropdown Subheadings (Visible on hover)</strong></label>
                            <span class="description">(Optional) If empty, this heading acts as a direct link without a dropdown</span>
                        </div>
                        <div class="cic-subheadings-list"></div>
                        <div style="margin-top: 10px;">
                            <button type="button" class="button button-secondary cic-add-subheading-btn"><span class="dashicons dashicons-plus-alt2"></span> Add Subheading</button>
                        </div>
                    </div>
                </div>
            </div>`;
        $list.append(headingHtml);
        reindexNavBuilder();
    });

    // Add Subheading
    $(document).on('click', '.cic-add-subheading-btn', function(e) {
        e.preventDefault();
        var $heading = $(this).closest('.cic-heading-item');
        var hIdx = $heading.attr('data-heading-idx') || 0;
        var $subList = $heading.find('.cic-subheadings-list');
        var sIdx = $subList.children('.cic-subheading-item').length;
        var subHtml = `
            <div class="cic-subheading-item cic-tree-card-sub" data-sub-idx="${sIdx}">
                <div class="cic-tree-card-header cic-subheading-header">
                    <span class="cic-tree-badge badge-subheading">SUBHEADING</span>
                    <strong class="cic-subheading-label-preview">New Subheading</strong>
                    <div class="cic-tree-actions">
                        <button type="button" class="button-link cic-delete-subheading-btn text-danger" title="Delete Subheading"><span class="dashicons dashicons-trash"></span> Delete Subheading</button>
                    </div>
                </div>
                <div class="cic-tree-card-body">
                    <div class="cic-fields-grid-2">
                        <div class="cic-field-group">
                            <label>Subheading Label</label>
                            <input type="text" name="cic_header_settings[nav_headings][${hIdx}][subheadings][${sIdx}][title]" value="" class="regular-text cic-subheading-title-input" placeholder="e.g. Faculty Members" required>
                        </div>
                        <div class="cic-field-group">
                            <label>Destination URL</label>
                            <input type="text" name="cic_header_settings[nav_headings][${hIdx}][subheadings][${sIdx}][url]" value="#" class="regular-text" placeholder="https://... or #">
                        </div>
                    </div>

                    <div class="cic-subsubheadings-wrapper">
                        <div class="cic-level-header">
                            <label><strong><span class="dashicons dashicons-arrow-right-alt2"></span> Sub-subheadings (Nested 3rd Level)</strong></label>
                            <span class="description">(Optional) Displays nested side-dropdown when hovering this subheading</span>
                        </div>
                        <div class="cic-subsubheadings-list"></div>
                        <div style="margin-top: 8px;">
                            <button type="button" class="button button-small cic-add-subsubheading-btn"><span class="dashicons dashicons-plus"></span> Add Sub-subheading</button>
                        </div>
                    </div>
                </div>
            </div>`;
        $subList.append(subHtml);
        reindexNavBuilder();
    });

    // Add Sub-subheading
    $(document).on('click', '.cic-add-subsubheading-btn', function(e) {
        e.preventDefault();
        var $heading = $(this).closest('.cic-heading-item');
        var $sub = $(this).closest('.cic-subheading-item');
        var hIdx = $heading.attr('data-heading-idx') || 0;
        var sIdx = $sub.attr('data-sub-idx') || 0;
        var $ssList = $sub.find('.cic-subsubheadings-list');
        var ssIdx = $ssList.children('.cic-subsubheading-row').length;
        var ssHtml = `
            <div class="cic-subsubheading-row">
                <span class="cic-tree-badge badge-subsub">LEVEL 3</span>
                <input type="text" name="cic_header_settings[nav_headings][${hIdx}][subheadings][${sIdx}][sub_subheadings][${ssIdx}][title]" value="" class="regular-text" placeholder="e.g. Professors" required>
                <input type="text" name="cic_header_settings[nav_headings][${hIdx}][subheadings][${sIdx}][sub_subheadings][${ssIdx}][url]" value="#" class="regular-text" placeholder="https://... or #">
                <button type="button" class="button-link cic-delete-subsubheading-btn text-danger" title="Delete Sub-subheading"><span class="dashicons dashicons-trash"></span></button>
            </div>`;
        $ssList.append(ssHtml);
        reindexNavBuilder();
    });

    // 8. Footer Quick Links: Add
    $('.cic-repeater-add-quick-btn').on('click', function(e) {
        e.preventDefault();
        var $container = $('#footer-quick-links-repeater .cic-repeater-items');
        var index = $container.children('.cic-repeater-row').length;
        var rowHtml = `
            <tr class="cic-repeater-row">
                <td><input type="text" name="cic_footer_settings[quick_links][${index}][title]" value="" class="regular-text" placeholder="Title"></td>
                <td><input type="text" name="cic_footer_settings[quick_links][${index}][url]" value="#" class="regular-text" placeholder="URL"></td>
                <td><button type="button" class="button-link cic-row-delete-btn text-danger"><span class="dashicons dashicons-trash"></span></button></td>
            </tr>`;
        $container.append(rowHtml);
        reindexRepeater($container);
    });

    // 9. Footer Academics Links: Add
    $('.cic-repeater-add-acad-btn').on('click', function(e) {
        e.preventDefault();
        var $container = $('#footer-acad-links-repeater .cic-repeater-items');
        var index = $container.children('.cic-repeater-row').length;
        var rowHtml = `
            <tr class="cic-repeater-row">
                <td><input type="text" name="cic_footer_settings[academics_links][${index}][title]" value="" class="regular-text" placeholder="Title"></td>
                <td><input type="text" name="cic_footer_settings[academics_links][${index}][url]" value="#" class="regular-text" placeholder="URL"></td>
                <td><button type="button" class="button-link cic-row-delete-btn text-danger"><span class="dashicons dashicons-trash"></span></button></td>
            </tr>`;
        $container.append(rowHtml);
        reindexRepeater($container);
    });

    // 10. Footer Legal Links: Add
    $('.cic-repeater-add-legal-btn').on('click', function(e) {
        e.preventDefault();
        var $container = $('#footer-legal-links-repeater .cic-repeater-items');
        var index = $container.children('.cic-repeater-row').length;
        var rowHtml = `
            <tr class="cic-repeater-row">
                <td><input type="text" name="cic_footer_settings[legal_links][${index}][title]" value="" class="regular-text" placeholder="Title"></td>
                <td><input type="text" name="cic_footer_settings[legal_links][${index}][url]" value="#" class="regular-text" placeholder="URL"></td>
                <td><button type="button" class="button-link cic-row-delete-btn text-danger"><span class="dashicons dashicons-trash"></span></button></td>
            </tr>`;
        $container.append(rowHtml);
        reindexRepeater($container);
    });

    // WordPress Media Uploader Handling
    $(document).on('click', '.cic-media-upload-btn', function(e) {
        e.preventDefault();
        var $btn = $(this);
        var $parent = $btn.closest('.cic-media-row, .cic-media-picker-group');
        var $input = $parent.find('.cic-media-url-input');
        var $preview = $parent.find('.cic-img-preview');
        var $placeholder = $parent.find('.cic-placeholder');
        var $removeBtn = $parent.find('.cic-media-remove-btn');

        var frame = wp.media({
            title: 'Select or Upload Image',
            button: { text: 'Use this image' },
            multiple: false
        });

        frame.on('select', function() {
            var attachment = frame.state().get('selection').first().toJSON();
            $input.val(attachment.url);
            $preview.attr('src', attachment.url).show();
            $placeholder.hide();
            if ($removeBtn.length) {
                $removeBtn.show();
            }
        });

        frame.open();
    });

    $(document).on('click', '.cic-media-remove-btn', function(e) {
        e.preventDefault();
        var $parent = $(this).closest('.cic-media-picker-group');
        $parent.find('.cic-media-url-input').val('');
        $parent.find('.cic-img-preview').attr('src', '').hide();
        $parent.find('.cic-placeholder').show();
        $(this).hide();
    });
});

