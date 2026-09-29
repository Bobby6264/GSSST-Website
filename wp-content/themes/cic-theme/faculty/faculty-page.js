/**
 * Faculty Directory Interactive Search & Filter Script
 */
(function($) {
    'use strict';

    $(document).ready(function() {
        var $searchInput = $('#facultySearchInput');
        var $clearBtn    = $('#facultySearchClear');
        var $filterTabs  = $('.faculty-filter-tabs .filter-tab');
        var $cards       = $('.faculty-card');
        var $noResults   = $('#facultyNoResults');
        var $grid        = $('#facultyCardsGrid');

        var currentRoleFilter = 'all';
        var currentSearchTerm = '';

        function filterFaculty() {
            var visibleCount = 0;

            $cards.each(function() {
                var $card = $(this);
                var role = $card.data('role') || '';
                var searchData = ($card.data('search') || '').toString().toLowerCase();

                // 1. Role filter match
                var matchesRole = (currentRoleFilter === 'all') || (role === currentRoleFilter);

                // 2. Search term match
                var matchesSearch = true;
                if (currentSearchTerm.length > 0) {
                    matchesSearch = searchData.indexOf(currentSearchTerm) !== -1;
                }

                if (matchesRole && matchesSearch) {
                    $card.show();
                    visibleCount++;
                } else {
                    $card.hide();
                }
            });

            if (visibleCount === 0) {
                $noResults.show();
            } else {
                $noResults.hide();
            }
        }

        // Search Input Event
        $searchInput.on('input', function() {
            currentSearchTerm = $.trim($(this).val().toLowerCase());
            if (currentSearchTerm.length > 0) {
                $clearBtn.show();
            } else {
                $clearBtn.hide();
            }
            filterFaculty();
        });

        // Clear Search Button
        $clearBtn.on('click', function() {
            $searchInput.val('').trigger('input').focus();
        });

        // Filter Tabs Click
        $filterTabs.on('click', function() {
            $filterTabs.removeClass('active');
            $(this).addClass('active');
            currentRoleFilter = $(this).data('filter') || 'all';
            filterFaculty();
        });

        // Reset Filters Button
        $('#facultyResetFiltersBtn').on('click', function() {
            $searchInput.val('');
            currentSearchTerm = '';
            $clearBtn.hide();
            $filterTabs.removeClass('active').filter('[data-filter="all"]').addClass('active');
            currentRoleFilter = 'all';
            filterFaculty();
        });
    });
})(jQuery);
