document.addEventListener('DOMContentLoaded', function() {
    var header = document.querySelector('#masthead');
    var adminBar = document.getElementById('wpadminbar');
    var searchIcon = document.querySelector('.search-toggle');
    var searchForm = document.querySelector('.header-search');

    function adjustSearchPosition() {
        var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        var headerHeight = header.offsetHeight;
        var adminBarHeight = (adminBar && scrollTop === 0) ? adminBar.offsetHeight : 0;

        searchForm.style.top = (headerHeight + adminBarHeight) + 'px';
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

    if (searchIcon) {
        searchIcon.addEventListener('click', function(event) {
            event.preventDefault();
            toggleSearch();
        });
    }

    document.addEventListener('click', function(event) {
        if (!header.contains(event.target)) {
            if (searchForm.style.display === 'block') {
                searchForm.style.display = 'none';
                searchForm.classList.remove('active');
            }
        }
    });
});
