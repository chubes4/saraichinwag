/**
 * Pinterest integration script
 * Loads Pinterest's pinit.js with proper attributes
 */
(function() {
    const script = document.createElement('script');
    script.type = 'text/javascript';
    script.async = true;
    script.defer = true;
    script.src = 'https://assets.pinterest.com/js/pinit.js';
    script.setAttribute('data-pin-hover', 'true');
    script.setAttribute('data-pin-tall', 'true');
    
    document.head.appendChild(script);
})();