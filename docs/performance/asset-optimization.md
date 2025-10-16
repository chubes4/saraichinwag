# Asset Optimization

The Sarai Chinwag theme implements comprehensive asset optimization strategies including dynamic versioning, efficient loading, and performance-optimized file serving.

## Dynamic Asset Versioning

### Cache Busting System

**Implementation**: Uses `filemtime()` for automatic cache invalidation
**Benefit**: Ensures users get updated assets immediately
**Performance**: Prevents stale asset caching issues
**Automation**: No manual version management required

#### CSS Versioning
```php
$style_version = filemtime(get_template_directory() . '/style.css');
wp_enqueue_style('sarai-chinwag-style', get_stylesheet_uri(), array(), $style_version);
```

#### JavaScript Versioning
```php
$nav_version = filemtime(get_template_directory() . '/inc/assets/js/nav.js');
wp_enqueue_script('sarai-chinwag-nav',
    get_template_directory_uri() . '/inc/assets/js/nav.js',
    array(), $nav_version, true);
```

### Version Management Benefits

**Automatic Updates**: Asset versions update automatically on file changes
**Browser Caching**: Efficient browser caching with automatic invalidation
**CDN Compatibility**: Works with CDN caching strategies
**Development Friendly**: No manual version bumping during development

## Centralized Asset Management System

### Asset Management Architecture

**Centralized Control**: All asset loading managed via `inc/core/assets.php`
**Conditional Loading**: CSS and JavaScript files load only when needed
**Dependency Management**: Proper dependency chains for all assets
**Dynamic Versioning**: Automatic cache busting using `filemtime()`

**Core Functions**:
- `sarai_chinwag_enqueue_styles()` - Frontend CSS loading
- `sarai_chinwag_enqueue_scripts()` - Frontend JavaScript loading
- `sarai_chinwag_enqueue_admin_styles()` - Admin-only CSS
- `sarai_chinwag_enqueue_editor_styles()` - Editor-specific styles

## CSS Optimization

### Modular CSS Architecture

**Root CSS System**: Centralized CSS custom properties
**File Structure**:
- `/style.css` - Main theme stylesheet
- `/inc/assets/css/root.css` - CSS custom properties and variables (loaded first)
- `/inc/assets/css/customizer.css` - Live preview styling (admin only)
- `/inc/assets/css/editor.css` - WordPress editor integration
- `/inc/assets/css/recipes.css` - Recipe-specific styles (conditional)
- `/inc/assets/css/single.css` - Single post/page styles (conditional)
- `/inc/assets/css/archive.css` - Archive-specific styles (conditional)
- `/inc/assets/css/image-mode.css` - Image gallery styles (conditional)
- `/inc/assets/css/sidebar.css` - Sidebar styles (conditional)
- `/inc/assets/css/contact.css` - Contact form styles (conditional)

### CSS Loading Strategy

**Dependency Management**: Proper CSS dependency chains
```php
// Root CSS loaded first with custom properties
wp_enqueue_style('sarai-chinwag-root-css',
    $theme_uri . '/inc/assets/css/root.css', array(), $root_version);

// Main stylesheet depends on root CSS
wp_enqueue_style('sarai-chinwag-style', get_stylesheet_uri(),
    array('sarai-chinwag-root-css'), $style_version);
```

**Conditional Loading Examples**:
```php
// Recipe styles only on recipe pages
if (!sarai_chinwag_recipes_disabled() && is_singular('recipe')) {
    wp_enqueue_style('sarai-chinwag-recipes',
        $theme_uri . '/inc/assets/css/recipes.css',
        array('sarai-chinwag-root-css'), $recipes_version);
}

// Contact form styles only when shortcode is present
if (sarai_chinwag_has_contact_form()) {
    wp_enqueue_style('sarai-chinwag-contact',
        $theme_uri . '/inc/assets/css/contact.css',
        array('sarai-chinwag-root-css'), $contact_version);
}
```

**Load Order Optimization**:
1. Root CSS with custom properties (highest priority, always loaded)
2. Main theme stylesheet (always loaded, depends on root)
3. Context-specific CSS (conditional based on page type)

### CSS Performance Features

**Custom Properties**: Efficient CSS variable system
**Minimal Inline CSS**: No inline styles in HTML
**File-Based Approach**: All styling in external files
**Caching Friendly**: Static CSS files cache efficiently

## JavaScript Optimization

### Modular JavaScript Architecture

**Centralized JavaScript** (loaded via `inc/core/assets.php`):
- `/inc/assets/js/nav.js` - Header navigation functionality
- `/inc/assets/js/gallery-utils.js` - Image gallery utilities
- `/inc/assets/js/pinterest.js` - Pinterest integration

**Root-Level JavaScript** (specialized functionality):
- `/js/filter-bar.js` - Advanced AJAX filtering system
- `/js/load-more.js` - Infinite scroll functionality
- `/js/rating.js` - Recipe rating system
- `/js/customizer.js` - Live preview functionality
- `/js/contact-form.js` - Contact form handling

### JavaScript Loading Strategy

