# Sarai Chinwag WordPress Theme

A versatile WordPress theme with advanced filtering, randomized content discovery, and full-width layout optimization. Features anti-chronological design that encourages serendipitous browsing through intelligent content presentation. See it in action at [saraichinwag.com](https://saraichinwag.com).

## Features

### Advanced Filter System
- **Multiple Sort Options**: Random (default), Most Popular (by view count), Most Recent, Oldest
- **Content Type Filtering**: Filter between Posts, Recipes, or All content seamlessly
- **AJAX-Powered**: Real-time filtering without page reloads, preserves state across Load More
- **Load More Integration**: Seamless infinite scroll that maintains filter state
- **Mobile Optimized**: Touch-friendly interface with collapsible design

### Full-Width Layout System
- **Maximized Content Discovery**: 4-column responsive grid on home and archive pages (vs traditional 2-3)
- **Strategic Sidebar Placement**: Removed from browse pages, maintained on single content for discovery widgets
- **Responsive Grid**: 4 columns (desktop) → 3 (laptop) → 2 (tablet) → 1 (mobile)
- **Performance Focused**: Simple view counter system tracks popularity without complex analytics

### Universal Theme Design
- **Recipe Site Mode**: Full recipe post type with ratings, schema markup, and specialized templates
- **Standard Blog Mode**: Clean blog functionality via admin toggle  
- **White Label Ready**: Customizable for any site type

### Enhanced Navigation
- **Badge-Breadcrumb System**: Clickable category/tag badges on single posts for intuitive navigation
- **Smart Breadcrumbs**: Context-aware hierarchical navigation on archive pages
- **Random Discovery**: Multiple random access points including `/random-all` for mixed content

### Randomization Focus
- **Anti-Chronological Design**: Randomization as default behavior breaks traditional blog patterns
- **Random Content Discovery**: Home and archive pages encourage serendipitous browsing
- **Multiple Random Endpoints**: `/random-post`, `/random-recipe`, and `/random-all` for instant discovery
- **Performance Optimized**: Cached random queries for fast randomization without database strain

### Dynamic Typography System
- **Google Fonts Integration**: Access to all Google Fonts via API
- **Smart Font Organization**: Display fonts for headings, sans-serif + serif for body text
- **Percentage-Based Scaling**: 1-100% size control with proportional heading hierarchy
- **Responsive Design**: All font sizes scale across breakpoints

### Performance & Security
- **Object Caching**: wp_cache_* functions with specialized cache groups for optimal performance
- **Dynamic Asset Versioning**: Automatic cache busting using `filemtime()`
- **Secure API Integration**: Proper sanitization and escaping throughout
- **Optimized Loading**: Only loads selected Google Fonts with `font-display: swap`
- **Complete Image Gallery System**: Advanced image extraction, gallery archives, and specialized display templates

### Admin Features
- **Theme Settings Panel**: Configure API keys and toggle functionality
- **WordPress Customizer**: Live preview font and size changes
- **Recipe Toggle**: Completely disable recipe features for universal use

## Installation

1. Upload theme files to `/wp-content/themes/saraichinwag/`
2. Activate the theme in WordPress admin
3. Go to **Settings → Theme Settings** to configure Google Fonts API key
4. Customize fonts and sizing via **Appearance → Customize → Typography**

## Configuration

### Google Fonts API Key
1. Get your API key from [Google Fonts Developer API](https://developers.google.com/fonts/docs/developer_api)
2. Add it in **Settings → Theme Settings → Google Fonts API Key**
3. All Google Fonts will then be available in the customizer

### Universal Theme Usage
Toggle recipe functionality in **Settings → Theme Settings**:
- **Recipes Enabled**: Full recipe site with ratings and schema
- **Recipes Disabled**: Clean blog theme for any content type

## Development

### File Structure
```
saraichinwag/
├── inc/                          # PHP module directory (organized functionality)
├── js/                           # JavaScript files
│   ├── filter-bar.js            # Advanced filter system frontend
│   ├── load-more.js             # AJAX Load More functionality
│   ├── gallery-utils.js         # Image gallery and lightbox functionality
│   ├── pinterest.js             # Pinterest save button integration
│   ├── customizer.js            # Live preview functionality
│   ├── rating.js                # Recipe rating interactions
│   └── nav.js                   # Navigation enhancements
├── template-parts/              # Reusable template components
│   ├── content-recipe.php       # Recipe display templates
│   ├── content-single.php       # Single post template
│   ├── content.php              # Standard post template
│   ├── content-image-gallery.php # Image gallery post template
│   ├── filter-bar.php           # Filter interface component
│   └── gallery-item.php         # Individual gallery item
└── fonts/                       # Local theme fonts
```

### Coding Standards
- WordPress coding standards throughout
- All output properly escaped with `esc_html()`, `esc_url()`, etc.
- Input sanitization with WordPress functions
- Uses `wp_remote_get()` instead of cURL
- Object caching with wp_cache_* functions for expensive operations

## Customization

### Font System
- **Default**: 50% = current theme appearance
- **Scaling**: 100% = 2x larger, 1% = minimal
- **Fallbacks**: Gluten (theme font) → System fonts
- **Categories**: Display (headings) + Sans-serif/Serif (body)

### CSS Custom Properties
```css
:root {
  --font-heading: 'Font Name', fallbacks;
  --font-body: 'Font Name', fallbacks;
  --font-heading-scale: 1.0;
  --font-body-scale: 1.0;
}
```

## Live Demo

See the theme in action at [saraichinwag.com](https://saraichinwag.com).

## Support

For technical support, please create an issue in this repository or contact the developer at [chubes.net](https://chubes.net).

## License

This theme is designed for personal use and white-labeling. Commercial distribution requires permission.

---

**Version**: 2.2  
**Author**: Chris Huber  
**Website**: [chubes.net](https://chubes.net)  
**Theme URI**: [saraichinwag.com](https://saraichinwag.com)

## Changelog

### v2.2 - WordPress Editor Integration & Complete Image Gallery System
- **NEW**: WordPress Editor Font Integration - consistent fonts between editors and frontend
- **NEW**: Complete Image Gallery System - advanced image extraction, gallery archives, and specialized display templates
- **NEW**: Image Search & Discovery - comprehensive image search with category/tag-based filtering  
- **NEW**: Load More Integration - AJAX infinite scroll that preserves filter state
- **NEW**: Enhanced Pinterest Integration - improved social functionality with js/pinterest.js
- **IMPROVED**: Footer Architecture Simplification - removed category/tag clouds for better UX/SEO
- **IMPROVED**: Object Caching Evolution - wp_cache_* functions with specialized cache groups including images
- **PERFORMANCE**: Enhanced caching strategies with proper cache group segregation

### v2.1 - Advanced Filter & Layout System
- **NEW**: Advanced filter bar with multiple sort options (Random, Popular, Recent, Oldest)
- **NEW**: Full-width 4-column responsive grid layout for maximum content discovery
- **NEW**: Simple view counter system for popularity tracking
- **NEW**: Badge-breadcrumb navigation system for enhanced UX
- **NEW**: Pinterest follow button relocated to footer
- **NEW**: Random discovery section replaces complex related posts logic
- **IMPROVED**: Mobile-optimized responsive design with touch-friendly controls
- **IMPROVED**: AJAX filtering with seamless Load More integration
- **PERFORMANCE**: Optimized queries and smart caching for all new features
