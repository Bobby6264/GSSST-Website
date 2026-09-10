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

    function updateHeadingLimit() {
        var count = $('#cic-nav-builder .cic-nav-headings-list > .cic-heading-item').length;
        var $btn = $('.cic-add-heading-btn');
        var $countNum = $('#cic-heading-count-num');
        if ($countNum.length) {
            $countNum.text(count);
        }
        if (count >= 8) {
            $btn.prop('disabled', true).addClass('disabled').html('<span class="dashicons dashicons-lock"></span> Maximum 8 Headings Reached');
            if ($('#cic-heading-limit-notice').length === 0) {
                $btn.parent().append('<span id="cic-heading-limit-notice" style="color: #d63638; font-weight: 600; font-size: 13px;">(Maximum 8 navbar headings allowed)</span>');
            }
        } else {
            $btn.prop('disabled', false).removeClass('disabled').html('<span class="dashicons dashicons-plus-alt2"></span> Add Heading (Top-Level Menu)');
            $('#cic-heading-limit-notice').remove();
        }
    }

    function updateHeadingOrderButtons() {
        var $headings = $('#cic-nav-builder .cic-nav-headings-list > .cic-heading-item');
        $headings.each(function(index) {
            var isFirst = (index === 0);
            var isLast = (index === $headings.length - 1);
            $(this).find('> .cic-heading-header .cic-move-heading-up-btn').prop('disabled', isFirst);
            $(this).find('> .cic-heading-header .cic-move-heading-down-btn').prop('disabled', isLast);
            $(this).find('> .cic-heading-header .badge-heading').text('HEADING ' + (index + 1));
        });

        $('#cic-nav-builder .cic-subheadings-list').each(function() {
            var $subs = $(this).children('.cic-subheading-item');
            $subs.each(function(sIndex) {
                var sFirst = (sIndex === 0);
                var sLast = (sIndex === $subs.length - 1);
                $(this).find('> .cic-subheading-header .cic-move-sub-up-btn').prop('disabled', sFirst);
                $(this).find('> .cic-subheading-header .cic-move-sub-down-btn').prop('disabled', sLast);
            });
        });
    }

    function initNavSortables() {
        if ($.fn.sortable) {
            $('#cic-nav-builder .cic-nav-headings-list').sortable({
                handle: '.cic-heading-header',
                items: '> .cic-heading-item',
                placeholder: 'cic-heading-sortable-placeholder',
                axis: 'y',
                cursor: 'grab',
                opacity: 0.85,
                stop: function() {
                    reindexNavBuilder();
                    updateHeadingOrderButtons();
                }
            });

            $('#cic-nav-builder .cic-subheadings-list').sortable({
                handle: '.cic-subheading-header',
                items: '> .cic-subheading-item',
                placeholder: 'cic-subheading-sortable-placeholder',
                axis: 'y',
                cursor: 'grab',
                opacity: 0.85,
                stop: function() {
                    reindexNavBuilder();
                    updateHeadingOrderButtons();
                }
            });
        }
    }

    // Move Heading Up
    $(document).on('click', '.cic-move-heading-up-btn', function(e) {
        e.preventDefault();
        var $item = $(this).closest('.cic-heading-item');
        var $prev = $item.prev('.cic-heading-item');
        if ($prev.length > 0) {
            $item.insertBefore($prev);
            reindexNavBuilder();
            updateHeadingLimit();
            updateHeadingOrderButtons();
            $item.css('background-color', '#e8f4fd');
            setTimeout(function() { $item.css('background-color', ''); }, 400);
        }
    });

    // Move Heading Down
    $(document).on('click', '.cic-move-heading-down-btn', function(e) {
        e.preventDefault();
        var $item = $(this).closest('.cic-heading-item');
        var $next = $item.next('.cic-heading-item');
        if ($next.length > 0) {
            $item.insertAfter($next);
            reindexNavBuilder();
            updateHeadingLimit();
            updateHeadingOrderButtons();
            $item.css('background-color', '#e8f4fd');
            setTimeout(function() { $item.css('background-color', ''); }, 400);
        }
    });

    // Move Subheading Up
    $(document).on('click', '.cic-move-sub-up-btn', function(e) {
        e.preventDefault();
        var $item = $(this).closest('.cic-subheading-item');
        var $prev = $item.prev('.cic-subheading-item');
        if ($prev.length > 0) {
            $item.insertBefore($prev);
            reindexNavBuilder();
            updateHeadingOrderButtons();
            $item.css('background-color', '#edf7ed');
            setTimeout(function() { $item.css('background-color', ''); }, 400);
        }
    });

    // Move Subheading Down
    $(document).on('click', '.cic-move-sub-down-btn', function(e) {
        e.preventDefault();
        var $item = $(this).closest('.cic-subheading-item');
        var $next = $item.next('.cic-subheading-item');
        if ($next.length > 0) {
            $item.insertAfter($next);
            reindexNavBuilder();
            updateHeadingOrderButtons();
            $item.css('background-color', '#edf7ed');
            setTimeout(function() { $item.css('background-color', ''); }, 400);
        }
    });

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
                updateHeadingLimit();
                updateHeadingOrderButtons();
            });
        }
    });

    // Delete Subheading
    $(document).on('click', '.cic-delete-subheading-btn', function(e) {
        e.preventDefault();
        var $item = $(this).closest('.cic-subheading-item');
        var $list = $item.closest('.cic-subheadings-list');
        $item.fadeOut(200, function() {
            $item.remove();
            reindexNavBuilder();
            updateHeadingOrderButtons();
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

    // Add Heading (Max 8)
    $(document).on('click', '.cic-add-heading-btn', function(e) {
        e.preventDefault();
        var $list = $('#cic-nav-builder .cic-nav-headings-list');
        var count = $list.children('.cic-heading-item').length;
        if (count >= 8) {
            alert('You cannot add more than 8 buttons in the navbar.');
            return;
        }
        var hIdx = count;
        var headingHtml = `
            <div class="cic-heading-item cic-tree-card" data-heading-idx="${hIdx}">
                <div class="cic-tree-card-header cic-heading-header" style="cursor: grab;">
                    <span class="cic-tree-badge badge-heading">HEADING ${hIdx + 1}</span>
                    <strong class="cic-heading-label-preview">New Heading</strong>
                    <div class="cic-tree-actions">
                        <button type="button" class="button button-small cic-move-heading-up-btn" title="Move Up"><span class="dashicons dashicons-arrow-up-alt2"></span> Up</button>
                        <button type="button" class="button button-small cic-move-heading-down-btn" title="Move Down"><span class="dashicons dashicons-arrow-down-alt2"></span> Down</button>
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
        updateHeadingLimit();
        updateHeadingOrderButtons();
        initNavSortables();
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
                <div class="cic-tree-card-header cic-subheading-header" style="cursor: grab;">
                    <span class="cic-tree-badge badge-subheading">SUBHEADING</span>
                    <strong class="cic-subheading-label-preview">New Subheading</strong>
                    <div class="cic-tree-actions">
                        <button type="button" class="button button-small cic-move-sub-up-btn" title="Move Up"><span class="dashicons dashicons-arrow-up-alt2"></span></button>
                        <button type="button" class="button button-small cic-move-sub-down-btn" title="Move Down"><span class="dashicons dashicons-arrow-down-alt2"></span></button>
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
        updateHeadingOrderButtons();
        initNavSortables();
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

    // Initialize Nav Builder limits, order buttons, and sortables
    if ($('#cic-nav-builder').length) {
        updateHeadingLimit();
        updateHeadingOrderButtons();
        initNavSortables();
    }
});

