document.addEventListener('DOMContentLoaded', function() {
    var header = document.querySelector('#masthead');
    var adminBar = document.getElementById('wpadminbar');
    var searchForm = document.querySelector('.header-search');

    function adjustSearchPosition() {
        var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        var headerHeight = header.offsetHeight;
        var adminBarHeight = (adminBar && scrollTop === 0) ? adminBar.offsetHeight : 0;

        // Adjust the position by reducing 1px
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

    // Use event delegation for .search-toggle clicks
    document.addEventListener('click', function(event) {
        if (event.target.closest('.search-toggle')) {
            event.preventDefault();
            toggleSearch();
        }
    });

    // Close the search form if clicking outside of it, but not when clicking on .search-toggle or inside the search form
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