**Centralized Loading**: Core scripts loaded via `sarai_chinwag_enqueue_scripts()`
```php
// Navigation script with dynamic versioning
$nav_version = filemtime($theme_dir . '/inc/assets/js/nav.js');
wp_enqueue_script('sarai-chinwag-nav',
    $theme_uri . '/inc/assets/js/nav.js',
    array(), $nav_version, true);
```

**Footer Loading**: All scripts loaded in footer for optimal performance
**Dependency Management**: Proper script dependencies defined
**Conditional Loading**: Specialized scripts loaded only when needed
**No Inline JavaScript**: All JavaScript in external files

### Performance JavaScript Features

**Event Delegation**: Efficient event handling
**Debounced Handlers**: Optimized resize and scroll handlers
**Memory Management**: Proper cleanup and garbage collection
**AJAX Optimization**: Efficient AJAX request handling

## Image Optimization

### Custom Image Sizes

**Optimized Grid Size**: Single `grid-thumb` size (450x450px)
**Removed Sizes**: Eliminated unnecessary WordPress defaults
- Removed: `thumbnail` (150px)
- Removed: `medium` (300px)  
- Removed: `medium_large` (768px)

### Image Size Benefits

**Storage Efficiency**: 75% reduction in generated image files
**Bandwidth Optimization**: Serves appropriately sized images
**Mobile Optimization**: 450px size optimal for mobile viewports
**Performance**: Single size reduces server storage and processing

### Image Loading Strategy

**Lazy Loading**: Images below fold load lazily
**Priority Loading**: First 12 images load eagerly
**Responsive Images**: Proper width/height attributes
**Progressive Enhancement**: Images enhance progressively

## Google Fonts Optimization

### Font Loading Strategy

**Display Swap**: `font-display: swap` prevents invisible text
**Weight Optimization**: Only necessary weights loaded (400, 500, 600, 700)
**Preconnect**: Preconnect to Google Fonts domain
**Fallback Fonts**: Immediate rendering with system fonts

### Font Caching Strategy

**API Response Caching**: 24-hour cache for font listings
**Browser Caching**: Fonts cached by browser automatically
**Selective Loading**: Only selected fonts loaded
**Efficient URLs**: Optimized Google Fonts API v2 URLs

```
https://fonts.googleapis.com/css2?family=FontName:wght@400;500;600;700&display=swap
```

## Asset Delivery Optimization

### File Compression

**Server Compression**: Works with Gzip/Brotli compression
**Minification Ready**: Asset structure supports minification
**CDN Optimization**: Compatible with CDN delivery
**HTTP/2 Optimized**: Efficient with HTTP/2 multiplexing

### Resource Hints

**DNS Prefetch**: Optimized DNS resolution for external resources
**Preconnect**: Connection pre-establishment for critical resources
**Resource Priorities**: Proper resource loading priorities
**Critical Path**: Optimized critical rendering path

## Performance Monitoring

### Asset Performance Metrics

**Load Times**: Optimized asset loading times
**Cache Hit Rates**: Efficient browser and CDN caching
**File Sizes**: Minimized asset file sizes
**Network Requests**: Reduced number of network requests

### Core Web Vitals Impact

**Largest Contentful Paint (LCP)**: Optimized image loading
**First Input Delay (FID)**: Efficient JavaScript execution
**Cumulative Layout Shift (CLS)**: Stable layouts with proper sizing
**Time to First Byte (TTFB)**: Optimized server response

## Development Optimization

### Development Workflow

**File Watching**: Assets update automatically during development
**Browser Caching**: Proper cache invalidation during development
**Debug Mode**: Asset optimization works in debug mode
**Live Reload**: Compatible with live reload systems

### Build Process Compatibility

**Source Files**: Clean source file structure
**Build Ready**: Compatible with build processes
**Version Control**: Asset versioning works with Git
**Deployment**: Optimized for production deployment

## Mobile Optimization

### Mobile Asset Strategy

**Reduced Payloads**: Smaller asset sizes for mobile
**Touch Interactions**: Touch-optimized JavaScript
**Viewport Optimization**: Assets optimized for mobile viewports
**Connection Awareness**: Efficient on slower connections

### Progressive Enhancement

**Basic Functionality**: Works without JavaScript
**Enhanced Features**: JavaScript enhances functionality
**Graceful Degradation**: Fallbacks for failed asset loading
**Accessibility**: Asset loading maintains accessibility

## Troubleshooting

### Asset Loading Issues

**Version Issues**:
1. Check file modification times
2. Verify asset file permissions
3. Test with browser hard refresh
4. Check server file access

**Performance Issues**:
1. Monitor network requests
2. Check asset file sizes
3. Verify compression settings
4. Test with different browsers

### CSS/JavaScript Problems

**Loading Order Issues**:
1. Check dependency declarations
2. Verify enqueue priorities
3. Test with different themes
4. Check for plugin conflicts

**Caching Issues**:
1. Clear browser cache
2. Check CDN cache settings
3. Verify version strings
4. Test with incognito mode

## Advanced Optimization

### Custom Build Integration

The asset system can be integrated with custom build processes while maintaining the dynamic versioning benefits.

### CDN Integration

Assets are optimized for CDN delivery with proper versioning and caching headers.

### Performance Budgets

Asset optimization supports performance budgets and can be monitored with performance tools for continuous optimization.