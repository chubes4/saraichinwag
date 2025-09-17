/**
 * Header search functionality with admin bar position adjustment
 *
 * Handles search toggle positioning relative to header and WordPress admin bar.
 * Includes click-outside-to-close behavior and responsive positioning.
 *
 * @version 2.2
 * @since 1.0.0
 */
document.addEventListener('DOMContentLoaded', function() {
    var header = document.querySelector('#masthead');
    var adminBar = document.getElementById('wpadminbar');
    var searchForm = document.querySelector('.header-search');

    function adjustSearchPosition() {
        var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        var headerHeight = header.offsetHeight;
        var adminBarHeight = (adminBar && scrollTop === 0) ? adminBar.offsetHeight : 0;

        searchForm.style.top = (headerHeight + adminBarHeight - 1) + 'px';
    }

    function toggleSearch() {
        searchForm.classList.toggle('active');
        searchForm.style.display = searchForm.style.display === 'block' ? 'none' : 'block';
        if (searchForm.classList.contains('active')) {
            adjustSearchPosition();
        }
    }

    adjustSearchPosition();
    window.addEventListener('resize', adjustSearchPosition);
    window.addEventListener('scroll', function() {
        if (searchForm.classList.contains('active')) {
            adjustSearchPosition();
        }
    });

    document.addEventListener('click', function(event) {
        if (event.target.closest('.search-toggle')) {
            event.preventDefault();
            toggleSearch();
        }
    });

    document.addEventListener('click', function(event) {
        if (
            !header.contains(event.target) &&
            !searchForm.contains(event.target) &&
            !event.target.closest('.search-toggle')
        ) {
            if (searchForm.style.display === 'block') {
                searchForm.style.display = 'none';
                searchForm.classList.remove('active');
            }
        }
    });
});
